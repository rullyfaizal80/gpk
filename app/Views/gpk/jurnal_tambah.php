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

<div class="d-flex align-items-center mb-3">
    <a href="/gpk/dashboard" class="text-secondary me-3">
        <i class="fas fa-arrow-left fs-5"></i>
    </a>
    <div>
        <h5 class="fw-bold mb-0 text-rose">Form Jurnal Harian</h5>
        <small class="text-muted">Unit <?= esc($anak['unit']) ?> - Kelas <?= esc($anak['kelas']) ?></small>
    </div>
</div>

<!-- Card Ringkasan PDBK -->
<div class="card card-mobile p-3 bg-white mb-3 shadow-sm">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <span class="badge bg-rose-light text-rose border border-rose-subtle mb-1"><?= esc($anak['unit']) ?></span>
            <h6 class="fw-bold text-dark mb-0"><?= esc($anak['nama_pdbk']) ?></h6>
            <small class="text-muted"><i class="fas fa-brain me-1"></i> <?= esc($anak['keterangan_iq'] ?? '-') ?></small>
        </div>
        <div class="text-end">
            <span class="small text-muted d-block">Tanggal</span>
            <strong class="small text-dark"><?= date('d M Y') ?></strong>
        </div>
    </div>
</div>

<form action="/gpk/jurnal/simpan" method="post" enctype="multipart/form-data" onsubmit="return validasiDanLoading(event)">
    <input type="hidden" name="pdbk_id" value="<?= $anak['id'] ?>">
    <input type="hidden" name="unit" value="<?= $anak['unit'] ?>">
    <input type="hidden" name="jenis_form" value="<?= ($anak['unit'] === 'PERIL') ? 'peril' : 'standard' ?>">

    <?php if($anak['unit'] === 'PERIL'): ?>
        <!-- ========================================== -->
        <!-- FORM 1: KHUSUS UNIT PERIL (PG - RA)        -->
        <!-- ========================================== -->
        <div class="card card-mobile p-3 bg-white mb-3 shadow-sm">
            <h6 class="fw-bold text-rose mb-3"><i class="fas fa-child me-2"></i>1. Kegiatan Pembelajaran</h6>
            <div class="mb-3">
                <label class="form-label small fw-bold">Kegiatan yang Diikuti *</label>
                <textarea name="peril_kegiatan" class="form-control" rows="2" placeholder="Contoh: Murojaah, menempel garis, dll" required></textarea>
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
                <label class="form-label small fw-bold">Catatan Kegiatan & Respons Ananda *</label>
                <textarea name="peril_catatan_kegiatan" class="form-control" rows="2" placeholder="Uraian respons ananda" required></textarea>
            </div>
        </div>

        <div class="card card-mobile p-3 bg-white mb-3 shadow-sm">
            <h6 class="fw-bold text-rose mb-3"><i class="fas fa-utensils me-2"></i>2. Kemandirian & Aktivitas Harian</h6>
            <div class="mb-3">
                <label class="form-label small fw-bold">Kegiatan Makan & Minum</label>
                <input type="text" name="peril_makan_minum" class="form-control" placeholder="Contoh: Makan mandiri tanpa bersisa">
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Kebersihan Diri & Toilet</label>
                <input type="text" name="peril_kebersihan_toilet" class="form-control" placeholder="Contoh: Toilet training mandiri">
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Kemandirian Merapikan Barang</label>
                <input type="text" name="peril_merapikan_barang" class="form-control" placeholder="Contoh: Merapikan tas sendiri">
            </div>
            <div class="mb-0">
                <label class="form-label small fw-bold">Bantuan yang Masih Diperlukan</label>
                <input type="text" name="peril_bantuan_harian" class="form-control" placeholder="Contoh: Membuka tutup tempat makan">
            </div>
        </div>

        <div class="card card-mobile p-3 bg-white mb-3 shadow-sm">
            <h6 class="fw-bold text-rose mb-3"><i class="fas fa-comments me-2"></i>3. Komunikasi & Interaksi Sosial</h6>
            <div class="mb-3">
                <label class="form-label small fw-bold">Penyampaian Keinginan / Instruksi</label>
                <textarea name="peril_komunikasi" class="form-control" rows="2" placeholder="Cara anak mengungkapkan pesan"></textarea>
            </div>
            <div class="mb-0">
                <label class="form-label small fw-bold">Interaksi dengan Guru & Teman</label>
                <textarea name="peril_interaksi" class="form-control" rows="2" placeholder="Merespons sapaan, main bersama, bergiliran"></textarea>
            </div>
        </div>

        <div class="card card-mobile p-3 bg-white mb-3 shadow-sm">
            <h6 class="fw-bold text-rose mb-3"><i class="fas fa-smile me-2"></i>4. Regulasi Emosi</h6>
            <div class="mb-3">
                <label class="form-label small fw-bold">Kondisi Emosi Selama Kegiatan</label>
                <input type="text" name="peril_emosi" class="form-control" placeholder="Deskripsi objektif emosi ananda">
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Respons Perpindahan Kegiatan</label>
                <input type="text" name="peril_respon_arahan" class="form-control" placeholder="Respons saat transisi materi">
            </div>
            <div class="mb-0">
                <label class="form-label small fw-bold">Strategi / Dukungan yang Membantu</label>
                <input type="text" name="peril_strategi_dukungan" class="form-control" placeholder="Misal: Jadwal visual, jeda singkat">
            </div>
        </div>

        <div class="card card-mobile p-3 bg-white mb-3 shadow-sm">
            <h6 class="fw-bold text-rose mb-3"><i class="fas fa-star me-2"></i>5. Apresiasi & Informasi Ortu</h6>
            <div class="mb-3">
                <label class="form-label small fw-bold">Pencapaian / Usaha Positif Hari Ini</label>
                <textarea name="peril_pencapaian_positif" class="form-control" rows="2"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Keterampilan Perlu Distimulasi</label>
                <textarea name="peril_perlu_stimulasi" class="form-control" rows="2"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Informasi Khusus Orang Tua</label>
                <input type="text" name="peril_info_ortu" class="form-control" value="Tidak ada">
            </div>
            <div class="mb-0">
                <label class="form-label small fw-bold">Saran Stimulasi di Rumah</label>
                <textarea name="peril_stimulasi_rumah" class="form-control" rows="2" placeholder="Saran praktis untuk ortu"></textarea>
            </div>
        </div>

    <?php else: ?>
        <!-- ========================================== -->
        <!-- FORM 2: UNTUK UNIT MI DAN MTS (STANDARD)   -->
        <!-- ========================================== -->
        <div class="card card-mobile p-3 bg-white mb-3 shadow-sm">
            <h6 class="fw-bold text-rose mb-3"><i class="fas fa-book me-2"></i>1. Bidang Akademik</h6>
            <div class="mb-3">
                <label class="form-label small fw-bold">Mata Pelajaran *</label>
                <input type="text" name="akademik_mapel" class="form-control" placeholder="Contoh: Matematika, Bahasa Indonesia" required>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Uraian Materi *</label>
                <textarea name="akademik_materi" class="form-control" rows="2" placeholder="Contoh: Penjumlahan bersusun ke bawah" required></textarea>
            </div>
            <div class="mb-0">
                <label class="form-label small fw-bold">Catatan Kegiatan (Respons Anak) *</label>
                <textarea name="akademik_catatan" class="form-control" rows="2" placeholder="Fokus anak, pengerjaan LKPD, dll" required></textarea>
            </div>
        </div>

        <div class="card card-mobile p-3 bg-white mb-3 shadow-sm">
            <h6 class="fw-bold text-rose mb-3"><i class="fas fa-user-check me-2"></i>2. Daily Life Skill</h6>
            <div class="mb-3">
                <label class="form-label small fw-bold">Toilet Training</label>
                <input type="text" name="daily_toilet" class="form-control" placeholder="Contoh: Mandiri & mampu membersihkan sendiri">
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Kemandirian</label>
                <input type="text" name="daily_kemandirian" class="form-control" placeholder="Merapikan alat tulis, makan sendiri">
            </div>
            <div class="mb-0">
                <label class="form-label small fw-bold">Keterlibatan Kegiatan di Kelas</label>
                <input type="text" name="daily_keterlibatan" class="form-control" placeholder="Bergabung & interaksi dengan teman">
            </div>
        </div>

        <div class="card card-mobile p-3 bg-white mb-3 shadow-sm">
            <h6 class="fw-bold text-rose mb-3"><i class="fas fa-seedling me-2"></i>3. Aspek Perkembangan</h6>
            <div class="mb-3">
                <label class="form-label small fw-bold">Aspek yang Distimulasi</label>
                <input type="text" name="perkembangan_aspek" class="form-control" placeholder="Contoh: Motorik Kasar / Emosi">
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Uraian Kegiatan</label>
                <textarea name="perkembangan_kegiatan" class="form-control" rows="2" placeholder="Lempar tangkap bola 2 tangan"></textarea>
            </div>
            <div class="mb-0">
                <label class="form-label small fw-bold">Catatan Kegiatan (Respons Anak)</label>
                <textarea name="perkembangan_catatan" class="form-control" rows="2" placeholder="Mampu menangkap bola tepat sasaran"></textarea>
            </div>
        </div>
    <?php endif; ?>

    <!-- ========================================== -->
    <!-- BAGIAN UPLOAD FOTO DOKUMENTASI (MAX 10)    -->
    <!-- ========================================== -->
    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm">
        <h6 class="fw-bold text-rose mb-2"><i class="fas fa-camera me-2"></i>Foto Kegiatan (Maksimal 10 Foto)</h6>
        <p class="small text-muted mb-3">Foto akan otomatis diunggah dan tersimpan ke Google Drive sekolah.</p>

        <!-- Input File Tersembunyi -->
        <input type="file" id="input_kamera" accept="image/*" capture="environment" multiple class="d-none" onchange="tangkapFoto(this)">
        <input type="file" id="input_galeri" accept="image/*" multiple class="d-none" onchange="tangkapFoto(this)">

        <!-- Tombol Pilihan -->
        <div class="d-flex gap-2 mb-3">
            <label for="input_kamera" class="btn btn-outline-danger flex-fill fw-bold py-2 shadow-sm cursor-pointer mb-0">
                <i class="fas fa-camera me-1"></i> Kamera
            </label>
            <label for="input_galeri" class="btn btn-outline-success flex-fill fw-bold py-2 shadow-sm cursor-pointer mb-0">
                <i class="fas fa-images me-1"></i> Galeri
            </label>
        </div>

        <!-- Hidden Container untuk Menyimpan File Final -->
        <div id="file-holder" class="d-none"></div>

        <!-- Pratinjau Grid Foto -->
        <div id="preview-section" class="d-none">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <small class="fw-bold text-dark">Pratinjau Foto (<span id="foto-count">0</span>/10):</small>
                <button type="button" class="btn btn-link btn-sm text-danger p-0 text-decoration-none" onclick="resetFoto()">Hapus Semua</button>
            </div>
            <div id="preview-grid" class="preview-grid"></div>
        </div>
    </div>

    <!-- Tombol Simpan Jurnal -->
    <button type="submit" id="btn-submit" class="btn btn-rose w-100 py-3 fw-bold shadow mb-4">
        <i class="fas fa-paper-plane me-2"></i> Simpan & Unggah Jurnal
    </button>
