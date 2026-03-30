<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\VendorModel;

class Vendor extends BaseController
{
    protected $vendorModel;

    public function __construct()
    {
        $this->vendorModel = new VendorModel();
    }

    public function index()
    {
        $keyword = $this->request->getGet('q');

        if ($keyword) {
            $vendors = $this->vendorModel->search($keyword);
        } else {
            $vendors = $this->vendorModel
                ->orderBy('nama_vendor', 'ASC')
                ->findAll();
        }

        return view('admin/vendor/index', [
            'title' => 'Master Vendor',
            'vendors' => $vendors,
            'keyword' => $keyword
        ]);
    }

    public function create()
    {
        return view('admin/vendor/create', [
            'title' => 'Tambah Vendor'
        ]);
    }

    public function store()
    {
        $this->vendorModel->insert([
            'nama_vendor' => $this->request->getPost('nama_vendor'),
            'telepon'     => $this->request->getPost('telepon'),
            'email'       => $this->request->getPost('email'),
            'alamat'      => $this->request->getPost('alamat'),
            'keterangan'  => $this->request->getPost('keterangan'),
            'is_active'   => 1,
            'created_by'  => user()->id
        ]);

        return redirect()->to('/admin/vendor')
            ->with('success', 'Vendor berhasil ditambahkan');
    }

    public function edit($id)
    {
        $vendor = $this->vendorModel->find($id);

        if (!$vendor) {
            return redirect()->to('/admin/vendor')
                ->with('error', 'Vendor tidak ditemukan');
        }

        return view('admin/vendor/edit', [
            'title'  => 'Edit Vendor',
            'vendor' => $vendor
        ]);
    }

    public function update($id)
    {
        $this->vendorModel->update($id, [
            'nama_vendor' => $this->request->getPost('nama_vendor'),
            'telepon'     => $this->request->getPost('telepon'),
            'email'       => $this->request->getPost('email'),
            'alamat'      => $this->request->getPost('alamat'),
            'keterangan'  => $this->request->getPost('keterangan'),
            'updated_by'  => user()->id
        ]);

        return redirect()->to('/admin/vendor')
            ->with('success', 'Vendor berhasil diperbarui');
    }

    public function toggle($id)
    {
        $vendor = $this->vendorModel->find($id);

        if (!$vendor) {
            return redirect()->back()
                ->with('error', 'Vendor tidak ditemukan');
        }

        $this->vendorModel->update($id, [
            'is_active'  => $vendor['is_active'] ? 0 : 1,
            'updated_by' => user()->id
        ]);

        return redirect()->back()
            ->with('success', 'Status vendor diperbarui');
    }
}
