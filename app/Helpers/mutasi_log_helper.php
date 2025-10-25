<?php

use App\Models\MutasiAsetLogModel;

/**
 * Tulis log mutasi dengan payload yang fleksibel.
 *
 * @param array $data [
 *   'id_mutasi'      => int (wajib),
 *   'event'          => 'create'|'send'|'receive'|'cancel' (wajib),
 *   'status_from'    => string|null,
 *   'status_to'      => string|null,
 *   'qty'            => int|null,
 *   'id_aset_sumber' => int|null,
 *   'id_aset_tujuan' => int|null,
 *   'dari_cabang'    => int|null,
 *   'ke_cabang'      => int|null,
 *   'actor_user'     => int|null,   // default = user_id() kalau helper auth ada
 *   'message'        => string|null,
 *   'created_at'     => string|null // default NOW()
 * ]
 */
if (! function_exists('mutasi_log_write')) {
    function mutasi_log_write(array $data): bool
    {
        // Validasi ringan
        if (empty($data['id_mutasi'])) return false;
        if (empty($data['event']) || ! in_array($data['event'], ['create', 'send', 'receive', 'cancel'], true)) {
            return false;
        }

        // Default otomatis
        if (! isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        if (! isset($data['actor_user']) && function_exists('user_id')) {
            $data['actor_user'] = user_id();
        }

        // Tulis
        $model = model(MutasiAsetLogModel::class);
        return (bool) $model->insert($data, false);
    }
}

/** Ringkas: log saat pengajuan dibuat (status: NULL -> pending) */
if (! function_exists('log_mutasi_create')) {
    function log_mutasi_create(int $idMutasi, array $snap): bool
    {
        // $snap bisa berisi: qty, id_aset_sumber, dari_cabang, ke_cabang, message(optional)
        return mutasi_log_write(array_merge($snap, [
            'id_mutasi'   => $idMutasi,
            'event'       => 'create',
            'status_from' => null,
            'status_to'   => 'pending',
        ]));
    }
}

/** Ringkas: log saat dikirim (pending -> dikirim) */
if (! function_exists('log_mutasi_send')) {
    function log_mutasi_send(int $idMutasi, array $snap = []): bool
    {
        return mutasi_log_write(array_merge($snap, [
            'id_mutasi'   => $idMutasi,
            'event'       => 'send',
            'status_from' => 'pending',
            'status_to'   => 'dikirim',
        ]));
    }
}

/** Ringkas: log saat diterima (dikirim|pending -> diterima) */
if (! function_exists('log_mutasi_receive')) {
    function log_mutasi_receive(int $idMutasi, array $snap): bool
    {
        // $snap bisa berisi: qty, id_aset_sumber, id_aset_tujuan, dari_cabang, ke_cabang, message(optional)
        // status_from dibiarkan fleksibel (bisa pending/dikirim); kalau tidak diset, kita isi 'dikirim' default
        if (! isset($snap['status_from'])) {
            $snap['status_from'] = 'dikirim';
        }
        return mutasi_log_write(array_merge($snap, [
            'id_mutasi' => $idMutasi,
            'event'     => 'receive',
            'status_to' => 'diterima',
        ]));
    }
}

/** Ringkas: log saat dibatalkan (pending -> dibatalkan) */
if (! function_exists('log_mutasi_cancel')) {
    function log_mutasi_cancel(int $idMutasi, array $snap = []): bool
    {
        // snap bisa memuat message = alasan pembatalan
        return mutasi_log_write(array_merge($snap, [
            'id_mutasi'   => $idMutasi,
            'event'       => 'cancel',
            'status_from' => 'pending',
            'status_to'   => 'dibatalkan',
        ]));
    }
}
