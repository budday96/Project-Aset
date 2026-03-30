<?php

namespace App\Models;

use CodeIgniter\Model;

class VendorModel extends Model
{
    protected $table            = 'vendor_service';
    protected $primaryKey       = 'id_vendor';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'nama_vendor',
        'telepon',
        'email',
        'alamat',
        'keterangan',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // ======================
    // Custom Query
    // ======================

    public function getActive()
    {
        return $this->where('is_active', 1)
            ->orderBy('nama_vendor', 'ASC')
            ->findAll();
    }

    public function search($keyword)
    {
        return $this->like('nama_vendor', $keyword)
            ->orLike('email', $keyword)
            ->orderBy('nama_vendor', 'ASC')
            ->findAll();
    }
}
