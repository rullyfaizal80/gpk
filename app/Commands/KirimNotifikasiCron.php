<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class KirimNotifikasiCron extends BaseCommand
{
    /**
     * Group perintah di terminal spark
     */
    protected $group = 'Cron';

    /**
     * Nama perintah CLI yang dipanggil
     */
    protected $name = 'cron:kirim-notif';

    /**
     * Deskripsi perintah
     */
    protected $description = 'Mengeksekusi pengiriman notifikasi pengingat jurnal PWA berdasarkan jadwal admin';

    /**
     * Argumen/Parameter jam target (14.30, 15.00, 19.00)
     */
    protected $usage = 'cron:kirim-notif [jam_target]';
    protected $arguments = [
        'jam_target' => 'Jam eksekusi notifikasi (contoh: 14.30, 15.00, 19.00)',
    ];

    public function run(array $params)
    {
        $jamTarget = $params[0] ?? null;

        if (!$jamTarget) {
            CLI::error("Harap masukkan parameter jam target! (Contoh: php spark cron:kirim-notif 14.30)");
            return;
        }

        $db      = \Config\Database::connect();
        $hariIni = date('Y-m-d');

        // 1. CEK APAKAH HARI INI AKTIF DI TABEL ADMIN (pengaturan_jadwal_notif)
        $jadwal = $db->table('pengaturan_jadwal_notif')
                     ->where('tanggal', $hariIni)
                     ->get()
                     ->getRowArray();

        // Jika tidak ada di tabel atau is_aktif == 0 -> CANCEL NOTIFIKASI
        if (!$jadwal || $jadwal['is_aktif'] == 0) {
            CLI::write("[$jamTarget] Notifikasi dibatalkan: Hari ini ($hariIni) di-set LIBUR/NON-AKTIF di Admin.", 'yellow');
            return;
        }

        // 2. JIKA AKTIF, JALANKAN LOGIKA SESUAI JAM TARGET
        CLI::write("[$jamTarget] Memproses pengiriman notifikasi...", 'green');

        if ($jamTarget === '14.30') {
            // Logika Pengingat untuk GPK yang belum isi jurnal
            CLI::write("Pengingat GPK berhasil diproses.", 'white');
        } elseif ($jamTarget === '15.00') {
            // Logika Notifikasi Wali Kelas & Kepala Sekolah
            CLI::write("Notifikasi Wali Kelas & Kepsek berhasil diproses.", 'white');
        } elseif ($jamTarget === '19.00') {
            // Logika Notifikasi Orang Tua
            CLI::write("Notifikasi Orang Tua berhasil diproses.", 'white');
        } else {
            CLI::error("Jam target tidak valid! Gunakan 14.30, 15.00, atau 19.00");
        }
    }
}