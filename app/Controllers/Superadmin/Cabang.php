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

    // ==== LIST AKTIF ====
    public function index()
    {
        $data = [
            'title'   => 'Daftar Cabang',
            'cabangs' => $this->cabangModel->orderBy('id_cabang', 'DESC')->findAll(), // soft delete -> otomatis aktif saja
        ];
        return view('superadmin/cabang/index', $data);
    }

    // ==== LIST ARSIP ====
    public function trash()
    {
        $data = [
            'title'   => 'Arsip Cabang',
            'cabangs' => $this->cabangModel->onlyDeleted()->orderBy('deleted_at', 'DESC')->findAll(),
        ];
        return view('superadmin/cabang/trash', $data);
    }

    // ==== CREATE ====
    public function create()
    {
        return view('superadmin/cabang/create', ['title' => 'Tambah Cabang']);
    }

    public function store()
    {
        $rules = [
            'kode_cabang' => 'required',
            'nama_cabang' => 'required',
            'alamat'      => 'required',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Cek unik kode (hanya terhadap baris yang belum dihapus)
        $exists = $this->cabangModel
            ->where('kode_cabang', trim((string)$this->request->getPost('kode_cabang')))
            ->where('deleted_at', null)
            ->first();
        if ($exists) {
            return redirect()->back()->withInput()->with('error', 'Kode cabang sudah dipakai.');
        }

        $this->cabangModel->insert([
            'kode_cabang' => trim((string)$this->request->getPost('kode_cabang')),
            'nama_cabang' => trim((string)$this->request->getPost('nama_cabang')),
            'alamat'      => trim((string)$this->request->getPost('alamat')),
        ]);

        return redirect()->to('/superadmin/cabang')->with('success', 'Cabang berhasil ditambahkan!');
    }

    // ==== UPDATE ====
    public function edit($id = null)
    {
        if (empty($id)) {
            return redirect()->to('/superadmin/cabang')->with('error', 'Parameter tidak valid');
        }
        $row = $this->cabangModel->withDeleted()->find((int)$id);
        if (! $row) {
            return redirect()->to('/superadmin/cabang')->with('error', 'Data tidak ditemukan.');
        }
        return view('superadmin/cabang/edit', ['title' => 'Edit Cabang', 'cabang' => $row]);
    }

    public function update($id)
    {
        $rules = [
            'kode_cabang' => 'required',
            'nama_cabang' => 'required',
            'alamat'      => 'required',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Cek unik kode untuk baris aktif lain (abaikan dirinya & baris ter-arsip)
        $kode = trim((string)$this->request->getPost('kode_cabang'));
        $exists = $this->cabangModel
            ->where('kode_cabang', $kode)
            ->where('id_cabang !=', (int)$id)
            ->where('deleted_at', null)
            ->first();
        if ($exists) {
            return redirect()->back()->withInput()->with('error', 'Kode cabang sudah dipakai cabang lain.');
        }

        $this->cabangModel->update((int)$id, [
            'kode_cabang' => $kode,
            'nama_cabang' => trim((string)$this->request->getPost('nama_cabang')),
            'alamat'      => trim((string)$this->request->getPost('alamat')),
        ]);

        return redirect()->to('/superadmin/cabang')->with('success', 'Cabang berhasil diupdate!');
    }

    // ==== SOFT DELETE ====
    public function delete($id)
    {
        $db = \Config\Database::connect();

        // Cek apakah cabang masih dipakai aset aktif
        $dipakai = (int) $db->table('aset')
            ->where('id_cabang', (int)$id)
            ->where('deleted_at', null)
            ->countAllResults();
        if ($dipakai > 0) {
            return redirect()
                ->back()
                ->with('error', "Cabang tidak bisa dihapus karena masih dipakai oleh {$dipakai} aset aktif.");
        }

        // Cek apakah masih ada mutasi yang melibatkan cabang ini
        $mutasi = (int) $db->table('mutasi_aset')
            ->groupStart()
            ->where('dari_cabang', (int)$id)
            ->orWhere('ke_cabang', (int)$id)
            ->groupEnd()
            ->countAllResults();
        if ($mutasi > 0) {
            return redirect()
                ->back()
                ->with('error', "Cabang tidak bisa dihapus karena masih tercatat di {$mutasi} riwayat mutasi aset.");
        }

        // Jika aman → soft delete
        $this->cabangModel->delete((int)$id);
        return redirect()
            ->to('/superadmin/cabang')
            ->with('success', 'Cabang berhasil diarsipkan.');
    }


    // ==== RESTORE (pulihkan dari arsip) ====
    public function restore($id)
    {
        $ok = $this->cabangModel->withDeleted()->update((int)$id, ['deleted_at' => null]);
        return $ok
            ? redirect()->to('/superadmin/cabang/trash')->with('success', 'Cabang dipulihkan.')
            : redirect()->back()->with('error', 'Gagal memulihkan cabang.');
    }

    // ==== PURGE (hapus permanen) ====
    public function purge($id)
    {
        $db = \Config\Database::connect();

        // Guard: tolak jika masih dirujuk aset (bahkan yang terarsip, supaya aman)
        $refAset = (int) $db->table('aset')->where('id_cabang', (int)$id)->countAllResults();
        if ($refAset > 0) {
            return redirect()->back()->with('error', "Tidak bisa hapus permanen: masih dirujuk {$refAset} aset.");
        }

        // Guard: tolak jika masih dirujuk mutasi
        $refMutasi = (int) $db->table('mutasi_aset')
            ->groupStart()->where('dari_cabang', (int)$id)->orWhere('ke_cabang', (int)$id)->groupEnd()
            ->countAllResults();
        if ($refMutasi > 0) {
            return redirect()->back()->with('error', "Tidak bisa hapus permanen: masih ada {$refMutasi} riwayat mutasi.");
        }

        // Hard delete
        $this->cabangModel->delete((int)$id, true);
        return redirect()->to('/superadmin/cabang/trash')->with('success', 'Cabang dihapus permanen.');
    }
}
