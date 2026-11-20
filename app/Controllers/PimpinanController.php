<?php

namespace App\Controllers;

class PimpinanController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // 1. DASHBOARD PIMPINAN
    public function index()
    {
        $bulanAktif = date('m');
        $tahunAktif = date('Y');

        // Statistik Utama
        $totalPdbk     = $this->db->table('peserta_didik')->countAllResults();
        $totalGpk      = $this->db->table('users')->where('role', 'gpk')->countAllResults();
        $jurnalHariIni = $this->db->table('jurnal')->where('tanggal', date('Y-m-d'))->countAllResults();
        $jurnalBulanIni= $this->db->table('jurnal')
                                  ->where('MONTH(tanggal)', $bulanAktif)
                                  ->where('YEAR(tanggal)', $tahunAktif)
                                  ->countAllResults();

        // Rekap Keterisian per Unit Bulan Ini
        $rekapUnit = [
            'PERIL' => $this->db->table('jurnal')->where('unit', 'PERIL')->where('MONTH(tanggal)', $bulanAktif)->where('YEAR(tanggal)', $tahunAktif)->countAllResults(),
            'MI'    => $this->db->table('jurnal')->where('unit', 'MI')->where('MONTH(tanggal)', $bulanAktif)->where('YEAR(tanggal)', $tahunAktif)->countAllResults(),
            'MTs'   => $this->db->table('jurnal')->where('unit', 'MTs')->where('MONTH(tanggal)', $bulanAktif)->where('YEAR(tanggal)', $tahunAktif)->countAllResults(),
        ];

        // 5 Jurnal Terbaru
        $latestJurnal = $this->db->table('jurnal')
                                 ->select('jurnal.*, peserta_didik.nama_pdbk, peserta_didik.kelas, users.nama_lengkap as nama_gpk')
                                 ->join('peserta_didik', 'peserta_didik.id = jurnal.pdbk_id')
                                 ->join('users', 'users.id = jurnal.gpk_id', 'left')
                                 ->orderBy('jurnal.tanggal', 'DESC')
                                 ->orderBy('jurnal.id', 'DESC')
                                 ->limit(5)
                                 ->get()->getResultArray();

        $data = [
            'title'          => 'Dashboard Pimpinan',
            'totalPdbk'      => $totalPdbk,
            'totalGpk'       => $totalGpk,
            'jurnalHariIni'  => $jurnalHariIni,
            'jurnalBulanIni' => $jurnalBulanIni,
            'rekapUnit'      => $rekapUnit,
            'latestJurnal'   => $latestJurnal,
        ];

        return view('pimpinan/dashboard', $data);
    }

    // 2. MONITORING JURNAL HARIAN (SELURUH UNIT)
    public function jurnal()
    {
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $unit  = $this->request->getGet('unit') ?? '';

        $builder = $this->db->table('jurnal')
                           ->select('jurnal.*, peserta_didik.nama_pdbk, peserta_didik.kelas, peserta_didik.unit, users.nama_lengkap as nama_gpk, (SELECT google_file_id FROM jurnal_foto WHERE jurnal_id = jurnal.id LIMIT 1) as foto_sampul')
                           ->join('peserta_didik', 'peserta_didik.id = jurnal.pdbk_id')
                           ->join('users', 'users.id = jurnal.gpk_id', 'left')
                           ->where('MONTH(jurnal.tanggal)', $bulan)
                           ->where('YEAR(jurnal.tanggal)', $tahun);

        if (!empty($unit)) {
            $builder->where('jurnal.unit', $unit);
        }

        $jurnalList = $builder->orderBy('jurnal.tanggal', 'DESC')
                              ->orderBy('jurnal.id', 'DESC')
                              ->get()->getResultArray();

        $data = [
            'title'      => 'Monitoring Jurnal Pimpinan',
            'jurnalList' => $jurnalList,
            'bulan'      => sprintf('%02d', $bulan),
            'tahun'      => $tahun,
            'unit'       => $unit
        ];

        return view('pimpinan/jurnal_index', $data);
    }

    // DETAIL JURNAL UNTUK PIMPINAN
    public function detailJurnal($id = null)
    {
        // 1. Ambil data utama jurnal
        $jurnal = $this->db->table('jurnal')
                           ->select('jurnal.*, peserta_didik.nama_pdbk, peserta_didik.kelas, peserta_didik.keterangan_iq, users.nama_lengkap as nama_gpk')
                           ->join('peserta_didik', 'peserta_didik.id = jurnal.pdbk_id')
                           ->join('users', 'users.id = jurnal.gpk_id', 'left')
                           ->where('jurnal.id', $id)
                           ->get()->getRowArray();

        if (!$jurnal) {
            session()->setFlashdata('error', 'Jurnal tidak ditemukan.');
            return redirect()->to('/pimpinan/jurnal');
        }

        // 2. KUNCI PERBAIKAN: Ambil array foto dari tabel jurnal_foto
        $fotos = $this->db->table('jurnal_foto')
                         ->where('jurnal_id', $id)
                         ->get()->getResultArray();

        // 3. Kirim $fotos ke View
        $data = [
            'title'  => 'Detail Jurnal Harian',
            'jurnal' => $jurnal,
            'fotos'  => $fotos
        ];

        return view('pimpinan/jurnal_detail', $data);
    }

    // Tampilan Profil Pimpinan
    public function profil()
    {
        $userId = session()->get('id');
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);

        $data = [
            'title' => 'Profil Pimpinan',
            'user'  => $user,
        ];

        return view('pimpinan/profil', $data);
    }

    // Proses Ganti Password Pimpinan
    public function updatePassword()
    {
        $userId = session()->get('id');
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);

        $passwordLama = $this->request->getPost('password_lama');
        $passwordBaru = $this->request->getPost('password_baru');
        $konfirmasi   = $this->request->getPost('konfirmasi_password');

        // Verifikasi Password Lama
        $passwordMatch = password_verify($passwordLama, $user['password'] ?? '') || ($user['password'] === $passwordLama);

        if (!$passwordMatch) {
            session()->setFlashdata('error', 'Password saat ini salah.');
            return redirect()->back();
        }

        // Validasi Konfirmasi Password Baru
        if ($passwordBaru !== $konfirmasi) {
            session()->setFlashdata('error', 'Konfirmasi password baru tidak cocok.');
            return redirect()->back();
        }

        // Validasi Minimal 6 Karakter
        if (strlen($passwordBaru) < 6) {
            session()->setFlashdata('error', 'Password baru minimal 6 karakter.');
            return redirect()->back();
        }

        // Update ke Database
        $userModel->update($userId, [
            'password'   => password_hash($passwordBaru, PASSWORD_BCRYPT),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        session()->setFlashdata('success', 'Password berhasil diperbarui.');
        return redirect()->to('/pimpinan/profil');
    }
}