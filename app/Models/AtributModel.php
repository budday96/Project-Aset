<?php

namespace App\Models;

use CodeIgniter\Model;

class AtributModel extends Model
{
    protected $table         = 'atribut_aset';
    protected $primaryKey    = 'id_atribut';
    protected $allowedFields = [
        'id_subkategori',
        'nama_atribut',
        'kode_atribut',
        'tipe_input',
        'satuan',
        'is_required',
        'options_json',
        'urutan',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;

    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';

    public function bySubkategori(int $idSub): array
    {
        return $this->where('id_subkategori', $idSub)->orderBy('urutan', 'ASC')->findAll();
    }
}
