<?= $this->extend('layouts/mobile_layout') ?>

<?= $this->section('content') ?>
<style>
    .text-rose { color: #e11d48 !important; }
    .bg-rose { background-color: #e11d48 !important; color: white; }
    .bg-rose-light { background-color: #fff1f2 !important; }
    .border-rose-subtle { border-color: #fecdd3 !important; }

    .preview-grid-mobile {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
    }
    .preview-item-mobile {
        aspect-ratio: 1;
        overflow: hidden;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }
    .preview-item-mobile img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<!-- Header Back -->
<div class="d-flex align-items-center mb-3">
    <a href="/pimpinan/jurnal" class="text-secondary me-3">
        <i class="fas fa-arrow-left fs-5"></i>
    </a>
    <div>
        <h5 class="fw-bold mb-0 text-rose">Detail Jurnal Harian</h5>
        <small class="text-muted"><?= date('d F Y', strtotime($jurnal['tanggal'])) ?></small>
    </div>
</div>

<!-- Card Identitas PDBK & GPK -->
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

<?php if($jurnal['jenis_form'] === 'peril'): ?>
    <!-- FORM PERIL LENGKAP -->
    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-3"><i class="fas fa-child me-2"></i>1. Kegiatan Pembelajaran</h6>
        <div class="mb-2">
            <small class="fw-bold text-secondary d-block">Kegiatan yang Diikuti:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['peril_kegiatan']) ?></p>
        </div>
        <div class="mb-2">
            <small class="fw-bold text-secondary d-block">Tingkat Partisipasi:</small>
            <span class="badge bg-info text-dark small"><?= esc($jurnal['peril_partisipasi']) ?></span>
        </div>
        <div>
            <small class="fw-bold text-secondary d-block">Catatan & Respons Ananda:</small>
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
            <small class="fw-bold text-secondary d-block">Kebersihan Diri & Toilet:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['peril_kebersihan_toilet'] ?? '-') ?></p>
        </div>
        <div class="mb-2">
            <small class="fw-bold text-secondary d-block">Merapikan Barang:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['peril_merapikan_barang'] ?? '-') ?></p>
        </div>
        <div>
            <small class="fw-bold text-secondary d-block">Bantuan Diperlukan:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['peril_bantuan_harian'] ?? '-') ?></p>
        </div>
    </div>

    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-3"><i class="fas fa-comments me-2"></i>3. Komunikasi & Interaksi Sosial</h6>
        <div class="mb-2">
            <small class="fw-bold text-secondary d-block">Penyampaian Keinginan / Instruksi:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['peril_komunikasi'] ?? '-') ?></p>
        </div>
        <div>
            <small class="fw-bold text-secondary d-block">Interaksi Guru & Teman:</small>
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
            <small class="fw-bold text-secondary d-block">Respons Perpindahan Kegiatan:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['peril_respon_arahan'] ?? '-') ?></p>
        </div>
        <div>
            <small class="fw-bold text-secondary d-block">Strategi / Dukungan Membantu:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['peril_strategi_dukungan'] ?? '-') ?></p>
        </div>
    </div>

    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-3"><i class="fas fa-star me-2"></i>5. Apresiasi & Info Ortu</h6>
        <div class="mb-2">
            <small class="fw-bold text-secondary d-block">Pencapaian Positif:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['peril_pencapaian_positif'] ?? '-') ?></p>
        </div>
        <div class="mb-2">
            <small class="fw-bold text-secondary d-block">Keterampilan Perlu Distimulasi:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['peril_perlu_stimulasi'] ?? '-') ?></p>
        </div>
        <div class="mb-2">
            <small class="fw-bold text-secondary d-block">Informasi Khusus Orang Tua:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['peril_info_ortu'] ?? '-') ?></p>
        </div>
        <div>
            <small class="fw-bold text-secondary d-block">Saran Stimulasi di Rumah:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['peril_stimulasi_rumah'] ?? '-') ?></p>
        </div>
    </div>

<?php else: ?>
    <!-- FORM STANDARD LENGKAP -->
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
            <small class="fw-bold text-secondary d-block">Catatan Kegiatan (Respons Anak):</small>
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
            <small class="fw-bold text-secondary d-block">Keterlibatan Kegiatan di Kelas:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['daily_keterlibatan'] ?? '-') ?></p>
        </div>
    </div>

    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-3"><i class="fas fa-seedling me-2"></i>3. Aspek Perkembangan</h6>
        <div class="mb-2">
            <small class="fw-bold text-secondary d-block">Aspek yang Distimulasi:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['perkembangan_aspek'] ?? '-') ?></p>
        </div>
        <div class="mb-2">
            <small class="fw-bold text-secondary d-block">Uraian Kegiatan:</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['perkembangan_kegiatan'] ?? '-') ?></p>
        </div>
        <div>
            <small class="fw-bold text-secondary d-block">Catatan Kegiatan (Respons Anak):</small>
            <p class="small text-dark mb-0"><?= esc($jurnal['perkembangan_catatan'] ?? '-') ?></p>
        </div>
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
                            <!-- Mengarahkan ke gpk/renderFoto agar pasti ter-load lewat cache lokal -->
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

<!-- MODAL ZOOM-IN GAMBAR (Di-copy dari gpk/jurnal_detail) -->
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