<?= $this->extend('layout/admin_template/index'); ?>

<?= $this->section('content'); ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="col-lg-12">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 px-4 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
            <h4 class="mb-2 mb-md-0">List Kategori</h4>
            <div class="d-flex flex-wrap gap-2">
                <a href="#" class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Export PDF">
                    <i class="bi bi-filetype-pdf fs-5"></i>
                </a>
                <a href="#" class="btn btn-outline-success btn-sm" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Export Excel">
                    <i class="bi bi-filetype-xls fs-5"></i>
                </a>
                <a href="<?= base_url('/admin/kategori'); ?>" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Refresh">
                    <i class="bi bi-arrow-clockwise fs-5"></i>
                </a>
                <a class="btn btn-warning btn-sm fw-semibold d-flex align-items-center" href="<?= base_url('admin/kategori/create') ?>">
                    <i class="bi bi-plus-circle me-1 fs-5"></i>
                    <span class="d-none d-sm-inline">Tambah Kategori</span>
                    <span class="d-inline d-sm-none">Add</span>
                </a>
                <a class="btn btn-dark btn-sm fw-semibold d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#view-notes" href="#">
                    <i class="bi bi-upload me-2"></i>
                    <span class="d-none d-sm-inline">Import Kategori</span>
                    <span class="d-inline d-sm-none">Import</span>
                </a>
            </div>
        </div>
        <div class="card-body px-0">
            <div class="table-responsive rounded mb-3">
                <table id="example" class="table table-hover">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>No</th>
                            <th>Nama Kategori</th>
                            <th>Kode Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kategoris as $i => $kategori): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= esc($kategori['nama_kategori']) ?></td>
                                <td><?= esc($kategori['kode_kategori']) ?></td>
                                <td>
                                    <a href="<?= base_url('admin/kategori/edit/' . $kategori['id_kategori']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="<?= base_url('admin/kategori/delete/' . $kategori['id_kategori']) ?>" method="post" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
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