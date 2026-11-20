<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center justify-content-between mb-3 pt-3">
    <div class="d-flex align-items-center">
        <a href="/admin/jurnal" class="btn btn-outline-secondary btn-sm me-3">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
        <div>
            <h4 class="fw-bold mb-0">Detail Jurnal Harian PDBK</h4>
            <small class="text-muted">ID Jurnal: #<?= $jurnal['id'] ?> | Tanggal: <?= date('d F Y', strtotime($jurnal['tanggal'])) ?></small>
        </div>
    </div>
</div>

<!-- Header Card Identitas -->
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle mb-1"><?= esc($jurnal['unit']) ?> - Kelas <?= esc($jurnal['kelas']) ?></span>
                <h5 class="fw-bold text-dark mb-1"><?= esc($jurnal['nama_pdbk']) ?></h5>
                <small class="text-muted"><i class="fas fa-brain me-1"></i> IQ: <?= esc($jurnal['keterangan_iq'] ?? '-') ?></small>
            </div>
            <div class="text-end">
                <small class="text-muted d-block">Guru Pendamping (GPK):</small>
                <span class="fw-bold text-dark"><i class="fas fa-user-tie me-1"></i> <?= esc($jurnal['nama_gpk'] ?? '-') ?></span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Kolom Utama: Narasi Lengkap Aspek Jurnal -->
    <div class="col-md-8">
        <?php if($jurnal['jenis_form'] === 'peril'): ?>
            <!-- ========================================== -->
            <!-- DETAIL FORM PERIL LENGKAP (PG - RA)        -->
            <!-- ========================================== -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white fw-bold text-danger"><i class="fas fa-child me-2"></i>1. Kegiatan Pembelajaran</div>
                <div class="card-body">
                    <p class="mb-2"><strong>Kegiatan yang Diikuti:</strong><br><?= esc($jurnal['peril_kegiatan']) ?></p>
                    <p class="mb-2"><strong>Tingkat Partisipasi:</strong> <span class="badge bg-info text-dark"><?= esc($jurnal['peril_partisipasi']) ?></span></p>
                    <p class="mb-0"><strong>Catatan Kegiatan & Respons Ananda:</strong><br><?= esc($jurnal['peril_catatan_kegiatan']) ?></p>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white fw-bold text-danger"><i class="fas fa-utensils me-2"></i>2. Kemandirian & Aktivitas Harian</div>
                <div class="card-body">
                    <p class="mb-2"><strong>Kegiatan Makan & Minum:</strong> <?= esc($jurnal['peril_makan_minum'] ?? '-') ?></p>
                    <p class="mb-2"><strong>Kebersihan Diri & Toilet:</strong> <?= esc($jurnal['peril_kebersihan_toilet'] ?? '-') ?></p>
                    <p class="mb-2"><strong>Kemandirian Merapikan Barang:</strong> <?= esc($jurnal['peril_merapikan_barang'] ?? '-') ?></p>
                    <p class="mb-0"><strong>Bantuan yang Masih Diperlukan:</strong> <?= esc($jurnal['peril_bantuan_harian'] ?? '-') ?></p>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white fw-bold text-danger"><i class="fas fa-comments me-2"></i>3. Komunikasi & Interaksi Sosial</div>
                <div class="card-body">
                    <p class="mb-2"><strong>Penyampaian Keinginan / Instruksi:</strong><br><?= esc($jurnal['peril_komunikasi'] ?? '-') ?></p>
                    <p class="mb-0"><strong>Interaksi dengan Guru & Teman:</strong><br><?= esc($jurnal['peril_interaksi'] ?? '-') ?></p>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white fw-bold text-danger"><i class="fas fa-smile me-2"></i>4. Regulasi Emosi</div>
                <div class="card-body">
                    <p class="mb-2"><strong>Kondisi Emosi Selama Kegiatan:</strong> <?= esc($jurnal['peril_emosi'] ?? '-') ?></p>
                    <p class="mb-2"><strong>Respons Perpindahan Kegiatan:</strong> <?= esc($jurnal['peril_respon_arahan'] ?? '-') ?></p>
                    <p class="mb-0"><strong>Strategi / Dukungan yang Membantu:</strong> <?= esc($jurnal['peril_strategi_dukungan'] ?? '-') ?></p>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white fw-bold text-danger"><i class="fas fa-star me-2"></i>5. Apresiasi & Informasi Ortu</div>
                <div class="card-body">
                    <p class="mb-2"><strong>Pencapaian / Usaha Positif Hari Ini:</strong><br><?= esc($jurnal['peril_pencapaian_positif'] ?? '-') ?></p>
                    <p class="mb-2"><strong>Keterampilan Perlu Distimulasi:</strong><br><?= esc($jurnal['peril_perlu_stimulasi'] ?? '-') ?></p>
                    <p class="mb-2"><strong>Informasi Khusus Orang Tua:</strong> <?= esc($jurnal['peril_info_ortu'] ?? '-') ?></p>
                    <p class="mb-0"><strong>Saran Stimulasi di Rumah:</strong><br><?= esc($jurnal['peril_stimulasi_rumah'] ?? '-') ?></p>
                </div>
            </div>

        <?php else: ?>
            <!-- ========================================== -->
            <!-- DETAIL FORM STANDARD LENGKAP (MI / MTS)    -->
            <!-- ========================================== -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white fw-bold text-primary"><i class="fas fa-book me-2"></i>1. Bidang Akademik</div>
                <div class="card-body">
                    <p class="mb-2"><strong>Mata Pelajaran:</strong> <?= esc($jurnal['akademik_mapel']) ?></p>
                    <p class="mb-2"><strong>Uraian Materi:</strong><br><?= esc($jurnal['akademik_materi']) ?></p>
                    <p class="mb-0"><strong>Catatan Kegiatan (Respons Anak):</strong><br><?= esc($jurnal['akademik_catatan']) ?></p>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white fw-bold text-primary"><i class="fas fa-user-check me-2"></i>2. Daily Life Skill</div>
                <div class="card-body">
                    <p class="mb-2"><strong>Toilet Training:</strong> <?= esc($jurnal['daily_toilet'] ?? '-') ?></p>
                    <p class="mb-2"><strong>Kemandirian:</strong> <?= esc($jurnal['daily_kemandirian'] ?? '-') ?></p>
                    <p class="mb-0"><strong>Keterlibatan Kegiatan di Kelas:</strong> <?= esc($jurnal['daily_keterlibatan'] ?? '-') ?></p>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white fw-bold text-primary"><i class="fas fa-seedling me-2"></i>3. Aspek Perkembangan</div>
                <div class="card-body">
                    <p class="mb-2"><strong>Aspek yang Distimulasi:</strong> <?= esc($jurnal['perkembangan_aspek'] ?? '-') ?></p>
                    <p class="mb-2"><strong>Uraian Kegiatan:</strong><br><?= esc($jurnal['perkembangan_kegiatan'] ?? '-') ?></p>
                    <p class="mb-0"><strong>Catatan Kegiatan (Respons Anak):</strong><br><?= esc($jurnal['perkembangan_catatan'] ?? '-') ?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Kolom Samping: Dokumentasi Foto Google Drive -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white fw-bold"><i class="fas fa-camera text-primary me-2"></i>Foto Dokumentasi Drive</div>
            <div class="card-body">
                <?php if(!empty($fotos)): ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach($fotos as $f): ?>
                            <div class="border rounded overflow-hidden shadow-sm">
                                <img loading="lazy" src="<?= base_url('gpk/renderFoto/' . esc($f['google_file_id'])) ?>" class="img-fluid w-100" alt="Dokumentasi">
                                <div class="bg-light p-2 border-top text-center">
                                    <small class="text-muted"><i class="far fa-clock me-1"></i> <?= date('d/m/Y H:i', strtotime($f['tgl_upload'])) ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-image fa-3x mb-2 text-secondary opacity-50"></i>
                        <p class="small mb-0">Tidak ada foto dokumentasi untuk jurnal ini.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>