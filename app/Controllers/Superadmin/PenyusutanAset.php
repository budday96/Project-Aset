<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Models\AsetModel;
use CodeIgniter\Database\RawSql;

class PenyusutanAset extends BaseController
{
    protected $asetModel;

    public function __construct()
    {
        $this->asetModel = new AsetModel();
    }

    public function index()
    {
        $builder = $this->asetModel
            ->select('
                aset.*,
                master_aset.nama_master,
                kelompok_harta.umur_tahun,
                kelompok_harta.tarif_persen_th
            ')
            ->join('master_aset', 'master_aset.id_master_aset = aset.id_master_aset', 'left')
            ->join('kelompok_harta', 'kelompok_harta.id_kelompok_harta = master_aset.id_kelompok_harta', 'left')
            ->orderBy('aset.id_aset', 'ASC')
            ->findAll();

        $asetList = [];

        foreach ($builder as $item) {
            $nilai = (float)$item['nilai_perolehan'];
            $tarif = (float)$item['tarif_persen_th'];
            $perolehan = $item['periode_perolehan'];

            if (!$nilai || !$tarif || !$perolehan) {
                $item['penyusutan_bulanan'] = 0;
                $item['akumulasi'] = 0;
                $item['nilai_buku'] = $nilai;
                $item['bulan'] = 0;
            } else {
                $penyusutan_tahunan = $nilai * $tarif / 100;
                $penyusutan_bulanan = $penyusutan_tahunan / 12;

                $bulanBerjalan = $this->hitungSelisihBulan($perolehan, date('Y-m-01'));
                $akumulasi = $penyusutan_bulanan * $bulanBerjalan;
                $nilai_buku = max(0, $nilai - $akumulasi);

                $item['penyusutan_bulanan'] = round($penyusutan_bulanan, 2);
                $item['akumulasi'] = round($akumulasi, 2);
                $item['nilai_buku'] = round($nilai_buku, 2);
                $item['bulan'] = $bulanBerjalan;
            }

            $asetList[] = $item;
        }

        return view('superadmin/penyusutan_aset/index', [
            'title' => 'Penyusutan Aset',
            'items' => $asetList,
        ]);
    }

    private function hitungSelisihBulan($mulai, $sampai)
    {
        $start = new \DateTime($mulai);
        $end = new \DateTime($sampai);
        $diff = $start->diff($end);
        return ($diff->y * 12) + $diff->m;
    }
}
