<?= $this->extend('layout/admin_template/index'); ?>

<?= $this->section('content'); ?>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('admin/aset/store') ?>" method="post" enctype="multipart/form-data"
            class="needs-validation" novalidate>
            <?= csrf_field() ?>

            <div class="row g-3">
                <!-- Nama Aset -->
                <div class="col-12">
                    <label class="form-label">Nama Aset *</label>
                    <input type="text"
                        name="nama_aset"
                        class="form-control <?= session('errors.nama_aset') ? 'is-invalid' : '' ?>"
                        placeholder="Masukkan Nama Aset"
                        value="<?= old('nama_aset') ?>" required>
                    <div class="invalid-feedback">
                        <?= session('errors.nama_aset') ?: 'Nama aset wajib diisi.' ?>
                    </div>
                </div>

                <!-- Kategori -->
                <div class="col-md-6">
                    <label class="form-label">Kategori *</label>
                    <select name="id_kategori"
                        class="form-select <?= session('errors.id_kategori') ? 'is-invalid' : '' ?>"
                        required>
                        <option value="" disabled <?= old('id_kategori') ? '' : 'selected' ?>>-- Pilih Kategori --</option>
                        <?php foreach ($kategoris as $k): ?>
                            <option value="<?= $k['id_kategori'] ?>"
                                <?= old('id_kategori') == $k['id_kategori'] ? 'selected' : '' ?>>
                                <?= esc($k['nama_kategori']) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                    <div class="invalid-feedback">
                        <?= session('errors.id_kategori') ?: 'Silakan pilih kategori.' ?>
                    </div>
                </div>

                <!-- Tahun Perolehan -->
                <div class="col-md-6">
                    <label class="form-label">Tahun Perolehan *</label>
                    <input type="number"
                        name="tahun_perolehan"
                        class="form-control <?= session('errors.tahun_perolehan') ? 'is-invalid' : '' ?>"
                        placeholder="Contoh: 2025"
                        value="<?= old('tahun_perolehan') ?>" required>
                    <div class="invalid-feedback">
                        <?= session('errors.tahun_perolehan') ?: 'Tahun perolehan wajib diisi.' ?>
                    </div>
                </div>

                <!-- Kondisi -->
                <div class="col-md-6">
                    <label class="form-label">Kondisi *</label>
                    <select name="kondisi"
                        class="form-select <?= session('errors.kondisi') ? 'is-invalid' : '' ?>"
                        required>
                        <option value="" disabled <?= old('kondisi') ? '' : 'selected' ?>>-- Pilih Kondisi --</option>
                        <option value="Baik" <?= old('kondisi') == 'Baik' ? 'selected' : '' ?>>Baik</option>
                        <option value="Rusak Ringan" <?= old('kondisi') == 'Rusak Ringan' ? 'selected' : '' ?>>Rusak Ringan</option>
                        <option value="Rusak Berat" <?= old('kondisi') == 'Rusak Berat' ? 'selected' : '' ?>>Rusak Berat</option>
                    </select>
                    <div class="invalid-feedback">
                        <?= session('errors.kondisi') ?: 'Silakan pilih kondisi.' ?>
                    </div>
                </div>

                <!-- Status -->
                <div class="col-md-6">
                    <label class="form-label">Status *</label>
                    <select name="status"
                        class="form-select <?= session('errors.status') ? 'is-invalid' : '' ?>"
                        required>
                        <option value="" disabled <?= old('status') ? '' : 'selected' ?>>-- Pilih Status --</option>
                        <option value="Digunakan" <?= old('status') == 'Digunakan' ? 'selected' : '' ?>>Digunakan</option>
                        <option value="Tidak Digunakan" <?= old('status') == 'Tidak Digunakan' ? 'selected' : '' ?>>Tidak Digunakan</option>
                        <option value="Hilang" <?= old('status') == 'Hilang' ? 'selected' : '' ?>>Hilang</option>
                    </select>
                    <div class="invalid-feedback">
                        <?= session('errors.status') ?: 'Silakan pilih status.' ?>
                    </div>
                </div>

                <!-- Expired (opsional) -->
                <div class="col-md-6">
                    <label class="form-label">Expired/Kadaluarsa (Opsional)</label>
                    <input type="date"
                        name="expired_at"
                        class="form-control"
                        value="<?= old('expired_at') ?>">
                </div>

                <!-- Gambar (opsional) -->
                <div class="col-12">
                    <label class="form-label">Gambar Aset</label>
                    <input type="file"
                        name="gambar"
                        class="form-control <?= session('errors.gambar') ? 'is-invalid' : '' ?>"
                        accept="image/*">
                    <div class="invalid-feedback">
                        <?= session('errors.gambar') ?: 'File gambar tidak valid.' ?>
                    </div>
                </div>

                <!-- Keterangan (opsional) -->
                <div class="col-12">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan"
                        class="form-control"
                        rows="4"
                        placeholder="Deskripsi / detail aset"><?= old('keterangan') ?></textarea>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary me-2">Simpan</button>
                <a href="<?= base_url('admin/aset') ?>" class="btn btn-secondary">Kembali</a>
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