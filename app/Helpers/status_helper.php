<?php

if (!function_exists('status_badge')) {

    function status_badge(string $status): string
    {
        $map = [

            'REPORTED' => [
                'class' => 'secondary',
                'label' => 'Dilaporkan'
            ],

            'APPROVED' => [
                'class' => 'info',
                'label' => 'Disetujui'
            ],

            'ASSIGNED' => [
                'class' => 'primary',
                'label' => 'Vendor Ditentukan'
            ],

            'IN_PROGRESS' => [
                'class' => 'warning',
                'label' => 'Sedang Diperbaiki'
            ],

            'DONE' => [
                'class' => 'success',
                'label' => 'Selesai'
            ],

            'CONFIRMED' => [
                'class' => 'dark',
                'label' => 'Dikonfirmasi'
            ],

            'REJECTED' => [
                'class' => 'danger',
                'label' => 'Ditolak'
            ],
        ];

        $badge = $map[$status] ?? [
            'class' => 'secondary',
            'label' => $status
        ];

        return '<span class="badge bg-' . $badge['class'] . '">' .
            esc($badge['label']) .
            '</span>';
    }
}
