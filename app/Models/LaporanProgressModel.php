<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanProgressModel extends Model
{
    protected $table      = 'laporan_aset_progress';
    protected $primaryKey = 'id_progress';
    protected $returnType = 'array';

    protected $allowedFields = [
        'laporan_id',
        'status',
        'catatan',
        'vendor_id',
        'created_by',
        'created_at'
    ];
}
