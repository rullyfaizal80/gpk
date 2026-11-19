<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard' ?> - Jurnal Harian GPK</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        :root {
            --rose-primary: #e11d48;
            --rose-hover: #be123c;
            --rose-light: #fff1f2;
            --rose-border: #fecdd3;
        }

        .bg-rose { background-color: var(--rose-primary) !important; }
        .text-rose { color: var(--rose-primary) !important; }
        .bg-rose-light { background-color: var(--rose-light) !important; }
        .border-rose-subtle { border-color: var(--rose-border) !important; }

        .navbar-theme {
            background: linear-gradient(135deg, #e11d48, #fb7185);
        }

        .sidebar {
            min-height: calc(100vh - 56px);
            box-shadow: 2px 0 10px rgba(225, 29, 72, 0.05);
        }
        .nav-rose {
            color: #4b5563;
            font-weight: 500;
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.2s ease-in-out;
        }
        .nav-rose:hover {
            color: var(--rose-primary);
            background-color: var(--rose-light);
        }
        .nav-rose.active {
            color: white !important;
            background-color: var(--rose-primary) !important;
            box-shadow: 0 4px 12px rgba(225, 29, 72, 0.25);
        }

        .btn-rose {
            background-color: var(--rose-primary);
            color: white;
            border-color: var(--rose-primary);
        }
        .btn-rose:hover {
            background-color: var(--rose-hover);
            color: white;
            border-color: var(--rose-hover);
        }
    </style>
</head>
<body>

    <!-- Top Navbar -->
    <header class="navbar navbar-dark sticky-top navbar-theme flex-md-nowrap p-0 shadow-sm">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 ps-4 fs-6 fw-bold text-white d-flex align-items-center" href="#">
            <i class="fas fa-book-reader me-2"></i> Jurnal Harian GPK MIMHa
        </a>
        <button class="navbar-toggler position-absolute d-md-none collapsed mt-2 me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-nav ms-auto">
            <div class="nav-item text-nowrap">
                <!-- Tombol Keluar memicu Modal -->
                <button type="button" class="btn nav-link px-3 text-white fw-semibold border-0 bg-transparent" data-bs-toggle="modal" data-bs-target="#modalLogout">
                    <i class="fas fa-sign-out-alt me-1"></i> Keluar
                </button>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Terpisah -->
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-white sidebar collapse py-3 border-end">
                <div class="position-sticky sidebar-sticky">
                    <?= $this->include('layouts/sidebar') ?>
                </div>
            </nav>

            <!-- Main Content Area -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <!-- MODAL KONFIRMASI LOGOUT -->
    <div class="modal fade" id="modalLogout" tabindex="-1" aria-labelledby="modalLogoutLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-body p-4 text-center">
                    <div class="mb-3">
                        <span class="d-inline-block rounded-circle p-3 bg-rose-light text-rose">
                            <i class="fas fa-sign-out-alt fa-2x"></i>
                        </span>
                    </div>
                    <h5 class="fw-bold mb-2">Konfirmasi Keluar</h5>
                    <p class="small text-muted mb-4">Apakah Anda yakin ingin keluar dari aplikasi ini?</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-light rounded-pill px-4 fw-semibold border" data-bs-dismiss="modal">Batal</button>
                        <a href="/logout" class="btn btn-rose rounded-pill px-4 fw-semibold shadow-sm">Ya, Keluar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>