<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Models\SubkategoriModel;
use App\Models\AtributModel;

class AsetAjax extends BaseController
{
    public function subkategoriByKategori($idKategori)
    {
        $rows = (new SubkategoriModel())
            ->where('id_kategori', (int)$idKategori)
            ->orderBy('nama_subkategori', 'ASC')
            ->findAll();

        return $this->response->setJSON($rows);
    }

    public function atributBySubkategori($idSubkategori)
    {
        $rows = (new AtributModel())->bySubkategori((int)$idSubkategori);

        $data = array_map(function ($r) {
            return [
                'id_atribut'   => (int)$r['id_atribut'],
                'nama_atribut' => $r['nama_atribut'],
                'tipe_input'   => $r['tipe_input'],
                'is_required'  => (int)$r['is_required'],
                'satuan'       => $r['satuan'],
                'options'      => $r['options_json'] ? json_decode($r['options_json'], true) : null,
            ];
        }, $rows);

        return $this->response->setJSON($data);
    }
}
