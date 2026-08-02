<?php

namespace App\Models;

use CodeIgniter\Model;

class PenggajianModel extends Model
{
    protected $table            = 'penggajian';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kode_transaksi',
        'karyawan_id',
        'bulan',
        'tahun',
        'gaji_pokok',
        'tunj_jabatan',
        'tunj_kehadiran',
        'tunj_keluarga',
        'bonus_lembur',
        'total_pendapatan',
        'pot_bpjs_ks',
        'pot_bpjs_tk',
        'pot_pph21',
        'pot_absensi',
        'total_potongan',
        'gaji_bersih',
        'foto_bukti_transfer',
        'tanggal_dibayar',
        'status_bayar'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getPenggajianFull($bulan = null, $tahun = null, $karyawanId = null, $id = null)
    {
        $builder = $this->db->table('penggajian g')
            ->select('g.*, k.nip, k.nama, k.status_nikah, k.jumlah_anak, k.alamat, k.no_telp, j.nama_jabatan, u.email')
            ->join('karyawan k', 'k.id = g.karyawan_id', 'inner')
            ->join('jabatan j', 'j.id = k.jabatan_id', 'left')
            ->join('users u', 'u.id = k.user_id', 'left');

        if ($id !== null) {
            return $builder->where('g.id', $id)->get()->getRowArray();
        }
        if ($bulan !== null) {
            $builder->where('g.bulan', $bulan);
        }
        if ($tahun !== null) {
            $builder->where('g.tahun', $tahun);
        }
        if ($karyawanId !== null) {
            $builder->where('g.karyawan_id', $karyawanId);
        }

        return $builder->orderBy('g.id', 'DESC')->get()->getResultArray();
    }

    public function getMonthlyPayrollSummary()
    {
        return $this->db->table('penggajian')
            ->select('bulan, tahun, SUM(gaji_bersih) as total_gaji_bersih, COUNT(id) as total_karyawan')
            ->groupBy('tahun, bulan')
            ->orderBy('tahun', 'ASC')
            ->orderBy('bulan', 'ASC')
            ->get()
            ->getResultArray();
    }
}
