<?= $this->extend('layouts/mobile_layout') ?>

<?= $this->section('content') ?>
<style>
    .text-rose { color: #e11d48 !important; }
    .bg-rose-light { background-color: #fff1f2 !important; }
    .border-rose-subtle { border-color: #fecdd3 !important; }
    
    .btn-rose { 
        background-color: #e11d48; 
        color: white; 
        border-radius: 10px;
        border: 1px solid #e11d48;
    }
    .btn-rose:hover { 
        background-color: #be123c; 
        color: white; 
    }
    
    .btn-outline-rose { 
        color: #e11d48; 
        border: 1px solid #e11d48; 
        border-radius: 10px;
        background-color: white;
    }
    .btn-outline-rose:hover { 
        background-color: #fff1f2; 
        color: #be123c; 
    }
</style>

<div class="mb-3">
    <h5 class="fw-bold text-rose mb-0"><i class="fas fa-user-graduate me-1"></i> Jurnal Anak Dampingan</h5>
    <small class="text-muted">Pilih siswa untuk mengunggah atau melihat jurnal harian</small>
</div>

<?php if(!empty($anakDampingan)): ?>
    <div class="d-flex flex-column gap-3 mb-4">
        <?php foreach($anakDampingan as $a): ?>
            <div class="card card-mobile p-3 bg-white shadow-sm border-0">
                
                <!-- Informatif Terstruktur 3 Baris -->
                <div class="mb-3">
                    <!-- Baris 1: Nama Anak -->
                    <h6 class="fw-bold text-dark mb-1 fs-6"><?= esc($a['nama_pdbk']) ?></h6>
                    
                    <!-- Baris 2: Unit & Kelas -->
                    <div class="mb-2">
                        <span class="badge bg-rose-light text-rose border border-rose-subtle">
                            <?= esc($a['unit']) ?> - Kelas <?= esc($a['kelas']) ?>
                        </span>
                    </div>

                    <!-- Baris 3: Keterangan IQ -->
                    <div class="small text-muted">
                        <i class="fas fa-brain me-1 text-secondary"></i> IQ: <?= esc($a['keterangan_iq'] ?? '-') ?>
                    </div>
                </div>

                <!-- Baris 4: 2 Tombol Aksi Berdampingan -->
                <div class="row g-2">
                    <div class="col-6">
                        <a href="/gpk/jurnal/tambah/<?= $a['id'] ?>" class="btn btn-rose btn-sm w-100 py-2 fw-bold shadow-sm">
                            <i class="fas fa-pen-square me-1"></i> + Isi Jurnal
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="/gpk/jurnal?pdbk_id=<?= $a['id'] ?>" class="btn btn-outline-rose btn-sm w-100 py-2 fw-semibold">
                            <i class="fas fa-book-open me-1"></i> Lihat Jurnal
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