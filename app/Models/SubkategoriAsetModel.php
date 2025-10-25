<?php

namespace App\Models;

use CodeIgniter\Model;

class SubkategoriAsetModel extends Model
{
    protected $table            = 'subkategori_aset';
    protected $primaryKey       = 'id_subkategori';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'id_kategori',
        'nama_subkategori',
        'deskripsi',
        'sort',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $dateFormat    = 'datetime';

    /** Ambil semua subkategori milik kategori tertentu (urut sort, nama). */
    public function getByKategori(int $idKategori): array
    {
        return $this->where('id_kategori', $idKategori)
            ->orderBy('sort', 'ASC')
            ->orderBy('nama_subkategori', 'ASC')
            ->findAll();
    }
}
