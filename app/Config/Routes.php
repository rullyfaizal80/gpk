<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// --- ROUTE AUTENTIKASI ---
$routes->get('/', 'Auth::index');
$routes->get('login', 'Auth::index');
$routes->post('login/process', 'Auth::process');
$routes->get('logout', 'Auth::logout');
$routes->get('auth/logout', 'Auth::logout');
$routes->get('unauthorized', 'Auth::unauthorized');
$routes->get('auth/unauthorized', 'Auth::unauthorized');

// --- ROUTE ADMIN ---
// Diizinkan sementara untuk role admin, gpk, pimpinan, dan ortu agar pengembangan tidak terbentur filter
$routes->group('admin', ['filter' => 'roleFilter:admin,gpk,pimpinan,ortu'], static function ($routes) {
    $routes->get('dashboard', 'AdminController::index');
    
    // Kelola User
    $routes->get('user', 'AdminController::users');
    $routes->post('user/tambah', 'AdminController::tambahUser');
    $routes->post('user/edit', 'AdminController::editUser');
    $routes->get('user/hapus/(:num)', 'AdminController::hapusUser/$1');
    
    // Kelola Peserta Didik (PDBK)
    $routes->get('peserta-didik', 'AdminController::pesertaDidik');
    $routes->post('peserta-didik/tambah', 'AdminController::tambahPesertaDidik');
    $routes->post('peserta-didik/edit', 'AdminController::editPesertaDidik');
    $routes->get('peserta-didik/hapus/(:num)', 'AdminController::hapusPesertaDidik/$1');

    // Jurnal & Laporan
    $routes->get('jurnal', 'AdminController::jurnal');
    $routes->get('laporan', 'AdminController::laporan');
    $routes->get('laporan/cetak', 'AdminController::cetak');
    $routes->get('renderFoto/(:any)', 'AdminController::renderFoto/$1');
});

// --- ROUTE GPK (GURU PENDAMPING KHUSUS) ---
$routes->group('gpk', ['filter' => 'roleFilter:gpk,admin'], static function ($routes) {
    $routes->get('dashboard', 'GpkController::index');
    $routes->get('peserta-didik', 'GpkController::pesertaDidik');
    $routes->get('jurnal', 'GpkController::jurnal');
    $routes->post('jurnal/simpan', 'GpkController::simpanJurnal');
});

// --- ROUTE PIMPINAN ---
$routes->group('pimpinan', ['filter' => 'roleFilter:pimpinan,admin'], static function ($routes) { 
    $routes->get('dashboard', 'PimpinanController::index');
    $routes->get('laporan', 'PimpinanController::laporan');
    $routes->get('laporan/cetak', 'PimpinanController::cetak');
    $routes->get('renderFoto/(:any)', 'PimpinanController::renderFoto/$1');
});

// --- ROUTE ORANG TUA ---
$routes->group('ortu', ['filter' => 'roleFilter:ortu,admin'], static function ($routes) {
    $routes->get('dashboard', 'OrtuController::index');
    $routes->get('jurnal', 'OrtuController::jurnal');
});