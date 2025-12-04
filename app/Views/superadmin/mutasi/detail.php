<?= $this->extend('layout/superadmin_template/index'); ?>
<?= $this->section('content'); ?>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 fw-bold text-primary">Detail Mutasi Aset</h4>
            <a href="<?= site_url('superadmin/mutasi'); ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-sm-5 text-muted">Kode Mutasi</dt>
                    <dd class="col-sm-7"><?= esc($header['kode_mutasi']); ?></dd>

                    <dt class="col-sm-5 text-muted">Tanggal</dt>
                    <dd class="col-sm-7"><?= date('d M Y H:i', strtotime($header['tanggal_mutasi'])); ?></dd>

                    <dt class="col-sm-5 text-muted">Cabang Asal</dt>
                    <dd class="col-sm-7"><?= esc($header['cabang_asal']); ?></dd>

                    <dt class="col-sm-5 text-muted">Cabang Tujuan</dt>
                    <dd class="col-sm-7"><?= esc($header['cabang_tujuan']); ?></dd>
                </dl>
            </div>
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-sm-5 text-muted">Status</dt>
                    <dd class="col-sm-7">
                        <span class="badge <?= $header['status'] === 'selesai' ? 'bg-success' : 'bg-warning'; ?>">
                            <?= ucfirst($header['status']); ?>
                        </span>
                    </dd>
                    <dt class="col-sm-5 text-muted">Catatan</dt>
                    <dd class="col-sm-7"><?= esc($header['catatan']); ?></dd>
                </dl>
            </div>
        </div>

        <h6 class="fw-semibold mb-3 text-secondary">Detail Aset</h6>
        <div class="table-responsive">
            <table class="table table-borderless">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">No</th>
                        <th>Kode Aset</th>
                        <th>Nama Master</th>
                        <th class="text-center">Qty Mutasi</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($details as $d): ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td class="fw-semibold"><?= esc($d['kode_aset']); ?></td>
                            <td><?= esc($d['nama_master']); ?></td>
                            <td class="text-center"><?= (int)$d['qty']; ?></td>
                            <td><?= esc($d['keterangan']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($details)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">Tidak ada detail mutasi.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>