<?= $this->extend('layout/superadmin_template/index'); ?>
<?= $this->section('content'); ?>

<h4>Detail Penyusutan Aset: <?= $aset['kode_aset'] ?> - <?= $aset['nama_aset'] ?></h4>
<p>Cabang: <b><?= $aset['nama_cabang'] ?></b></p>
<p>Kategori: <b><?= $aset['nama_kategori'] ?></b></p>

<div class="card p-3 mt-3">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Tahun</th>
                <th>Bulan</th>
                <th>Beban Penyusutan</th>
                <th>Akumulasi</th>
                <th>Nilai Buku</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($history as $h): ?>
                <tr>
                    <td><?= $h['tahun'] ?></td>
                    <td><?= $h['bulan'] ?></td>
                    <td><?= number_format($h['beban_penyusutan_bulan'], 0, ',', '.') ?></td>
                    <td><?= number_format($h['akumulasi_sampai_bulan_ini'], 0, ',', '.') ?></td>
                    <td><?= number_format($h['nilai_buku'], 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>

    </table>
</div>

<?= $this->endSection(); ?>