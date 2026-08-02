<?php

namespace App\Controllers;

use App\Models\KaryawanModel;
use App\Models\JabatanModel;
use App\Models\PresensiModel;
use App\Models\PenggajianModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $karyawanModel  = new KaryawanModel();
        $jabatanModel   = new JabatanModel();
        $presensiModel  = new PresensiModel();
        $penggajianModel = new PenggajianModel();

        $role = session()->get('role');
        $karyawanId = session()->get('karyawanId');

        $data = [
            'title' => 'Dashboard Overview',
        ];

        if ($role === 'admin') {
            $data['totalKaryawan'] = $karyawanModel->countAllResults();
            $data['totalJabatan']  = $jabatanModel->countAllResults();
            
            $currentMonth = (int)date('m');
            $currentYear  = (int)date('Y');

            $data['totalPresensi'] = $presensiModel->where('bulan', $currentMonth)
                                                   ->where('tahun', $currentYear)
                                                   ->countAllResults();

            $totalGajiRow = $penggajianModel->selectSum('gaji_bersih')
                                            ->where('bulan', 7) // Bulan 7 2026 data uji
                                            ->where('tahun', 2026)
                                            ->first();
            $data['totalGajiBulanIni'] = $totalGajiRow ? (float)$totalGajiRow['gaji_bersih'] : 0;

            // Chart 1: Monthly Expenditure
            $monthlySummary = $penggajianModel->getMonthlyPayrollSummary();
            $data['chartMonthly'] = $monthlySummary;

            // Chart 2: Department Distribution
            $data['chartJabatan'] = $karyawanModel->select('j.nama_jabatan, COUNT(k.id) as count')
                                                 ->from('karyawan k')
                                                 ->join('jabatan j', 'j.id = k.jabatan_id', 'left')
                                                 ->groupBy('j.id')
                                                 ->get()
                                                 ->getResultArray();
        } else {
            // Karyawan View
            $data['karyawanInfo'] = $karyawanModel->getKaryawanWithJabatan($karyawanId);
            $data['riwayatGaji']  = $penggajianModel->getPenggajianFull(null, null, $karyawanId);
        }

        return view('dashboard/index', $data);
    }
}
