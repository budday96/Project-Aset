<?= $this->extend('layout/admin_template/index'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid px-0">
    <div class="card shadow-sm mb-4">
        <div class="card-header text-white"
            style="background-color: #fd7e14;">
            <h5 class="mb-0"><?= esc($title ?? 'Detail User') ?></h5>
        </div>

        <div class="card-body">
            <?php if (session('error')): ?>
                <div class="alert alert-danger"><?= esc(session('error')) ?></div>
            <?php endif; ?>

            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <?php
                    $filename = $user->user_image ?? '';
                    $imgPath = $filename ? FCPATH . 'img/' . $filename : '';
                    $showUrl = ($filename && file_exists($imgPath))
                        ? base_url('img/' . $filename)
                        : base_url('img/default.jpg');
                    ?>
                    <img class="img-profile rounded-circle"
                        src="<?= esc($showUrl) ?>"
                        alt="<?= esc($user->full_name) ?>"
                        width="150" height="150">
                </div>

                <div class="col-md-8">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="small text-muted">Nama Lengkap</div>
                            <div class="fw-bold"><?= esc($user->full_name) ?></div>
                        </div>

                        <div class="col-sm-6">
                            <div class="small text-muted">Username</div>
                            <div><code><?= esc($user->username) ?></code></div>
                        </div>

                        <div class="col-sm-6">
                            <div class="small text-muted">Email</div>
                            <div><?= esc($user->email) ?></div>
                        </div>

                        <div class="col-sm-6">
                            <div class="small text-muted">Role</div>
                            <div class="fw-semibold"><?= esc($group_name) ?></div>
                        </div>

                        <div class="col-sm-6">
                            <div class="small text-muted">Cabang</div>
                            <div class="fw-semibold">
                                <?= esc(get_nama_cabang($user->id_cabang)) ?>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="small text-muted">Status</div>
                            <span class="badge bg-<?= $user->active ? 'success' : 'secondary' ?>">
                                <?= $user->active ? 'Aktif' : 'Nonaktif' ?>
                            </span>
                        </div>

                        <div class="col-sm-6">
                            <div class="small text-muted">Terdaftar</div>
                            <div>
                                <?= $user->created_at
                                    ? date('Y-m-d H:i', strtotime($user->created_at))
                                    : '-' ?>
                            </div>
                        </div>

                        <?php if (!empty($user->last_login_at)): ?>
                            <div class="col-sm-6">
                                <div class="small text-muted">Terakhir Login</div>
                                <div>
                                    <?= date('Y-m-d H:i', strtotime($user->last_login_at)) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="<?= base_url('admin/user') ?>"
                    class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>