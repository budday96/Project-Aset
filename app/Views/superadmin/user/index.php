<?= $this->extend('layout/superadmin_template/index'); ?>
<?= $this->section('content'); ?>

<?php if ($msg = session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= esc($msg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="col-lg-12">
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
                    <a href="<?= base_url('/superadmin/user'); ?>" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="Refresh">
                        <i class="bi bi-arrow-clockwise fs-5"></i>
                    </a>
                    <a class="btn btn-warning btn-sm fw-semibold d-flex align-items-center" href="<?= base_url('superadmin/user/create') ?>">
                        <i class="bi bi-plus-circle me-1 fs-5"></i>
                        <span class="d-none d-sm-inline">Tambah User</span>
                        <span class="d-inline d-sm-none">Add</span>
                    </a>
                </div>
            </div>
            <div class="card-body px-0">
                <div class="table-responsive rounded mb-3">
                    <table class="table table-hover datatable-myasset">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>
                                    <div class="checkbox d-inline-block">
                                        <input type="checkbox" class="checkbox-input" id="checkbox1">
                                        <label for="checkbox1" class="mb-0"></label>
                                    </div>
                                </th>
                                <th>Nama Lengkap</th>
                                <th>Cabang</th>
                                <th>Status</th>
                                <th>Role</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $i => $user): ?>
                                <tr>
                                    <td>
                                        <div class="checkbox d-inline-block">
                                            <?php $checkboxId = 'checkbox_user_' . $user->id; ?>
                                            <input type="checkbox" class="checkbox-input" id="<?= $checkboxId ?>">
                                            <label for="<?= $checkboxId ?>" class="mb-0"></label>
                                        </div>
                                    </td>
                                    <td><?= esc($user->full_name ?? '-') ?></td>
                                    <td><?= esc($user->nama_cabang ?? '-') ?></td>
                                    <td>
                                        <?php if ($user->active): ?>
                                            <span class="badge rounded-pill bg-success" title="Aktif">
                                                <i class="bi bi-check-circle me-1"></i>Aktif
                                            </span>
                                        <?php else: ?>
                                            <span class="badge rounded-pill bg-danger" title="Nonaktif">
                                                <i class="bi bi-x-circle me-1"></i>Nonaktif
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($user->group_name ?? 'Tidak ada') ?></td>
                                    <td class="text-center align-middle">
                                        <div class="d-flex justify-content-center align-items-center list-action">

                                            <!-- Tombol View -->
                                            <a href="<?= base_url('superadmin/user/detail/' . $user->id) ?>"
                                                class="btn btn-sm"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="View">
                                                <i class="bi bi-eye" style="color: #fd7e14;"></i>
                                            </a>

                                            <!-- Tombol Edit -->
                                            <a href="<?= base_url('superadmin/user/edit/' . $user->id) ?>"
                                                class="btn btn-sm"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="Edit">
                                                <i class="bi bi-pen" style="color: #fd7e14;"></i>
                                            </a>

                                            <!-- Toggle Aktif/Nonaktif -->
                                            <form action="<?= base_url('superadmin/user/toggle/' . $user->id) ?>"
                                                method="post"
                                                class="d-inline"
                                                <?= csrf_field() ?>
                                                <button type="submit"
                                                class="btn btn-sm"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="<?= $user->active ? 'Nonaktifkan' : 'Aktifkan' ?>">
                                                <i class="bi <?= $user->active ? 'bi-toggle-on' : 'bi-toggle-off' ?>" style="color: #fd7e14;"></i>
                                                </button>
                                            </form>

                                            <!-- Tombol Delete -->
                                            <form action="<?= base_url('superadmin/user/delete/' . $user->id) ?>"
                                                method="post"
                                                class="d-inline"
                                                onsubmit="return confirm('Hapus aset ini?')">
                                                <?= csrf_field() ?>
                                                <button type="submit"
                                                    class="btn btn-sm"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    title="Delete">
                                                    <i class="bi bi-trash3" style="color: #fd7e14;"></i>
                                                </button>
                                            </form>
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
</div>

<?= $this->endSection(); ?>