<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ==========================================
// 1. ROUTE AUTENTIKASI & LANDING
// ==========================================
$routes->get('/', 'Auth::index');
$routes->get('login', 'Auth::index');
$routes->post('login/process', 'Auth::process');
$routes->get('logout', 'Auth::logout');
$routes->get('auth/logout', 'Auth::logout');
$routes->get('unauthorized', 'Auth::unauthorized');
$routes->get('auth/unauthorized', 'Auth::unauthorized');

// --- ROUTE ADMIN ---
$routes->group('admin', ['filter' => 'roleFilter:admin'], static function ($routes) {
    // Dashboard Admin
    $routes->get('dashboard', 'AdminController::index');
    
    // Kelola User
    $routes->get('user', 'AdminController::users');
    $routes->post('user/tambah', 'AdminController::tambahUser');
    $routes->post('user/edit', 'AdminController::editUser');
    $routes->get('user/hapus/(:num)', 'AdminController::hapusUser/$1');
    
    // Kelola Peserta Didik (Menggunakan PesertaDidikController)
    $routes->get('peserta-didik', 'PesertaDidikController::index');
    $routes->post('peserta-didik/tambah', 'PesertaDidikController::tambah');
    $routes->post('peserta-didik/edit', 'PesertaDidikController::edit');
    $routes->get('peserta-didik/hapus/(:num)', 'PesertaDidikController::hapus/$1');

    // Monitoring Jurnal Harian Admin
    $routes->get('jurnal', 'AdminController::jurnal');
    $routes->get('jurnal/detail/(:num)', 'AdminController::detailJurnal/$1');

    // Jadwal Notifikasi Admin (Prefix 'admin' Otomatis Terikut)
    $routes->get('jadwal-notif', 'Admin\JadwalNotifController::index');
    $routes->post('jadwal-notif/toggle', 'Admin\JadwalNotifController::toggleStatus');
    $routes->post('jadwal-notif/generate', 'Admin\JadwalNotifController::generateBulan');
});

// --- ROUTE CRON JOB (DILUAR FILTER ADMIN AGAR DAPAT DIPANGGIL SERVER) ---
$routes->get('cron/notifikasi/(:segment)', 'CronController::eksekusiNotifikasi/$1');

// Route API Simpan Token Web Push PWA (Bisa diakses oleh semua user terautentikasi)
$routes->post('pwa/subscribe', 'PwaController::subscribe');

$routes->group('gpk', ['filter' => 'roleFilter:gpk,admin'], static function ($routes) {
    $routes->get('dashboard', 'GpkController::index');
    $routes->get('jurnal-anak', 'GpkController::jurnalAnak'); // ROUTE BARU JURNAL ANAK
    $routes->get('jurnal', 'GpkController::jurnal');
    $routes->get('jurnal/tambah/(:num)', 'GpkController::tambahJurnal/$1');
    $routes->post('jurnal/simpan', 'GpkController::simpanJurnal');
    $routes->get('jurnal/detail/(:num)', 'GpkController::detailJurnal/$1');
    $routes->get('profil', 'GpkController::profil');
    $routes->get('renderFoto/(:any)', 'GpkController::renderFoto/$1');
    // RUTE EDIT & HAPUS JURNAL
    $routes->get('jurnal/edit/(:num)', 'GpkController::editJurnal/$1');
    $routes->post('jurnal/update/(:num)', 'GpkController::updateJurnal/$1');
    $routes->get('jurnal/foto/hapus/(:num)', 'GpkController::hapusFotoSingle/$1'); // ROUTE BARU HAPUS FOTO
    $routes->post('jurnal/hapus/(:num)', 'GpkController::hapusJurnal/$1');
    $routes->get('profil', 'GpkController::profil');
    $routes->post('profil/update-password', 'GpkController::updatePassword');
});

$routes->group('pimpinan', ['filter' => 'roleFilter:pimpinan,admin'], static function ($routes) {
    $routes->get('dashboard', 'PimpinanController::index');
    $routes->get('jurnal', 'PimpinanController::jurnal');
    $routes->get('jurnal/detail/(:num)', 'PimpinanController::detailJurnal/$1');
    
    // Rute Profil & Ganti Password Pimpinan
    $routes->get('profil', 'PimpinanController::profil');
    $routes->post('profil/update-password', 'PimpinanController::updatePassword');
});

// --- ROUTE ORANG TUA ---
$routes->group('ortu', ['filter' => 'roleFilter:ortu,admin'], static function ($routes) {
    $routes->get('dashboard', 'OrtuController::index');
    $routes->get('jurnal', 'OrtuController::jurnal');
    $routes->get('jurnal/detail/(:num)', 'OrtuController::detailJurnal/$1');
    $routes->get('profil', 'OrtuController::profil');
    $routes->post('profil/update-password', 'OrtuController::updatePassword');
});