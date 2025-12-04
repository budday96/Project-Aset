<?php

namespace App\Models;

use CodeIgniter\Model;

class MutasiAsetDetailModel extends Model
{
    protected $table            = 'mutasi_aset_detail';
    protected $primaryKey       = 'id_detail';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        'id_mutasi',
        'id_aset_asal',
        'qty',
        'keterangan',
    ];
}
