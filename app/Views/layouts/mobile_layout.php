<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title><?= $title ?? 'App' ?> - Guru Pendamping Khusus</title>
    
    <!-- PWA Meta Tags -->
    <link rel="manifest" href="<?= base_url('manifest.json') ?>">
    <meta name="theme-color" content="#e11d48">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="<?= base_url('assets/images/logo.png') ?>">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- FontAwesome (Ikon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

    <style>
        :root {
            --rose-primary: #e11d48;
            --rose-hover: #be123c;
            --rose-light: #fff1f2;
            --rose-border: #fecdd3;
        }

        body.mobile-body {
            background-color: #f8fafc;
            padding-bottom: 75px; /* Ruang agar konten tidak tertutup Bottom Nav */
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        /* Top Header Navbar */
        .navbar-mobile {
            background: linear-gradient(135deg, #e11d48, #fb7185);
            color: white;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
        }

        /* Bottom Navigation Bar */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #ffffff;
            border-top: 1px solid #f1f5f9;
            box-shadow: 0 -4px 20px rgba(225, 29, 72, 0.08);
            z-index: 1030;
        }

        .bottom-nav-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #64748b;
            text-decoration: none;
            flex: 1;
            transition: all 0.2s ease-in-out;
        }

        .bottom-nav-link:hover {
            color: var(--rose-primary);
        }

        .bottom-nav-link.active {
            color: var(--rose-primary);
            font-weight: 700;
        }

        .bottom-nav-link .small-text {
            font-size: 0.72rem;
            margin-top: 2px;
        }

        /* Card & UI Mobile Elements */
        .card-mobile {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        }

        .badge-role {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 0.7rem;
            padding: 3px 8px;
            border-radius: 12px;
        }
    </style>
</head>
<body class="mobile-body">

    <!-- Header Atas (Warna Rose Soft Gradient) -->
    <nav class="navbar navbar-mobile sticky-top shadow-sm py-2 px-3">
        <div class="container-fluid p-0 d-flex justify-content-between align-items-center">
            <!-- Brand / Judul -->
            <div class="d-flex align-items-center">
                <i class="fas fa-chalkboard-teacher me-2 fs-5"></i>
                <span class="fw-bold fs-6">GPK MIMHa</span>
            </div>

            <!-- Profile User Ringkas -->
            <div class="d-flex align-items-center gap-2">
                <div class="text-end" style="max-width: 140px">
                    <span class="small fw-bold text-truncate d-block" title="<?= esc(session()->get('nama_lengkap')) ?>">
                        <?= esc(session()->get('nama_lengkap') ?? 'Pengguna') ?>
                    </span>
                    <span class="badge-role text-uppercase">
                        <?= esc(session()->get('role') ?? 'User') ?>
                    </span>
                </div>
                <a href="/logout" onclick="return confirm('Keluar dari aplikasi?')" class="text-white opacity-75 ms-1">
                    <i class="fas fa-sign-out-alt fs-5"></i>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <div class="container px-3 mt-3">
        <?= $this->renderSection('content') ?>
    </div>

    <!-- Bottom Navigation Bar (Otomatis Mengikuti Role) -->
    <nav class="bottom-nav">
        <?= $this->include('layouts/bottom_nav') ?>
    </nav>

    <!-- Universal Image Preview Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered px-3">
            <div class="modal-content border-0 bg-transparent">
                <div class="modal-header border-0 p-2 justify-content-end">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 text-center">
                    <img loading="lazy" id="modalImageSource" src="" class="img-fluid rounded-4 shadow-lg" alt="Preview Gambar" />
                </div>
            </div>
        </div>
    </div>

    <!-- Script Global -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showImage(imageSrc) {
            document.getElementById("modalImageSource").src = imageSrc;
            var imageModal = new bootstrap.Modal(document.getElementById("imageModal"));
            imageModal.show();
        }
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>