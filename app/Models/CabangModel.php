<?php

namespace App\Models;

use CodeIgniter\Model;

class CabangModel extends Model
{
    protected $table            = 'cabang';
    protected $primaryKey       = 'id_cabang';
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'kode_cabang',
        'nama_cabang',
        'alamat',
        'created_at',
        'updated_at',
        'deleted_at', //agar bisa diisi otomatis
    ];

    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';
}
