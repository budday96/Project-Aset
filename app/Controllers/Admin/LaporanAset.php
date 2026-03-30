<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LaporanAsetModel;
use App\Models\LaporanItemModel;
use App\Models\LaporanProgressModel;
use App\Models\VendorModel;
use App\Models\AsetModel;

class LaporanAset extends BaseController
{
    protected $laporanModel;
    protected $itemModel;
    protected $progressModel;
    protected $vendorModel;
    protected $asetModel;

    public function __construct()
    {
        $this->laporanModel  = new LaporanAsetModel();
        $this->itemModel     = new LaporanItemModel();
        $this->progressModel = new LaporanProgressModel();
        $this->vendorModel   = new VendorModel();
        $this->asetModel = new AsetModel();
    }

    public function index()
    {
        $data['title'] = 'Laporan Aset';
        $data['laporan'] = $this->laporanModel
            ->where('status', 'REPORTED')
            ->findAll();

        $status = $this->request->getGet('status');

        $query = $this->laporanModel->orderBy('created_at', 'DESC');

        if ($status) {
            $query->where('status', $status);
        }

        $data['laporan'] = $query->findAll();

        return view('admin/laporan/index', $data);
    }

    public function detail($id)
    {
        $data['title'] = 'Detail Laporan Aset';
        $data['laporan'] = $this->laporanModel->find($id);

        $data['items'] = $this->itemModel
            ->select('
        laporan_aset_item.*,
        aset.kode_aset,
        aset.posisi,
        aset.kondisi,
        aset.gambar as gambar_aset,
        master_aset.nama_master
    ')
            ->join('aset', 'aset.id_aset = laporan_aset_item.aset_id')
            ->join('master_aset', 'master_aset.id_master_aset = aset.id_master_aset')
            ->where('laporan_aset_item.laporan_id', $id)
            ->findAll();

        $data['progress'] = $this->progressModel
            ->select('laporan_aset_progress.*, vendor_service.nama_vendor')
            ->join('vendor_service', 'vendor_service.id_vendor = laporan_aset_progress.vendor_id', 'left')
            ->where('laporan_id', $id)
            ->orderBy('created_at', 'ASC')
            ->findAll();

        return view('admin/laporan/detail', $data);
    }

    public function approve($id)
    {
        $laporan = $this->laporanModel->find($id);

        if ($redirect = $this->requireStatus($laporan, ['REPORTED'])) {
            return $redirect;
        }

        $adminId = user()->id;

        $this->laporanModel->update($id, [
            'status' => 'APPROVED',
            'updated_by' => $adminId
        ]);

        $this->progressModel->insert([
            'laporan_id' => $id,
            'status' => 'APPROVED',
            'catatan' => 'Laporan disetujui admin',
            'created_by' => $adminId,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Laporan di-approve');
    }

    public function reject($id)
    {
        $adminId = user()->id;

        $laporan = $this->laporanModel->find($id);

        if ($redirect = $this->requireStatus($laporan, ['REPORTED'])) {
            return $redirect;
        }

        $this->laporanModel->update($id, [
            'status' => 'REJECTED',
            'updated_by' => $adminId
        ]);

        $this->progressModel->insert([
            'laporan_id' => $id,
            'status' => 'REJECTED',
            'catatan' => 'Laporan ditolak admin',
            'created_by' => $adminId,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Laporan ditolak');
    }

    public function assignForm($id)
    {
        $data['title'] = 'Assign Vendor';
        $data['laporan'] = $this->laporanModel->find($id);

        $data['vendors'] = $this->vendorModel->getActive();

        return view('admin/laporan/assign', $data);
    }

    public function assign($id)
    {
        $adminId = user()->id;
        $vendorId = $this->request->getPost('vendor_id');
        $estimasi = $this->request->getPost('estimasi_biaya');

        $laporan = $this->laporanModel->find($id);

        if ($redirect = $this->requireStatus($laporan, ['APPROVED'])) {
            return $redirect;
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // update header laporan
        $this->laporanModel->update($id, [
            'status' => 'ASSIGNED',
            'estimasi_biaya' => $estimasi,
            'updated_by' => $adminId
        ]);

        $this->updateKondisiAset($id, 'Dalam Perbaikan');

        // insert progress
        $this->progressModel->insert([
            'laporan_id' => $id,
            'status' => 'ASSIGNED',
            'vendor_id' => $vendorId,
            'catatan' => 'Vendor telah ditentukan',
            'created_by' => $adminId,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $db->transComplete();

        return redirect()->to('/admin/laporan/' . $id)
            ->with('success', 'Vendor berhasil di-assign');
    }

    public function progressForm($id)
    {
        $data['title'] = 'Update Progress Laporan';
        $data['laporan'] = $this->laporanModel->find($id);
        $data['progress'] = $this->progressModel
            ->select('laporan_aset_progress.*, vendor_service.nama_vendor')
            ->join('vendor_service', 'vendor_service.id_vendor = laporan_aset_progress.vendor_id', 'left')
            ->where('laporan_id', $id)
            ->orderBy('created_at', 'ASC')
            ->findAll();
        return view('admin/laporan/progress', $data);
    }

    public function updateProgress($id)
    {

        $laporan = $this->laporanModel->find($id);

        if ($redirect = $this->requireStatus($laporan, [
            'ASSIGNED',
            'IN_PROGRESS'
        ])) {
            return $redirect;
        }

        $adminId = user()->id;
        $status = $this->request->getPost('status');
        $catatan = $this->request->getPost('catatan');

        $allowedNext = ['IN_PROGRESS', 'DONE'];

        if (!in_array($status, $allowedNext)) {
            return redirect()->back()->with('error', 'Status tidak diizinkan.');
        }

        if ($status === 'DONE') {
            $this->updateStatusAset($id, 'Digunakan');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // update status header
        $this->laporanModel->update($id, [
            'status' => $status,
            'updated_by' => $adminId
        ]);

        if ($status === 'DONE') {
            $this->updateKondisiAset($id, 'Baik');
        }

        // insert progress log
        $this->progressModel->insert([
            'laporan_id' => $id,
            'status' => $status,
            'catatan' => $catatan,
            'created_by' => $adminId,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $db->transComplete();

        return redirect()->to('/admin/laporan/' . $id)
            ->with('success', 'Progress berhasil diupdate');
    }

    private function requireStatus($laporan, array $allowed)
    {
        if (!$laporan) {
            return redirect()->to('/admin/laporan')
                ->with('error', 'Laporan tidak ditemukan.');
        }

        if (!in_array($laporan['status'], $allowed)) {
            return redirect()->back()
                ->with('error', 'Status laporan tidak valid untuk aksi ini.');
        }

        return null;
    }

    private function updateKondisiAset($laporanId, $kondisi)
    {
        $items = $this->itemModel
            ->where('laporan_id', $laporanId)
            ->findAll();

        foreach ($items as $item) {
            $this->asetModel
                ->update($item['aset_id'], [
                    'kondisi' => $kondisi,
                    'updated_by' => user()->id
                ]);
        }
    }

    private function updateStatusAset($laporanId, $statusAset)
    {
        $items = $this->itemModel
            ->where('laporan_id', $laporanId)
            ->findAll();

        foreach ($items as $item) {
            $this->asetModel->update($item['aset_id'], [
                'status' => $statusAset,
                'updated_by' => user()->id
            ]);
        }
    }
}
