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

<!-- Header Back + Title -->
<div class="d-flex align-items-center mb-3">
    <a href="/pimpinan/dashboard" class="text-secondary me-3">
        <i class="fas fa-arrow-left fs-5"></i>
    </a>
    <div>
        <h5 class="fw-bold mb-0 text-rose">Monitoring Jurnal</h5>
        <small class="text-muted">Rekapitulasi Jurnal Harian PDBK</small>
    </div>
</div>

<!-- Form Filter Mobile Compact -->
<form method="get" action="/pimpinan/jurnal" class="card card-mobile p-2 bg-white shadow-sm border-0 mb-3">
    <div class="row g-2">
        <div class="col-4">
            <select name="bulan" class="form-select form-select-sm border-0 bg-light fw-bold text-secondary" onchange="this.form.submit()">
                <?php 
                $namaBulan = ['01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'];
                foreach($namaBulan as $k => $v): 
                ?>
                    <option value="<?= $k ?>" <?= ($bulan == $k) ? 'selected' : '' ?>><?= $v ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-4">
            <select name="tahun" class="form-select form-select-sm border-0 bg-light fw-bold text-secondary" onchange="this.form.submit()">
                <?php for($y = date('Y'); $y >= date('Y')-2; $y--): ?>
                    <option value="<?= $y ?>" <?= ($tahun == $y) ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-4">
            <select name="unit" class="form-select form-select-sm border-0 bg-light fw-bold text-secondary" onchange="this.form.submit()">
                <option value="">Semua Unit</option>
                <option value="PERIL" <?= ($unit == 'PERIL') ? 'selected' : '' ?>>PERIL</option>
                <option value="MI" <?= ($unit == 'MI') ? 'selected' : '' ?>>MI</option>
                <option value="MTs" <?= ($unit == 'MTs') ? 'selected' : '' ?>>MTs</option>
            </select>
        </div>
    </div>
</form>

<!-- Daftar Jurnal Mobile List Card -->
<?php if(!empty($jurnalList)): ?>
    <div class="d-flex flex-column gap-2 mb-4">
        <?php foreach($jurnalList as $j): ?>
            <a href="/pimpinan/jurnal/detail/<?= $j['id'] ?>" class="card card-mobile p-3 bg-white shadow-sm border-0 card-jurnal-mobile text-decoration-none text-dark">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div>
                        <span class="badge bg-rose-light text-rose border border-rose-subtle me-1" style="font-size: 0.7rem;"><?= esc($j['unit']) ?></span>
                        <small class="text-muted fw-bold" style="font-size: 0.75rem;">Kelas <?= esc($j['kelas']) ?></small>
                    </div>
                    <small class="text-muted" style="font-size: 0.75rem;"><i class="far fa-calendar-alt me-1 text-rose"></i><?= date('d/m/Y', strtotime($j['tanggal'])) ?></small>
                </div>

                <h6 class="fw-bold mb-1 text-dark"><?= esc($j['nama_pdbk']) ?></h6>
                
                <small class="text-muted d-block text-truncate mb-2" style="font-size: 0.8rem;">
                    <i class="fas fa-tasks me-1 text-secondary"></i> <?= esc(($j['jenis_form'] == 'peril') ? $j['peril_kegiatan'] : $j['akademik_mapel']) ?>
                </small>

                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                    <small class="text-muted" style="font-size: 0.75rem;"><i class="fas fa-user-circle me-1"></i> <?= esc($j['nama_gpk'] ?? 'GPK') ?></small>
                    <?php if(!empty($j['foto_sampul'])): ?>
                        <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.65rem;"><i class="fas fa-camera me-1"></i> Ada Foto</span>
                    <?php else: ?>
                        <span class="badge bg-light text-muted" style="font-size: 0.65rem;">Tanpa Foto</span>
                    <?php endif; ?>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="text-center py-5 bg-white rounded-4 border border-dashed mb-4">
        <i class="fas fa-folder-open fa-3x text-muted mb-2"></i>
        <p class="small text-muted mb-0">Tidak ditemukan data jurnal pada periode ini.</p>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>