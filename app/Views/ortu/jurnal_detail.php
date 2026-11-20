<?= $this->extend('layouts/mobile_layout') ?>

<?= $this->section('content') ?>
<style>
    .text-rose { color: #e11d48 !important; }
    .bg-rose { background-color: #e11d48 !important; color: white; }
    .bg-rose-light { background-color: #fff1f2 !important; }
    .border-rose-subtle { border-color: #fecdd3 !important; }
</style>

<div class="d-flex align-items-center mb-3">
    <a href="/ortu/dashboard" class="text-secondary me-3">
        <i class="fas fa-arrow-left fs-5"></i>
    </a>
    <div>
        <h5 class="fw-bold mb-0 text-rose">Catatan Perkembangan</h5>
        <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> <?= date('d F Y', strtotime($jurnal['tanggal'])) ?></small>
    </div>
</div>

<!-- Header Identitas -->
<div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <span class="badge bg-rose-light text-rose border border-rose-subtle mb-1"><?= esc($jurnal['unit']) ?> - Kelas <?= esc($jurnal['kelas']) ?></span>
            <h6 class="fw-bold text-dark mb-0"><?= esc($jurnal['nama_pdbk']) ?></h6>
        </div>
        <div class="text-end">
            <small class="text-muted d-block" style="font-size: 0.7rem;">Guru Pendamping:</small>
            <span class="fw-bold text-dark small"><i class="fas fa-user-circle me-1 text-rose"></i> <?= esc($jurnal['nama_gpk'] ?? '-') ?></span>
        </div>
    </div>
</div>

<?php if($jurnal['jenis_form'] == 'peril'): ?>
    <!-- RINCIAN JURNAL PERIL -->
    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-2"><i class="fas fa-child me-2"></i>1. Kegiatan Pembelajaran</h6>
        <p class="small mb-1"><strong>Kegiatan:</strong> <?= esc($jurnal['peril_kegiatan']) ?></p>
        <p class="small mb-1"><strong>Partisipasi:</strong> <span class="badge bg-warning text-dark"><?= esc($jurnal['peril_partisipasi']) ?></span></p>
        <p class="small mb-0"><strong>Catatan Respons:</strong> <?= esc($jurnal['peril_catatan_kegiatan']) ?></p>
    </div>

    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-2"><i class="fas fa-utensils me-2"></i>2. Kemandirian & Aktivitas Harian</h6>
        <p class="small mb-1"><strong>Makan/Minum:</strong> <?= esc($jurnal['peril_makan_minum'] ?? '-') ?></p>
        <p class="small mb-1"><strong>Toilet Training:</strong> <?= esc($jurnal['peril_kebersihan_toilet'] ?? '-') ?></p>
        <p class="small mb-1"><strong>Merapikan Barang:</strong> <?= esc($jurnal['peril_merapikan_barang'] ?? '-') ?></p>
        <p class="small mb-0"><strong>Bantuan Diperlukan:</strong> <?= esc($jurnal['peril_bantuan_harian'] ?? '-') ?></p>
    </div>

    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-2"><i class="fas fa-comments me-2"></i>3. Komunikasi & Interaksi Sosial</h6>
        <p class="small mb-1"><strong>Komunikasi:</strong> <?= esc($jurnal['peril_komunikasi'] ?? '-') ?></p>
        <p class="small mb-0"><strong>Interaksi:</strong> <?= esc($jurnal['peril_interaksi'] ?? '-') ?></p>
    </div>

    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-2"><i class="fas fa-smile me-2"></i>4. Regulasi Emosi</h6>
        <p class="small mb-1"><strong>Kondisi Emosi:</strong> <?= esc($jurnal['peril_emosi'] ?? '-') ?></p>
        <p class="small mb-1"><strong>Respons Transisi:</strong> <?= esc($jurnal['peril_respon_arahan'] ?? '-') ?></p>
        <p class="small mb-0"><strong>Strategi Membantu:</strong> <?= esc($jurnal['peril_strategi_dukungan'] ?? '-') ?></p>
    </div>

    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-2"><i class="fas fa-star me-2"></i>5. Apresiasi & Catatan Rumah</h6>
        <p class="small mb-1"><strong>Pencapaian Positif:</strong> <?= esc($jurnal['peril_pencapaian_positif'] ?? '-') ?></p>
        <p class="small mb-1"><strong>Perlu Distimulasi:</strong> <?= esc($jurnal['peril_perlu_stimulasi'] ?? '-') ?></p>
        <p class="small mb-1"><strong>Catatan Khusus Ortu:</strong> <?= esc($jurnal['peril_info_ortu'] ?? '-') ?></p>
        <p class="small mb-0"><strong>Saran Stimulasi di Rumah:</strong> <?= esc($jurnal['peril_stimulasi_rumah'] ?? '-') ?></p>
    </div>

<?php else: ?>
    <!-- RINCIAN JURNAL MI & MTS -->
    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-2"><i class="fas fa-book me-2"></i>1. Bidang Akademik</h6>
        <p class="small mb-1"><strong>Mata Pelajaran:</strong> <?= esc($jurnal['akademik_mapel']) ?></p>
        <p class="small mb-1"><strong>Uraian Materi:</strong> <?= esc($jurnal['akademik_materi']) ?></p>
        <p class="small mb-0"><strong>Catatan Respons:</strong> <?= esc($jurnal['akademik_catatan']) ?></p>
    </div>

    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-2"><i class="fas fa-user-check me-2"></i>2. Daily Life Skill</h6>
        <p class="small mb-1"><strong>Toilet Training:</strong> <?= esc($jurnal['daily_toilet'] ?? '-') ?></p>
        <p class="small mb-1"><strong>Kemandirian:</strong> <?= esc($jurnal['daily_kemandirian'] ?? '-') ?></p>
        <p class="small mb-0"><strong>Keterlibatan Kelas:</strong> <?= esc($jurnal['daily_keterlibatan'] ?? '-') ?></p>
    </div>

    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-2"><i class="fas fa-seedling me-2"></i>3. Aspek Perkembangan</h6>
        <p class="small mb-1"><strong>Aspek Distimulasi:</strong> <?= esc($jurnal['perkembangan_aspek'] ?? '-') ?></p>
        <p class="small mb-1"><strong>Uraian Kegiatan:</strong> <?= esc($jurnal['perkembangan_kegiatan'] ?? '-') ?></p>
        <p class="small mb-0"><strong>Catatan Respons:</strong> <?= esc($jurnal['perkembangan_catatan'] ?? '-') ?></p>
    </div>
<?php endif; ?>

<!-- FOTO DOKUMENTASI GOOGLE DRIVE -->
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