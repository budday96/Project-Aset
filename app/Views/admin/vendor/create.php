<?= $this->extend('layout/admin_template/index'); ?>
<?= $this->section('content'); ?>

<div class="card shadow-sm">
    <div class="card-body">

        <h5>Tambah Vendor</h5>

        <form method="post" action="/admin/vendor/store">
            <?= csrf_field(); ?>

            <div class="mb-3">
                <label>Nama Vendor</label>
                <input type="text" name="nama_vendor" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Telepon</label>
                <input type="text" name="telepon" class="form-control">
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control">
            </div>

            <div class="mb-3">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control"></textarea>
            </div>

            <button class="btn btn-success">Simpan</button>
            <a href="/admin/vendor" class="btn btn-secondary">Kembali</a>
        </form>

    </div>
</div>

<?= $this->endSection(); ?>