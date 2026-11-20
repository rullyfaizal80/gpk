<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('sidebar_menu') ?>
    <li class="nav-item"><a class="nav-link active" href="/admin/dashboard"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
    <li class="nav-item"><a class="nav-link" href="/admin/user"><i class="fas fa-users me-2"></i> Kelola User</a></li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard Admin</h1>
    </div>

    <!-- JUDUL STATISTIK SISTEM GPK -->
    <div class="d-flex justify-content-between align-items-center mb-3 mt-2">
        <h5 class="fw-bold mb-0">
            <i class="fas fa-chart-pie text-primary me-2"></i> Ringkasan Sistem GPK
        </h5>
        <span class="badge bg-primary px-3 py-2 fs-6 rounded-pill shadow-sm">
            <i class="fas fa-calendar-day me-1"></i> <?= date('F Y') ?>
        </span>
    </div>

    <!-- KARTU STATISTIK ADMIN -->
    <div class="row">
        
        <!-- Card 1: Total GPK -->
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary h-100 shadow-sm border-0">
                <div class="card-body">
                    <h6 class="card-title fw-bold"><i class="fas fa-user-tie me-1"></i> Total GPK</h6>
                    <p class="card-text display-5 fw-bold mb-0"><?= $totalGpk ?></p>
                    <small>Guru Pendamping Khusus</small>
                </div>
            </div>
        </div>
        
        <!-- Card 2: Total PDBK (Anak Dampingan) -->
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-info h-100 shadow-sm border-0">
                <div class="card-body">
                    <h6 class="card-title fw-bold"><i class="fas fa-child me-1"></i> Total PDBK</h6>
                    <p class="card-text display-5 fw-bold mb-0"><?= $totalPdbk ?></p>
                    <small>Peserta Didik</small>
                </div>
            </div>
        </div>

        <!-- Card 3: Jurnal Bulan Ini -->
        <div class="col-md-3 mb-3">
            <div class="card text-dark bg-warning h-100 shadow-sm border-0">
                <div class="card-body">
                    <h6 class="card-title fw-bold"><i class="fas fa-calendar-alt me-1"></i> Jurnal Bulan Ini</h6>
                    <p class="card-text display-5 fw-bold mb-0"><?= $jurnalBulanIni ?></p>
                    <small>Entri Jurnal Terisi</small>
                </div>
            </div>
        </div>
        
        <!-- Card 4: Total Seluruh Jurnal -->
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success h-100 shadow-sm border-0">
                <div class="card-body">
                    <h6 class="card-title fw-bold"><i class="fas fa-book-open me-1"></i> Total Jurnal</h6>
                    <p class="card-text display-5 fw-bold mb-0"><?= $totalJurnal ?></p>
                    <small>Keseluruhan Riwayat</small>
                </div>
            </div>
        </div>
        
    </div>

    <hr class="my-4 border-2 border-secondary opacity-25">

    <!-- SELAMAT DATANG ADMIN -->
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-white fw-bold">
            <i class="fas fa-info-circle text-primary me-1"></i> Informasi Sistem Pelaporan GPK
        </div>
        <div class="card-body">
            <h5 class="fw-bold">Selamat datang di Panel Administrator</h5>
            <p class="text-muted mb-0">
                Sistem ini digunakan untuk mengelola data akun Guru Pendamping Khusus (GPK) serta memantau keterisian jurnal harian peserta didik (PDBK). Gunakan menu <strong>Kelola User</strong> pada bilah navigasi untuk menambah, mengubah, atau mereset kata sandi akun pengguna.
            </p>
        </div>
    </div>
<?= $this->endSection() ?>