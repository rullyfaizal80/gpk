<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
    <style>
        .text-rose { color: #e11d48 !important; }
        .btn-rose { background-color: #e11d48; color: white; border-color: #e11d48; }
        .btn-rose:hover { background-color: #be123c; color: white; border-color: #be123c; }
        .btn-outline-rose { color: #e11d48; border-color: #e11d48; }
        .btn-outline-rose:hover { background-color: #e11d48; color: white; }
        .bg-rose { background-color: #e11d48 !important; color: white; }
        .badge-rose { background-color: #ffe4e6; color: #e11d48; border: 1px solid #fecdd3; }
    </style>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2 fw-bold text-rose">Kelola Pengguna Sistem</h1>
        <button type="button" class="btn btn-rose shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fas fa-user-plus me-1"></i> Tambah Pengguna
        </button>
    </div>

    <!-- Alert Notifikasi -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-1"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Tabel Data User -->
    <div class="card shadow-sm border-0">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Role / Posisi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($users as $u): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td class="fw-bold"><?= esc($u['nama_lengkap']) ?></td>
                            <td><?= esc($u['username']) ?></td>
                            <td>
                                <?php 
                                    $roleBadge = 'badge-rose';
                                    if($u['role'] == 'admin') $roleBadge = 'bg-danger text-white';
                                    if($u['role'] == 'gpk') $roleBadge = 'bg-rose text-white';
                                    if($u['role'] == 'pimpinan') $roleBadge = 'bg-primary text-white';
                                    if($u['role'] == 'ortu') $roleBadge = 'bg-success text-white';
                                ?>
                                <span class="badge <?= $roleBadge ?> rounded-pill text-uppercase px-3 py-2"><?= esc($u['role']) ?></span>
                            </td>
                            <td>
                                <?php if($u['is_active'] == 1): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success"><i class="fas fa-check-circle"></i> Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger"><i class="fas fa-ban"></i> Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-rose" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $u['id'] ?>">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                
                                <?php if($u['id'] != session()->get('id')): ?>
                                    <a href="/admin/user/hapus/<?= $u['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus pengguna ini?')">
                                        <i class="fas fa-trash-alt"></i> Hapus
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <!-- MODAL EDIT USER -->
                        <div class="modal fade" id="modalEdit<?= $u['id'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="/admin/user/edit" method="post">
                                        <div class="modal-header bg-rose">
                                            <h5 class="modal-title text-white">Edit Pengguna</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Nama Lengkap</label>
                                                <input type="text" class="form-control" name="nama_lengkap" value="<?= esc($u['nama_lengkap']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Username</label>
                                                <input type="text" class="form-control" name="username" value="<?= esc($u['username']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Password Baru (Opsional)</label>
                                                <input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak diubah">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Role / Akses</label>
                                                <select class="form-select" name="role" required>
                                                    <option value="admin" <?= $u['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                                    <option value="gpk" <?= $u['role'] == 'gpk' ? 'selected' : '' ?>>GPK (Guru Pendamping Khusus)</option>
                                                    <option value="pimpinan" <?= $u['role'] == 'pimpinan' ? 'selected' : '' ?>>Pimpinan / Kepala Sekolah</option>
                                                    <option value="ortu" <?= $u['role'] == 'ortu' ? 'selected' : '' ?>>Orang Tua Peserta Didik</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Status Akun</label>
                                                <select class="form-select" name="is_active" required>
                                                    <option value="1" <?= $u['is_active'] == 1 ? 'selected' : '' ?>>Aktif</option>
                                                    <option value="0" <?= $u['is_active'] == 0 ? 'selected' : '' ?>>Nonaktif</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-rose"><i class="fas fa-save me-1"></i> Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL TAMBAH USER -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/admin/user/tambah" method="post">
                    <div class="modal-header bg-rose">
                        <h5 class="modal-title text-white">Tambah Pengguna Baru</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama_lengkap" placeholder="Masukkan nama lengkap" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Username</label>
                            <input type="text" class="form-control" name="username" placeholder="Buat username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Buat password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Role / Akses</label>
                            <select class="form-select" name="role" required>
                                <option value="" disabled selected>-- Pilih Posisi --</option>
                                <option value="admin">Admin</option>
                                <option value="gpk">GPK (Guru Pendamping Khusus)</option>
                                <option value="pimpinan">Pimpinan / Kepala Sekolah</option>
                                <option value="ortu">Orang Tua Peserta Didik</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-rose"><i class="fas fa-user-plus me-1"></i> Tambah User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>