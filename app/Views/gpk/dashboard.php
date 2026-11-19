<?= $this->extend('layouts/mobile_layout') ?>

<?= $this->section('content') ?>
    <style>
        .card-stat { background: linear-gradient(135deg, #e11d48, #fb7185); color: white; }
        .text-rose { color: #e11d48 !important; }
        .btn-rose { background-color: #e11d48; color: white; border-radius: 12px; }
        .btn-rose:hover { background-color: #be123c; color: white; }
    </style>

    <!-- Greeting & Banner Stats -->
    <div class="card card-mobile card-stat p-3 mb-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span class="badge bg-white text-dark mb-1">Guru Pendamping Khusus</span>
                <h5 class="fw-bold mb-0">Halo, <?= esc($nama_gpk) ?>!</h5>
                <small class="opacity-75">Siap mendampingi Ananda hari ini?</small>
            </div>
            <div class="text-end">
                <h2 class="fw-bold mb-0"><?= $total_anak ?></h2>
                <small class="opacity-75">Anak Dampingan</small>
            </div>
        </div>
    </div>

    <!-- Alert Batas Waktu Pengisian -->
    <div class="alert alert-warning border-0 shadow-sm rounded-4 d-flex align-items-center p-3 mb-3" role="alert">
        <i class="fas fa-clock fa-2x text-warning me-3"></i>
        <div>
            <strong class="small d-block">Pengisian Jurnal Harian</strong>
            <span class="small text-muted">Batas waktu pengisian setelah PBM (14:30 WIB)[cite: 11].</span>
        </div>
    </div>

    <!-- Daftar Anak Dampingan -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="fw-bold text-dark mb-0"><i class="fas fa-user-graduate text-rose me-1"></i> Anak Dampingan</h6>
    </div>

    <?php if(!empty($anakDampingan)): ?>
        <?php foreach($anakDampingan as $a): ?>
            <div class="card card-mobile p-3 bg-white mb-2 shadow-sm border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-1 text-dark"><?= esc($a['nama_pdbk']) ?></h6>
                        <span class="badge bg-rose-light text-rose border border-rose-subtle me-1">Kelas <?= esc($a['kelas']) ?></span>
                        <span class="badge bg-light text-dark border"><?= esc($a['unit']) ?></span>
                        <p class="small text-muted mt-1 mb-0"><i class="fas fa-brain me-1"></i> <?= esc($a['keterangan_iq']) ?></p>
                    </div>
                    <a href="/gpk/jurnal/tambah/<?= $a['id'] ?>" class="btn btn-rose btn-sm shadow-sm px-3 py-2">
                        <i class="fas fa-pen me-1"></i> Jurnal
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-center py-4 bg-white rounded-4 border">
            <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
            <p class="small text-muted mb-0">Belum ada anak dampingan yang ditugaskan.</p>
        </div>
    <?php endif; ?>
<?= $this->endSection() ?>