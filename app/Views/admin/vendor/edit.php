<?= $this->extend('layout/admin_template/index'); ?>
<?= $this->section('content'); ?>

<div class="card shadow-sm">
    <div class="card-body">

        <h5>Edit Vendor</h5>

        <form method="post" action="/admin/vendor/update/<?= $vendor['id_vendor'] ?>">
            <?= csrf_field(); ?>

            <div class="mb-3">
                <label>Nama Vendor</label>
                <input type="text"
                    name="nama_vendor"
                    value="<?= esc($vendor['nama_vendor']) ?>"
                    class="form-control"
                    required>
            </div>

            <div class="mb-3">
                <label>Telepon</label>
                <input type="text"
                    name="telepon"
                    value="<?= esc($vendor['telepon'] ?? '') ?>"
                    class="form-control">
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email"
                    name="email"
                    value="<?= esc($vendor['email'] ?? '') ?>"
                    class="form-control">
            </div>

            <div class="mb-3">
                <label>Alamat</label>
                <textarea name="alamat"
                    class="form-control"><?= esc($vendor['alamat'] ?? '') ?></textarea>
            </div>

            <div class="mb-3">
                <label>Keterangan</label>
                <textarea name="keterangan"
                    class="form-control"><?= esc($vendor['keterangan'] ?? '') ?></textarea>
            </div>

            <button class="btn btn-primary">Update</button>
            <a href="/admin/vendor" class="btn btn-secondary">Kembali</a>

        </form>

    </div>
</div>

<?= $this->endSection(); ?>