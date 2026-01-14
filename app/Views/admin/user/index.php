<?= $this->extend('layout/admin_template/index'); ?>
<?= $this->section('content'); ?>

<div class="card">
    <div class="card-body">
        <div class="card-header bg-white py-3 px-4">
            <!-- TOP BAR: TITLE + ACTION BUTTONS -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">

                <!-- TITLE -->
                <h4 class="fw-bold mb-3 mb-md-0 text-center text-md-start w-100">
                    List User
                </h4>

                <!-- BUTTON GROUP -->
                <div class="d-flex flex-wrap gap-2 w-100 w-md-auto justify-content-center justify-content-md-end">

                    <!-- EXPORT PDF -->
                    <button id="btn-export-pdf" class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" title="Export PDF">
                        <i class="bi bi-filetype-pdf fs-5"></i>
                    </button>

                    <!-- EXPORT EXCEL -->
                    <button id="btn-export-excel" class="btn btn-outline-success btn-sm" data-bs-toggle="tooltip" title="Export Excel">
                        <i class="bi bi-filetype-xls fs-5"></i>
                    </button>

                    <!-- REFRESH -->
                    <a href="<?= base_url('/admin/user'); ?>"
                        class="btn btn-outline-secondary btn-sm"
                        data-bs-toggle="tooltip" title="Refresh">
                        <i class="bi bi-arrow-clockwise fs-5"></i>
                    </a>

                    <!-- TAMBAH User -->
                    <a class="btn btn-warning btn-sm fw-semibold d-flex align-items-center justify-content-center px-2"
                        href="<?= base_url('admin/user/create') ?>">

                        <i class="bi bi-plus-circle me-1 fs-5"></i>
                        <span class="text-truncate" style="max-width: 90px;">Tambah User</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body px-0">
            <div class="table-responsive rounded mb-3">
                <table class="table table-hover datatable-myasset">
                    <thead class="bg-white text-uppercase">
                        <tr>
                            <th>
                                <div class="checkbox d-inline-block">
                                    <input type="checkbox" class="checkbox-input" id="checkbox1">
                                    <label for="checkbox1" class="mb-0"></label>
                                </div>
                            </th>
                            <th>Nama Lengkap</th>
                            <th>Status</th>
                            <th>Role</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <div class="checkbox d-inline-block">
                                        <?php $checkboxId = 'checkbox_user_' . $user->id; ?>
                                        <input type="checkbox" class="checkbox-input" id="<?= $checkboxId ?>">
                                        <label for="<?= $checkboxId ?>" class="mb-0"></label>
                                    </div>
                                </td>
                                <td><?= esc($user->full_name) ?></td>

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

                                <td><?= esc($user->group_name ?? 'User') ?></td>

                                <td class="text-center">
                                    <a href="<?= base_url('admin/user/detail/' . $user->id) ?>"
                                        class="btn btn-sm"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        title="Detail">
                                        <i class="bi bi-eye" style="color: #fd7e14;"></i>
                                    </a>

                                    <a href="<?= base_url('admin/user/edit/' . $user->id) ?>"
                                        class="btn btn-sm"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        title="Edit">
                                        <i class="bi bi-pen" style="color: #fd7e14;"></i>
                                    </a>

                                    <!-- Toggle Aktif/Nonaktif -->
                                    <form action="<?= base_url('admin/user/toggle/' . $user->id) ?>"
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
                                    <form action="<?= base_url('admin/user/delete/' . $user->id) ?>"
                                        method="post"
                                        class="d-inline"
                                        onsubmit="return confirm('Hapus user ini?')">
                                        <?= csrf_field() ?>
                                        <button type="submit"
                                            class="btn btn-sm"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Delete">
                                            <i class="bi bi-trash3" style="color: #fd7e14;"></i>
                                        </button>
                                    </form>
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