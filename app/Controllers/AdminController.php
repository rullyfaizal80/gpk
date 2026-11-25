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

    public function index()
{
    // Mengunci bulan dan tahun ke bulan berjalan saat ini
    $bulanAktif = date('m');
    $tahunAktif = date('Y');

    // 1. Hitung Statistik Ringkasan
    $totalGpk       = $this->userModel->where('role', 'gpk')->countAllResults();
    $totalPdbk      = $this->db->table('peserta_didik')->countAllResults();
    $totalJurnal    = $this->db->table('jurnal')->countAllResults();
    $jurnalBulanIni = $this->db->table('jurnal')
                               ->where('MONTH(tanggal)', $bulanAktif)
                               ->where('YEAR(tanggal)', $tahunAktif)
                               ->countAllResults();

    // 2. Ambil Jadwal Hari Efektif Bulan Ini
    $jadwalBulan = $this->db->table('pengaturan_jadwal_notif')
                            ->where('MONTH(tanggal)', $bulanAktif)
                            ->where('YEAR(tanggal)', $tahunAktif)
                            ->orderBy('tanggal', 'ASC')
                            ->get()->getResultArray();

    $mapTanggal = [];
    foreach ($jadwalBulan as $j) {
        $mapTanggal[$j['tanggal']] = $j;
    }

    // 3. Susun Matriks Ceklisan GPK
    $gpkList = $this->userModel->where('role', 'gpk')->findAll();
    $matriksData = [];

    foreach ($gpkList as $gpk) {
        $listAnak = $this->db->table('peserta_didik')
                             ->where('gpk_id', $gpk['id'])
                             ->get()->getResultArray();
        $totalAnak = count($listAnak);

        $rowGPK = [
            'nama_gpk'   => $gpk['nama_lengkap'],
            'total_anak' => $totalAnak,
            'harian'     => []
        ];

        foreach ($mapTanggal as $tgl => $infoTgl) {
            if ($infoTgl['is_aktif'] == 0) {
                $rowGPK['harian'][$tgl] = ['status' => 'libur', 'keterangan' => $infoTgl['keterangan']];
            } else {
                $listAnakIds = array_column($listAnak, 'id');
                $terisi = 0;
                if (!empty($listAnakIds)) {
                    $terisi = $this->db->table('jurnal')
                                       ->where('gpk_id', $gpk['id'])
                                       ->where('tanggal', $tgl)
                                       ->whereIn('pdbk_id', $listAnakIds)
                                       ->countAllResults();
                }

                if ($terisi == 0) {
                    $status = 'kosong';
                } elseif ($terisi < $totalAnak) {
                    $status = 'parsial';
                } else {
                    $status = 'lengkap';
                }

                $rowGPK['harian'][$tgl] = [
                    'status' => $status,
                    'terisi' => $terisi,
                    'total'  => $totalAnak
                ];
            }
        }

        $matriksData[] = $rowGPK;
    }

    $data = [
        'title'          => 'Dashboard Admin',
        'totalGpk'       => $totalGpk,
        'totalPdbk'      => $totalPdbk,
        'totalJurnal'    => $totalJurnal,
        'jurnalBulanIni' => $jurnalBulanIni,
        'matriksData'    => $matriksData,
        'mapTanggal'     => $mapTanggal,
        'bulanAktif'     => sprintf('%02d', $bulanAktif),
        'tahunAktif'     => $tahunAktif,
    ];

    return view('admin/dashboard', $data);
}

    // ==========================================
    // BAGIAN KELOLA USER (PENGGUNA)
    // ==========================================
    
   public function users()
{
    $perPage = 10; // Menampilkan 10 data per halaman

    $data = [
        'title'       => 'Kelola Pengguna Sistem',
        'users'       => $this->userModel->orderBy('role', 'ASC')->orderBy('nama_lengkap', 'ASC')->paginate($perPage, 'users'),
        'pager'       => $this->userModel->pager,
        'currentPage' => $this->request->getVar('page_users') ? $this->request->getVar('page_users') : 1,
        'perPage'     => $perPage
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

    // ==========================================
    // KELOLA & MONITORING JURNAL HARIAN (ADMIN)
    // ==========================================

   // 1. DAFTAR & REKAP MONITORING JURNAL HARIAN (ADMIN)
    public function jurnal()
    {
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $unit  = $this->request->getGet('unit') ?? '';

        // A. AMBIL JADWAL HARI EFEKTIF BULAN FILTER
        $jadwalBulan = $this->db->table('pengaturan_jadwal_notif')
                                ->where('MONTH(tanggal)', $bulan)
                                ->where('YEAR(tanggal)', $tahun)
                                ->orderBy('tanggal', 'ASC')
                                ->get()->getResultArray();

        $mapTanggal = [];
        foreach ($jadwalBulan as $j) {
            $mapTanggal[$j['tanggal']] = $j;
        }

        // B. MATRIKS PROGRESS PENGISIAN GPK PERTANGGAL
        $gpkList = $this->userModel->where('role', 'gpk')->findAll();
        $matriksData = [];

        foreach ($gpkList as $gpk) {
            $builderAnak = $this->db->table('peserta_didik')->where('gpk_id', $gpk['id']);
            if (!empty($unit)) {
                $builderAnak->where('unit', $unit);
            }
            $listAnak = $builderAnak->get()->getResultArray();
            $totalAnak = count($listAnak);

            if ($totalAnak === 0 && !empty($unit)) {
                continue;
            }

            $rowGPK = [
                'nama_gpk'   => $gpk['nama_lengkap'],
                'total_anak' => $totalAnak,
                'harian'     => []
            ];

            foreach ($mapTanggal as $tgl => $infoTgl) {
                if ($infoTgl['is_aktif'] == 0) {
                    $rowGPK['harian'][$tgl] = ['status' => 'libur', 'keterangan' => $infoTgl['keterangan']];
                } else {
                    $listAnakIds = array_column($listAnak, 'id');
                    $terisi = 0;
                    if (!empty($listAnakIds)) {
                        $terisi = $this->db->table('jurnal')
                                           ->where('gpk_id', $gpk['id'])
                                           ->where('tanggal', $tgl)
                                           ->whereIn('pdbk_id', $listAnakIds)
                                           ->countAllResults();
                    }

                    if ($terisi == 0) {
                        $status = 'kosong';
                    } elseif ($terisi < $totalAnak) {
                        $status = 'parsial';
                    } else {
                        $status = 'lengkap';
                    }

                    $rowGPK['harian'][$tgl] = [
                        'status' => $status,
                        'terisi' => $terisi,
                        'total'  => $totalAnak
                    ];
                }
            }

            $matriksData[] = $rowGPK;
        }

        // C. TABEL DAFTAR ENTRI JURNAL HARIAN + PAGINATION (10 DATA PER HALAMAN)
        $perPage     = 10;
        $currentPage = (int) ($this->request->getGet('page_jurnal') ?? 1);

        $builderJurnal = $this->db->table('jurnal')
                                  ->select('jurnal.*, peserta_didik.nama_pdbk, peserta_didik.kelas, peserta_didik.unit, users.nama_lengkap as nama_gpk, (SELECT google_file_id FROM jurnal_foto WHERE jurnal_id = jurnal.id LIMIT 1) as foto_sampul')
                                  ->join('peserta_didik', 'peserta_didik.id = jurnal.pdbk_id')
                                  ->join('users', 'users.id = jurnal.gpk_id', 'left')
                                  ->where('MONTH(jurnal.tanggal)', $bulan)
                                  ->where('YEAR(jurnal.tanggal)', $tahun);

        if (!empty($unit)) {
            $builderJurnal->where('jurnal.unit', $unit);
        }

        $totalEntries = $builderJurnal->countAllResults(false);

        $jurnalList = $builderJurnal->orderBy('jurnal.tanggal', 'DESC')
                                    ->orderBy('jurnal.id', 'DESC')
                                    ->limit($perPage, ($currentPage - 1) * $perPage)
                                    ->get()->getResultArray();

        // Inisialisasi Pager CodeIgniter 4 (Konsisten dengan Kelola User)
        $pager = \Config\Services::pager();

        $data = [
            'title'        => 'Monitoring Jurnal Harian GPK',
            'matriksData'  => $matriksData,
            'mapTanggal'   => $mapTanggal,
            'jurnalList'   => $jurnalList,
            'totalEntries' => $totalEntries,
            'pager'        => $pager,
            'currentPage'  => $currentPage,
            'perPage'      => $perPage,
            'bulan'        => sprintf('%02d', $bulan),
            'tahun'        => $tahun,
            'unit'         => $unit
        ];

        return view('admin/jurnal_index', $data);
    }

    // 2. DETAIL ENTRI JURNAL HARIAN
    public function detailJurnal($id = null)
    {
        $jurnal = $this->db->table('jurnal')
                           ->select('jurnal.*, peserta_didik.nama_pdbk, peserta_didik.kelas, peserta_didik.keterangan_iq, users.nama_lengkap as nama_gpk')
                           ->join('peserta_didik', 'peserta_didik.id = jurnal.pdbk_id')
                           ->join('users', 'users.id = jurnal.gpk_id', 'left')
                           ->where('jurnal.id', $id)
                           ->get()->getRowArray();

        if (!$jurnal) {
            session()->setFlashdata('error', 'Jurnal tidak ditemukan.');
            return redirect()->to('/admin/jurnal');
        }

        $fotos = $this->db->table('jurnal_foto')
                         ->where('jurnal_id', $id)
                         ->get()->getResultArray();

        $data = [
            'title'  => 'Detail Jurnal Harian PDBK',
            'jurnal' => $jurnal,
            'fotos'  => $fotos
        ];

        return view('admin/jurnal_detail', $data);
    }

}