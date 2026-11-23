<?= $this->extend('layouts/mobile_layout') ?>

<?= $this->section('content') ?>
<style>
    .text-rose { color: #e11d48 !important; }
    .bg-rose { background-color: #e11d48 !important; color: white; }
    .bg-rose-light { background-color: #fff1f2 !important; }
    .border-rose-subtle { border-color: #fecdd3 !important; }
    .btn-rose { background-color: #e11d48; color: white; border-radius: 12px; }
    .btn-rose:hover { background-color: #be123c; color: white; }
    
    .preview-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
    }
    .preview-item {
        position: relative;
        aspect-ratio: 1;
        overflow: hidden;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }
    .preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<!-- Header Navigasi -->
<div class="d-flex align-items-center mb-3">
    <a href="/gpk/jurnal-anak" class="text-secondary me-3">
        <i class="fas fa-arrow-left fs-5"></i>
    </a>
    <div>
        <h5 class="fw-bold mb-0 text-rose">Form Jurnal Harian</h5>
        <small class="text-muted">Unit <?= esc($anak['unit']) ?> - Kelas <?= esc($anak['kelas']) ?></small>
    </div>
</div>

<!-- FORM UTAMA -->
<form action="/gpk/jurnal/simpan" method="post" enctype="multipart/form-data" onsubmit="return validasiDanLoading(event)">
    <?= csrf_field() ?>
    <input type="hidden" name="pdbk_id" value="<?= $anak['id'] ?>">
    <input type="hidden" name="unit" value="<?= $anak['unit'] ?>">
    <input type="hidden" name="jenis_form" value="<?= ($anak['unit'] === 'PERIL') ? 'peril' : 'standard' ?>">

    <!-- Card Ringkasan PDBK & Input Tanggal -->
    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <div>
                <h6 class="fw-bold text-dark mb-0"><?= esc($anak['nama_pdbk']) ?></h6>
            </div>
        </div>
        <hr class="my-2 opacity-25">
        
        <div>
            <label for="tanggal_jurnal" class="form-label small fw-bold text-secondary mb-1">
                <i class="far fa-calendar-alt me-1 text-rose"></i> Tanggal Kegiatan *
            </label>
            <input type="date" id="tanggal_jurnal" name="tanggal" class="form-control form-control-sm fw-bold border-light bg-light" value="<?= date('Y-m-d') ?>" required>
        </div>
    </div>

    <?php if($anak['unit'] === 'PERIL'): ?>
        <!-- ========================================== -->
        <!-- FORM KHUSUS UNIT PERIL (PG - RA)           -->
        <!-- ========================================== -->
        <div class="card card-mobile p-3 bg-white mb-3 shadow-sm">
            <h6 class="fw-bold text-rose mb-3"><i class="fas fa-child me-2"></i>1. Kegiatan Pembelajaran</h6>
            <div class="mb-3">
                <label class="form-label small fw-bold">Kegiatan yang diikuti ananda hari ini *</label>
                <textarea name="peril_kegiatan" class="form-control" rows="2" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Tingkat Partisipasi *</label>
                <select name="peril_partisipasi" class="form-select" required>
                    <option value="">-- Pilih Partisipasi --</option>
                    <option value="Mandiri">Mandiri</option>
                    <option value="Dengan isyarat atau pengingat">Dengan isyarat atau pengingat</option>
                    <option value="Dengan arahan verbal">Dengan arahan verbal</option>
                    <option value="Dengan contoh langsung">Dengan contoh langsung</option>
                    <option value="Dengan bantuan fisik">Dengan bantuan fisik</option>
                    <option value="Belum bersedia mengikuti">Belum bersedia mengikuti</option>
                </select>
            </div>
            <div class="mb-0">
                <label class="form-label small fw-bold">Catatan kegiatan dan respons ananda *</label>
                <textarea name="peril_catatan_kegiatan" class="form-control" rows="2" required></textarea>
            </div>
        </div>

        <div class="card card-mobile p-3 bg-white mb-3 shadow-sm">
            <h6 class="fw-bold text-rose mb-3"><i class="fas fa-utensils me-2"></i>2. Kemandirian & Aktivitas Harian</h6>
            <div class="mb-3">
                <label class="form-label small fw-bold">Kegiatan makan dan minum *</label>
                <textarea name="peril_makan_minum" class="form-control" rows="2" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Kebersihan diri dan toilet *</label>
                <textarea name="peril_kebersihan_toilet" class="form-control" rows="2" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Kemandirian merapikan barang pribadi *</label>
                <textarea name="peril_merapikan_barang" class="form-control" rows="2" required></textarea>
            </div>
            <div class="mb-0">
                <label class="form-label small fw-bold">Bantuan yang masih diperlukan dalam aktivitas harian *</label>
                <textarea name="peril_bantuan_harian" class="form-control" rows="2" required></textarea>
            </div>
        </div>

        <div class="card card-mobile p-3 bg-white mb-3 shadow-sm">
            <h6 class="fw-bold text-rose mb-3"><i class="fas fa-comments me-2"></i>3. Komunikasi & Interaksi Sosial</h6>
            <div class="mb-3">
                <label class="form-label small fw-bold">Cara ananda menyampaikan keinginan atau merespons instruksi *</label>
                <textarea name="peril_komunikasi" class="form-control" rows="2" required></textarea>
            </div>
            <div class="mb-0">
                <label class="form-label small fw-bold">Interaksi ananda dengan guru dan teman *</label>
                <textarea name="peril_interaksi" class="form-control" rows="2" required></textarea>
            </div>
        </div>

        <div class="card card-mobile p-3 bg-white mb-3 shadow-sm">
            <h6 class="fw-bold text-rose mb-3"><i class="fas fa-smile me-2"></i>4. Regulasi Emosi</h6>
            <div class="mb-3">
                <label class="form-label small fw-bold">Kondisi emosi ananda selama kegiatan *</label>
                <textarea name="peril_emosi" class="form-control" rows="2" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Respons terhadap arahan atau perpindahan kegiatan *</label>
                <textarea name="peril_respon_arahan" class="form-control" rows="2" required></textarea>
            </div>
            <div class="mb-0">
                <label class="form-label small fw-bold">Strategi atau dukungan yang membantu ananda *</label>
                <textarea name="peril_strategi_dukungan" class="form-control" rows="2" required></textarea>
            </div>
        </div>

        <div class="card card-mobile p-3 bg-white mb-3 shadow-sm">
            <h6 class="fw-bold text-rose mb-3"><i class="fas fa-star me-2"></i>5. Apresiasi dan Perkembangan Hari Ini</h6>
            <div class="mb-3">
                <label class="form-label small fw-bold">Pencapaian atau usaha positif ananda hari ini *</label>
                <textarea name="peril_pencapaian_positif" class="form-control" rows="2" required></textarea>
            </div>
            <div class="mb-0">
                <label class="form-label small fw-bold">Keterampilan yang masih perlu distimulasi *</label>
                <textarea name="peril_perlu_stimulasi" class="form-control" rows="2" required></textarea>
            </div>
        </div>

        <div class="card card-mobile p-3 bg-white mb-3 shadow-sm">
            <h6 class="fw-bold text-rose mb-3"><i class="fas fa-info-circle me-2"></i>6. Informasi Tambahan dan Stimulasi di Rumah</h6>            
            <div class="mb-3">
                <label class="form-label small fw-bold">Informasi khusus untuk orang tua *</label>
                <textarea name="peril_info_ortu" class="form-control" rows="2" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Stimulasi sederhana yang disarankan di rumah *</label>
                <textarea name="peril_stimulasi_rumah" class="form-control" rows="2" required></textarea>
            </div>
            <div class="mb-0">
                <label class="form-label small fw-bold">Hal yang perlu dikomunikasikan lebih lanjut kepada orang tua atau tim pendamping *</label>
                <textarea name="peril_komunikasi_lanjut" class="form-control" rows="2" required></textarea>
            </div>
        </div>

    <?php else: ?>
        <!-- ========================================== -->
        <!-- FORM UNTUK UNIT MI DAN MTS (STANDARD)      -->
        <!-- ========================================== -->
        <div class="card card-mobile p-3 bg-white mb-3 shadow-sm">
            <h6 class="fw-bold text-rose mb-3"><i class="fas fa-book me-2"></i>1. Bidang Akademik</h6>
            <div class="mb-3">
                <label class="form-label small fw-bold">Mata pelajaran *</label>
                <textarea name="akademik_mapel" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Uraian materi *</label>
                <textarea name="akademik_materi" class="form-control" rows="2" required></textarea>
            </div>
            <div class="mb-0">
                <label class="form-label small fw-bold">Catatan kegiatan (Respon anak saat materi diberikan) *</label>
                <textarea name="akademik_catatan" class="form-control" rows="2" required></textarea>
            </div>
        </div>

        <div class="card card-mobile p-3 bg-white mb-3 shadow-sm">
            <h6 class="fw-bold text-rose mb-3"><i class="fas fa-user-check me-2"></i>2. Daily Life Skill</h6>
            <div class="mb-3">
                <label class="form-label small fw-bold">Toilet training *</label>
                <textarea name="daily_toilet" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Kemandirian *</label>
                <textarea name="daily_kemandirian" class="form-control" required></textarea>
            </div>
            <div class="mb-0">
                <label class="form-label small fw-bold">Keterlibatan kegiatan di kelas *</label>
                <textarea name="daily_keterlibatan" class="form-control" required></textarea>
            </div>
        </div>

        <div class="card card-mobile p-3 bg-white mb-3 shadow-sm">
            <h6 class="fw-bold text-rose mb-3"><i class="fas fa-seedling me-2"></i>3. Perkembangan</h6>
            <div class="mb-3">
                <label class="form-label small fw-bold">Aspek perkembangan yang distimulasi *</label>
                <textarea name="perkembangan_aspek" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Uraian kegiatan *</label>
                <textarea name="perkembangan_kegiatan" class="form-control" rows="2" required></textarea>
            </div>
            <div class="mb-0">
                <label class="form-label small fw-bold">Catatan kegiatan (Respon anak saat distimulasi) *</label>
                <textarea name="perkembangan_catatan" class="form-control" rows="2" required></textarea>
            </div>
        </div>
    <?php endif; ?>

    <!-- UPLOAD FOTO DOKUMENTASI -->
    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm">
        <h6 class="fw-bold text-rose mb-3"><i class="fas fa-camera me-2"></i>Foto Kegiatan (Maksimal 10 Foto)</h6>

        <input type="file" id="input_kamera" accept="image/*" capture="environment" multiple class="d-none" onchange="tangkapFoto(this)">
        <input type="file" id="input_galeri" accept="image/*" multiple class="d-none" onchange="tangkapFoto(this)">

        <div class="d-flex gap-2 mb-0">
            <label for="input_kamera" class="btn btn-outline-danger flex-fill fw-bold py-2 shadow-sm cursor-pointer mb-0">
                <i class="fas fa-camera me-1"></i> Kamera
            </label>
            <label for="input_galeri" class="btn btn-outline-success flex-fill fw-bold py-2 shadow-sm cursor-pointer mb-0">
                <i class="fas fa-images me-1"></i> Galeri
            </label>
        </div>

        <div id="preview-section" class="d-none mt-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <small class="fw-bold text-dark">Pratinjau Foto (<span id="foto-count">0</span>/10):</small>
                <button type="button" class="btn btn-link btn-sm text-danger p-0 text-decoration-none" onclick="resetFoto()">Hapus Semua</button>
            </div>
            <div id="preview-grid" class="preview-grid"></div>
        </div>
    </div>

    <!-- Tombol Simpan Jurnal -->
    <button type="submit" id="btn-submit" class="btn btn-rose w-100 py-3 fw-bold shadow mb-4">
        <i class="fas fa-paper-plane me-2"></i> Simpan Jurnal
    </button>
</form>

<!-- OVERLAY LOADING SAAT UPLOAD DRIVE -->
<div id="loading-overlay" class="d-none" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(255, 255, 255, 0.9); z-index: 9999; display: flex; flex-direction: column; justify-content: center; align-items: center;">
    <div class="spinner-border text-rose mb-3" style="width: 3.5rem; height: 3.5rem;" role="status"></div>
    <h5 class="fw-bold text-rose mb-1">Mengunggah Jurnal & Foto...</h5>
    <p class="text-muted small mb-0">Mohon tunggu, media sedang diunggah ke Google Drive.</p>
</div>

<script>
    let containerFile = new DataTransfer();

    function tangkapFoto(input) {
        const files = input.files;
        if (!files.length) return;

        if (containerFile.files.length + files.length > 10) {
            alert('Maksimal foto yang dapat dilampirkan adalah 10 foto.');
            return;
        }

        for (let i = 0; i < files.length; i++) {
            if (files[i].size > 5 * 1024 * 1024) {
                alert('File ' + files[i].name + ' melebihi ukuran maksimal 5 MB.');
                continue;
            }
            containerFile.items.add(files[i]);
        }

        renderPreview();
    }

    function renderPreview() {
        const grid = document.getElementById('preview-grid');
        const section = document.getElementById('preview-section');
        const count = document.getElementById('foto-count');
        
        grid.innerHTML = '';
        const currentFiles = containerFile.files;
        count.textContent = currentFiles.length;

        if (currentFiles.length > 0) {
            section.classList.remove('d-none');
        } else {
            section.classList.add('d-none');
        }

        for (let i = 0; i < currentFiles.length; i++) {
            const file = currentFiles[i];
            const src = URL.createObjectURL(file);
            
            const div = document.createElement('div');
            div.className = 'preview-item';
            div.innerHTML = `<img src="${src}">`;
            grid.appendChild(div);
        }
    }

    function resetFoto() {
        containerFile = new DataTransfer();
        document.getElementById('input_kamera').value = '';
        document.getElementById('input_galeri').value = '';
        renderPreview();
    }

    function validasiDanLoading(event) {
        const form = event.target;
        let hiddenInput = document.getElementById('foto_jurnal_final');
        
        if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'file';
            hiddenInput.name = 'foto_jurnal[]';
            hiddenInput.multiple = true;
            hiddenInput.id = 'foto_jurnal_final';
            hiddenInput.className = 'd-none';
            form.appendChild(hiddenInput);
        }
        
        hiddenInput.files = containerFile.files;

        document.getElementById('loading-overlay').classList.remove('d-none');
        const btn = document.getElementById('btn-submit');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

        return true;
    }
</script>
<?= $this->endSection() ?>