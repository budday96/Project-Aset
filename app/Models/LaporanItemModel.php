<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanItemModel extends Model
{
    protected $table      = 'laporan_aset_item';
    protected $primaryKey = 'id_item';
    protected $returnType = 'array';

    protected $allowedFields = [
        'laporan_id',
        'aset_id',
        'deskripsi_kerusakan',
        'foto'
    ];

    public function asetSedangDilaporkan($asetId)
    {
        return $this->select('laporan_aset.status')
            ->join('laporan_aset', 'laporan_aset.id_laporan = laporan_aset_item.laporan_id')
            ->where('laporan_aset_item.aset_id', $asetId)
            ->whereIn('laporan_aset.status', [
                'REPORTED',
                'APPROVED',
                'ASSIGNED',
                'IN_PROGRESS'
            ])
            ->first();
    }
}
