<?php

namespace App\Controllers;

use App\Models\UserModel;

class AdminController extends BaseController
{
    protected $userModel;
    protected $db;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->db        = \Config\Database::connect();
    }

    // 1. DASHBOARD ADMIN
    public function index()
    {
        // Menghitung statistik pengguna & jurnal untuk dashboard admin GPK
        $totalGpk      = $this->userModel->where('role', 'gpk')->countAllResults();
        $totalPdbk     = $this->db->table('peserta_didik')->countAllResults();
        $totalJurnal   = $this->db->table('jurnal')->countAllResults();
        $jurnalBulanIni = $this->db->table('jurnal')
                                  ->where('MONTH(tanggal)', date('m'))
                                  ->where('YEAR(tanggal)', date('Y'))
                                  ->countAllResults();

        $data = [
            'title'          => 'Dashboard Admin',
            'totalGpk'       => $totalGpk,
            'totalPdbk'      => $totalPdbk,
            'totalJurnal'    => $totalJurnal,
            'jurnalBulanIni' => $jurnalBulanIni,
        ];

        return view('admin/dashboard', $data);
    }

    // ==========================================
    // BAGIAN KELOLA USER (PENGGUNA)
    // ==========================================
    
    // Tampilkan daftar pengguna
    public function users()
    {
        $data = [
            'title' => 'Kelola Pengguna',
            'users' => $this->userModel->orderBy('role', 'ASC')->orderBy('nama_lengkap', 'ASC')->findAll()
        ];

        return view('admin/kelola_user', $data);
    }

    // Tambah Pengguna Baru
    public function tambahUser()
    {
        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username'     => $this->request->getPost('username'),
            'password'     => password_hash((string)$this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'         => $this->request->getPost('role'),
            'is_active'    => 1 // Aktif secara default
        ];

        $this->userModel->insert($data);
        session()->setFlashdata('success', 'Pengguna baru berhasil ditambahkan.');
        return redirect()->to('/admin/user');
    }

    // Edit Data Pengguna / Reset Password
    public function editUser()
    {
        $id = $this->request->getPost('id');
        
        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username'     => $this->request->getPost('username'),
            'role'         => $this->request->getPost('role'),
            'is_active'    => $this->request->getPost('is_active')
        ];

        // Jika form password diisi, berarti Admin mereset password user
        $password_baru = $this->request->getPost('password');
        if (!empty($password_baru)) {
            $data['password'] = password_hash((string)$password_baru, PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $data);
        session()->setFlashdata('success', 'Data pengguna berhasil diperbarui.');
        return redirect()->to('/admin/user');
    }

    // Hapus Pengguna
    public function hapusUser($id)
    {
        // Proteksi: Mencegah Admin menghapus dirinya sendiri
        if ($id == session()->get('id')) {
            session()->setFlashdata('error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login.');
            return redirect()->to('/admin/user');
        }

        try {
            $this->userModel->delete($id);
            session()->setFlashdata('success', 'Akun pengguna berhasil dihapus.');
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            if (strpos($e->getMessage(), 'foreign key constraint') !== false || strpos($e->getMessage(), '1451') !== false) {
                session()->setFlashdata('error', 'Gagal! Pengguna ini tidak dapat dihapus karena terikat dengan data jurnal/dampingan. Silakan ubah statusnya menjadi "Tidak Aktif".');
            } else {
                session()->setFlashdata('error', 'Terjadi kesalahan database: ' . $e->getMessage());
            }
        }

        return redirect()->to('/admin/user');
    }
}