<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SubkategoriAsetModel;
use App\Models\KategoriAsetModel;

class Subkategori extends BaseController
{
    protected $subM;
    protected $katM;

    public function __construct()
    {
        $this->subM = new SubkategoriAsetModel();
        $this->katM = new KategoriAsetModel();
    }

    public function index()
    {
        // Default model exclude deleted
        $data = [
            'title'        => 'Subkategori',
            'subkategoris' => $this->subM
                ->select('subkategori_aset.*, kategori_aset.nama_kategori')
                ->join('kategori_aset', 'kategori_aset.id_kategori = subkategori_aset.id_kategori')
                ->orderBy('kategori_aset.nama_kategori', 'ASC')
                ->orderBy('subkategori_aset.nama_subkategori', 'ASC')
                ->findAll(),
        ];
        return view('admin/subkategori/index', $data);
    }

    public function trash()
    {
        $items = $this->subM
            ->withDeleted()->onlyDeleted()
            ->select('subkategori_aset.*, kategori_aset.nama_kategori')
            ->join('kategori_aset', 'kategori_aset.id_kategori = subkategori_aset.id_kategori')
            ->orderBy('subkategori_aset.deleted_at', 'DESC')
            ->findAll();

        return view('admin/subkategori/trash', [
            'title' => 'Arsip Subkategori',
            'items' => $items,
        ]);
    }

    public function create()
    {
        return view('admin/subkategori/create', [
            'title'     => 'Tambah Subkategori',
            'kategoris' => $this->katM->orderBy('nama_kategori', 'ASC')->findAll(),
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

        $idKat = (int)$this->request->getPost('id_kategori');
        $nama  = trim((string)$this->request->getPost('nama_subkategori'));
        $slug  = url_title($nama, '-', true);

        // Cegah duplikat aktif per kategori (exclude deleted)
        $dup = $this->subM->where([
            'id_kategori'      => $idKat,
            'slug'             => $slug,
        ])->first(); // default exclude deleted
        if ($dup) {
            return redirect()->back()->withInput()->with('error', 'Subkategori sudah ada untuk kategori tersebut.');
        }

        $this->subM->insert([
            'id_kategori'      => $idKat,
            'nama_subkategori' => $nama,
            'slug'             => $slug,
            'deskripsi'        => $this->request->getPost('deskripsi') ?: null,
            'sort'             => (int)($this->request->getPost('sort') ?? 0),
        ]);

        return redirect()->to(base_url('admin/subkategori'))->with('success', 'Subkategori ditambahkan.');
    }

    public function edit($id)
    {
        $row = $this->subM->withDeleted()->find((int)$id);
        if (!$row) return redirect()->back()->with('error', 'Data tidak ditemukan.');
        if (!empty($row['deleted_at'])) {
            return redirect()->to(base_url('admin/subkategori'))->with('error', 'Tidak bisa mengedit data yang diarsip.');
        }

        $kat = $this->katM->find((int)$row['id_kategori']);

        return view('admin/subkategori/edit', [
            'title'        => 'Edit Subkategori',
            'row'          => $row,
            'kategoriNama' => $kat['nama_kategori'] ?? '-',
        ]);
    }


    public function update($id)
    {
        $id = (int) $id;

        $row = $this->subM->withDeleted()->find($id);
        if (!$row) return redirect()->back()->with('error', 'Data tidak ditemukan.');
        if (!empty($row['deleted_at'])) {
            return redirect()->to(base_url('admin/subkategori'))->with('error', 'Tidak bisa mengedit data yang diarsip.');
        }

        // Validasi: cukup nama & (opsional) field lain
        $rules = [
            'nama_subkategori' => 'required|min_length[2]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.');
        }

        // KATEGORI DIKUNCI: ambil dari DB, abaikan input user
        $idKat = (int) $row['id_kategori'];

        $nama = trim((string)$this->request->getPost('nama_subkategori'));
        $slug = url_title($nama, '-', true);

        // Cegah duplikat aktif lain di kategori yang sama (exclude diri sendiri)
        $dup = $this->subM
            ->where('id_kategori', $idKat)
            ->where('slug', $slug)
            ->where('id_subkategori !=', $id)
            ->first();
        if ($dup) {
            return redirect()->back()->withInput()->with('error', 'Subkategori dengan nama tersebut sudah ada di kategori ini.');
        }

        $this->subM->update($id, [
            'id_kategori'      => $idKat, // tetap dari DB
            'nama_subkategori' => $nama,
            'slug'             => $slug,
            'deskripsi'        => $this->request->getPost('deskripsi') ?: null,
            'sort'             => (int)($this->request->getPost('sort') ?? 0),
        ]);

        return redirect()->to(base_url('admin/subkategori'))->with('success', 'Subkategori diupdate.');
    }


    // Soft delete (arsip)
    public function delete($id)
    {
        $this->subM->delete((int)$id);
        return redirect()->back()->with('success', 'Subkategori diarsipkan.');
    }

    // Restore dari arsip
    public function restore($id)
    {
        $db = \Config\Database::connect();
        $ok = $db->table('subkategori_aset')
            ->set('deleted_at', null, false)
            ->where('id_subkategori', (int)$id)
            ->update();

        return $ok
            ? redirect()->to(base_url('admin/subkategori/trash'))->with('success', 'Subkategori dipulihkan.')
            : redirect()->back()->with('error', 'Gagal memulihkan subkategori.');
    }

    // Hapus permanen (pastikan tidak dipakai master_aset)
    public function purge($id)
    {
        $db   = \Config\Database::connect();
        $used = $db->table('master_aset')->where('id_subkategori', (int)$id)->countAllResults();

        if ($used > 0) {
            return redirect()->back()->with('error', 'Tidak bisa hapus permanen: masih dipakai master aset.');
        }

        $this->subM->delete((int)$id, true); // purge
        return redirect()->to(base_url('admin/subkategori/trash'))->with('success', 'Subkategori dihapus permanen.');
    }
}
