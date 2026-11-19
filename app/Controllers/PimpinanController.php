<?php

namespace App\Controllers;

class PimpinanController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        // Statistik Ringkas untuk Pimpinan
        $totalPdbk = $db->table('peserta_didik')->countAllResults();
        $totalGpk  = $db->table('users')->where('role', 'gpk')->countAllResults();
        
        // Ambil data PDBK per Unit
        $perilCount = $db->table('peserta_didik')->where('unit', 'PERIL')->countAllResults();
        $miCount    = $db->table('peserta_didik')->where('unit', 'MI')->countAllResults();
        $mtsCount   = $db->table('peserta_didik')->where('unit', 'MTs')->countAllResults();

        $data = [
            'title'      => 'Dashboard Pimpinan',
            'totalPdbk'  => $totalPdbk,
            'totalGpk'   => $totalGpk,
            'perilCount' => $perilCount,
            'miCount'    => $miCount,
            'mtsCount'   => $mtsCount
        ];

        return view('pimpinan/dashboard', $data);
    }
}