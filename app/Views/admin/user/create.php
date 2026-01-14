<?= $this->extend('layout/admin_template/index'); ?>
<?= $this->section('content'); ?>

<h4 class="fw-bold mb-4"><?= esc($title) ?></h4>

<?php if (session('errors')): ?>
    <div class="alert alert-danger">
        <ul>
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
            <?= csrf_field() ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" name="full_name"
                        class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Username *</label>
                    <input type="text" name="username"
                        class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email"
                        class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password *</label>
                    <input type="password" name="password"
                        class="form-control" required>
                </div>
            </div>

            <div class="mt-3">
                <button class="btn btn-primary">Simpan</button>
                <a href="<?= base_url('admin/user') ?>"
                    class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>