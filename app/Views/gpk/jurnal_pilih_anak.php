<?= $this->extend('layouts/mobile_layout') ?>

<?= $this->section('content') ?>
<style>
    .text-rose { color: #e11d48 !important; }
    .btn-rose { background-color: #e11d48; color: white; border-radius: 10px; }
    .btn-rose:hover { background-color: #be123c; color: white; }
    .bg-rose-light { background-color: #fff1f2 !important; }
    .border-rose-subtle { border-color: #fecdd3 !important; }
</style>

<div class="mb-3">
    <h5 class="fw-bold text-rose mb-0"><i class="fas fa-pen-square me-1"></i> Pilih Anak Dampingan</h5>
    <small class="text-muted">Pilih siswa yang akan diisi jurnal hariannya</small>
</div>

<?php if(!empty($anakDampingan)): ?>
    <div class="row g-2 mb-4">
        <?php foreach($anakDampingan as $a): ?>
            <div class="col-12 col-md-6">
                <div class="card card-mobile p-3 bg-white shadow-sm border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-rose-light text-rose border border-rose-subtle mb-1"><?= esc($a['unit']) ?> - Kelas <?= esc($a['kelas']) ?></span>
                            <h6 class="fw-bold text-dark mb-0"><?= esc($a['nama_pdbk']) ?></h6>
                            <small class="text-muted"><i class="fas fa-brain me-1"></i> IQ: <?= esc($a['keterangan_iq'] ?? '-') ?></small>
                        </div>
                        <a href="/gpk/jurnal/tambah/<?= $a['id'] ?>" class="btn btn-rose btn-sm px-3 py-2 fw-bold shadow-sm">
                            <i class="fas fa-plus me-1"></i> Isi Jurnal
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="text-center py-5 bg-white rounded-4 border">
        <i class="fas fa-user-slash fa-3x text-muted mb-2"></i>
        <p class="small text-muted mb-0">Belum ada anak dampingan yang terdaftar.</p>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>