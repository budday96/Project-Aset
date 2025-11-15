<?= $this->extend('layout/superadmin_template/index') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Daftar Kelompok Harta</h2>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"> <?= session()->getFlashdata('success') ?> </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"> <?= session()->getFlashdata('error') ?> </div>
<?php endif; ?>

<a href="<?= site_url('superadmin/kelompokharta/create') ?>" class="btn btn-primary mb-3">Tambah Kelompok Harta</a>

<table class="table table-hover datatable-myasset">
    <thead>
        <tr>
            <th>#</th>
            <th>Kode</th>
            <th>Nama Kelompok</th>
            <th>Umur (tahun)</th>
            <th>Tarif (%)</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $i => $row): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= esc($row['kode_kelompok']) ?></td>
                <td><?= esc($row['nama_kelompok']) ?></td>
                <td><?= esc($row['umur_tahun']) ?></td>
                <td><?= esc($row['tarif_persen_th']) ?>%</td>
                <td>
                    <?= $row['is_active'] ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Nonaktif</span>' ?>
                </td>
                <td>
                    <a href="<?= site_url('superadmin/kelompokharta/edit/' . $row['id_kelompok_harta']) ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="<?= site_url('superadmin/kelompokharta/delete/' . $row['id_kelompok_harta']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Nonaktifkan kelompok ini?')">Nonaktifkan</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>