<?php

namespace App\Models;

use CodeIgniter\Model;

class AtributDefinisiModel extends Model
{
    protected $table            = 'atribut_definisi';
    protected $primaryKey       = 'id_atribut';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'id_subkategori',
        'nama_atribut',
        'input_type',
        'unit',
        'is_required',
        'validations',
        'help_text',
        'sort',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $dateFormat    = 'datetime';

    /** Ambil semua definisi atribut untuk subkategori. */
    public function getBySubkategori(int $idSubkategori): array
    {
        return $this->where('id_subkategori', $idSubkategori)
            ->orderBy('sort', 'ASC')
            ->orderBy('nama_atribut', 'ASC')
            ->findAll();
    }

    /**
     * Ambil definisi atribut + opsi (untuk input_type=select) dalam satu array.
     * Menghindari N+1 query dengan men-load semua opsi sekaligus.
     */
    public function getWithOptions(int $idSubkategori): array
    {
        $defs = $this->getBySubkategori($idSubkategori);
        if (empty($defs)) {
            return [];
        }

        $ids    = array_column($defs, 'id_atribut');
        $optM   = model(AtributOpsiModel::class);
        $allOps = $optM->getByAtributIn($ids);

        // Kelompokkan opsi per atribut
        $opsByAttr = [];
        foreach ($allOps as $op) {
            $opsByAttr[$op['id_atribut']][] = $op;
        }

        foreach ($defs as &$d) {
            $d['options'] = ($d['input_type'] === 'select')
                ? ($opsByAttr[$d['id_atribut']] ?? [])
                : [];
        }
        return $defs;
    }
}
