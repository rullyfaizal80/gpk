<?= $this->extend('layouts/mobile_layout') ?>

<?= $this->section('content') ?>
<style>
    .text-rose { color: #e11d48 !important; }
    .bg-rose { background-color: #e11d48 !important; color: white; }
    .bg-rose-light { background-color: #fff1f2 !important; }
    .border-rose-subtle { border-color: #fecdd3 !important; }

    .stat-card-mobile {
        border-radius: 14px;
        padding: 12px 16px;
        border: none;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
</style>

<!-- Header Ringkas Mobile -->
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h5 class="fw-bold mb-0 text-rose"><i class="fas fa-chart-line me-2"></i>Dashboard Pimpinan</h5>
        <small class="text-muted"><?= date('d F Y') ?></small>
    </div>
    <span class="badge bg-rose-light text-rose border border-rose-subtle px-2 py-1">Pimpinan</span>
</div>

<!-- Ringkasan Statistik Grid Mobile (2 Kolom) -->
<div class="row g-2 mb-3">
    <div class="col-6">
        <div class="stat-card-mobile bg-white">
            <small class="text-muted d-block mb-1"><i class="fas fa-child text-primary me-1"></i> Total PDBK</small>
            <h4 class="fw-bold text-dark mb-0"><?= $totalPdbk ?></h4>
        </div>
    </div>
    <div class="col-6">
        <div class="stat-card-mobile bg-white">
            <small class="text-muted d-block mb-1"><i class="fas fa-user-tie text-info me-1"></i> Total GPK</small>
            <h4 class="fw-bold text-dark mb-0"><?= $totalGpk ?></h4>
        </div>
    </div>
    <div class="col-6">
        <div class="stat-card-mobile bg-white">
            <small class="text-muted d-block mb-1"><i class="fas fa-edit text-warning me-1"></i> Hari Ini</small>
            <h4 class="fw-bold text-dark mb-0"><?= $jurnalHariIni ?></h4>
        </div>
    </div>
    <div class="col-6">
        <div class="stat-card-mobile bg-white">
            <small class="text-muted d-block mb-1"><i class="fas fa-calendar-check text-success me-1"></i> Bulan Ini</small>
            <h4 class="fw-bold text-dark mb-0"><?= $jurnalBulanIni ?></h4>
        </div>
    </div>
</div>

<!-- Rekap Keterisian Unit -->
<div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
    <h6 class="fw-bold text-rose mb-3">
        <i class="fas fa-layer-group me-2"></i>Rekap Jurnal per Unit
    </h6>
    
    <!-- Pembagi Kolom Presisi & Simetris -->
    <div class="d-flex text-center pt-2 border-top">
        <div class="flex-fill px-2">
            <small class="text-muted d-block fw-bold mb-1">PERIL</small>
            <span class="fs-4 fw-bold text-danger"><?= $rekapUnit['PERIL'] ?></span>
        </div>
        
        <div class="border-end opacity-25"></div>
        
        <div class="flex-fill px-2">
            <small class="text-muted d-block fw-bold mb-1">MI</small>
            <span class="fs-4 fw-bold text-primary"><?= $rekapUnit['MI'] ?></span>
        </div>
        
        <div class="border-end opacity-25"></div>
        
        <div class="flex-fill px-2">
            <small class="text-muted d-block fw-bold mb-1">MTs</small>
            <span class="fs-4 fw-bold text-success"><?= $rekapUnit['MTs'] ?></span>
        </div>
    </div>
</div>

<!-- Daftar Jurnal Terbaru (Mobile Cards List) -->
<div class="d-flex justify-content-between align-items-center mb-2">
    <h6 class="fw-bold text-dark mb-0"><i class="fas fa-clock text-rose me-1"></i> Jurnal Terbaru</h6>
    <a href="/pimpinan/jurnal" class="small text-rose fw-bold text-decoration-none">Lihat Semua <i class="fas fa-chevron-right ms-1"></i></a>
</div>

<?php if(!empty($latestJurnal)): ?>
    <div class="d-flex flex-column gap-2 mb-4">
        <?php foreach($latestJurnal as $j): ?>
            <a href="/pimpinan/jurnal/detail/<?= $j['id'] ?>" class="card card-mobile p-3 bg-white shadow-sm border-0 text-decoration-none text-dark">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <span class="badge bg-rose-light text-rose border border-rose-subtle" style="font-size: 0.7rem;"><?= esc($j['unit']) ?> - Kelas <?= esc($j['kelas']) ?></span>
                    <small class="text-muted" style="font-size: 0.75rem;"><i class="far fa-calendar-alt me-1"></i><?= date('d/m/Y', strtotime($j['tanggal'])) ?></small>
                </div>
                <h6 class="fw-bold mb-1 text-dark"><?= esc($j['nama_pdbk']) ?></h6>
                <small class="text-muted d-block"><i class="fas fa-user-circle me-1"></i> GPK: <?= esc($j['nama_gpk'] ?? '-') ?></small>
            </a>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="text-center py-4 bg-white rounded-4 border border-dashed mb-4">
        <small class="text-muted">Belum ada jurnal yang terisi.</small>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>