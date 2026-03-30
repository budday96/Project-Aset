<?= $this->extend('layout/admin_template/index'); ?>
<?= $this->section('content'); ?>

<div class="card">
    <div class="card-body">

        <h3>Assign Vendor</h3>

        <form method="post" action="/admin/laporan/assign/<?= $laporan['id_laporan'] ?>">

            <label>Pilih Vendor</label>
            <select name="vendor_id" required>
                <option value="">-- Pilih Vendor --</option>
                <?php foreach ($vendors as $v): ?>
                    <option value="<?= $v['id_vendor'] ?>">
                        <?= $v['nama_vendor'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <br><br>

            <label>Estimasi Biaya</label>
            <input type="number" name="estimasi_biaya" step="0.01" required>

            <br><br>

            <button type="submit">Assign</button>
        </form>

    </div>
</div>

<?= $this->endSection(); ?>