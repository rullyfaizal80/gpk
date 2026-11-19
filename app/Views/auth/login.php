<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Jurnal Harian GPK</title>

    <!-- Favicon Browser & Apple Touch Icon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>">
    <link rel="apple-touch-icon" href="<?= base_url('assets/images/logo.png') ?>">
    
    <!-- PWA Meta Tags -->
    <link rel="manifest" href="<?= base_url('manifest.json') ?>">
    <meta name="theme-color" content="#e11d48">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Jurnal GPK">
    <link rel="apple-touch-icon" href="<?= base_url('assets/images/logo.png') ?>">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #fff1f2; /* Latar belakang rose lembut */
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(225, 29, 72, 0.12);
            border: none;
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #e11d48, #fb7185);
            color: white;
            padding: 35px 20px;
            text-align: center;
        }
        .text-theme {
            color: #e11d48 !important;
        }
        .btn-theme {
            background-color: #e11d48;
            border-color: #e11d48;
            color: white;
            transition: all 0.2s ease-in-out;
        }
        .btn-theme:hover, .btn-theme:focus {
            background-color: #be123c;
            border-color: #be123c;
            color: white;
        }
        .input-group-text {
            border-right: none;
            background-color: transparent;
        }
        .form-control {
            border-left: none;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #dee2e6;
        }
        .input-group:focus-within {
            box-shadow: 0 0 0 0.25rem rgba(225, 29, 72, 0.25);
            border-radius: 0.375rem;
        }
        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control {
            border-color: #fda4af;
        }

        /* Banner Install PWA In-Card (Tepat di bawah tombol login) */
        .pwa-install-box {
            background-color: #fff1f2;
            border: 1px dashed #fecdd3;
            border-radius: 12px;
            padding: 12px 15px;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container px-3 d-flex justify-content-center">
        <div class="card login-card">
            
            <!-- Judul Aplikasi Baru: Jurnal Harian GPK -->
            <div class="login-header">
                <i class="fas fa-book-reader fa-3x mb-3 shadow-sm rounded-circle p-3 bg-white text-theme"></i>
                <h4 class="mb-0 fw-bold">Jurnal Harian GPK</h4>
                <small class="opacity-75">Sistem Pelaporan Inklusi MIMHa</small>
            </div>
            
            <div class="card-body p-4 bg-white">
                
                <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger d-flex align-items-center p-2 mb-4 small border-0 bg-danger text-white rounded-3" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div><?= session()->getFlashdata('error') ?></div>
                    </div>
                <?php endif; ?>
                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success p-2 mb-4 small border-0 rounded-3 text-center" role="alert">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('login/process') ?>" method="post">
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user text-muted"></i></span>
                            <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock text-muted"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-theme w-100 py-2 fw-bold text-uppercase shadow-sm rounded-3">
                        Masuk <i class="fas fa-sign-in-alt ms-2"></i>
                    </button>
                    
                </form>

                <!-- BANNER INSTALL PWA (DI BAWAH TOMBOL LOGIN, TIDAK POP-UP MELAYANG) -->
                <div id="install-banner" class="pwa-install-box d-none">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-mobile-alt fa-2x text-theme me-3"></i>
                        <div class="text-start">
                            <strong class="text-dark small d-block">Instal Aplikasi Mobile</strong>
                            <span class="text-muted" style="font-size: 0.75rem;">Tambahkan ke Layar Utama HP untuk akses cepat.</span>
                        </div>
                    </div>
                    <button type="button" id="btn-install" class="btn btn-sm btn-outline-danger w-100 fw-bold rounded-pill shadow-sm">
                        <i class="fas fa-download me-1"></i> Instal Sekarang
                    </button>
                </div>

            </div>
            
            <div class="card-footer bg-light border-0 text-center py-3">
                <small class="text-muted fw-semibold">© <?= date('Y') ?> MIMHa Apps</small>
            </div>
            
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script PWA -->
    <script>
        // Registrasi Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').then((registration) => {
                    console.log('ServiceWorker terdaftar:', registration.scope);
                }).catch((err) => {
                    console.log('ServiceWorker gagal:', err);
                });
            });
        }

        let deferredPrompt;
        const installBanner = document.getElementById('install-banner');
        const btnInstall = document.getElementById('btn-install');

        // Cek apakah aplikasi sudah dalam mode PWA/Installed (Standalone)
        const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone;

        window.addEventListener('beforeinstallprompt', (e) => {
            // Jika belum di-install, tampilkan tombol di bawah form login
            if (!isStandalone) {
                e.preventDefault();
                deferredPrompt = e;
                installBanner.classList.remove('d-none');
            }
        });

        btnInstall.addEventListener('click', async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                if (outcome === 'accepted') {
                    installBanner.classList.add('d-none');
                }
                deferredPrompt = null;
            }
        });

        window.addEventListener('appinstalled', () => {
            installBanner.classList.add('d-none');
            deferredPrompt = null;
        });
    </script>
</body>
</html>