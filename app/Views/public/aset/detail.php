<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= esc($title ?? 'Detail Aset') ?></title>
    <link rel="stylesheet" href="<?= base_url(); ?>vendor/bootstrap/css/bootstrap.min.css">
    <style>
        body {
            background: #f8f9fc;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .05);
        }

        .badge {
            font-size: .9rem;
        }

        .footer-note {
            font-size: .85rem;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header text-white text-center" style="background-color: #fd7e14;">
                        <strong>Detail Aset</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5 text-center mb-3">
                                <!-- QR Code publik -->
                                <img src="<?= base_url('p/qr/' . $aset['qr_token']) ?>" alt="QR Code Aset" class="img-fluid mb-2" style="max-width:220px;">
                                <div>
                                    <a class="btn btn-sm btn-outline-secondary"
                                        href="<?= base_url('p/qr/' . $aset['qr_token']) ?>"
                                        download="qr_aset_<?= esc($aset['kode_aset']) ?>.png">
                                        Unduh QR Code
                                    </a>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <table class="table table-sm">
                                    <tr>
                                        <th style="width:40%">Nama Aset</th>
                                        <td><?= esc($aset['nama_aset']) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Kode Aset</th>
                                        <td><?= esc($aset['kode_aset']) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Kategori</th>
                                        <td><?= esc($aset['nama_kategori']) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Subkategori</th>
                                        <td><?= esc($aset['nama_subkategori'] ?? '-') ?></td>
                                    </tr>
                                    <tr>
                                        <th>Cabang</th>
                                        <td><?= esc($aset['nama_cabang']) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Tahun Perolehan</th>
                                        <td><?= $aset['periode_perolehan'] ? date('Y', strtotime($aset['periode_perolehan'])) : '-' ?></td>
                                    </tr>

                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <?php
                                            $status = $aset['status'];
                                            $statusClass = ($status === 'Digunakan') ? 'success'
                                                : (($status === 'Tidak Digunakan') ? 'secondary'
                                                    : (($status === 'Hilang') ? 'danger' : 'light'));
                                            ?>
                                            <span class="badge bg-<?= $statusClass ?>"><?= esc($status) ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Kondisi</th>
                                        <td>
                                            <?php
                                            $kondisi = $aset['kondisi'];
                                            $kondisiClass = ($kondisi === 'Baik') ? 'info'
                                                : (($kondisi === 'Rusak Ringan') ? 'warning'
                                                    : (($kondisi === 'Rusak Berat') ? 'danger' : 'secondary'));
                                            ?>
                                            <span class="badge bg-<?= $kondisiClass ?>"><?= esc($kondisi) ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Expired/Kadaluarsa</th>
                                        <td><?= $aset['expired_at'] ? date('d-m-Y', strtotime($aset['expired_at'])) : '-' ?></td>
                                    </tr>
                                    <tr>
                                        <th>Keterangan</th>
                                        <td><?= esc($aset['keterangan']) ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <hr>
                        <div class="text-center mb-3">
                            <strong>Gambar Aset:</strong><br>
                            <?php if (!empty($aset['gambar'])): ?>
                                <img src="<?= base_url('uploads/aset/' . $aset['gambar']) ?>" alt="Gambar Aset" class="img-thumbnail mt-2" style="max-width:320px;">
                            <?php else: ?>
                                <span class="text-muted">Tidak ada gambar</span>
                            <?php endif; ?>
                        </div>

                        <!-- ===== Detail Atribut (Publik) ===== -->
                        <h5 class="mb-3">Detail Atribut</h5>
                        <?php
                        // Helper untuk format nilai atribut publik
                        $fmtAttr = function (array $row): string {
                            $val    = $row['nilai'];
                            $tipe   = $row['tipe_input'] ?? 'text';
                            $satuan = $row['satuan'] ?? null;

                            // Jika nilai JSON → ringkas
                            $trim = is_string($val) ? trim($val) : $val;
                            if (is_string($trim) && $trim !== '' && ($trim[0] === '[' || $trim[0] === '{')) {
                                $decoded = json_decode($trim, true);
                                if (json_last_error() === JSON_ERROR_NONE) {
                                    if (is_array($decoded)) {
                                        $val = implode(', ', array_map(function ($v) {
                                            return is_scalar($v) ? (string)$v : json_encode($v, JSON_UNESCAPED_UNICODE);
                                        }, is_assoc($decoded) ? array_values($decoded) : $decoded));
                                    } else {
                                        $val = (string)$trim;
                                    }
                                }
                            }

                            // Format tanggal
                            if ($tipe === 'date' && !empty($val)) {
                                $dt = \DateTime::createFromFormat('Y-m-d', $val);
                                if ($dt) $val = $dt->format('d-m-Y');
                            }

                            // Tambah satuan jika ada
                            if (!empty($satuan) && $val !== '' && $val !== null) {
                                $val = $val . ' ' . $satuan;
                            }

                            return ($val === '' || $val === null) ? '-' : esc($val);
                        };

                        function is_assoc(array $arr)
                        {
                            if ([] === $arr) return false;
                            return array_keys($arr) !== range(0, count($arr) - 1);
                        }
                        ?>

                        <?php if (!empty($nilaiAtribut)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-sm">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width:40%;">Nama Atribut</th>
                                            <th>Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($nilaiAtribut as $row): ?>
                                            <tr>
                                                <td><?= esc($row['nama_atribut']) ?></td>
                                                <td><?= $fmtAttr($row) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert bg-secondary mb-0 text-white">Tidak ada atribut untuk subkategori ini.</div>
                        <?php endif; ?>
                        <!-- ================================ -->

                    </div>
                </div>

                <div class="text-center footer-note">
                    © <?= date('Y') ?> — Halaman ini hanya untuk penelusuran informasi aset. Hubungi admin untuk perubahan data.
                </div>
            </div>
        </div>
    </div>
</body>

</html>