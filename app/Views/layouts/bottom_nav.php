<?php
$uri = service('uri');
$segment = $uri->getSegment(2); // Mengambil segment setelah /gpk/, /pimpinan/, /ortu/
$role = session()->get('role');

$navItems = [];

if ($role === 'gpk') {
    $navItems = [
        ['title' => 'Beranda',    'url' => '/gpk/dashboard',     'icon' => 'fas fa-home',          'slug' => 'dashboard'],
        ['title' => 'Anak',       'url' => '/gpk/peserta-didik', 'icon' => 'fas fa-user-graduate', 'slug' => 'peserta-didik'],
        ['title' => 'Isi Jurnal', 'url' => '/gpk/isi-jurnal',    'icon' => 'fas fa-pen-square',    'slug' => 'isi-jurnal'],
        ['title' => 'Akun',       'url' => '/gpk/profil',        'icon' => 'fas fa-user-circle',   'slug' => 'profil'],
    ];
} elseif ($role === 'pimpinan') {
    $navItems = [
        ['title' => 'Beranda',    'url' => '/pimpinan/dashboard', 'icon' => 'fas fa-home',        'slug' => 'dashboard'],
        ['title' => 'Jurnal',     'url' => '/pimpinan/jurnal',    'icon' => 'fas fa-book-open',   'slug' => 'jurnal'],
        ['title' => 'Laporan',    'url' => '/pimpinan/laporan',   'icon' => 'fas fa-chart-bar',   'slug' => 'laporan'],
        ['title' => 'Akun',       'url' => '/pimpinan/profil',    'icon' => 'fas fa-user-circle', 'slug' => 'profil'],
    ];
} elseif ($role === 'ortu') {
    $navItems = [
        ['title' => 'Beranda',       'url' => '/ortu/dashboard', 'icon' => 'fas fa-home',        'slug' => 'dashboard'],
        ['title' => 'Jurnal Ananda', 'url' => '/ortu/jurnal',    'icon' => 'fas fa-child',       'slug' => 'jurnal'],
        ['title' => 'Akun',          'url' => '/ortu/profil',    'icon' => 'fas fa-user-circle', 'slug' => 'profil'],
    ];
} else {
    // Default fallback (Admin/Lainnya)
    $navItems = [
        ['title' => 'Beranda', 'url' => '/admin/dashboard', 'icon' => 'fas fa-home',      'slug' => 'dashboard'],
        ['title' => 'Kelola',  'url' => '/admin/user',      'icon' => 'fas fa-users-cog', 'slug' => 'user'],
        ['title' => 'Akun',    'url' => '/admin/profil',    'icon' => 'fas fa-user-circle','slug' => 'profil'],
    ];
}
?>

<div class="d-flex justify-content-around align-items-center w-100 py-2">
    <?php foreach ($navItems as $item): ?>
        <?php 
            // Cek keaktifan berdasarkan segment URI atau penanganan khusus sub-route jurnal
            $isActive = ($segment === $item['slug']);
            if ($role === 'gpk' && $item['slug'] === 'isi-jurnal' && ($segment === 'isi-jurnal' || uri_string() === 'gpk/isi-jurnal')) {
                $isActive = true;
            }
        ?>
        <a href="<?= base_url($item['url']) ?>" class="bottom-nav-link <?= $isActive ? 'active' : '' ?>">
            <i class="<?= $item['icon'] ?> mb-1 fs-5"></i>
            <span class="small-text"><?= $item['title'] ?></span>
        </a>
    <?php endforeach; ?>
</div>