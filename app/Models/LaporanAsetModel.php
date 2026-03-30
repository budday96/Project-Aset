<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanAsetModel extends Model
{
    protected $table            = 'laporan_aset';
    protected $primaryKey       = 'id_laporan';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'kode_laporan',
        'user_id',
        'status',
        'estimasi_biaya',
        'keterangan',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function generateKode()
    {
        $year = date('Y');

        $count = $this->where('YEAR(created_at)', $year)
            ->countAllResults();

        return 'LPR-' . $year . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }
}
