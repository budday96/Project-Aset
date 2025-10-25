<?= $this->extend('layout/template_user/index'); ?>

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
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
<?php endif; ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
    <a href="<?= base_url('user/aset/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Aset
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive rounded mb-3">
            <table class="data-tables table mb-0 tbl-server-info" id="dataTable" width="100%" cellspacing="0">
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
                                <span class="badge badge-<?=
                                                            $aset['status'] == 'Digunakan' ? 'success' : (
                                                                $aset['status'] == 'Tidak Digunakan' ? 'secondary' : (
                                                                    $aset['status'] == 'Hilang' ? 'danger' : 'light'
                                                                )
                                                            )
                                                            ?>">
                                    <?= esc($aset['status']) ?>
                                </span>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-<?=
                                                            $aset['kondisi'] == 'Baik' ? 'info' : (
                                                                $aset['kondisi'] == 'Rusak Ringan' ? 'warning' : (
                                                                    $aset['kondisi'] == 'Rusak Berat' ? 'danger' : 'secondary'
                                                                )
                                                            )
                                                            ?>">
                                    <?= esc($aset['kondisi']) ?>
                                </span>
                            </td>

                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center align-items-center list-action">
                                    <!-- Tombol View -->
                                    <a href="<?= base_url('user/aset/detail/' . $aset['id_aset']) ?>"
                                        class="badge badge-info mr-2"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="View">
                                        <i class="ri-eye-line mr-0"></i>
                                    </a>

                                    <!-- Tombol Edit -->
                                    <a href="<?= base_url('user/aset/edit/' . $aset['id_aset']) ?>"
                                        class="badge badge-success mr-2"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="Edit">
                                        <i class="ri-pencil-line mr-0"></i>
                                    </a>

                                    <!-- Tombol Delete -->
                                    <form action="<?= base_url('user/aset/delete/' . $aset['id_aset']) ?>"
                                        method="post"
                                        class="d-inline"
                                        onsubmit="return confirm('Hapus aset ini?')">
                                        <?= csrf_field() ?>
                                        <button type="submit"
                                            class="badge badge-danger border-0 mr-2"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Delete"
                                            style="cursor:pointer">
                                            <i class="ri-delete-bin-line mr-0"></i>
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

<?= $this->endSection(); ?>