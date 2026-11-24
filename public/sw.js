// Service Worker PWA MIMHa

self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(clients.claim());
});

self.addEventListener('push', function(event) {
    let payload = {};
    if (event.data) {
        try {
            payload = event.data.json();
        } catch (e) {
            payload = {
                title: 'Notifikasi MIMHa',
                body: event.data.text()
            };
        }
    }

    const title = payload.title || 'Notifikasi MIMHa';
    
    // Gunakan URL Absolut Penuh ke Domain Anda
    const baseUrl = self.location.origin;

    const options = {
        body: payload.body || 'Ada pengingat jurnal harian PDBK.',
        icon: baseUrl + '/assets/images/logo.png',
        badge: baseUrl + '/assets/images/logo.png',
        vibrate: [200, 100, 200],
        tag: 'jurnal-notif-' + Date.now(),
        renotify: true,
        data: {
            url: payload.url || '/gpk/dashboard'
        }
    };

    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    const targetUrl = event.notification.data.url || '/';

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