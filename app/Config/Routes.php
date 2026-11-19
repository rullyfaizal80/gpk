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

// ==========================================
// 2. ROUTE ADMIN (MANAJEMEN SISTEM)
// ==========================================
$routes->group('admin', ['filter' => 'roleFilter:admin'], static function ($routes) {
    $routes->get('dashboard', 'AdminController::index');
    
    // Kelola User (Admin, GPK, Pimpinan, Ortu)
    $routes->get('user', 'AdminController::users');
    $routes->post('user/tambah', 'AdminController::tambahUser');
    $routes->post('user/edit', 'AdminController::editUser');
    $routes->get('user/hapus/(:num)', 'AdminController::hapusUser/$1');
    
    // Kelola Peserta Didik (PDBK)
    $routes->get('peserta-didik', 'AdminController::pesertaDidik');
    $routes->post('peserta-didik/tambah', 'AdminController::tambahPesertaDidik');
    $routes->post('peserta-didik/edit', 'AdminController::editPesertaDidik');
    $routes->get('peserta-didik/hapus/(:num)', 'AdminController::hapusPesertaDidik/$1');

    // Jurnal & Rekap Laporan
    $routes->get('jurnal', 'AdminController::jurnal');
    $routes->get('laporan', 'AdminController::laporan');
    $routes->get('laporan/cetak', 'AdminController::cetak');
    $routes->get('renderFoto/(:any)', 'AdminController::renderFoto/$1');
});

// ==========================================
// 3. ROUTE GPK (GURU PENDAMPING KHUSUS)
// ==========================================
$routes->group('gpk', ['filter' => 'roleFilter:gpk,admin'], static function ($routes) {
    $routes->get('dashboard', 'GpkController::index');
    
    // Data Anak Dampingan & Profil
    $routes->get('peserta-didik', 'GpkController::pesertaDidik');
    $routes->get('profil', 'GpkController::profil');

    // Input & Kelola Jurnal Harian
    $routes->get('jurnal', 'GpkController::jurnal');
    $routes->get('jurnal/tambah/(:num)', 'GpkController::tambahJurnal/$1');
    $routes->post('jurnal/simpan', 'GpkController::simpanJurnal');
    $routes->get('jurnal/detail/(:num)', 'GpkController::detailJurnal/$1');
});

// ==========================================
// 4. ROUTE PIMPINAN (KEPALA SEKOLAH / MONITORING)
// ==========================================
$routes->group('pimpinan', ['filter' => 'roleFilter:pimpinan,admin'], static function ($routes) { 
    $routes->get('dashboard', 'PimpinanController::index');
    $routes->get('profil', 'PimpinanController::profil');
    
    // Monitoring Jurnal Harian Seluruh Unit (MI/MTs)
    $routes->get('jurnal', 'PimpinanController::jurnal');
    $routes->get('jurnal/detail/(:num)', 'PimpinanController::detailJurnal/$1');
    
    // Rekap & Laporan Inklusi
    $routes->get('laporan', 'PimpinanController::laporan');
    $routes->get('laporan/cetak', 'PimpinanController::cetak');
    $routes->get('renderFoto/(:any)', 'PimpinanController::renderFoto/$1');
});

// ==========================================
// 5. ROUTE ORANG TUA (PESERTA DIDIK)
// ==========================================
$routes->group('ortu', ['filter' => 'roleFilter:ortu,admin'], static function ($routes) {
    $routes->get('dashboard', 'OrtuController::index');
    $routes->get('profil', 'OrtuController::profil');
    
    // Membaca Narasi Perkembangan Ananda
    $routes->get('jurnal', 'OrtuController::jurnal');
    $routes->get('jurnal/detail/(:num)', 'OrtuController::detailJurnal/$1');
});