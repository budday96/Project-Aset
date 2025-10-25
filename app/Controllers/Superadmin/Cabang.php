<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Models\CabangModel;

class Cabang extends BaseController
{
    protected $cabangModel;

    public function __construct()
    {
        $this->cabangModel = new CabangModel();
    }

    // List semua cabang
    public function index()
    {
        $data['title'] = 'Daftar Cabang';
        $data['cabangs'] = $this->cabangModel->orderBy('id_cabang', 'DESC')->findAll();
        return view('superadmin/cabang/index', $data);
    }

    // Tampilkan form tambah
    public function create()
    {

        $data['title'] = 'Tambah Cabang';
        return view('superadmin/cabang/create', $data);
    }

    // Simpan data cabang baru
    public function store()
    {

        $rules = [
            'kode_cabang' => 'required|is_unique[cabang.kode_cabang]',
            'nama_cabang' => 'required',
            'alamat'      => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->cabangModel->insert([
            'kode_cabang' => $this->request->getPost('kode_cabang'),
            'nama_cabang' => $this->request->getPost('nama_cabang'),
            'alamat'      => $this->request->getPost('alamat'),
        ]);
        return redirect()->to('/superadmin/cabang')->with('success', 'Cabang berhasil ditambahkan!');
    }

    // Form edit cabang
    public function edit($id = null)
    {
        if (empty($id)) {
            return redirect()->to('/superadmin/cabang')->with('error', 'Parameter tidak valid');
        }

        $data['title'] = 'Edit Cabang';
        $data['cabang'] = $this->cabangModel->find($id);
        if (!$data['cabang']) return redirect()->to('/superadmin/cabang')->with('error', 'Data tidak ditemukan.');
        return view('superadmin/cabang/edit', $data);
    }

    // Update data cabang
    public function update($id)
    {

        $rules = [
            'kode_cabang' => "required|is_unique[cabang.kode_cabang,id_cabang,{$id}]",
            'nama_cabang' => 'required',
            'alamat'      => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->cabangModel->update($id, [
            'kode_cabang' => $this->request->getPost('kode_cabang'),
            'nama_cabang' => $this->request->getPost('nama_cabang'),
            'alamat'      => $this->request->getPost('alamat'),
        ]);
        return redirect()->to('/superadmin/cabang')->with('success', 'Cabang berhasil diupdate!');
    }

    // Hapus cabang
    public function delete($id)
    {
        $this->cabangModel->delete($id);
        return redirect()->to('/superadmin/cabang')->with('success', 'Cabang berhasil dihapus!');
    }
}
