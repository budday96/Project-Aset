<?php

use App\Models\MutasiAsetLogModel;

if (! function_exists('log_mutasi_header')) {

    /**
     * Log level HEADER.
     */
    function log_mutasi_header(int $idHeader, string $aksi, string $pesan = '')
    {
        $log = new MutasiAsetLogModel();

        $log->insert([
            'id_header'   => $idHeader,
            'id_detail'   => null, // kolom yang benar
            'aksi'        => $aksi,
            'qty'         => null,
            'id_aset'     => null,
            'pesan'       => $pesan,
            'dibuat_oleh' => user_id(),
            'dibuat_pada' => date('Y-m-d H:i:s'),
        ]);
    }
}

if (! function_exists('log_mutasi_detail')) {

    /**
     * Log level DETAIL.
     */
    function log_mutasi_detail(
        int    $idHeader,
        int    $idDetail,
        string $aksi,
        int    $qty,
        int    $idAset,
        string $pesan = ''
    ) {
        $log = new MutasiAsetLogModel();

        $log->insert([
            'id_header'   => $idHeader,
            'id_detail'   => $idDetail, // kolom yang benar
            'aksi'        => $aksi,
            'qty'         => $qty,
            'id_aset'     => $idAset,
            'pesan'       => $pesan,
            'dibuat_oleh' => user_id(),
            'dibuat_pada' => date('Y-m-d H:i:s'),
        ]);
    }
}
