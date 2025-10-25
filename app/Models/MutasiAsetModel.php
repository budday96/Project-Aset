<?php

namespace App\Models;

use CodeIgniter\Model;

class MutasiAsetModel extends Model
{
    protected $table = 'mutasi_aset';
    protected $primaryKey = 'id_mutasi';
    protected $allowedFields = [
        'id_aset',
        'dari_cabang',
        'ke_cabang',
        'tanggal_mutasi',
        'status',
        'keterangan',
        'diterima_oleh',
        'diterima_pada',
        'dibuat_oleh',
        'qty',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;
}