</form>

<!-- OVERLAY LOADING SAAT UPLOAD DRIVE -->
<div id="loading-overlay" class="d-none" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(255, 255, 255, 0.9); z-index: 9999; display: flex; flex-direction: column; justify-content: center; align-items: center;">
    <div class="spinner-border text-rose mb-3" style="width: 3.5rem; height: 3.5rem;" role="status"></div>
    <h5 class="fw-bold text-rose mb-1">Mengunggah Jurnal & Foto...</h5>
    <p class="text-muted small mb-0">Mohon tunggu, media sedang diunggah ke Google Drive.</p>
</div>

<!-- SCRIPT HANDLER FOTO MULTIPLE & PREVIEW -->
<script>
    let containerFile = new DataTransfer();

    function tangkapFoto(input) {
        const files = input.files;
        if (!files.length) return;

        // Cek jika melebihi batas 10 foto
        if (containerFile.files.length + files.length > 10) {
            alert('Maksimal foto yang dapat dilampirkan adalah 10 foto.');
            return;
        }

        for (let i = 0; i < files.length; i++) {
            // Validasi ukuran max 5MB per foto
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
            div.innerHTML = `<img src="${src}" onclick="showImage('${src}')">`;
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
        // Tempelkan file dari containerFile ke input form sebelum diposting
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

        // Tampilkan overlay loading
        document.getElementById('loading-overlay').classList.remove('d-none');
        const btn = document.getElementById('btn-submit');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

        return true;
    }
</script>
<?= $this->endSection() ?>