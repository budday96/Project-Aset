<?= $this->extend('layout/admin_template/index'); ?>
<?= $this->section('content'); ?>

<div class="card shadow-sm">

    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Master Vendor</h5>
        <a href="/admin/vendor/create" class="btn btn-primary btn-sm">
            + Tambah Vendor
        </a>
    </div>

    <div class="card-body">

        <!-- SEARCH -->
        <form method="get" class="mb-3">
            <div class="input-group">
                <input type="text"
                    name="q"
                    value="<?= esc($keyword ?? '') ?>"
                    class="form-control"
                    placeholder="Cari nama atau email vendor...">
                <button class="btn btn-outline-secondary">Cari</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama Vendor</th>
                        <th>Telepon</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    <?php if (empty($vendors)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Tidak ada data vendor
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($vendors as $v): ?>
                        <tr>
                            <td><strong><?= esc($v['nama_vendor']) ?></strong></td>
                            <td><?= esc($v['telepon'] ?? '-') ?></td>
                            <td><?= esc($v['email'] ?? '-') ?></td>
                            <td>
                                <?php if ($v['is_active']): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="/admin/vendor/edit/<?= $v['id_vendor'] ?>"
                                    class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <a href="/admin/vendor/toggle/<?= $v['id_vendor'] ?>"
                                    class="btn btn-sm <?= $v['is_active'] ? 'btn-danger' : 'btn-success' ?>"
                                    onclick="return confirm('Ubah status vendor ini?')">
                                    <?= $v['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?>
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