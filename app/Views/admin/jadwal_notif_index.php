<?= $this->extend('layouts/mobile_layout') ?>

<?= $this->section('content') ?>
<style>
    .text-rose { color: #e11d48 !important; }
    .bg-rose { background-color: #e11d48 !important; color: white; }
    .btn-rose { background-color: #e11d48; color: white; border-radius: 10px; }
    .btn-rose:hover { background-color: #be123c; color: white; }
</style>

<!-- Header Navigasi -->
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h5 class="fw-bold mb-0 text-rose"><i class="fas fa-calendar-alt me-2"></i>Jadwal Notifikasi PWA</h5>
        <small class="text-muted">Kelola tanggal aktif pengingat & hari libur</small>
    </div>
</div>

<!-- Alert Notifikasi Flashdata -->
<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-3 small" role="alert">
        <i class="fas fa-check-circle me-1"></i> <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Card Filter Bulan & Auto Generate -->
<div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
    <!-- Form Filter Bulan/Tahun -->
    <form action="/admin/jadwal-notif" method="get" class="row g-2 align-items-center mb-3">
        <div class="col-6">
            <select name="bulan" class="form-select form-select-sm fw-bold border-light bg-light">
                <?php 
                $namaBulan = ['01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'];
                foreach($namaBulan as $k => $v): 
                ?>
                    <option value="<?= $k ?>" <?= ($bulanAktif == $k) ? 'selected' : '' ?>><?= $v ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-4">
            <select name="tahun" class="form-select form-select-sm fw-bold border-light bg-light">
                <?php for($y=date('Y')-1; $y<=date('Y')+1; $y++): ?>
                    <option value="<?= $y ?>" <?= ($tahunAktif == $y) ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-2">
            <button type="submit" class="btn btn-sm btn-rose w-100 fw-bold"><i class="fas fa-search"></i></button>
        </div>
    </form>

    <!-- Tombol Auto-Generate Bulan Ini -->
    <form action="/admin/jadwal-notif/generate" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="bulan" value="<?= $bulanAktif ?>">
        <input type="hidden" name="tahun" value="<?= $tahunAktif ?>">
        <button type="submit" class="btn btn-outline-danger btn-sm w-100 fw-bold rounded-3">
            <i class="fas fa-magic me-1"></i> Auto-Generate Hari Efektif Bulan Ini
        </button>
    </form>
</div>

<!-- Daftar Tanggal -->
<div class="card card-mobile p-3 bg-white mb-4 shadow-sm border-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold text-dark mb-0">Daftar Hari Efektif (<?= $bulanAktif ?>/<?= $tahunAktif ?>)</h6>
        <span class="badge bg-light text-secondary border"><?= count($jadwalList) ?> Tanggal</span>
    </div>

    <?php if(!empty($jadwalList)): ?>
        <div class="list-group list-group-flush">
            <?php foreach($jadwalList as $j): ?>
                <?php 
                    $hari = date('N', strtotime($j['tanggal'])); // 1 (Senin) .. 7 (Minggu)
                    $isWeekend = ($hari >= 6);
                ?>
                <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-bottom">
                    <div>
                        <strong class="<?= $isWeekend ? 'text-danger' : 'text-dark' ?>" style="font-size: 0.9rem;">
                            <?= date('d F Y', strtotime($j['tanggal'])) ?>
                        </strong>
                        <small class="d-block text-muted" style="font-size: 0.75rem;"><?= esc($j['keterangan']) ?></small>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input toggle-notif-switch" type="checkbox" role="switch"
                               data-tanggal="<?= $j['tanggal'] ?>" 
                               <?= $j['is_aktif'] ? 'checked' : '' ?>>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-4 text-muted small border border-dashed rounded-3">
            <i class="fas fa-calendar-times fa-2x mb-2 text-secondary"></i><br>
            Belum ada data jadwal untuk bulan ini.<br>Klik tombol <strong>Auto-Generate</strong> di atas untuk membuat otomatis.
        </div>
    <?php endif; ?>
</div>

<script>
document.querySelectorAll('.toggle-notif-switch').forEach(el => {
    el.addEventListener('change', function() {
        const tanggal = this.getAttribute('data-tanggal');
        const isAktif = this.checked ? 1 : 0;

        fetch('/admin/jadwal-notif/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({
                'tanggal': tanggal,
                'is_aktif': isAktif,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            })
        })
        .then(res => res.json())
        .then(data => {
            console.log('Status diubah:', data.message);
        })
        .catch(err => {
            console.error('Gagal memperbarui status:', err);
        });
    });
});
</script>
<?= $this->endSection() ?>