<?php

namespace App\Controllers;

use App\Models\PesertaDidikModel;
use App\Models\UserModel;

class PesertaDidikController extends BaseController
{
    protected $pesertaDidikModel;
    protected $userModel;

    public function __construct()
    {
        $this->pesertaDidikModel = new PesertaDidikModel();
        $this->userModel        = new UserModel();
    }

    public function index()
{
    $perPage = 10;

    $data = [
        'title'         => 'Kelola Peserta Didik (PDBK)',
        'peserta_didik' => $this->pesertaDidikModel->getPesertaDidikPaginated($perPage),
        'pager'         => $this->pesertaDidikModel->pager,
        'currentPage'   => $this->request->getVar('page_peserta_didik') ? $this->request->getVar('page_peserta_didik') : 1,
        'perPage'       => $perPage,
        'list_gpk'      => $this->userModel->where('role', 'gpk')->where('is_active', 1)->findAll(),
        'list_ortu'     => $this->userModel->where('role', 'ortu')->where('is_active', 1)->findAll(),
    ];

    return view('admin/peserta_didik', $data);
}

    public function tambah()
    {
        $this->pesertaDidikModel->save([
            'nama_pdbk'     => trim($this->request->getPost('nama_pdbk')),
            'keterangan_iq' => trim($this->request->getPost('keterangan_iq')),
            'kelas'         => trim($this->request->getPost('kelas')),
            'unit'          => $this->request->getPost('unit'),
            'gpk_id'        => $this->request->getPost('gpk_id') ?: null,
            'ortu_id'       => $this->request->getPost('ortu_id') ?: null,
        ]);

        session()->setFlashdata('success', 'Data Peserta Didik berhasil ditambahkan.');
        return redirect()->to('/admin/peserta-didik');
    }

    public function edit()
    {
        $id = $this->request->getPost('id');

        $this->pesertaDidikModel->update($id, [
            'nama_pdbk'     => trim($this->request->getPost('nama_pdbk')),
            'keterangan_iq' => trim($this->request->getPost('keterangan_iq')),
            'kelas'         => trim($this->request->getPost('kelas')),
            'unit'          => $this->request->getPost('unit'),
            'gpk_id'        => $this->request->getPost('gpk_id') ?: null,
            'ortu_id'       => $this->request->getPost('ortu_id') ?: null,
        ]);

        session()->setFlashdata('success', 'Data Peserta Didik berhasil diperbarui.');
        return redirect()->to('/admin/peserta-didik');
    }

    public function hapus($id)
    {
        $this->pesertaDidikModel->delete($id);
        session()->setFlashdata('success', 'Data Peserta Didik berhasil dihapus.');
        return redirect()->to('/admin/peserta-didik');
    }
}