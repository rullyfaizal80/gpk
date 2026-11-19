<?= $this->extend('layouts/mobile_layout') ?>

<?= $this->section('content') ?>
<style>
    .text-rose { color: #e11d48 !important; }
    .bg-rose { background-color: #e11d48 !important; color: white; }
    .bg-rose-light { background-color: #fff1f2 !important; }
    .border-rose-subtle { border-color: #fecdd3 !important; }
    
    .card-jurnal {
        border: 1px solid #f1f5f9;
        border-radius: 16px;
        transition: all 0.2s ease-in-out;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .card-jurnal:active {
        transform: scale(0.98);
    }
    
    /* Wadah Ukuran Tetap untuk Gambar Thumbnail */
    .thumb-box {
        width: 70px;
        height: 70px;
        min-width: 70px;
        border-radius: 12px;
        overflow: hidden;
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
    }
    .thumb-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<!-- Banner Header -->
<div class="card card-mobile bg-rose text-white p-3 mb-3 shadow-sm border-0" style="background: linear-gradient(135deg, #e11d48, #fb7185);">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <span class="small opacity-75">Selamat bertugas,</span>
            <h6 class="fw-bold mb-0 text-white"><?= esc($nama_gpk) ?></h6>
        </div>
        <i class="fas fa-book-reader fa-2x opacity-50"></i>
    </div>
</div>

<!-- Header Judul Riwayat -->
<div class="d-flex justify-content-between align-items-center mb-2">
    <h6 class="fw-bold text-dark mb-0"><i class="fas fa-history me-1 text-rose"></i> Riwayat Jurnal Harian</h6>
</div>

<!-- Form Filter Bulan & Tahun -->
<form method="get" action="/gpk/dashboard" class="card card-mobile p-2 bg-white shadow-sm border-0 mb-3">
    <div class="row g-2">
        <div class="col-6">
            <select name="bulan" class="form-select form-select-sm border-0 bg-light fw-bold text-secondary" onchange="this.form.submit()">
                <?php 
                $namaBulan = [
                    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                ];
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

<!-- Daftar Kartu Jurnal -->
<?php if(!empty($jurnalList)): ?>
    <div class="d-flex flex-column gap-2 mb-4">
        <?php foreach($jurnalList as $j): ?>
            <a href="/gpk/jurnal/detail/<?= $j['id'] ?>" class="card card-jurnal bg-white p-3 shadow-sm">
                <div class="d-flex align-items-center gap-3">
                    
                    <!-- Box Thumbnail Gambar -->
                    <div class="thumb-box d-flex align-items-center justify-content-center">
                        <?php if(!empty($j['foto_sampul'])): ?>
                            <img loading="lazy" src="<?= base_url('gpk/renderFoto/' . esc($j['foto_sampul'])) ?>" alt="Dokumentasi">
                        <?php else: ?>
                            <div class="text-rose text-center">
                                <i class="fas fa-file-alt fa-lg"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Informasi Jurnal -->
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge bg-rose-light text-rose border border-rose-subtle" style="font-size: 0.65rem;"><?= esc($j['unit']) ?> - Kelas <?= esc($j['kelas']) ?></span>
                            <small class="text-muted fw-semibold" style="font-size: 0.72rem;"><i class="far fa-calendar-alt me-1"></i> <?= date('d M Y', strtotime($j['tanggal'])) ?></small>
                        </div>
                        <h6 class="fw-bold text-dark mb-1 text-truncate" style="font-size: 0.9rem;"><?= esc($j['nama_pdbk']) ?></h6>
                        <small class="text-muted d-block text-truncate" style="font-size: 0.75rem;">
                            <?php if($j['jenis_form'] == 'peril'): ?>
                                <i class="fas fa-tasks text-warning me-1"></i> <?= esc($j['peril_kegiatan'] ?? 'Jurnal PERIL') ?>
                            <?php else: ?>
                                <i class="fas fa-book text-primary me-1"></i> Mapel: <?= esc($j['akademik_mapel'] ?? 'Akademik') ?>
                            <?php endif; ?>
                        </small>
                    </div>

                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="text-center py-5 bg-white rounded-4 border border-dashed mb-4">
        <i class="fas fa-folder-open fa-3x text-muted mb-2"></i>
        <p class="small text-muted mb-0">Belum ada riwayat jurnal pada bulan <?= $namaBulan[$bulanAktif] ?> <?= $tahunAktif ?>.</p>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>