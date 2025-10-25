<?= $this->extend('layout/superadmin_template/index'); ?>
<?= $this->section('content'); ?>



<div class="card">
    <div class="card-body">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= esc(session()->getFlashdata('error')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= esc(session()->getFlashdata('success')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('superadmin/aset/store') ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            <?= csrf_field() ?>

            <div class="row g-3">
                <!-- Nama Aset (ambil dari master) -->
                <div class="col-12">
                    <div class="d-flex align-items-start justify-content-between mb-1">
                        <label class="form-label mb-0">Nama Aset*</label>
                        <small class="text-muted">Pilih dari daftar atau buat master baru</small>
                    </div>
                    <div class="input-group">
                        <select id="id_master_aset" name="id_master_aset"
                            class="form-select <?= session('errors.id_master_aset') ? 'is-invalid' : '' ?>" required
                            aria-label="Pilih Nama Aset (Master)">
                            <option value="" disabled <?= old('id_master_aset') ? '' : 'selected' ?>>-- Pilih Nama Aset--</option>
                            <?php foreach ($masters as $m): ?>
                                <option value="<?= $m['id_master_aset'] ?>" <?= old('id_master_aset') == $m['id_master_aset'] ? 'selected' : '' ?>>
                                    <?= esc($m['nama_master']) ?> (<?= esc($m['nama_kategori'] ?? '-') ?>/<?= esc($m['nama_subkategori'] ?? '-') ?>)
                                </option>
                            <?php endforeach ?>
                        </select>
                        <button type="button" class="btn btn-outline-primary" id="btn-add-master" title="Tambah master baru">
                            + Master Baru
                        </button>
                    </div>


                    <div class="form-text mt-1">
                        Gunakan tombol "Master Baru" untuk menambahkan entry cepat tanpa meninggalkan halaman.
                    </div>

                    <div class="invalid-feedback">
                        <?= session('errors.id_master_aset') ?: 'Silakan pilih nama aset dari master.' ?>
                    </div>
                </div>


                <!-- Kategori/Subkategori (display-only) -->
                <div class="col-md-6">
                    <label class="form-label">Kategori</label>
                    <div id="nama_kategori" class="form-control-plaintext border rounded px-2 py-2 bg-light">—</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Subkategori</label>
                    <div id="nama_subkategori" class="form-control-plaintext border rounded px-2 py-2 bg-light">—</div>
                </div>

                <!-- Info dari master (display-only) -->
                <div class="col-md-6">
                    <label class="form-label">Nilai Perolehan</label>
                    <div id="master_nilai" class="form-control-plaintext border rounded px-2 py-2 bg-light">—</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Bulan–Tahun Perolehan</label>
                    <div id="master_periode" class="form-control-plaintext border rounded px-2 py-2 bg-light">—</div>
                </div>

                <!-- Cabang -->
                <?php if (in_groups('superadmin')): ?>
                    <div class="col-md-12">
                        <label class="form-label">Cabang *</label>
                        <select name="id_cabang" class="form-select <?= session('errors.id_cabang') ? 'is-invalid' : '' ?>" required>
                            <option value="" disabled <?= old('id_cabang') ? '' : 'selected' ?>>-- Pilih Cabang --</option>
                            <?php foreach ($cabangs as $c): ?>
                                <option value="<?= $c['id_cabang'] ?>" <?= old('id_cabang') == $c['id_cabang'] ? 'selected' : '' ?>>
                                    <?= esc($c['nama_cabang']) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                        <div class="invalid-feedback"><?= session('errors.id_cabang') ?: 'Silakan pilih cabang.' ?></div>
                    </div>
                <?php else: ?>
                    <input type="hidden" name="id_cabang" value="<?= user()->id_cabang ?>">
                <?php endif; ?>

                <!-- Stock -->
                <div class="col-md-12">
                    <label class="form-label">Jumlah / Stok Aset *</label>
                    <input type="number"
                        name="stock"
                        min="1"
                        class="form-control <?= session('errors.stock') ? 'is-invalid' : '' ?>"
                        value="<?= old('stock', 1) ?>">
                    <div class="form-text">
                        Angka ini akan <strong>ditambahkan</strong> ke stok yang sudah ada bila pilihan “Tambah stok jika sudah ada” aktif.
                    </div>

                    <div class="invalid-feedback">
                        <?= session('errors.stock') ?: 'Masukkan jumlah aset minimal 1.' ?>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="merge_if_exists" id="merge_if_exists"
                            value="1" <?= old('merge_if_exists', '1') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="merge_if_exists">
                            Tambah stok jika aset master ini sudah ada di cabang ini (atau pulihkan jika sedang diarsip)
                        </label>
                    </div>
                    <div class="form-text">
                        Jika dinyalakan: sistem tidak membuat baris baru, melainkan menambah stok (atau memulihkan baris yang diarsip).
                        Jika dimatikan: sistem menolak penyimpanan bila baris sudah ada—kamu akan melihat pesan error.
                    </div>
                </div>


                <!-- Kondisi & Status -->
                <div class="col-md-6">
                    <label class="form-label">Kondisi *</label>
                    <select name="kondisi" class="form-select <?= session('errors.kondisi') ? 'is-invalid' : '' ?>" required>
                        <option value="" disabled <?= old('kondisi') ? '' : 'selected' ?>>-- Pilih Kondisi --</option>
                        <option value="Baik" <?= old('kondisi') == 'Baik' ? 'selected' : '' ?>>Baik</option>
                        <option value="Rusak Ringan" <?= old('kondisi') == 'Rusak Ringan' ? 'selected' : '' ?>>Rusak Ringan</option>
                        <option value="Rusak Berat" <?= old('kondisi') == 'Rusak Berat' ? 'selected' : '' ?>>Rusak Berat</option>
                    </select>
                    <div class="invalid-feedback"><?= session('errors.kondisi') ?: 'Silakan pilih kondisi.' ?></div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select <?= session('errors.status') ? 'is-invalid' : '' ?>" required>
                        <option value="" disabled <?= old('status') ? '' : 'selected' ?>>-- Pilih Status --</option>
                        <option value="Digunakan" <?= old('status') == 'Digunakan' ? 'selected' : '' ?>>Digunakan</option>
                        <option value="Tidak Digunakan" <?= old('status') == 'Tidak Digunakan' ? 'selected' : '' ?>>Tidak Digunakan</option>
                        <option value="Hilang" <?= old('status') == 'Hilang' ? 'selected' : '' ?>>Hilang</option>
                    </select>
                    <div class="invalid-feedback"><?= session('errors.status') ?: 'Silakan pilih status.' ?></div>
                </div>

                <!-- Posisi -->
                <div class="col-md-6">
                    <label class="form-label">Posisi/Lokasi Saat Ini</label>
                    <input type="text" name="posisi" class="form-control" value="<?= old('posisi') ?>" placeholder="Contoh: Gudang A / Yard 3">
                </div>

                <!-- Gambar -->
                <div class="col-md-6">
                    <label class="form-label">Gambar Aset</label>
                    <input type="file" name="gambar" class="form-control <?= session('errors.gambar') ? 'is-invalid' : '' ?>" accept="image/*">
                    <div class="invalid-feedback"><?= session('errors.gambar') ?: 'File gambar tidak valid.' ?></div>
                </div>

                <!-- Atribut (auto isi & dikunci mengikuti master) -->
                <div class="col-12">
                    <hr>
                    <h6 class="mb-2">Detail Atribut</h6>
                    <div class="row g-3" id="dynamic-fields"></div>
                </div>

                <!-- Keterangan -->
                <div class="col-12">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3"><?= old('keterangan') ?></textarea>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary me-2">Simpan</button>
                <button type="reset" class="btn btn-danger me-2">Reset</button>
                <a href="<?= base_url('superadmin/aset') ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Master -->
<div class="modal fade" id="modalMasterBaru" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form id="form-quick-master" class="needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Master Aset</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Nama Master *</label>
                            <input type="text" name="nama_master" class="form-control" required>
                            <div class="invalid-feedback">Nama master wajib diisi</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Expired (opsional)</label>
                            <input type="date" name="expired_default" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kategori *</label>
                            <select name="id_kategori" id="qm_id_kategori" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Kategori --</option>
                                <?php foreach (($kategoris ?? []) as $k): ?>
                                    <option value="<?= (int)$k['id_kategori'] ?>">
                                        <?= esc($k['nama_kategori']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Kategori wajib dipilih</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Subkategori *</label>
                            <select name="id_subkategori" id="qm_id_subkategori" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Subkategori --</option>
                            </select>
                            <div class="invalid-feedback">Subkategori wajib dipilih</div>
                        </div>


                        <div class="col-md-6">
                            <label class="form-label">Nilai Perolehan Default (opsional)</label>
                            <input type="number" step="0.01" name="nilai_perolehan_default" class="form-control" placeholder="Contoh: 2500000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Periode Perolehan Default (Bulan) (opsional)</label>
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



<script>
    (() => {
        const BASE = '<?= base_url() ?>';

        const selMaster = document.getElementById('id_master_aset');
        const nmKat = document.getElementById('nama_kategori');
        const nmSub = document.getElementById('nama_subkategori');
        const masterNilai = document.getElementById('master_nilai');
        const masterPeriode = document.getElementById('master_periode');
        const fieldsBox = document.getElementById('dynamic-fields');

        // ---- Helpers ----
        function setText(el, val = '') {
            if (!el) return;
            el.textContent = (val ?? '') === '' ? '—' : String(val);
        }

        function formatRupiah(value, opts = {}) {
            const num = Number(value);
            if (!isFinite(num)) return '—';
            const decimals = opts.decimals ?? 'auto';
            let min = 0,
                max = 2;
            if (decimals === 'auto') {
                const hasFrac = Math.round(num * 100) % 100 !== 0;
                min = hasFrac ? 2 : 0;
                max = 2;
            } else if (typeof decimals === 'number') {
                min = max = decimals;
            }
            const s = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: min,
                maximumFractionDigits: max,
            }).format(num);
            return s.replace('Rp', 'Rp ');
        }

        function toArray(maybe) {
            try {
                if (Array.isArray(maybe)) return maybe;
                if (maybe === null || maybe === undefined || maybe === '') return [];
                if (typeof maybe === 'string') {
                    const parsed = JSON.parse(maybe);
                    return Array.isArray(parsed) ? parsed : [];
                }
                return [];
            } catch {
                return [];
            }
        }

        function renderAtribut(attrs) {
            fieldsBox.innerHTML = '';
            if (!attrs || !attrs.length) {
                fieldsBox.innerHTML = '<div class="col-12 text-muted">Tidak ada atribut untuk master ini.</div>';
                return;
            }
            attrs.forEach(a => {
                const col = document.createElement('div');
                col.className = 'col-md-6';
                const star = a.is_required ? ' *' : '';
                const satuan = a.satuan ? ` <small class="text-muted">(${a.satuan})</small>` : '';
                const label = `<label class="form-label">${a.nama_atribut}${star}${satuan}</label>`;

                const val = a.nilai ?? '';
                let fieldHTML = '';
                if (a.tipe_input === 'number') {
                    fieldHTML = `<input type="number" step="any" class="form-control" value="${String(val).replace(/"/g,'&quot;')}" disabled>`;
                } else if (a.tipe_input === 'date') {
                    fieldHTML = `<input type="date" class="form-control" value="${String(val).replace(/"/g,'&quot;')}" disabled>`;
                } else if (a.tipe_input === 'select') {
                    const opts = (toArray(a.options) || toArray(a.options_json)).map(o => {
                        const sel = (String(o) === String(val)) ? 'selected' : '';
                        return `<option ${sel}>${String(o).replace(/</g,'&lt;').replace(/>/g,'&gt;')}</option>`;
                    }).join('');
                    fieldHTML = `<select class="form-select" disabled>${opts}</select>`;
                } else if (a.tipe_input === 'textarea') {
                    fieldHTML = `<textarea class="form-control" rows="3" disabled>${String(val).replace(/</g,'&lt;').replace(/>/g,'&gt;')}</textarea>`;
                } else {
                    fieldHTML = `<input type="text" class="form-control" value="${String(val).replace(/"/g,'&quot;')}" disabled>`;
                }
                col.innerHTML = label + fieldHTML;
                fieldsBox.appendChild(col);
            });
        }

        async function loadMasterDetail(id) {
            if (!id) return;
            fieldsBox.innerHTML = '<div class="col-12">Memuat data master...</div>';
            try {
                const res = await fetch(`${BASE}/superadmin/aset/ajax-master-detail/${id}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const js = await res.json();
                if (!js.ok) throw new Error(js.message || 'Gagal memuat master');

                setText(nmKat, js.nama_kategori ?? '—');
                setText(nmSub, js.nama_subkategori ?? '—');

                // Nilai → rupiah
                setText(
                    masterNilai,
                    (js.nilai_default !== null && js.nilai_default !== '') ? formatRupiah(js.nilai_default) : '—'
                );

                // Periode YYYY-MM-01 → YYYY-MM
                setText(masterPeriode, js.periode_default ? js.periode_default.slice(0, 7) : '—');

                renderAtribut(js.atribut);
            } catch (e) {
                console.error(e);
                fieldsBox.innerHTML = '<div class="col-12 text-danger">Gagal memuat data master.</div>';
                setText(nmKat, '');
                setText(nmSub, '');
                setText(masterNilai, '');
                setText(masterPeriode, '');
            }
        }

        selMaster.addEventListener('change', function() {
            loadMasterDetail(this.value);
        });

        // restore old() kalau ada
        document.addEventListener('DOMContentLoaded', function() {
            const oldMaster = '<?= old('id_master_aset') ?>';
            if (oldMaster) loadMasterDetail(oldMaster);
        });
    })();
</script>

<!-- Bootstrap validation -->
<script>
    (() => {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', e => {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>

<!-- Modal Master Pintasan -->
<script>
    (function() {
        const BASE = '<?= base_url() ?>';
        const csrfName = '<?= csrf_token() ?>';
        let csrfValue = '<?= csrf_hash() ?>';

        const btnAdd = document.getElementById('btn-add-master');
        const modalEl = document.getElementById('modalMasterBaru');
        const formQM = document.getElementById('form-quick-master');

        const selKat = document.getElementById('qm_id_kategori');
        const selSub = document.getElementById('qm_id_subkategori');

        const errBox = document.getElementById('qm_error');
        const okBox = document.getElementById('qm_success');
        const selMaster = document.getElementById('id_master_aset');

        let bsModal = null;

        function ensureModal() {
            if (!bsModal) bsModal = new bootstrap.Modal(modalEl, {
                backdrop: 'static'
            });
        }

        function showErr(msg) {
            errBox.textContent = msg || 'Terjadi kesalahan.';
            errBox.classList.remove('d-none');
            okBox.classList.add('d-none');
        }

        function showOk(msg) {
            okBox.textContent = msg || 'Berhasil.';
            okBox.classList.remove('d-none');
            errBox.classList.add('d-none');
        }

        function clearMsg() {
            errBox.classList.add('d-none');
            okBox.classList.add('d-none');
            errBox.textContent = '';
            okBox.textContent = '';
        }

        // === buka modal ===
        btnAdd?.addEventListener('click', () => {
            ensureModal();
            formQM.reset();
            formQM.classList.remove('was-validated');
            clearMsg();
            selSub.innerHTML = '<option value="" selected disabled>-- Pilih Subkategori --</option>';
            bsModal.show();
        });

        // === load subkategori ===
        selKat?.addEventListener('change', async function() {
            const idKat = this.value;
            selSub.innerHTML = '<option value="" selected disabled>Memuat...</option>';
            try {
                const res = await fetch(`${BASE}/superadmin/master-aset/subkategori/${idKat}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest' // penting agar isAJAX() true
                    }
                });
                const js = await res.json();
                selSub.innerHTML = '<option value="" selected disabled>-- Pilih Subkategori --</option>';
                if (js.ok && Array.isArray(js.items) && js.items.length) {
                    js.items.forEach(it => {
                        const opt = document.createElement('option');
                        opt.value = it.id_subkategori;
                        opt.textContent = it.nama_subkategori;
                        selSub.appendChild(opt);
                    });
                } else {
                    selSub.innerHTML = '<option value="" selected disabled>Tidak ada subkategori</option>';
                }
            } catch (e) {
                selSub.innerHTML = '<option value="" selected disabled>Gagal memuat subkategori</option>';
            }
        });

        // === submit quick master ===
        formQM?.addEventListener('submit', async function(e) {
            e.preventDefault();

            if (!formQM.checkValidity()) {
                formQM.classList.add('was-validated');
                return;
            }

            clearMsg();

            const fd = new FormData(formQM);
            if (!fd.has(csrfName)) fd.append(csrfName, csrfValue);

            try {
                const res = await fetch(`${BASE}/superadmin/master-aset/quick-store`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest' // penting juga di sini
                    },
                    body: fd
                });

                const newToken = res.headers.get('X-CSRF-TOKEN');
                if (newToken) csrfValue = newToken;

                const js = await res.json();

                if (!res.ok || !js.ok) {
                    const msg = js?.message || (js?.errors ? Object.values(js.errors).join('; ') : 'Gagal menyimpan master.');
                    showErr(msg);
                    return;
                }

                // sukses -> tambahkan ke select utama
                const label = `${js.nama_master} (${js.nama_kategori ?? '-'}\/${js.nama_subkategori ?? '-'})`;
                const opt = new Option(label, js.id_master_aset, true, true);
                selMaster.add(opt);
                selMaster.dispatchEvent(new Event('change'));

                showOk('Master berhasil dibuat dan dipilih.');
                setTimeout(() => bsModal.hide(), 600);
            } catch (err) {
                showErr('Terjadi kesalahan jaringan/server. Coba lagi.');
            }
        });
    })();
</script>

<?= $this->endSection(); ?>