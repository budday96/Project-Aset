<?= $this->extend('layout/superadmin_template/index'); ?>
<?= $this->section('content'); ?>

<div class="card">
    <div class="card-body">
        <form method="get" class="card card-body border-0 shadow-sm mb-3">
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="form-label mb-0 small">Event</label>
                    <select name="event" class="form-select form-select-sm">
                        <?php $ev = $filter['event'] ?? ''; ?>
                        <option value="">— Semua —</option>
                        <option value="create" <?= $ev === 'create' ? 'selected' : ''; ?>>create (pengajuan)</option>
                        <option value="send" <?= $ev === 'send' ? 'selected' : ''; ?>>send (dikirim)</option>
                        <option value="receive" <?= $ev === 'receive' ? 'selected' : ''; ?>>receive (diterima)</option>
                        <option value="cancel" <?= $ev === 'cancel' ? 'selected' : ''; ?>>cancel (dibatalkan)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-0 small">Status Tujuan</label>
                    <?php $st = $filter['statusTo'] ?? ''; ?>
                    <select name="status_to" class="form-select form-select-sm">
                        <option value="">— Semua —</option>
                        <option value="pending" <?= $st === 'pending' ? 'selected' : ''; ?>>pending</option>
                        <option value="dikirim" <?= $st === 'dikirim' ? 'selected' : ''; ?>>dikirim</option>
                        <option value="diterima" <?= $st === 'diterima' ? 'selected' : ''; ?>>diterima</option>
                        <option value="dibatalkan" <?= $st === 'dibatalkan' ? 'selected' : ''; ?>>dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label mb-0 small">Dari Tanggal</label>
                    <input type="date" name="from" class="form-control form-control-sm"
                        value="<?= esc($filter['dateFrom'] ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label mb-0 small">Sampai Tanggal</label>
                    <input type="date" name="to" class="form-control form-control-sm"
                        value="<?= esc($filter['dateTo'] ?? '') ?>">
                </div>

                <!-- Tombol Reset -->
                <div class="col-md-1 d-flex align-items-end">
                    <a href="<?= site_url('superadmin/riwayat-mutasi') ?>" class="btn btn-sm btn-warning w-100"
                        data-bs-toggle="tooltip" data-bs-placement="top" title="Reset">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                    </a>
                </div>

                <!-- Tombol Terapkan -->
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-sm w-100" style="background-color: #fd7e14;"
                        data-bs-toggle="tooltip" data-bs-placement="top" title="Terapkan">
                        <i class="bi bi-funnel me-1"></i>
                    </button>
                </div>
            </div>

        </form>

        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table id="example" class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 110px;">Waktu</th>
                            <th>Event</th>
                            <th>Status</th>
                            <th>Mutasi</th>
                            <th>Master / Kode</th>
                            <th>Qty</th>
                            <th>Actor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($logs)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Tidak ada riwayat.</td>
                            </tr>
                            <?php else:
                            // helper label
                            $eventBadge = [
                                'create'  => 'secondary',
                                'send'    => 'info',
                                'receive' => 'success',
                                'cancel'  => 'danger',
                            ];
                            $statusBadge = [
                                'pending'    => 'secondary',
                                'dikirim'    => 'info',
                                'diterima'   => 'success',
                                'dibatalkan' => 'danger',
                            ];
                            foreach ($logs as $r):
                                $waktu = $r['created_at'] ? date('d/m/Y H:i', strtotime($r['created_at'])) : '-';
                                $eCls  = $eventBadge[$r['event']] ?? 'secondary';
                                $sCls  = $statusBadge[$r['status_to']] ?? 'secondary';
                            ?>
                                <tr>
                                    <td class="text-nowrap"><?= esc($waktu) ?></td>
                                    <td>
                                        <span class="badge bg-<?= esc($eCls) ?>"><?= esc($r['event']) ?></span>
                                        <?php if (!empty($r['message'])): ?>
                                            <div class="small text-muted mt-1"><?= esc($r['message']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="small text-muted"><?= esc($r['status_from'] ?? '-') ?> →</div>
                                        <span class="badge bg-<?= esc($sCls) ?>"><?= esc($r['status_to'] ?? '-') ?></span>
                                    </td>
                                    <td class="small">
                                        <div>Dari: <strong><?= esc($r['nama_cabang_asal'] ?? '-') ?></strong> → Ke: <strong><?= esc($r['nama_cabang_tujuan'] ?? '-') ?></strong></div>
                                    </td>
                                    <td class="small">
                                        <div class="fw-semibold"><?= esc($r['nama_master'] ?? '-') ?></div>
                                        <div class="text-muted"><?= esc($r['kode_aset'] ?? '-') ?></div>
                                    </td>
                                    <td><strong><?= (int)$r['qty'] ?></strong></td>
                                    <td class="small text-muted">
                                        <?= esc($r['nama_aktor'] ?? '—') ?>
                                    </td>
                                </tr>
                        <?php endforeach;
                        endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>