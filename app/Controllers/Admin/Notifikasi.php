<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\NotifikasiModel;

class Notifikasi extends BaseController
{
    protected $notifModel;

    public function __construct()
    {
        $this->notifModel = new NotifikasiModel();
    }

    public function index()
    {
        $items = $this->notifModel
            ->where('id_user', user()->id)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('admin/notifikasi/index', [
            'title' => 'Notifikasi',
            'items' => $items
        ]);
    }

    public function markAllRead()
    {
        $this->notifModel
            ->where('id_user', user()->id)
            ->set(['is_read' => 1])
            ->update();

        return redirect()->back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
