<?= $this->extend('layout/admin_template/index'); ?>
<?= $this->section('content'); ?>

<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Manajemen Laporan Aset</h5>
    </div>

    <div class="card-body">

        <!-- FILTER STATUS -->
        <div class="mb-3">
            <a href="/admin/laporan" class="btn btn-sm btn-outline-secondary">Semua</a>
            <a href="?status=REPORTED" class="btn btn-sm btn-outline-secondary">Reported</a>
            <a href="?status=APPROVED" class="btn btn-sm btn-outline-info">Approved</a>
            <a href="?status=ASSIGNED" class="btn btn-sm btn-outline-primary">Assigned</a>
            <a href="?status=IN_PROGRESS" class="btn btn-sm btn-outline-warning">Progress</a>
            <a href="?status=DONE" class="btn btn-sm btn-outline-success">Done</a>
            <a href="?status=REJECTED" class="btn btn-sm btn-outline-danger">Rejected</a>
        </div>

        <!-- TABLE -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Kode Laporan</th>
                        <th>Pelapor</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($laporan)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                Tidak ada data laporan
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($laporan as $l): ?>
                        <tr>

                            <td>
                                <strong><?= esc($l['kode_laporan']) ?></strong>
                            </td>

                            <td>
                                User #<?= esc($l['user_id']) ?>
                            </td>

                            <td>
                                <?= status_badge($l['status']); ?>
                            </td>

                            <td>
                                <?= date('d M Y H:i', strtotime($l['created_at'])) ?>
                            </td>

                            <td>
                                <a href="/admin/laporan/<?= $l['id_laporan'] ?>"
                                    class="btn btn-sm btn-primary">
                                    Detail
                                </a>
                            </td>

                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>

    </div>
</div>

<?= $this->endSection(); ?>