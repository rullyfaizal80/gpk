<?= $this->extend('layouts/mobile_layout') ?>

<?= $this->section('content') ?>
<style>
    .text-rose { color: #e11d48 !important; }
    .bg-rose { background-color: #e11d48 !important; color: white; }
    .bg-rose-light { background-color: #fff1f2 !important; }
    .border-rose-subtle { border-color: #fecdd3 !important; }

    .card-jurnal {
        border: 1px solid #f1f5f9;
        border-radius: 14px;
        transition: all 0.2s ease-in-out;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .card-jurnal:active {
        transform: scale(0.98);
    }
</style>

<!-- Header Back + Title -->
<div class="d-flex align-items-center mb-3">
    <a href="/pimpinan/dashboard" class="text-secondary me-3">
        <i class="fas fa-arrow-left fs-5"></i>
    </a>
    <div>
        <h5 class="fw-bold mb-0 text-rose"><i class="fas fa-journal-whills me-1"></i> Monitoring Jurnal</h5>
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
                <option value="">Semua</option>
                <option value="PERIL" <?= ($unit == 'PERIL') ? 'selected' : '' ?>>PERIL</option>
                <option value="MI" <?= ($unit == 'MI') ? 'selected' : '' ?>>MI</option>
                <option value="MTs" <?= ($unit == 'MTs') ? 'selected' : '' ?>>MTs</option>
            </select>
        </div>
    </div>
</form>

<!-- Daftar Kartu Jurnal Mobile Pimpinan (5 Baris Terstruktur) -->
<?php if(!empty($jurnalList)): ?>
    <div class="d-flex flex-column gap-2 mb-4">
        <?php foreach($jurnalList as $j): ?>
            <a href="/pimpinan/jurnal/detail/<?= $j['id'] ?>" class="card card-jurnal bg-white p-3 shadow-sm">
                
                <!-- Baris 1: Tanggal Jurnal & Badge Unit/Kelas -->
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <small class="text-muted fw-bold" style="font-size: 0.75rem;">
                        <i class="far fa-calendar-alt me-1 text-rose"></i> <?= date('d F Y', strtotime($j['tanggal'])) ?>
                    </small>

                    <div>
                        <span class="badge bg-rose-light text-rose border border-rose-subtle" style="font-size: 0.65rem;">
                            <?= esc($j['unit']) ?> - Kelas <?= esc($j['kelas']) ?>
                        </span>
                    </div>
                </div>

                <!-- Baris 2: Nama Anak Dampingan (PDBK) -->
                <h6 class="fw-bold text-dark mb-1 text-truncate" style="font-size: 0.9rem;">
                    <?= esc($j['nama_pdbk']) ?>
                </h6>

                <!-- Baris 3: Ringkasan Kegiatan / Mapel Utama -->
                <small class="text-secondary d-block text-truncate fw-semibold mb-1" style="font-size: 0.78rem;">
                    <?php if($j['jenis_form'] === 'peril'): ?>
                        <i class="fas fa-child text-warning me-1"></i> Kegiatan: <?= esc($j['peril_kegiatan'] ?? 'Kegiatan Harian PERIL') ?>
                    <?php else: ?>
                        <i class="fas fa-book text-success me-1"></i> Mapel: <?= esc($j['akademik_mapel'] ?? 'Akademik') ?>
                    <?php endif; ?>
                </small>

                <!-- Baris 4: Uraian Spesifik (Partisipasi / Materi) -->
                <small class="text-dark d-block text-truncate fw-semibold mb-1" style="font-size: 0.78rem;">
                    <?php if($j['jenis_form'] === 'peril'): ?>
                        <i class="fas fa-chart-line text-info me-1"></i> Partisipasi: <span class="badge bg-warning text-dark py-0 px-1 font-normal"><?= esc($j['peril_partisipasi'] ?? 'Belum Diisi') ?></span>
                    <?php else: ?>
                        <i class="fas fa-file-alt text-primary me-1"></i> Materi: <?= esc($j['akademik_materi'] ?? '-') ?>
                    <?php endif; ?>
                </small>

                <!-- Baris 5 (Tersendiri di Paling Bawah): Status Foto & GPK Pendamping -->
                <div class="d-flex justify-content-between align-items-center pt-2 border-top mt-1" style="font-size: 0.75rem;">
                    <div>
                        <?php if(!empty($j['foto_sampul'])): ?>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size: 0.65rem;">
                                <i class="fas fa-camera me-1"></i> Ada Foto
                            </span>
                        <?php else: ?>
                            <span class="badge bg-light text-muted border px-2 py-1" style="font-size: 0.65rem;">
                                Tanpa Foto
                            </span>
                        <?php endif; ?>
                    </div>

                    <small class="text-muted fw-semibold" style="font-size: 0.75rem;">
                        <i class="fas fa-user-circle me-1 text-rose"></i> GPK: <?= esc($j['nama_gpk'] ?? '-') ?>
                    </small>
                </div>

            </a>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <!-- Tampilan Jika Jurnal Belum Ada -->
    <div class="text-center py-5 bg-white rounded-4 border border-dashed mb-4">
        <i class="fas fa-folder-open fa-3x text-muted mb-2"></i>
        <p class="small text-muted mb-0">Tidak ditemukan data jurnal pada periode ini.</p>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>