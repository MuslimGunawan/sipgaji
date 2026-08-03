<?php

namespace App\Controllers;

use App\Models\PenggajianModel;
use App\Models\KaryawanModel;
use App\Models\PresensiModel;
use App\Models\JabatanModel;

class Penggajian extends BaseController
{
    protected $penggajianModel;
    protected $karyawanModel;
    protected $presensiModel;
    protected $jabatanModel;

    public function __construct()
    {
        $this->penggajianModel = new PenggajianModel();
        $this->karyawanModel   = new KaryawanModel();
        $this->presensiModel   = new PresensiModel();
        $this->jabatanModel    = new JabatanModel();
    }

    public function index()
    {
        $bulan = (int)($this->request->getGet('bulan') ?: 7);
        $tahun = (int)($this->request->getGet('tahun') ?: 2026);

        $role = session()->get('role');
        $karyawanId = session()->get('karyawanId');

        if ($role === 'karyawan') {
            $penggajianList = $this->penggajianModel->getPenggajianFull($bulan, $tahun, $karyawanId);
        } else {
            $penggajianList = $this->penggajianModel->getPenggajianFull($bulan, $tahun);
        }

        $data = [
            'title'          => 'Kalkulasi & Daftar Penggajian',
            'bulan'          => $bulan,
            'tahun'          => $tahun,
            'penggajianList' => $penggajianList,
        ];

        return view('penggajian/index', $data);
    }

