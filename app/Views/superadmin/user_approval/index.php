<?= $this->extend('layout/superadmin_template/index'); ?>
<?= $this->section('content'); ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="col-lg-12">
            <div class="card-body px-0">
                <div class="table-responsive rounded mb-3">
                    <table class="table table-hover datatable-myasset">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Username</th>
                                <th>Validasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= esc($user->full_name) ?></td>
                                    <td><?= esc($user->email) ?></td>
                                    <td><?= esc($user->username) ?></td>
                                    <td>
                                        <form method="post" action="<?= base_url('superadmin/userapproval/setrole/' . $user->id) ?>">
                                            <?= csrf_field() ?>
                                            <div class="form-row">
                                                <div class="col">
                                                    <select name="role" class="form-control" required>
                                                        <option value="">-- Pilih Role --</option>
                                                        <option value="admin">Admin</option>
                                                        <option value="user">User</option>
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <select name="id_cabang" class="form-control" required>
                                                        <option value="">-- Pilih Cabang --</option>
                                                        <?php foreach ($cabang as $c): ?>
                                                            <option value="<?= $c['id_cabang'] ?>"><?= esc($c['nama_cabang']) ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                                <div class="col-auto">
                                                    <button type="submit" class="btn btn-primary btn-sm">Tetapkan</button>
                                                </div>
                                            </div>
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
</div>


<?= $this->endSection(); ?>