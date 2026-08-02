<?php

namespace App\Controllers;

use App\Models\JabatanModel;

class Jabatan extends BaseController
{
    protected $jabatanModel;

    public function __construct()
    {
        $this->jabatanModel = new JabatanModel();
    }

    public function index()
    {
        $data = [
            'title'   => 'Data Jabatan & Skema Gaji',
            'jabatan' => $this->jabatanModel->orderBy('nama_jabatan', 'ASC')->findAll(),
        ];

        return view('jabatan/index', $data);
    }

    public function store()
    {
        $rules = [
            'nama_jabatan'            => 'required|min_length[3]|max_length[100]',
            'gaji_pokok'              => 'required|numeric|greater_than_equal_to[0]',
            'tunj_jabatan'            => 'required|numeric|greater_than_equal_to[0]',
            'tunj_makan_per_hari'     => 'required|numeric|greater_than_equal_to[0]',
            'tunj_transport_per_hari' => 'required|numeric|greater_than_equal_to[0]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->jabatanModel->insert([
            'nama_jabatan'            => $this->request->getPost('nama_jabatan'),
            'gaji_pokok'              => $this->request->getPost('gaji_pokok'),
            'tunj_jabatan'            => $this->request->getPost('tunj_jabatan'),
            'tunj_makan_per_hari'     => $this->request->getPost('tunj_makan_per_hari'),
            'tunj_transport_per_hari' => $this->request->getPost('tunj_transport_per_hari'),
        ]);

        return redirect()->to('/jabatan')->with('success', 'Data Jabatan berhasil ditambahkan.');
    }

    public function update($id = null)
    {
        $jabatan = $this->jabatanModel->find($id);
        if (! $jabatan) {
            return redirect()->to('/jabatan')->with('error', 'Data Jabatan tidak ditemukan.');
        }

        $rules = [
            'nama_jabatan'            => 'required|min_length[3]|max_length[100]',
            'gaji_pokok'              => 'required|numeric|greater_than_equal_to[0]',
            'tunj_jabatan'            => 'required|numeric|greater_than_equal_to[0]',
            'tunj_makan_per_hari'     => 'required|numeric|greater_than_equal_to[0]',
            'tunj_transport_per_hari' => 'required|numeric|greater_than_equal_to[0]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->jabatanModel->update($id, [
            'nama_jabatan'            => $this->request->getPost('nama_jabatan'),
            'gaji_pokok'              => $this->request->getPost('gaji_pokok'),
            'tunj_jabatan'            => $this->request->getPost('tunj_jabatan'),
            'tunj_makan_per_hari'     => $this->request->getPost('tunj_makan_per_hari'),
            'tunj_transport_per_hari' => $this->request->getPost('tunj_transport_per_hari'),
        ]);

        return redirect()->to('/jabatan')->with('success', 'Data Jabatan berhasil diperbarui.');
    }

    public function delete($id = null)
    {
        $jabatan = $this->jabatanModel->find($id);
        if (! $jabatan) {
            return redirect()->to('/jabatan')->with('error', 'Data Jabatan tidak ditemukan.');
        }

        $this->jabatanModel->delete($id);
        return redirect()->to('/jabatan')->with('success', 'Data Jabatan berhasil dihapus.');
    }
}
