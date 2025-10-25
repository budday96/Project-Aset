<?= $this->extend('layout/template_user/index'); ?>

<?= $this->section('content'); ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><?= $title ?></h4>
                    <a href="<?= base_url('user/aset') ?>" class="btn btn-light btn-sm">Kembali ke Daftar</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered mb-4">
                        <tr>
                            <th style="width: 30%;">Kode Aset</th>
                            <td><?= esc($aset['kode_aset']) ?></td>
                        </tr>
                        <tr>
                            <th>Barcode</th>
                            <td>
                                <img src="<?= base_url('p/qr/' . $aset['qr_token']) ?>" alt="QR Code Aset" width="200">
                                <br>
                                <a href="<?= base_url('p/qr/' . $aset['qr_token']) ?>" download="qr_aset_<?= esc($aset['kode_aset']) ?>.png" class="btn btn-sm btn-secondary mt-2">
                                    Unduh QR Code
                                </a>
                            </td>

                        </tr>
                        <tr>
                            <th>Nama Aset</th>
                            <td><?= esc($aset['nama_aset']) ?></td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td><?= esc($aset['nama_kategori']) ?></td>
                        </tr>
                        <tr>
                            <th>Cabang</th>
                            <td><?= esc($aset['nama_cabang']) ?></td>
                        </tr>
                        <tr>
                            <th>Tahun Perolehan</th>
                            <td><?= esc($aset['tahun_perolehan']) ?></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
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
                        </tr>
                        <tr>
                            <th>Kondisi</th>
                            <td>
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
                        </tr>

                        <tr>
                            <th>Expired/Kadaluarsa</th>
                            <td>
                                <?= $aset['expired_at'] ? date('d-m-Y', strtotime($aset['expired_at'])) : '-' ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Gambar</th>
                            <td>
                                <?php if (!empty($aset['gambar'])): ?>
                                    <img src="<?= base_url('uploads/aset/' . $aset['gambar']) ?>" class="img-thumbnail" width="200">
                                <?php else: ?>
                                    <span class="text-muted">Tidak ada gambar</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td><?= esc($aset['keterangan']) ?></td>
                        </tr>
                    </table>
                    <div class="d-flex justify-content-start">
                        <a href="<?= base_url('user/aset/edit/' . $aset['id_aset']) ?>" class="btn btn-warning mr-2">Edit</a>
                        <form action="<?= base_url('user/aset/delete/' . $aset['id_aset']) ?>" method="post" onsubmit="return confirm('Hapus aset ini?')">
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