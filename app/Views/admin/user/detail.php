<?= $this->extend('layout/admin_template/index'); ?>
<?= $this->section('content'); ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><?= $title ?></h4>
                    <a href="<?= base_url('admin/user') ?>" class="btn btn-light btn-sm">Kembali</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered mb-4">
                        <tr>
                            <th style="width: 30%;">Nama Lengkap</th>
                            <td><?= esc($user->full_name) ?></td>
                        </tr>
                        <tr>
                            <th>Username</th>
                            <td><?= esc($user->username) ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?= esc($user->email) ?></td>
                        </tr>
                        <tr>
                            <th>Role</th>
                            <td><?= esc($group_name ?? '-') ?></td>
                        </tr>
                        <tr>
                            <th>Cabang</th>
                            <td><?= esc(get_nama_cabang($user->id_cabang)) ?></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge badge-<?= $user->active ? 'success' : 'secondary' ?>">
                                    <?= $user->active ? 'Aktif' : 'Nonaktif' ?>
                                </span>
                            </td>
                        </tr>
                    </table>

                    <div class="d-flex justify-content-start">
                        <a href="<?= base_url('admin/user/edit/' . $user->id) ?>" class="btn btn-warning mr-2">Edit</a>
                        <form action="<?= base_url('admin/user/delete/' . $user->id) ?>" method="post" onsubmit="return confirm('Yakin hapus user ini?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>