<?= $this->extend('layout/admin_template/index'); ?>
<?= $this->section('content'); ?>

<?php if (session('errors')): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach (session('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('admin/user/store') ?>" method="post"
            class="needs-validation" novalidate>
            <?= csrf_field(); ?>

            <div class="row g-3">
                <!-- Nama Lengkap -->
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text"
                        name="full_name"
                        class="form-control <?= session('errors.full_name') ? 'is-invalid' : '' ?>"
                        placeholder="Masukkan nama lengkap"
                        value="<?= old('full_name') ?>" required>
                    <div class="invalid-feedback">
                        <?= session('errors.full_name') ?: 'Nama lengkap wajib diisi.' ?>
                    </div>
                </div>

                <!-- Username -->
                <div class="col-md-6">
                    <label class="form-label">Username *</label>
                    <input type="text"
                        name="username"
                        class="form-control <?= session('errors.username') ? 'is-invalid' : '' ?>"
                        placeholder="Masukkan username"
                        value="<?= old('username') ?>" required>
                    <div class="invalid-feedback">
                        <?= session('errors.username') ?: 'Username wajib diisi.' ?>
                    </div>
                </div>

                <!-- Email -->
                <div class="col-md-6">
                    <label class="form-label">Alamat Email *</label>
                    <input type="email"
                        name="email"
                        class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>"
                        placeholder="nama@contoh.com"
                        value="<?= old('email') ?>" required>
                    <div class="invalid-feedback">
                        <?= session('errors.email') ?: 'Email wajib diisi dan harus valid.' ?>
                    </div>
                </div>

                <!-- Kata Sandi -->
                <div class="col-md-6">
                    <label class="form-label">Kata Sandi *</label>
                    <input type="password"
                        name="password"
                        class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>"
                        placeholder="Masukkan kata sandi"
                        autocomplete="new-password" required>
                    <div class="invalid-feedback">
                        <?= session('errors.password') ?: 'Kata sandi wajib diisi.' ?>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary me-2">Simpan</button>
                <a href="<?= base_url('admin/user') ?>" class="btn btn-secondary">Batal</a>
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