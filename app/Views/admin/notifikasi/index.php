<?= $this->extend('layout/admin_template/index'); ?>
<?= $this->section('content'); ?>

<div class="card">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-bell me-1"></i> Notifikasi
            </h5>

            <?php if (!empty($items)): ?>
                <a href="<?= site_url('admin/notifikasi/read-all'); ?>"
                    class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-check2-all"></i> Tandai semua dibaca
                </a>
            <?php endif; ?>
        </div>

        <?php if (empty($items)): ?>
            <div class="text-center text-muted py-5">
                <i class="bi bi-bell-slash fs-1"></i>
                <p class="mt-2">Tidak ada notifikasi</p>
            </div>
        <?php else: ?>

            <div class="list-group list-group-flush">

                <?php foreach ($items as $n): ?>
                    <a href="<?= esc($n['url']) ?>"
                        class="list-group-item list-group-item-action d-flex gap-3 align-items-start
                              <?= $n['is_read'] ? '' : 'bg-light'; ?>">

                        <div class="text-warning fs-5">
                            <?php if ($n['tipe'] === 'mutasi'): ?>
                                <i class="bi bi-arrow-left-right"></i>
                            <?php else: ?>
                                <i class="bi bi-info-circle"></i>
                            <?php endif; ?>
                        </div>

                        <div class="flex-grow-1">
                            <div class="fw-semibold"><?= esc($n['judul']) ?></div>
                            <div class="small text-muted"><?= esc($n['pesan']) ?></div>
                            <div class="small text-secondary">
                                <i class="bi bi-clock"></i>
                                <?= date('d M Y H:i', strtotime($n['created_at'])) ?>
                            </div>
                        </div>

                        <?php if (!$n['is_read']): ?>
                            <span class="badge bg-danger align-self-start">Baru</span>
                        <?php endif; ?>

                    </a>
                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>
</div>

<?= $this->endSection(); ?>