<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard' ?> - Aplikasi Guru Pendamping Khusus</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome (Ikon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        /* TEMA WARNA ROSE (Kombinasi Login & Dashboard) */
        :root {
            --rose-primary: #e11d48;
            --rose-hover: #be123c;
            --rose-light: #fff1f2;
            --rose-border: #fecdd3;
        }

        .bg-rose {
            background-color: var(--rose-primary) !important;
        }
        .text-rose {
            color: var(--rose-primary) !important;
        }
        .bg-rose-light {
            background-color: var(--rose-light) !important;
        }
        .border-rose-subtle {
            border-color: var(--rose-border) !important;
        }

        /* Styling Top Navbar */
        .navbar-theme {
            background: linear-gradient(135deg, #e11d48, #fb7185);
        }

        /* Styling Sidebar Menu */
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
    </style>
</head>
<body>

    <!-- Top Navbar Bertema Rose -->
    <header class="navbar navbar-dark sticky-top navbar-theme flex-md-nowrap p-0 shadow-sm">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 ps-4 fs-6 fw-bold text-white d-flex align-items-center" href="#">
            <i class="fas fa-chalkboard-teacher me-2"></i> GPK MIMHa
        </a>
        <button class="navbar-toggler position-absolute d-md-none collapsed mt-2 me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-nav ms-auto">
            <div class="nav-item text-nowrap">
                <a class="nav-link px-3 text-white fw-semibold" href="/logout" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                    <i class="fas fa-sign-out-alt me-1"></i> Keluar
                </a>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Terpisah -->
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-white sidebar collapse py-3 border-end">
                <div class="position-sticky sidebar-sticky">
                    <!-- PANGGIL FILE SIDEBAR SECARA TERPUSAT -->
                    <?= $this->include('layouts/sidebar') ?>
                </div>
            </nav>

            <!-- Main Content Area -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>