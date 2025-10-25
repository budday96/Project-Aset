<?= $this->extend('layout/superadmin_template/index'); ?>

<?= $this->section('content'); ?>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('superadmin/kategori/update/' . $kategori['id_kategori']) ?>" method="post"
            class="needs-validation" novalidate>
            <?= csrf_field() ?>

            <div class="row g-3">
                <!-- Nama Kategori -->
                <div class="col-md-12">
                    <label class="form-label">Nama Kategori *</label>
                    <input type="text"
                        name="nama_kategori"
                        class="form-control <?= session('errors.nama_kategori') ? 'is-invalid' : '' ?>"
                        placeholder="Masukkan nama kategori"
                        value="<?= old('nama_kategori', $kategori['nama_kategori']) ?>"
                        required>
                    <div class="invalid-feedback">
                        <?= session('errors.nama_kategori') ?: 'Nama kategori wajib diisi.' ?>
                    </div>
                </div>

                <!-- Kode Kategori -->
                <div class="col-md-12">
                    <label class="form-label">Kode Kategori *</label>
                    <input type="text"
                        name="kode_kategori"
                        class="form-control <?= session('errors.kode_kategori') ? 'is-invalid' : '' ?>"
                        placeholder="Masukkan Kode kategori"
                        value="<?= old('kode_kategori', $kategori['kode_kategori']) ?>"
                        required>
                    <div class="invalid-feedback">
                        <?= session('errors.kode_kategori') ?: 'Kode kategori wajib diisi.' ?>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-success me-2">Update</button>
                <a href="<?= base_url('superadmin/kategori') ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </form>

    </div>
</div>

<?= $this->endSection(); ?>