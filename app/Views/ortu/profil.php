<?= $this->extend('layouts/mobile_layout') ?>

<?= $this->section('content') ?>
<style>
    .text-rose { color: #e11d48 !important; }
    .bg-rose-light { background-color: #fff1f2 !important; }
    .border-rose-subtle { border-color: #fecdd3 !important; }
    .btn-rose { background-color: #e11d48; color: white; border-radius: 12px; }
    .btn-rose:hover { background-color: #be123c; color: white; }
</style>

<div class="d-flex align-items-center mb-3">
    <a href="/ortu/dashboard" class="text-secondary me-3">
        <i class="fas fa-arrow-left fs-5"></i>
    </a>
    <div>
        <h5 class="fw-bold mb-0 text-rose">Akun Orang Tua</h5>
        <small class="text-muted">Pengaturan Kata Sandi</small>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-3 small" role="alert">
        <i class="fas fa-check-circle me-1"></i> <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-3 small" role="alert">
        <i class="fas fa-exclamation-circle me-1"></i> <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php 
// Normalisasi data anak: jika $anak single array, ubah menjadi list array
$listAnak = [];
if (!empty($anak)) {
    $listAnak = isset($anak[0]) ? $anak : [$anak];
}
?>

<div class="card card-mobile p-3 bg-white mb-3 shadow-sm border-0 text-center">
    <div class="mb-2">
        <div class="bg-rose-light text-rose rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
            <i class="fas fa-user-circle fa-2x"></i>
        </div>
    </div>
    <h6 class="fw-bold text-dark mb-1"><?= esc($user['nama_lengkap'] ?? session()->get('nama_lengkap')) ?></h6>
    <span class="badge bg-rose-light text-rose border border-rose-subtle align-self-center text-uppercase mb-2">Orang Tua / Wali</span>
    
    <?php if(!empty($listAnak)): ?>
        <small class="text-muted d-block">
            <i class="fas fa-child me-1"></i> Orang Tua dari: 
            <strong>
                <?= esc(implode(', ', array_column($listAnak, 'nama_pdbk'))) ?>
            </strong>
        </small>
    <?php endif; ?>
</div>

<div class="card card-mobile p-3 bg-white mb-4 shadow-sm border-0">
    <h6 class="fw-bold text-rose mb-3">
        <i class="fas fa-key me-2"></i>Ganti Password
    </h6>

    <form action="/ortu/profil/update-password" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="form-label small fw-bold text-secondary">Password Saat Ini *</label>
            <div class="input-group">
                <input type="password" name="password_lama" id="pass_lama" class="form-control" required placeholder="Masukkan password lama">
                <button class="btn btn-outline-secondary" type="button" onclick="togglePass('pass_lama', this)">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold text-secondary">Password Baru *</label>
            <div class="input-group">
                <input type="password" name="password_baru" id="pass_baru" class="form-control" required minlength="6" placeholder="Minimal 6 karakter">
                <button class="btn btn-outline-secondary" type="button" onclick="togglePass('pass_baru', this)">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label small fw-bold text-secondary">Konfirmasi Password Baru *</label>
            <div class="input-group">
                <input type="password" name="konfirmasi_password" id="pass_konfirm" class="form-control" required minlength="6" placeholder="Ulangi password baru">
                <button class="btn btn-outline-secondary" type="button" onclick="togglePass('pass_konfirm', this)">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-rose w-100 py-2.5 fw-bold shadow-sm">
            <i class="fas fa-lock me-2"></i> Perbarui Password
        </button>
    </form>
</div>

<script>
    function togglePass(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
<?= $this->endSection() ?>