// Service Worker PWA MIMHa

self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(clients.claim());
});

// 1. Handling Event PUSH Notification dari Server/CLI
self.addEventListener('push', function(event) {
    if (!event.data) return;

    let payload = {};
    try {
        payload = event.data.json();
    } catch (e) {
        payload = {
            title: 'Notifikasi PDBK MIMHa',
            body: event.data.text()
        };
    }

    const title = payload.title || 'Notifikasi PDBK MIMHa';
    
    // Konfigurasi Tampilan Notifikasi Native PWA Modern
    const options = {
        body: payload.body || 'Ada pembaruan jurnal harian PDBK.',
        icon: '/assets/images/logo.png',          // Icon utama notifikasi (Logo MIMHa)
        badge: '/assets/images/badge-icon.png',    // Icon kecil di status bar HP (Opsional)
        vibrate: [200, 100, 200],                  // Pola getar HP (Getar-Diam-Getar)
        tag: 'jurnal-notif-' + Date.now(),          // Mengelompokkan notifikasi
        renotify: true,
        data: {
            url: payload.url || '/gpk/dashboard'
        },
        // Tombol Aksi di Bawah Notifikasi
        actions: [
            {
                action: 'open_url',
                title: 'Buka Aplikasi'
            },
            {
                action: 'close',
                title: 'Tutup'
            }
        ]
    };

    // 2. Tampilkan Pop-up Push Notification Native OS/HP
    const notificationPromise = self.registration.showNotification(title, options);

    // 3. Set Balloon / Badge di Icon Aplikasi HP (App Badging API)
    let badgePromise = Promise.resolve();
    if ('setAppBadge' in navigator) {
        badgePromise = navigator.setAppBadge(payload.unreadCount || 1);
    }

    event.waitUntil(Promise.all([notificationPromise, badgePromise]));
});

// Handling Ketika Notifikasi Diklik oleh User
self.addEventListener('notificationclick', function(event) {
    event.notification.close();

    // Hapus/Bersihkan Balloon Icon saat aplikasi dibuka
    if ('clearAppBadge' in navigator) {
        navigator.clearAppBadge();
    }

    if (event.action === 'close') {
        return;
    }

    const targetUrl = event.notification.data.url || '/';

    // Buka atau fokus ke tab aplikasi
    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function(clientList) {
            for (let i = 0; i < clientList.length; i++) {
                const client = clientList[i];
                if (client.url.includes(targetUrl) && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});