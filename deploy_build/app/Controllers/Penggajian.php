<?php

namespace App\Controllers;

use App\Models\PenggajianModel;
use App\Models\PresensiModel;
use App\Models\KaryawanModel;

class Penggajian extends BaseController
{
    protected $penggajianModel;
    protected $presensiModel;
    protected $karyawanModel;

    public function __construct()
    {
        $this->penggajianModel = new PenggajianModel();
        $this->presensiModel   = new PresensiModel();
        $this->karyawanModel   = new KaryawanModel();
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
        $bulan = (int)($this->request->getPost('bulan') ?: 7);
        $tahun = (int)($this->request->getPost('tahun') ?: 2026);

        // Get all presensi data for this month and year
        $presensiList = $this->presensiModel->getPresensiWithKaryawan($bulan, $tahun);

        if (empty($presensiList)) {
            return redirect()->back()->with('error', "Belum ada data presensi pada Bulan {$bulan} Tahun {$tahun}. Silakan input presensi terlebih dahulu.");
        }

        $countProcessed = 0;

        foreach ($presensiList as $p) {
            $karyawan = $this->karyawanModel->getKaryawanWithJabatan($p['karyawan_id']);
            if (! $karyawan) {
                continue;
            }

            $gajiPokok   = (float)$karyawan['gaji_pokok'];
            $tunjJabatan = (float)$karyawan['tunj_jabatan'];

            // Tunjangan Kehadiran = (Hadir * Makan/Hari) + (Hadir * Transport/Hari)
            $hadir = (int)$p['jumlah_hadir'];
            $tunjKehadiran = ($hadir * (float)$karyawan['tunj_makan_per_hari']) + ($hadir * (float)$karyawan['tunj_transport_per_hari']);

            // Tunjangan Keluarga = (10% jika Menikah) + (5% per Anak, Max 2)
            $tunjKeluarga = 0.00;
            if ($karyawan['status_nikah'] === 'Menikah') {
                $tunjKeluarga += 0.10 * $gajiPokok;
                $anakCount = min(2, (int)$karyawan['jumlah_anak']);
                $tunjKeluarga += (0.05 * $gajiPokok * $anakCount);
            }

            // Bonus Lembur = Jam Lembur * (1.5 * Gaji Pokok / 173)
            $lembur = (int)$p['jumlah_lembur_jam'];
            $bonusLembur = $lembur * (1.5 * ($gajiPokok / 173.0));

            // Total Pendapatan
            $totalPendapatan = $gajiPokok + $tunjJabatan + $tunjKehadiran + $tunjKeluarga + $bonusLembur;

            // Potongan BPJS Kesehatan (1%) & Ketenagakerjaan (2%)
            $potBpjsKs = 0.01 * $gajiPokok;
            $potBpjsTk = 0.02 * $gajiPokok;

            // Potongan PPh 21 (5% Progresif Gaji Pokok)
            $potPph21 = 0.05 * $gajiPokok;

            // Potongan Absensi = Alpa * (Gaji Pokok / 22)
            $alpa = (int)$p['jumlah_alpa'];
            $potAbsensi = $alpa * ($gajiPokok / 22.0);

            // Total Potongan
            $totalPotongan = $potBpjsKs + $potBpjsTk + $potPph21 + $potAbsensi;

            // Gaji Bersih
            $gajiBersih = $totalPendapatan - $totalPotongan;

            $kodeTrx = 'TRX-PAY-' . $tahun . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-' . str_pad($karyawan['id'], 3, '0', STR_PAD_LEFT);

            // Check if record exists
            $existing = $this->penggajianModel->where('karyawan_id', $karyawan['id'])
                                              ->where('bulan', $bulan)
                                              ->where('tahun', $tahun)
                                              ->first();

            $payrollData = [
                'kode_transaksi'   => $kodeTrx,
                'karyawan_id'      => $karyawan['id'],
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
