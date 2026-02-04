<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AsetModel;

class Dashboard extends BaseController
{
    protected $asetModel;

    public function __construct()
    {
        $this->asetModel = new AsetModel();
    }

    public function index(): string
    {
        $idCabang = user()->id_cabang;

        // ==========================
        // Ambil aset akan expired
        // ==========================
        $expiredSoon = $this->asetModel
            ->select('aset.*, master_aset.nama_master')
            ->join('master_aset', 'master_aset.id_master_aset = aset.id_master_aset', 'left')
            ->where('aset.id_cabang', $idCabang)
            ->where('aset.deleted_at IS NULL', null, false)
            ->where('expired_at IS NOT NULL', null, false)
            ->where('expired_at >=', date('Y-m-d'))
            ->where('expired_at <=', date('Y-m-d', strtotime('+30 days')))
            ->orderBy('expired_at', 'ASC')
            ->limit(5)
            ->findAll();


        return view('admin/dashboard/index', [
            'title'       => 'Dashboard',
            'expiredSoon' => $expiredSoon
        ]);
    }
}
