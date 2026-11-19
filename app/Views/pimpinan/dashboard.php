<?= $this->extend('layouts/mobile_layout') ?>

<?= $this->section('content') ?>
    <style>
        .text-rose { color: #e11d48 !important; }
        .bg-rose-light { background-color: #fff1f2 !important; }
    </style>

    <!-- Ringkasan Pimpinan -->
    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-3"><i class="fas fa-chart-pie me-1"></i> Ringkasan Inklusi MIMHa</h6>
        <div class="row text-center g-2">
            <div class="col-6">
                <div class="p-3 rounded-4 bg-rose-light border border-rose-subtle">
                    <i class="fas fa-user-graduate fa-2x text-rose mb-1"></i>
                    <h3 class="fw-bold text-rose mb-0"><?= $totalPdbk ?></h3>
                    <small class="text-muted fw-semibold">Total PDBK</small>
                </div>
            </div>
            <div class="col-6">
                <div class="p-3 rounded-4 bg-light border">
                    <i class="fas fa-chalkboard-teacher fa-2x text-secondary mb-1"></i>
                    <h3 class="fw-bold text-dark mb-0"><?= $totalGpk ?></h3>
                    <small class="text-muted fw-semibold">Guru GPK</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Distribution Unit PDBK -->
    <h6 class="fw-bold text-dark mb-2"><i class="fas fa-school text-rose me-1"></i> Sebaran Per Unit</h6>
    <div class="row g-2 mb-3">
        <div class="col-4">
            <div class="card card-mobile p-2 text-center bg-white shadow-sm">
                <span class="badge bg-warning text-dark mb-1">PERIL</span>
                <h5 class="fw-bold mb-0"><?= $perilCount ?></h5>
                <small class="text-muted">Anak</small>
            </div>
        </div>
        <div class="col-4">
            <div class="card card-mobile p-2 text-center bg-white shadow-sm">
                <span class="badge bg-primary mb-1">MI</span>
                <h5 class="fw-bold mb-0"><?= $miCount ?></h5>
                <small class="text-muted">Anak</small>
            </div>
        </div>
        <div class="col-4">
            <div class="card card-mobile p-2 text-center bg-white shadow-sm">
                <span class="badge bg-success mb-1">MTs</span>
                <h5 class="fw-bold mb-0"><?= $mtsCount ?></h5>
                <small class="text-muted">Anak</small>
            </div>
        </div>
    </div>

    <!-- Menu Akses Cepat Monitoring -->
    <a href="/pimpinan/jurnal" class="card card-mobile p-3 bg-white shadow-sm text-decoration-none text-dark d-flex flex-row align-items-center justify-content-between mb-2">
        <div class="d-flex align-items-center">
            <div class="p-3 rounded-circle bg-rose-light text-rose me-3">
                <i class="fas fa-book-open fa-lg"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0">Monitoring Jurnal Harian</h6>
                <small class="text-muted">Pantau jurnal harian seluruh unit (15.00 WIB)[cite: 11]</small>
            </div>
        </div>
        <i class="fas fa-chevron-right text-muted"></i>
    </a>
<?= $this->endSection() ?>