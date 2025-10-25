<?= $this->extend('layout/superadmin_template/index'); ?>
<?= $this->section('content'); ?>

<h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

<?php if (session('errors')): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach (session('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card shadow">
    <div class="card-body">
        <form action="<?= base_url('superadmin/user/update/' . $user->id) ?>" method="post"
            class="needs-validation" novalidate>
            <?= csrf_field() ?>

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

                <!-- Role -->
                <?php if (empty($userGroup)): ?>
                    <div class="col-md-6">
                        <label class="form-label">Role *</label>
                        <select name="role"
                            class="form-select <?= session('errors.role') ? 'is-invalid' : '' ?>"
                            required>
                            <option value="" disabled <?= old('role') ? '' : 'selected' ?>>-- Pilih Role --</option>
                            <?php foreach ($groups as $group): ?>
                                <option value="<?= $group->id ?>"
                                    <?= old('role') == $group->id ? 'selected' : '' ?>>
                                    <?= esc($group->description) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">
                            <?= session('errors.role') ?: 'Silakan pilih role.' ?>
                        </div>
                    </div>
                <?php else: ?>
                    <?php
                    // Cari deskripsi role saat ini
                    $roleDesc = '-';
                    foreach ($groups as $g) {
                        if ((string)$g->id === (string)$userGroup) {
                            $roleDesc = $g->description;
                            break;
                        }
                    }
                    ?>
                    <div class="col-md-6">
                        <label class="form-label">Role</label>
                        <input type="text" class="form-control" value="<?= esc($roleDesc) ?>" readonly>
                        <input type="hidden" name="role" value="<?= $userGroup ?>">
                    </div>
                <?php endif; ?>

                <!-- Cabang -->
                <?php if (empty($user->id_cabang)): ?>
                    <div class="col-md-6">
                        <label class="form-label">Pilih Cabang *</label>
                        <select name="id_cabang"
                            class="form-select <?= session('errors.id_cabang') ? 'is-invalid' : '' ?>"
                            required>
                            <option value="" disabled <?= old('id_cabang') ? '' : 'selected' ?>>-- Pilih Cabang --</option>
                            <?php foreach ($cabang as $c): ?>
                                <option value="<?= $c['id_cabang'] ?>"
                                    <?= old('id_cabang') == $c['id_cabang'] ? 'selected' : '' ?>>
                                    <?= esc($c['nama_cabang']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">
                            <?= session('errors.id_cabang') ?: 'Silakan pilih cabang.' ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-md-6">
                        <label class="form-label">Cabang</label>
                        <input type="text" class="form-control"
                            value="<?= esc(get_nama_cabang($user->id_cabang)) ?>" readonly>
                        <input type="hidden" name="id_cabang" value="<?= $user->id_cabang ?>">
                    </div>
                <?php endif; ?>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary me-2">Perbarui</button>
                <a href="<?= base_url('superadmin/user') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>