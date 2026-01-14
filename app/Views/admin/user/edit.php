<?= $this->extend('layout/admin_template/index'); ?>
<?= $this->section('content'); ?>

<h4 class="fw-bold mb-4"><?= esc($title) ?></h4>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('admin/user/update/' . $user->id) ?>"
            method="post">
            <?= csrf_field() ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" name="full_name"
                        class="form-control"
                        value="<?= esc($user->full_name) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Username *</label>
                    <input type="text" name="username"
                        class="form-control"
                        value="<?= esc($user->username) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email"
                        class="form-control"
                        value="<?= esc($user->email) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password (opsional)</label>
                    <input type="password" name="password"
                        class="form-control">
                </div>
            </div>

            <div class="mt-3">
                <button class="btn btn-primary">Update</button>
                <a href="<?= base_url('admin/user') ?>"
                    class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>