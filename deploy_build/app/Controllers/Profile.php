<?php

namespace App\Controllers;

use App\Models\KaryawanModel;
use App\Models\UserModel;

class Profile extends BaseController
{
    protected $karyawanModel;
    protected $userModel;

    public function __construct()
    {
        $this->karyawanModel = new KaryawanModel();
        $this->userModel     = new UserModel();
    }

    public function index()
    {
        $userId = session()->get('userId');
        $role   = session()->get('role');

        $user = $this->userModel->find($userId);
        $karyawan = null;

        if ($role === 'karyawan') {
            $karyawan = $this->karyawanModel->getKaryawanByUserId($userId);
        }

        $data = [
            'title'    => 'Edit Profil Saya',
            'user'     => $user,
            'karyawan' => $karyawan,
        ];

        return view('profile/index', $data);
    }

    public function update()
    {
        $userId = session()->get('userId');
        $role   = session()->get('role');

        $rules = [
            'no_telp' => 'permit_empty|min_length[8]|max_length[20]',
            'alamat'  => 'permit_empty',
            'foto'    => 'permit_empty|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png,image/webp]|max_size[foto,2048]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if ($role === 'karyawan') {
            $karyawan = $this->karyawanModel->where('user_id', $userId)->first();
            if ($karyawan) {
                $namaFoto = $karyawan['foto'];
                $fotoFile = $this->request->getFile('foto');

                if ($fotoFile && $fotoFile->isValid() && ! $fotoFile->hasMoved()) {
                    $namaFoto = $fotoFile->getRandomName();
                    $fotoFile->move(FCPATH . 'uploads/karyawan', $namaFoto);

                    if ($karyawan['foto'] && $karyawan['foto'] !== 'default.png' && file_exists(FCPATH . 'uploads/karyawan/' . $karyawan['foto'])) {
                        @unlink(FCPATH . 'uploads/karyawan/' . $karyawan['foto']);
                    }
                }

                $this->karyawanModel->update($karyawan['id'], [
                    'no_telp' => $this->request->getPost('no_telp'),
                    'alamat'  => $this->request->getPost('alamat'),
                    'foto'    => $namaFoto,
                ]);

                // Update session
                session()->set('foto', $namaFoto);
            }
        }

        // Handle Password Change
        $oldPassword     = $this->request->getPost('old_password');
        $newPassword     = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        if (! empty($newPassword)) {
            $user = $this->userModel->find($userId);
            if (! password_verify($oldPassword, $user['password'])) {
                return redirect()->back()->with('error', 'Password lama yang Anda masukkan tidak sesuai.');
            }

            if (strlen($newPassword) < 6) {
                return redirect()->back()->with('error', 'Password baru minimal harus 6 karakter.');
            }

            if ($newPassword !== $confirmPassword) {
                return redirect()->back()->with('error', 'Konfirmasi password baru tidak cocok.');
            }

            $this->userModel->update($userId, [
                'password' => password_hash($newPassword, PASSWORD_DEFAULT),
            ]);
        }

        return redirect()->to('/profile')->with('success', 'Profil dan kredensial Anda berhasil diperbarui.');
    }
}
