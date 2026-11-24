<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>
    <style>
        .text-rose { color: #e11d48 !important; }
        .btn-rose { background-color: #e11d48; color: white; border-color: #e11d48; }
        .btn-rose:hover { background-color: #be123c; color: white; border-color: #be123c; }
        .btn-outline-rose { color: #e11d48; border-color: #e11d48; }
        .btn-outline-rose:hover { background-color: #e11d48; color: white; }
        .bg-rose { background-color: #e11d48 !important; color: white; }
        /* Custom Pagination Styling */
.pagination .page-link {
    color: #e11d48;
    background-color: #ffffff;
    transition: all 0.2s ease-in-out;
}

.pagination .page-link:hover {
    background-color: #fff1f2;
    color: #be123c;
}

.pagination .page-item.active .page-link {
    background-color: #e11d48 !important;
    border-color: #e11d48 !important;
    color: #ffffff !important;
    box-shadow: 0 4px 10px rgba(225, 29, 72, 0.3) !important;
}
    </style>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2 fw-bold text-rose">Kelola Peserta Didik (PDBK)</h1>
        <button type="button" class="btn btn-rose shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahPdbk">
            <i class="fas fa-plus me-1"></i> Tambah PDBK
        </button>
    </div>

    <!-- Alert Flashdata -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-1"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Tabel Data PDBK -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th>No</th>
                        <th>Nama PDBK</th>
                        <th>Keterangan IQ</th>
                        <th>Kelas</th>
                        <th>Unit</th>
                        <th>GPK Pendamping</th>
                        <th>Orang Tua / Wali</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($peserta_didik)): ?>
                        <?php 
                        // Hitung nomor urut dinamis berdasarkan halaman pagination
                        $page = isset($currentPage) ? $currentPage : 1;
                        $perPage = isset($perPage) ? $perPage : 10;
                        $no = 1 + ($perPage * ($page - 1));

                        foreach($peserta_didik as $p): 
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td class="fw-bold text-dark"><?= esc($p['nama_pdbk']) ?></td>
                                <td><span class="badge bg-light text-dark border"><?= esc($p['keterangan_iq'] ?? '-') ?></span></td>
                                <td><span class="fw-semibold text-rose"><?= esc($p['kelas'] ?? '-') ?></span></td>
                                <td>
                                    <?php 
                                        $badgeUnit = 'bg-secondary';
                                        if($p['unit'] == 'PERIL') $badgeUnit = 'bg-warning text-dark';
                                        if($p['unit'] == 'MI') $badgeUnit = 'bg-primary';
                                        if($p['unit'] == 'MTs') $badgeUnit = 'bg-success';
                                    ?>
                                    <span class="badge <?= $badgeUnit ?>"><?= esc($p['unit']) ?></span>
                                </td>
                                <td>
                                    <?php if(!empty($p['nama_gpk'])): ?>
                                        <i class="fas fa-user-tie text-rose me-1"></i> <?= esc($p['nama_gpk']) ?>
                                    <?php else: ?>
                                        <span class="text-muted small italic">Belum Ditentukan</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if(!empty($p['nama_ortu'])): ?>
                                        <i class="fas fa-heart text-danger me-1"></i> <?= esc($p['nama_ortu']) ?>
                                    <?php else: ?>
                                        <span class="text-muted small">Belum Dihubungkan</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-rose" data-bs-toggle="modal" data-bs-target="#modalEditPdbk<?= $p['id'] ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="/admin/peserta-didik/hapus/<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin menghapus data PDBK ini?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- MODAL EDIT PDBK -->
                            <div class="modal fade" id="modalEditPdbk<?= $p['id'] ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="/admin/peserta-didik/edit" method="post">
                                            <div class="modal-header bg-rose">
                                                <h5 class="modal-title text-white">Edit Data PDBK</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                                
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Nama PDBK</label>
                                                    <input type="text" class="form-control" name="nama_pdbk" value="<?= esc($p['nama_pdbk']) ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Keterangan IQ / Hasil Tes</label>
                                                    <input type="text" class="form-control" name="keterangan_iq" value="<?= esc($p['keterangan_iq']) ?>">
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold small">Kelas</label>
                                                        <input type="text" class="form-control" name="kelas" value="<?= esc($p['kelas']) ?>" placeholder="Misal: 1C, 2B, 7">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold small">Unit Sekolah</label>
                                                        <select class="form-select" name="unit" required>
                                                            <option value="PERIL" <?= $p['unit'] == 'PERIL' ? 'selected' : '' ?>>PERIL</option>
                                                            <option value="MI" <?= $p['unit'] == 'MI' ? 'selected' : '' ?>>MI</option>
                                                            <option value="MTs" <?= $p['unit'] == 'MTs' ? 'selected' : '' ?>>MTs</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Guru GPK Pendamping</label>
                                                    <select class="form-select" name="gpk_id">
                                                        <option value="">-- Belum Ditentukan --</option>
                                                        <?php if(!empty($list_gpk)): foreach($list_gpk as $gpk): ?>
                                                            <option value="<?= $gpk['id'] ?>" <?= $p['gpk_id'] == $gpk['id'] ? 'selected' : '' ?>>
                                                                <?= esc($gpk['nama_lengkap']) ?>
                                                            </option>
                                                        <?php endforeach; endif; ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Akun Orang Tua</label>
                                                    <select class="form-select" name="ortu_id">
                                                        <option value="">-- Hubungkan dengan Akun Ortu --</option>
                                                        <?php if(!empty($list_ortu)): foreach($list_ortu as $ortu): ?>
                                                            <option value="<?= $ortu['id'] ?>" <?= $p['ortu_id'] == $ortu['id'] ? 'selected' : '' ?>>
                                                                <?= esc($ortu['nama_lengkap']) ?> (<?= esc($ortu['username']) ?>)
                                                            </option>
                                                        <?php endforeach; endif; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-rose"><i class="fas fa-save me-1"></i> Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data Peserta Didik.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

       <!-- Render Link Pagination Bootstrap Kustom -->
<?php if (isset($pager) && $pager): ?>
    <div class="card-footer bg-white border-0 py-3">
        <?= $pager->links('peserta_didik', 'bootstrap_full') ?>
    </div>
<?php endif; ?>
    </div>

    <!-- MODAL TAMBAH PDBK -->
    <div class="modal fade" id="modalTambahPdbk" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/admin/peserta-didik/tambah" method="post">
                    <div class="modal-header bg-rose">
                        <h5 class="modal-title text-white">Tambah Peserta Didik (PDBK)</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Nama PDBK</label>
                            <input type="text" class="form-control" name="nama_pdbk" placeholder="Masukkan nama lengkap anak" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Keterangan IQ / Hasil Asesmen</label>
                            <input type="text" class="form-control" name="keterangan_iq" placeholder="Contoh: Borderline / Mental Defective">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">Kelas</label>
                                <input type="text" class="form-control" name="kelas" placeholder="Misal: TKA, 1C, 7">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">Unit Sekolah</label>
                                <select class="form-select" name="unit" required>
                                    <option value="MI" selected>MI</option>
                                    <option value="PERIL">PERIL</option>
                                    <option value="MTs">MTs</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Tugaskan Guru GPK</label>
                            <select class="form-select" name="gpk_id">
                                <option value="">-- Pilih GPK Pendamping --</option>
                                <?php if(!empty($list_gpk)): foreach($list_gpk as $gpk): ?>
                                    <option value="<?= $gpk['id'] ?>"><?= esc($gpk['nama_lengkap']) ?></option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Hubungkan Akun Orang Tua</label>
                            <select class="form-select" name="ortu_id">
                                <option value="">-- Pilih Akun Orang Tua --</option>
                                <?php if(!empty($list_ortu)): foreach($list_ortu as $ortu): ?>
                                    <option value="<?= $ortu['id'] ?>"><?= esc($ortu['nama_lengkap']) ?> (<?= esc($ortu['username']) ?>)</option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-rose"><i class="fas fa-plus me-1"></i> Tambah Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>