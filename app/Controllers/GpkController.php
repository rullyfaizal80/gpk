<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PesertaDidikModel; // Sesuaikan dengan nama model PDBK Anda

class GpkController extends BaseController
{
    public function index()
    {
        $session = session();
        $gpkId = $session->get('id');

        // Mengambil anak dampingan khusus GPK yang sedang login
        $db = \Config\Database::connect();
        $builder = $db->table('peserta_didik');
        $anakDampingan = $builder->where('gpk_id', $gpkId)->get()->getResultArray();

        $data = [
            'title'          => 'Dashboard GPK',
            'nama_gpk'       => $session->get('nama_lengkap'),
            'total_anak'     => count($anakDampingan),
            'anakDampingan'  => $anakDampingan,
        ];

        return view('gpk/dashboard', $data);
    }
}