<?php

namespace App\Models;

use CodeIgniter\Model;

class SubkategoriModel extends Model
{
    protected $table         = 'subkategori_aset';
    protected $primaryKey    = 'id_subkategori';
    protected $returnType    = 'array';
    protected $allowedFields = ['id_kategori', 'nama_subkategori', 'slug', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
}
