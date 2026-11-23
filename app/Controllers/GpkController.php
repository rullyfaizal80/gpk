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

    // 1. DASHBOARD GPK (BERANDA - HANYA RIWAYAT JURNAL HARIAN)
    public function index()
    {
        $gpkId = session()->get('id');

        $bulanAktif = $this->request->getGet('bulan') ?? date('m');
        $tahunAktif = $this->request->getGet('tahun') ?? date('Y');

        $jurnalList = $this->db->table('jurnal')
                               ->select('jurnal.*, peserta_didik.nama_pdbk, peserta_didik.kelas, (SELECT google_file_id FROM jurnal_foto WHERE jurnal_id = jurnal.id LIMIT 1) as foto_sampul')
                               ->join('peserta_didik', 'peserta_didik.id = jurnal.pdbk_id')
                               ->where('jurnal.gpk_id', $gpkId)
                               ->where('MONTH(jurnal.tanggal)', $bulanAktif)
                               ->where('YEAR(jurnal.tanggal)', $tahunAktif)
                               ->orderBy('jurnal.tanggal', 'DESC')
                               ->orderBy('jurnal.id', 'DESC')
                               ->get()->getResultArray();

        $data = [
            'title'      => 'Dashboard GPK',
            'nama_gpk'   => session()->get('nama_lengkap'),
            'jurnalList' => $jurnalList,
            'bulanAktif' => sprintf('%02d', $bulanAktif),
            'tahunAktif' => $tahunAktif,
        ];

        return view('gpk/dashboard', $data);
    }

    // 2. MENU JURNAL ANAK (DAFTAR ANAK DAMPINGAN)
    public function jurnalAnak()
    {
        $gpkId = session()->get('id');

        $anakDampingan = $this->db->table('peserta_didik')
                                  ->where('gpk_id', $gpkId)
                                  ->get()->getResultArray();

        $data = [
            'title'         => 'Jurnal Anak Dampingan',
            'anakDampingan' => $anakDampingan,
        ];

        return view('gpk/jurnal_anak', $data);
    }

    // 3. DAFTAR JURNAL KHUSUS TIAP ANAK DAMPINGAN
    public function jurnal()
    {
        $gpkId  = session()->get('id');
        $pdbkId = $this->request->getGet('pdbk_id');

        if (!$pdbkId) {
            return redirect()->to('/gpk/jurnal-anak');
        }

        $anak = $this->pesertaDidikModel->where('id', $pdbkId)
                                        ->where('gpk_id', $gpkId)
                                        ->first();

        if (!$anak) {
            session()->setFlashdata('error', 'Data anak tidak ditemukan atau bukan dampingan Anda.');
            return redirect()->to('/gpk/jurnal-anak');
        }

        $bulanAktif = $this->request->getGet('bulan') ?? date('m');
        $tahunAktif = $this->request->getGet('tahun') ?? date('Y');

        $jurnalList = $this->db->table('jurnal')
                               ->select('jurnal.*, (SELECT google_file_id FROM jurnal_foto WHERE jurnal_id = jurnal.id LIMIT 1) as foto_sampul')
                               ->where('jurnal.gpk_id', $gpkId)
                               ->where('jurnal.pdbk_id', $pdbkId)
                               ->where('MONTH(jurnal.tanggal)', $bulanAktif)
                               ->where('YEAR(jurnal.tanggal)', $tahunAktif)
                               ->orderBy('jurnal.tanggal', 'DESC')
                               ->orderBy('jurnal.id', 'DESC')
                               ->get()->getResultArray();

        $data = [
            'title'      => 'Jurnal ' . $anak['nama_pdbk'],
            'anak'       => $anak,
            'jurnalList' => $jurnalList,
            'bulanAktif' => sprintf('%02d', $bulanAktif),
            'tahunAktif' => $tahunAktif,
        ];

        return view('gpk/jurnal_index', $data);
    }

    // 4. FORM TAMBAH JURNAL HARIAN UNTUK ANAK SPESIFIK
    public function tambahJurnal($id_pdbk = null)
    {
        $gpkId = session()->get('id');

        $anak = $this->pesertaDidikModel->where('id', $id_pdbk)
                                        ->where('gpk_id', $gpkId)
                                        ->first();

        if (!$anak) {
            session()->setFlashdata('error', 'Data anak tidak ditemukan atau bukan dampingan Anda.');
            return redirect()->to('/gpk/jurnal-anak');
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
        $gpkId  = session()->get('id');
        $pdbkId = $this->request->getPost('pdbk_id');

        $inputTanggal = $this->request->getPost('tanggal');
        $tanggalJurnal = (!empty($inputTanggal)) ? $inputTanggal : date('Y-m-d');

        $this->db->transStart();

        $dataJurnal = [
            'tanggal'                 => $tanggalJurnal,
            'pdbk_id'                 => $pdbkId,
            'gpk_id'                  => $gpkId,
            'unit'                    => $this->request->getPost('unit'),
            'jenis_form'              => $this->request->getPost('jenis_form'),
            'akademik_mapel'          => $this->request->getPost('akademik_mapel'),
            'akademik_materi'         => $this->request->getPost('akademik_materi'),
            'akademik_catatan'        => $this->request->getPost('akademik_catatan'),
            'daily_toilet'            => $this->request->getPost('daily_toilet'),
            'daily_kemandirian'       => $this->request->getPost('daily_kemandirian'),
            'daily_keterlibatan'      => $this->request->getPost('daily_keterlibatan'),
            'perkembangan_aspek'      => $this->request->getPost('perkembangan_aspek'),
            'perkembangan_kegiatan'   => $this->request->getPost('perkembangan_kegiatan'),
            'perkembangan_catatan'    => $this->request->getPost('perkembangan_catatan'),
            'peril_kegiatan'          => $this->request->getPost('peril_kegiatan'),
            'peril_partisipasi'       => $this->request->getPost('peril_partisipasi'),
            'peril_catatan_kegiatan'  => $this->request->getPost('peril_catatan_kegiatan'),
            'peril_makan_minum'       => $this->request->getPost('peril_makan_minum'),
            'peril_kebersihan_toilet' => $this->request->getPost('peril_kebersihan_toilet'),
            'peril_merapikan_barang'  => $this->request->getPost('peril_merapikan_barang'),
            'peril_bantuan_harian'    => $this->request->getPost('peril_bantuan_harian'),
            'peril_komunikasi'        => $this->request->getPost('peril_komunikasi'),
            'peril_interaksi'         => $this->request->getPost('peril_interaksi'),
            'peril_emosi'             => $this->request->getPost('peril_emosi'),
            'peril_respon_arahan'     => $this->request->getPost('peril_respon_arahan'),
            'peril_strategi_dukungan' => $this->request->getPost('peril_strategi_dukungan'),
            'peril_pencapaian_positif'=> $this->request->getPost('peril_pencapaian_positif'),
            'peril_perlu_stimulasi'   => $this->request->getPost('peril_perlu_stimulasi'),
            'peril_info_ortu'          => $this->request->getPost('peril_info_ortu'),
'peril_stimulasi_rumah'    => $this->request->getPost('peril_stimulasi_rumah'),
'peril_komunikasi_lanjut' => $this->request->getPost('peril_komunikasi_lanjut'), // <-- Tambahkan baris ini
'created_at'               => date('Y-m-d H:i:s')
        ];

        $this->db->table('jurnal')->insert($dataJurnal);
        $jurnalId = $this->db->insertID();

        $files = $this->request->getFiles();
        if (isset($files['foto_jurnal'])) {
            $driveService = new \App\Libraries\GoogleDriveService();
            $tempFolder   = FCPATH . 'uploads/temp/';

            if (!is_dir($tempFolder)) {
                mkdir($tempFolder, 0777, true);
            }

            foreach ($files['foto_jurnal'] as $foto) {
                if ($foto->isValid() && !$foto->hasMoved()) {
                    $newName  = $foto->getRandomName();
                    $foto->move($tempFolder, $newName);
                    $fullPath = $tempFolder . $newName;
                    $mimeType = mime_content_type($fullPath);

                    try {
                        $googleFileId = $driveService->uploadImageToMonthlyFolder($fullPath, $newName, $mimeType);

                        $this->db->table('jurnal_foto')->insert([
                            'jurnal_id'      => $jurnalId,
                            'google_file_id' => $googleFileId,
                            'nama_file'      => $newName,
                            'tgl_upload'     => date('Y-m-d H:i:s')
                        ]);

                        if (file_exists($fullPath)) {
                            unlink($fullPath);
                        }
                    } catch (\Exception $e) {
                        if (file_exists($fullPath)) {
                            unlink($fullPath);
                        }
                        $this->db->transRollback();
                        session()->setFlashdata('error', 'Gagal upload foto ke Google Drive: ' . $e->getMessage());
                        return redirect()->back()->withInput();
                    }
                }
            }
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            session()->setFlashdata('error', 'Gagal menyimpan data jurnal ke database.');
            return redirect()->back()->withInput();
        }

        session()->setFlashdata('success', 'Jurnal harian dan foto dokumentasi berhasil disimpan!');
        return redirect()->to('/gpk/dashboard');
    }

    // 6. DETAIL JURNAL HARIAN
    public function detailJurnal($id_jurnal)
    {
        $gpkId  = session()->get('id');
        $jurnal = $this->db->table('jurnal')
                           ->select('jurnal.*, peserta_didik.nama_pdbk, peserta_didik.kelas, peserta_didik.keterangan_iq')
                           ->join('peserta_didik', 'peserta_didik.id = jurnal.pdbk_id')
                           ->where('jurnal.id', $id_jurnal)
                           ->where('jurnal.gpk_id', $gpkId)
                           ->get()->getRowArray();

        if (!$jurnal) {
            session()->setFlashdata('error', 'Jurnal tidak ditemukan.');
            return redirect()->to('/gpk/dashboard');
        }

        $fotos = $this->db->table('jurnal_foto')
                         ->where('jurnal_id', $id_jurnal)
                         ->get()->getResultArray();

        $data = [
            'title'  => 'Detail Jurnal Harian',
            'jurnal' => $jurnal,
            'fotos'  => $fotos
        ];

        return view('gpk/jurnal_detail', $data);
    }

    // 1. FORM EDIT JURNAL (ASPEK LENGKAP & FOTO)
    public function editJurnal($id = null)
    {
        $gpkId = session()->get('id');

        $jurnal = $this->db->table('jurnal')
                           ->select('jurnal.*, peserta_didik.nama_pdbk, peserta_didik.kelas, peserta_didik.keterangan_iq')
                           ->join('peserta_didik', 'peserta_didik.id = jurnal.pdbk_id')
                           ->where('jurnal.id', $id)
                           ->where('jurnal.gpk_id', $gpkId)
                           ->get()->getRowArray();

        if (!$jurnal) {
            session()->setFlashdata('error', 'Jurnal tidak ditemukan atau Anda tidak memiliki akses.');
            return redirect()->to('/gpk/jurnal-anak');
        }

        // Ambil daftar foto terkait jurnal ini
        $fotos = $this->db->table('jurnal_foto')
                          ->where('jurnal_id', $id)
                          ->get()->getResultArray();

        $data = [
            'title'  => 'Edit Jurnal Harian',
            'jurnal' => $jurnal,
            'fotos'  => $fotos,
        ];

        return view('gpk/jurnal_edit', $data);
    }

    // PROSES UPDATE JURNAL HARIAN (DIPISAH BERDASARKAN JENIS FORM)
    public function updateJurnal($id = null)
    {
        $gpkId = session()->get('id');

        $jurnal = $this->db->table('jurnal')
                           ->where('id', $id)
                           ->where('gpk_id', $gpkId)
                           ->get()->getRowArray();

        if (!$jurnal) {
            session()->setFlashdata('error', 'Jurnal tidak ditemukan.');
            return redirect()->to('/gpk/jurnal-anak');
        }

        $inputTanggal  = $this->request->getPost('tanggal');
        $tanggalJurnal = (!empty($inputTanggal)) ? $inputTanggal : $jurnal['tanggal'];
        $jenisForm     = $jurnal['jenis_form']; // Ambil jenis form dari data existing

        $this->db->transStart();

        // 1. Data Dasar
        $dataUpdate = [
            'tanggal' => $tanggalJurnal,
        ];

        // 2. Pemetaan Data Berdasarkan Jenis Form
        if ($jenisForm === 'peril') {
            $dataUpdate['peril_kegiatan']          = $this->request->getPost('peril_kegiatan');
            $dataUpdate['peril_partisipasi']       = $this->request->getPost('peril_partisipasi');
            $dataUpdate['peril_catatan_kegiatan']  = $this->request->getPost('peril_catatan_kegiatan');
            $dataUpdate['peril_makan_minum']       = $this->request->getPost('peril_makan_minum');
            $dataUpdate['peril_kebersihan_toilet'] = $this->request->getPost('peril_kebersihan_toilet');
            $dataUpdate['peril_merapikan_barang']  = $this->request->getPost('peril_merapikan_barang');
            $dataUpdate['peril_bantuan_harian']    = $this->request->getPost('peril_bantuan_harian');
            $dataUpdate['peril_komunikasi']        = $this->request->getPost('peril_komunikasi');
            $dataUpdate['peril_interaksi']         = $this->request->getPost('peril_interaksi');
            $dataUpdate['peril_emosi']             = $this->request->getPost('peril_emosi');
            $dataUpdate['peril_respon_arahan']     = $this->request->getPost('peril_respon_arahan');
            $dataUpdate['peril_strategi_dukungan'] = $this->request->getPost('peril_strategi_dukungan');
            $dataUpdate['peril_pencapaian_positif']= $this->request->getPost('peril_pencapaian_positif');
            $dataUpdate['peril_perlu_stimulasi']   = $this->request->getPost('peril_perlu_stimulasi');
            $dataUpdate['peril_info_ortu']          = $this->request->getPost('peril_info_ortu');
$dataUpdate['peril_stimulasi_rumah']    = $this->request->getPost('peril_stimulasi_rumah');
$dataUpdate['peril_komunikasi_lanjut'] = $this->request->getPost('peril_komunikasi_lanjut'); // <-- Tambahkan baris ini
        } else {
            $dataUpdate['akademik_mapel']        = $this->request->getPost('akademik_mapel');
            $dataUpdate['akademik_materi']       = $this->request->getPost('akademik_materi');
            $dataUpdate['akademik_catatan']      = $this->request->getPost('akademik_catatan');
            $dataUpdate['daily_toilet']          = $this->request->getPost('daily_toilet');
            $dataUpdate['daily_kemandirian']     = $this->request->getPost('daily_kemandirian');
            $dataUpdate['daily_keterlibatan']    = $this->request->getPost('daily_keterlibatan');
            $dataUpdate['perkembangan_aspek']    = $this->request->getPost('perkembangan_aspek');
            $dataUpdate['perkembangan_kegiatan'] = $this->request->getPost('perkembangan_kegiatan');
            $dataUpdate['perkembangan_catatan']  = $this->request->getPost('perkembangan_catatan');
        }

        // Eksekusi Update ke Database
        $this->db->table('jurnal')->where('id', $id)->update($dataUpdate);

        // 3. Upload Foto Tambahan Baru ke Google Drive jika Ada
        $files = $this->request->getFiles();
        if (isset($files['foto_jurnal'])) {
            $driveService = new \App\Libraries\GoogleDriveService();
            $tempFolder   = FCPATH . 'uploads/temp/';

            if (!is_dir($tempFolder)) {
                mkdir($tempFolder, 0777, true);
            }

            foreach ($files['foto_jurnal'] as $foto) {
                if ($foto->isValid() && !$foto->hasMoved()) {
                    $newName  = $foto->getRandomName();
                    $foto->move($tempFolder, $newName);
                    $fullPath = $tempFolder . $newName;
                    $mimeType = mime_content_type($fullPath);

                    try {
                        $googleFileId = $driveService->uploadImageToMonthlyFolder($fullPath, $newName, $mimeType);

                        $this->db->table('jurnal_foto')->insert([
                            'jurnal_id'      => $id,
                            'google_file_id' => $googleFileId,
                            'nama_file'      => $newName,
                            'tgl_upload'     => date('Y-m-d H:i:s')
                        ]);

                        if (file_exists($fullPath)) {
                            unlink($fullPath);
                        }
                    } catch (\Exception $e) {
                        if (file_exists($fullPath)) {
                            unlink($fullPath);
                        }
                    }
                }
            }
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            session()->setFlashdata('error', 'Gagal memperbarui data jurnal di database.');
            return redirect()->back()->withInput();
        }

        session()->setFlashdata('success', 'Jurnal harian berhasil diperbarui.');
        return redirect()->to('/gpk/jurnal/detail/' . $id);
    }

    // 3. PROSES HAPUS 1 FOTO SPESIFIK DARI EDIT JURNAL
    public function hapusFotoSingle($fotoId = null)
    {
        $gpkId = session()->get('id');

        $foto = $this->db->table('jurnal_foto')
                         ->select('jurnal_foto.*, jurnal.gpk_id')
                         ->join('jurnal', 'jurnal.id = jurnal_foto.jurnal_id')
                         ->where('jurnal_foto.id', $fotoId)
                         ->where('jurnal.gpk_id', $gpkId)
                         ->get()->getRowArray();

        if (!$foto) {
            session()->setFlashdata('error', 'Foto tidak ditemukan.');
            return redirect()->back();
        }

        $jurnalId = $foto['jurnal_id'];

        // Hapus fisik file di Google Drive
        if (!empty($foto['google_file_id'])) {
            $driveService = new \App\Libraries\GoogleDriveService();
            $driveService->deleteFile($foto['google_file_id']);
        }

        // Hapus record dari database
        $this->db->table('jurnal_foto')->where('id', $fotoId)->delete();

        session()->setFlashdata('success', 'Foto dokumentasi berhasil dihapus.');
        return redirect()->to('/gpk/jurnal/edit/' . $jurnalId);
    }

    // PROSES HAPUS JURNAL HARIAN
    public function hapusJurnal($id = null)
    {
        $gpkId = session()->get('id');

        // 1. Cek keberadaan jurnal
        $jurnal = $this->db->table('jurnal')
                           ->where('id', $id)
                           ->where('gpk_id', $gpkId)
                           ->get()->getRowArray();

        if (!$jurnal) {
            session()->setFlashdata('error', 'Jurnal tidak ditemukan.');
            return redirect()->to('/gpk/dashboard');
        }

        $pdbkId = $jurnal['pdbk_id'];

        // 2. AMBIL FOTO SEBELUM TRANSAKSI / PENGHAPUSAN DATABASE
        $fotos = $this->db->table('jurnal_foto')
                         ->where('jurnal_id', $id)
                         ->get()->getResultArray();

        // 3. HAPUS FOTO DI GOOGLE DRIVE TERLEBIH DAHULU
        if (!empty($fotos)) {
            $driveService = new \App\Libraries\GoogleDriveService();
            foreach ($fotos as $f) {
                if (!empty($f['google_file_id'])) {
                    $driveService->deleteFile($f['google_file_id']);
                }
            }
        }

        // 4. SETELAH DRIVE CLEANUP, BARU HAPUS DATABASE LOCAL
        $this->db->transStart();
        
        $this->db->table('jurnal_foto')->where('jurnal_id', $id)->delete();
        $this->db->table('jurnal')->where('id', $id)->delete();

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            session()->setFlashdata('error', 'Gagal menghapus data jurnal dari database.');
            return redirect()->back();
        }

        session()->setFlashdata('success', 'Jurnal harian beserta foto dokumentasi di Google Drive berhasil dihapus.');
        return redirect()->to('/gpk/jurnal?pdbk_id=' . $pdbkId);
    }

    // 10. TAMPILAN PROFIL GPK
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

    // PROSES GANTI PASSWORD GPK
    public function updatePassword()
    {
        $userId = session()->get('id');
        $user   = $this->userModel->find($userId);

        $passwordLama = $this->request->getPost('password_lama');
        $passwordBaru = $this->request->getPost('password_baru');
        $konfirmasi   = $this->request->getPost('konfirmasi_password');

        // 1. Verifikasi Password Lama (Gunakan password_verify jika di-hash atau perbandingan langsung jika plain text)
        $passwordMatch = password_verify($passwordLama, $user['password'] ?? '') || ($user['password'] === $passwordLama);

        if (!$passwordMatch) {
            session()->setFlashdata('error', 'Password saat ini salah.');
            return redirect()->back();
        }

        // 2. Validasi Konfirmasi Password Baru
        if ($passwordBaru !== $konfirmasi) {
            session()->setFlashdata('error', 'Konfirmasi password baru tidak cocok.');
            return redirect()->back();
        }

        // 3. Validasi Panjang Password Baru
        if (strlen($passwordBaru) < 6) {
            session()->setFlashdata('error', 'Password baru minimal harus 6 karakter.');
            return redirect()->back();
        }

        // 4. Update Password (Direkomendasikan me-hash password dengan password_hash)
        $newPasswordHash = password_hash($passwordBaru, PASSWORD_BCRYPT);

        $this->userModel->update($userId, [
            'password'   => $newPasswordHash,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        session()->setFlashdata('success', 'Password berhasil diperbarui.');
        return redirect()->to('/gpk/profil');
    }

    // 11. RENDER FOTO DARI GOOGLE DRIVE
    public function renderFoto($fileId = null)
    {
        if (empty($fileId)) {
            return $this->response->setStatusCode(404)->setBody('File ID Kosong');
        }

        try {
            $driveService = new \App\Libraries\GoogleDriveService();
            $gambarData   = $driveService->getMedia($fileId);
            
            return $this->response
                        ->setContentType('image/jpeg')
                        ->setHeader('Cache-Control', 'max-age=86400, public')
                        ->setBody($gambarData);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(404)->setBody('Gambar tidak ditemukan: ' . $e->getMessage());
        }
    }
}