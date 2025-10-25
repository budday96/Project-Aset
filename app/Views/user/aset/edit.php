<?= $this->extend('layout/template_user/index'); ?>

<?= $this->section('content'); ?>

<h1><?= $title ?></h1>
<form action="<?= base_url('user/aset/update/' . $aset['id_aset']) ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="form-group">
        <label>Nama Aset</label>
        <input type="text" name="nama_aset" class="form-control" value="<?= old('nama_aset', $aset['nama_aset']) ?>">
        <small class="text-danger"><?= session('errors.nama_aset') ?></small>
    </div>

    <div class="form-group">
        <label>Kategori</label>
        <select name="id_kategori" class="form-control">
            <option value="">-- Pilih Kategori --</option>
            <?php foreach ($kategoris as $k): ?>
                <option value="<?= $k['id_kategori'] ?>" <?= old('id_kategori', $aset['id_kategori']) == $k['id_kategori'] ? 'selected' : '' ?>>
                    <?= esc($k['nama_kategori']) ?>
                </option>
            <?php endforeach ?>
        </select>
        <small class="text-danger"><?= session('errors.id_kategori') ?></small>
    </div>

    <div class="form-group">
        <label>Tahun Perolehan</label>
        <input type="number" name="tahun_perolehan" class="form-control" value="<?= old('tahun_perolehan', $aset['tahun_perolehan']) ?>">
        <small class="text-danger"><?= session('errors.tahun_perolehan') ?></small>
    </div>

    <div class="form-group">
        <label>Kondisi</label>
        <select name="kondisi" class="form-control">
            <option value="">-- Pilih Kondisi --</option>
            <option value="Baik" <?= old('kondisi', $aset['kondisi']) == 'Baik' ? 'selected' : '' ?>>Baik</option>
            <option value="Rusak Ringan" <?= old('kondisi', $aset['kondisi']) == 'Rusak Ringan' ? 'selected' : '' ?>>Rusak Ringan</option>
            <option value="Rusak Berat" <?= old('kondisi', $aset['kondisi']) == 'Rusak Berat' ? 'selected' : '' ?>>Rusak Berat</option>
        </select>
        <small class="text-danger"><?= session('errors.kondisi') ?></small>
    </div>

    <div class="form-group">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="Digunakan" <?= old('status', $aset['status']) == 'Digunakan' ? 'selected' : '' ?>>Digunakan</option>
            <option value="Tidak Digunakan" <?= old('status', $aset['status']) == 'Tidak Digunakan' ? 'selected' : '' ?>>Tidak Digunakan</option>
            <option value="Hilang" <?= old('status', $aset['status']) == 'Hilang' ? 'selected' : '' ?>>Hilang</option>
        </select>
        <small class="text-danger"><?= session('errors.status') ?></small>
    </div>

    <div class="form-group">
        <label>Gambar Aset</label>
        <input type="file" name="gambar" class="form-control">
        <small class="text-danger"><?= session('errors.gambar') ?></small>
        <?php if (!empty($aset['gambar'])): ?>
            <br><img src="<?= base_url('uploads/aset/' . $aset['gambar']) ?>" width="120">
        <?php endif; ?>
        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>
    </div>

    <div class="form-group">
        <label>Expired/Kadaluarsa (Opsional)</label>
        <input type="date" name="expired_at" class="form-control" value="<?= old('expired_at', $aset['expired_at']) ?>">
    </div>

    <div class="form-group">
        <label>Keterangan</label>
        <textarea name="keterangan" class="form-control"><?= old('keterangan', $aset['keterangan']) ?></textarea>
    </div>

    <button type="submit" class="btn btn-success">Update</button>
    <a href="<?= base_url('user/aset') ?>" class="btn btn-secondary">Kembali</a>
</form>

<?= $this->endSection(); ?>