<?php

namespace App\Services;

use App\Models\NotifikasiModel;

class NotificationService
{
    protected $notifModel;

    public function __construct()
    {
        $this->notifModel = new NotifikasiModel();
    }

    public function send(
        int $userId,
        string $type,
        string $title,
        string $message,
        string $path,
        ?int $refId = null
    ) {
        return $this->notifModel->insert([
            'id_user' => $userId,
            'tipe'    => $type,
            'judul'   => $title,
            'pesan'   => $message,
            'url'     => $path,
            'ref_id'  => $refId,
        ]);
    }
}
