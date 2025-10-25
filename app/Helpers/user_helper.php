<?php


use App\Models\CabangModel;

function get_nama_cabang($id_cabang)
{
    $cabangModel = new CabangModel();
    $cabang = $cabangModel->find($id_cabang);
    return $cabang['nama_cabang'] ?? '-';
}
