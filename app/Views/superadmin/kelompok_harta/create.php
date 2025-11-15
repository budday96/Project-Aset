<?= $this->extend('layout/superadmin_template/index'); ?>
<?= $this->section('content'); ?>

<!-- Form Kelompok Harta -->
<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-3">Form Tambah Kelompok Harta</h5>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('superadmin/kelompokharta/store') ?>" method="post" class="needs-validation" novalidate>
            <?= csrf_field() ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Kode Kelompok *</label>
                    <input type="text" name="kode_kelompok" class="form-control <?= session('errors.kode_kelompok') ? 'is-invalid' : '' ?>" value="<?= old('kode_kelompok') ?>" required>
                    <div class="invalid-feedback">
                        <?= session('errors.kode_kelompok') ?: 'Kode kelompok wajib diisi.' ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nama Kelompok *</label>
                    <input type="text" name="nama_kelompok" class="form-control <?= session('errors.nama_kelompok') ? 'is-invalid' : '' ?>" value="<?= old('nama_kelompok') ?>" required>
                    <div class="invalid-feedback">
                        <?= session('errors.nama_kelompok') ?: 'Nama kelompok wajib diisi.' ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Umur (tahun) *</label>
                    <input type="number" name="umur_tahun" class="form-control <?= session('errors.umur_tahun') ? 'is-invalid' : '' ?>" value="<?= old('umur_tahun') ?>" required>
                    <div class="invalid-feedback">
                        <?= session('errors.umur_tahun') ?: 'Umur dalam tahun wajib diisi.' ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tarif Penyusutan per Tahun (%) *</label>
                    <input type="number" name="tarif_persen_th" step="0.01" class="form-control <?= session('errors.tarif_persen_th') ? 'is-invalid' : '' ?>" value="<?= old('tarif_persen_th') ?>" required>
                    <div class="invalid-feedback">
                        <?= session('errors.tarif_persen_th') ?: 'Tarif penyusutan per tahun wajib diisi.' ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select <?= session('errors.status') ? 'is-invalid' : '' ?>" required>
                        <option value="Aktif" <?= old('status') == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                        <option value="Nonaktif" <?= old('status') == 'Nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                    <div class="invalid-feedback">
                        <?= session('errors.status') ?: 'Status wajib dipilih.' ?>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?= base_url('superadmin/kelompokharta') ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>