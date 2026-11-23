<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class JadwalNotifController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // Tampilan Halaman Utama Kelola Jadwal Notifikasi
    public function index()
    {
        $bulanAktif = $this->request->getGet('bulan') ?? date('m');
        $tahunAktif = $this->request->getGet('tahun') ?? date('Y');

        $jadwalList = $this->db->table('pengaturan_jadwal_notif')
                               ->where('MONTH(tanggal)', $bulanAktif)
                               ->where('YEAR(tanggal)', $tahunAktif)
                               ->orderBy('tanggal', 'ASC')
                               ->get()->getResultArray();

        $data = [
            'title'      => 'Kelola Jadwal Notifikasi & Hari Efektif',
            'jadwalList' => $jadwalList,
            'bulanAktif' => sprintf('%02d', $bulanAktif),
            'tahunAktif' => $tahunAktif,
        ];

        return view('admin/jadwal_notif_index', $data);
    }

    // Toggle status aktif/non-aktif tanggal secara cepat (via AJAX)
    public function toggleStatus()
    {
        $tanggal    = $this->request->getPost('tanggal');
        $isAktif    = $this->request->getPost('is_aktif'); // 1 atau 0
        $keterangan = $this->request->getPost('keterangan');

        $existing = $this->db->table('pengaturan_jadwal_notif')->where('tanggal', $tanggal)->get()->getRowArray();

        if ($existing) {
            $this->db->table('pengaturan_jadwal_notif')
                     ->where('tanggal', $tanggal)
                     ->update([
                         'is_aktif'   => $isAktif,
                         'keterangan' => $keterangan ?? $existing['keterangan']
                     ]);
        } else {
            $this->db->table('pengaturan_jadwal_notif')->insert([
                'tanggal'    => $tanggal,
                'is_aktif'   => $isAktif,
                'keterangan' => $keterangan ?? ($isAktif ? 'Hari Sekolah' : 'Hari Libur')
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'success', 
            'message' => 'Status tanggal berhasil diperbarui.'
        ]);
    }

    // Generate otomatis tanggal 1 bulan (Senin-Jumat = Aktif, Sabtu-Minggu = Non-Aktif)
    public function generateBulan()
    {
        $bulan = $this->request->getPost('bulan') ?? date('m');
        $tahun = $this->request->getPost('tahun') ?? date('Y');

        $jumlahHari = cal_days_in_month(CAL_GREGORIAN, (int)$bulan, (int)$tahun);

        for ($d = 1; $d <= $jumlahHari; $d++) {
            $tglStr    = sprintf('%04d-%02d-%02d', $tahun, $bulan, $d);
            $dayOfWeek = date('N', strtotime($tglStr)); // 1 (Senin) .. 7 (Minggu)

            // Sabtu (6) & Minggu (7) otomatis Non-Aktif (0), Senin-Jumat Aktif (1)
            $isAktif    = ($dayOfWeek >= 6) ? 0 : 1;
            $keterangan = ($dayOfWeek >= 6) ? 'Akhir Pekan' : 'Hari Efektif KBM';

            $existing = $this->db->table('pengaturan_jadwal_notif')->where('tanggal', $tglStr)->get()->getRowArray();

            if (!$existing) {
                $this->db->table('pengaturan_jadwal_notif')->insert([
                    'tanggal'    => $tglStr,
                    'is_aktif'   => $isAktif,
                    'keterangan' => $keterangan
                ]);
            }
        }

        session()->setFlashdata('success', "Jadwal notifikasi bulan $bulan/$tahun berhasil digenerate!");
        return redirect()->to('/admin/jadwal-notif?bulan=' . $bulan . '&tahun=' . $tahun);
    }
}