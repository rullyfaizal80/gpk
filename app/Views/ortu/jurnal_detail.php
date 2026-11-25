<?= $this->extend('layouts/mobile_layout') ?>

<?= $this->section('content') ?>
<style>
    .text-rose { color: #e11d48 !important; }
    .bg-rose { background-color: #e11d48 !important; color: white; }
    .bg-rose-light { background-color: #fff1f2 !important; }
    .border-rose-subtle { border-color: #fecdd3 !important; }

    .card-mobile {
        border-radius: 14px;
    }
</style>

<?php 
    // Pengecekan parameter 'from' dari URL atau HTTP_REFERER agar navigasi kembali presisi
    $from = $_GET['from'] ?? '';
    if ($from === 'jurnal' || (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], '/ortu/jurnal') !== false)) {
        $backUrl = base_url('ortu/jurnal');
    } else {
        $backUrl = base_url('ortu/dashboard');
    }
?>

<!-- Header Back + Title -->
<div class="d-flex align-items-center mb-3">
    <a href="<?= $backUrl ?>" class="text-secondary me-3">
        <i class="fas fa-arrow-left fs-5"></i>
    </a>
    <div>
        <h5 class="fw-bold mb-0 text-rose">Detail Jurnal Harian</h5>
        <small class="text-muted"><i class="far fa-calendar-alt me-1 text-rose"></i> <?= date('d F Y', strtotime($jurnal['tanggal'])) ?></small>
    </div>
</div>

<!-- Header Identitas 4 Baris (Unit&Kelas -> Nama -> Label GPK -> Nama GPK) -->
<div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
    <!-- Baris 1: Unit & Kelas -->
    <div class="mb-1">
        <span class="badge bg-rose-light text-rose border border-rose-subtle">
            <?= esc($jurnal['unit']) ?> - Kelas <?= esc($jurnal['kelas']) ?>
        </span>
    </div>

    <!-- Baris 2: Nama Lengkap Anak -->
    <h6 class="fw-bold text-dark mb-2 fs-6"><?= esc($jurnal['nama_pdbk']) ?></h6>

    <!-- Baris 3: Tulisan Label Guru Pendamping -->
    <small class="text-muted d-block" style="font-size: 0.75rem;">Guru Pendamping:</small>

    <!-- Baris 4: Nama Guru Pendamping (GPK) -->
    <div class="fw-bold text-dark small mt-1">
        <i class="fas fa-user-circle me-1 text-rose"></i> <?= esc($jurnal['nama_gpk'] ?? 'Guru Pendamping') ?>
    </div>
</div>

<?php if($jurnal['jenis_form'] == 'peril'): ?>
    <!-- ========================================== -->
    <!-- RINCIAN JURNAL PERIL (6 BAGIAN LENGKAP)    -->
    <!-- ========================================== -->
    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-3"><i class="fas fa-child me-2"></i>1. Kegiatan Pembelajaran</h6>
        
        <div class="mb-2">
            <small class="fw-bold text-secondary d-block">Kegiatan yang Diikuti:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['peril_kegiatan']) ?></p>
        </div>
        
        <div class="mb-2">
            <small class="fw-bold text-secondary d-block mb-1">Tingkat Partisipasi:</small>
            <span class="badge bg-warning text-dark px-2 py-1"><?= esc($jurnal['peril_partisipasi']) ?></span>
        </div>
        
        <div>
            <small class="fw-bold text-secondary d-block">Catatan Kegiatan & Respons Ananda:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['peril_catatan_kegiatan']) ?></p>
        </div>
    </div>

    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-3"><i class="fas fa-utensils me-2"></i>2. Kemandirian & Aktivitas Harian</h6>
        
        <div class="mb-2">
            <small class="fw-bold text-secondary d-block">Makan & Minum:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['peril_makan_minum'] ?? '-') ?></p>
        </div>
        
        <div class="mb-2">
            <small class="fw-bold text-secondary d-block">Toilet Training:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['peril_kebersihan_toilet'] ?? '-') ?></p>
        </div>
        
        <div class="mb-2">
            <small class="fw-bold text-secondary d-block">Merapikan Barang:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['peril_merapikan_barang'] ?? '-') ?></p>
        </div>
        
        <div>
            <small class="fw-bold text-secondary d-block">Bantuan yang Masih Diperlukan:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['peril_bantuan_harian'] ?? '-') ?></p>
        </div>
    </div>

    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-3"><i class="fas fa-comments me-2"></i>3. Komunikasi & Interaksi Sosial</h6>
        
        <div class="mb-2">
            <small class="fw-bold text-secondary d-block">Komunikasi:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['peril_komunikasi'] ?? '-') ?></p>
        </div>
        
        <div>
            <small class="fw-bold text-secondary d-block">Interaksi Sosial:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['peril_interaksi'] ?? '-') ?></p>
        </div>
    </div>

    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-3"><i class="fas fa-smile me-2"></i>4. Regulasi Emosi</h6>
        
        <div class="mb-2">
            <small class="fw-bold text-secondary d-block">Kondisi Emosi:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['peril_emosi'] ?? '-') ?></p>
        </div>
        
        <div class="mb-2">
            <small class="fw-bold text-secondary d-block">Respons Transisi / Perpindahan Kegiatan:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['peril_respon_arahan'] ?? '-') ?></p>
        </div>
        
        <div>
            <small class="fw-bold text-secondary d-block">Strategi Membantu:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['peril_strategi_dukungan'] ?? '-') ?></p>
        </div>
    </div>

    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-3"><i class="fas fa-star me-2"></i>5. Apresiasi & Catatan Rumah</h6>
        
        <div class="mb-2">
            <small class="fw-bold text-secondary d-block">Pencapaian Positif:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['peril_pencapaian_positif'] ?? '-') ?></p>
        </div>
        
        <div>
            <small class="fw-bold text-secondary d-block">Keterampilan Perlu Distimulasi:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['peril_perlu_stimulasi'] ?? '-') ?></p>
        </div>
    </div>

    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-3"><i class="fas fa-info-circle me-2"></i>6. Informasi Tambahan & Stimulasi di Rumah</h6>
        
        <div class="mb-2">
            <small class="fw-bold text-secondary d-block">Informasi Khusus untuk Orang Tua:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['peril_info_ortu'] ?? '-') ?></p>
        </div>
        
        <div class="mb-2">
            <small class="fw-bold text-secondary d-block">Stimulasi Sederhana di Rumah:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['peril_stimulasi_rumah'] ?? '-') ?></p>
        </div>
        
        <div>
            <small class="fw-bold text-secondary d-block">Hal Dikomunikasikan Lebih Lanjut (Ortu/Tim):</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['peril_komunikasi_lanjut'] ?? '-') ?></p>
        </div>
    </div>

