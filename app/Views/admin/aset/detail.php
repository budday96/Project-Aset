<?= $this->extend('layout/admin_template/index'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid px-0">

    <div class="card shadow-lg border-0 mb-4" style="border-radius: 15px; overflow: hidden;">
        <div class="card-header text-white d-flex justify-content-between align-items-center"
            style="background: linear-gradient(135deg, #fd7e14, #ff9f43);">
            <h5 class="mb-0 d-flex align-items-center">
                <i class="bi bi-info-circle-fill me-2"></i> Detail Aset
            </h5>
            <div>
                <a href="<?= base_url('admin/aset/edit/' . (int)$row['id_aset']) ?>"
                    class="btn btn-light btn-sm me-2 shadow-sm">
                    <i class="bi bi-pencil-fill"></i> Edit
                </a>
                <a href="<?= base_url('admin/aset') ?>"
                    class="btn btn-outline-light btn-sm shadow-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card-body p-4">

            <?php if (session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= esc(session('error')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?= esc(session('success')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- QR + Detail -->
            <div class="row mb-4 align-items-start">

                <!-- QR Section -->
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="card border-0 shadow-sm bg-light" style="border-radius: 10px;">
                        <div class="card-body text-center p-4">
                            <div class="text-uppercase small fw-bold mb-3 text-primary">QR Code</div>
                            <img src="<?= base_url('p/qr/' . $row['qr_token']) ?>"
                                class="img-fluid rounded shadow-sm mb-3"
                                style="max-height: 180px;"
                                alt="QR Code Aset">
                            <div class="d-flex justify-content-center flex-wrap gap-2">
                                <a href="<?= base_url('p/qr/' . $row['qr_token']) ?>"
                                    download="qr-<?= esc($row['kode_aset']) ?>.png"
                                    class="btn btn-outline-secondary btn-sm shadow-sm">
                                    <i class="bi bi-download"></i> Download QR
                                </a>
                                <a href="<?= base_url('p/aset/' . $row['qr_token']) ?>"
                                    target="_blank"
                                    rel="noopener"
                                    class="btn btn-outline-primary btn-sm shadow-sm">
                                    <i class="bi bi-box-arrow-up-right"></i> Halaman Publik
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Info -->
                <div class="col-md-8">
                    <div class="row g-3">
                        <?php
                        $fields = [
                            ['Kode Aset', '<code class="bg-light px-2 py-1 rounded">' . esc($row["kode_aset"]) . '</code>'],
                            ['Nama (Master)', esc($row['nama_master'] ?? '-') . (!empty($row['master_deleted_at']) ? ' <span class="badge bg-warning text-dark ms-1"><i class="bi bi-archive"></i> Arsip</span>' : '')],
                            ['Kategori / Subkategori', esc($row['nama_kategori'] ?? '-') . ' / ' . esc($row['nama_subkategori'] ?? '-')],
                            ['Cabang', esc($row['nama_cabang'] ?? '-')],
                        ];
                        ?>

                        <?php foreach ($fields as $f): ?>
                            <div class="col-sm-6 pb-3 border-bottom border-light">
                                <div class="text-muted small fw-semibold text-uppercase mb-1"><?= $f[0] ?></div>
                                <div class="fw-bold text-dark"><?= $f[1] ?></div>
                            </div>
                        <?php endforeach; ?>

                        <!-- Nilai Perolehan -->
                        <div class="col-sm-4 pb-3 border-bottom border-light">
                            <div class="text-muted small fw-semibold text-uppercase mb-1">Nilai Perolehan</div>
                            <div>
                                <?php
                                $val = $row['nilai_perolehan'];
                                echo $val === null
                                    ? '<span class="text-muted">—</span>'
                                    : '<span class="fw-bold text-success">Rp ' . number_format((float)$val, 2, ',', '.') . '</span>';
                                ?>
                            </div>
                        </div>

                        <!-- Periode -->
                        <div class="col-sm-4 pb-3 border-bottom border-light">
                            <div class="text-muted small fw-semibold text-uppercase mb-1">Periode Perolehan</div>
                            <div class="fw-bold text-dark"><?= !empty($row['periode_perolehan']) ? date('Y-m', strtotime($row['periode_perolehan'])) : '<span class="text-muted">—</span>' ?></div>
                        </div>

                        <!-- Expired -->
                        <div class="col-sm-4 pb-3 border-bottom border-light">
                            <div class="text-muted small fw-semibold text-uppercase mb-1">Expired</div>
                            <div class="fw-bold text-dark"><?= !empty($row['expired_at']) ? esc($row['expired_at']) : '<span class="text-muted">—</span>' ?></div>
                        </div>

                        <!-- Stok -->
                        <div class="col-sm-4 pb-3 border-bottom border-light">
                            <div class="text-muted small fw-semibold text-uppercase mb-1">Jumlah / Stok</div>
                            <div class="fw-bold text-dark badge bg-info text-white fs-6 px-3 py-2"><?= esc($row['stock'] ?? 1) ?></div>
                        </div>

                        <!-- Kondisi -->
                        <div class="col-sm-4 pb-3 border-bottom border-light">
                            <div class="text-muted small fw-semibold text-uppercase mb-1">Kondisi</div>
                            <div class="fw-bold text-dark">
                                <?php
                                $kondisi = esc($row['kondisi'] ?? '-');
                                $badgeClass = match (strtolower($kondisi)) {
                                    'baik' => 'bg-success',
                                    'rusak' => 'bg-danger',
                                    'perlu perbaikan' => 'bg-warning',
                                    default => 'bg-secondary'
                                };
                                echo "<span class='badge $badgeClass'>$kondisi</span>";
                                ?>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-sm-4 pb-3 border-bottom border-light">
                            <div class="text-muted small fw-semibold text-uppercase mb-1">Status</div>
                            <div class="fw-bold text-dark">
                                <?php
                                $status = esc($row['status'] ?? '-');
                                $badgeClass = match (strtolower($status)) {
                                    'aktif' => 'bg-success',
                                    'non-aktif' => 'bg-secondary',
                                    'dipinjam' => 'bg-warning',
                                    default => 'bg-secondary'
                                };
                                echo "<span class='badge $badgeClass'>$status</span>";
                                ?>
                            </div>
                        </div>

                        <!-- Posisi -->
                        <div class="col-sm-4 pb-3 border-bottom border-light">
                            <div class="text-muted small fw-semibold text-uppercase mb-1">Posisi</div>
                            <div class="fw-bold text-dark"><i class="bi bi-geo-alt-fill text-primary me-1"></i><?= esc($row['posisi'] ?? '-') ?></div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Gambar -->
            <?php if (!empty($row['gambar'])): ?>
                <div class="mb-4 text-center">
                    <div class="text-uppercase small fw-bold mb-3 text-primary">Gambar Aset</div>
                    <img src="<?= base_url('uploads/aset/' . $row['gambar']) ?>"
                        class="img-fluid rounded shadow-lg"
                        style="max-height: 300px; cursor: pointer;"
                        alt="Gambar Aset"
                        data-bs-toggle="modal"
                        data-bs-target="#imageModal">
                </div>

                <!-- Modal for Image -->
                <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="imageModalLabel">Gambar Aset</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center p-0">
                                <img src="<?= base_url('uploads/aset/' . $row['gambar']) ?>" class="img-fluid" alt="Gambar Aset">
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Atribut -->
            <div class="mb-4">
                <h6 class="fw-bold mb-3 text-primary d-flex align-items-center">
                    <i class="bi bi-list-check me-2"></i> Atribut Tambahan
                </h6>

                <?php if (!empty($atributs)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered align-middle shadow-sm" style="border-radius: 10px; overflow: hidden;">
                            <thead class="table-primary text-white">
                                <tr>
                                    <th style="width:35%" class="fw-semibold">Nama Atribut</th>
                                    <th class="fw-semibold">Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($atributs as $at): ?>
                                    <tr>
                                        <td class="fw-semibold text-dark"><?= esc($at['nama_atribut']) ?></td>
                                        <td class="text-muted"><?= esc($at['nilai']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-muted fst-italic bg-light p-3 rounded shadow-sm">
                        <i class="bi bi-info-circle me-1"></i> Tidak ada atribut untuk aset ini.
                    </div>
                <?php endif; ?>
            </div>

            <!-- Keterangan -->
            <?php if (!empty($row['keterangan'])): ?>
                <div class="mb-2">
                    <div class="fw-bold mb-2 text-primary d-flex align-items-center">
                        <i class="bi bi-chat-quote me-2"></i> Keterangan
                    </div>
                    <div class="bg-light p-3 rounded shadow-sm text-muted">
                        <?= nl2br(esc($row['keterangan'])) ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?= $this->endSection(); ?>