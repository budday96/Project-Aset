<?= $this->extend('layout/admin_template/index'); ?>
<?= $this->section('content'); ?>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="text-center mb-4">
            <?php
            $avatar = $user->user_image ?: 'default.jpg';
            ?>
            <img src="<?= base_url('/img/' . $avatar); ?>"
                alt="<?= esc($user->username); ?>"
                class="rounded-circle shadow mb-3 border border-3 border-primary"
                width="150" height="150"
                style="object-fit: cover;">

            <h4 class="fw-bold mb-0"><?= esc($user->full_name); ?></h4>
            <p class="text-muted mb-2">@<?= esc($user->username); ?></p>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-7">
                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-envelope me-2"></i><strong>Email:</strong></span>
                        <span><?= esc($user->email); ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-person-check me-2"></i><strong>Status:</strong></span>
                        <?php if ($user->active): ?>
                            <span class="badge bg-warning text-dark">Aktif</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Nonaktif</span>
                        <?php endif; ?>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-shield-lock me-2"></i><strong>Role:</strong></span>
                        <?php
                        $badgeClass = 'secondary';
                        if ($group_name == 'admin') {
                            $badgeClass = 'danger';
                        } elseif ($group_name == 'admin') {
                            $badgeClass = 'success';
                        } elseif ($group_name == 'user') {
                            $badgeClass = 'primary';
                        }
                        ?>
                        <span class="badge bg-<?= $badgeClass; ?>">
                            <?= esc($group_name); ?>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-footer bg-light border-top">
        <h5 class="fw-bold mb-3">Ubah Profil</h5>
        <form action="<?= base_url('admin/profile/update') ?>" method="post" enctype="multipart/form-data" class="row g-3">
            <?= csrf_field() ?>

            <div class="col-md-6">
                <label for="full_name" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control <?php if (session('errors.full_name')) : ?>is-invalid<?php endif ?>"
                    name="full_name" id="full_name" value="<?= old('full_name', $user->full_name); ?>">
                <?php if (session('errors.full_name')) : ?>
                    <div class="invalid-feedback"><?= session('errors.full_name') ?></div>
                <?php endif ?>
            </div>

            <div class="col-md-6">
                <label for="user_image" class="form-label">Gambar Baru</label>
                <input type="file" class="form-control <?php if (session('errors.user_image')) : ?>is-invalid<?php endif ?>"
                    name="user_image" id="user_image">
                <?php if (session('errors.user_image')) : ?>
                    <div class="invalid-feedback"><?= session('errors.user_image') ?></div>
                <?php endif ?>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-success w-100"><i class="bi bi-save me-2"></i>Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>