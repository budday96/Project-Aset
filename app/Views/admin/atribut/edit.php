<?= $this->extend('layout/admin_template/index') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('admin/atribut/update/' . $row['id_atribut']) ?>" method="post" class="row g-3">
            <?= csrf_field() ?>
            <input type="hidden" name="id_subkategori" value="<?= $sub['id_subkategori'] ?>">

            <div class="col-md-6">
                <label class="form-label">Nama Atribut *</label>
                <input type="text" class="form-control" name="nama_atribut" value="<?= esc($row['nama_atribut']) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Kode (opsional)</label>
                <input type="text" class="form-control" name="kode_atribut" value="<?= esc($row['kode_atribut']) ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label">Tipe Input *</label>
                <select class="form-select" name="tipe_input" id="tipe_input" required>
                    <?php foreach (['text', 'number', 'date', 'select', 'textarea'] as $t): ?>
                        <option value="<?= $t ?>" <?= $row['tipe_input'] === $t ? 'selected' : '' ?>><?= $t ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Satuan (opsional)</label>
                <input type="text" class="form-control" name="satuan" value="<?= esc($row['satuan']) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label d-block">Wajib?</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_required" value="1" <?= $row['is_required'] ? 'checked' : '' ?>>
                    <label class="form-check-label">Ya, wajib diisi</label>
                </div>
            </div>

            <div class="col-12" id="options_wrap" style="<?= $row['tipe_input'] === 'select' ? '' : 'display:none' ?>">
                <label class="form-label">Opsi (untuk tipe <em>select</em>)</label>
                <textarea class="form-control" name="options" rows="4"><?= esc($options_text) ?></textarea>
            </div>

            <div class="col-12">
                <label class="form-label">Urutan</label>
                <input type="number" class="form-control" name="urutan" value="<?= (int)$row['urutan'] ?>">
            </div>

            <div class="col-12">
                <button class="btn btn-primary">Update</button>
                <a href="<?= base_url('admin/atribut/' . $sub['id_subkategori']) ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('tipe_input').addEventListener('change', function() {
        document.getElementById('options_wrap').style.display = (this.value === 'select') ? '' : 'none';
    });
</script>

<?= $this->endSection() ?>