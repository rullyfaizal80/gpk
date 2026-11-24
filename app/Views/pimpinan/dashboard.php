<?= $this->extend('layouts/mobile_layout') ?>

<?= $this->section('content') ?>
<style>
    .text-rose { color: #e11d48 !important; }
    .bg-rose { background-color: #e11d48 !important; color: white; }
    .bg-rose-light { background-color: #fff1f2 !important; }
    .border-rose-subtle { border-color: #fecdd3 !important; }

    .stat-card-mobile {
        border-radius: 14px;
        padding: 12px 16px;
        border: none;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
</style>

<!-- Header Ringkas Mobile -->
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h5 class="fw-bold mb-0 text-rose"><i class="fas fa-chart-line me-2"></i>Dashboard Pimpinan</h5>
        <small class="text-muted"><?= date('d F Y') ?></small>
    </div>
    <span class="badge bg-rose-light text-rose border border-rose-subtle px-2 py-1">Pimpinan</span>
</div>

<!-- Ringkasan Statistik Grid Mobile (2 Kolom) -->
<div class="row g-2 mb-3">
    <div class="col-6">
        <div class="stat-card-mobile bg-white">
            <small class="text-muted d-block mb-1"><i class="fas fa-child text-primary me-1"></i> Total PDBK</small>
            <h4 class="fw-bold text-dark mb-0"><?= $totalPdbk ?></h4>
        </div>
    </div>
    <div class="col-6">
        <div class="stat-card-mobile bg-white">
            <small class="text-muted d-block mb-1"><i class="fas fa-user-tie text-info me-1"></i> Total GPK</small>
            <h4 class="fw-bold text-dark mb-0"><?= $totalGpk ?></h4>
        </div>
    </div>
    <div class="col-6">
        <div class="stat-card-mobile bg-white">
            <small class="text-muted d-block mb-1"><i class="fas fa-edit text-warning me-1"></i> Hari Ini</small>
            <h4 class="fw-bold text-dark mb-0"><?= $jurnalHariIni ?></h4>
        </div>
    </div>
    <div class="col-6">
        <div class="stat-card-mobile bg-white">
            <small class="text-muted d-block mb-1"><i class="fas fa-calendar-check text-success me-1"></i> Bulan Ini</small>
            <h4 class="fw-bold text-dark mb-0"><?= $jurnalBulanIni ?></h4>
        </div>
    </div>
</div>

<!-- MATRIKS MONITORING PENGISIAN HARIAN PERTANGGAL -->
<div class="d-flex justify-content-between align-items-center mb-2">
    <h6 class="fw-bold text-dark mb-0">
        <i class="fas fa-calendar-check text-rose me-1"></i> Monitoring Pengisian Pertanggal
    </h6>
</div>

<!-- Dropdown Filter Otomatis Reload (Tanpa Tombol Submit) -->
<div class="card p-2 bg-white border-0 mb-3 shadow-sm">
    <form method="get" action="/pimpinan/dashboard">
        <div class="row g-2">
            <div class="col-4">
                <select name="bulan" class="form-select form-select-sm border-light bg-light text-dark fw-bold" onchange="this.form.submit()">
                    <?php 
                    $namaBulan = ['01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'Mei','06'=>'Jun','07'=>'Jul','08'=>'Agu','09'=>'Sep','10'=>'Okt','11'=>'Nov','12'=>'Des'];
                    foreach($namaBulan as $k => $v): ?>
                        <option value="<?= $k ?>" <?= (($bulanAktif ?? date('m')) == $k) ? 'selected' : '' ?>><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-4">
                <select name="tahun" class="form-select form-select-sm border-light bg-light text-dark fw-bold" onchange="this.form.submit()">
                    <?php for($y = date('Y'); $y >= date('Y')-2; $y--): ?>
                        <option value="<?= $y ?>" <?= (($tahunAktif ?? date('Y')) == $y) ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-4">
                <select name="unit" class="form-select form-select-sm border-light bg-light text-dark fw-bold" onchange="this.form.submit()">
                    <option value="">Semua</option>
                    <option value="PERIL" <?= (($unitFilter ?? '') == 'PERIL') ? 'selected' : '' ?>>PERIL</option>
                    <option value="MI" <?= (($unitFilter ?? '') == 'MI') ? 'selected' : '' ?>>MI</option>
                    <option value="MTs" <?= (($unitFilter ?? '') == 'MTs') ? 'selected' : '' ?>>MTs</option>
                </select>
            </div>
        </div>
    </form>
</div>

<!-- Keterangan Warna Status -->
<div class="d-flex flex-wrap gap-2 mb-2 px-1 text-dark" style="font-size: 0.72rem;">
    <span><i class="fas fa-square text-success me-1"></i> Lengkap</span>
    <span><i class="fas fa-square text-warning me-1"></i> Parsial</span>
    <span><i class="fas fa-square text-danger me-1"></i> Kosong</span>
    <span><i class="fas fa-square text-secondary me-1"></i> Libur</span>
</div>

<!-- Tabel Matriks Grid Scrollable -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-0 table-responsive">
        <table class="table table-bordered table-sm text-center align-middle mb-0" style="font-size: 0.78rem;">
            <thead class="bg-dark text-white">
                <tr>
                    <th style="min-width: 130px;" class="text-start ps-2 py-2">Nama GPK</th>
                    <th style="width: 45px;">Anak</th>
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
                            <td class="text-start ps-2 fw-bold text-dark text-truncate" style="max-width: 130px;"><?= esc($m['nama_gpk']) ?></td>
                            <td><span class="badge bg-light text-dark border"><?= $m['total_anak'] ?></span></td>
                            <?php foreach($m['harian'] as $tgl => $h): ?>
                                <?php if($h['status'] === 'libur'): ?>
                                    <td class="table-secondary text-muted" title="<?= esc($h['keterangan']) ?>">—</td>
                                <?php elseif($h['status'] === 'lengkap'): ?>
                                    <td class="bg-success text-white fw-bold" title="Lengkap (<?= $h['terisi'] ?>/<?= $h['total'] ?>)"><i class="fas fa-check"></i></td>
                                <?php elseif($h['status'] === 'parsial'): ?>
                                    <td class="bg-warning text-dark fw-bold" title="Terisi <?= $h['terisi'] ?> dari <?= $h['total'] ?>"><?= $h['terisi'] ?>/<?= $h['total'] ?></td>
                                <?php else: ?>
                                    <td class="bg-danger text-white fw-bold" title="Belum mengisi jurnal"><i class="fas fa-times"></i></td>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?= count($mapTanggal ?? []) + 2 ?>" class="py-4 text-muted">Data GPK atau jadwal pengisian tidak ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>