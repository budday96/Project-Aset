<?= $this->extend('layout/superadmin_template/index') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('superadmin/subkategori/' . $row['id_subkategori'] . '/update') ?>" method="post" class="row g-3">
            <?= csrf_field() ?>
            <div class="col-md-6">
                <label class="form-label">Kategori *</label>
                <select name="id_kategori" class="form-select" required>
                    <?php foreach ($kategoris as $k): ?>
                        <option value="<?= $k['id_kategori'] ?>" <?= $row['id_kategori'] == $k['id_kategori'] ? 'selected' : '' ?>>
                            <?= esc($k['nama_kategori']) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nama Subkategori *</label>
                <input type="text" class="form-control" name="nama_subkategori" value="<?= esc($row['nama_subkategori']) ?>" required>
            </div>
            <div class="col-12">
                <button class="btn btn-primary">Update</button>
                <a href="<?= base_url('superadmin/subkategori') ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>