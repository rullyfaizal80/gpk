<?= $this->extend('layouts/mobile_layout') ?>

<?= $this->section('content') ?>
<style>
    .text-rose { color: #e11d48 !important; }
    .bg-rose { background-color: #e11d48 !important; color: white; }
    .bg-rose-light { background-color: #fff1f2 !important; }
    .border-rose-subtle { border-color: #fecdd3 !important; }
</style>

<?php 
    // Pengecekan parameter 'from' dari URL
    $from = $_GET['from'] ?? '';
    if ($from === 'jurnal_anak') {
        $backUrl = base_url('gpk/jurnal?pdbk_id=' . $jurnal['pdbk_id']);
    } else {
        $backUrl = base_url('gpk/dashboard');
    }
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

<!-- Header Identitas PDBK -->
<div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <span class="badge bg-rose-light text-rose border border-rose-subtle mb-1"><?= esc($jurnal['unit']) ?> - Kelas <?= esc($jurnal['kelas']) ?></span>
            <h6 class="fw-bold text-dark mb-0"><?= esc($jurnal['nama_pdbk']) ?></h6>
            <small class="text-muted"><i class="fas fa-brain me-1"></i> IQ: <?= esc($jurnal['keterangan_iq'] ?? '-') ?></small>
        </div>
    </div>
</div>

<?php if($jurnal['jenis_form'] == 'peril'): ?>
    <!-- ================= RINCIAN JURNAL PERIL ================= -->
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
        <p class="small mb-1"><strong>Info Ortu:</strong> <?= esc($jurnal['peril_info_ortu'] ?? '-') ?></p>
        <p class="small mb-0"><strong>Saran Rumah:</strong> <?= esc($jurnal['peril_stimulasi_rumah'] ?? '-') ?></p>
    </div>

<?php else: ?>
    <!-- ================= RINCIAN JURNAL MI & MTS ================= -->
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

<!-- TOMBOL AKSI EDIT & HAPUS -->
<div class="d-flex gap-2 mb-5">
    <a href="/gpk/jurnal/edit/<?= $jurnal['id'] ?>" class="btn btn-warning text-dark flex-fill py-2 fw-bold rounded-3 shadow-sm">
        <i class="fas fa-edit me-1"></i> Edit Jurnal
    </a>
    
    <button type="button" class="btn btn-outline-danger flex-fill py-2 fw-bold rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalHapusJurnal">
        <i class="fas fa-trash-alt me-1"></i> Hapus Jurnal
    </button>
</div>

<!-- MODAL KONFIRMASI HAPUS -->
<div class="modal fade" id="modalHapusJurnal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow">
      <div class="modal-body text-center p-4">
        <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
        <h6 class="fw-bold mb-2">Hapus Jurnal Harian?</h6>
        <p class="small text-muted mb-4">Data jurnal dan foto dokumentasi di Google Drive akan dihapus secara permanen.</p>
        
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-light flex-fill fw-semibold py-2 rounded-3" data-bs-dismiss="modal">Batal</button>
            <form action="/gpk/jurnal/hapus/<?= $jurnal['id'] ?>" method="post" class="flex-fill">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-danger w-100 fw-bold py-2 rounded-3">Ya, Hapus</button>
            </form>
        </div>
      </div>
    </div>
  </div>
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