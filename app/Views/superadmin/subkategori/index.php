<?= $this->extend('layout/superadmin_template/index') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>
<div class="card">
    <div class="card-body">
        <div class="card-header bg-white py-3 px-4 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
            <h4 class="mb-2 mb-md-0 fw-bold">List Subkategori</h4>
            <div class="d-flex flex-wrap gap-2">
                <a href="#" class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Export PDF">
                    <i class="bi bi-filetype-pdf fs-5"></i>
                </a>
                <a href="#" class="btn btn-outline-success btn-sm" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Export Excel">
                    <i class="bi bi-filetype-xls fs-5"></i>
                </a>
                <a href="<?= base_url('superadmin/subkategori'); ?>" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Refresh">
                    <i class="bi bi-arrow-clockwise fs-5"></i>
                </a>
                <a class="btn btn-warning btn-sm fw-semibold d-flex align-items-center" href="<?= base_url('superadmin/subkategori/create') ?>">
                    <i class="bi bi-plus-circle me-1 fs-5"></i>
                    <span class="d-none d-sm-inline">Tambah Subkategori</span>
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
                            <th>Kategori</th>
                            <th>Subkategori</th>
                            <th>Atribut</th>
                            <th style="width:160px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($subkategoris as $s): ?>
                            <tr>
                                <td><?= esc($s['nama_kategori']) ?></td>
                                <td><?= esc($s['nama_subkategori']) ?></td>
                                <td><a class="btn btn-outline-secondary btn-sm" href="<?= base_url('superadmin/atribut/' . $s['id_subkategori']) ?>">Kelola</a></td>
                                <td>
                                    <a class="btn btn-warning btn-sm" href="<?= base_url('superadmin/subkategori/' . $s['id_subkategori'] . '/edit') ?>">Edit</a>
                                    <form action="<?= base_url('superadmin/subkategori/' . $s['id_subkategori'] . '/delete') ?>" method="post" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus subkategori?')">Hapus</button>
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

<?= $this->endSection() ?>