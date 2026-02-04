<?php

namespace App\Models;

use CodeIgniter\Model;

class NotifikasiModel extends Model
{
    protected $table      = 'notifikasi';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id_user',
        'id_cabang',
        'tipe',
        'judul',
        'pesan',
        'url',
        'ref_id',
        'is_read',
    ];

    public function getUnread($userId, $limit = 5)
    {
        return $this->groupStart()
            ->where('id_user', $userId)
            ->orWhere('id_cabang', user()->id_cabang)
            ->groupEnd()
            ->where('is_read', 0)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }


    public function markAsReadByUrl($userId, $url)
    {
        return $this->where('id_user', $userId)
            ->where('url', $url)
            ->set(['is_read' => 1])
            ->update();
    }
}
