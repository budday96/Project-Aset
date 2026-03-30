<?= $this->extend('layout/admin_template/index'); ?>
<?= $this->section('content'); ?>

<div class="row">

    <!-- HEADER -->
    <div class="col-12 mb-3">
        <div class="card shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="mb-1">
                        <?= esc($laporan['kode_laporan']); ?>
                    </h5>

                    <?= status_badge($laporan['status']); ?>

                    <div class="text-muted small mt-1">
                        Dibuat:
                        <?= date('d M Y H:i', strtotime($laporan['created_at'])) ?>
                    </div>
                </div>

                <!-- ACTION BUTTON -->
                <div>

                    <?php if ($laporan['status'] == 'REPORTED'): ?>

                        <form method="post"
                            action="/admin/laporan/approve/<?= $laporan['id_laporan'] ?>"
                            class="d-inline">
                            <?= csrf_field(); ?>
                            <button class="btn btn-success btn-sm">
                                Approve
                            </button>
                        </form>

                        <form method="post"
                            action="/admin/laporan/reject/<?= $laporan['id_laporan'] ?>"
                            class="d-inline">
                            <?= csrf_field(); ?>
                            <button class="btn btn-danger btn-sm">
                                Reject
                            </button>
                        </form>

                    <?php endif; ?>

                    <?php if ($laporan['status'] == 'APPROVED'): ?>
                        <a href="/admin/laporan/assign/<?= $laporan['id_laporan'] ?>"
                            class="btn btn-primary btn-sm">
                            Assign Vendor
                        </a>
                    <?php endif; ?>

                    <?php if (in_array($laporan['status'], ['ASSIGNED', 'IN_PROGRESS'])): ?>
                        <a href="/admin/laporan/progress/<?= $laporan['id_laporan'] ?>"
                            class="btn btn-warning btn-sm">
                            Update Progress
                        </a>
                    <?php endif; ?>

                </div>

            </div>
        </div>
    </div>


    <!-- LIST ASET -->
    <div class="col-md-6">
        <div class="card shadow-sm mb-3">
            <div class="card-header">
                <strong>Aset Dilaporkan</strong>
            </div>

            <div class="card-body">

                <?php foreach ($items as $item): ?>
                    <div class="border rounded p-3 mb-3">

                        <div class="fw-bold">
                            Aset ID: <?= esc($item['aset_id']); ?>
                        </div>

                        <div class="mt-2">
                            <?= esc($item['deskripsi_kerusakan']); ?>
                        </div>

                        <?php if ($item['foto']): ?>
                            <img src="/uploads/laporan/<?= $item['foto']; ?>"
                                class="img-thumbnail mt-2"
                                width="140">
                        <?php endif; ?>

                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>


    <!-- TIMELINE -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header">
                <strong>Timeline Progress</strong>
            </div>

            <div class="card-body">

                <?php foreach ($progress as $p): ?>
                    <div class="d-flex mb-3">

                        <div class="me-3">
                            <span class="badge bg-secondary">&nbsp;</span>
                        </div>

                        <div>
                            <div>
                                <?= status_badge($p['status']); ?>
                            </div>

                            <?php if (!empty($p['nama_vendor'])): ?>
                                <small class="text-muted">
                                    Vendor: <?= esc($p['nama_vendor']); ?>
                                </small><br>
                            <?php endif; ?>

                            <div>
                                <?= esc($p['catatan']); ?>
                            </div>

                            <small class="text-muted">
                                <?= date('d M Y H:i', strtotime($p['created_at'])) ?>
                            </small>
                        </div>

                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>

</div>

<?= $this->endSection(); ?>