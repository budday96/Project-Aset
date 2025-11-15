<?php

namespace App\Models;

use CodeIgniter\Model;

class KelompokHartaModel extends Model
{
    protected $table            = 'kelompok_harta';
    protected $primaryKey       = 'id_kelompok_harta';
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'kode_kelompok',
        'nama_kelompok',
        'umur_tahun',
        'tarif_persen_th',
        'is_active',
        'created_at',
        'updated_at'
    ];

    /** Ambil hanya kelompok aktif */
    public function getActive()
    {
        return $this->where('is_active', 1)->orderBy('id_kelompok_harta', 'ASC')->findAll();
    }

    /** Format singkat untuk dropdown */
    public function dropdownList(): array
    {
        $rows = $this->getActive();
        $list = [];
        foreach ($rows as $r) {
            $list[$r['id_kelompok_harta']] = "{$r['kode_kelompok']} - {$r['nama_kelompok']} ({$r['umur_tahun']} th / {$r['tarif_persen_th']}%)";
        }
        return $list;
    }
}
