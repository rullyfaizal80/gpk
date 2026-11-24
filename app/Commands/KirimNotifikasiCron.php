<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class KirimNotifikasiCron extends BaseCommand
{
    protected $group       = 'Cron';
    protected $name        = 'cron:kirim-notif';
    protected $description = 'Kirim Notifikasi PWA Terjadwal (GPK, Walas/Kepsek, Ortu)';
    protected $usage       = 'cron:kirim-notif [jam]';

    public function run(array $params)
    {
        $jamTarget = $params[0] ?? null;
        $today     = date('Y-m-d');
        $db        = \Config\Database::connect();

        // 1. Cek Hari Aktif di Pengaturan Admin
        $jadwal = $db->table('pengaturan_jadwal_notif')
                     ->where('tanggal', $today)
                     ->get()
                     ->getRowArray();

        if ($jadwal && (int)$jadwal['is_aktif'] === 0) {
            CLI::write("[{$jamTarget}] Notifikasi dibatalkan: Hari ini ({$today}) di-set LIBUR/NON-AKTIF di Admin.", 'yellow');
            return;
        }

        CLI::write("[{$jamTarget}] Memproses pengiriman Web Push Notification PWA...", 'green');

        switch ($jamTarget) {
            case '14.30':
                $this->prosesPengingatGPK($db, $today);
                break;

            case '15.00':
                $this->prosesNotifWalasKepsek($db, $today);
                break;

            case '19.00':
                $this->prosesNotifOrangTua($db, $today);
                break;

            default:
                CLI::error("Jam target tidak valid. Gunakan 14.30, 15.00, atau 19.00");
                break;
        }
    }

    private function prosesPengingatGPK($db, $today)
    {
        $title = "Pengingat Jurnal Harian PDBK";
        $body  = "Assalamu'alaikum Ustadz/Ustadzah, mengingatkan untuk mengisi Jurnal Harian PDBK hari ini.";
        $url   = "/gpk/jurnal/tambah";

        $res = $this->kirimWebPush($db, 'gpk', $title, $body, $url);
        CLI::write("Pengingat GPK (14.30): Terkirim={$res['sukses']}, Gagal={$res['gagal']}.", 'green');
    }

    private function prosesNotifWalasKepsek($db, $today)
    {
        $title = "Jurnal Harian PDBK Masuk";
        $body  = "Jurnal harian PDBK anak dampingan telah diisi. Silakan pantau di aplikasi.";
        $url   = "/admin/jurnal";

        $resWalas  = $this->kirimWebPush($db, 'walas', $title, $body, $url);
        $resKepsek = $this->kirimWebPush($db, 'kepsek', $title, $body, $url);
        
        $totalSukses = $resWalas['sukses'] + $resKepsek['sukses'];
        $totalGagal  = $resWalas['gagal'] + $resKepsek['gagal'];
        
        CLI::write("Notifikasi Walas & Kepsek (15.00): Terkirim={$totalSukses}, Gagal={$totalGagal}.", 'green');
    }

    private function prosesNotifOrangTua($db, $today)
    {
        $title = "Laporan Jurnal Harian Ananda";
        $body  = "Assalamu'alaikum Ayah/Bunda, Laporan Jurnal Harian Ananda hari ini sudah tersedia.";
        $url   = "/ortu/jurnal";

        $res = $this->kirimWebPush($db, 'orang_tua', $title, $body, $url);
        CLI::write("Notifikasi Orang Tua (19.00): Terkirim={$res['sukses']}, Gagal={$res['gagal']}.", 'green');
    }

    /**
     * Fungsi Helper Pengiriman Web Push VAPID dengan Output Logging Detail
     */
    private function kirimWebPush($db, $roleTarget, $title, $body, $url)
    {
        $auth = [
            'VAPID' => [
                'subject'    => env('VAPID_SUBJECT'),
                'publicKey'  => env('VAPID_PUBLIC_KEY'),
                'privateKey' => env('VAPID_PRIVATE_KEY'),
            ],
        ];

        // Ambil subscription token user berdasarkan role
        $subscriptions = $db->table('pwa_subscriptions')
                            ->select('pwa_subscriptions.*, users.username, users.role')
                            ->join('users', 'users.id = pwa_subscriptions.user_id')
                            ->where('users.role', $roleTarget)
                            ->get()
                            ->getResultArray();

        if (empty($subscriptions)) {
            CLI::write("  ↳ [INFO] Tidak ada token subscription di DB untuk role '{$roleTarget}'.", 'yellow');
            return ['sukses' => 0, 'gagal' => 0];
        }

        $webPush = new WebPush($auth);
        $sukses  = 0;
        $gagal   = 0;

        foreach ($subscriptions as $sub) {
            $subscription = Subscription::create([
                'endpoint'        => $sub['endpoint'],
                'publicKey'       => $sub['public_key'],
                'authToken'       => $sub['auth_token'],
                'contentEncoding' => !empty($sub['content_encoding']) ? $sub['content_encoding'] : 'aesgcm',
            ]);

            $payload = json_encode([
                'title'       => $title,
                'body'        => $body,
                'url'         => $url,
                'unreadCount' => 1
            ]);

            $webPush->queueNotification($subscription, $payload);
        }

        foreach ($webPush->flush() as $report) {
            $endpoint = $report->getRequest()->getUri()->__toString();
            if ($report->isSuccess()) {
                $sukses++;
                CLI::write("  ↳ [OK] Push notification terkirim ke endpoint: " . substr($endpoint, 0, 45) . "...", 'green');
            } else {
                $gagal++;
                CLI::write("  ↳ [ERROR] Gagal kirim ke endpoint: {$report->getReason()}", 'red');
                
                // Jika endpoint kadaluarsa/unregistered, bersihkan dari database
                if ($report->isSubscriptionExpired()) {
                    $db->table('pwa_subscriptions')->where('endpoint', $endpoint)->delete();
                    CLI::write("  ↳ [CLEANUP] Token expired dihapus dari database.", 'yellow');
                }
            }
        }

        return ['sukses' => $sukses, 'gagal' => $gagal];
    }
}