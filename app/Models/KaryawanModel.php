<?php

namespace App\Models;

use CodeIgniter\Model;

class KaryawanModel extends Model
{
    protected $table            = 'karyawan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'nip',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_telp',
        'foto',
        'jabatan_id',
        'tanggal_masuk',
        'status_nikah',
        'jumlah_anak'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Model Validation Rules disabled to prevent silent update failures on Model::update()
    protected $validationRules = [];

    public function getKaryawanWithJabatan($id = null)
    {
        $builder = $this->db->table('karyawan k')
            ->select('k.*, j.nama_jabatan, j.gaji_pokok, j.tunj_jabatan, j.tunj_makan_per_hari, j.tunj_transport_per_hari, u.username, u.email, u.role')
            ->join('jabatan j', 'j.id = k.jabatan_id', 'left')
            ->join('users u', 'u.id = k.user_id', 'left');

        if ($id !== null) {
            return $builder->where('k.id', $id)->get()->getRowArray();
        }

        return $builder->orderBy('k.nama', 'ASC')->get()->getResultArray();
    }

    public function getKaryawanByUserId($userId)
    {
        return $this->db->table('karyawan k')
            ->select('k.*, j.nama_jabatan, j.gaji_pokok, j.tunj_jabatan, j.tunj_makan_per_hari, j.tunj_transport_per_hari, u.username, u.email')
            ->join('jabatan j', 'j.id = k.jabatan_id', 'left')
            ->join('users u', 'u.id = k.user_id', 'left')
            ->where('k.user_id', $userId)
            ->get()
            ->getRowArray();
    }
}
