<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Models\SubkategoriModel;
use App\Models\KategoriAsetModel;

class Subkategori extends BaseController
{
    public function index()
    {
        $m = new SubkategoriModel();
        $data = [
            'title'        => 'Subkategori',
            'subkategoris' => $m->select('subkategori_aset.*, kategori_aset.nama_kategori')
                ->join('kategori_aset', 'kategori_aset.id_kategori=subkategori_aset.id_kategori')
                ->orderBy('kategori_aset.nama_kategori', 'ASC')
                ->orderBy('subkategori_aset.nama_subkategori', 'ASC')
                ->findAll(),
        ];
        return view('superadmin/subkategori/index', $data);
    }

    public function create()
    {
        $k = new KategoriAsetModel();
        return view('superadmin/subkategori/create', [
            'title'     => 'Tambah Subkategori',
            'kategoris' => $k->orderBy('nama_kategori', 'ASC')->findAll(),
        ]);
    }

    public function store()
    {
        $rules = [
            'id_kategori'      => 'required|integer',
            'nama_subkategori' => 'required|min_length[2]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.');
        }

        $nama = trim($this->request->getPost('nama_subkategori'));
        $slug = url_title($nama, '-', true);

        (new SubkategoriModel())->insert([
            'id_kategori'      => (int)$this->request->getPost('id_kategori'),
            'nama_subkategori' => $nama,
            'slug'             => $slug,
        ]);

        return redirect()->to(base_url('superadmin/subkategori'))->with('success', 'Subkategori ditambahkan.');
    }

    public function edit($id)
    {
        $m   = new SubkategoriModel();
        $row = $m->find((int)$id);
        if (!$row) return redirect()->back()->with('error', 'Data tidak ditemukan');

        $k = new KategoriAsetModel();
        return view('superadmin/subkategori/edit', [
            'title'     => 'Edit Subkategori',
            'row'       => $row,
            'kategoris' => $k->orderBy('nama_kategori', 'ASC')->findAll(),
        ]);
    }

    public function update($id)
    {
        $rules = [
            'id_kategori'      => 'required|integer',
            'nama_subkategori' => 'required|min_length[2]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.');
        }

        $nama = trim($this->request->getPost('nama_subkategori'));
        $slug = url_title($nama, '-', true);

        (new SubkategoriModel())->update((int)$id, [
            'id_kategori'      => (int)$this->request->getPost('id_kategori'),
            'nama_subkategori' => $nama,
            'slug'             => $slug,
        ]);

        return redirect()->to(base_url('superadmin/subkategori'))->with('success', 'Subkategori diupdate.');
    }

    public function delete($id)
    {
        (new SubkategoriModel())->delete((int)$id);
        return redirect()->back()->with('success', 'Subkategori dihapus.');
    }
}
