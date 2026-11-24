<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('sidebar_menu') ?>
    <li class="nav-item"><a class="nav-link active" href="/admin/dashboard"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
    <li class="nav-item"><a class="nav-link" href="/admin/peserta-didik"><i class="fas fa-user-graduate me-2"></i> Data Peserta Didik</a></li>
    <li class="nav-item"><a class="nav-link" href="/admin/user"><i class="fas fa-users me-2"></i> Kelola User</a></li>
    <li class="nav-item"><a class="nav-link" href="/admin/jurnal"><i class="fas fa-journal-whills me-2"></i> Monitoring Jurnal</a></li>
    <li class="nav-item"><a class="nav-link" href="/admin/jadwal-notif"><i class="fas fa-calendar-alt me-2"></i> Jadwal Notifikasi</a></li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2 fw-bold">Dashboard Admin</h1>
    </div>

    <!-- JUDUL STATISTIK SISTEM GPK -->
    <div class="d-flex justify-content-between align-items-center mb-3 mt-2">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="fas fa-chart-pie text-primary me-2"></i> Ringkasan Sistem GPK
        </h5>
        <span class="badge bg-primary px-3 py-2 fs-6 rounded-pill shadow-sm">
            <i class="fas fa-calendar-day me-1"></i> <?= date('F Y') ?>
        </span>
    </div>

    <!-- KARTU STATISTIK ADMIN -->
    <div class="row mb-4">
        
        <!-- Card 1: Total GPK -->
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary h-100 shadow-sm border-0">
                <div class="card-body">
                    <h6 class="card-title fw-bold"><i class="fas fa-user-tie me-1"></i> Total GPK</h6>
                    <p class="card-text display-5 fw-bold mb-0"><?= $totalGpk ?></p>
                    <small>Guru Pendamping Khusus</small>
                </div>
            </div>
        </div>
        
        <!-- Card 2: Total PDBK (Anak Dampingan) -->
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-info h-100 shadow-sm border-0">
                <div class="card-body">
                    <h6 class="card-title fw-bold"><i class="fas fa-child me-1"></i> Total PDBK</h6>
                    <p class="card-text display-5 fw-bold mb-0"><?= $totalPdbk ?></p>
                    <small>Peserta Didik</small>
                </div>
            </div>
        </div>

        <!-- Card 3: Jurnal Bulan Ini -->
        <div class="col-md-3 mb-3">
            <div class="card text-dark bg-warning h-100 shadow-sm border-0">
                <div class="card-body">
                    <h6 class="card-title fw-bold"><i class="fas fa-calendar-alt me-1"></i> Jurnal Bulan Ini</h6>
                    <p class="card-text display-5 fw-bold mb-0"><?= $jurnalBulanIni ?></p>
                    <small>Entri Jurnal Terisi</small>
                </div>
            </div>
        </div>
        
        <!-- Card 4: Total Seluruh Jurnal -->
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success h-100 shadow-sm border-0">
                <div class="card-body">
                    <h6 class="card-title fw-bold"><i class="fas fa-book-open me-1"></i> Total Jurnal</h6>
                    <p class="card-text display-5 fw-bold mb-0"><?= $totalJurnal ?></p>
                    <small>Keseluruhan Riwayat</small>
                </div>
            </div>
        </div>
        
    </div>

    <!-- MATRIKS MONITORING PENGISIAN HARIAN (GANTI INFORMASI SISTEM) -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="fas fa-calendar-check text-success me-2"></i> Monitoring Pengisian Jurnal Pertanggal
        </h5>
    </div>

    <!-- Form Filter Bulan, Tahun, Unit -->
    <div class="card p-3 bg-light border-0 mb-3 shadow-sm">
        <form method="get" action="/admin/dashboard" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold mb-1">Bulan</label>
                <select name="bulan" class="form-select form-select-sm">
                    <?php 
                    $namaBulan = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
                    foreach($namaBulan as $k => $v): ?>
                        <option value="<?= $k ?>" <?= (($bulanAktif ?? date('m')) == $k) ? 'selected' : '' ?>><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold mb-1">Tahun</label>
                <select name="tahun" class="form-select form-select-sm">
                    <?php for($y = date('Y'); $y >= date('Y')-2; $y--): ?>
                        <option value="<?= $y ?>" <?= (($tahunAktif ?? date('Y')) == $y) ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold mb-1">Unit</label>
                <select name="unit" class="form-select form-select-sm">
                    <option value="">Semua Unit</option>
                    <option value="PERIL" <?= (($unitFilter ?? '') == 'PERIL') ? 'selected' : '' ?>>PERIL</option>
                    <option value="MI" <?= (($unitFilter ?? '') == 'MI') ? 'selected' : '' ?>>MI</option>
                    <option value="MTs" <?= (($unitFilter ?? '') == 'MTs') ? 'selected' : '' ?>>MTs</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-filter me-1"></i> Filter</button>
            </div>
        </form>
    </div>

    <!-- Petunjuk Keterangan Warna Status -->
    <div class="d-flex flex-wrap gap-3 mb-2 small fw-bold text-dark">
        <span><i class="fas fa-square text-success me-1"></i> Lengkap</span>
        <span><i class="fas fa-square text-warning me-1"></i> Parsial (Sebagian)</span>
        <span><i class="fas fa-square text-danger me-1"></i> Kosong (Belum Mengisi)</span>
        <span><i class="fas fa-square text-secondary me-1"></i> Libur / Non-Aktif</span>
    </div>

    <!-- Tabel Matriks Grid -->
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
                            <td colspan="<?= count($mapTanggal ?? []) + 2 ?>" class="py-4 text-muted">Data GPK atau jadwal pengisian tidak ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?= $this->endSection() ?>