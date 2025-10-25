<?= $this->extend('layout/superadmin_template/index'); ?>
<?= $this->section('content'); ?>

<h5 class="mb-3"><?= esc($title ?? 'Tambah Master Aset'); ?></h5>

<?php if (session('error')): ?>
    <div class="alert alert-danger"><?= esc(session('error')) ?></div>
<?php endif; ?>
<?php if (session('success')): ?>
    <div class="alert alert-success"><?= esc(session('success')) ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('superadmin/master-aset/store') ?>" method="post" class="needs-validation" novalidate>
            <?= csrf_field() ?>

            <div class="row g-3">
                <!-- Nama Master -->
                <div class="col-md-6">
                    <label class="form-label">Nama Master *</label>
                    <input type="text"
                        name="nama_master"
                        class="form-control <?= session('errors.nama_master') ? 'is-invalid' : '' ?>"
                        value="<?= old('nama_master', '') ?>"
                        required>
                    <div class="invalid-feedback">
                        <?= session('errors.nama_master') ?: 'Nama master wajib diisi.' ?>
                    </div>
                </div>

                <!-- Expired Default -->
                <div class="col-md-3">
                    <label class="form-label">Expired Default</label>
                    <input type="date"
                        name="expired_default"
                        class="form-control <?= session('errors.expired_default') ? 'is-invalid' : '' ?>"
                        value="<?= old('expired_default', '') ?>">
                    <div class="invalid-feedback">
                        <?= session('errors.expired_default') ?: 'Tanggal tidak valid (YYYY-MM-DD).' ?>
                    </div>
                </div>

                <!-- Nilai Perolehan Default (Rupiah visible + hidden raw) -->
                <div class="col-md-6">
                    <label class="form-label">Nilai Perolehan (Default)</label>
                    <input id="nilai_perolehan_default_fmt" type="text" inputmode="numeric"
                        class="form-control <?= session('errors.nilai_perolehan_default') ? 'is-invalid' : '' ?>"
                        value="">
                    <input id="nilai_perolehan_default" type="hidden" name="nilai_perolehan_default"
                        value="<?= esc(old('nilai_perolehan_default', '')) ?>">
                    <div class="invalid-feedback">
                        <?= session('errors.nilai_perolehan_default') ?: 'Masukkan angka yang valid.' ?>
                    </div>
                </div>

                <!-- Periode (month) -->
                <div class="col-md-6">
                    <label class="form-label">Bulan–Tahun Perolehan (Default)</label>
                    <input type="month"
                        name="periode_perolehan_default_month"
                        class="form-control <?= session('errors.periode_perolehan_default_month') ? 'is-invalid' : '' ?>"
                        value="<?= esc(old('periode_perolehan_default_month', '')) ?>">
                    <div class="invalid-feedback">
                        <?= session('errors.periode_perolehan_default_month') ?: 'Format harus YYYY-MM.' ?>
                    </div>
                </div>

                <!-- Kategori -->
                <div class="col-md-6">
                    <label class="form-label">Kategori *</label>
                    <select id="id_kategori" name="id_kategori"
                        class="form-select <?= session('errors.id_kategori') ? 'is-invalid' : '' ?>" required>
                        <option value="" disabled <?= old('id_kategori') ? '' : 'selected' ?>>-- Pilih --</option>
                        <?php foreach ($kategoris as $k): ?>
                            <option value="<?= $k['id_kategori'] ?>"
                                <?= (string)old('id_kategori') === (string)$k['id_kategori'] ? 'selected' : '' ?>>
                                <?= esc($k['nama_kategori']) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                    <div class="invalid-feedback">
                        <?= session('errors.id_kategori') ?: 'Silakan pilih kategori.' ?>
                    </div>
                </div>

                <!-- Subkategori -->
                <div class="col-md-6">
                    <label class="form-label">Subkategori *</label>
                    <select id="id_subkategori" name="id_subkategori"
                        class="form-select <?= session('errors.id_subkategori') ? 'is-invalid' : '' ?>" required>
                        <option value="" disabled selected>-- Pilih Kategori dahulu --</option>
                    </select>
                    <div class="invalid-feedback">
                        <?= session('errors.id_subkategori') ?: 'Silakan pilih subkategori.' ?>
                    </div>
                </div>

                <!-- Default atribut -->
                <div class="col-12">
                    <hr>
                    <h6 class="mb-2">Default Atribut (ikut Subkategori)</h6>
                    <div class="row g-3" id="attr-box">
                        <div class="col-12 text-muted">Pilih subkategori untuk memuat atribut.</div>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?= base_url('superadmin/master-aset') ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

