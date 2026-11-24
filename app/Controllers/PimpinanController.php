<?php

namespace App\Controllers;

class PimpinanController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // 1. DASHBOARD PIMPINAN (MATRIKS HARIAN TANPA REKAP UNIT)
public function index()
{
    $bulanAktif = $this->request->getGet('bulan') ?? date('m');
    $tahunAktif = $this->request->getGet('tahun') ?? date('Y');
    $unitFilter = $this->request->getGet('unit') ?? '';

    // 1. Statistik Utama
    $totalPdbk     = $this->db->table('peserta_didik')->countAllResults();
    $totalGpk      = $this->db->table('users')->where('role', 'gpk')->countAllResults();
    $jurnalHariIni = $this->db->table('jurnal')->where('tanggal', date('Y-m-d'))->countAllResults();
    $jurnalBulanIni= $this->db->table('jurnal')
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

    // 3. Hitung Matriks Ceklisan GPK
    $gpkList = $this->db->table('users')->where('role', 'gpk')->get()->getResultArray();
    $matriksData = [];

    foreach ($gpkList as $gpk) {
        $builderAnak = $this->db->table('peserta_didik')->where('gpk_id', $gpk['id']);
        if (!empty($unitFilter)) {
            $builderAnak->where('unit', $unitFilter);
        }
        $listAnak = $builderAnak->get()->getResultArray();
        $totalAnak = count($listAnak);

        if ($totalAnak === 0 && !empty($unitFilter)) {
            continue; // Skip jika GPK tidak mengajar di unit yang difilter
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

    $data = [
        'title'          => 'Dashboard Pimpinan',
        'totalPdbk'      => $totalPdbk,
        'totalGpk'       => $totalGpk,
        'jurnalHariIni'  => $jurnalHariIni,
        'jurnalBulanIni' => $jurnalBulanIni,
        'matriksData'    => $matriksData,
        'mapTanggal'     => $mapTanggal,
        'bulanAktif'     => sprintf('%02d', $bulanAktif),
        'tahunAktif'     => $tahunAktif,
        'unitFilter'     => $unitFilter,
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