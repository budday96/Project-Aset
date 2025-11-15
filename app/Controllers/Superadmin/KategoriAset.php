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
        // default: model otomatis exclude deleted
        $data['title']     = 'Kelola Kategori Aset';
        $data['kategoris'] = $this->kategoriModel->orderBy('id_kategori', 'DESC')->findAll();
        return view('superadmin/kategori/index', $data);
    }

    public function trash()
    {
        $items = $this->kategoriModel->withDeleted()->onlyDeleted()->orderBy('deleted_at', 'DESC')->findAll();
        return view('superadmin/kategori/trash', [
            'title'  => 'Arsip Kategori Aset',
            'items'  => $items,
        ]);
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

        // Cegah duplikat untuk kategori AKTIF (deleted_at IS NULL)
        $dup = $this->kategoriModel
            ->where('kode_kategori', $this->request->getPost('kode_kategori'))
            ->first(); // default exclude deleted
        if ($dup) {
            return redirect()->back()->withInput()->with('error', 'Kode kategori sudah digunakan (aktif).');
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

        $data['title']    = 'Edit Kategori Aset';
        $data['kategori'] = $this->kategoriModel->withDeleted()->find($id);
        if (!$data['kategori']) {
            return redirect()->to('/superadmin/kategori')->with('error', 'Data tidak ditemukan.');
        }
        if (!empty($data['kategori']['deleted_at'])) {
            return redirect()->to('/superadmin/kategori')->with('error', 'Tidak dapat mengedit data yang diarsip.');
        }

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

        // Opsional: lindungi kode_kategori agar unik di data aktif bila diubah
        $newKode = trim((string)$this->request->getPost('kode_kategori'));
        if ($newKode !== '') {
            $dup = $this->kategoriModel
                ->where('kode_kategori', $newKode)
                ->where('id_kategori !=', (int)$id)
                ->first();
            if ($dup) {
                return redirect()->back()->withInput()->with('error', 'Kode kategori sudah dipakai.');
            }
        }

        $payload = ['nama_kategori' => $this->request->getPost('nama_kategori')];
        if ($newKode !== '') $payload['kode_kategori'] = $newKode;

        $this->kategoriModel->update($id, $payload);
        return redirect()->to('/superadmin/kategori')->with('success', 'Kategori berhasil diupdate!');
    }

    // Soft delete → set deleted_at
    public function delete($id)
    {
        try {
            $this->kategoriModel->delete($id);
            return redirect()->to('/superadmin/kategori')->with('success', 'Kategori diarsipkan.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Tidak dapat mengarsipkan kategori: ' . $e->getMessage());
        }
    }

    // Pulihkan dari arsip
    public function restore($id)
    {
        $db = \Config\Database::connect();
        $ok = $db->table('kategori_aset')->set('deleted_at', null, false)
            ->where('id_kategori', (int)$id)->update();

        return $ok
            ? redirect()->to('/superadmin/kategori/trash')->with('success', 'Kategori dipulihkan.')
            : redirect()->back()->with('error', 'Gagal memulihkan kategori.');
    }

    // Hapus permanen (opsional: hanya jika tidak dipakai)
    public function purge($id)
    {
        $db = \Config\Database::connect();

        // Cek referensi (master_aset, subkategori_aset)
        $used1 = $db->table('master_aset')->where('id_kategori', (int)$id)->countAllResults();
        $used2 = $db->table('subkategori_aset')->where('id_kategori', (int)$id)->countAllResults();

        if ($used1 > 0 || $used2 > 0) {
            return redirect()->back()->with('error', 'Tidak bisa hapus permanen: masih dipakai master/subkategori.');
        }

        $this->kategoriModel->delete((int)$id, true); // purge
        return redirect()->to('/superadmin/kategori/trash')->with('success', 'Kategori dihapus permanen.');
    }
}
