<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriAsetModel extends Model
{
    protected $table      = 'kategori_aset';
    protected $primaryKey = 'id_kategori';
    protected $returnType = 'array';

    protected $allowedFields = [
        'kode_kategori',
        'nama_kategori',
        'created_at',
        'updated_at',
    ];

    // timestamps
    protected $useTimestamps = true;

    // SOFT DELETE
    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';
}
