<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\LaporanAsetModel;
use App\Models\LaporanItemModel;
use App\Models\LaporanProgressModel;
use App\Models\AsetModel;

class LaporanAset extends BaseController
{
    protected $laporanModel;
    protected $itemModel;
    protected $progressModel;
    protected $asetModel;

    public function __construct()
    {
        $this->laporanModel  = new LaporanAsetModel();
        $this->itemModel     = new LaporanItemModel();
        $this->progressModel = new LaporanProgressModel();
        $this->asetModel     = new AsetModel();
    }

    public function index()
    {
        $userId = user()->id;

        $data['title'] = 'Laporan Saya';

        $data['laporan'] = $this->laporanModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('user/laporan/index', $data);
    }


    public function create()
    {
        $user = user(); // myth auth helper

        $data['title'] = 'Buat Laporan Aset';

        $data['aset'] = $this->asetModel
            ->where('id_cabang', $user->id_cabang)
            ->where('status', 'Digunakan')
            ->where('deleted_at', null)
            ->orderBy('kode_aset', 'ASC')
            ->findAll();

        return view('user/laporan/create', $data);
    }

    public function store()
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $userId = user()->id;

        // generate kode laporan
        $kode = $this->laporanModel->generateKode();

        // insert laporan
        $this->laporanModel->insert([
            'kode_laporan' => $kode,
            'user_id'      => $userId,
            'status'       => 'REPORTED',
            'created_by'   => $userId
        ]);

        $laporanId = $this->laporanModel->getInsertID();

        $asetIds   = $this->request->getPost('aset_id');
        $deskripsi = $this->request->getPost('deskripsi');

        foreach ($asetIds as $i => $asetId) {

            // cek laporan aktif
            $aktif = $this->itemModel->asetSedangDilaporkan($asetId);

            if ($aktif) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Aset dengan ID ' . $asetId . ' masih memiliki laporan aktif.');
            }

            $aset = $this->asetModel
                ->where('id_aset', $asetId)
                ->where('id_cabang', user()->id_cabang)
                ->first();

            if (!$aset) {
                continue;
            }

            $fotoName = null;

            if (!empty($_FILES['foto']['name'][$i])) {
                $file = $this->request->getFileMultiple('foto')[$i];

                if ($file->isValid()) {
                    $fotoName = $file->getRandomName();
                    $file->move('uploads/laporan/', $fotoName);
                }
            }

            $this->itemModel->insert([
                'laporan_id' => $laporanId,
                'aset_id' => $asetId,
                'deskripsi_kerusakan' => $deskripsi[$i],
                'foto' => $fotoName
            ]);
        }

        // progress pertama
        $this->progressModel->insert([
            'laporan_id' => $laporanId,
            'status' => 'REPORTED',
            'catatan' => 'Laporan dibuat oleh user',
            'created_by' => $userId,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $db->transComplete();

        return redirect()->to('/user/laporan')->with('success', 'Laporan berhasil dibuat');
    }

    public function detail($id)
    {
        $userId = user()->id;

        $laporan = $this->laporanModel
            ->where('id_laporan', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$laporan) {
            return redirect()->to('/user/laporan')
                ->with('error', 'Laporan tidak ditemukan');
        }

        $items = $this->itemModel
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

        $progress = $this->progressModel
            ->select('laporan_aset_progress.*, vendor_service.nama_vendor')
            ->join('vendor_service', 'vendor_service.id_vendor = laporan_aset_progress.vendor_id', 'left')
            ->where('laporan_id', $id)
            ->orderBy('created_at', 'ASC')
            ->findAll();

        $data = [
            'title'    => 'Detail Laporan',
            'laporan'  => $laporan,
            'items'    => $items,
            'progress' => $progress
        ];

        return view('user/laporan/detail', $data);
    }


    public function confirm($id)
    {
        $userId = user()->id;

        $this->laporanModel->update($id, [
            'status' => 'CONFIRMED',
            'updated_by' => $userId
        ]);

        $this->progressModel->insert([
            'laporan_id' => $id,
            'status' => 'CONFIRMED',
            'catatan' => 'Laporan dikonfirmasi selesai oleh user',
            'created_by' => $userId,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Laporan dikonfirmasi');
    }

    public function getAsetDetail($id)
    {
        $aset = $this->asetModel
            ->select('kode_aset, posisi, kondisi, gambar')
            ->where('id_aset', $id)
            ->where('id_cabang', user()->id_cabang)
            ->first();

        if (!$aset) {
            return $this->response->setJSON([
                'status' => false
            ]);
        }

        return $this->response->setJSON([
            'status' => true,
            'data' => $aset
        ]);
    }
}
