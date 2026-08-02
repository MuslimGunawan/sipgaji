<?php

namespace App\Controllers;

use App\Models\KaryawanModel;
use App\Models\JabatanModel;
use App\Models\UserModel;

class Karyawan extends BaseController
{
    protected $karyawanModel;
    protected $jabatanModel;
    protected $userModel;

    public function __construct()
    {
        $this->karyawanModel = new KaryawanModel();
        $this->jabatanModel  = new JabatanModel();
        $this->userModel     = new UserModel();
    }

    public function index()
    {
        $keyword = $this->request->getGet('search');

        $builder = $this->karyawanModel->select('karyawan.*, jabatan.nama_jabatan, users.username, users.email')
            ->join('jabatan', 'jabatan.id = karyawan.jabatan_id', 'left')
            ->join('users', 'users.id = karyawan.user_id', 'left');

        if (! empty($keyword)) {
            $builder->groupStart()
                ->like('karyawan.nip', $keyword)
                ->orLike('karyawan.nama', $keyword)
                ->orLike('jabatan.nama_jabatan', $keyword)
                ->orLike('karyawan.alamat', $keyword)
                ->groupEnd();
        }

        $karyawanList = $builder->orderBy('karyawan.nama', 'ASC')->paginate(10);

        $data = [
            'title'        => 'Data Karyawan',
            'karyawanList' => $karyawanList,
            'pager'        => $this->karyawanModel->pager,
            'jabatanList'  => $this->jabatanModel->findAll(),
            'search'       => $keyword,
        ];

        return view('karyawan/index', $data);
    }

    public function store()
    {
        $rules = [
            'nip'           => 'required|min_length[5]|is_unique[karyawan.nip]',
            'nama'          => 'required|min_length[3]|max_length[150]',
            'username'      => 'required|min_length[3]|is_unique[users.username]',
            'email'         => 'required|valid_email|is_unique[users.email]',
            'password'      => 'required|min_length[6]',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'jabatan_id'    => 'required|integer',
            'tanggal_masuk' => 'required|valid_date',
            'status_nikah'  => 'required|in_list[Belum Menikah,Menikah]',
            'jumlah_anak'   => 'required|integer|greater_than_equal_to[0]',
            'foto'          => 'permit_empty|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png,image/webp]|max_size[foto,2048]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 1. Create User Account
        $userId = $this->userModel->insert([
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => 'karyawan',
        ]);

        // 2. Handle File Upload (Optional)
        $namaFoto = 'default.png';
        $fotoFile = $this->request->getFile('foto');

        if ($fotoFile && $fotoFile->isValid() && ! $fotoFile->hasMoved()) {
            $namaFoto = $fotoFile->getRandomName();
            $fotoFile->move(FCPATH . 'uploads/karyawan', $namaFoto);
        }

        // 3. Create Karyawan Data
        $this->karyawanModel->insert([
            'user_id'       => $userId,
            'nip'           => $this->request->getPost('nip'),
            'nama'          => $this->request->getPost('nama'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'tempat_lahir'  => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir') ?: null,
            'alamat'        => $this->request->getPost('alamat'),
            'no_telp'       => $this->request->getPost('no_telp'),
            'foto'          => $namaFoto,
            'jabatan_id'    => $this->request->getPost('jabatan_id'),
            'tanggal_masuk' => $this->request->getPost('tanggal_masuk'),
            'status_nikah'  => $this->request->getPost('status_nikah'),
            'jumlah_anak'   => $this->request->getPost('jumlah_anak'),
        ]);

        return redirect()->to('/karyawan')->with('success', 'Data Karyawan & Akun User berhasil dibuat.');
    }

    public function update($id = null)
    {
        $karyawan = $this->karyawanModel->find($id);
        if (! $karyawan) {
            return redirect()->to('/karyawan')->with('error', 'Data Karyawan tidak ditemukan.');
        }

        $rules = [
            'nip'           => 'required|min_length[5]|is_unique[karyawan.nip,id,' . $id . ']',
            'nama'          => 'required|min_length[3]|max_length[150]',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'jabatan_id'    => 'required|integer',
            'tanggal_masuk' => 'required|valid_date',
            'status_nikah'  => 'required|in_list[Belum Menikah,Menikah]',
            'jumlah_anak'   => 'required|integer|greater_than_equal_to[0]',
            'foto'          => 'permit_empty|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png,image/webp]|max_size[foto,2048]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $namaFoto = $karyawan['foto'];
        $fotoFile = $this->request->getFile('foto');

        if ($fotoFile && $fotoFile->isValid() && ! $fotoFile->hasMoved()) {
            $namaFoto = $fotoFile->getRandomName();
            $fotoFile->move(FCPATH . 'uploads/karyawan', $namaFoto);

            if ($karyawan['foto'] && $karyawan['foto'] !== 'default.png' && file_exists(FCPATH . 'uploads/karyawan/' . $karyawan['foto'])) {
                @unlink(FCPATH . 'uploads/karyawan/' . $karyawan['foto']);
            }
        }

        $this->karyawanModel->update($id, [
            'nip'           => $this->request->getPost('nip'),
            'nama'          => $this->request->getPost('nama'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'tempat_lahir'  => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir') ?: null,
            'alamat'        => $this->request->getPost('alamat'),
            'no_telp'       => $this->request->getPost('no_telp'),
            'foto'          => $namaFoto,
            'jabatan_id'    => $this->request->getPost('jabatan_id'),
            'tanggal_masuk' => $this->request->getPost('tanggal_masuk'),
            'status_nikah'  => $this->request->getPost('status_nikah'),
            'jumlah_anak'   => $this->request->getPost('jumlah_anak'),
        ]);

        return redirect()->to('/karyawan')->with('success', 'Data Karyawan berhasil diperbarui.');
    }

    public function delete($id = null)
    {
        $karyawan = $this->karyawanModel->find($id);
        if (! $karyawan) {
            return redirect()->to('/karyawan')->with('error', 'Data Karyawan tidak ditemukan.');
        }

        if ($karyawan['user_id']) {
            $this->userModel->delete($karyawan['user_id']);
        }

        if ($karyawan['foto'] && $karyawan['foto'] !== 'default.png' && file_exists(FCPATH . 'uploads/karyawan/' . $karyawan['foto'])) {
            @unlink(FCPATH . 'uploads/karyawan/' . $karyawan['foto']);
        }

        $this->karyawanModel->delete($id);
        return redirect()->to('/karyawan')->with('success', 'Data Karyawan & Akun User berhasil dihapus.');
    }
}
