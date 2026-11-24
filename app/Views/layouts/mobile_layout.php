<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title><?= $title ?? 'App' ?> - Jurnal Harian GPK</title>

    <!-- Favicon Browser & Apple Touch Icon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>">
    <link rel="apple-touch-icon" href="<?= base_url('assets/images/logo.png') ?>">
    
    <!-- PWA Meta Tags -->
    <link rel="manifest" href="<?= base_url('manifest.json') ?>">
    <meta name="theme-color" content="#e11d48">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="<?= base_url('assets/images/logo.png') ?>">

    <!-- SweetAlert2 CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- FontAwesome -->
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
            padding-bottom: 75px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        .navbar-mobile {
            background: linear-gradient(135deg, #e11d48, #fb7185);
            color: white;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
        }

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

        .bottom-nav-link:hover, .bottom-nav-link.active {
            color: var(--rose-primary);
            font-weight: 700;
        }

        .bottom-nav-link .small-text {
            font-size: 0.72rem;
            margin-top: 2px;
        }

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

        .btn-rose {
            background-color: var(--rose-primary);
            color: white;
            border-color: var(--rose-primary);
        }
        .btn-rose:hover {
            background-color: var(--rose-hover);
            color: white;
        }
    </style>
</head>
<body class="mobile-body">

    <!-- Header Atas -->
    <nav class="navbar navbar-mobile sticky-top shadow-sm py-2 px-3">
        <div class="container-fluid p-0 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="fas fa-book-reader me-2 fs-5"></i>
                <span class="fw-bold fs-6">GPK MIMHa</span>
            </div>

            <div class="d-flex align-items-center gap-2">
                <div class="text-end" style="max-width: 140px">
                    <span class="small fw-bold text-truncate d-block" title="<?= esc(session()->get('nama_lengkap')) ?>">
                        <?= esc(session()->get('nama_lengkap') ?? 'Pengguna') ?>
                    </span>
                    <span class="badge-role text-uppercase">
                        <?= esc(session()->get('role') ?? 'User') ?>
                    </span>
                </div>
                <!-- Tombol Logout Pemicu Modal -->
                <button type="button" class="btn p-0 text-white opacity-90 ms-1 border-0 bg-transparent" data-bs-toggle="modal" data-bs-target="#modalLogoutMobile">
                    <i class="fas fa-sign-out-alt fs-5"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <div class="container px-3 mt-3">
        <?= $this->renderSection('content') ?>
    </div>

    <!-- Bottom Navigation Bar -->
    <nav class="bottom-nav">
        <?= $this->include('layouts/bottom_nav') ?>
    </nav>

    <!-- MODAL KONFIRMASI LOGOUT MOBILE -->
    <div class="modal fade" id="modalLogoutMobile" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered px-3">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-body p-4 text-center">
                    <div class="mb-3">
                        <span class="d-inline-block rounded-circle p-3 bg-light text-rose">
                            <i class="fas fa-sign-out-alt fa-2x"></i>
                        </span>
                    </div>
                    <h5 class="fw-bold mb-1">Keluar Aplikasi?</h5>
                    <p class="small text-muted mb-4">Sesi Anda akan diakhiri dan harus login kembali untuk mengakses data.</p>
                    <div class="d-grid gap-2">
                        <a href="/logout" class="btn btn-rose rounded-pill fw-bold py-2 shadow-sm">Ya, Keluar Sekarang</a>
                        <button type="button" class="btn btn-light rounded-pill fw-bold py-2 text-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Global -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts') ?>

    <!-- PWA Push Notification Scripts -->
    <script src="<?= base_url('assets/js/pwa-push.js') ?>"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const vapidPublicKey = "<?= env('VAPID_PUBLIC_KEY') ?>";
        if (vapidPublicKey && typeof initPwaPushNotification === 'function') {
            initPwaPushNotification(vapidPublicKey);
        }
    });
    </script>
</body>
</html>