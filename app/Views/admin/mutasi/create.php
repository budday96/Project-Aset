<?= $this->extend('layout/admin_template/index'); ?>
<?= $this->section('content'); ?>

<?php
$mode      = $mode ?? 'create';
$isEdit    = ($mode === 'edit');
$header    = $header ?? null;
$details   = $details ?? [];

$detailMap = [];
foreach ($details as $d) {
    $detailMap[$d['id_aset_asal']] = $d;
}
?>

<style>
    .table-responsive {
        max-height: 320px;
        overflow-y: auto;
    }

    .table-responsive thead th {
        position: sticky;
        top: 0;
        background: #f8f9fa;
        z-index: 5;
    }
</style>

<div class="card">
    <div class="card-body">

        <h4 class="fw-bold mb-2">
            <?= $isEdit ? 'Edit Mutasi Aset' : 'Mutasi Aset Antar Cabang'; ?>
        </h4>

        <p class="text-muted mb-4">
            <?= $isEdit
                ? 'Perbarui data mutasi sebelum dikirim ke cabang tujuan.'
                : 'Gunakan form berikut untuk membuat permintaan mutasi aset antar cabang.'; ?>
        </p>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <form method="post"
            action="<?= $isEdit
                        ? site_url('admin/mutasi/update/' . $header['id_mutasi'])
                        : site_url('admin/mutasi/store'); ?>">

            <?= csrf_field(); ?>

            <!-- ===========================
                 DETAIL MUTASI
            ============================ -->
            <div class="mb-4 p-3 rounded-3 border bg-light">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Cabang Tujuan</label>
                        <select name="id_cabang_tujuan" class="form-select" required>
                            <option value="">-- Pilih Cabang Tujuan --</option>
                            <?php foreach ($cabangs as $c): ?>
                                <option value="<?= $c['id_cabang']; ?>"
                                    <?= $isEdit && $header['id_cabang_tujuan'] == $c['id_cabang'] ? 'selected' : ''; ?>>
                                    <?= esc($c['nama_cabang']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-7">
                        <label class="form-label fw-semibold">Catatan (Opsional)</label>
                        <textarea name="catatan" rows="1" class="form-control"
                            placeholder="Tambahkan catatan..."><?= old('catatan', $header['catatan'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- ===========================
                 PILIH ASET
            ============================ -->
            <div class="mb-3">
                <h6 class="fw-bold mb-2">Pilih Aset untuk Dimutasi</h6>
                <p class="text-muted small mb-3">
                    Centang aset yang ingin dipindahkan dan tentukan jumlah mutasi.
                </p>

                <div class="row justify-content-end mb-2">
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                            <input type="text" id="searchAset" class="form-control" placeholder="Cari aset...">
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center"><input type="checkbox" id="checkAll"></th>
                                <th>Kode</th>
                                <th>Nama Aset</th>
                                <th>Kategori</th>
                                <th class="text-center">Stock</th>
                                <th class="text-center" width="120">Qty</th>
                                <th width="220">Keterangan</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($asets as $a):
                                $checked = isset($detailMap[$a['id_aset']]);
                                $qty     = $checked ? $detailMap[$a['id_aset']]['qty'] : 1;
                                $ket     = $checked ? $detailMap[$a['id_aset']]['keterangan'] : '';
                            ?>
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox"
                                            class="row-check"
                                            name="items[<?= $a['id_aset']; ?>][checked]"
                                            value="1"
                                            <?= $checked ? 'checked' : ''; ?>>
                                    </td>
                                    <td><strong><?= esc($a['kode_aset']); ?></strong></td>
                                    <td><?= esc($a['nama_master']); ?></td>
                                    <td><?= esc($a['nama_kategori']); ?></td>
                                    <td class="text-center fw-bold"><?= (int)$a['stock']; ?></td>
                                    <td>
                                        <input type="number"
                                            class="form-control form-control-sm text-center"
                                            name="items[<?= $a['id_aset']; ?>][qty]"
                                            min="1"
                                            max="<?= (int)$a['stock']; ?>"
                                            value="<?= $qty ?>">
                                    </td>
                                    <td>
                                        <input type="text"
                                            class="form-control form-control-sm"
                                            name="items[<?= $a['id_aset']; ?>][keterangan]"
                                            value="<?= esc($ket) ?>"
                                            placeholder="Opsional">
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                            <?php if (empty($asets)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-3">
                                        Tidak ada aset di cabang asal.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ===========================
                 ACTION BAR
            ============================ -->
            <div class="position-sticky bg-white border-top pt-2 pb-2" style="bottom:0;z-index:99;">
                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= site_url('admin/mutasi'); ?>" class="btn btn-secondary">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save"></i>
                        <?= $isEdit ? 'Update Mutasi' : 'Simpan Mutasi'; ?>
                    </button>
                </div>
            </div>

        </form>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const checkAll = document.getElementById('checkAll');
        if (checkAll) {
            checkAll.addEventListener('change', function() {
                document.querySelectorAll('.row-check').forEach(cb => cb.checked = checkAll.checked);
            });
        }

        const searchInput = document.getElementById('searchAset');
        const rows = document.querySelectorAll("table tbody tr");

        if (searchInput) {
            searchInput.addEventListener("keyup", function() {
                const keyword = this.value.toLowerCase();
                rows.forEach(row => {
                    row.style.display = row.innerText.toLowerCase().includes(keyword) ? "" : "none";
                });
            });
        }

    });
</script>

<?= $this->endSection(); ?>