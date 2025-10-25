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
            <h4 class="mb-2 mb-md-0">List <?= $title; ?></h4>
            <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-warning btn-sm fw-semibold d-flex align-items-center" href="<?= base_url('superadmin/atribut/' . $sub['id_subkategori'] . '/create') ?>">
                    <i class="bi bi-plus-circle me-1 fs-5"></i>
                    <span class="d-none d-sm-inline">Tambah Atribut</span>
                </a>
            </div>
        </div>
        <div class="card-body px-0">
            <div class="table-responsive rounded mb-3">
                <table id="example" class="table table-hover">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>No</th>
                            <th>Nama</th>
                            <th>Tipe</th>
                            <th>Wajib</th>
                            <th>Satuan</th>
                            <th style="width:160px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($atributs as $a): ?>
                            <tr>
                                <td><?= (int)$a['urutan'] ?></td>
                                <td><?= esc($a['nama_atribut']) ?></td>
                                <td><?= esc($a['tipe_input']) ?></td>
                                <td><?= $a['is_required'] ? 'Ya' : 'Tidak' ?></td>
                                <td><?= esc($a['satuan']) ?></td>
                                <td>
                                    <a class="btn btn-warning btn-sm" href="<?= base_url('superadmin/atribut/edit/' . $a['id_atribut']) ?>">Edit</a>
                                    <form action="<?= base_url('superadmin/atribut/delete/' . $a['id_atribut']) ?>" method="post" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus atribut?')">Hapus</button>
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