<?= $this->extend('layouts/mobile_layout') ?>

<?= $this->section('content') ?>
    <style>
        .card-ortu { background: linear-gradient(135deg, #e11d48, #fb7185); color: white; }
        .text-rose { color: #e11d48 !important; }
        .bg-rose-light { background-color: #fff1f2 !important; }
    </style>

    <?php if($anak): ?>
        <!-- Card Info Ananda -->
        <div class="card card-mobile card-ortu p-3 mb-3 shadow-sm">
            <div class="d-flex align-items-center">
                <div class="p-3 bg-white rounded-circle text-rose me-3 shadow-sm">
                    <i class="fas fa-child fa-2x"></i>
                </div>
                <div>
                    <span class="badge bg-white text-dark mb-1">Perkembangan Ananda</span>
                    <h5 class="fw-bold mb-0"><?= esc($anak['nama_pdbk']) ?></h5>
                    <small class="opacity-75">Kelas <?= esc($anak['kelas']) ?> - <?= esc($anak['unit']) ?></small>
                </div>
            </div>
            <hr class="border-white opacity-25 my-2">
            <div class="d-flex justify-content-between align-items-center small opacity-90">
                <span><i class="fas fa-user-tie me-1"></i> GPK: <strong><?= esc($anak['nama_gpk'] ?? 'Pendamping') ?></strong></span>
            </div>
        </div>

        <!-- Informational Banner Jam Akses -->
        <div class="alert alert-info border-0 shadow-sm rounded-4 small p-3 mb-3">
            <i class="fas fa-info-circle me-1"></i> Jurnal narasi harian dapat diakses dan diperbarui setiap pukul <strong>19.00 WIB</strong>[cite: 11].
        </div>

        <!-- Tombol Akses Jurnal -->
        <a href="/ortu/jurnal" class="card card-mobile p-3 bg-white shadow-sm text-decoration-none text-dark d-flex align-items-center justify-content-between mb-3 border-0">
            <div class="d-flex align-items-center">
                <div class="p-3 rounded-circle bg-rose-light text-rose me-3">
                    <i class="fas fa-book-reader fa-lg"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-rose">Lihat Narasi Jurnal Ananda</h6>
                    <small class="text-muted">Baca catatan aktivitas & perkembangan harian</small>
                </div>
            </div>
            <i class="fas fa-chevron-right text-muted"></i>
        </a>
    <?php else: ?>
        <div class="card card-mobile p-4 bg-white text-center shadow-sm">
            <i class="fas fa-exclamation-circle fa-3x text-warning mb-2"></i>
            <h6 class="fw-bold">Data Ananda Belum Terhubung</h6>
            <p class="small text-muted">Akun Anda belum terhubung dengan data peserta didik. Silakan hubungi Administrator sekolah.</p>
        </div>
    <?php endif; ?>
<?= $this->endSection() ?>