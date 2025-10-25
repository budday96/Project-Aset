<?= $this->extend('layout/template_user/index'); ?>

<?= $this->section('content'); ?>

<h1><?= $title ?></h1>
<form action="<?= base_url('user/aset/store') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="form-group">
        <label>Nama Aset</label>
        <input type="text" name="nama_aset" class="form-control" value="<?= old('nama_aset') ?>">
        <small class="text-danger"><?= session('errors.nama_aset') ?></small>
    </div>

    <div class="form-group">
        <label>Kategori</label>
        <select name="id_kategori" class="form-control">
            <option value="">-- Pilih Kategori --</option>
            <?php foreach ($kategoris as $k): ?>
                <option value="<?= $k['id_kategori'] ?>" <?= old('id_kategori') == $k['id_kategori'] ? 'selected' : '' ?>>
                    <?= esc($k['nama_kategori']) ?>
                </option>
            <?php endforeach ?>
        </select>
        <small class="text-danger"><?= session('errors.id_kategori') ?></small>
    </div>

    <div class="form-group">
        <label>Tahun Perolehan</label>
        <input type="number" name="tahun_perolehan" class="form-control" value="<?= old('tahun_perolehan') ?>">
        <small class="text-danger"><?= session('errors.tahun_perolehan') ?></small>
    </div>

    <div class="form-group">
        <label>Kondisi</label>
        <select name="kondisi" class="form-control">
            <option value="">-- Pilih Kondisi --</option>
            <option value="Baik" <?= old('kondisi') == 'Baik' ? 'selected' : '' ?>>Baik</option>
            <option value="Rusak Ringan" <?= old('kondisi') == 'Rusak Ringan' ? 'selected' : '' ?>>Rusak Ringan</option>
            <option value="Rusak Berat" <?= old('kondisi') == 'Rusak Berat' ? 'selected' : '' ?>>Rusak Berat</option>
        </select>
        <small class="text-danger"><?= session('errors.kondisi') ?></small>
    </div>

    <div class="form-group">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="Digunakan" <?= old('status') == 'Digunakan' ? 'selected' : '' ?>>Digunakan</option>
            <option value="Tidak Digunakan" <?= old('status') == 'Tidak Digunakan' ? 'selected' : '' ?>>Tidak Digunakan</option>
            <option value="Hilang" <?= old('status') == 'Hilang' ? 'selected' : '' ?>>Hilang</option>
        </select>
        <small class="text-danger"><?= session('errors.status') ?></small>
    </div>

    <div class="form-group">
        <label>Gambar Aset</label>
        <input type="file" name="gambar" class="form-control">
        <small class="text-danger"><?= session('errors.gambar') ?></small>
    </div>

    <div class="form-group">
        <label>Expired/Kadaluarsa (Opsional)</label>
        <input type="date" name="expired_at" class="form-control" value="<?= old('expired_at') ?>">
    </div>

    <div class="form-group">
        <label>Keterangan</label>
        <textarea name="keterangan" class="form-control"><?= old('keterangan') ?></textarea>
    </div>

    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="<?= base_url('user/aset') ?>" class="btn btn-secondary">Kembali</a>
</form>

<?= $this->endSection(); ?>