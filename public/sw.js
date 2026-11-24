// public/sw.js - MIMHa Web Push Worker

self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(clients.claim());
});

self.addEventListener('push', function(event) {
    let payload = {
        title: 'Pengingat Jurnal Harian PDBK',
        body: 'Assalamu\'alaikum, silakan periksa dan isi Jurnal Harian PDBK Anda hari ini.',
        url: '/gpk/dashboard'
    };

    if (event.data) {
        try {
            const dataJson = event.data.json();
            if (dataJson.title) payload.title = dataJson.title;
            if (dataJson.body)  payload.body  = dataJson.body;
            if (dataJson.url)   payload.url   = dataJson.url;
        } catch (e) {
            try {
                const textData = event.data.text();
                if (textData) payload.body = textData;
            } catch (err) {}
        }
    }

    const swUrl = self.location.href;
    const basePath = swUrl.substring(0, swUrl.lastIndexOf('/') + 1);

    const options = {
        body: payload.body,
        icon: basePath + 'assets/images/logo.png',
        badge: basePath + 'assets/images/logo.png',
        vibrate: [100, 50, 100],
        tag: 'mimha-push-' + Date.now(),
        renotify: true,
        data: {
            url: payload.url
        }
    };

    event.waitUntil(
        self.registration.showNotification(payload.title, options)
    );
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();

    const targetUrl = (event.notification.data && event.notification.data.url) 
        ? event.notification.data.url 
        : '/gpk/dashboard';

    const fullUrl = self.location.origin + targetUrl;

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function(clientList) {
            for (let i = 0; i < clientList.length; i++) {
                const client = clientList[i];
                if (client.url.includes(targetUrl) && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(fullUrl);
            }
        })
    );
});