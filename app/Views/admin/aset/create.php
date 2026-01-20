<?= $this->extend('layout/admin_template/index'); ?>
<?= $this->section('content'); ?>

<div class="card">
    <div class="card-body">
        <?php if (session()->getFlashdata('error')): ?>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    showToast("<?= esc(session()->getFlashdata('error')) ?>", 'danger');
                });
            </script>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    showToast("<?= esc(session()->getFlashdata('success')) ?>", 'success');
                });
            </script>
        <?php endif; ?>


        <form action="<?= base_url('admin/aset/store') ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            <?= csrf_field() ?>

            <div class="row g-3">
                <!-- Nama Aset -->
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label mb-0">Nama Aset *</label>
                        <small class="text-muted">buat master baru</small>
                    </div>

                    <div class="row g-2">
                        <!-- Select2 -->
                        <div class="col-md-9 col-12">
                            <select id="master_aset"
                                class="form-select"
                                name="id_master_aset"
                                required
                                style="width: 100%">
                                <option value="">-- Pilih Nama Aset --</option>
                            </select>
                        </div>

                        <!-- Tombol Master Baru -->
                        <div class="col-md-3 col-12 d-grid">
                            <button type="button"
                                class="btn btn-outline-warning"
                                id="btn-add-master">
                                + Master Baru
                            </button>
                        </div>
                    </div>

                    <div class="invalid-feedback">
                        <?= session('errors.id_master_aset') ?: 'Silakan pilih nama aset dari master.' ?>
                    </div>
                </div>


                <!-- Kategori/Subkategori -->
                <div class="col-md-6">
                    <label>Kategori</label>
                    <input type="text" id="kategori" class="form-control" readonly>
                </div>
                <div class="col-md-6">
                    <label>Subkategori</label>
                    <input type="text" id="subkategori" class="form-control" readonly>
                </div>

                <!-- Nilai dan Periode -->
                <div class="col-md-6">
                    <label>Nilai Perolehan</label>
                    <input type="text" id="nilai_perolehan" class="form-control" readonly>
                </div>
                <div class="col-md-6">
                    <label>Bulan–Tahun Perolehan</label>
                    <input type="text" id="bulan_tahun" class="form-control" readonly>
                </div>

                <!-- Stok -->
                <div class="col-md-12">
                    <label class="form-label">Jumlah / Stok Aset *</label>
                    <input type="number" name="stock" min="1" class="form-control" value="<?= old('stock', 1) ?>" required>
                    <div class="form-text">Angka ini akan <strong>ditambahkan</strong> ke stok yang sudah ada bila opsi “Tambah stok jika sudah ada” aktif.</div>
                    <div class="invalid-feedback">Masukkan jumlah minimal 1.</div>
                </div>

                <div class="col-12">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="merge_if_exists" id="merge_if_exists" value="1" <?= old('merge_if_exists', '1') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="merge_if_exists">
                            Tambah stok jika aset master ini sudah ada di cabang ini.
                        </label>
                    </div>
                </div>

                <!-- Kondisi / Status -->
                <div class="col-md-6">
                    <label>Kondisi *</label>
                    <select name="kondisi" class="form-select" required>
                        <option value="">-- Pilih Kondisi --</option>
                        <option value="Baik">Baik</option>
                        <option value="Rusak Ringan">Rusak Ringan</option>
                        <option value="Rusak Berat">Rusak Berat</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Status *</label>
                    <select name="status" class="form-select" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="Digunakan">Digunakan</option>
                        <option value="Tidak Digunakan">Tidak Digunakan</option>
                        <option value="Hilang">Hilang</option>
                    </select>
                </div>

                <!-- Posisi -->
                <div class="col-md-6">
                    <label>Posisi/Lokasi Saat Ini</label>
                    <input type="text" name="posisi" class="form-control" placeholder="Contoh: Gudang A / Yard 3">
                </div>

                <!-- Gambar -->
                <div class="col-md-6">
                    <label>Gambar Aset</label>
                    <input type="file" name="gambar" class="form-control" accept="image/*">
                </div>

                <!-- Detail Atribut -->
                <div class="col-12">
                    <hr>
                    <h6>Detail Atribut</h6>
                    <div class="row g-3" id="dynamic-fields"></div>
                </div>

                <!-- Keterangan -->
                <div class="col-12">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3"></textarea>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary me-2">Simpan</button>
                <button type="reset" class="btn btn-danger me-2">Reset</button>
                <a href="<?= base_url('admin/aset') ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL TAMBAH MASTER ================= -->