<?php else: ?>
    <!-- ========================================== -->
    <!-- RINCIAN JURNAL STANDAR (MI & MTS)          -->
    <!-- ========================================== -->
    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-3"><i class="fas fa-book me-2"></i>1. Bidang Akademik</h6>
        
        <div class="mb-2">
            <small class="fw-bold text-secondary d-block">Mata Pelajaran:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['akademik_mapel']) ?></p>
        </div>
        
        <div class="mb-2">
            <small class="fw-bold text-secondary d-block">Uraian Materi:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['akademik_materi']) ?></p>
        </div>
        
        <div>
            <small class="fw-bold text-secondary d-block">Catatan Kegiatan (Respons Anak saat Materi Diberikan):</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['akademik_catatan']) ?></p>
        </div>
    </div>

    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-3"><i class="fas fa-user-check me-2"></i>2. Daily Life Skill</h6>
        
        <div class="mb-2">
            <small class="fw-bold text-secondary d-block">Toilet Training:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['daily_toilet'] ?? '-') ?></p>
        </div>
        
        <div class="mb-2">
            <small class="fw-bold text-secondary d-block">Kemandirian:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['daily_kemandirian'] ?? '-') ?></p>
        </div>
        
        <div>
            <small class="fw-bold text-secondary d-block">Keterlibatan dalam Kegiatan di Kelas:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['daily_keterlibatan'] ?? '-') ?></p>
        </div>
    </div>

    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-3"><i class="fas fa-seedling me-2"></i>3. Aspek Perkembangan</h6>
        
        <div class="mb-2">
            <small class="fw-bold text-secondary d-block">Aspek Perkembangan yang Distimulasi:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['perkembangan_aspek'] ?? '-') ?></p>
        </div>
        
        <div class="mb-2">
            <small class="fw-bold text-secondary d-block">Uraian Kegiatan:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['perkembangan_kegiatan'] ?? '-') ?></p>
        </div>
        
        <div>
            <small class="fw-bold text-secondary d-block">Catatan Kegiatan (Respons Anak saat Distimulasi):</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['perkembangan_catatan'] ?? '-') ?></p>
        </div>
    </div>
<?php endif; ?>

<!-- DOKUMENTASI FOTO GOOGLE DRIVE -->
<div class="card card-mobile p-3 bg-white mb-4 shadow-sm border-0">
    <h6 class="fw-bold text-rose mb-3"><i class="fas fa-images me-2"></i>Dokumentasi Kegiatan</h6>
    
    <?php if(!empty($fotos)): ?>
        <div class="row g-2">
            <?php foreach($fotos as $f): ?>
                <div class="col-6 col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body p-2 text-center bg-light rounded">
                            <img
                              loading="lazy"
                              src="<?= base_url('gpk/renderFoto/' . esc($f['google_file_id'])) ?>"
                              class="img-fluid rounded border shadow-sm"
                              alt="Foto Jurnal"
                              style="max-height: 180px; width: 100%; object-fit: cover; cursor: zoom-in;"
                              onclick="showImage(this.src)"
                            />
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="bg-light rounded py-4 text-center text-muted small border">
            <i class="fas fa-image fa-2x mb-2 text-secondary"></i><br />Tidak ada foto dokumentasi.
        </div>
    <?php endif; ?>
</div>

<!-- MODAL ZOOM-IN GAMBAR -->
<div class="modal fade" id="imageViewerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-transparent border-0 shadow-none">
      <div class="modal-header border-0 pb-0 justify-content-end">
        <button type="button" class="btn-close btn-close-white fs-4" data-bs-dismiss="modal" aria-label="Close" style="background-color: white; border-radius: 50%; padding: 0.5rem"></button>
      </div>
      <div class="modal-body text-center pt-2">
        <img loading="lazy" id="enlargedImage" src="" class="img-fluid rounded shadow-lg" style="max-height: 85vh; object-fit: contain" />
      </div>
    </div>
  </div>
</div>

<script>
  function showImage(src) {
    document.getElementById("enlargedImage").src = src;
    const viewerModal = new bootstrap.Modal(document.getElementById("imageViewerModal"));
    viewerModal.show();
  }
</script>

<?= $this->endSection() ?>