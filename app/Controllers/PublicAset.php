<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AsetModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class PublicAset extends BaseController
{
    protected $asetModel;

    public function __construct()
    {
        $this->asetModel = new AsetModel();
    }

    public function detail(string $token)
    {
        // Ambil aset by token + join master untuk nama
        $aset = $this->asetModel
            ->select('
                aset.*,
                cabang.nama_cabang,
                kategori_aset.nama_kategori,
                subkategori_aset.nama_subkategori,
                master_aset.nama_master AS nama_aset
            ')
            ->join('cabang', 'cabang.id_cabang = aset.id_cabang')
            ->join('kategori_aset', 'kategori_aset.id_kategori = aset.id_kategori')
            ->join('subkategori_aset', 'subkategori_aset.id_subkategori = aset.id_subkategori', 'left')
            ->join('master_aset', 'master_aset.id_master_aset = aset.id_master_aset', 'left')
            ->where('aset.qr_token', $token)
            ->first();

        if (!$aset) {
            throw PageNotFoundException::forPageNotFound('Aset tidak ditemukan.');
        }

        // Ambil nilai atribut (tabel yang benar: aset_atribut + atribut_aset)
        $db = \Config\Database::connect();
        $nilaiAtribut = $db->table('aset_atribut')
            ->select('aset_atribut.nilai, atribut_aset.nama_atribut, atribut_aset.satuan, atribut_aset.tipe_input, atribut_aset.urutan')
            ->join('atribut_aset', 'atribut_aset.id_atribut = aset_atribut.id_atribut')
            ->where('aset_atribut.id_aset', (int)$aset['id_aset'])
            ->orderBy('atribut_aset.urutan', 'ASC')
            ->get()->getResultArray();

        return view('public/aset/detail', [
            'title'        => 'Detail Aset',
            'aset'         => $aset,
            'nilaiAtribut' => $nilaiAtribut,
        ]);
    }
}
