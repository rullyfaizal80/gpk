<?php
$uri = service('uri');
$segment = $uri->getSegment(2); // Mengambil segment URL kedua (misal: dashboard, jurnal, profil)
$role = session()->get('role');

$navItems = [];

if ($role === 'gpk') {
    $navItems = [
        ['title' => 'Beranda', 'url' => '/gpk/dashboard',  'icon' => 'fas fa-home',        'slug' => 'dashboard'],
        ['title' => 'Anak',    'url' => '/gpk/jurnal-anak', 'icon' => 'fas fa-child',       'slug' => 'jurnal-anak'],
        ['title' => 'Profil',  'url' => '/gpk/profil',      'icon' => 'fas fa-user-circle', 'slug' => 'profil'],
    ];
} elseif ($role === 'pimpinan') {
    $navItems = [
        ['title' => 'Beranda', 'url' => '/pimpinan/dashboard', 'icon' => 'fas fa-tachometer-alt', 'slug' => 'dashboard'],
        ['title' => 'Jurnal',  'url' => '/pimpinan/jurnal',    'icon' => 'fas fa-book-open',     'slug' => 'jurnal'],
        ['title' => 'Akun',    'url' => '/pimpinan/profil',    'icon' => 'fas fa-user-circle',   'slug' => 'profil'],
    ];
} elseif ($role === 'ortu') {
    // Navigasi Khusus Orang Tua
    $navItems = [
        ['title' => 'Beranda', 'url' => '/ortu/dashboard', 'icon' => 'fas fa-home',        'slug' => 'dashboard'],
        ['title' => 'Jurnal',  'url' => '/ortu/jurnal',    'icon' => 'fas fa-child',       'slug' => 'jurnal'],
        ['title' => 'Akun',    'url' => '/ortu/profil',    'icon' => 'fas fa-user-circle', 'slug' => 'profil'],
    ];
}
?>

<div class="d-flex justify-content-around align-items-center w-100 h-100 py-1">
    <?php foreach ($navItems as $item): ?>
        <?php $isActive = ($segment == $item['slug']) ? 'active' : ''; ?>
        <a href="<?= $item['url'] ?>" class="bottom-nav-link <?= $isActive ?>">
            <i class="<?= $item['icon'] ?> fs-5"></i>
            <span class="small-text"><?= $item['title'] ?></span>
        </a>
    <?php endforeach; ?>
</div>