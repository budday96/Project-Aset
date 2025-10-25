<?= $this->extend('layout/superadmin_template/index') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('superadmin/subkategori/store') ?>" method="post" class="row g-3">
            <?= csrf_field() ?>
            <div class="col-md-6">
                <label class="form-label">Kategori *</label>
                <select name="id_kategori" class="form-select" required>
                    <option value="" disabled selected>-- Pilih Kategori --</option>
                    <?php foreach ($kategoris as $k): ?>
                        <option value="<?= $k['id_kategori'] ?>"><?= esc($k['nama_kategori']) ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nama Subkategori *</label>
                <input type="text" class="form-control" name="nama_subkategori" required>
            </div>
            <div class="col-12">
                <button class="btn btn-primary">Simpan</button>
                <a href="<?= base_url('superadmin/subkategori') ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>