<div class="modal fade" id="modalMasterBaru" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form id="form-quick-master" class="needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Master Aset</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label>Nama Master *</label>
                            <input type="text" name="nama_master" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label>Expired (opsional)</label>
                            <input type="date" name="expired_default" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Kategori *</label>
                            <select name="id_kategori" id="qm_id_kategori" class="form-select" style="width:100%" required>
                                <option value="" selected disabled>-- Pilih Kategori --</option>
                                <?php foreach (($kategoris ?? []) as $k): ?>
                                    <option value="<?= (int)$k['id_kategori'] ?>"><?= esc($k['nama_kategori']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Subkategori *</label>
                            <select name="id_subkategori" id="qm_id_subkategori" class="form-select" style="width:100%" required>
                                <option value="" selected disabled>-- Pilih Subkategori --</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Nilai Perolehan Default</label>
                            <input type="number" name="nilai_perolehan_default" class="form-control" placeholder="Contoh: 2500000">
                        </div>
                        <div class="col-md-6">
                            <label>Periode Perolehan Default (Bulan)</label>
                            <input type="month" name="periode_perolehan_default_month" class="form-control">
                        </div>
                    </div>
                    <div class="alert alert-danger mt-3 d-none" id="qm_error"></div>
                    <div class="alert alert-success mt-3 d-none" id="qm_success"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Master</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>


<?= $this->section('css'); ?>
<style>
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 38px;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 0.375rem 0.75rem;
    }

    .input-group>.select2-container {
        flex: 1 1 auto;
    }

    #btn-add-master {
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
<?= $this->endSection(); ?>


