<?= $this->extend('layout/admin_template/index'); ?>
<?= $this->section('content'); ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="card-header bg-white py-3 px-4 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
            <h4 class="mb-2 mb-md-0 fw-bold"><?= $title; ?></h4>
            <div class="d-flex flex-wrap gap-2">
                <a href="#" class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Export PDF">
                    <i class="bi bi-filetype-pdf fs-5"></i>
                </a>
                <a href="#" class="btn btn-outline-success btn-sm" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Export Excel">
                    <i class="bi bi-filetype-xls fs-5"></i>
                </a>
                <a href="<?= base_url('/admin/user'); ?>" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Refresh">
                    <i class="bi bi-arrow-clockwise fs-5"></i>
                </a>
                <a class="btn btn-warning btn-sm fw-semibold d-flex align-items-center" href="<?= base_url('admin/user/create') ?>">
                    <i class="bi bi-plus-circle me-1 fs-5"></i>
                    <span class="d-none d-sm-inline">Tambah User</span>
                    <span class="d-inline d-sm-none">Add</span>
                </a>
                <a class="btn btn-dark btn-sm fw-semibold d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#view-notes" href="#">
                    <i class="bi bi-upload me-2"></i>
                    <span class="d-none d-sm-inline">Import Product</span>
                    <span class="d-inline d-sm-none">Import</span>
                </a>
            </div>
        </div>
        <div class="card-body px-0">
            <div class="table-responsive rounded mb-3">
                <table id="example" class="table table-hover">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>
                                <div class="checkbox d-inline-block">
                                    <input type="checkbox" class="checkbox-input" id="checkbox1">
                                    <label for="checkbox1" class="mb-0"></label>
                                </div>
                            </th>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Status</th>
                            <th>Role</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $i => $user): ?>
                            <?php
                            // Proteksi aksi di UI
                            $isSelf         = (int)$user->id === (int)user()->id;
                            $isProtected    = in_array($user->group_name ?? '', ['admin', 'superadmin'], true);
                            $canManage      = !$isSelf && !$isProtected; // cabang sudah difilter di controller
                            $checkboxId     = 'checkbox_user_' . $user->id;
                            ?>
                            <tr>
                                <td>
                                    <div class="checkbox d-inline-block">
                                        <input type="checkbox" class="checkbox-input" id="<?= esc($checkboxId) ?>">
                                        <label for="<?= esc($checkboxId) ?>" class="mb-0"></label>
                                    </div>
                                </td>
                                <td><?= esc($user->full_name ?? '-') ?></td>
                                <td><?= esc($user->username ?? '-') ?></td>
                                <td>
                                    <span class="badge badge-<?= $user->active ? 'success' : 'secondary' ?>">
                                        <?= $user->active ? 'Aktif' : 'Nonaktif' ?>
                                    </span>
                                </td>
                                <td><?= esc($user->group_name ?? 'Tidak ada') ?></td>
                                <td class="text-center align-middle">
                                    <div class="d-flex justify-content-center align-items-center list-action gap-2">
                                        <!-- View: selalu boleh -->
                                        <a href="<?= base_url('admin/user/detail/' . $user->id) ?>"
                                            class="btn btn-outline-warning btn-sm"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Lihat detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <?php if ($canManage): ?>
                                            <!-- Edit -->
                                            <a href="<?= base_url('admin/user/edit/' . $user->id) ?>"
                                                class="btn btn-outline-warning btn-sm"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <!-- Toggle Aktif/Nonaktif (POST) -->
                                            <form action="<?= base_url('admin/user/toggle/' . $user->id) ?>"
                                                method="post"
                                                class="d-inline">
                                                <?= csrf_field() ?>
                                                <button type="submit"
                                                    class="btn btn-outline-warning btn-sm"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    title="<?= $user->active ? 'Aktifkan' : 'Nonaktifkan' ?>"
                                                    style="cursor:pointer">
                                                    <?php if ($user->active): ?>
                                                        <i class="bi bi-person-dash text-danger"></i>
                                                    <?php else: ?>
                                                        <i class="bi bi-person-check text-success"></i>
                                                    <?php endif; ?>
                                                </button>
                                            </form>

                                            <!-- Delete (POST) -->
                                            <form action="<?= base_url('admin/user/delete/' . $user->id) ?>"
                                                method="post"
                                                class="d-inline"
                                                onsubmit="return confirm('Hapus user ini?')">
                                                <?= csrf_field() ?>
                                                <button type="submit"
                                                    class="btn btn-outline-warning btn-sm"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    title="Hapus"
                                                    style="cursor:pointer">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <!-- Tampilkan indikator proteksi agar jelas di UI -->
                                            <span class="badge badge-light" data-toggle="tooltip" title="<?= $isSelf ? 'Akun Anda sendiri' : 'Akun terproteksi' ?>">—</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>