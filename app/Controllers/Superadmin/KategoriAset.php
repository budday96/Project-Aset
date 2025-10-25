<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Models\KategoriAsetModel;

class KategoriAset extends BaseController
{
    protected $kategoriModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriAsetModel();
    }

    public function index()
    {
        $data['title'] = 'Kelola Kategori Aset';
        $data['kategoris'] = $this->kategoriModel->orderBy('id_kategori', 'DESC')->findAll();
        return view('superadmin/kategori/index', $data);
    }

    public function create()
    {
        $data['title'] = 'Tambah Kategori Aset';
        return view('superadmin/kategori/create', $data);
    }

    public function store()
    {
        $rules = [
            'nama_kategori' => 'required',
            'kode_kategori' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->kategoriModel->insert([
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'kode_kategori' => $this->request->getPost('kode_kategori'),
        ]);
        return redirect()->to('/superadmin/kategori')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit($id = null)
    {
        if (empty($id)) {
            return redirect()->to('/superadmin/kategori')->with('error', 'Parameter tidak valid');
        }

        $data['title'] = 'Edit Kategori Aset';
        $data['kategori'] = $this->kategoriModel->find($id);
        if (!$data['kategori']) return redirect()->to('/superadmin/kategori')->with('error', 'Data tidak ditemukan.');
        return view('superadmin/kategori/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'nama_kategori' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->kategoriModel->update($id, [
            'nama_kategori' => $this->request->getPost('nama_kategori'),
        ]);
        return redirect()->to('/superadmin/kategori')->with('success', 'Kategori berhasil diupdate!');
    }

    public function delete($id)
    {
        $this->kategoriModel->delete($id);
        return redirect()->to('/superadmin/kategori')->with('success', 'Kategori berhasil dihapus!');
    }
}