<?= $this->section('scripts'); ?>
<script>
    $(function() {
        const BASE = '<?= base_url() ?>';
        const csrfName = '<?= csrf_token() ?>';
        let csrfValue = '<?= csrf_hash() ?>';

        const modalEl = $('#modalMasterBaru');
        const $kat = $('#qm_id_kategori');
        const $sub = $('#qm_id_subkategori');

        // === Fungsi global untuk update CSRF dari response ===
        function updateCsrfTokenFromResponse(res) {
            const newToken = res.headers.get('X-CSRF-TOKEN');
            if (newToken) {
                csrfValue = newToken;
                document.querySelectorAll(`input[name="${csrfName}"]`).forEach(inp => inp.value = newToken);
            }
        }

        // === Select2 utama ===
        $('#master_aset').select2({
            theme: 'bootstrap-5',
            width: '100%',
            dropdownParent: $('#master_aset').parent(),
            placeholder: '-- Pilih Nama Aset --',
            ajax: {
                url: `${BASE}/admin/aset/getMasterAset`,
                dataType: 'json',
                delay: 250,
                processResults: data => ({
                    results: data.results
                })
            }
        });

        // === Load detail aset ===
        $('#master_aset').on('change', function() {
            const id = $(this).val();
            if (!id) return;

            $.getJSON(`${BASE}/admin/aset/ajaxMasterDetail/${id}`, data => {
                if (!data.ok) {
                    alert('Master tidak ditemukan');
                    return;
                }

                // Set kategori dll
                $('#kategori').val(data.nama_kategori || '-');
                $('#subkategori').val(data.nama_subkategori || '-');
                $('#nilai_perolehan').val(data.nilai_default || '-');
                $('#bulan_tahun').val(data.periode_default || '-');

                // ==== RENDER ATTRIBUT ====
                const wrap = $('#dynamic-fields');
                wrap.empty();

                if (!data.atribut || data.atribut.length === 0) {
                    wrap.append(`<div class="col-12"><em>Tidak ada atribut untuk master ini.</em></div>`);
                    return;
                }

                data.atribut.forEach(a => {
                    let field = '';

                    // Text
                    if (a.tipe_input === 'text') {
                        field = `
                    <div class="col-md-6">
                        <label>${a.nama_atribut}${a.is_required ? ' *' : ''}</label>
                        <input type="text" 
                               name="atribut[${a.id_atribut}]" 
                               class="form-control"
                               value="${a.nilai || ''}"
                               ${a.is_required ? 'required' : ''}>
                    </div>
                `;
                    }

                    // Number
                    else if (a.tipe_input === 'number') {
                        field = `
                    <div class="col-md-6">
                        <label>${a.nama_atribut}${a.is_required ? ' *' : ''}</label>
                        <input type="number" 
                               name="atribut[${a.id_atribut}]" 
                               class="form-control"
                               value="${a.nilai || ''}"
                               ${a.is_required ? 'required' : ''}>
                    </div>
                `;
                    }

                    // Select
                    else if (a.tipe_input === 'select') {
                        let options = '<option value="">-- Pilih --</option>';
                        if (Array.isArray(a.options)) {
                            a.options.forEach(opt => {
                                const selected = (opt == a.nilai) ? 'selected' : '';
                                options += `<option value="${opt}" ${selected}>${opt}</option>`;
                            });
                        }

                        field = `
                    <div class="col-md-6">
                        <label>${a.nama_atribut}${a.is_required ? ' *' : ''}</label>
                        <select name="atribut[${a.id_atribut}]" 
                                class="form-select"
                                ${a.is_required ? 'required' : ''}>
                            ${options}
                        </select>
                    </div>
                `;
                    }

                    wrap.append(field);
                });
            });
        });


        // === Inisialisasi Select2 di modal ===
        $kat.select2({
            theme: 'bootstrap-5',
            dropdownParent: modalEl,
            placeholder: '-- Pilih Kategori --',
            width: '100%',
            allowClear: true
        });

        $sub.select2({
            theme: 'bootstrap-5',
            dropdownParent: modalEl,
            placeholder: '-- Pilih Subkategori --',
            width: '100%',
            allowClear: true
        });

        modalEl.on('shown.bs.modal', () => {
            $kat.trigger('change.select2');
            $sub.trigger('change.select2');
        });

        // === Tombol buka modal ===
        $('#btn-add-master').on('click', function() {
            $('#form-quick-master')[0].reset();
            $('#qm_error, #qm_success').addClass('d-none');
            $kat.val('').trigger('change');
            $sub.html('<option value="">-- Pilih Subkategori --</option>').val('').trigger('change');
            new bootstrap.Modal(modalEl[0]).show();
        });

        // === Kategori → Subkategori ===
        $kat.on('change', function() {
            const idKat = $(this).val();
            $sub.empty().append('<option value="">Memuat...</option>').trigger('change');
            if (!idKat) return;
            $.ajax({
                url: `${BASE}/admin/master-aset/subkategori/${idKat}`,
                dataType: 'json',
                success: function(res) {
                    $sub.empty().append('<option value="">-- Pilih Subkategori --</option>');
                    if (res.ok && Array.isArray(res.items)) {
                        res.items.forEach(it => {
                            const opt = new Option(it.nama_subkategori, it.id_subkategori, false, false);
                            $sub.append(opt);
                        });
                    } else {
                        $sub.append('<option value="">(Tidak ada subkategori)</option>');
                    }
                    $sub.trigger('change');
                },
                error: () => $sub.html('<option value="">(Gagal memuat data)</option>')
            });
        });

        // === Simpan Quick Master (final + update CSRF) ===
        $('#form-quick-master').on('submit', async function(e) {
            e.preventDefault();
            const form = this;

            // Validasi Bootstrap
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            $('#qm_error, #qm_success').addClass('d-none');
            const fd = new FormData(form);
            if (!fd.has(csrfName)) fd.append(csrfName, csrfValue);

            const $btn = $(form).find('button[type="submit"]');
            const originalText = $btn.html();
            $btn.prop('disabled', true).html(`
      <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
      Menyimpan...
    `);

            try {
                const res = await fetch(`${BASE}/admin/master-aset/quick-store`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: fd
                });

                updateCsrfTokenFromResponse(res);

                const js = await res.json();

                if (!res.ok || !js.ok) {
                    const msg = js?.message || (js?.errors ? Object.values(js.errors).join('; ') : 'Data tidak valid.');
                    $('#qm_error').text(msg).removeClass('d-none');
                    return;
                }

                // Berhasil
                const newOpt = new Option(`${js.nama_master} (${js.nama_kategori}/${js.nama_subkategori})`, js.id_master_aset, true, true);
                $('#master_aset').append(newOpt).trigger('change');
                $('#qm_success').text('Master berhasil dibuat.').removeClass('d-none');
                setTimeout(() => bootstrap.Modal.getInstance(modalEl[0]).hide(), 600);

            } catch (err) {
                $('#qm_error').text('Terjadi kesalahan jaringan atau server.').removeClass('d-none');
            } finally {
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });

    // === Validasi Bootstrap 5 + Spinner Loading untuk Form Utama ===
    (() => {
        'use strict';

        const forms = document.querySelectorAll('.needs-validation');

        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                // Cegah submit kalau belum valid
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                    form.classList.add('was-validated');
                    return;
                }

                // Kalau valid → tampilkan spinner
                const btnSubmit = form.querySelector('button[type="submit"]');
                if (btnSubmit) {
                    const originalText = btnSubmit.innerHTML;
                    btnSubmit.disabled = true;
                    btnSubmit.innerHTML = `
          <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
          Menyimpan...
        `;

                    // Simpan teks asli di dataset supaya bisa dipulihkan
                    form.dataset.originalBtnText = originalText;
                }

                // Setelah submit selesai (gunakan event 'submit' async aware)
                form.addEventListener('submitDone', () => {
                    if (btnSubmit) {
                        btnSubmit.disabled = false;
                        btnSubmit.innerHTML = form.dataset.originalBtnText || 'Simpan';
                    }
                });
            }, false);
        });

        // Jika pakai AJAX bisa trigger `form.dispatchEvent(new Event('submitDone'))`
    })();
</script>
<?= $this->endSection(); ?>