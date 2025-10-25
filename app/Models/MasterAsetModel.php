<?php

namespace App\Models;

use CodeIgniter\Model;

class MasterAsetModel extends Model
{
    protected $table = 'master_aset';
    protected $primaryKey = 'id_master_aset';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';

    protected $allowedFields = [
        'kode_master',
        'nama_master',
        'id_kategori',
        'id_subkategori',
        'nilai_perolehan_default',
        'periode_perolehan_default',
        'expired_default',
        'created_at',
        'updated_at', /* deleted_at cukup di-declare lewat $deletedField */
    ];

    public function withJoin()
    {
        return $this->select('master_aset.*, kategori_aset.nama_kategori, subkategori_aset.nama_subkategori')
            ->join('kategori_aset', 'kategori_aset.id_kategori = master_aset.id_kategori')
            ->join('subkategori_aset', 'subkategori_aset.id_subkategori = master_aset.id_subkategori');
    }

    public function getAtributDefaults(int $idMaster): array
    {
        return $this->db->table('master_aset_atribut maa')
            ->select('maa.id_atribut, maa.nilai_default, aa.nama_atribut, aa.tipe_input, aa.satuan, aa.urutan, aa.is_required, aa.options_json')
            ->join('atribut_aset aa', 'aa.id_atribut = maa.id_atribut')
            ->where('maa.id_master_aset', $idMaster)
            ->orderBy('aa.urutan', 'ASC')
            ->get()->getResultArray();
    }
}
