<?php

namespace App\Models;

use CodeIgniter\Model;

class AsetModel extends Model
{
    protected $table      = 'aset';
    protected $primaryKey = 'id_aset';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';

    protected $allowedFields = [
        'kode_aset',
        'qr_token',
        'id_master_aset',
        'id_kategori',
        'id_subkategori',
        'id_cabang',
        'id_kelompok_harta',
        'nilai_perolehan',
        'nilai_buku',
        'periode_perolehan',
        'stock',
        'kondisi',
        'status',
        'posisi',
        'gambar',
        'expired_at',
        'keterangan',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];

    /** Relasi join dengan kelompok */
    public function withJoin()
    {
        return $this->select('aset.*, kelompok_harta.nama_kelompok, kelompok_harta.umur_tahun, kelompok_harta.tarif_persen_th, cabang.nama_cabang')
            ->join('kelompok_harta', 'kelompok_harta.id_kelompok_harta = aset.id_kelompok_harta', 'left')
            ->join('cabang', 'cabang.id_cabang = aset.id_cabang', 'left');
    }

    public function getExpiredSoon(int $days = 30)
    {
        return $this->select('aset.*, master_aset.nama_master, cabang.nama_cabang')
            ->join('master_aset', 'master_aset.id_master_aset = aset.id_master_aset', 'left')
            ->join('cabang', 'cabang.id_cabang = aset.id_cabang', 'left')
            ->where('expired_at IS NOT NULL', null, false)
            ->where('expired_at >=', date('Y-m-d'))
            ->where('expired_at <=', date('Y-m-d', strtotime("+{$days} days")))
            ->findAll();
    }
}
