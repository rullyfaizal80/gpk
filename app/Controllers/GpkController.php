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

        $jurnal = $this->db->table('jurnal')
                           ->select('jurnal.*, peserta_didik.nama_pdbk, peserta_didik.kelas')
                           ->join('peserta_didik', 'peserta_didik.id = jurnal.pdbk_id')
                           ->where('jurnal.gpk_id', $gpkId)
                           ->orderBy('jurnal.tanggal', 'DESC')
                           ->orderBy('jurnal.id', 'DESC')
                           ->get()->getResultArray();

        $data = [
            'title'  => 'Jurnal Harian GPK',
            'jurnal' => $jurnal
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

    // 5. PROSES SIMPAN JURNAL HARIAN & UPLOAD MULTI FOTO KE GOOGLE DRIVE
    public function simpanJurnal()
    {
        $gpkId  = session()->get('id');
        $pdbkId = $this->request->getPost('pdbk_id');

        $this->db->transStart();

        // 1. Data Jurnal Utama (Support Form PERIL & Form Standard MI/MTs)
        $dataJurnal = [
            'tanggal'                 => date('Y-m-d'),
            'pdbk_id'                 => $pdbkId,
            'gpk_id'                  => $gpkId,
            'unit'                    => $this->request->getPost('unit'),
            'jenis_form'              => $this->request->getPost('jenis_form'),
            // Form Standard (MI/MTs)
            'akademik_mapel'          => $this->request->getPost('akademik_mapel'),
            'akademik_materi'         => $this->request->getPost('akademik_materi'),
            'akademik_catatan'        => $this->request->getPost('akademik_catatan'),
            'daily_toilet'            => $this->request->getPost('daily_toilet'),
            'daily_kemandirian'       => $this->request->getPost('daily_kemandirian'),
            'daily_keterlibatan'      => $this->request->getPost('daily_keterlibatan'),
            'perkembangan_aspek'      => $this->request->getPost('perkembangan_aspek'),
            'perkembangan_kegiatan'   => $this->request->getPost('perkembangan_kegiatan'),
            'perkembangan_catatan'    => $this->request->getPost('perkembangan_catatan'),
            // Form PERIL (PG-RA)
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
            'peril_info_ortu'         => $this->request->getPost('peril_info_ortu'),
            'peril_stimulasi_rumah'   => $this->request->getPost('peril_stimulasi_rumah'),
            'created_at'              => date('Y-m-d H:i:s')
        ];

        $this->db->table('jurnal')->insert($dataJurnal);
        $jurnalId = $this->db->insertID();

        // 2. Unggah Multi-Foto ke Google Drive (Struktur: Root -> GPK -> Tahun -> Bulan)
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
                        // Memanggil penciptaan sub-folder otomatis di Google Drive
                        $googleFileId = $driveService->uploadImageToMonthlyFolder($fullPath, $newName, $mimeType);

                        // Simpan metadata ke tabel jurnal_foto
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
            return redirect()->to('/gpk/jurnal');
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

    // 8. RENDER FOTO DARI GOOGLE DRIVE DENGAN CACHE
    public function renderFoto($fileId)
    {
        try {
            $driveService = new \App\Libraries\GoogleDriveService();
            $gambarData   = $driveService->getMedia($fileId);
            
            return $this->response
                        ->setContentType('image/jpeg') 
                        ->setHeader('Cache-Control', 'max-age=86400')
                        ->setBody($gambarData);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(404)->setBody('Gambar tidak ditemukan');
        }
    }
}