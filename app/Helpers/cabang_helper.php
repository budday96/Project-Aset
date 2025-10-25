<?php

use CodeIgniter\Model;
use App\Models\CabangModel;

if (!function_exists('nama_cabang_login')) {
    function nama_cabang_login(): ?string
    {
        // Pastikan fungsi auth tersedia
        if (!function_exists('logged_in')) {
            helper('auth');
        }

        if (function_exists('logged_in') && logged_in()) {
            $user = user();
            if ($user && isset($user->id_cabang)) {
                // cache sederhana per request
                static $cache = [];
                $id = (int) $user->id_cabang;

                if (!isset($cache[$id])) {
                    $cache[$id] = (new CabangModel())->find($id)['nama_cabang'] ?? null;
                }
                return $cache[$id];
            }
        }
        return null;
    }
}

function filter_by_cabang(Model $model)
{
    $user = user();

    if (in_groups('superadmin')) {
        return $model;
    }

    return $model->where('aset.id_cabang', $user->id_cabang);
}
