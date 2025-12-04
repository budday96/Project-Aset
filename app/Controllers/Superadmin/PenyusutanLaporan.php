<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Models\PenyusutanAsetModel;
use App\Models\AsetModel;
use App\Models\CabangModel;
use App\Models\KategoriAsetModel;

class PenyusutanLaporan extends BaseController
{
    protected $penyModel;
    protected $asetModel;
    protected $cabangModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->penyModel     = new PenyusutanAsetModel();
        $this->asetModel     = new AsetModel();
        $this->cabangModel   = new CabangModel();
        $this->kategoriModel = new KategoriAsetModel();
    }

    /**
     * 📌 LAPORAN UTAMA DENGAN FILTER
     */
    public function index()
    {
        $tahun       = $this->request->getGet('tahun') ?? date('Y');
        $bulan       = $this->request->getGet('bulan') ?? null;
        $id_cabang   = $this->request->getGet('cabang') ?? null;
        $id_kategori = $this->request->getGet('kategori') ?? null;

        $builder = $this->penyModel
            ->select("
        penyusutan_aset.*,
        aset.kode_aset,
        aset.periode_perolehan,
        aset.nilai_perolehan AS harga_perolehan,
        aset.id_cabang,
        aset.id_kategori,
        cabang.nama_cabang,
        kategori_aset.nama_kategori,
        master_aset.nama_master AS nama_aset,
        kelompok_harta.tarif_persen_th,
        kelompok_harta.umur_tahun,
        kelompok_harta.umur_bulan
    ")
            ->join('aset', 'aset.id_aset = penyusutan_aset.id_aset')
            ->join('master_aset', 'master_aset.id_master_aset = aset.id_master_aset')
            ->join('kelompok_harta', 'kelompok_harta.id_kelompok_harta = master_aset.id_kelompok_harta')
            ->join('cabang', 'cabang.id_cabang = aset.id_cabang', 'left')
            ->join('kategori_aset', 'kategori_aset.id_kategori = aset.id_kategori', 'left')
            ->where('penyusutan_aset.tahun', $tahun);



        if ($bulan) {
            $builder->where('penyusutan_aset.bulan', $bulan);
        }
        if ($id_cabang) {
            $builder->where('aset.id_cabang', $id_cabang);
        }
        if ($id_kategori) {
            $builder->where('aset.id_kategori', $id_kategori);
        }

        $builder->orderBy('aset.kode_aset', 'ASC');

        $rows = $builder->findAll();

        return view('superadmin/penyusutan/index', [
            'title'       => 'Laporan Penyusutan Aset',
            'items'       => $rows,
            'cabangs'     => $this->cabangModel->findAll(),
            'kategoris'   => $this->kategoriModel->findAll(),
            'tahun'       => $tahun,
            'bulan'       => $bulan,
            'id_cabang'   => $id_cabang,
            'id_kategori' => $id_kategori,
        ]);
    }

    /**
     * 📌 DETAIL PENYUSUTAN PER ASET
     */
    public function detail($idAset)
    {
        $aset = $this->asetModel
            ->select("
                aset.*,
                master_aset.nama_master AS nama_aset,
                cabang.nama_cabang,
                kategori_aset.nama_kategori
            ")
            ->join('master_aset', 'master_aset.id_master_aset = aset.id_master_aset')
            ->join('cabang', 'cabang.id_cabang = aset.id_cabang', 'left')
            ->join('kategori_aset', 'kategori_aset.id_kategori = aset.id_kategori', 'left')
            ->find($idAset);

        if (!$aset) {
            return redirect()->back()->with('error', 'Aset tidak ditemukan.');
        }

        // Ambil seluruh riwayat penyusutan
        $history = $this->penyModel
            ->where('id_aset', $idAset)
            ->orderBy('tahun ASC, bulan ASC')
            ->findAll();

        return view('superadmin/penyusutan/detail', [
            'title'   => 'Detail Penyusutan - ' . $aset['kode_aset'],
            'aset'    => $aset,
            'history' => $history,
        ]);
    }
}
