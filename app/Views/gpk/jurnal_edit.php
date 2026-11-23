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
    .btn-delete-photo {
        position: absolute;
        top: 4px;
        right: 4px;
        background: rgba(225, 29, 72, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 26px;
        height: 26px;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
</style>

<!-- Header Navigasi -->
<div class="d-flex align-items-center mb-3">
    <a href="/gpk/jurnal/detail/<?= $jurnal['id'] ?>" class="text-secondary me-3">
        <i class="fas fa-arrow-left fs-5"></i>
    </a>
    <div>
        <h5 class="fw-bold mb-0 text-rose">Edit Jurnal Harian</h5>
        <small class="text-muted"><?= esc($jurnal['nama_pdbk']) ?></small>
    </div>
</div>

<form action="/gpk/jurnal/update/<?= $jurnal['id'] ?>" method="post" enctype="multipart/form-data" onsubmit="return validasiDanLoading(event)">
    <?= csrf_field() ?>

    <input type="hidden" name="jenis_form" value="<?= esc($jurnal['jenis_form']) ?>">
    <input type="hidden" name="unit" value="<?= esc($jurnal['unit']) ?>">
    <input type="hidden" name="pdbk_id" value="<?= esc($jurnal['pdbk_id']) ?>">

    <!-- Identitas & Input Tanggal -->
    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <div class="mb-2">
            <span class="badge bg-rose-light text-rose border border-rose-subtle mb-1"><?= esc($jurnal['unit']) ?> - Kelas <?= esc($jurnal['kelas']) ?></span>
            <h6 class="fw-bold text-dark mb-0"><?= esc($jurnal['nama_pdbk']) ?></h6>
            <small class="text-muted"><i class="fas fa-brain me-1"></i> IQ: <?= esc($jurnal['keterangan_iq'] ?? '-') ?></small>
        </div>
        <hr class="my-2 opacity-25">
        <div>
            <label class="form-label small fw-bold text-secondary mb-1">
                <i class="far fa-calendar-alt me-1 text-rose"></i> Tanggal Kegiatan *
            </label>
            <input type="date" name="tanggal" class="form-control form-control-sm fw-bold border-light bg-light" value="<?= esc($jurnal['tanggal']) ?>" required>
        </div>
    </div>

    <?php if($jurnal['jenis_form'] === 'peril'): ?>
        <!-- ================= FORM PERIL LENGKAP ================= -->
        <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
            <h6 class="fw-bold text-rose mb-3"><i class="fas fa-child me-2"></i>1. Kegiatan Pembelajaran</h6>
            <div class="mb-3">
                <label class="form-label small fw-bold">Kegiatan yang Diikuti *</label>
                <textarea name="peril_kegiatan" class="form-control" rows="2" required><?= esc($jurnal['peril_kegiatan']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Tingkat Partisipasi *</label>
                <select name="peril_partisipasi" class="form-select" required>
                    <?php 
                        $opts = ["Mandiri", "Dengan isyarat atau pengingat", "Dengan arahan verbal", "Dengan contoh langsung", "Dengan bantuan fisik", "Belum bersedia mengikuti"];
                        foreach($opts as $o): 
                    ?>
                        <option value="<?= $o ?>" <?= ($jurnal['peril_partisipasi'] == $o) ? 'selected' : '' ?>><?= $o ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-0">
                <label class="form-label small fw-bold">Catatan Kegiatan & Respons Ananda *</label>
                <textarea name="peril_catatan_kegiatan" class="form-control" rows="2" required><?= esc($jurnal['peril_catatan_kegiatan']) ?></textarea>
            </div>
        </div>

        <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
            <h6 class="fw-bold text-rose mb-3"><i class="fas fa-utensils me-2"></i>2. Kemandirian & Aktivitas Harian</h6>
            <div class="mb-3">
                <label class="form-label small fw-bold">Kegiatan Makan & Minum *</label>
                <textarea name="peril_makan_minum" class="form-control" rows="2" required><?= esc($jurnal['peril_makan_minum']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Kebersihan Diri & Toilet *</label>
                <textarea name="peril_kebersihan_toilet" class="form-control" rows="2" required><?= esc($jurnal['peril_kebersihan_toilet']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Kemandirian Merapikan Barang *</label>
                <textarea name="peril_merapikan_barang" class="form-control" rows="2" required><?= esc($jurnal['peril_merapikan_barang']) ?></textarea>
            </div>
            <div class="mb-0">
                <label class="form-label small fw-bold">Bantuan yang Masih Diperlukan *</label>
                <textarea name="peril_bantuan_harian" class="form-control" rows="2" required><?= esc($jurnal['peril_bantuan_harian']) ?></textarea>
            </div>
        </div>

        <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
            <h6 class="fw-bold text-rose mb-3"><i class="fas fa-comments me-2"></i>3. Komunikasi & Interaksi Sosial</h6>
            <div class="mb-3">
                <label class="form-label small fw-bold">Penyampaian Keinginan / Instruksi *</label>
                <textarea name="peril_komunikasi" class="form-control" rows="2" required><?= esc($jurnal['peril_komunikasi']) ?></textarea>
            </div>
            <div class="mb-0">
                <label class="form-label small fw-bold">Interaksi dengan Guru & Teman *</label>
                <textarea name="peril_interaksi" class="form-control" rows="2" required><?= esc($jurnal['peril_interaksi']) ?></textarea>
            </div>
        </div>

        <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
            <h6 class="fw-bold text-rose mb-3"><i class="fas fa-smile me-2"></i>4. Regulasi Emosi</h6>
            <div class="mb-3">
                <label class="form-label small fw-bold">Kondisi Emosi Selama Kegiatan *</label>
                <textarea name="peril_emosi" class="form-control" rows="2" required><?= esc($jurnal['peril_emosi']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Respons Perpindahan Kegiatan *</label>
                <textarea name="peril_respon_arahan" class="form-control" rows="2" required><?= esc($jurnal['peril_respon_arahan']) ?></textarea>
            </div>
            <div class="mb-0">
                <label class="form-label small fw-bold">Strategi / Dukungan yang Membantu *</label>
                <textarea name="peril_strategi_dukungan" class="form-control" rows="2" required><?= esc($jurnal['peril_strategi_dukungan']) ?></textarea>
            </div>
        </div>

        <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
            <h6 class="fw-bold text-rose mb-3"><i class="fas fa-star me-2"></i>5. Apresiasi & Perkembangan Hari Ini</h6>
            <div class="mb-3">
                <label class="form-label small fw-bold">Pencapaian / Usaha Positif Hari Ini *</label>
                <textarea name="peril_pencapaian_positif" class="form-control" rows="2" required><?= esc($jurnal['peril_pencapaian_positif']) ?></textarea>
            </div>
            <div class="mb-0">
                <label class="form-label small fw-bold">Keterampilan Perlu Distimulasi *</label>
                <textarea name="peril_perlu_stimulasi" class="form-control" rows="2" required><?= esc($jurnal['peril_perlu_stimulasi']) ?></textarea>
            </div>
        </div>

        <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
            <h6 class="fw-bold text-rose mb-3"><i class="fas fa-info-circle me-2"></i>6. Informasi Tambahan & Stimulasi di Rumah</h6>
            <div class="mb-3">
                <label class="form-label small fw-bold">Informasi Khusus Orang Tua *</label>
                <textarea name="peril_info_ortu" class="form-control" rows="2" required><?= esc($jurnal['peril_info_ortu']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Saran Stimulasi di Rumah *</label>
                <textarea name="peril_stimulasi_rumah" class="form-control" rows="2" required><?= esc($jurnal['peril_stimulasi_rumah']) ?></textarea>
            </div>
            <div class="mb-0">
                <label class="form-label small fw-bold">Hal Dikomunikasikan Lebih Lanjut (Ortu/Tim Pendamping) *</label>
                <textarea name="peril_komunikasi_lanjut" class="form-control" rows="2" required><?= esc($jurnal['peril_komunikasi_lanjut']) ?></textarea>
            </div>
        </div>

    <?php else: ?>
        <!-- ================= FORM STANDARD LENGKAP ================= -->
        <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
            <h6 class="fw-bold text-rose mb-3"><i class="fas fa-book me-2"></i>1. Bidang Akademik</h6>
            <div class="mb-3">
                <label class="form-label small fw-bold">Mata Pelajaran *</label>
                <textarea name="akademik_mapel" class="form-control" rows="1" required><?= esc($jurnal['akademik_mapel']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Uraian Materi *</label>
                <textarea name="akademik_materi" class="form-control" rows="2" required><?= esc($jurnal['akademik_materi']) ?></textarea>
            </div>
            <div class="mb-0">
                <label class="form-label small fw-bold">Catatan Kegiatan (Respons Anak) *</label>
                <textarea name="akademik_catatan" class="form-control" rows="2" required><?= esc($jurnal['akademik_catatan']) ?></textarea>
            </div>
        </div>

        <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
            <h6 class="fw-bold text-rose mb-3"><i class="fas fa-user-check me-2"></i>2. Daily Life Skill</h6>
            <div class="mb-3">
                <label class="form-label small fw-bold">Toilet Training *</label>
                <textarea name="daily_toilet" class="form-control" rows="2" required><?= esc($jurnal['daily_toilet']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Kemandirian *</label>
                <textarea name="daily_kemandirian" class="form-control" rows="2" required><?= esc($jurnal['daily_kemandirian']) ?></textarea>
            </div>
            <div class="mb-0">
                <label class="form-label small fw-bold">Keterlibatan Kegiatan di Kelas *</label>
                <textarea name="daily_keterlibatan" class="form-control" rows="2" required><?= esc($jurnal['daily_keterlibatan']) ?></textarea>
            </div>
        </div>

        <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
            <h6 class="fw-bold text-rose mb-3"><i class="fas fa-seedling me-2"></i>3. Aspek Perkembangan</h6>
            <div class="mb-3">
                <label class="form-label small fw-bold">Aspek yang Distimulasi *</label>
                <textarea name="perkembangan_aspek" class="form-control" rows="2" required><?= esc($jurnal['perkembangan_aspek']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Uraian Kegiatan *</label>
                <textarea name="perkembangan_kegiatan" class="form-control" rows="2" required><?= esc($jurnal['perkembangan_kegiatan']) ?></textarea>
            </div>
            <div class="mb-0">
                <label class="form-label small fw-bold">Catatan Kegiatan (Respons Anak) *</label>
                <textarea name="perkembangan_catatan" class="form-control" rows="2" required><?= esc($jurnal['perkembangan_catatan']) ?></textarea>
            </div>
        </div>
    <?php endif; ?>

    <!-- ================= KELOLA FOTO DOKUMENTASI DRIVE ================= -->
    <div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0">
        <h6 class="fw-bold text-rose mb-2"><i class="fas fa-camera me-2"></i>Kelola Foto Dokumentasi</h6>
        
        <!-- List Foto Lama -->
        <?php if(!empty($fotos)): ?>
            <label class="form-label small fw-bold text-secondary mb-2">Foto Terunggah di Google Drive:</label>
            <div class="preview-grid mb-3">
                <?php foreach($fotos as $f): ?>
                    <div class="preview-item">
                        <img loading="lazy" src="<?= base_url('gpk/renderFoto/' . esc($f['google_file_id'])) ?>" alt="Dokumentasi">
                        <button type="button" class="btn-delete-photo btn-hapus-foto" data-id="<?= $f['id'] ?>" title="Hapus Foto">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Form Tambah Foto Baru -->
        <label class="form-label small fw-bold text-secondary mb-1">Tambah Foto Baru (Opsional):</label>
        <input type="file" id="input_kamera" accept="image/*" capture="environment" multiple class="d-none" onchange="tangkapFoto(this)">
        <input type="file" id="input_galeri" accept="image/*" multiple class="d-none" onchange="tangkapFoto(this)">

        <div class="d-flex gap-2 mb-3">
            <label for="input_kamera" class="btn btn-outline-danger flex-fill fw-bold py-2 shadow-sm cursor-pointer mb-0">
                <i class="fas fa-camera me-1"></i> Kamera
            </label>
            <label for="input_galeri" class="btn btn-outline-success flex-fill fw-bold py-2 shadow-sm cursor-pointer mb-0">
                <i class="fas fa-images me-1"></i> Galeri
            </label>
        </div>

        <div id="preview-section" class="d-none">
            <small class="fw-bold text-dark d-block mb-2">Pratinjau Foto Baru (<span id="foto-count">0</span>):</small>
            <div id="preview-grid" class="preview-grid"></div>
        </div>
    </div>

    <!-- Tombol Simpan Perubahan -->
    <button type="submit" id="btn-submit" class="btn btn-rose w-100 py-3 fw-bold shadow mb-4">
        <i class="fas fa-save me-2"></i> Simpan Perubahan Jurnal
    </button>
</form>

<!-- MODAL KONFIRMASI HAPUS FOTO -->
<div class="modal fade" id="modalHapusFoto" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content border-0 rounded-4 shadow">
      <div class="modal-body text-center p-4">
        <i class="fas fa-exclamation-circle fa-3x text-rose mb-3"></i>
        <h6 class="fw-bold mb-2">Hapus Foto Ini?</h6>
        <p class="small text-muted mb-4">File foto di Google Drive akan dihapus secara permanen.</p>
        
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-light flex-fill fw-semibold py-2 rounded-3" data-bs-dismiss="modal">Batal</button>
            <a id="btnConfirmDeleteFoto" href="#" class="btn btn-rose flex-fill fw-bold py-2 rounded-3">Hapus</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- OVERLAY LOADING SAAT SIMPAN -->
<div id="loading-overlay" class="d-none" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(255, 255, 255, 0.9); z-index: 9999; display: flex; flex-direction: column; justify-content: center; align-items: center;">
    <div class="spinner-border text-rose mb-3" style="width: 3.5rem; height: 3.5rem;" role="status"></div>
    <h5 class="fw-bold text-rose mb-1">Menyimpan Perubahan...</h5>
    <p class="text-muted small mb-0">Mohon tunggu, data dan foto sedang diperbarui.</p>
</div>

<script>
    let containerFile = new DataTransfer();

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-hapus-foto');
        if (btn) {
            const fotoId = btn.getAttribute('data-id');
            document.getElementById('btnConfirmDeleteFoto').href = '/gpk/jurnal/foto/hapus/' + fotoId;
            const modal = new bootstrap.Modal(document.getElementById('modalHapusFoto'));
            modal.show();
        }
    });

    function tangkapFoto(input) {
        const files = input.files;
        if (!files.length) return;

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