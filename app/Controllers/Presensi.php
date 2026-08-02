<?php

namespace App\Controllers;

use App\Models\PresensiModel;
use App\Models\KaryawanModel;

class Presensi extends BaseController
{
    protected $presensiModel;
    protected $karyawanModel;

    public function __construct()
    {
        $this->presensiModel = new PresensiModel();
        $this->karyawanModel = new KaryawanModel();
    }

    public function index()
    {
        $bulan = (int)($this->request->getGet('bulan') ?: 7);
        $tahun = (int)($this->request->getGet('tahun') ?: 2026);

        $role = session()->get('role');
        $karyawanId = session()->get('karyawanId');

        if ($role === 'karyawan') {
            $presensiList = $this->presensiModel->getPresensiWithKaryawan($bulan, $tahun, $karyawanId);
        } else {
            $presensiList = $this->presensiModel->getPresensiWithKaryawan($bulan, $tahun);
        }

        $data = [
            'title'        => 'Rekapitulasi Presensi & Lembur',
            'bulan'        => $bulan,
            'tahun'        => $tahun,
            'presensiList' => $presensiList,
            'karyawanList' => $this->karyawanModel->orderBy('nama', 'ASC')->findAll(),
        ];

        return view('presensi/index', $data);
    }

    public function store()
    {
        $rules = [
            'karyawan_id'       => 'required|integer',
            'bulan'             => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[12]',
            'tahun'             => 'required|integer|greater_than_equal_to[2000]',
            'jumlah_hadir'      => 'required|integer|greater_than_equal_to[0]',
            'jumlah_sakit'      => 'required|integer|greater_than_equal_to[0]',
            'jumlah_izin'       => 'required|integer|greater_than_equal_to[0]',
            'jumlah_alpa'       => 'required|integer|greater_than_equal_to[0]',
            'jumlah_lembur_jam' => 'required|integer|greater_than_equal_to[0]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $karyawanId = $this->request->getPost('karyawan_id');
        $bulan      = $this->request->getPost('bulan');
        $tahun      = $this->request->getPost('tahun');

        // Check if attendance already exists for employee in this month/year
        $existing = $this->presensiModel->where('karyawan_id', $karyawanId)
                                         ->where('bulan', $bulan)
                                         ->where('tahun', $tahun)
                                         ->first();

        $dataPost = [
            'karyawan_id'       => $karyawanId,
            'bulan'             => $bulan,
            'tahun'             => $tahun,
            'jumlah_hadir'      => $this->request->getPost('jumlah_hadir'),
            'jumlah_sakit'      => $this->request->getPost('jumlah_sakit'),
            'jumlah_izin'       => $this->request->getPost('jumlah_izin'),
            'jumlah_alpa'       => $this->request->getPost('jumlah_alpa'),
            'jumlah_lembur_jam' => $this->request->getPost('jumlah_lembur_jam'),
        ];

        if ($existing) {
            $this->presensiModel->update($existing['id'], $dataPost);
            $msg = 'Data Presensi berhasil diperbarui.';
        } else {
            $this->presensiModel->insert($dataPost);
            $msg = 'Data Presensi berhasil disimpan.';
        }

        return redirect()->to('/presensi?bulan=' . $bulan . '&tahun=' . $tahun)->with('success', $msg);
    }
}
