<?php

namespace App\Controllers;

use App\Models\ActivityLogModel;

class ActivityLog extends BaseController
{
    protected $activityLogModel;

    public function __construct()
    {
        $this->activityLogModel = new ActivityLogModel();
    }

    public function index()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak. Halaman Log Aktivitas hanya dapat diakses oleh Admin.');
        }

        $search = $this->request->getGet('search');
        $actionFilter = $this->request->getGet('action');

        $logs = $this->activityLogModel->getLogs($search, $actionFilter, 100);

        $data = [
            'title'        => 'Log Aktivitas Sistem',
            'logs'         => $logs,
            'search'       => $search,
            'actionFilter' => $actionFilter,
        ];

        return view('activity_logs/index', $data);
    }

    public function clear()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard');
        }

        $this->activityLogModel->truncate();
        
        ActivityLogModel::log('CLEAR_LOGS', 'Admin membersihkan seluruh histori log aktivitas sistem');

        return redirect()->to('/activity-logs')->with('success', 'Seluruh data histori log aktivitas telah berhasil dibersihkan.');
    }
}
