<?= $this->extend('layout/superadmin_template/index'); ?>
<?= $this->section('content'); ?>

<h4 class="mb-3">Laporan Penyusutan Aset</h4>

<div class="card mb-3 p-3">
    <form method="get">
        <div class="row g-3">
            <div class="col-md-2">
                <label class="form-label">Tahun</label>
                <input type="number" name="tahun" class="form-control"
                    value="<?= esc($tahun) ?>">
            </div>

            <div class="col-md-2">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select">
                    <option value="">Semua</option>
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?= $i ?>" <?= $bulan == $i ? 'selected' : '' ?>>
                            <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Cabang</label>
                <select name="cabang" class="form-select">
                    <option value="">Semua Cabang</option>
                    <?php foreach ($cabangs as $c): ?>
                        <option value="<?= $c['id_cabang'] ?>"
                            <?= $id_cabang == $c['id_cabang'] ? 'selected' : '' ?>>
                            <?= $c['nama_cabang'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($kategoris as $k): ?>
                        <option value="<?= $k['id_kategori'] ?>"
                            <?= $id_kategori == $k['id_kategori'] ? 'selected' : '' ?>>
                            <?= $k['nama_kategori'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
    </form>
    <div class="d-flex justify-content-end mb-3">
        <form action="<?= site_url('superadmin/penyusutan/generate') ?>" method="post"
            onsubmit="return confirm('Generate penyusutan bulan ini?');">
            <button class="btn btn-success">
                <i class="bi bi-calculator"></i> Generate Penyusutan Bulan Ini
            </button>
        </form>
    </div>
</div>

<div class="card p-3">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Kode Aset</th>
                <th>Nama Aset</th>
                <th>Cabang</th>
                <th>Kategori</th>
                <th>Harga Perolehan</th>
                <th>Bulan/Tahun Perolehan</th>
                <th>Tarif Penyusutan</th>
                <th>Bulan</th>
                <th>Umur Ekonomis</th>
                <th>Beban Penyusutan</th>
                <th>Akumulasi</th>
                <th>Nilai Buku</th>
                <th>Aksi</th>
            </tr>

        </thead>

        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= esc($item['kode_aset']); ?></td>
                    <td><?= esc($item['nama_aset']); ?></td>
                    <td><?= esc($item['nama_cabang']); ?></td>
                    <td><?= esc($item['nama_kategori']); ?></td>


                    <td><?= number_format($item['harga_perolehan'], 0, ',', '.'); ?></td>
                    <td>
                        <?php if (!empty($item['periode_perolehan'])): ?>
                            <?= date('m/Y', strtotime($item['periode_perolehan'])); ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>

                    <td><?= rtrim(rtrim(number_format($item['tarif_persen_th'], 2, ',', '.'), '0'), ','); ?>%</td>
                    <td><?= sprintf('%02d/%d', $item['bulan'], $item['tahun']); ?></td>
                    <td><?= $item['umur_tahun']; ?> tahun (<?= $item['umur_bulan']; ?> bulan)</td>

                    <td><?= number_format($item['beban_penyusutan_bulan'], 3, ',', '.'); ?></td>
                    <td><?= number_format($item['akumulasi_sampai_bulan_ini'], 3, ',', '.'); ?></td>
                    <td><?= number_format($item['nilai_buku'], 3, ',', '.'); ?></td>
                    <td>
                        <a href="<?= site_url('superadmin/penyusutan/detail/' . $item['id_aset']); ?>" class="btn btn-sm btn-primary">
                            Detail
                        </a>
                    </td>
                </tr>

            <?php endforeach; ?>
        </tbody>

    </table>
</div>

<?= $this->endSection(); ?>