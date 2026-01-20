<?= $this->extend('layout/admin_template/index') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('admin/subkategori/' . $row['id_subkategori'] . '/update') ?>" method="post" class="row g-3">
            <?= csrf_field() ?>
            <div class="col-md-6">
                <label class="form-label">Kategori *</label>
                <input type="text" class="form-control" value="<?= esc($kategoriNama) ?>" readonly>
                <!-- kirim nilai aslinya lewat hidden -->
                <input type="hidden" name="id_kategori" value="<?= (int)$row['id_kategori'] ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Nama Subkategori *</label>
                <input type="text" class="form-control" name="nama_subkategori" value="<?= esc($row['nama_subkategori']) ?>" required>
            </div>
            <div class="col-12">
                <button class="btn btn-primary">Update</button>
                <a href="<?= base_url('admin/subkategori') ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>