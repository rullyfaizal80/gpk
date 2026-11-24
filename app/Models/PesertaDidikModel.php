<?php

namespace App\Models;

use CodeIgniter\Model;

class PesertaDidikModel extends Model
{
    protected $table            = 'peserta_didik';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nama_pdbk', 'keterangan_iq', 'kelas', 'unit', 'gpk_id', 'ortu_id'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = '';

   public function getPesertaDidikPaginated($perPage = 10)
{
    return $this->select('peserta_didik.*, gpk.nama_lengkap as nama_gpk, ortu.nama_lengkap as nama_ortu')
                ->join('users as gpk', 'gpk.id = peserta_didik.gpk_id', 'left')
                ->join('users as ortu', 'ortu.id = peserta_didik.ortu_id', 'left')
                ->orderBy('peserta_didik.id', 'DESC')
                ->paginate($perPage, 'peserta_didik');
}
}