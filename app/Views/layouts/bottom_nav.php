<?php
$uri = service('uri');
$segment = $uri->getSegment(2); // Mengambil segment setelah /gpk/, /pimpinan/, /ortu/
$role = session()->get('role');

$navItems = [];

if ($role === 'gpk') {
    $navItems = [
        ['title' => 'Beranda',     'url' => '/gpk/dashboard',  'icon' => 'fas fa-home',          'slug' => 'dashboard'],
        ['title' => 'Jurnal Anak', 'url' => '/gpk/jurnal-anak', 'icon' => 'fas fa-user-graduate', 'slug' => 'jurnal-anak'],
        ['title' => 'Akun',        'url' => '/gpk/profil',     'icon' => 'fas fa-user-circle',   'slug' => 'profil'],
    ];
} elseif ($role === 'pimpinan') {$navItems = [
        ['title' => 'Beranda', 'url' => '/pimpinan/dashboard', 'icon' => 'fas fa-tachometer-alt', 'slug' => 'dashboard'],
        ['title' => 'Jurnal',  'url' => '/pimpinan/jurnal',    'icon' => 'fas fa-book-open',     'slug' => 'jurnal'],
        ['title' => 'Akun',    'url' => '/pimpinan/profil',    'icon' => 'fas fa-user-circle',   'slug' => 'profil'],
    ];
} elseif ($role === 'ortu') {
    $navItems = [
        ['title' => 'Beranda',       'url' => '/ortu/dashboard', 'icon' => 'fas fa-home',        'slug' => 'dashboard'],
        ['title' => 'Jurnal Ananda', 'url' => '/ortu/jurnal',    'icon' => 'fas fa-child',       'slug' => 'jurnal'],
        ['title' => 'Akun',          'url' => '/ortu/profil',    'icon' => 'fas fa-user-circle', 'slug' => 'profil'],
    ];
} else {
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
            $isActive = ($segment === $item['slug']);
            // Aktifkan tab jika sedang membuka sub-halaman terkait jurnal anak
            if ($role === 'gpk' && $item['slug'] === 'jurnal-anak' && in_array($segment, ['jurnal-anak', 'peserta-didik', 'isi-jurnal', 'jurnal'])) {
                $isActive = true;
            }
        ?>
        <a href="<?= base_url($item['url']) ?>" class="bottom-nav-link <?= $isActive ? 'active' : '' ?>">
            <i class="<?= $item['icon'] ?> mb-1 fs-5"></i>
            <span class="small-text"><?= $item['title'] ?></span>
        </a>
    <?php endforeach; ?>
</div>