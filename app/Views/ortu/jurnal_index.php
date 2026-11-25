<?= $this->extend('layouts/mobile_layout') ?>

<?= $this->section('content') ?>
<style>
    .text-rose { color: #e11d48 !important; }
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

<!-- Header Halaman -->
<div class="d-flex align-items-center mb-3">
    <a href="/ortu/dashboard" class="text-secondary me-3">
        <i class="fas fa-arrow-left fs-5"></i>
    </a>
    <div>
        <h5 class="fw-bold mb-0 text-rose"><i class="fas fa-book-open me-1"></i> Jurnal Perkembangan</h5>
        <small class="text-muted">Riwayat jurnal harian ananda per bulan</small>
    </div>
</div>

<!-- Form Filter Bulan & Tahun -->
<form method="get" action="/ortu/jurnal" class="card card-mobile p-2 bg-white shadow-sm border-0 mb-3">
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

<!-- Daftar Kartu Jurnal (4 Baris Terstruktur Disamakan Dengan GPK) -->
<?php if(!empty($jurnalList)): ?>
    <div class="d-flex flex-column gap-2 mb-4">
        <?php foreach($jurnalList as $j): ?>
            <a href="/ortu/jurnal/detail/<?= $j['id'] ?>?from=jurnal" class="card card-jurnal bg-white p-3 shadow-sm">
                
                <!-- Baris 1: Tanggal & Badge Status Foto -->
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <small class="text-muted fw-bold" style="font-size: 0.75rem;">
                        <i class="far fa-calendar-alt me-1 text-rose"></i> <?= date('d F Y', strtotime($j['tanggal'])) ?>
                    </small>
                    
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
                </div>

                <!-- Baris 2: Nama Anak -->
                <h6 class="fw-bold text-dark mb-1 text-truncate" style="font-size: 0.9rem;">
                    <?= esc($j['nama_pdbk'] ?? 'Ananda') ?>
                </h6>

                <!-- Baris 3: Ringkasan Kegiatan / Mapel Utama -->
                <small class="text-secondary d-block text-truncate fw-semibold mb-1" style="font-size: 0.78rem;">
                    <?php if($j['jenis_form'] === 'peril'): ?>
                        <i class="fas fa-child text-warning me-1"></i> Kegiatan: <?= esc($j['peril_kegiatan'] ?? 'Kegiatan Harian PERIL') ?>
                    <?php else: ?>
                        <i class="fas fa-book text-success me-1"></i> Mapel: <?= esc($j['akademik_mapel'] ?? 'Akademik') ?>
                    <?php endif; ?>
                </small>

                <!-- Baris 4: Informasi Spesifik (Materi Akademik / Partisipasi PERIL) -->
                <small class="text-dark d-block text-truncate fw-semibold" style="font-size: 0.78rem;">
                    <?php if($j['jenis_form'] === 'peril'): ?>
                        <i class="fas fa-chart-line text-info me-1"></i> Partisipasi: <span class="badge bg-warning text-dark py-0 px-1 font-normal"><?= esc($j['peril_partisipasi'] ?? 'Belum Diisi') ?></span>
                    <?php else: ?>
                        <i class="fas fa-file-alt text-primary me-1"></i> Materi: <?= esc($j['akademik_materi'] ?? '-') ?>
                    <?php endif; ?>
                </small>

            </a>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <!-- Tampilan Jika Jurnal Belum Ada -->
    <div class="text-center py-5 bg-white rounded-4 border border-dashed mb-4">
        <i class="fas fa-folder-open fa-3x text-muted mb-2"></i>
        <p class="small text-muted mb-0">Belum ada riwayat jurnal pada bulan <?= $namaBulan[$bulanAktif] ?> <?= $tahunAktif ?>.</p>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>