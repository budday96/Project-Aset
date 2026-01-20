<?= $this->extend('layout/superadmin_template/index'); ?>
<?= $this->section('content'); ?>

<style>
    .table-responsive {
        max-height: 300px;
        overflow-y: auto;
        position: relative;
    }

    /* Sticky Header */
    .table-responsive thead th {
        position: sticky;
        top: 0;
        background: #f8f9fa;
        /* Warna header Bootstrap */
        z-index: 5;
    }
</style>


<div class="card shadow-sm border-0">
    <div class="card-body p-4">

        <h4 class="fw-bold mb-2">Mutasi Aset Antar Cabang</h4>
        <p class="text-muted mb-4">Gunakan form berikut untuk membuat permintaan mutasi aset antar cabang.</p>

        <!-- ALERT -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>


        <!-- ===========================
             STEP 1 – PILIH CABANG ASAL
        ============================ -->
        <div class="mb-4 p-3 rounded-3 border bg-light">
            <h6 class="fw-bold mb-3">Step 1 — Pilih Cabang Asal</h6>

            <form class="row g-3" method="get" action="<?= current_url(); ?>">
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Cabang Asal</label>
                    <select name="cabang_asal" id="cabang_asal" class="form-select" required>
                        <option value="">-- Pilih Cabang Asal --</option>
                        <?php foreach ($cabangs as $c): ?>
                            <option value="<?= $c['id_cabang']; ?>"
                                <?= ($selectedCabangAsal == $c['id_cabang']) ? 'selected' : ''; ?>>
                                <?= esc($c['nama_cabang']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Tampilkan Aset
                    </button>
                </div>
            </form>
        </div>


        <?php if ($selectedCabangAsal): ?>

            <!-- ===========================
             STEP 2 – DETAIL MUTASI
        ============================ -->
            <form method="post" action="<?= site_url('superadmin/mutasi/store'); ?>">
                <?= csrf_field(); ?>
                <input type="hidden" name="id_cabang_asal" value="<?= $selectedCabangAsal; ?>">

                <div class="mb-4 p-3 rounded-3 border bg-light">
                    <h6 class="fw-bold mb-3">Step 2 — Detail Mutasi</h6>

                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Cabang Tujuan</label>
                            <select name="id_cabang_tujuan" id="id_cabang_tujuan" class="form-select" required>
                                <option value="">-- Pilih Cabang Tujuan --</option>
                                <?php foreach ($cabangs as $c): ?>
                                    <?php if ($c['id_cabang'] != $selectedCabangAsal): ?>
                                        <option value="<?= $c['id_cabang']; ?>">
                                            <?= esc($c['nama_cabang']); ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-7">
                            <label class="form-label fw-semibold">Catatan (Opsional)</label>
                            <textarea name="catatan" id="catatan" rows="1" class="form-control"
                                placeholder="Tambahkan catatan tambahan jika diperlukan..."><?= old('catatan'); ?></textarea>
                        </div>
                    </div>
                </div>


                <!-- ===========================
                 STEP 3 – PILIH ASET
            ============================ -->
                <div class="mb-3">
                    <h6 class="fw-bold mb-2">Step 3 — Pilih Aset untuk Dimutasi</h6>
                    <p class="text-muted small mb-3">
                        Centang aset yang ingin dipindahkan, kemudian isi jumlah mutasi. Sistem otomatis memvalidasi stok & cabang.
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
                                    <th class="text-center">
                                        <input type="checkbox" id="checkAll">
                                    </th>
                                    <th>Kode Aset</th>
                                    <th>Nama Aset</th>
                                    <th>Kategori</th>
                                    <th>Cabang</th>
                                    <th class="text-center">Stock</th>
                                    <th class="text-center" width="140">Qty Mutasi</th>
                                    <th width="200">Keterangan</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($asets as $a): ?>
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox"
                                                class="row-check"
                                                name="items[<?= $a['id_aset']; ?>][checked]"
                                                value="1">
                                        </td>
                                        <td>
                                            <strong><?= esc($a['kode_aset']); ?></strong><br>
                                        </td>
                                        <td><?= esc($a['nama_master']); ?></td>
                                        <td><?= esc($a['nama_kategori']); ?></td>
                                        <td><?= esc($a['nama_cabang']); ?></td>
                                        <td class="text-center fw-bold"><?= (int)$a['stock']; ?></td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-center"
                                                name="items[<?= $a['id_aset']; ?>][qty]"
                                                min="1"
                                                max="<?= (int)$a['stock']; ?>"
                                                value="1">
                                        </td>
                                        <td>
                                            <input type="text"
                                                name="items[<?= $a['id_aset']; ?>][keterangan]"
                                                class="form-control form-control-sm"
                                                placeholder="Tambahan (opsional)">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                                <?php if (empty($asets)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-3">
                                            Tidak ada aset di cabang asal ini.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>


                <!-- ===========================
                 ACTION BUTTONS
            ============================ -->
                <!-- FLOATING ACTION BAR -->
                <div class="position-sticky bg-white border-top pt-2 pb-2"
                    style="bottom: 0; z-index: 99;">
                    <div class="d-flex justify-content-end gap-2 container">
                        <a href="<?= site_url('superadmin/mutasi'); ?>" class="btn btn-secondary">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-send-check"></i> Proses Mutasi
                        </button>
                    </div>
                </div>

            </form>

        <?php else: ?>
            <p class="text-muted mt-3">Silakan pilih cabang asal untuk menampilkan daftar aset.</p>
        <?php endif; ?>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {

        // CHECK ALL
        const checkAll = document.getElementById('checkAll');
        if (checkAll) {
            checkAll.addEventListener('change', function() {
                document.querySelectorAll('.row-check').forEach(cb => cb.checked = checkAll.checked);
            });
        }

        // LIVE SEARCH
        const searchInput = document.getElementById('searchAset');
        const tableRows = document.querySelectorAll("table tbody tr");

        if (searchInput) {
            searchInput.addEventListener("keyup", function() {
                const keyword = this.value.toLowerCase();

                tableRows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    row.style.display = text.includes(keyword) ? "" : "none";
                });
            });
        }
    });
</script>


<?= $this->endSection(); ?>