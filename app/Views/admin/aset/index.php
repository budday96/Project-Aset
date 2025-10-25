<?= $this->extend('layout/admin_template/index'); ?>

<?= $this->section('content'); ?>

<style>
    .aset-thumbnail {
        width: 45px;
        height: 45px;
        border-radius: 6px;
        object-fit: cover;
        background: #f6f8fb;
        display: block;
    }

    .aset-thumbnail.placeholder {
        color: #b0b3bb;
        font-size: 24px;
    }
</style>

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
                <a href="<?= base_url('/admin/aset'); ?>" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Refresh">
                    <i class="bi bi-arrow-clockwise fs-5"></i>
                </a>
                <a class="btn btn-warning btn-sm fw-semibold d-flex align-items-center" href="<?= base_url('admin/aset/create') ?>">
                    <i class="bi bi-plus-circle me-1 fs-5"></i>
                    <span class="d-none d-sm-inline">Tambah Aset</span>
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
                            <th>Gambar</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Kondisi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($asets as $i => $aset): ?>
                            <tr>
                                <td class="align-middle">
                                    <div class="checkbox d-inline-block">
                                        <?php $checkboxId = 'checkbox_' . $aset['id_aset']; ?>
                                        <input type="checkbox"
                                            class="checkbox-input"
                                            id="<?= $checkboxId ?>"
                                            name="selected[]"
                                            value="<?= $aset['id_aset'] ?>">
                                        <label for="<?= $checkboxId ?>" class="mb-0"></label>
                                    </div>
                                </td>
                                <td style="vertical-align: middle;">
                                    <?php if ($aset['gambar']): ?>
                                        <img src="<?= base_url('uploads/aset/' . $aset['gambar']) ?>"
                                            alt="Gambar Aset"
                                            class="aset-thumbnail">
                                    <?php else: ?>
                                        <div class="aset-thumbnail placeholder d-flex align-items-center justify-content-center">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td class="align-middle"><?= esc($aset['nama_aset']) ?></td>
                                <td class="align-middle"><?= esc($aset['nama_kategori']) ?></td>
                                <td class="align-middle">
                                    <span class="badge bg-<?=
                                                            $aset['status'] == 'Digunakan' ? 'primary' : (
                                                                $aset['status'] == 'Tidak Digunakan' ? 'secondary' : (
                                                                    $aset['status'] == 'Hilang' ? 'danger' : 'light'
                                                                )
                                                            )
                                                            ?>">
                                        <?= esc($aset['status']) ?>
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <span class="badge bg-<?=
                                                            $aset['kondisi'] == 'Baik' ? 'success' : (
                                                                $aset['kondisi'] == 'Rusak Ringan' ? 'warning' : (
                                                                    $aset['kondisi'] == 'Rusak Berat' ? 'danger' : 'secondary'
                                                                )
                                                            )
                                                            ?>">
                                        <?= esc($aset['kondisi']) ?>
                                    </span>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="d-flex justify-content-center align-items-center list-action gap-2">

                                        <!-- Tombol View -->
                                        <a href="<?= base_url('admin/aset/detail/' . $aset['id_aset']) ?>"
                                            class="btn btn-outline-warning btn-sm"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <!-- Tombol Edit -->
                                        <a href="<?= base_url('admin/aset/edit/' . $aset['id_aset']) ?>"
                                            class="btn btn-outline-warning btn-sm"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <!-- Tombol Delete -->
                                        <form action="<?= base_url('admin/aset/delete/' . $aset['id_aset']) ?>"
                                            method="post"
                                            class="d-inline"
                                            onsubmit="return confirm('Hapus aset ini?')">
                                            <?= csrf_field() ?>
                                            <button type="submit"
                                                class="btn btn-outline-warning btn-sm"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="Delete">
                                                <i class="bi bi-trash3"></i>
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