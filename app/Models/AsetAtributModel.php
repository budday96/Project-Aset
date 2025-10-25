<?php

namespace App\Models;

use CodeIgniter\Model;

class AsetAtributModel extends Model
{
    protected $table            = 'aset_atribut';
    protected $primaryKey       = null; // composite
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = ['id_aset', 'id_atribut', 'nilai'];
}
