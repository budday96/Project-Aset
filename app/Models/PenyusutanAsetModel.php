<?php

namespace App\Models;

use CodeIgniter\Model;

class PenyusutanAsetModel extends Model
{
    protected $table = 'penyusutan_aset';
    protected $primaryKey = 'id_penyusutan';

    protected $allowedFields = [
        'id_aset',
        'tahun',
        'bulan',
        'nilai_perolehan',
        'umur_ekonomis_bulan',
        'metode_penyusutan',
        'beban_penyusutan_bulan',
        'akumulasi_sampai_bulan_ini',
        'nilai_buku',
        'created_at'
    ];

    public $useTimestamps = false;
}
