<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KelompokHartaModel;
use CodeIgniter\HTTP\RedirectResponse;

class KelompokHarta extends BaseController
{
    protected KelompokHartaModel $model;
    protected $helpers = ['form', 'url'];

    public function __construct()
    {
        $this->model = new KelompokHartaModel();
    }

    /**
     * List semua kelompok (aktif & non aktif jika butuh)
     */
    public function index()
    {
        // kalau mau hanya aktif: $items = $this->model->where('is_active',1)->findAll();
        $items = $this->model->orderBy('id_kelompok_harta', 'ASC')->findAll();

        return view('admin/kelompok_harta/index', [
            'items' => $items,
            'title' => 'Kelompok Harta'
        ]);
    }

    /**
     * Tampilkan form tambah
     */
    public function create()
    {
        // kosongkan old input bila tidak ada
        return view('admin/kelompok_harta/create', [
            'title' => 'Tambah Kelompok Harta',
            'action' => site_url('admin/kelompokharta/store'),
            'method' => 'post',
            'item' => null,
            'validation' => \Config\Services::validation()
        ]);
    }

    /**
     * Simpan data baru
     */
    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'kode_kelompok'   => 'required|alpha_numeric_punct|min_length[1]|max_length[20]|is_unique[kelompok_harta.kode_kelompok]',
            'nama_kelompok'   => 'required|min_length[3]|max_length[120]',
            'umur_tahun'      => 'required|integer|greater_than[0]',
            'tarif_persen_th' => 'required|numeric|greater_than_equal_to[0]'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'kode_kelompok'   => htmlspecialchars(trim($this->request->getPost('kode_kelompok')), ENT_QUOTES, 'UTF-8'),
            'nama_kelompok'   => htmlspecialchars(trim($this->request->getPost('nama_kelompok')), ENT_QUOTES, 'UTF-8'),
            'umur_tahun'      => (int) $this->request->getPost('umur_tahun'),
            'tarif_persen_th' => (float) $this->request->getPost('tarif_persen_th'),
            'is_active'       => (int) $this->request->getPost('is_active') ?: 1,
        ];

        $this->model->insert($data);

        session()->setFlashdata('success', 'Kelompok harta berhasil ditambahkan.');
        return redirect()->to(site_url('admin/kelompokharta'));
    }

    /**
     * Tampilkan form edit
     */
    public function edit($id = null)
    {
        $id = (int) $id;
        $item = $this->model->find($id);
        if (! $item) {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
            return redirect()->to(site_url('admin/kelompokharta'));
        }

        return view('admin/kelompok_harta/edit', [
            'title' => 'Edit Kelompok Harta',
            'action' => site_url("admin/kelompokharta/update/{$id}"),
            'method' => 'post',
            'item' => $item,
            'validation' => \Config\Services::validation()
        ]);
    }

    /**
     * Update data
     */
    public function update($id = null)
    {
        $id = (int) $id;
        $item = $this->model->find($id);
        if (! $item) {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
            return redirect()->to(site_url('admin/kelompokharta'));
        }

        $validation = \Config\Services::validation();

        // aturan unique untuk kode: kecuali record sendiri
        $rules = [
            'kode_kelompok'   => "required|alpha_numeric_punct|min_length[1]|max_length[20]|is_unique[kelompok_harta.kode_kelompok,id_kelompok_harta,{$id}]",
            'nama_kelompok'   => 'required|min_length[3]|max_length[120]',
            'umur_tahun'      => 'required|integer|greater_than[0]',
            'tarif_persen_th' => 'required|numeric|greater_than_equal_to[0]'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'kode_kelompok'   => htmlspecialchars(trim($this->request->getPost('kode_kelompok')), ENT_QUOTES, 'UTF-8'),
            'nama_kelompok'   => htmlspecialchars(trim($this->request->getPost('nama_kelompok')), ENT_QUOTES, 'UTF-8'),
            'umur_tahun'      => (int) $this->request->getPost('umur_tahun'),
            'tarif_persen_th' => (float) $this->request->getPost('tarif_persen_th'),
            'is_active'       => (int) $this->request->getPost('is_active') ?: 1,
        ];

        $this->model->update($id, $data);

        session()->setFlashdata('success', 'Kelompok harta berhasil diperbarui.');
        return redirect()->to(site_url('admin/kelompokharta'));
    }

    /**
     * Soft-delete / non-aktifkan
     */
    public function delete($id = null)
    {
        $id = (int) $id;
        $item = $this->model->find($id);
        if (! $item) {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
            return redirect()->to(site_url('admin/kelompokharta'));
        }

        // soft-delete behaviour kita: set is_active = 0
        $this->model->update($id, ['is_active' => 0]);

        session()->setFlashdata('success', 'Kelompok harta telah dinonaktifkan.');
        return redirect()->to(site_url('admin/kelompokharta'));
    }
}
