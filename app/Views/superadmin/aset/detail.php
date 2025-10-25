<?= $this->extend('layout/superadmin_template/index'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid px-0">
    <div class="card shadow-sm mb-4">
        <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #fd7e14;">
            <h5 class="mb-0">Detail Aset</h5>
            <div>
                <a href="<?= base_url('superadmin/aset/edit/' . (int)$row['id_aset']) ?>" class="btn btn-light btn-sm me-2">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="<?= base_url('superadmin/aset') ?>" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if (session('error')): ?>
                <div class="alert alert-danger"><?= esc(session('error')) ?></div>
            <?php endif; ?>
            <?php if (session('success')): ?>
                <div class="alert alert-success"><?= esc(session('success')) ?></div>
            <?php endif; ?>

            <div class="row mb-4">
                <div class="col-md-4 text-center mb-3 mb-md-0">
                    <div class="mb-2 fw-semibold">QR Code</div>
                    <img
                        src="<?= base_url('p/qr/' . $row['qr_token']) ?>"
                        alt="QR Code Aset"
                        class="img-thumbnail border-0 shadow-sm"
                        style="max-height:180px">
                    <div class="mt-3 d-flex justify-content-center gap-2">
                        <a class="btn btn-sm btn-outline-secondary"
                            href="<?= base_url('p/qr/' . $row['qr_token']) ?>"
                            download="qr-<?= esc($row['kode_aset']) ?>.png">
                            <i class="bi bi-download"></i> Download QR
                        </a>
                        <a class="btn btn-sm btn-outline-primary"
                            href="<?= base_url('p/aset/' . $row['qr_token']) ?>"
                            target="_blank" rel="noopener">
                            <i class="bi bi-box-arrow-up-right"></i> Halaman Publik
                        </a>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="small text-muted">Kode Aset</div>
                            <div class="fw-bold"><code><?= esc($row['kode_aset']) ?></code></div>
                        </div>
                        <div class="col-sm-6">
                            <div class="small text-muted">Nama (Master)</div>
                            <div>
                                <?= esc($row['nama_master'] ?? '-') ?>
                                <?php if (!empty($row['master_deleted_at'])): ?>
                                    <span class="badge bg-warning text-dark ms-1">Arsip</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="small text-muted">Kategori / Subkategori</div>
                            <div><?= esc($row['nama_kategori'] ?? '-') ?> / <?= esc($row['nama_subkategori'] ?? '-') ?></div>
                        </div>
                        <div class="col-sm-6">
                            <div class="small text-muted">Cabang</div>
                            <div><?= esc($row['nama_cabang'] ?? '-') ?></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="small text-muted">Nilai Perolehan</div>
                            <div>
                                <?php
                                $val = $row['nilai_perolehan'];
                                echo $val === null ? '—' : '<span class="fw-semibold text-success">Rp ' . number_format((float)$val, 2, ',', '.') . '</span>';
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="small text-muted">Bulan–Tahun Perolehan</div>
                            <div><?= !empty($row['periode_perolehan']) ? date('Y-m', strtotime($row['periode_perolehan'])) : '—' ?></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="small text-muted">Expired/Kadaluarsa</div>
                            <div><?= !empty($row['expired_at']) ? esc($row['expired_at']) : '—' ?></div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-2"><strong>Jumlah / Stok</strong></div>
                            <div><?= esc($row['stock'] ?? 1) ?></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="small text-muted">Kondisi</div>
                            <div><?= esc($row['kondisi'] ?? '-') ?></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="small text-muted">Status</div>
                            <div><?= esc($row['status'] ?? '-') ?></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="small text-muted">Posisi</div>
                            <div><?= esc($row['posisi'] ?? '-') ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!empty($row['gambar'])): ?>
                <div class="mb-4 text-center">
                    <div class="mb-2 fw-semibold">Gambar</div>
                    <img src="<?= base_url('uploads/aset/' . $row['gambar']) ?>" alt="gambar" class="img-thumbnail shadow-sm" style="max-height:220px">
                </div>
            <?php endif; ?>

            <div class="mb-4">
                <h6 class="fw-semibold mb-2">Atribut</h6>
                <?php if (!empty($atributs)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:40%">Nama Atribut</th>
                                    <th>Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($atributs as $at): ?>
                                    <tr>
                                        <td><?= esc($at['nama_atribut']) ?></td>
                                        <td><?= esc($at['nilai']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-muted fst-italic">Tidak ada atribut untuk aset ini.</div>
                <?php endif; ?>
            </div>

            <?php if (!empty($row['keterangan'])): ?>
                <div class="mb-2">
                    <div class="fw-semibold mb-1">Keterangan</div>
                    <div class="text-muted"><?= nl2br(esc($row['keterangan'])) ?></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>