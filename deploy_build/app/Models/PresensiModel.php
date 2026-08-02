<?php

namespace App\Models;

use CodeIgniter\Model;

class PresensiModel extends Model
{
    protected $table            = 'presensi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'karyawan_id',
        'bulan',
        'tahun',
        'jumlah_hadir',
        'jumlah_sakit',
        'jumlah_izin',
        'jumlah_alpa',
        'jumlah_lembur_jam'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Model validation disabled to prevent silent update failures
    protected $validationRules = [];

    public function getPresensiWithKaryawan($bulan = null, $tahun = null, $karyawanId = null)
    {
        $builder = $this->db->table('presensi p')
            ->select('p.*, k.nip, k.nama, j.nama_jabatan')
            ->join('karyawan k', 'k.id = p.karyawan_id', 'inner')
            ->join('jabatan j', 'j.id = k.jabatan_id', 'left');

        if ($bulan !== null) {
            $builder->where('p.bulan', $bulan);
        }
        if ($tahun !== null) {
            $builder->where('p.tahun', $tahun);
        }
        if ($karyawanId !== null) {
            $builder->where('p.karyawan_id', $karyawanId);
        }

        return $builder->orderBy('k.nama', 'ASC')->get()->getResultArray();
    }
}
