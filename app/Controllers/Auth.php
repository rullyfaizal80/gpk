<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        // Jika user sudah login, redirect otomatis sesuai role
        if (session()->get('isLoggedIn')) {
            return $this->redirectBasedOnRole(session()->get('role'));
        }
        
        // Menampilkan view login yang berada di folder app/Views/auth/login.php
        return view('auth/login');
    }

    public function process()
    {
        $session   = session();
        $userModel = new UserModel();

        $username = trim($this->request->getPost('username'));
        $password = trim($this->request->getPost('password'));

        // 1. Cari user di database berdasarkan username
        $user = $userModel->where('username', $username)->first();

        if ($user) {
            // 2. Cek status aktif akun
            if ($user['is_active'] != 1) {
                $session->setFlashdata('error', 'Akun Anda telah dinonaktifkan.');
                return redirect()->to('/login');
            }

            // 3. Verifikasi Hash Password Standar
            if (password_verify($password, $user['password'])) {
                $sessData = [
                    'id'           => $user['id'],
                    'username'     => $user['username'],
                    'nama_lengkap' => $user['nama_lengkap'],
                    'role'         => $user['role'],
                    'isLoggedIn'   => true
                ];
                $session->set($sessData);

                return $this->redirectBasedOnRole($user['role']);
            } else {
                $session->setFlashdata('error', 'Password salah.');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('error', 'Username tidak ditemukan.');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function unauthorized()
    {
        return "Akses Ditolak. Anda tidak memiliki izin untuk membuka halaman ini.";
    }

    // Routing otomatis berdasarkan role pengguna
    private function redirectBasedOnRole($role)
    {
        switch ($role) {
            case 'admin':
            case 'gpk':
            case 'pimpinan':
            case 'ortu':
                // Sementara semua role diarahkan ke /admin/dashboard agar tidak error 404
                return redirect()->to('/admin/dashboard');
            default:
                return redirect()->to('/login');
        }
    }
}