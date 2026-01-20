<?= $this->extend('layout/admin_template/index'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid px-0">
    <div class="card shadow-sm mb-4">
        <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #fd7e14;">
            <h5 class="mb-0"><?= esc($title ?? 'Detail Master Aset'); ?></h5>
            <div>
                <a class="btn btn-light btn-sm me-2" href="<?= base_url('admin/master-aset/edit/' . (int)$row['id_master_aset']) ?>">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a class="btn btn-outline-light btn-sm" href="<?= base_url('admin/master-aset') ?>">
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
                <div class="col-md-12">
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <div class="small text-muted">Kode Master</div>
                            <div class="fw-bold"><code><?= esc($row['kode_master'] ?? '-') ?></code></div>
                        </div>
                        <div class="col-sm-8">
                            <div class="small text-muted">Nama Master</div>
                            <div class="fw-semibold"><?= esc($row['nama_master'] ?? '-') ?></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="small text-muted">Kategori</div>
                            <div><?= esc($row['nama_kategori'] ?? '-') ?></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="small text-muted">Subkategori</div>
                            <div><?= esc($row['nama_subkategori'] ?? '-') ?></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="small text-muted">Expired Default</div>
                            <div><?= !empty($row['expired_default']) ? esc($row['expired_default']) : '—' ?></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="small text-muted">Nilai Perolehan Default</div>
                            <div>
                                <?php
                                $nom = $row['nilai_perolehan_default'] ?? null;
                                echo $nom === null || $nom === '' ? '—' : '<span class="fw-semibold text-success">Rp ' . number_format((float)$nom, 0, ',', '.') . '</span>';
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="small text-muted">Periode Perolehan Default</div>
                            <div>
                                <?php
                                echo !empty($row['periode_perolehan_default'])
                                    ? date('Y-m', strtotime($row['periode_perolehan_default']))
                                    : '—';
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="small text-muted">Dibuat / Diubah</div>
                            <div><?= esc($row['created_at'] ?? '-') ?> / <?= esc($row['updated_at'] ?? '-') ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <h6 class="fw-semibold mb-2">Atribut Default</h6>
                <?php $attr = $atributDefaults ?? []; ?>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:60px">#</th>
                                <th>Nama Atribut</th>
                                <th style="width:140px">Tipe</th>
                                <th style="width:120px">Satuan</th>
                                <th>Nilai Default</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($attr)): ?>
                                <?php $no = 1;
                                foreach ($attr as $a): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= esc($a['nama_atribut']) ?></td>
                                        <td><?= esc($a['tipe_input']) ?></td>
                                        <td><?= esc($a['satuan'] ?? '-') ?></td>
                                        <td>
                                            <?php
                                            $v = $a['nilai_default'] ?? '';
                                            $decoded = json_decode((string)$v, true);
                                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                                echo esc(implode(', ', $decoded));
                                            } else {
                                                echo esc($v === '' ? '—' : $v);
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">Tidak ada atribut default.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>