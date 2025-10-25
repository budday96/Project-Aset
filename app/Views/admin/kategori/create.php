<?= $this->extend('layout/admin_template/index'); ?>

<?= $this->section('content'); ?>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('admin/kategori/store') ?>" method="post"
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
                        value="<?= old('nama_kategori') ?>" required>
                    <div class="invalid-feedback">
                        <?= session('errors.nama_kategori') ?: 'Nama kategori wajib diisi.' ?>
                    </div>
                </div>

                <!-- Nama Kategori -->
                <div class="col-md-12">
                    <label class="form-label">Kode Kategori *</label>
                    <input type="text"
                        name="kode_kategori"
                        class="form-control <?= session('errors.kode_kategori') ? 'is-invalid' : '' ?>"
                        placeholder="Masukkan nama kategori"
                        value="<?= old('kode_kategori') ?>" required>
                    <div class="invalid-feedback">
                        <?= session('errors.kode_kategori') ?: 'kode kategori wajib diisi.' ?>
                    </div>
                </div>

                <!-- Deskripsi (opsional) -->
                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi"
                        class="form-control <?= session('errors.deskripsi') ? 'is-invalid' : '' ?>"
                        rows="4"
                        placeholder="Deskripsi kategori (opsional)"><?= old('deskripsi') ?></textarea>
                    <?php if (session('errors.deskripsi')): ?>
                        <div class="invalid-feedback"><?= session('errors.deskripsi') ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-success me-2">Simpan</button>
                <a href="<?= base_url('admin/kategori') ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

<!-- JS validasi Bootstrap 5 (tanpa jQuery) -->
<script>
    (() => {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', e => {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>

<?= $this->endSection(); ?>