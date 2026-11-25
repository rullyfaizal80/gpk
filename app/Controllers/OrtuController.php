<?php

namespace App\Controllers;

use App\Models\UserModel;

class OrtuController extends BaseController
{
    protected $db;
    protected $userModel;

    public function __construct()
    {
        $this->db        = \Config\Database::connect();
        $this->userModel = new UserModel();
    }

    // Helper Private: Mengambil SEMUA data anak yang terhubung dengan akun ortu ini
    private function getAnakOrtu()
    {
        $userId = session()->get('id');
        
        // Ambil semua anak dalam bentuk Multi-Array (getResultArray)
        return $this->db->table('peserta_didik')
                        ->select('peserta_didik.*, users.nama_lengkap as nama_gpk')
                        ->join('users', 'users.id = peserta_didik.gpk_id', 'left')
                        ->where('peserta_didik.ortu_id', $userId)
                        ->get()->getResultArray();
    }

    // 1. DASHBOARD ORANG TUA
public function index()
{
    $anakList = $this->getAnakOrtu();

    if (empty($anakList)) {
        $data = [
            'title' => 'Dashboard Orang Tua',
            'anak'  => [],
        ];
        return view('ortu/dashboard', $data);
    }

    $anakIds = array_column($anakList, 'id');

    $bulanAktif = $this->request->getGet('bulan') ?? date('m');
    $tahunAktif = $this->request->getGet('tahun') ?? date('Y');

    // PERBAIKAN: Tambahkan JOIN ke tabel peserta_didik untuk mengambil nama_pdbk
    $jurnalList = $this->db->table('jurnal')
                           ->select('jurnal.*, peserta_didik.nama_pdbk, (SELECT google_file_id FROM jurnal_foto WHERE jurnal_id = jurnal.id LIMIT 1) as foto_sampul')
                           ->join('peserta_didik', 'peserta_didik.id = jurnal.pdbk_id')
                           ->whereIn('jurnal.pdbk_id', $anakIds)
                           ->where('MONTH(jurnal.tanggal)', $bulanAktif)
                           ->where('YEAR(jurnal.tanggal)', $tahunAktif)
                           ->orderBy('jurnal.tanggal', 'DESC')
                           ->orderBy('jurnal.id', 'DESC')
                           ->get()->getResultArray();

    $data = [
        'title'      => 'Dashboard Orang Tua',
        'anak'       => $anakList,
        'jurnalList' => $jurnalList,
        'bulanAktif' => sprintf('%02d', $bulanAktif),
        'tahunAktif' => $tahunAktif,
    ];

    return view('ortu/dashboard', $data);
}

    // 2. DAFTAR JURNAL PERKEMBANGAN (SEMUA ANAK PER BULAN)
    public function jurnal()
    {
        $anakList = $this->getAnakOrtu();

        if (empty($anakList)) {
            return redirect()->to('/ortu/dashboard');
        }

        $anakIds = array_column($anakList, 'id');

        $bulanAktif = $this->request->getGet('bulan') ?? date('m');
        $tahunAktif = $this->request->getGet('tahun') ?? date('Y');

        // Mengambil seluruh jurnal milik anak ortu ini dengan JOIN ke peserta_didik
        $jurnalList = $this->db->table('jurnal')
                               ->select('jurnal.*, peserta_didik.nama_pdbk, (SELECT google_file_id FROM jurnal_foto WHERE jurnal_id = jurnal.id LIMIT 1) as foto_sampul')
                               ->join('peserta_didik', 'peserta_didik.id = jurnal.pdbk_id')
                               ->whereIn('jurnal.pdbk_id', $anakIds)
                               ->where('MONTH(jurnal.tanggal)', $bulanAktif)
                               ->where('YEAR(jurnal.tanggal)', $tahunAktif)
                               ->orderBy('jurnal.tanggal', 'DESC')
                               ->orderBy('jurnal.id', 'DESC')
                               ->get()->getResultArray();

        $data = [
            'title'      => 'Jurnal Perkembangan Ananda',
            'anak'       => $anakList,
            'jurnalList' => $jurnalList,
            'bulanAktif' => sprintf('%02d', $bulanAktif),
            'tahunAktif' => $tahunAktif,
        ];

        return view('ortu/jurnal_index', $data);
    }

    // 3. DETAIL JURNAL PERKEMBANGAN ANANDA
    public function detailJurnal($id = null)
    {
        $anakList = $this->getAnakOrtu();

        if (empty($anakList)) {
            session()->setFlashdata('error', 'Akses ditolak.');
            return redirect()->to('/ortu/dashboard');
        }

        $anakIds = array_column($anakList, 'id');

        // Proteksi: Hanya bisa membaca detail jurnal jika pdbk_id termasuk salah satu anak milik ortu ini
        $jurnal = $this->db->table('jurnal')
                           ->select('jurnal.*, peserta_didik.nama_pdbk, peserta_didik.kelas, users.nama_lengkap as nama_gpk')
                           ->join('peserta_didik', 'peserta_didik.id = jurnal.pdbk_id')
                           ->join('users', 'users.id = jurnal.gpk_id', 'left')
                           ->where('jurnal.id', $id)
                           ->whereIn('jurnal.pdbk_id', $anakIds)
                           ->get()->getRowArray();

        if (!$jurnal) {
            session()->setFlashdata('error', 'Jurnal tidak ditemukan atau bukan milik ananda.');
            return redirect()->to('/ortu/dashboard');
        }

        $fotos = $this->db->table('jurnal_foto')
                         ->where('jurnal_id', $id)
                         ->get()->getResultArray();

        $data = [
            'title'  => 'Detail Jurnal Ananda',
            'jurnal' => $jurnal,
            'fotos'  => $fotos
        ];

        return view('ortu/jurnal_detail', $data);
    }

    // 4. PROFIL AKUN ORTU & GANTI PASSWORD
    public function profil()
    {
        $userId   = session()->get('id');
        $user     = $this->userModel->find($userId);
        $anakList = $this->getAnakOrtu();

        $data = [
            'title' => 'Akun Orang Tua',
            'user'  => $user,
            'anak'  => $anakList,
        ];

        return view('ortu/profil', $data);
    }
}