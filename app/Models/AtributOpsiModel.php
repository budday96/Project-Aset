<?php

namespace App\Models;

use CodeIgniter\Model;

class AtributOpsiModel extends Model
{
    protected $table            = 'atribut_opsi';
    protected $primaryKey       = 'id_opsi';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'id_atribut',
        'label',
        'value',
        'sort',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $dateFormat    = 'datetime';

    /** Ambil opsi untuk satu atribut. */
    public function getByAtribut(int $idAtribut): array
    {
        return $this->where('id_atribut', $idAtribut)
            ->orderBy('sort', 'ASC')
            ->orderBy('label', 'ASC')
            ->findAll();
    }

    /** Ambil opsi untuk banyak atribut sekaligus. */
    public function getByAtributIn(array $ids): array
    {
        if (empty($ids)) return [];
        return $this->whereIn('id_atribut', $ids)
            ->orderBy('id_atribut', 'ASC')
            ->orderBy('sort', 'ASC')
            ->orderBy('label', 'ASC')
            ->findAll();
    }
}
