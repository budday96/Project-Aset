<?php

namespace App\Models;

use CodeIgniter\Model;

class SubkategoriAsetModel extends Model
{
    protected $table            = 'subkategori_aset';
    protected $primaryKey       = 'id_subkategori';
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'id_kategori',
        'nama_subkategori',
        'slug',          // pastikan kolom ini ada di DB (kamu sudah pakai di controller)
        'created_at',
        'updated_at',
        // 'deleted_at' tidak perlu di-allowed
    ];

    // timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $dateFormat    = 'datetime';

    // SOFT DELETE
    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';

    /** Ambil semua subkategori milik kategori tertentu (exclude deleted secara default). */
    public function getByKategori(int $idKategori): array
    {
        return $this->where('id_kategori', $idKategori)
            ->orderBy('sort', 'ASC')
            ->orderBy('nama_subkategori', 'ASC')
            ->findAll();
    }
}
