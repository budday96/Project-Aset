<?= $this->extend('layout/superadmin_template/index'); ?>
<?= $this->section('content'); ?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-3">Daftar Penyusutan Aset</h5>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table id="mytable" class="table table-bordered table-hover table-striped align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>Jenis Aktiva</th>
                        <th>Harga Perolehan</th>
                        <th>Tanggal Perolehan</th>
                        <th>Umur Ekonomis (Tahun)</th>
                        <th>Tarif % / Tahun</th>
                        <th>Penyusutan / Bulan</th>
                        <th>Umur (Bulan)</th>
                        <th>Akumulasi Penyusutan</th>
                        <th>Nilai Buku Saat Ini</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($items)): ?>
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data aset.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($items as $item): ?>
                            <tr class="<?= empty($item['tarif_persen_th']) ? 'table-warning' : '' ?>">
                                <td>
                                    <?= esc($item['nama_master'] ?? '-') ?>

                                </td>
                                <td class="text-end">Rp<?= number_format($item['nilai_perolehan'], 0, ',', '.') ?></td>
                                <td class="text-center">
                                    <?= $item['periode_perolehan'] ? date('M Y', strtotime($item['periode_perolehan'])) : '—' ?>
                                </td>
                                <td class="text-center">
                                    <?= $item['umur_tahun'] ?>
                                    <?php if (empty($item['tarif_persen_th'])): ?>
                                        <span class="badge bg-warning text-dark ms-1">Belum dikategorikan</span>
                                    <?php endif ?>
                                </td>
                                <td class="text-center">
                                    <?= isset($item['tarif_persen_th']) ? number_format($item['tarif_persen_th'], 2) . '%' : '' ?>
                                    <?php if (empty($item['tarif_persen_th'])): ?>
                                        <span class="badge bg-warning text-dark ms-1">Belum dikategorikan</span>
                                    <?php endif ?>
                                </td>
                                <td class="text-end">
                                    Rp<?= number_format($item['penyusutan_bulanan'], 0, ',', '.') ?>
                                </td>
                                <td class="text-center">
                                    <?= $item['bulan'] ?? '—' ?> bln
                                </td>
                                <td class="text-end">
                                    Rp<?= number_format($item['akumulasi'], 0, ',', '.') ?>
                                </td>
                                <td class="text-end fw-bold">
                                    Rp<?= number_format($item['nilai_buku'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>