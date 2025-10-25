<?= $this->extend('layout/admin_template/index'); ?>
<?= $this->section('content'); ?>

<h1 class="h3 mb-4 text-gray-800"><?= esc($title) ?></h1>

<?php if (session('errors')): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach (session('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card shadow">
    <div class="card-body">
        <form action="<?= base_url('admin/user/update/' . $user->id) ?>" method="post"
            class="needs-validation" novalidate>
            <?= csrf_field() ?>

            <?php
            // Siapkan tampilan read-only untuk Role & Cabang (fallback aman)
            $roleDisplay   = $group_name   ?? ($user->group_name ?? 'user');
            $cabangDisplay = $cabang_name  ?? (function_exists('get_nama_cabang')
                ? get_nama_cabang($user->id_cabang)
                : ($user->nama_cabang ?? '-'));
            ?>

            <div class="row g-3">
                <!-- Nama Lengkap -->
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text"
                        name="full_name"
                        class="form-control <?= session('errors.full_name') ? 'is-invalid' : '' ?>"
                        placeholder="Masukkan nama lengkap"
                        value="<?= old('full_name', $user->full_name ?? '') ?>" required>
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
                        value="<?= old('username', $user->username ?? '') ?>" required>
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
                        value="<?= old('email', $user->email ?? '') ?>" required>
                    <div class="invalid-feedback">
                        <?= session('errors.email') ?: 'Email wajib diisi dan harus valid.' ?>
                    </div>
                </div>

                <!-- Password baru (opsional) -->
                <div class="col-md-6">
                    <label class="form-label">Password Baru (kosongkan jika tidak diubah)</label>
                    <input type="password"
                        name="password"
                        class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>"
                        placeholder="Masukkan password baru (opsional)"
                        autocomplete="new-password">
                    <div class="invalid-feedback">
                        <?= session('errors.password') ?: 'Password tidak valid.' ?>
                    </div>
                </div>

                <!-- Role (read-only) -->
                <div class="col-md-6">
                    <label class="form-label">Role</label>
                    <input type="text" class="form-control" value="<?= esc($roleDisplay) ?>" readonly>
                </div>

                <!-- Cabang (read-only) -->
                <div class="col-md-6">
                    <label class="form-label">Cabang</label>
                    <input type="text" class="form-control" value="<?= esc($cabangDisplay) ?>" readonly>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary me-2">Perbarui</button>
                <a href="<?= base_url('admin/user') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>