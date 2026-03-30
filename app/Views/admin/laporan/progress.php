<?= $this->extend('layout/admin_template/index'); ?>
<?= $this->section('content'); ?>

<div class="card">
    <div class="card-body">

        <h3>Update Progress</h3>

        <form method="post" action="/admin/laporan/progress/<?= $laporan['id_laporan'] ?>">

            <label>Status</label>
            <select name="status" required>
                <option value="IN_PROGRESS">IN_PROGRESS</option>
                <option value="DONE">DONE</option>
            </select>

            <br><br>

            <label>Catatan</label>
            <textarea name="catatan" required></textarea>

            <br><br>

            <button type="submit">Update</button>
        </form>

    </div>
</div>

<?= $this->endSection(); ?>