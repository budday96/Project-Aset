<?= $this->extend('layout/superadmin_template/index'); ?>
<?= $this->section('content'); ?>

<div class="card">
    <div class="card-body">
        <?php if (session('error')): ?>
            <div class="alert alert-danger mb-3"><?= esc(session('error')) ?></div>
        <?php endif; ?>
        <?php if (session('success')): ?>
            <div class="alert alert-success mb-3"><?= esc(session('success')) ?></div>
        <?php endif; ?>

        <?php
        // $row: data aset (join kategori/sub/master/cabang bila perlu)
        // $masters: list master aktif (boleh juga tambahkan current master bila arsip)
        // $cabangs: list cabang (untuk superadmin)
        ?>

        <form action="<?= base_url('superadmin/aset/update/' . (int)$row['id_aset']) ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            <?= csrf_field() ?>

            <div class="row g-3">

                <!-- Master (READ ONLY) -->
                <div class="col-12">
                    <label class="form-label">Nama Aset (Master)</label>

                    <!-- HIDDEN INPUT agar tetap terkirim ke controller -->
                    <input type="hidden" name="id_master_aset" value="<?= $row['id_master_aset'] ?>">

                    <!-- SELECT DISABLED (tetap punya ID agar JS tetap bekerja) -->
                    <select id="id_master_aset" class="form-select" disabled>
                        <option value="<?= $row['id_master_aset'] ?>">
                            <?= esc($row['nama_master']) ?>
                            (<?= esc($row['nama_kategori'] ?? '-') ?>/<?= esc($row['nama_subkategori'] ?? '-') ?>)
                        </option>
                    </select>

                    <small class="text-muted">Master aset tidak dapat diubah.</small>
                </div>


                <!-- Display-only -->
                <div class="col-md-6">
                    <label class="form-label">Kategori</label>
                    <div id="nama_kategori" class="form-control-plaintext border rounded px-2 py-2 bg-light">—</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Subkategori</label>
                    <div id="nama_subkategori" class="form-control-plaintext border rounded px-2 py-2 bg-light">—</div>
                </div>

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
                            <?php foreach ($cabangs as $c): ?>
                                <option value="<?= $c['id_cabang'] ?>" <?= (string)old('id_cabang', $row['id_cabang'] ?? '') === (string)$c['id_cabang'] ? 'selected' : '' ?>>
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
                <div class="col-md-6">
                    <label class="form-label">Stock *</label>
                    <input type="number" name="stock" class="form-control <?= session('errors.stock') ? 'is-invalid' : '' ?>"
                        value="<?= old('stock', $row['stock'] ?? '') ?>" min="1" required>
                    <div class="invalid-feedback"><?= session('errors.stock') ?: 'Silakan masukkan stock aset (minimal 1).' ?></div>
                </div>

                <!-- Kondisi & Status -->
                <div class="col-md-6">
                    <label class="form-label">Kondisi *</label>
                    <select name="kondisi" class="form-select <?= session('errors.kondisi') ? 'is-invalid' : '' ?>" required>
                        <?php $kOld = old('kondisi', $row['kondisi'] ?? ''); ?>
                        <option value="" disabled <?= $kOld ? '' : 'selected' ?>>-- Pilih Kondisi --</option>
                        <option value="Baik" <?= $kOld === 'Baik' ? 'selected' : '' ?>>Baik</option>
                        <option value="Rusak Ringan" <?= $kOld === 'Rusak Ringan' ? 'selected' : '' ?>>Rusak Ringan</option>
                        <option value="Rusak Berat" <?= $kOld === 'Rusak Berat' ? 'selected' : '' ?>>Rusak Berat</option>
                    </select>
                    <div class="invalid-feedback"><?= session('errors.kondisi') ?: 'Silakan pilih kondisi.' ?></div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select <?= session('errors.status') ? 'is-invalid' : '' ?>" required>
                        <?php $sOld = old('status', $row['status'] ?? ''); ?>
                        <option value="" disabled <?= $sOld ? '' : 'selected' ?>>-- Pilih Status --</option>
                        <option value="Digunakan" <?= $sOld === 'Digunakan' ? 'selected' : '' ?>>Digunakan</option>
                        <option value="Tidak Digunakan" <?= $sOld === 'Tidak Digunakan' ? 'selected' : '' ?>>Tidak Digunakan</option>
                        <option value="Hilang" <?= $sOld === 'Hilang' ? 'selected' : '' ?>>Hilang</option>
                    </select>
                    <div class="invalid-feedback"><?= session('errors.status') ?: 'Silakan pilih status.' ?></div>
                </div>

                <!-- Posisi -->
                <div class="col-md-6">
                    <label class="form-label">Posisi/Lokasi Saat Ini</label>
                    <input type="text" name="posisi" class="form-control" value="<?= old('posisi', $row['posisi'] ?? '') ?>" placeholder="Contoh: Gudang A / Yard 3">
                </div>

                <!-- Gambar -->
                <div class="col-md-6">
                    <label class="form-label">Gambar Aset</label>
                    <?php if (!empty($row['gambar'])): ?>
                        <div class="mb-2">
                            <img src="<?= base_url('uploads/aset/' . $row['gambar']) ?>" alt="gambar" class="img-thumbnail" style="max-height:120px">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="gambar" class="form-control <?= session('errors.gambar') ? 'is-invalid' : '' ?>" accept="image/*">
                    <div class="invalid-feedback"><?= session('errors.gambar') ?: 'File gambar tidak valid.' ?></div>
                </div>

                <!-- Atribut (preview dari master) -->
                <div class="col-12">
                    <hr>
                    <h6 class="mb-2">Detail Atribut (dari Master)</h6>
                    <div class="row g-3" id="dynamic-fields"></div>
                    <small class="text-muted">Atribut dikunci mengikuti master.</small>
                </div>

                <!-- Keterangan -->
                <div class="col-12">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3"><?= old('keterangan', $row['keterangan'] ?? '') ?></textarea>
                </div>

            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary me-2">Simpan Perubahan</button>
                <a href="<?= base_url('superadmin/aset') ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    (() => {
        const BASE = '<?= base_url() ?>';
        const selMaster = document.getElementById('id_master_aset');
        const nmKat = document.getElementById('nama_kategori');
        const nmSub = document.getElementById('nama_subkategori');
        const masterNilai = document.getElementById('master_nilai');
        const masterPeriode = document.getElementById('master_periode');
        const fieldsBox = document.getElementById('dynamic-fields');

        function formatIDR(n) {
            if (n === null || n === undefined || n === '') return '—';
            const num = Number(n);
            if (Number.isNaN(num)) return '—';
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 2
            }).format(num);
        }

        function setText(el, val = '') {
            if (el) el.textContent = (val ?? '') === '' ? '—' : String(val);
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
                const satuan = a.satuan ? ` <small class="text-muted">(${String(a.satuan).replace(/</g,'&lt;').replace(/>/g,'&gt;')})</small>` : '';
                const label = `<label class="form-label">${String(a.nama_atribut).replace(/</g,'&lt;').replace(/>/g,'&gt;')}${star}${satuan}</label>`;
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
                const res = await fetch(`${BASE}/superadmin/aset/ajax-master-detail/${id}`);
                const js = await res.json();
                if (!js.ok) throw new Error(js.message || 'Gagal memuat master');

                setText(nmKat, js.nama_kategori ?? '—');
                setText(nmSub, js.nama_subkategori ?? '—');
                setText(masterNilai, formatIDR(js.nilai_default));
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

        document.addEventListener('DOMContentLoaded', function() {
            const current = '<?= old('id_master_aset', $row['id_master_aset'] ?? '') ?>';
            if (current) loadMasterDetail(current);
        });
    })();

    // 
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
<?= $this->endSection(); ?>