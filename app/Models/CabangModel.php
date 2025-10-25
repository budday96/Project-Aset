<?php

namespace App\Models;

use CodeIgniter\Model;

class CabangModel extends Model
{
    protected $table      = 'cabang';
    protected $primaryKey = 'id_cabang';
    protected $returnType = 'array';
    protected $allowedFields = ['kode_cabang', 'nama_cabang', 'alamat', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
}
