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

<!-- Header Salam Orang Tua -->
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h5 class="fw-bold mb-0 text-rose"><i class="fas fa-heart me-1"></i> Jurnal Ananda</h5>
        <small class="text-muted">Pantau perkembangan harian buah hati</small>
    </div>
    <span class="badge bg-rose-light text-rose border border-rose-subtle px-2 py-1">Orang Tua</span>
</div>

<?php 
// Normalisasi data anak jika dikirim sebagai single array
$listAnak = [];
if (!empty($anak)) {
    $listAnak = isset($anak[0]) ? $anak : [$anak];
}
?>

<?php if(!empty($listAnak)): ?>
    <!-- Card Profil Ananda (Menampilkan Semua Anak Terhubung) -->
    <div class="d-flex flex-column gap-2 mb-3">
        <?php foreach($listAnak as $a): ?>
            <div class="card card-mobile p-3 bg-white shadow-sm border-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-rose-light text-rose rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; min-width: 50px;">
                        <i class="fas fa-child fa-2x"></i>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <span class="badge bg-rose-light text-rose border border-rose-subtle mb-1"><?= esc($a['unit']) ?> - Kelas <?= esc($a['kelas']) ?></span>
                        <h6 class="fw-bold text-dark mb-0 text-truncate"><?= esc($a['nama_pdbk']) ?></h6>
                        <small class="text-muted d-block text-truncate"><i class="fas fa-user-circle me-1"></i> GPK: <?= esc($a['nama_gpk'] ?? 'Guru Pendamping') ?></small>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Filter List Jurnal Khusus Minggu Ini (Senin - Minggu) -->
    <?php
    $jurnalMingguIni = [];
    if (!empty($jurnalList)) {
        $startOfWeek = strtotime('monday this week');
        $endOfWeek   = strtotime('sunday this week 23:59:59');

        foreach ($jurnalList as $j) {
            $tglJurnal = strtotime($j['tanggal']);
            if ($tglJurnal >= $startOfWeek && $tglJurnal <= $endOfWeek) {
                $jurnalMingguIni[] = $j;
            }
        }
    }
    ?>

    <!-- Header List Jurnal -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="fw-bold text-dark mb-0"><i class="fas fa-book-open text-rose me-1"></i> Jurnal Minggu Ini</h6>
        <small class="text-muted">Total: <?= count($jurnalMingguIni) ?> Catatan</small>
    </div>

    <!-- Daftar Kartu Jurnal Ortu (4 Baris Terstruktur Disamakan dengan GPK) -->
    <?php if(!empty($jurnalMingguIni)): ?>
        <div class="d-flex flex-column gap-2 mb-4">
            <?php foreach($jurnalMingguIni as $j): ?>
                <a href="/ortu/jurnal/detail/<?= $j['id'] ?>" class="card card-jurnal bg-white p-3 shadow-sm">
                    
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

                   <!-- Baris 2: Nama Anak Dampingan -->
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
            <p class="small text-muted mb-0">Belum ada riwayat jurnal pada minggu ini.</p>
        </div>
    <?php endif; ?>

<?php else: ?>
    <div class="text-center py-5 bg-white rounded-4 border border-dashed mb-4 p-4">
        <i class="fas fa-exclamation-circle fa-3x text-warning mb-3"></i>
        <h6 class="fw-bold mb-1">Akun Belum Terhubung</h6>
        <p class="small text-muted mb-0">Akun ini belum terhubung dengan data siswa. Silakan hubungi pihak sekolah atau GPK untuk memverifikasi akun Anda.</p>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>