// Konversi VAPID Public Key dari Base64 String ke Uint8Array
function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

// Inisialisasi Pendaftaran Web Push Notification
async function initPwaPushNotification(vapidPublicKey) {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
        console.warn('Browser/HP ini tidak mendukung Web Push Notification.');
        return;
    }

    try {
        const registration = await navigator.serviceWorker.ready;
        
        // 1. Minta Izin Notifikasi ke User
        const permission = await Notification.requestPermission();
        if (permission !== 'granted') {
            console.log('Izin notifikasi ditolak atau ditutup oleh pengguna.');
            return;
        }

        // 2. Dapatkan Subscription Token dari Browser
        let subscription = await registration.pushManager.getSubscription();

        if (!subscription) {
            const convertedVapidKey = urlBase64ToUint8Array(vapidPublicKey);
            subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: convertedVapidKey
            });
        }

        // 3. Konversi Subscription ke JSON Lengkap
        const subData = subscription.toJSON();

        // 4. Kirim Token Subscription ke Server CodeIgniter 4 (Menyesuaikan Origin Dynamic)
        const targetUrl = window.location.origin + '/pwa/subscribe';

        const response = await fetch(targetUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(subData)
        });

        const resJson = await response.json();
        console.log('Respons Server Subscription:', resJson);

    } catch (error) {
        console.error('Gagal mengaktifkan Web Push PWA:', error);
    }
}

// Function Helper Toast SweetAlert2
function showProfessionalToast(title, message, icon = 'info', targetUrl = null) {
    if (typeof Swal === 'undefined') return;
    
    Swal.fire({
        title: title,
        text: message,
        icon: icon,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        background: '#ffffff',
        color: '#1e293b',
        iconColor: '#e11d48',
        customClass: {
            popup: 'shadow-lg rounded-4 border-0 mt-2'
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
            if (targetUrl) {
                toast.addEventListener('click', () => {
                    window.location.href = targetUrl;
                });
            }
        }
    });
}