<?= $this->extend('layout/superadmin_template/index') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('superadmin/atribut/store') ?>" method="post" class="row g-3">
            <?= csrf_field() ?>
            <input type="hidden" name="id_subkategori" value="<?= $sub['id_subkategori'] ?>">

            <div class="col-md-6">
                <label class="form-label">Nama Atribut *</label>
                <input type="text" class="form-control" name="nama_atribut" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Kode (opsional)</label>
                <input type="text" class="form-control" name="kode_atribut" placeholder="mis. ram, ssd">
            </div>

            <div class="col-md-4">
                <label class="form-label">Tipe Input *</label>
                <select class="form-select" name="tipe_input" id="tipe_input" required>
                    <option value="text">text</option>
                    <option value="number">number</option>
                    <option value="date">date</option>
                    <option value="select">select</option>
                    <option value="textarea">textarea</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Satuan (opsional)</label>
                <input type="text" class="form-control" name="satuan" placeholder="GB, inch, cm, dll">
            </div>
            <div class="col-md-4">
                <label class="form-label d-block">Wajib?</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_required" value="1">
                    <label class="form-check-label">Ya, wajib diisi</label>
                </div>
            </div>

            <div class="col-12" id="options_wrap" style="display:none">
                <label class="form-label">Opsi (untuk tipe <em>select</em>)</label>
                <textarea class="form-control" name="options" rows="4" placeholder="Satu opsi per baris"></textarea>
            </div>

            <div class="col-12">
                <label class="form-label">Urutan</label>
                <input type="number" class="form-control" name="urutan" value="0">
            </div>

            <div class="col-12">
                <button class="btn btn-primary">Simpan</button>
                <a href="<?= base_url('superadmin/atribut/' . $sub['id_subkategori']) ?>" class="btn btn-secondary">Kembali</a>
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