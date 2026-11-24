// Service Worker untuk Web Push Notification PWA MIMHa

self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(clients.claim());
});

// Event menangkap sinyal Push Notifikasi dari Server
self.addEventListener('push', function(event) {
    let payload = {
        title: 'Pengingat Jurnal Harian MIMHa',
        body: 'Assalamu\'alaikum, silakan periksa aplikasi Jurnal Harian PDBK Anda.',
        url: '/gpk/dashboard'
    };

    // Parsing payload data JSON yang dikirim server CodeIgniter
    if (event.data) {
        try {
            const dataJson = event.data.json();
            payload.title = dataJson.title || payload.title;
            payload.body  = dataJson.body  || payload.body;
            payload.url   = dataJson.url   || payload.url;
        } catch (e) {
            // Fallback jika payload dikirim sebagai plain text
            const textData = event.data.text();
            if (textData) {
                payload.body = textData;
            }
        }
    }

    const originUrl = self.location.origin;

    const options = {
        body: payload.body,
        icon: originUrl + '/assets/images/logo.png',
        badge: originUrl + '/assets/images/logo.png',
        vibrate: [200, 100, 200, 100, 200],
        tag: 'mimha-jurnal-notification',
        renotify: true,
        data: {
            url: payload.url
        }
    };

    // PENTING: event.waitUntil wajib membungkus showNotification agar Chrome Android 
    // tidak menampilkan pesan "Situs ini diperbarui di latar belakang".
    event.waitUntil(
        self.registration.showNotification(payload.title, options)
    );
});

// Event ketika notifikasi di-klik oleh pengguna
self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    
    const targetUrl = event.notification.data && event.notification.data.url 
        ? event.notification.data.url 
        : '/';

    const fullUrl = self.location.origin + targetUrl;

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function(clientList) {
            for (let i = 0; i < clientList.length; i++) {
                const client = clientList[i];
                if (client.url === fullUrl && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(fullUrl);
            }
        })
    );
});