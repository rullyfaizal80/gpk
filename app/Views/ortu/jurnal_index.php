<?= $this->extend('layouts/mobile_layout') ?>

<?= $this->section('content') ?>
<style>
    .text-rose { color: #e11d48 !important; }
    .bg-rose-light { background-color: #fff1f2 !important; }
    .border-rose-subtle { border-color: #fecdd3 !important; }
    .card-jurnal-mobile:active { transform: scale(0.98); }
</style>

<div class="d-flex align-items-center mb-3">
    <a href="/ortu/dashboard" class="text-secondary me-3">
        <i class="fas fa-arrow-left fs-5"></i>
    </a>
    <div>
        <h5 class="fw-bold mb-0 text-rose">Jurnal Perkembangan</h5>
        <small class="text-muted"><?= esc($anak['nama_pdbk']) ?> (<?= esc($anak['unit']) ?> - Kelas <?= esc($anak['kelas']) ?>)</small>
    </div>
</div>

<form method="get" action="/ortu/jurnal" class="card card-mobile p-2 bg-white shadow-sm border-0 mb-3">
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
        <p class="small text-muted mb-0">Belum ada jurnal pada bulan <?= $namaBulan[$bulanAktif] ?> <?= $tahunAktif ?>.</p>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>