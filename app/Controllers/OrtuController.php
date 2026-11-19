<?php

namespace App\Controllers;

class OrtuController extends BaseController
{
    public function index()
    {
        $session = session();
        $ortuId  = $session->get('id');

        $db = \Config\Database::connect();
        
        // Ambil data anak milik orang tua yang sedang login
        $anak = $db->table('peserta_didik')
                   ->select('peserta_didik.*, users.nama_lengkap as nama_gpk')
                   ->join('users', 'users.id = peserta_didik.gpk_id', 'left')
                   ->where('peserta_didik.ortu_id', $ortuId)
                   ->get()->getRowArray();

        $data = [
            'title' => 'Dashboard Orang Tua',
            'anak'  => $anak
        ];

        return view('ortu/dashboard', $data);
    }
}