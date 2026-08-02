<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\KaryawanModel;

class Auth extends BaseController
{
    public function index()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function processLogin()
    {
        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->to('/login');
        }

        $session = session();
        $userModel = new UserModel();

        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Username dan password wajib diisi.');
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $userModel->where('username', $username)
                          ->orWhere('email', $username)
                          ->first();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                // If role is karyawan, get employee profile ID
                $karyawanId = null;
                $namaLengkap = $user['username'];
                $foto = 'default.png';

                if ($user['role'] === 'karyawan') {
                    $karyawanModel = new KaryawanModel();
                    $karyawan = $karyawanModel->where('user_id', $user['id'])->first();
                    if ($karyawan) {
                        $karyawanId = $karyawan['id'];
                        $namaLengkap = $karyawan['nama'];
                        $foto = $karyawan['foto'];
                    }
                }

                $sessionData = [
                    'userId'      => $user['id'],
                    'username'    => $user['username'],
                    'email'       => $user['email'],
                    'role'        => $user['role'],
                    'karyawanId'  => $karyawanId,
                    'namaLengkap' => $namaLengkap,
                    'foto'        => $foto,
                    'isLoggedIn'  => true,
                ];

                $session->set($sessionData);
                if (function_exists('session_write_close')) {
                    session_write_close();
                }
                return redirect()->to('/dashboard')->with('success', 'Selamat datang kembali, ' . esc($namaLengkap) . '!');
            } else {
                return redirect()->back()->withInput()->with('error', 'Password yang Anda masukkan salah.');
            }
        } else {
            return redirect()->back()->withInput()->with('error', 'Username atau Email tidak ditemukan.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}
