<?= $this->extend('layout/user_template/index'); ?>
<?= $this->section('content'); ?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Buat Laporan Kerusakan Aset</h5>
        <small class="text-muted">
            Silakan pilih kode aset dan jelaskan kerusakan yang terjadi.
        </small>
    </div>

    <div class="card-body">

        <form method="post" action="/user/laporan/store" enctype="multipart/form-data">
            <?= csrf_field(); ?>

            <div id="aset-container">

                <!-- BARIS ASET -->
                <div class="aset-row border rounded p-3 mb-3">

                    <div class="mb-2">
                        <label class="form-label">Kode Aset</label>
                        <select name="aset_id[]" class="form-control" required>
                            <option value="">-- Pilih Kode Aset --</option>
                            <?php foreach ($aset as $a): ?>
                                <option value="<?= $a['id_aset'] ?>">
                                    <?= $a['kode_aset'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="aset-preview mt-2 p-2 bg-light rounded" style="display:none;">
                        <small>
                            <strong>Lokasi:</strong> <span class="preview-posisi"></span><br>
                            <strong>Kondisi:</strong> <span class="preview-kondisi"></span>
                        </small>

                        <div class="mt-2">
                            <img class="preview-image img-thumbnail" width="120">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Deskripsi Kerusakan</label>
                        <textarea name="deskripsi[]"
                            class="form-control"
                            rows="3"
                            placeholder="Jelaskan kerusakan yang terjadi..."
                            required></textarea>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Foto (Opsional)</label>
                        <input type="file"
                            name="foto[]"
                            class="form-control">
                    </div>

                    <div class="text-end">
                        <button type="button"
                            class="btn btn-sm btn-danger remove-row">
                            Hapus Aset
                        </button>
                    </div>

                </div>
                <!-- END BARIS ASET -->

            </div>

            <div class="mb-3">
                <button type="button"
                    class="btn btn-secondary"
                    id="add-aset">
                    + Tambah Aset
                </button>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">
                    Simpan Laporan
                </button>
            </div>

        </form>

    </div>
</div>

<script>
    document.getElementById('add-aset').addEventListener('click', function() {

        let container = document.getElementById('aset-container');
        let firstRow = document.querySelector('.aset-row');
        let newRow = firstRow.cloneNode(true);

        // reset value
        newRow.querySelectorAll('input, textarea, select').forEach(el => {
            el.value = '';
        });

        container.appendChild(newRow);
    });

    // hapus row
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-row')) {

            let rows = document.querySelectorAll('.aset-row');

            if (rows.length > 1) {
                e.target.closest('.aset-row').remove();
            } else {
                alert('Minimal harus ada 1 aset.');
            }
        }
    });
</script>

<script>
    document.addEventListener('change', function(e) {

        if (e.target.name === 'aset_id[]') {

            let asetId = e.target.value;
            let row = e.target.closest('.aset-row');
            let preview = row.querySelector('.aset-preview');

            if (!asetId) {
                preview.style.display = 'none';
                return;
            }

            fetch('/user/laporan/aset/' + asetId)
                .then(res => res.json())
                .then(res => {

                    if (!res.status) return;

                    row.querySelector('.preview-posisi').innerText =
                        res.data.posisi ?? '-';

                    row.querySelector('.preview-kondisi').innerText =
                        res.data.kondisi ?? '-';

                    row.querySelector('.preview-image').src =
                        '/uploads/aset/' + res.data.gambar;

                    preview.style.display = 'block';
                });
        }
    });
</script>

<?= $this->endSection(); ?>