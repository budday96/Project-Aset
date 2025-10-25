<?php

namespace App\Models;

use CodeIgniter\Model;

class AsetModel extends Model
{
    protected $table      = 'aset';
    protected $primaryKey = 'id_aset';
    protected $returnType = 'array';

    protected $allowedFields = [
        'kode_aset',
        'qr_token',
        'id_master_aset',
        // 'nama_aset',
        'id_kategori',
        'id_subkategori',
        'id_cabang',
        'nilai_perolehan',
        'periode_perolehan',
        'stock',
        'kondisi',
        'status',
        'posisi',
        'gambar',
        'expired_at',
        'keterangan',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
    protected $useTimestamps = true;

    // Soft delete
    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';
}
