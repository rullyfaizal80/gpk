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

    // Helper Private: Mengambil data anak yang terikat dengan akun ortu ini
    private function getAnakOrtu()
    {
        $userId = session()->get('id');
        
        // Cari data anak berdasarkan ortu_id (atau user_id ortu yang terhubung di tabel peserta_didik)
        $anak = $this->db->table('peserta_didik')
                         ->select('peserta_didik.*, users.nama_lengkap as nama_gpk')
                         ->join('users', 'users.id = peserta_didik.gpk_id', 'left')
                         ->where('peserta_didik.ortu_id', $userId)
                         ->get()->getRowArray();

        return $anak;
    }

    // 1. DASHBOARD ORANG TUA
    public function index()
    {
        $anak = $this->getAnakOrtu();

        if (!$anak) {
            $data = [
                'title' => 'Dashboard Orang Tua',
                'anak'  => null,
            ];
            return view('ortu/dashboard', $data);
        }

        $bulanAktif = $this->request->getGet('bulan') ?? date('m');
        $tahunAktif = $this->request->getGet('tahun') ?? date('Y');

        // Ambil riwayat jurnal khusus anak ortu ini
        $jurnalList = $this->db->table('jurnal')
                               ->select('jurnal.*, (SELECT google_file_id FROM jurnal_foto WHERE jurnal_id = jurnal.id LIMIT 1) as foto_sampul')
                               ->where('jurnal.pdbk_id', $anak['id'])
                               ->where('MONTH(jurnal.tanggal)', $bulanAktif)
                               ->where('YEAR(jurnal.tanggal)', $tahunAktif)
                               ->orderBy('jurnal.tanggal', 'DESC')
                               ->orderBy('jurnal.id', 'DESC')
                               ->get()->getResultArray();

        $data = [
            'title'      => 'Dashboard Orang Tua',
            'anak'       => $anak,
            'jurnalList' => $jurnalList,
            'bulanAktif' => sprintf('%02d', $bulanAktif),
            'tahunAktif' => $tahunAktif,
        ];

        return view('ortu/dashboard', $data);
    }

    // 2. DAFTAR JURNAL PERKEMBANGAN
    public function jurnal()
    {
        $anak = $this->getAnakOrtu();

        if (!$anak) {
            return redirect()->to('/ortu/dashboard');
        }

        $bulanAktif = $this->request->getGet('bulan') ?? date('m');
        $tahunAktif = $this->request->getGet('tahun') ?? date('Y');

        $jurnalList = $this->db->table('jurnal')
                               ->select('jurnal.*, (SELECT google_file_id FROM jurnal_foto WHERE jurnal_id = jurnal.id LIMIT 1) as foto_sampul')
                               ->where('jurnal.pdbk_id', $anak['id'])
                               ->where('MONTH(jurnal.tanggal)', $bulanAktif)
                               ->where('YEAR(jurnal.tanggal)', $tahunAktif)
                               ->orderBy('jurnal.tanggal', 'DESC')
                               ->orderBy('jurnal.id', 'DESC')
                               ->get()->getResultArray();

        $data = [
            'title'      => 'Jurnal Perkembangan Ananda',
            'anak'       => $anak,
            'jurnalList' => $jurnalList,
            'bulanAktif' => sprintf('%02d', $bulanAktif),
            'tahunAktif' => $tahunAktif,
        ];

        return view('ortu/jurnal_index', $data);
    }

    // 3. DETAIL JURNAL PERKEMBANGAN ANANDA
    public function detailJurnal($id = null)
    {
        $anak = $this->getAnakOrtu();

        if (!$anak) {
            session()->setFlashdata('error', 'Akses ditolak.');
            return redirect()->to('/ortu/dashboard');
        }

        // Proteksi: Hanya bisa membaca detail jurnal dari anaknya sendiri
        $jurnal = $this->db->table('jurnal')
                           ->select('jurnal.*, peserta_didik.nama_pdbk, peserta_didik.kelas, users.nama_lengkap as nama_gpk')
                           ->join('peserta_didik', 'peserta_didik.id = jurnal.pdbk_id')
                           ->join('users', 'users.id = jurnal.gpk_id', 'left')
                           ->where('jurnal.id', $id)
                           ->where('jurnal.pdbk_id', $anak['id'])
                           ->get()->getRowArray();

        if (!$jurnal) {
            session()->setFlashdata('error', 'Jurnal tidak ditemukan atau bukan milik ananda.');
            return redirect()->to('/ortu/jurnal');
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
        $userId = session()->get('id');
        $user   = $this->userModel->find($userId);
        $anak   = $this->getAnakOrtu();

        $data = [
            'title' => 'Akun Orang Tua',
            'user'  => $user,
            'anak'  => $anak,
        ];

        return view('ortu/profil', $data);
    }

    // PROSES GANTI PASSWORD ORTU
    public function updatePassword()
    {
        $userId = session()->get('id');
        $user   = $this->userModel->find($userId);

        $passwordLama = $this->request->getPost('password_lama');
        $passwordBaru = $this->request->getPost('password_baru');
        $konfirmasi   = $this->request->getPost('konfirmasi_password');

        $passwordMatch = password_verify($passwordLama, $user['password'] ?? '') || ($user['password'] === $passwordLama);

        if (!$passwordMatch) {
            session()->setFlashdata('error', 'Password saat ini salah.');
            return redirect()->back();
        }

        if ($passwordBaru !== $konfirmasi) {
            session()->setFlashdata('error', 'Konfirmasi password baru tidak cocok.');
            return redirect()->back();
        }

        if (strlen($passwordBaru) < 6) {
            session()->setFlashdata('error', 'Password baru minimal 6 karakter.');
            return redirect()->back();
        }

        $this->userModel->update($userId, [
            'password'   => password_hash($passwordBaru, PASSWORD_BCRYPT),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        session()->setFlashdata('success', 'Password berhasil diperbarui.');
        return redirect()->to('/ortu/profil');
    }
}