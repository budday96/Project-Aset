<?php

namespace App\Models;

use CodeIgniter\Model;

class MutasiAsetLogModel extends Model
{
    protected $table         = 'mutasi_aset_log';
    protected $primaryKey    = 'id_log';
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'id_mutasi',
        'id_aset_sumber',
        'id_aset_tujuan',
        'dari_cabang',
        'ke_cabang',
        'qty',
        'status_from',
        'status_to',
        'event',        // create | send | receive | cancel
        'actor_user',
        'message',
        'created_at',
    ];

    /** Tulisan ringkas untuk list */
    public function listByMutasi(int $idMutasi): array
    {
        return $this->where('id_mutasi', $idMutasi)
            ->orderBy('created_at', 'ASC')
            ->findAll();
    }

    /** List terbaru global (untuk halaman Riwayat) */
    public function latest(int $limit = 100): array
    {
        return $this->orderBy('id_log', 'DESC')->findAll($limit);
    }
}
