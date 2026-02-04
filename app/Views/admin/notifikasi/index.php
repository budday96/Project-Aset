<?= $this->extend('layout/admin_template/index'); ?>
<?= $this->section('content'); ?>

<style>
    /* ===== Notification UI polish ===== */
    .notif-item {
        border-left: 4px solid transparent;
        transition: all .15s ease;
        border-radius: 12px;
        margin-bottom: 8px;
    }

    .notif-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, .06);
    }

    .notif-unread {
        background: #f8fafc;
        font-weight: 500;
    }

    /* warna tipe */
    .notif-mutasi {
        border-left-color: #0d6efd;
    }

    .notif-expired {
        border-left-color: #dc3545;
    }

    .notif-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .icon-mutasi {
        background: #e7f1ff;
        color: #0d6efd;
    }

    .icon-expired {
        background: #fdeaea;
        color: #dc3545;
    }

    .nav-pills .nav-link {
        border-radius: 999px;
        padding: 6px 14px;
        font-size: 14px;
    }
</style>


<div class="card shadow-sm border-0">
    <div class="card-body p-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-bell me-2 text-warning"></i>
                Notifikasi
            </h5>

            <?php if (!empty($items)): ?>
                <a href="<?= site_url('admin/notifikasi/read-all'); ?>"
                    class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-check2-all me-1"></i> Tandai semua dibaca
                </a>
            <?php endif; ?>
        </div>

        <!-- FILTER TABS -->
        <ul class="nav nav-pills mb-4 gap-1" style="--bs-nav-pills-link-active-bg: #ffc107; --bs-nav-pills-link-active-color: #000;">

            <li class="nav-item">
                <a class="nav-link <?= !$filter ? 'active' : '' ?>"
                    href="<?= site_url('admin/notifikasi') ?>">
                    Semua
                    <span class="badge bg-secondary ms-1"><?= $countAll ?></span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= $filter === 'mutasi' ? 'active' : '' ?>"
                    href="<?= site_url('admin/notifikasi?tipe=mutasi') ?>">
                    <i class="bi bi-arrow-left-right"></i>
                    Mutasi
                    <span class="badge bg-primary ms-1"></span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= $filter === 'expired' ? 'active' : '' ?>"
                    href="<?= site_url('admin/notifikasi?tipe=expired') ?>">
                    <i class="bi bi-exclamation-triangle"></i>
                    Expired
                    <span class="badge bg-danger ms-1"></span>
                </a>
            </li>

        </ul>


        <?php if (empty($items)): ?>
            <!-- Empty state -->
            <div class="text-center text-muted py-5">
                <i class="bi bi-bell-slash fs-1 mb-2"></i>
                <p class="mb-0">Belum ada notifikasi</p>
            </div>
        <?php else: ?>
            <div class="list-group list-group-flush">
                <?php foreach ($items as $n): ?>
                    <?php
                    $isUnread = !$n['is_read'];
                    $typeClass = $n['tipe'] === 'mutasi'
                        ? 'notif-mutasi'
                        : 'notif-expired';
                    $iconClass = $n['tipe'] === 'mutasi'
                        ? 'bi-arrow-left-right icon-mutasi'
                        : 'bi-exclamation-triangle icon-expired';
                    ?>
                    <a href="<?= site_url('admin/notifikasi/open/' . $n['id']) ?>"
                        class="list-group-item notif-item <?= $typeClass ?> <?= $isUnread ? 'notif-unread' : '' ?>">
                        <div class="d-flex align-items-start gap-3">
                            <!-- Icon -->
                            <div class="notif-icon <?= $n['tipe'] === 'mutasi' ? 'icon-mutasi' : 'icon-expired' ?>">
                                <i class="bi <?= $iconClass ?>"></i>
                            </div>

                            <!-- Content -->
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <div class="fw-semibold">
                                        <?= esc($n['judul']) ?>
                                    </div>

                                    <?php if ($isUnread): ?>
                                        <span class="badge bg-danger rounded-pill">
                                            Baru
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="small text-muted mt-1">
                                    <?= esc($n['pesan']) ?>
                                </div>
                                <div class="small text-secondary mt-1">
                                    <i class="bi bi-clock me-1"></i>
                                    <?= date('d M Y H:i', strtotime($n['created_at'])) ?>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection(); ?>