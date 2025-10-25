<?= $this->extend('layout/superadmin_template/index'); ?>
<?= $this->section('content'); ?>
<?php helper('auth'); ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= esc(session()->getFlashdata('error')); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('superadmin/mutasi/store'); ?>" method="post" autocomplete="off">
            <?= csrf_field(); ?>

            <?php if (in_groups('superadmin')): ?>
                <!-- Cabang Asal (Superadmin memilih) -->
                <div class="mb-3">
                    <label for="dari_cabang" class="form-label">Cabang Asal <span class="text-danger">*</span></label>
                    <select name="dari_cabang" id="dari_cabang" class="form-select" required>
                        <option value="" disabled <?= old('dari_cabang') ? '' : 'selected' ?>>Pilih cabang asal...</option>
                        <?php foreach ($cabangs as $c): ?>
                            <option value="<?= esc($c['id_cabang']); ?>" <?= (string)old('dari_cabang') === (string)$c['id_cabang'] ? 'selected' : ''; ?>>
                                <?= esc($c['nama_cabang']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text">Aset akan dimuat otomatis berdasarkan cabang asal.</div>
                </div>
            <?php else: ?>
                <!-- Non-superadmin (fallback): cabang asal = cabang user, tidak ditampilkan -->
                <input type="hidden" name="dari_cabang" value="<?= esc(user()->id_cabang); ?>">
            <?php endif; ?>

            <!-- Pilih Aset (dinamis utk superadmin; langsung terisi utk non-superadmin) -->
            <div class="mb-3">
                <label for="id_aset" class="form-label">Aset <span class="text-danger">*</span></label>
                <select name="id_aset" id="id_aset" class="form-select" required <?= (in_groups('superadmin') && !old('dari_cabang')) ? 'disabled' : ''; ?>>
                    <?php if (in_groups('superadmin')): ?>
                        <option value="" disabled <?= old('id_aset') ? '' : 'selected' ?>>Pilih cabang asal dulu...</option>
                        <?php if (!empty($asets)): ?>
                            <?php foreach ($asets as $a): ?>
                                <option value="<?= esc($a['id_aset']); ?>" <?= (string)old('id_aset') === (string)$a['id_aset'] ? 'selected' : ''; ?>>
                                    <?= esc($a['nama_master'] ?? $a['nama_aset'] ?? '-') ?> (Kode: <?= esc($a['kode_aset'] ?? $a['id_aset']); ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <option value="" disabled <?= old('id_aset') ? '' : 'selected' ?>>Pilih aset...</option>
                        <?php foreach ($asets as $a): ?>
                            <option value="<?= esc($a['id_aset']); ?>" <?= (string)old('id_aset') === (string)$a['id_aset'] ? 'selected' : ''; ?>>
                                <?= esc($a['nama_master'] ?? $a['nama_aset'] ?? '-') ?> (Kode: <?= esc($a['kode_aset'] ?? $a['id_aset']); ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </select>
                <div class="form-text">Hanya aset milik cabang asal yang bisa dimutasi.</div>
            </div>

            <!-- Cabang Tujuan -->
            <div class="mb-3">
                <label for="ke_cabang" class="form-label">Cabang Tujuan <span class="text-danger">*</span></label>
                <select name="ke_cabang" id="ke_cabang" class="form-select" required>
                    <option value="" disabled <?= old('ke_cabang') ? '' : 'selected' ?>>Pilih cabang tujuan...</option>
                    <?php foreach ($cabangs as $c): ?>
                        <option value="<?= esc($c['id_cabang']); ?>" <?= (string)old('ke_cabang') === (string)$c['id_cabang'] ? 'selected' : ''; ?>>
                            <?= esc($c['nama_cabang']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">Cabang tujuan tidak boleh sama dengan cabang asal.</div>
            </div>

            <div class="mb-3">
                <label for="qty" class="form-label">Jumlah Mutasi <span class="text-danger">*</span></label>
                <input type="number" name="qty" id="qty" class="form-control" min="1" required
                    placeholder="Masukkan jumlah aset yang akan dimutasi..."
                    value="<?= old('qty', 1) ?>">
                <div class="form-text">Masukkan jumlah unit aset yang ingin dipindahkan (minimal 1).</div>
            </div>


            <!-- Keterangan -->
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="3" class="form-control" placeholder="Alasan mutasi atau catatan lain..."><?= esc(old('keterangan')); ?></textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="<?= base_url('superadmin/mutasi'); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-1"></i> Ajukan Mutasi
                </button>
            </div>
        </form>
    </div>

    <div class="card-footer">
        <small class="text-muted">
            Setelah diajukan, status akan <strong>pending</strong>.
            Cabang asal dapat <em>kirim</em> atau <em>batalkan</em>.
            Cabang tujuan dapat <em>terima</em> saat status <strong>pending/dikirim</strong>.
        </small>
    </div>
</div>

<!-- JS: muat aset ketika cabang asal dipilih & cegah tujuan sama dg asal -->
<script>
    (function() {
        const BASE = "<?= base_url(); ?>";
        const isSuperadmin = <?= in_groups('superadmin') ? 'true' : 'false'; ?>;

        const dariSel = document.getElementById('dari_cabang');
        const asetSel = document.getElementById('id_aset');
        const keSel = document.getElementById('ke_cabang');

        function setLoadingSelect(selectEl, text) {
            selectEl.innerHTML = '';
            const opt = document.createElement('option');
            opt.value = '';
            opt.disabled = true;
            opt.selected = true;
            opt.textContent = text;
            selectEl.appendChild(opt);
        }

        function disableKeCabangEqualToDari() {
            if (!keSel) return;
            const dariVal = dariSel ? dariSel.value : "<?= esc(old('dari_cabang') ?? (in_groups('superadmin') ? '' : (user()->id_cabang ?? ''))); ?>";
            let changed = false;

            [...keSel.options].forEach(opt => {
                if (!opt.value) return;
                if (opt.value === dariVal) {
                    opt.disabled = true;
                    // Jika ke == dari, reset pilihan
                    if (keSel.value === opt.value) {
                        keSel.value = '';
                        changed = true;
                    }
                } else {
                    opt.disabled = false;
                }
            });

            if (changed) {
                keSel.classList.add('is-invalid');
            } else {
                keSel.classList.remove('is-invalid');
            }
        }

        async function loadAsetsByCabang(cabangId) {
            if (!cabangId) return;
            asetSel.disabled = true;
            setLoadingSelect(asetSel, 'Memuat aset...');

            try {
                const res = await fetch(`${BASE}/superadmin/mutasi/assets-by-cabang/${encodeURIComponent(cabangId)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const data = await res.json();

                asetSel.innerHTML = '';
                const placeholder = document.createElement('option');
                placeholder.value = '';
                placeholder.disabled = true;
                placeholder.selected = true;
                placeholder.textContent = data.length ? 'Pilih aset...' : 'Tidak ada aset pada cabang ini';
                asetSel.appendChild(placeholder);

                data.forEach(a => {
                    const opt = document.createElement('option');
                    opt.value = a.id_aset;
                    const nama = a.nama_master || a.nama_aset || '-'; // ⬅️ pakai nama_master
                    opt.textContent = `${nama} (Kode: ${a.kode_aset ?? a.id_aset})`;
                    if ("<?= esc(old('id_aset')) ?>" && "<?= esc(old('id_aset')) ?>" == a.id_aset) {
                        opt.selected = true;
                        placeholder.selected = false;
                    }
                    asetSel.appendChild(opt);
                });


            } catch (e) {
                setLoadingSelect(asetSel, 'Gagal memuat aset');
                console.error(e);
            } finally {
                asetSel.disabled = false;
            }
        }

        // Init behavior
        if (isSuperadmin && dariSel) {
            // On change: reload assets & adjust ke_cabang
            dariSel.addEventListener('change', (e) => {
                const id = e.target.value;
                disableKeCabangEqualToDari();
                loadAsetsByCabang(id);
            });

            // First paint: if old('dari_cabang') exists, load assets
            if (dariSel.value) {
                disableKeCabangEqualToDari();
                loadAsetsByCabang(dariSel.value);
            } else {
                // Fresh page
                setLoadingSelect(asetSel, 'Pilih cabang asal dulu...');
                asetSel.disabled = true;
            }
        } else {
            // Non-superadmin: hanya perlu pastikan ke != dari
            disableKeCabangEqualToDari();
        }

        // Validasi ringan saat submit: ke != dari
        if (keSel) {
            keSel.addEventListener('change', disableKeCabangEqualToDari);
        }
    })();
</script>

<?= $this->endSection(); ?>