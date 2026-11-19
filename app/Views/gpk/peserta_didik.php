<?= $this->extend('layouts/mobile_layout') ?>

<?= $this->section('content') ?>
    <style>
        .text-rose { color: #e11d48 !important; }
        .btn-rose { background-color: #e11d48; color: white; border-radius: 10px; }
        .btn-rose:hover { background-color: #be123c; color: white; }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold text-rose mb-0"><i class="fas fa-user-graduate me-1"></i> Daftar Anak Dampingan</h6>
        <span class="badge bg-rose-light text-rose border border-rose-subtle"><?= count($anakDampingan) ?> Anak</span>
    </div>

    <?php if(!empty($anakDampingan)): ?>
        <?php foreach($anakDampingan as $a): ?>
            <div class="card card-mobile p-3 bg-white mb-2 shadow-sm border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-1 text-dark"><?= esc($a['nama_pdbk']) ?></h6>
                        <div class="mb-1">
                            <span class="badge bg-rose-light text-rose border border-rose-subtle">Kelas <?= esc($a['kelas']) ?></span>
                            <span class="badge bg-light text-dark border"><?= esc($a['unit']) ?></span>
                        </div>
                        <small class="text-muted d-block"><i class="fas fa-brain me-1"></i> <?= esc($a['keterangan_iq'] ?? '-') ?></small>
                    </div>
                    <div>
                        <a href="/gpk/jurnal/tambah/<?= $a['id'] ?>" class="btn btn-rose btn-sm px-3 py-2 shadow-sm">
                            <i class="fas fa-pen me-1"></i> Jurnal
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-center py-5 bg-white rounded-4 border">
            <i class="fas fa-user-slash fa-3x text-muted mb-2"></i>
            <p class="small text-muted mb-0">Belum ada anak yang didampingi.</p>
        </div>
    <?php endif; ?>
<?= $this->endSection() ?>