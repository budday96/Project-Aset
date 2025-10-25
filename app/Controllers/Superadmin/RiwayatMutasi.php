<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Models\MutasiAsetLogModel;

class RiwayatMutasi extends BaseController
{
    protected $logModel;

    public function __construct()
    {
        $this->logModel = new MutasiAsetLogModel();
        helper('form');
    }

    /** List global dengan filter sederhana */
    public function index()
    {
        $event     = $this->request->getGet('event');       // create|send|receive|cancel
        $statusTo  = $this->request->getGet('status_to');   // pending|dikirim|diterima|dibatalkan
        $dateFrom  = $this->request->getGet('from');        // YYYY-MM-DD
        $dateTo    = $this->request->getGet('to');          // YYYY-MM-DD

        $builder = $this->logModel
            ->select('mutasi_aset_log.*, 
              m.id_aset, 
              a.kode_aset, 
              ma.nama_master,
              u.username AS nama_aktor,
              c1.nama_cabang AS nama_cabang_asal,
              c2.nama_cabang AS nama_cabang_tujuan')
            ->join('mutasi_aset m', 'm.id_mutasi = mutasi_aset_log.id_mutasi')
            ->join('aset a', 'a.id_aset = m.id_aset')
            ->join('master_aset ma', 'ma.id_master_aset = a.id_master_aset', 'left')
            ->join('users u', 'u.id = mutasi_aset_log.actor_user', 'left')
            ->join('cabang c1', 'c1.id_cabang = m.dari_cabang', 'left')
            ->join('cabang c2', 'c2.id_cabang = m.ke_cabang', 'left')
            ->orderBy('mutasi_aset_log.id_log', 'DESC');


        if ($event) {
            $builder->where('mutasi_aset_log.event', $event);
        }
        if ($statusTo) {
            $builder->where('mutasi_aset_log.status_to', $statusTo);
        }
        if ($dateFrom) {
            $builder->where('mutasi_aset_log.created_at >=', $dateFrom . ' 00:00:00');
        }
        if ($dateTo) {
            $builder->where('mutasi_aset_log.created_at <=', $dateTo   . ' 23:59:59');
        }

        // Non-superadmin: batasi ke cabang user
        if (! in_groups('superadmin')) {
            $idCab = (int) user()->id_cabang;
            $builder->groupStart()
                ->where('m.dari_cabang', $idCab)
                ->orWhere('m.ke_cabang', $idCab)
                ->groupEnd();
        }

        $logs = $builder->findAll(200); // batasin 200 entri terbaru

        return view('superadmin/mutasi/riwayat_mutasi', [
            'title' => 'Riwayat Mutasi Aset',
            'logs'  => $logs,
            'filter' => compact('event', 'statusTo', 'dateFrom', 'dateTo'),
        ]);
    }
}
