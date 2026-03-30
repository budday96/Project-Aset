<?= $this->extend('layout/user_template/index'); ?>
<?= $this->section('content'); ?>

<div class="card">
    <div class="card-body">

        <a href="/user/laporan/create" class="btn btn-primary mb-3">
            Buat Laporan
        </a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Status</th>
                    <th>Estimasi Biaya</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($laporan as $l): ?>
                    <tr>
                        <td><?= $l['kode_laporan']; ?></td>
                        <td><?= $l['status']; ?></td>
                        <td>
                            <?= $l['estimasi_biaya']
                                ? 'Rp ' . number_format($l['estimasi_biaya'], 0, ',', '.')
                                : '-' ?>
                        </td>
                        <td>
                            <a href="/user/laporan/detail/<?= $l['id_laporan']; ?>"
                                class="btn btn-sm btn-info">
                                Detail
                            </a>

                            <?php if ($l['status'] == 'DONE'): ?>
                                <form method="post"
                                    action="/user/laporan/confirm/<?= $l['id_laporan']; ?>"
                                    style="display:inline;">
                                    <?= csrf_field(); ?>
                                    <button class="btn btn-sm btn-success">
                                        Konfirmasi
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</div>

<?= $this->endSection(); ?>