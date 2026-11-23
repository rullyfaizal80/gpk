<?php
$uri = service('uri');
$segment = $uri->getSegment(2); // Mengambil segment URL setelah /admin/, /gpk/, dsb.
$role = session()->get('role'); // Ambil role user dari session

// Struktur menu dinamis berdasarkan role
$menus = [];

if ($role === 'admin') {
    $menus = [
        ['title' => 'Dashboard', 'url' => '/admin/dashboard', 'icon' => 'fas fa-tachometer-alt', 'slug' => 'dashboard'],
        ['title' => 'Data Peserta Didik', 'url' => '/admin/peserta-didik', 'icon' => 'fas fa-user-graduate', 'slug' => 'peserta-didik'],
        ['title' => 'Kelola User', 'url' => '/admin/user', 'icon' => 'fas fa-users', 'slug' => 'user'],
        ['title' => 'Monitoring Jurnal', 'url' => '/admin/jurnal', 'icon' => 'fas fa-journal-whills', 'slug' => 'jurnal'],
        ['title' => 'Jadwal Notifikasi', 'url' => '/admin/jadwal-notif', 'icon' => 'fas fa-calendar-alt', 'slug' => 'jadwal-notif'],
    ];
} elseif ($role === 'gpk') {
    $menus = [
        ['title' => 'Dashboard', 'url' => '/gpk/dashboard', 'icon' => 'fas fa-tachometer-alt', 'slug' => 'dashboard'],
        ['title' => 'Anak Dampingan', 'url' => '/gpk/jurnal-anak', 'icon' => 'fas fa-child', 'slug' => 'jurnal-anak'],
        ['title' => 'Profil Saya', 'url' => '/gpk/profil', 'icon' => 'fas fa-user-cog', 'slug' => 'profil'],
    ];
} elseif ($role === 'pimpinan') {
    $menus = [
        ['title' => 'Dashboard', 'url' => '/pimpinan/dashboard', 'icon' => 'fas fa-tachometer-alt', 'slug' => 'dashboard'],
        ['title' => 'Monitoring Jurnal', 'url' => '/pimpinan/jurnal', 'icon' => 'fas fa-book-open', 'slug' => 'jurnal'],
    ];
} elseif ($role === 'ortu') {
    $menus = [
        ['title' => 'Dashboard', 'url' => '/ortu/dashboard', 'icon' => 'fas fa-home', 'slug' => 'dashboard'],
        ['title' => 'Jurnal Perkembangan', 'url' => '/ortu/jurnal', 'icon' => 'fas fa-file-alt', 'slug' => 'jurnal'],
    ];
}
?>

<!-- Info User Status -->
<div class="text-center mb-3 pb-3 border-bottom px-3">
    <div class="mb-2">
        <span class="d-inline-block rounded-circle p-2 bg-rose-light text-rose">
            <i class="fas fa-user-circle fa-3x"></i>
        </span>
    </div>
    <h6 class="fw-bold mb-0 text-truncate text-dark" title="<?= esc(session()->get('nama_lengkap')) ?>">
        <?= esc(session()->get('nama_lengkap') ?? 'Pengguna') ?>
    </h6>
    <small class="badge bg-rose-light text-rose border border-rose-subtle text-uppercase mt-1 px-2 py-1 fw-bold">
        <i class="fas fa-id-badge me-1"></i> <?= esc(session()->get('role') ?? 'Role') ?>
    </small>
</div>

<!-- Navigasi Menu Dinamis -->
<ul class="nav flex-column gap-1">
    <?php foreach ($menus as $m): ?>
        <li class="nav-item">
            <a class="nav-link nav-rose <?= ($segment == $m['slug']) ? 'active' : '' ?>" href="<?= $m['url'] ?>">
                <i class="<?= $m['icon'] ?> me-2"></i> <?= $m['title'] ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>