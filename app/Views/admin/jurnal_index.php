<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<style>
    .text-rose { color: #e11d48 !important; }
    .bg-rose { background-color: #e11d48 !important; color: white; }
    .btn-rose { background-color: #e11d48; color: white; border-color: #e11d48; }
    .btn-rose:hover { background-color: #be123c; color: white; border-color: #be123c; }
    .btn-outline-rose { color: #e11d48; border-color: #e11d48; }
    .btn-outline-rose:hover { background-color: #e11d48; color: white; }

    /* Custom Pagination Styling Konsisten Persis Seperti Kelola User */
    .pagination {
        margin-bottom: 0;
    }
    .pagination .page-link {
        color: #e11d48;
        background-color: #ffffff;
        transition: all 0.2s ease-in-out;
    }
    .pagination .page-link:hover {
        background-color: #fff1f2;
        color: #be123c;
    }
    .pagination .page-item.active .page-link {
        background-color: #e11d48 !important;
        border-color: #e11d48 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 10px rgba(225, 29, 72, 0.3) !important;
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 fw-bold text-rose"><i class="fas fa-journal-whills me-2"></i> Monitoring Jurnal Harian GPK</h1>
</div>

<!-- Filter Bulan, Tahun, dan Unit (Otomatis Submit saat Pilihan Berubah) -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body bg-light rounded p-3">
        <form method="get" action="/admin/jurnal" class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-secondary">Bulan</label>
                <select name="bulan" class="form-select" onchange="this.form.submit()">
                    <?php 
                    $namaBulan = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
                    foreach($namaBulan as $k => $v): 
                    ?>
                        <option value="<?= $k ?>" <?= ($bulan == $k) ? 'selected' : '' ?>><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-secondary">Tahun</label>
                <select name="tahun" class="form-select" onchange="this.form.submit()">
                    <?php for($y = date('Y'); $y >= date('Y')-2; $y--): ?>
                        <option value="<?= $y ?>" <?= ($tahun == $y) ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-secondary">Filter Unit</label>
                <select name="unit" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Unit --</option>
                    <option value="PERIL" <?= ($unit == 'PERIL') ? 'selected' : '' ?>>PERIL (PG-RA)</option>
                    <option value="MI" <?= ($unit == 'MI') ? 'selected' : '' ?>>MI</option>
                    <option value="MTs" <?= ($unit == 'MTs') ? 'selected' : '' ?>>MTs</option>
                </select>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================= -->
<!-- 1. TABEL PROGRESS PENGISIAN JURNAL PERTANGGAL (MATRIKS)    -->
<!-- ========================================================= -->
<div class="d-flex justify-content-between align-items-center mb-2">
    <h5 class="fw-bold text-dark mb-0">
        <i class="fas fa-calendar-check text-success me-2"></i> Progress Pengisian Jurnal Pertanggal (<?= $namaBulan[$bulan] ?> <?= $tahun ?>)
    </h5>
</div>

<!-- Petunjuk Keterangan Warna Status -->
<div class="d-flex flex-wrap gap-3 mb-2 small fw-bold text-dark">
    <span><i class="fas fa-square text-success me-1"></i> Lengkap</span>
    <span><i class="fas fa-square text-warning me-1"></i> Parsial (Sebagian)</span>
    <span><i class="fas fa-square text-danger me-1"></i> Kosong (Belum Mengisi)</span>
    <span><i class="fas fa-square text-secondary me-1"></i> Libur / Non-Aktif</span>
</div>

<!-- Tabel Matriks Grid Progress -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-0 table-responsive">
        <table class="table table-bordered table-sm text-center align-middle mb-0" style="font-size: 0.82rem;">
            <thead class="table-dark">
                <tr>
                    <th style="min-width: 160px;" class="text-start ps-2">Nama GPK</th>
                    <th style="width: 60px;">Anak</th>
                    <?php if(!empty($mapTanggal)): ?>
                        <?php foreach($mapTanggal as $tgl => $info): ?>
                            <th class="<?= ($info['is_aktif'] == 0) ? 'bg-secondary opacity-50' : '' ?>" title="<?= esc($info['keterangan']) ?>">
                                <?= date('d', strtotime($tgl)) ?>
                            </th>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <th>-</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($matriksData)): ?>
                    <?php foreach($matriksData as $m): ?>
                        <tr>
                            <td class="text-start ps-2 fw-bold text-dark"><?= esc($m['nama_gpk']) ?></td>
                            <td><span class="badge bg-light text-dark border"><?= $m['total_anak'] ?></span></td>
                            <?php foreach($m['harian'] as $tgl => $h): ?>
                                <?php if($h['status'] === 'libur'): ?>
                                    <td class="table-secondary text-muted" title="<?= esc($h['keterangan']) ?>">—</td>
                                <?php elseif($h['status'] === 'lengkap'): ?>
                                    <td class="bg-success text-white fw-bold" title="Lengkap (<?= $h['terisi'] ?>/<?= $h['total'] ?>)"><i class="fas fa-check"></i></td>
                                <?php elseif($h['status'] === 'parsial'): ?>
                                    <td class="bg-warning text-dark fw-bold" title="Baru terisi <?= $h['terisi'] ?> dari <?= $h['total'] ?>"><?= $h['terisi'] ?>/<?= $h['total'] ?></td>
                                <?php else: ?>
                                    <td class="bg-danger text-white fw-bold" title="Belum mengisi jurnal"><i class="fas fa-times"></i></td>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?= count($mapTanggal ?? []) + 2 ?>" class="py-4 text-muted">Data GPK atau jadwal pengisian tidak ditemukan pada bulan ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ========================================================= -->
<!-- 2. TABEL DAFTAR ENTRI JURNAL HARIAN + PAGINATION          -->
<!-- ========================================================= -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center py-3">
        <span><i class="fas fa-list text-rose me-2"></i> Daftar Entri Jurnal Harian</span>
        <span class="badge bg-rose rounded-pill">Total: <?= $totalEntries ?> Entri</span>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-muted small">
                <tr>
                    <th class="ps-3">Tanggal</th>
                    <th>Nama PDBK</th>
                    <th>Unit / Kelas</th>
                    <th>Pendamping (GPK)</th>
                    <th>Kegiatan / Mapel</th>
                    <th class="text-center">Dokumentasi</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($jurnalList)): ?>
                    <?php foreach($jurnalList as $j): ?>
                        <tr>
                            <td class="ps-3 fw-bold small text-nowrap">
                                <?= date('d/m/Y', strtotime($j['tanggal'])) ?>
                            </td>
                            <td>
                                <span class="fw-bold text-dark d-block"><?= esc($j['nama_pdbk']) ?></span>
                            </td>
                            <td>
                                <span class="badge bg-secondary"><?= esc($j['unit']) ?></span>
                                <small class="text-muted ms-1">Kelas <?= esc($j['kelas']) ?></small>
                            </td>
                            <td class="small">
                                <i class="fas fa-user-circle text-secondary me-1"></i> <?= esc($j['nama_gpk'] ?? 'GPK') ?>
                            </td>
                            <td class="small text-truncate" style="max-width: 220px;">
                                <?= esc(($j['jenis_form'] == 'peril') ? $j['peril_kegiatan'] : $j['akademik_mapel']) ?>
                            </td>
                            <td class="text-center">
                                <?php if(!empty($j['foto_sampul'])): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="fas fa-image me-1"></i> Ada Foto</span>
                                <?php else: ?>
                                    <span class="badge bg-light text-muted"><i class="fas fa-minus me-1"></i> Tanpa Foto</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="/admin/jurnal/detail/<?= $j['id'] ?>" class="btn btn-sm btn-outline-rose">
                                    <i class="fas fa-eye me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
                            Tidak ditemukan data jurnal harian pada periode/filter ini.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Render Link Pagination Bootstrap Kustom (Format Sama Persis dengan Kelola User) -->
    <?php if (isset($pager) && $pager): ?>
        <div class="card-footer bg-white border-0 py-3">
            <?= $pager->links('jurnal', 'bootstrap_full') ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>