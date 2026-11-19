<?= $this->extend('layouts/mobile_layout') ?>

<?= $this->section('content') ?>
<style>
    .text-rose { color: #e11d48 !important; }
    
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
    
    .thumb-box {
        width: 65px;
        height: 65px;
        min-width: 65px;
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

<!-- Header Ringkas: Tombol Back + Nama Anak -->
<div class="d-flex align-items-center mb-3">
    <a href="/gpk/jurnal-anak" class="text-secondary me-3">
        <i class="fas fa-arrow-left fs-5"></i>
    </a>
    <div>
        <h5 class="fw-bold mb-0 text-rose"><?= esc($anak['nama_pdbk']) ?></h5>
        <small class="text-muted">Riwayat Jurnal Harian (<?= esc($anak['unit']) ?> - Kelas <?= esc($anak['kelas']) ?>)</small>
    </div>
</div>

<!-- Form Filter Bulan & Tahun -->
<form method="get" action="/gpk/jurnal" class="card card-mobile p-2 bg-white shadow-sm border-0 mb-3">
    <input type="hidden" name="pdbk_id" value="<?= $anak['id'] ?>">
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

<!-- Daftar Kartu Jurnal Anak Ini -->
<?php if(!empty($jurnalList)): ?>
    <div class="d-flex flex-column gap-2 mb-4">
        <?php foreach($jurnalList as $j): ?>
            <a href="/gpk/jurnal/detail/<?= $j['id'] ?>?from=jurnal_anak" class="card card-jurnal bg-white p-3 shadow-sm">
                <div class="d-flex align-items-center gap-3">
                    
                    <!-- Thumbnail Foto Google Drive -->
                    <div class="thumb-box d-flex align-items-center justify-content-center">
                        <?php if(!empty($j['foto_sampul'])): ?>
                            <img loading="lazy" src="<?= base_url('gpk/renderFoto/' . esc($j['foto_sampul'])) ?>" alt="Dokumentasi">
                        <?php else: ?>
                            <div class="text-rose text-center">
                                <i class="fas fa-file-alt fa-lg"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Ringkasan Isi Jurnal (Tanpa Badge PERIL/STANDARD) -->
                    <div class="flex-grow-1 overflow-hidden">
                        <!-- Baris 1: Tanggal Jurnal -->
                        <small class="text-muted fw-bold d-block mb-1" style="font-size: 0.75rem;">
                            <i class="far fa-calendar-alt me-1 text-rose"></i> <?= date('d F Y', strtotime($j['tanggal'])) ?>
                        </small>

                        <!-- Baris 2: Judul Kegiatan / Mata Pelajaran (Mewakili Isi) -->
                        <h6 class="fw-bold text-dark mb-1 text-truncate" style="font-size: 0.9rem;">
                            <?php if($j['jenis_form'] == 'peril'): ?>
                                <i class="fas fa-child text-warning me-1"></i> <?= esc($j['peril_kegiatan'] ?? 'Kegiatan Harian') ?>
                            <?php else: ?>
                                <i class="fas fa-book text-primary me-1"></i> <?= esc($j['akademik_mapel'] ?? 'Akademik') ?>
                            <?php endif; ?>
                        </h6>

                        <!-- Baris 3: Ringkasan Singkat Respons Anak -->
                        <small class="text-muted d-block text-truncate" style="font-size: 0.75rem;">
                            <?= esc($j['peril_catatan_kegiatan'] ?? $j['akademik_materi'] ?? '-') ?>
                        </small>
                    </div>

                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="text-center py-5 bg-white rounded-4 border border-dashed mb-4">
        <i class="fas fa-folder-open fa-3x text-muted mb-2"></i>
        <p class="small text-muted mb-0">Belum ada jurnal harian pada bulan <?= $namaBulan[$bulanAktif] ?> <?= $tahunAktif ?>.</p>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>