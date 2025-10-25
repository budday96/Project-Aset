<?= $this->extend('layout/superadmin_template/index'); ?>
<?= $this->section('content'); ?>

<?php
$keluar = $keluar ?? [];
$masuk  = $masuk ?? [];

$badgeMap = [
    'pending'    => 'warning',
    'dikirim'    => 'info',
    'diterima'   => 'success',
    'dibatalkan' => 'dark',
];

$fmtTanggal = static function ($d) {
    if (empty($d)) return '-';
    $ts = strtotime((string)$d);
    return $ts ? date('d M Y H:i', $ts) : '-';
};

// ==== PERBAIKAN DI SINI: nama aset dari master_aset ====
$assetLabel = static function (array $m): string {
    $master = $m['nama_master'] ?? null;  // dari master_aset
    $kode   = $m['kode_aset']   ?? null;  // dari aset
    $id     = $m['id_aset']     ?? null;

    if ($master && trim($master) !== '') return (string)$master;
    if ($kode   && trim($kode)   !== '') return (string)$kode;
    return $id ? ('Aset #' . $id) : 'Aset';
};

// Qty mutasi (default 1 jika belum ada kolom di mutasi_aset)
$getQty = static function (array $m): int {
    if (!isset($m['qty'])) return 1;
    $q = (int)$m['qty'];
    return $q > 0 ? $q : 1;
};

// stok saat ini pada aset (optional, default '-')
$getStock = static function (array $m) {
    return isset($m['stock']) ? (int)$m['stock'] : null;
};
?>


