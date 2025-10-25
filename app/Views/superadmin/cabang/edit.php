<?= $this->extend('layout/superadmin_template/index'); ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('superadmin/cabang/update/' . $cabang['id_cabang']) ?>" method="post"
            class="needs-validation" novalidate>
            <?= csrf_field() ?>

            <div class="row g-3">
                <!-- Kode Cabang -->
                <div class="col-md-6">
                    <label class="form-label">Kode Cabang *</label>
                    <input type="text"
                        name="kode_cabang"
                        class="form-control <?= session('errors.kode_cabang') ? 'is-invalid' : '' ?>"
                        placeholder="Masukkan Kode Cabang"
                        value="<?= old('kode_cabang', $cabang['kode_cabang']) ?>" required>
                    <div class="invalid-feedback">
                        <?= session('errors.kode_cabang') ?: 'Kode cabang wajib diisi.' ?>
                    </div>
                </div>

                <!-- Nama Cabang -->
                <div class="col-md-6">
                    <label class="form-label">Nama Cabang *</label>
                    <input type="text"
                        name="nama_cabang"
                        class="form-control <?= session('errors.nama_cabang') ? 'is-invalid' : '' ?>"
                        placeholder="Masukkan Nama Cabang"
                        value="<?= old('nama_cabang', $cabang['nama_cabang']) ?>" required>
                    <div class="invalid-feedback">
                        <?= session('errors.nama_cabang') ?: 'Nama cabang wajib diisi.' ?>
                    </div>
                </div>

                <!-- Alamat -->
                <div class="col-12">
                    <label class="form-label">Alamat *</label>
                    <textarea name="alamat"
                        class="form-control <?= session('errors.alamat') ? 'is-invalid' : '' ?>"
                        rows="4"
                        placeholder="Masukkan alamat lengkap" required><?= old('alamat', $cabang['alamat']) ?></textarea>
                    <div class="invalid-feedback">
                        <?= session('errors.alamat') ?: 'Alamat wajib diisi.' ?>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-success me-2">Update</button>
                <a href="<?= base_url('superadmin/cabang') ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>