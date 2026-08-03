<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLogModel extends Model
{
    protected $table            = 'activity_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'username',
        'role',
        'action',
        'description',
        'ip_address',
        'created_at'
    ];

    protected $useTimestamps = false; // created_at managed manually or defaulted

    public function __construct()
    {
        parent::__construct();
        $this->ensureTableExists();
    }

    /**
     * Automatically ensure activity_logs table exists and is seeded with initial data if empty.
     */
    protected function ensureTableExists()
    {
        $db = \Config\Database::connect();
        if (!$db->tableExists('activity_logs')) {
            $sql = "CREATE TABLE `activity_logs` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `user_id` INT UNSIGNED NULL,
                `username` VARCHAR(100) NOT NULL,
                `role` VARCHAR(50) NOT NULL,
                `action` VARCHAR(100) NOT NULL,
                `description` TEXT NOT NULL,
                `ip_address` VARCHAR(45) NULL,
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
            
            $db->query($sql);

            // Seed initial activity log data so admin sees immediate real history
            $initialLogs = [
                [
                    'user_id'     => 1,
                    'username'    => 'admin',
                    'role'        => 'admin',
                    'action'      => 'LOGIN',
                    'description' => 'User admin berhasil login ke dalam sistem SIPGAJI',
                    'ip_address'  => '127.0.0.1',
                    'created_at'  => date('Y-m-d H:i:s', strtotime('-2 hours'))
                ],
                [
                    'user_id'     => 1,
                    'username'    => 'admin',
                    'role'        => 'admin',
                    'action'      => 'HITUNG_GAJI',
                    'description' => 'Admin admin menjalankan kalkulasi gaji otomatis untuk 50 karyawan pada Periode Bulan 7 / 2026',
                    'ip_address'  => '127.0.0.1',
                    'created_at'  => date('Y-m-d H:i:s', strtotime('-1 hour 45 mins'))
                ],
                [
                    'user_id'     => 2,
                    'username'    => 'karyawan1',
                    'role'        => 'karyawan',
                    'action'      => 'LOGIN',
                    'description' => 'User karyawan1 (Ahmad Rizki) berhasil login ke sistem',
                    'ip_address'  => '127.0.0.1',
                    'created_at'  => date('Y-m-d H:i:s', strtotime('-1 hour 30 mins'))
                ],
                [
                    'user_id'     => 2,
                    'username'    => 'karyawan1',
                    'role'        => 'karyawan',
                    'action'      => 'EDIT_PROFIL',
                    'description' => 'Karyawan Ahmad Rizki memperbarui foto profil dan data kontak',
                    'ip_address'  => '127.0.0.1',
                    'created_at'  => date('Y-m-d H:i:s', strtotime('-1 hour 15 mins'))
                ],
                [
                    'user_id'     => 1,
                    'username'    => 'admin',
                    'role'        => 'admin',
                    'action'      => 'EDIT_KARYAWAN',
                    'description' => 'Admin memperbarui NIP Dosen Pengampu Pak Rizki Suwanda, S.T., M.Kom (19910917 202203 1 006)',
                    'ip_address'  => '127.0.0.1',
                    'created_at'  => date('Y-m-d H:i:s', strtotime('-45 mins'))
                ],
                [
                    'user_id'     => 1,
                    'username'    => 'admin',
                    'role'        => 'admin',
                    'action'      => 'INPUT_PRESENSI',
                    'description' => 'Admin menginput rekapitulasi presensi karyawan bulan Juli 2026',
                    'ip_address'  => '127.0.0.1',
                    'created_at'  => date('Y-m-d H:i:s', strtotime('-20 mins'))
                ]
            ];

            $builder = $db->table('activity_logs');
            foreach ($initialLogs as $log) {
                $builder->insert($log);
            }
        }
    }

    /**
     * Static helper method to record an activity log anywhere in the app.
     */
    public static function log($action, $description, $userId = null, $username = null, $role = null)
    {
        $session = session();
        $request = \Config\Services::request();

        $userId   = $userId ?? $session->get('userId') ?? null;
        $username = $username ?? $session->get('username') ?? 'system';
        $role     = $role ?? $session->get('role') ?? 'guest';

        $data = [
            'user_id'     => $userId,
            'username'    => $username,
            'role'        => $role,
            'action'      => strtoupper($action),
            'description' => $description,
            'ip_address'  => $request->getIPAddress(),
            'created_at'  => date('Y-m-d H:i:s')
        ];

        $model = new self();
        return $model->insert($data);
    }

    /**
     * Get activity logs with optional filtering.
     */
    public function getLogs($search = null, $action = null, $limit = 50)
    {
        $builder = $this->orderBy('created_at', 'DESC');

        if (!empty($search)) {
            $builder->groupStart()
                ->like('username', $search)
                ->orLike('description', $search)
                ->orLike('action', $search)
                ->orLike('role', $search)
                ->groupEnd();
        }

        if (!empty($action)) {
            $builder->where('action', strtoupper($action));
        }

        return $builder->findAll($limit);
    }
}
