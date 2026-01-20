<?php

namespace App\Controllers\Admin;

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
        $data['title']     = 'Kelola Kategori Aset';
        $data['kategoris'] = $this->kategoriModel
            ->orderBy('id_kategori', 'DESC')
            ->findAll();

        return view('admin/kategori/index', $data);
    }

    public function trash()
    {
        $items = $this->kategoriModel
            ->withDeleted()
            ->onlyDeleted()
            ->orderBy('deleted_at', 'DESC')
            ->findAll();

        return view('admin/kategori/trash', [
            'title' => 'Arsip Kategori Aset',
            'items' => $items,
        ]);
    }

    public function create()
    {
        return view('admin/kategori/create', [
            'title' => 'Tambah Kategori Aset'
        ]);
    }

    public function store()
    {
        $rules = [
            'nama_kategori' => 'required',
            'kode_kategori' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Cegah duplikat kategori aktif
        $dup = $this->kategoriModel
            ->where('kode_kategori', $this->request->getPost('kode_kategori'))
            ->first();

        if ($dup) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kode kategori sudah digunakan.');
        }

        $this->kategoriModel->insert([
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'kode_kategori' => $this->request->getPost('kode_kategori'),
        ]);

        return redirect()->to('/admin/kategori')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit($id = null)
    {
        if (empty($id)) {
            return redirect()->to('/admin/kategori')
                ->with('error', 'Parameter tidak valid');
        }

        $kategori = $this->kategoriModel
            ->withDeleted()
            ->find($id);

        if (!$kategori) {
            return redirect()->to('/admin/kategori')
                ->with('error', 'Data tidak ditemukan.');
        }

        if (!empty($kategori['deleted_at'])) {
            return redirect()->to('/admin/kategori')
                ->with('error', 'Tidak dapat mengedit data yang diarsip.');
        }

        return view('admin/kategori/edit', [
            'title'    => 'Edit Kategori Aset',
            'kategori' => $kategori,
        ]);
    }

    public function update($id)
    {
        $rules = [
            'nama_kategori' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $newKode = trim((string)$this->request->getPost('kode_kategori'));
        if ($newKode !== '') {
            $dup = $this->kategoriModel
                ->where('kode_kategori', $newKode)
                ->where('id_kategori !=', (int)$id)
                ->first();

            if ($dup) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Kode kategori sudah dipakai.');
            }
        }

        $payload = [
            'nama_kategori' => $this->request->getPost('nama_kategori'),
        ];

        if ($newKode !== '') {
            $payload['kode_kategori'] = $newKode;
        }

        $this->kategoriModel->update($id, $payload);

        return redirect()->to('/admin/kategori')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function delete($id)
    {
        try {
            $this->kategoriModel->delete($id);
            return redirect()->to('/admin/kategori')
                ->with('success', 'Kategori diarsipkan.');
        } catch (\Throwable $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengarsipkan kategori.');
        }
    }

    public function restore($id)
    {
        $db = \Config\Database::connect();
        $ok = $db->table('kategori_aset')
            ->set('deleted_at', null, false)
            ->where('id_kategori', (int)$id)
            ->update();

        return $ok
            ? redirect()->to('/admin/kategori/trash')->with('success', 'Kategori dipulihkan.')
            : redirect()->back()->with('error', 'Gagal memulihkan kategori.');
    }
}
