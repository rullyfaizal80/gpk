<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-journal-whills text-primary me-2"></i> Monitoring Jurnal Harian GPK</h1>
</div>

<!-- Filter Bulan, Tahun, dan Unit -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body bg-light rounded">
        <form method="get" action="/admin/jurnal" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold text-secondary">Bulan</label>
                <select name="bulan" class="form-select">
                    <?php 
                    $namaBulan = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
                    foreach($namaBulan as $k => $v): 
                    ?>
                        <option value="<?= $k ?>" <?= ($bulan == $k) ? 'selected' : '' ?>><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-secondary">Tahun</label>
                <select name="tahun" class="form-select">
                    <?php for($y = date('Y'); $y >= date('Y')-2; $y--): ?>
                        <option value="<?= $y ?>" <?= ($tahun == $y) ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-secondary">Filter Unit</label>
                <select name="unit" class="form-select">
                    <option value="">-- Semua Unit --</option>
                    <option value="PERIL" <?= ($unit == 'PERIL') ? 'selected' : '' ?>>PERIL (PG-RA)</option>
                    <option value="MI" <?= ($unit == 'MI') ? 'selected' : '' ?>>MI</option>
                    <option value="MTs" <?= ($unit == 'MTs') ? 'selected' : '' ?>>MTs</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100 fw-bold">
                    <i class="fas fa-filter me-1"></i> Terapkan Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Riwayat Jurnal Harian -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
        <span><i class="fas fa-list text-primary me-2"></i> Daftar Entri Jurnal Harian</span>
        <span class="badge bg-primary rounded-pill">Total: <?= count($jurnalList) ?> Entri</span>
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
                            <!-- Bersih tanpa label PERIL/Akademik -->
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
                                <a href="/admin/jurnal/detail/<?= $j['id'] ?>" class="btn btn-sm btn-outline-primary">
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
</div>
<?= $this->endSection() ?>