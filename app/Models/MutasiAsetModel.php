<?php

namespace App\Models;

use CodeIgniter\Model;

class MutasiAsetModel extends Model
{
    protected $table            = 'mutasi_aset';
    protected $primaryKey       = 'id_mutasi';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        'kode_mutasi',
        'tanggal_mutasi',
        'id_cabang_asal',
        'id_cabang_tujuan',
        'status',
        'catatan',
        'metode_pengiriman',
        'nama_pengantar',
        'nomor_kendaraan',
        'nomor_surat_jalan',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];
}
