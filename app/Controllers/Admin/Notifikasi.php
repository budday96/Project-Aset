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
        $filter = $this->request->getGet('tipe'); // mutasi | expired | null

        $builder = $this->notifModel
            ->where('id_user', user()->id);

        // filter tipe
        if ($filter) {
            $builder->where('tipe', $filter);
        }

        $items = $builder
            ->orderBy('is_read', 'ASC')      // 🔴 unread dulu
            ->orderBy('created_at', 'DESC')  // terbaru dulu
            ->findAll();

        // hitung badge tabs
        $countAll = $this->notifModel->where('id_user', user()->id)->countAllResults(false);
        $countMutasi = $this->notifModel->where(['id_user' => user()->id, 'tipe' => 'mutasi'])->countAllResults(false);
        $countExpired = $this->notifModel->where(['id_user' => user()->id, 'tipe' => 'expired'])->countAllResults(false);

        return view('admin/notifikasi/index', [
            'title' => 'Notifikasi',
            'items' => $items,
            'filter' => $filter,
            'countAll' => $countAll,
            'countMutasi' => $countMutasi,
            'countExpired' => $countExpired
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

    public function open($id)
    {
        $notif = $this->notifModel->find($id);

        if (!$notif || $notif['id_user'] != user()->id) {
            return redirect()->to('/admin/notifikasi');
        }

        // mark read
        if (!$notif['is_read']) {
            $this->notifModel->update($id, ['is_read' => 1]);
        }

        switch ($notif['tipe']) {

            case 'expired':
                return redirect()->to(base_url('admin/aset/detail/' . $notif['ref_id']));

            case 'mutasi':
                return redirect()->to(base_url('admin/mutasi/' . $notif['ref_id']));

            default:
                return redirect()->to(base_url($notif['url']));
        }
    }
}
