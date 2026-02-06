<?php

namespace App\Services;

use App\Models\AsetModel;
use App\Models\NotifikasiModel;
use Myth\Auth\Models\UserModel;
use App\Services\NotificationService;

class ExpiredNotifier
{
    protected $asetModel;
    protected $notifModel;
    protected $userModel;
    protected $notificationService;

    public function __construct()
    {
        $this->asetModel  = new AsetModel();
        $this->notifModel = new NotifikasiModel();
        $this->userModel  = new UserModel();
        $this->notificationService = new NotificationService();
    }

    public function run(): void
    {
        $today = date('Y-m-d');
        $limit = date('Y-m-d', strtotime('+30 days'));

        /*
    ======================================================
    STEP A — HAPUS SEMUA NOTIF EXPIRED LAMA
    ======================================================
    */
        $this->notifModel
            ->where('tipe', 'expired')
            ->delete();


        /*
    ======================================================
    STEP B — HITUNG ULANG DARI DATA TERBARU
    ======================================================
    */
        $asets = $this->asetModel
            ->where('deleted_at IS NULL', null, false)
            ->where('expired_at >=', $today)
            ->where('expired_at <=', $limit)
            ->findAll();

        foreach ($asets as $a) {

            $days = ceil((strtotime($a['expired_at']) - time()) / 86400);

            // ambil user cabang TERBARU (ikut mutasi otomatis)
            $users = $this->userModel
                ->where('id_cabang', $a['id_cabang'])
                ->findAll();

            foreach ($users as $u) {

                $this->notificationService->send(
                    $u->id,
                    'expired',
                    'Aset Akan Expired',
                    $a['kode_aset'] . ' akan expired dalam ' . $days . ' hari',
                    'admin/aset/detail/' . $a['id_aset'],
                    $a['id_aset']
                );
            }
        }
    }
}
