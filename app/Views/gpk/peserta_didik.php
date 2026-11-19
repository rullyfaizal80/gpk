<?= $this->extend('layouts/mobile_layout') ?>

<?= $this->section('content') ?>
<style>
    .text-rose { color: #e11d48 !important; }
    .btn-outline-rose { color: #e11d48; border-color: #e11d48; border-radius: 10px; }
    .btn-outline-rose:hover { background-color: #e11d48; color: white; }
    .bg-rose-light { background-color: #fff1f2 !important; }
    .border-rose-subtle { border-color: #fecdd3 !important; }
</style>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="fw-bold text-rose mb-0"><i class="fas fa-user-graduate me-1"></i> Daftar Anak Dampingan</h5>
        <small class="text-muted">Total <?= count($anakDampingan) ?> Siswa Inklusi</small>
    </div>
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
                    <!-- Mengarahkan ke Filter Jurnal Khusus Anak Ini -->
                    <a href="/gpk/jurnal?pdbk_id=<?= $a['id'] ?>" class="btn btn-outline-rose btn-sm px-3 py-2 fw-semibold">
                        <i class="fas fa-eye me-1"></i> Jurnal
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