    public function hitungOtomatis()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard');
        }

        $bulan = (int)$this->request->getPost('bulan');
        $tahun = (int)$this->request->getPost('tahun');

        if (! $bulan || ! $tahun) {
            return redirect()->back()->with('error', 'Bulan dan Tahun wajib dipilih.');
        }

        $karyawanAll = $this->karyawanModel->getKaryawanWithJabatan();
        $countProcessed = 0;

        foreach ($karyawanAll as $k) {
            $karyawanId = $k['id'];
            $gajiPokok   = (float)($k['gaji_pokok'] ?? 0);
            $tunjJabatan = (float)($k['tunj_jabatan'] ?? 0);
            $rateMakan   = (float)($k['tunj_makan_per_hari'] ?? 0);
            $rateTrans   = (float)($k['tunj_transport_per_hari'] ?? 0);

            // Fetch attendance data for this month & year
            $presensi = $this->presensiModel->where('karyawan_id', $karyawanId)
                                            ->where('bulan', $bulan)
                                            ->where('tahun', $tahun)
                                            ->first();

            $hadir  = $presensi ? (int)$presensi['jumlah_hadir'] : 0;
            $sakit  = $presensi ? (int)$presensi['jumlah_sakit'] : 0;
            $izin   = $presensi ? (int)$presensi['jumlah_izin'] : 0;
            $alpa   = $presensi ? (int)$presensi['jumlah_alpa'] : 0;
            $lembur = $presensi ? (int)$presensi['jumlah_lembur_jam'] : 0;

            // 1. Tunjangan Kehadiran = (Hadir * Tunj. Makan/Hari) + (Hadir * Tunj. Transport/Hari)
            $tunjKehadiran = ($hadir * $rateMakan) + ($hadir * $rateTrans);

            // 2. Tunjangan Keluarga = Jika Menikah (10% Gaji Pokok) + (2% Gaji Pokok per anak, max 3 anak)
            $tunjKeluarga = 0;
            if (isset($k['status_nikah']) && $k['status_nikah'] === 'Menikah') {
                $tunjKeluarga += 0.10 * $gajiPokok;
                $jumlahAnak = min((int)($k['jumlah_anak'] ?? 0), 3);
                $tunjKeluarga += ($jumlahAnak * 0.02 * $gajiPokok);
            }

            // 3. Bonus Lembur = Lembur jam * (1/173 * Gaji Pokok)
            $rateLemburJam = (1 / 173) * $gajiPokok;
            $bonusLembur   = $lembur * $rateLemburJam;

            // Total Pendapatan Kotor (Gross Income)
            $totalPendapatan = $gajiPokok + $tunjJabatan + $tunjKehadiran + $tunjKeluarga + $bonusLembur;

            // Potongan mandatory
            // Potongan BPJS Kesehatan = 1% dari Total Pendapatan
            $potBpjsKs = 0.01 * $totalPendapatan;

            // Potongan BPJS Ketenagakerjaan = 2% dari Total Pendapatan
            $potBpjsTk = 0.02 * $totalPendapatan;

            // Potongan PPh 21 Estimasi Flat 5% (jika Gross > 5 Juta)
            $potPph21 = 0;
            if ($totalPendapatan > 5000000) {
                $potPph21 = 0.05 * ($totalPendapatan - 5000000);
            }

            // Potongan Absensi Alpa = Alpa * (1/26 * Gaji Pokok)
            $potAbsensi = $alpa * ((1 / 26) * $gajiPokok);

            $totalPotongan = $potBpjsKs + $potBpjsTk + $potPph21 + $potAbsensi;
            $gajiBersih    = max(0, $totalPendapatan - $totalPotongan);

            // Generate Kode Transaksi TRX-PAY-YYYYMM-XXX
            $kodeTransaksi = 'TRX-PAY-' . sprintf('%04d%02d', $tahun, $bulan) . '-' . sprintf('%03d', $karyawanId);

            // Check existing payroll record
            $existing = $this->penggajianModel->where('karyawan_id', $karyawanId)
                                               ->where('bulan', $bulan)
                                               ->where('tahun', $tahun)
                                               ->first();

            $payrollData = [
                'kode_transaksi'   => $kodeTransaksi,
                'karyawan_id'      => $karyawanId,
                'bulan'            => $bulan,
                'tahun'            => $tahun,
                'gaji_pokok'       => round($gajiPokok, 2),
                'tunj_jabatan'     => round($tunjJabatan, 2),
                'tunj_kehadiran'   => round($tunjKehadiran, 2),
                'tunj_keluarga'    => round($tunjKeluarga, 2),
                'bonus_lembur'     => round($bonusLembur, 2),
                'total_pendapatan' => round($totalPendapatan, 2),
                'pot_bpjs_ks'      => round($potBpjsKs, 2),
                'pot_bpjs_tk'      => round($potBpjsTk, 2),
                'pot_pph21'        => round($potPph21, 2),
                'pot_absensi'      => round($potAbsensi, 2),
                'total_potongan'   => round($totalPotongan, 2),
                'gaji_bersih'      => round($gajiBersih, 2),
                'status_bayar'     => 'Lunas',
                'tanggal_dibayar'  => date('Y-m-d H:i:s'),
            ];

            if ($existing) {
                $this->penggajianModel->update($existing['id'], $payrollData);
            } else {
                $this->penggajianModel->insert($payrollData);
            }

            $countProcessed++;
        }

        \App\Models\ActivityLogModel::log('HITUNG_GAJI', "Admin menjalankan perhitungan gaji otomatis untuk {$countProcessed} karyawan (Periode Bulan {$bulan} / {$tahun})");

        return redirect()->to('/penggajian?bulan=' . $bulan . '&tahun=' . $tahun)
            ->with('success', "Perhitungan Gaji Otomatis berhasil diproses untuk {$countProcessed} karyawan pada Bulan {$bulan} Tahun {$tahun}.");
    }

    public function uploadBukti($id = null)
    {
        $gaji = $this->penggajianModel->find($id);
        if (! $gaji) {
            return redirect()->to('/penggajian')->with('error', 'Data Penggajian tidak ditemukan.');
        }

        $rules = [
            'bukti' => 'uploaded[bukti]|mime_in[bukti,image/jpg,image/jpeg,image/png,application/pdf]|max_size[bukti,3072]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('error', 'File bukti transfer harus berupa gambar (JPG/PNG) atau PDF maksimal 3MB.');
        }

        $fileBukti = $this->request->getFile('bukti');
        if ($fileBukti->isValid() && ! $fileBukti->hasMoved()) {
            $namaBukti = $fileBukti->getRandomName();
            $fileBukti->move(FCPATH . 'uploads/bukti', $namaBukti);

            if ($gaji['foto_bukti_transfer'] && file_exists(FCPATH . 'uploads/bukti/' . $gaji['foto_bukti_transfer'])) {
                @unlink(FCPATH . 'uploads/bukti/' . $gaji['foto_bukti_transfer']);
            }

            $this->penggajianModel->update($id, [
                'foto_bukti_transfer' => $namaBukti,
                'status_bayar'        => 'Lunas',
                'tanggal_dibayar'     => date('Y-m-d H:i:s'),
            ]);

            $empNama = $gaji['nama'] ?? 'Karyawan';
            \App\Models\ActivityLogModel::log('UPLOAD_BUKTI_GAJI', "User " . session()->get('username') . " mengunggah bukti pembayaran gaji karyawan {$empNama} (TRX #{$gaji['kode_transaksi']})");
        }

        return redirect()->to('/penggajian')->with('success', 'Bukti transfer pembayaran gaji berhasil diunggah.');
    }

    public function slip($id = null)
    {
        $role = session()->get('role');
        $karyawanIdSession = session()->get('karyawanId');

        $gaji = $this->penggajianModel->getPenggajianFull(null, null, null, $id);
        if (! $gaji) {
            return redirect()->to('/penggajian')->with('error', 'Data Penggajian tidak ditemukan.');
        }

        if ($role === 'karyawan' && $gaji['karyawan_id'] != $karyawanIdSession) {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak berhak melihat slip gaji karyawan lain.');
        }

        $userSessionName = session()->get('namaLengkap') ?? session()->get('username');
        $roleName = $role === 'admin' ? 'Admin' : 'Karyawan';
        $namaPekerja = $gaji['nama'] ?? 'Karyawan';
        \App\Models\ActivityLogModel::log('LIHAT_SLIP_GAJI', "{$roleName} {$userSessionName} melihat/mencetak Slip Gaji karyawan {$namaPekerja} (Periode Bulan {$gaji['bulan']} / {$gaji['tahun']})");

        $presensi = $this->presensiModel->where('karyawan_id', $gaji['karyawan_id'])
                                        ->where('bulan', $gaji['bulan'])
                                        ->where('tahun', $gaji['tahun'])
                                        ->first();

        $data = [
            'title'    => 'Slip Gaji Karyawan',
            'gaji'     => $gaji,
            'presensi' => $presensi,
        ];

        return view('penggajian/slip', $data);
    }
}