<!-- JS: dropdown subkategori + atribut + formatter rupiah + bootstrap validation -->
<script>
    (() => {
        const BASE = '<?= base_url() ?>';
        const selKat = document.getElementById('id_kategori');
        const selSub = document.getElementById('id_subkategori');
        const box = document.getElementById('attr-box');

        function escapeHtml(str) {
            if (str === null || str === undefined) return '';
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;')
                .replace(/>/g, '&gt;').replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function toArray(maybe) {
            try {
                if (Array.isArray(maybe)) return maybe;
                if (maybe === null || maybe === undefined || maybe === '') return [];
                if (typeof maybe === 'string') {
                    const parsed = JSON.parse(maybe);
                    return Array.isArray(parsed) ? parsed : [];
                }
                return Array.isArray(maybe) ? maybe : [];
            } catch (e) {
                return [];
            }
        }

        async function loadSub(kid, pre = null) {
            selSub.innerHTML = '<option>Memuat...</option>';
            try {
                const r = await fetch(`${BASE}/superadmin/subkategori/by-kategori/${kid}`);
                const js = await r.json();
                selSub.innerHTML = '<option value="" disabled selected>-- Pilih Subkategori --</option>';
                js.forEach(s => {
                    const o = document.createElement('option');
                    o.value = s.id_subkategori;
                    o.textContent = s.nama_subkategori;
                    if (String(pre || '') === String(s.id_subkategori)) o.selected = true;
                    selSub.appendChild(o);
                });
                if (pre) await loadAttrs(pre);
            } catch (e) {
                console.error(e);
                selSub.innerHTML = '<option>Gagal memuat</option>';
            }
        }

        async function loadAttrs(sid) {
            box.innerHTML = '<div class="col-12">Memuat atribut...</div>';
            try {
                const r = await fetch(`${BASE}/superadmin/atribut/by-subkategori/${sid}`);
                const attrs = await r.json();
                box.innerHTML = '';
                if (!attrs.length) {
                    box.innerHTML = '<div class="col-12 text-muted">Tidak ada atribut.</div>';
                    return;
                }

                const preset = <?= json_encode(old('default_atribut', [])) ?>;

                attrs.forEach(a => {
                    const col = document.createElement('div');
                    col.className = 'col-md-6';

                    const name = `default_atribut[${a.id_atribut}]`;
                    const star = a.is_required ? ' *' : '';
                    const satuan = a.satuan ? ` <small class="text-muted">(${escapeHtml(a.satuan)})</small>` : '';

                    const preVal = preset[String(a.id_atribut)] ?? '';
                    const safeVal = escapeHtml(preVal);

                    const options = toArray(a.options) || toArray(a.options_json);

                    let html = '';
                    if (a.tipe_input === 'number') {
                        html = `<input type="number" step="any" class="form-control" name="${name}" value="${safeVal}">`;
                    } else if (a.tipe_input === 'date') {
                        html = `<input type="date" class="form-control" name="${name}" value="${safeVal}">`;
                    } else if (a.tipe_input === 'select' && Array.isArray(options)) {
                        const opts = options.map(o => {
                            const sel = (String(o) === String(preVal)) ? 'selected' : '';
                            return `<option value="${escapeHtml(o)}" ${sel}>${escapeHtml(o)}</option>`;
                        }).join('');
                        html = `<select class="form-select" name="${name}">${opts}</select>`;
                    } else if (a.tipe_input === 'textarea') {
                        html = `<textarea class="form-control" name="${name}" rows="3">${safeVal}</textarea>`;
                    } else {
                        html = `<input type="text" class="form-control" name="${name}" value="${safeVal}">`;
                    }

                    col.innerHTML = `<label class="form-label">${escapeHtml(a.nama_atribut)}${star}${satuan}</label>${html}`;
                    box.appendChild(col);
                });
            } catch (e) {
                console.error(e);
                box.innerHTML = '<div class="col-12 text-danger">Gagal memuat atribut.</div>';
            }
        }

        selKat.addEventListener('change', () => {
            if (selKat.value) loadSub(selKat.value, null);
        });
        selSub.addEventListener('change', () => {
            if (selSub.value) loadAttrs(selSub.value);
        });

        // Restore old()
        (function restoreKS() {
            const OLD_K = '<?= old('id_kategori', '') ?>';
            const OLD_S = '<?= old('id_subkategori', '') ?>';
            if (OLD_K) {
                selKat.value = OLD_K;
                loadSub(OLD_K, OLD_S || null);
            }
        })();

        // === Formatter Rupiah (visible text + hidden raw) ===
        const hiddenNilai = document.getElementById('nilai_perolehan_default');
        const inputNilai = document.getElementById('nilai_perolehan_default_fmt');

        function toRupiah(n) {
            let s = String(n ?? '').trim();
            if (!s) return '';
            s = s.replace(/[^\d.,-]/g, '').replace(/,/g, '.');
            let neg = s.startsWith('-');
            if (neg) s = s.slice(1);
            let [intPart, decPart] = s.split('.');
            intPart = (intPart || '0').replace(/^0+(?!$)/, '');
            intPart = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            let out = 'Rp ' + intPart;
            if (decPart) out += ',' + decPart.slice(0, 2);
            if (neg) out = '-' + out;
            return out;
        }

        function parseRupiah(str) {
            if (!str) return '';
            let s = String(str).replace(/[^0-9,.-]/g, '').replace(/\./g, '');
            let neg = s.startsWith('-');
            if (neg) s = s.slice(1);
            let [intPart, decPart] = s.split(',');
            intPart = intPart || '0';
            let out = intPart + (decPart ? '.' + decPart.slice(0, 2) : '');
            if (neg) out = '-' + out;
            return out;
        }

        (function initRupiah() {
            inputNilai.value = toRupiah(hiddenNilai.value);
            inputNilai.addEventListener('input', function() {
                const before = this.value;
                const caret = this.selectionStart || before.length;
                const numeric = parseRupiah(before);
                hiddenNilai.value = numeric;
                const formatted = toRupiah(numeric);
                this.value = formatted;
                const diff = formatted.length - before.length;
                const newCaret = Math.max(0, caret + diff);
                this.setSelectionRange(newCaret, newCaret);
            });
            inputNilai.addEventListener('blur', function() {
                if (this.value.trim() === '') hiddenNilai.value = '';
            });
        })();

        // Bootstrap validation
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

<?= $this->endSection(); ?>