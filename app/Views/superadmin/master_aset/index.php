<?= $this->extend('layout/superadmin_template/index'); ?>
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
            <h4 class="mb-2 mb-md-0">List Master Aset</h4>
            <div class="d-flex flex-wrap gap-2">
                <a href="#" class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Export PDF">
                    <i class="bi bi-filetype-pdf fs-5"></i>
                </a>
                <a href="#" class="btn btn-outline-success btn-sm" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Export Excel">
                    <i class="bi bi-filetype-xls fs-5"></i>
                </a>
                <a href="<?= base_url('/superadmin/master-aset'); ?>" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Refresh">
                    <i class="bi bi-arrow-clockwise fs-5"></i>
                </a>
                <a class="btn btn-warning btn-sm fw-semibold d-flex align-items-center" href="<?= base_url('superadmin/master-aset/create') ?>">
                    <i class="bi bi-plus-circle me-1 fs-5"></i>
                    <span class="d-none d-sm-inline">Tambah Master Aset</span>
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
                <table class="table table-hover datatable-myasset">
                    <thead class="bg-white">
                        <tr class="ligth ligth-data">
                            <th>
                                <div class="checkbox d-inline-block">
                                    <input type="checkbox" class="checkbox-input" id="checkbox1">
                                    <label for="checkbox1" class="mb-0"></label>
                                </div>
                            </th>
                            <th>Nama Master</th>
                            <th>Kategori</th>
                            <th>Subkategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $i => $r): ?>
                            <tr>
                                <td class="align-middle">
                                    <div class="checkbox d-inline-block">
                                        <?php $checkboxId = 'checkbox_' . $r['id_master_aset']; ?>
                                        <input type="checkbox"
                                            class="checkbox-input"
                                            id="<?= $checkboxId ?>"
                                            name="selected[]"
                                            value="<?= $r['id_master_aset'] ?>">
                                        <label for="<?= $checkboxId ?>" class="mb-0"></label>
                                    </div>
                                </td>
                                <td class="align-middle"><?= esc($r['nama_master']) ?></td>
                                <td class="align-middle"><?= esc($r['nama_kategori'] ?? '-') ?></td>
                                <td class="align-middle"><?= esc($r['nama_subkategori'] ?? '-') ?></td>
                                <td class="text-center align-middle">
                                    <div class="d-flex justify-content-center align-items-center list-action">

                                        <!-- Tombol View -->
                                        <a href="<?= base_url('superadmin/master-aset/detail/' . $r['id_master_aset']) ?>"
                                            class="btn btn-sm"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="View">
                                            <i class="bi bi-eye" style="color: #fd7e14;"></i>
                                        </a>

                                        <!-- Tombol Edit -->
                                        <a href="<?= base_url('superadmin/master-aset/edit/' . $r['id_master_aset']) ?>"
                                            class="btn btn-sm"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Edit">
                                            <i class="bi bi-pen" style="color: #fd7e14;"></i>
                                        </a>

                                        <!-- Tombol Delete -->
                                        <form action="<?= base_url('superadmin/master-aset/delete/' . $r['id_master_aset']) ?>"
                                            method="post"
                                            class="d-inline"
                                            onsubmit="return confirm('Hapus master aset ini?')">
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
<?= $this->endSection(); ?>