<?php

namespace App\Controllers;

use App\Models\PesertaDidikModel;
use App\Models\UserModel;

class GpkController extends BaseController
{
    protected $pesertaDidikModel;
    protected $userModel;
    protected $db;

    public function __construct()
    {
        $this->pesertaDidikModel = new PesertaDidikModel();
        $this->userModel        = new UserModel();
        $this->db               = \Config\Database::connect();
    }

    // 1. DASHBOARD GPK
    public function index()
    {
        $gpkId = session()->get('id');

        // Ambil data anak yang didampingi oleh GPK ini
        $anakDampingan = $this->db->table('peserta_didik')
                                  ->where('gpk_id', $gpkId)
                                  ->get()->getResultArray();

        $data = [
            'title'         => 'Dashboard GPK',
            'nama_gpk'      => session()->get('nama_lengkap'),
            'total_anak'    => count($anakDampingan),
            'anakDampingan' => $anakDampingan,
        ];

        return view('gpk/dashboard', $data);
    }

    // 2. DAFTAR PESERTA DIDIK (ANAK DAMPINGAN)
    public function pesertaDidik()
    {
        $gpkId = session()->get('id');

        $anakDampingan = $this->db->table('peserta_didik')
                                  ->where('gpk_id', $gpkId)
                                  ->get()->getResultArray();

        $data = [
            'title'         => 'Anak Dampingan',
            'anakDampingan' => $anakDampingan,
        ];

        return view('gpk/peserta_didik', $data);
    }

    // 3. DAFTAR JURNAL HARIAN
    public function jurnal()
    {
        $gpkId = session()->get('id');

        // Nanti tabel journals bisa di-join dengan peserta_didik
        $data = [
            'title'  => 'Jurnal Harian GPK',
            'jurnal' => [] // Siapkan array kosong dulu sebelum tabel journals dibuat
        ];

        return view('gpk/jurnal_index', $data);
    }

    // 4. FORM TAMBAH JURNAL HARIAN UNTUK ANAK SPESIFIK
    public function tambahJurnal($id_pdbk = null)
    {
        $gpkId = session()->get('id');

        // Pastikan anak tersebut adalah anak dampingan dari GPK yang sedang login
        $anak = $this->pesertaDidikModel->where('id', $id_pdbk)
                                        ->where('gpk_id', $gpkId)
                                        ->first();

        if (!$anak) {
            session()->setFlashdata('error', 'Data anak tidak ditemukan atau bukan dampingan Anda.');
            return redirect()->to('/gpk/peserta-didik');
        }

        $data = [
            'title' => 'Isi Jurnal Harian',
            'anak'  => $anak,
        ];

        return view('gpk/jurnal_tambah', $data);
    }

    // 5. PROSES SIMPAN JURNAL HARIAN
    public function simpanJurnal()
    {
        // Logika simpan data jurnal harian (Akademik, Daily Life Skill, Perkembangan)
        // serta upload foto ke Google Drive akan kita olah di tahap jurnal nanti.

        session()->setFlashdata('success', 'Jurnal harian berhasil disimpan.');
        return redirect()->to('/gpk/dashboard');
    }

    // 6. DETAIL JURNAL HARIAN
    public function detailJurnal($id_jurnal)
    {
        $data = [
            'title' => 'Detail Jurnal Harian',
            'id'    => $id_jurnal
        ];

        return view('gpk/jurnal_detail', $data);
    }

    // 7. PROFIL GPK
    public function profil()
    {
        $userId = session()->get('id');
        $user   = $this->userModel->find($userId);

        $data = [
            'title' => 'Profil Saya',
            'user'  => $user,
        ];

        return view('gpk/profil', $data);
    }
}