<!-- Flash messages (Bootstrap 5) -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= esc(session()->getFlashdata('success')); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= esc(session()->getFlashdata('error')); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="col-lg-12">
            <div class="card-header bg-white py-3 px-4 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                <ul class="nav nav-tabs card-header-tabs flex-nowrap" id="mutasiTab" role="tablist">
                    <li class="nav-item" style="min-width: 140px;">
                        <a class="nav-link active fw-semibold" id="keluar-tab" data-bs-toggle="tab" href="#keluar" role="tab" aria-controls="keluar" aria-selected="true">
                            <i class="fas fa-arrow-right me-1"></i> Mutasi Keluar
                        </a>
                    </li>
                    <li class="nav-item" style="min-width: 140px;">
                        <a class="nav-link fw-semibold" id="masuk-tab" data-bs-toggle="tab" href="#masuk" role="tab" aria-controls="masuk" aria-selected="false">
                            <i class="fas fa-arrow-left me-1"></i> Mutasi Masuk
                        </a>
                    </li>
                </ul>
                <div class="d-flex flex-wrap gap-2">
                    <a href="#" class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="Export PDF">
                        <i class="bi bi-filetype-pdf fs-5"></i>
                    </a>
                    <a href="#" class="btn btn-outline-success btn-sm" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="Export Excel">
                        <i class="bi bi-filetype-xls fs-5"></i>
                    </a>
                    <a href="<?= base_url('/superadmin/mutasi'); ?>" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="Refresh">
                        <i class="bi bi-arrow-clockwise fs-5"></i>
                    </a>
                    <a class="btn btn-warning btn-sm fw-semibold d-flex align-items-center" href="<?= base_url('superadmin/mutasi/create') ?>">
                        <i class="bi bi-arrow-left-right me-2 fs-6"></i>
                        <span class="d-none d-sm-inline">Ajukan Mutasi</span>
                        <span class="d-inline d-sm-none">Add</span>
                    </a>
                    <a class="btn btn-outline-danger btn-sm fw-semibold d-flex align-items-center" href="<?= base_url('superadmin/riwayat-mutasi'); ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Riwayat Mutasi">
                        <i class="bi bi-clock-history me-2"></i>
                        <span class="d-none d-sm-inline">Riwayat Mutasi</span>
                    </a>
                </div>
            </div>

            <div class="card-body bg-light">
                <div class="tab-content" id="mutasiTabContent">

                    <!-- List Mutasi Keluar -->
                    <div class="tab-pane fade show active" id="keluar" role="tabpanel" aria-labelledby="keluar-tab">
                        <?php if (!empty($keluar)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($keluar as $m): ?>
                                    <?php
                                    $badge = $badgeMap[$m['status'] ?? ''] ?? 'secondary';
                                    $qty   = $getQty($m);
                                    $label = $assetLabel($m);
                                    ?>
                                    <div class="list-group-item bg-white rounded-3 shadow-sm mb-3 border-0 px-4 py-3">
                                        <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <h5 class="mb-0 fw-bold text-primary"><?= esc($label); ?></h5>
                                                <span class="badge bg-secondary" title="Jumlah unit yang dimutasi">Qty: <?= $qty; ?></span>
                                            </div>
                                            <span class="badge bg-<?= $badge; ?> text-uppercase px-3 py-2 fs-7"><?= esc($m['status'] ?? '-'); ?></span>
                                        </div>

                                        <?php if (!empty($m['keterangan'])): ?>
                                            <p class="mb-2 text-muted">Keterangan : <?= esc($m['keterangan']); ?></p>
                                        <?php endif; ?>

                                        <div class="small mb-3 text-secondary">
                                            <i class="fas fa-building"></i>
                                            <span class="me-2">Cabang Asal : <b><?= esc($m['cabang_asal'] ?? '-'); ?></b></span>
                                            <span class="me-2">Cabang Tujuan : <b><?= esc($m['cabang_tujuan'] ?? '-'); ?></b></span>
                                            <i class="fas fa-calendar-alt ms-1"></i>
                                            <span class="me-2">Tgl Pengajuan: <b><?= $fmtTanggal($m['tanggal_mutasi'] ?? null); ?></b></span>
                                        </div>

                                        <div>
                                            <?php if (($m['status'] ?? '') === 'pending'): ?>
                                                <form action="<?= base_url('superadmin/mutasi/kirim/' . ($m['id_mutasi'] ?? '')); ?>" method="post" class="d-inline">
                                                    <?= csrf_field(); ?>
                                                    <button type="submit" class="btn btn-sm btn-info me-1" onclick="return confirm('Kirim mutasi ini?');">
                                                        <i class="fas fa-paper-plane me-1"></i> Kirim
                                                    </button>
                                                </form>
                                                <form action="<?= base_url('superadmin/mutasi/batalkan/' . ($m['id_mutasi'] ?? '')); ?>" method="post" class="d-inline">
                                                    <?= csrf_field(); ?>
                                                    <button type="submit" class="btn btn-sm btn-secondary" onclick="return confirm('Batalkan mutasi ini?');">
                                                        <i class="fas fa-times me-1"></i> Batalkan
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 mb-2"></i>
                                <div>Belum ada mutasi keluar.</div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- List Mutasi Masuk -->
                    <div class="tab-pane fade" id="masuk" role="tabpanel" aria-labelledby="masuk-tab">
                        <?php if (!empty($masuk)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($masuk as $m): ?>
                                    <?php
                                    $badge = $badgeMap[$m['status'] ?? ''] ?? 'secondary';
                                    $qty   = $getQty($m);
                                    $label = $assetLabel($m);
                                    ?>
                                    <div class="list-group-item bg-white rounded-3 shadow-sm mb-3 border-0 px-4 py-3">
                                        <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <h5 class="mb-0 fw-bold text-success"><?= esc($label); ?></h5>
                                                <span class="badge bg-secondary" title="Jumlah unit yang dimutasi">Qty: <?= $qty; ?></span>
                                            </div>
                                            <span class="badge bg-<?= $badge; ?> text-uppercase px-3 py-2 fs-7"><?= esc($m['status'] ?? '-'); ?></span>
                                        </div>

                                        <?php if (!empty($m['keterangan'])): ?>
                                            <p class="mb-2 text-muted fst-italic"><?= esc($m['keterangan']); ?></p>
                                        <?php endif; ?>

                                        <div class="small mb-3 text-secondary">
                                            <i class="fas fa-building"></i>
                                            <span class="me-2">Cabang Asal : <b><?= esc($m['cabang_asal'] ?? '-'); ?></b></span>
                                            <span class="me-2">Cabang Tujuan: <b><?= esc($m['cabang_tujuan'] ?? '-'); ?></b></span>
                                            <i class="fas fa-calendar-alt ms-1"></i>
                                            <span class="me-2">Tgl Pengajuan : <b><?= $fmtTanggal($m['tanggal_mutasi'] ?? null); ?></b></span>
                                        </div>

                                        <div>
                                            <?php if (in_array($m['status'] ?? '', ['pending', 'dikirim'], true)): ?>
                                                <form action="<?= base_url('superadmin/mutasi/terima/' . ($m['id_mutasi'] ?? '')); ?>" method="post" class="d-inline">
                                                    <?= csrf_field(); ?>
                                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Terima mutasi aset ini?');">
                                                        <i class="fas fa-check me-1"></i> Terima
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 mb-2"></i>
                                <div>Belum ada mutasi masuk.</div>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .list-group-item .badge {
        font-size: 0.85rem;
        letter-spacing: 0.05em;
    }

    .list-group-item {
        transition: box-shadow 0.2s;
    }

    .list-group-item:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.07);
        border-color: #f0f0f0;
    }
</style>

<?= $this->endSection(); ?>