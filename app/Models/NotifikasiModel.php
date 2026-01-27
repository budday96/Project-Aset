<?php

namespace App\Models;

use CodeIgniter\Model;

class NotifikasiModel extends Model
{
    protected $table      = 'notifikasi';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id_user',
        'tipe',
        'judul',
        'pesan',
        'url',
        'is_read',
    ];

    public function getUnread($userId, $limit = 5)
    {
        return $this->where('id_user', $userId)
            ->where('is_read', 0)
            ->orderBy('created_at', 'DESC')
            ->findAll($limit);
    }

    public function markAsReadByUrl($userId, $url)
    {
        return $this->where('id_user', $userId)
            ->where('url', $url)
            ->set(['is_read' => 1])
            ->update();
    }
}
