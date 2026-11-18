<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // 1. Cek apakah user sudah login. Jika belum, lempar ke halaman login.
        if (!$session->get('isLoggedIn')) {
            $session->setFlashdata('error', 'Akses ditolak. Silakan login terlebih dahulu.');
            return redirect()->to('/login');
        }

        // 2. Cek apakah role user saat ini ada dalam daftar $arguments (role yang diizinkan di Routes.php)
        if (!empty($arguments)) {
            $userRole = $session->get('role');
            
            // Jika role user tidak terdaftar dalam argument route, tolak akses
            if (!in_array($userRole, $arguments)) {
                return redirect()->to('/auth/unauthorized'); 
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Dikosongkan
    }
}