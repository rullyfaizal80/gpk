<?= $this->extend('layouts/mobile_layout') ?>

<?= $this->section('content') ?>
<style>
    .text-rose { color: #e11d48 !important; }
    .bg-rose { background-color: #e11d48 !important; color: white; }
    .bg-rose-light { background-color: #fff1f2 !important; }
    .border-rose-subtle { border-color: #fecdd3 !important; }

    .card-jurnal-mobile {
        border-radius: 14px;
        transition: transform 0.15s ease;
    }
    .card-jurnal-mobile:active {
        transform: scale(0.98);
    }
</style>

<!-- Header Salam Orang Tua -->
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h5 class="fw-bold mb-0 text-rose"><i class="fas fa-heart me-1"></i> Jurnal Ananda</h5>
        <small class="text-muted">Pantau perkembangan harian buah hati</small>
    </div>
    <span class="badge bg-rose-light text-rose border border-rose-subtle px-2 py-1">Orang Tua</span>
</div>

<?php if(!empty($anak)): ?>
    <!-- Card Profil Ananda -->
    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-rose-light text-rose rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; min-width: 50px;">
                <i class="fas fa-child fa-2x"></i>
            </div>
            <div class="flex-grow-1 overflow-hidden">
                <span class="badge bg-rose-light text-rose border border-rose-subtle mb-1"><?= esc($anak['unit']) ?> - Kelas <?= esc($anak['kelas']) ?></span>
                <h6 class="fw-bold text-dark mb-0 text-truncate"><?= esc($anak['nama_pdbk']) ?></h6>
                <small class="text-muted d-block text-truncate"><i class="fas fa-user-circle me-1"></i> GPK: <?= esc($anak['nama_gpk'] ?? 'Guru Pendamping') ?></small>
            </div>
        </div>
    </div>

    <!-- Filter Bulan & Tahun -->
    <form method="get" action="/ortu/dashboard" class="card card-mobile p-2 bg-white shadow-sm border-0 mb-3">
        <div class="row g-2">
            <div class="col-6">
                <select name="bulan" class="form-select form-select-sm border-0 bg-light fw-bold text-secondary" onchange="this.form.submit()">
                    <?php 
                    $namaBulan = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
                    foreach($namaBulan as $k => $v): 
                    ?>
                        <option value="<?= $k ?>" <?= ($bulanAktif == $k) ? 'selected' : '' ?>><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6">
                <select name="tahun" class="form-select form-select-sm border-0 bg-light fw-bold text-secondary" onchange="this.form.submit()">
                    <?php for($y = date('Y'); $y >= date('Y')-2; $y--): ?>
                        <option value="<?= $y ?>" <?= ($tahunAktif == $y) ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>
    </form>

    <!-- List Jurnal Ananda -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="fw-bold text-dark mb-0"><i class="fas fa-book-open text-rose me-1"></i> Catatan Harian</h6>
        <small class="text-muted">Total: <?= count($jurnalList) ?> Catatan</small>
    </div>

    <?php if(!empty($jurnalList)): ?>
        <div class="d-flex flex-column gap-2 mb-4">
            <?php foreach($jurnalList as $j): ?>
                <a href="/ortu/jurnal/detail/<?= $j['id'] ?>" class="card card-mobile p-3 bg-white shadow-sm border-0 card-jurnal-mobile text-decoration-none text-dark">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-rose fw-bold" style="font-size: 0.75rem;">
                            <i class="far fa-calendar-alt me-1"></i> <?= date('d F Y', strtotime($j['tanggal'])) ?>
                        </small>
                        <?php if(!empty($j['foto_sampul'])): ?>
                            <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.65rem;"><i class="fas fa-camera me-1"></i> Ada Dokumentasi</span>
                        <?php endif; ?>
                    </div>

                    <h6 class="fw-bold mb-1 text-dark text-truncate">
                        <?= esc(($j['jenis_form'] == 'peril') ? ($j['peril_kegiatan'] ?? 'Kegiatan Harian') : ($j['akademik_mapel'] ?? 'Akademik')) ?>
                    </h6>
                    
                    <small class="text-muted d-block text-truncate" style="font-size: 0.8rem;">
                        <?= esc(($j['jenis_form'] == 'peril') ? ($j['peril_catatan_kegiatan'] ?? '-') : ($j['akademik_materi'] ?? '-')) ?>
                    </small>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5 bg-white rounded-4 border border-dashed mb-4">
            <i class="fas fa-folder-open fa-3x text-muted mb-2"></i>
            <p class="small text-muted mb-0">Belum ada catatan jurnal pada bulan <?= $namaBulan[$bulanAktif] ?> <?= $tahunAktif ?>.</p>
        </div>
    <?php endif; ?>

<?php else: ?>
    <div class="text-center py-5 bg-white rounded-4 border border-dashed mb-4 p-4">
        <i class="fas fa-exclamation-circle fa-3x text-warning mb-3"></i>
        <h6 class="fw-bold mb-1">Akun Belum Terhubung</h6>
        <p class="small text-muted mb-0">Akun ini belum terhubung dengan data siswa. Silakan hubungi pihak sekolah atau GPK untuk memverifikasi akun Anda.</p>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>