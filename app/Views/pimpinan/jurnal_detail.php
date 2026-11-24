<?= $this->extend('layouts/mobile_layout') ?>

<?= $this->section('content') ?>
<style>
    .text-rose { color: #e11d48 !important; }
    .bg-rose { background-color: #e11d48 !important; color: white; }
    .bg-rose-light { background-color: #fff1f2 !important; }
    .border-rose-subtle { border-color: #fecdd3 !important; }
</style>

<?php 
    // Mengarahkan tombol back presisi ke Halaman Monitoring Jurnal Pimpinan
    $backUrl = base_url('pimpinan/jurnal');
?>

<!-- Header Navigasi Kembali -->
<div class="d-flex align-items-center mb-3">
    <a href="<?= $backUrl ?>" class="text-secondary me-3">
        <i class="fas fa-arrow-left fs-5"></i>
    </a>
    <div>
        <h5 class="fw-bold mb-0 text-rose">Detail Jurnal Harian</h5>
        <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> <?= date('d F Y', strtotime($jurnal['tanggal'])) ?></small>
    </div>
</div>

<!-- Header Identitas PDBK & GPK -->
<div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <span class="badge bg-rose-light text-rose border border-rose-subtle mb-1"><?= esc($jurnal['unit']) ?> - Kelas <?= esc($jurnal['kelas']) ?></span>
            <h6 class="fw-bold text-dark mb-0"><?= esc($jurnal['nama_pdbk']) ?></h6>
            <small class="text-muted"><i class="fas fa-brain me-1"></i> IQ: <?= esc($jurnal['keterangan_iq'] ?? '-') ?></small>
        </div>
        <div class="text-end">
            <small class="text-muted d-block" style="font-size: 0.7rem;">GPK Pendamping:</small>
            <span class="fw-bold text-dark small"><i class="fas fa-user-circle me-1 text-rose"></i> <?= esc($jurnal['nama_gpk'] ?? '-') ?></span>
        </div>
    </div>
</div>

<?php if($jurnal['jenis_form'] == 'peril'): ?>
    <!-- RINCIAN JURNAL PERIL (6 BAGIAN LENGKAP) -->
    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-3"><i class="fas fa-child me-2"></i>1. Kegiatan Pembelajaran</h6>
        <p class="small mb-2"><strong>Kegiatan yang Diikuti:</strong><br><?= esc($jurnal['peril_kegiatan']) ?></p>
        <p class="small mb-2"><strong>Tingkat Partisipasi:</strong> <span class="badge bg-warning text-dark"><?= esc($jurnal['peril_partisipasi']) ?></span></p>
        <p class="small mb-0"><strong>Catatan Kegiatan & Respons Ananda:</strong><br><?= esc($jurnal['peril_catatan_kegiatan']) ?></p>
    </div>

    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-3"><i class="fas fa-utensils me-2"></i>2. Kemandirian & Aktivitas Harian</h6>
        <p class="small mb-2"><strong>Kegiatan Makan & Minum:</strong><br><?= esc($jurnal['peril_makan_minum'] ?? '-') ?></p>
        <p class="small mb-2"><strong>Kebersihan Diri & Toilet:</strong><br><?= esc($jurnal['peril_kebersihan_toilet'] ?? '-') ?></p>
        <p class="small mb-2"><strong>Kemandirian Merapikan Barang:</strong><br><?= esc($jurnal['peril_merapikan_barang'] ?? '-') ?></p>
        <p class="small mb-0"><strong>Bantuan yang Masih Diperlukan:</strong><br><?= esc($jurnal['peril_bantuan_harian'] ?? '-') ?></p>
    </div>

    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-3"><i class="fas fa-comments me-2"></i>3. Komunikasi & Interaksi Sosial</h6>
        <p class="small mb-2"><strong>Cara Menyampaikan Keinginan / Instruksi:</strong><br><?= esc($jurnal['peril_komunikasi'] ?? '-') ?></p>
        <p class="small mb-0"><strong>Interaksi dengan Guru & Teman:</strong><br><?= esc($jurnal['peril_interaksi'] ?? '-') ?></p>
    </div>

    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-3"><i class="fas fa-smile me-2"></i>4. Regulasi Emosi</h6>
        <p class="small mb-2"><strong>Kondisi Emosi Selama Kegiatan:</strong><br><?= esc($jurnal['peril_emosi'] ?? '-') ?></p>
        <p class="small mb-2"><strong>Respons Arahan / Transisi Kegiatan:</strong><br><?= esc($jurnal['peril_respon_arahan'] ?? '-') ?></p>
        <p class="small mb-0"><strong>Strategi / Dukungan yang Membantu:</strong><br><?= esc($jurnal['peril_strategi_dukungan'] ?? '-') ?></p>
    </div>

    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-3"><i class="fas fa-star me-2"></i>5. Apresiasi & Perkembangan Hari Ini</h6>
        <p class="small mb-2"><strong>Pencapaian / Usaha Positif Hari Ini:</strong><br><?= esc($jurnal['peril_pencapaian_positif'] ?? '-') ?></p>
        <p class="small mb-0"><strong>Keterampilan Perlu Distimulasi:</strong><br><?= esc($jurnal['peril_perlu_stimulasi'] ?? '-') ?></p>
    </div>

    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-3"><i class="fas fa-info-circle me-2"></i>6. Informasi Tambahan & Stimulasi di Rumah</h6>
        <p class="small mb-2"><strong>Informasi Khusus untuk Orang Tua:</strong><br><?= esc($jurnal['peril_info_ortu'] ?? '-') ?></p>
        <p class="small mb-2"><strong>Stimulasi Sederhana di Rumah:</strong><br><?= esc($jurnal['peril_stimulasi_rumah'] ?? '-') ?></p>
        <p class="small mb-0"><strong>Hal Dikomunikasikan Lebih Lanjut (Ortu/Tim):</strong><br><?= esc($jurnal['peril_komunikasi_lanjut'] ?? '-') ?></p>
    </div>

<?php else: ?>
    <!-- RINCIAN JURNAL MI & MTS (STANDARD) -->
    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-3"><i class="fas fa-book me-2"></i>1. Bidang Akademik</h6>
        <p class="small mb-2"><strong>Mata Pelajaran:</strong> <?= esc($jurnal['akademik_mapel']) ?></p>
        <p class="small mb-2"><strong>Uraian Materi:</strong><br><?= esc($jurnal['akademik_materi']) ?></p>
        <p class="small mb-0"><strong>Catatan Kegiatan (Respons Anak):</strong><br><?= esc($jurnal['akademik_catatan']) ?></p>
    </div>

    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-3"><i class="fas fa-user-check me-2"></i>2. Daily Life Skill</h6>
        <p class="small mb-2"><strong>Toilet Training:</strong> <?= esc($jurnal['daily_toilet'] ?? '-') ?></p>
        <p class="small mb-2"><strong>Kemandirian:</strong> <?= esc($jurnal['daily_kemandirian'] ?? '-') ?></p>
        <p class="small mb-0"><strong>Keterlibatan Kegiatan di Kelas:</strong> <?= esc($jurnal['daily_keterlibatan'] ?? '-') ?></p>
    </div>

    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-3"><i class="fas fa-seedling me-2"></i>3. Perkembangan</h6>
        <p class="small mb-2"><strong>Aspek Distimulasi:</strong> <?= esc($jurnal['perkembangan_aspek'] ?? '-') ?></p>
        <p class="small mb-2"><strong>Uraian Kegiatan:</strong><br><?= esc($jurnal['perkembangan_kegiatan'] ?? '-') ?></p>
        <p class="small mb-0"><strong>Catatan Kegiatan (Respons Anak):</strong><br><?= esc($jurnal['perkembangan_catatan'] ?? '-') ?></p>
    </div>
<?php endif; ?>

<!-- DOKUMENTASI FOTO GOOGLE DRIVE -->
<div class="card card-mobile p-3 bg-white mb-4 shadow-sm border-0">
    <h6 class="fw-bold text-rose mb-3"><i class="fas fa-images me-2"></i>Foto Kegiatan / Dokumentasi</h6>
    
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
            <i class="fas fa-image fa-2x mb-2 text-secondary"></i><br />Tidak ada foto dokumentasi yang dilampirkan.
        </div>
    <?php endif; ?>
</div>

<!-- MODAL ZOOM-IN GAMBAR -->
<div class="modal fade" id="imageViewerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-transparent border-0 shadow-none">
      <div class="modal-header border-0 pb-0 justify-content-end">
        <button
          type="button"
          class="btn-close btn-close-white fs-4"
          data-bs-dismiss="modal"
          aria-label="Close"
          style="background-color: white; border-radius: 50%; padding: 0.5rem"
        ></button>
      </div>
      <div class="modal-body text-center pt-2">
        <img
          loading="lazy"
          id="enlargedImage"
          src=""
          class="img-fluid rounded shadow-lg"
          style="max-height: 85vh; object-fit: contain"
        />
      </div>
    </div>
  </div>
</div>

<script>
  function showImage(src) {
    document.getElementById("enlargedImage").src = src;
    const viewerModal = new bootstrap.Modal(
      document.getElementById("imageViewerModal")
    );
    viewerModal.show();
  }
</script>

<?= $this->endSection() ?>