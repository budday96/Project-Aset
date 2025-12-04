<?= $this->extend('layout/superadmin_template/index'); ?>
<?= $this->section('content'); ?>

<div class="card">
    <div class="card-body">
        <div class="card-header bg-light py-3 px-4 border-bottom">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">

                <!-- TITLE -->
                <h4 class="fw-bold mb-3 mb-md-0 text-center text-md-start w-100">
                    Laporan Penyusutan Aset
                </h4>

                <!-- BUTTON GROUP -->
                <div class="d-flex flex-wrap gap-2 w-100 w-md-auto justify-content-center justify-content-md-end align-items-center">

                    <!-- EXPORT PDF -->
                    <button id="btn-export-pdf" class="btn btn-outline-danger btn-sm d-flex align-items-center"
                        data-bs-toggle="tooltip" title="Export PDF">
                        <i class="bi bi-filetype-pdf fs-6 me-1"></i>
                        <span class="d-none d-md-inline">PDF</span>
                    </button>

                    <!-- EXPORT EXCEL -->
                    <button id="btn-export-excel" class="btn btn-outline-success btn-sm d-flex align-items-center"
                        data-bs-toggle="tooltip" title="Export Excel">
                        <i class="bi bi-filetype-xls fs-6 me-1"></i>
                        <span class="d-none d-md-inline">Excel</span>
                    </button>

                    <!-- REFRESH -->
                    <a href="<?= base_url('/superadmin/penyusutan'); ?>"
                        class="btn btn-outline-secondary btn-sm d-flex align-items-center"
                        data-bs-toggle="tooltip" title="Refresh">
                        <i class="bi bi-arrow-clockwise fs-6 me-1"></i>
                        <span class="d-none d-md-inline">Refresh</span>
                    </a>

                    <!-- GENERATE PENYUSUTAN -->
                    <form action="<?= site_url('superadmin/penyusutan/generate') ?>" method="post"
                        onsubmit="return confirm('Generate penyusutan bulan ini?');" class="mb-0">
                        <button class="btn btn-warning btn-sm d-flex align-items-center">
                            <i class="bi bi-calculator me-1"></i>
                            <span class="d-none d-md-inline">Generate Penyusutan</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- FILTER FORM -->
            <form method="get">
                <div class="p-3 rounded-3 border mb-3">
                    <div class="row g-3">

                        <!-- TAHUN (tetap manual) -->
                        <div class="col-md-2 col-6">
                            <label class="form-label fw-semibold mb-1">Tahun</label>
                            <input type="number" name="tahun" class="form-control form-control-sm"
                                value="<?= esc($tahun) ?>">
                        </div>

                        <!-- BULAN -->
                        <div class="col-md-2 col-6">
                            <label class="form-label fw-semibold mb-1">Bulan</label>
                            <select id="filter-bulan" class="form-select form-select-sm">
                                <option value="">Semua</option>
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?= sprintf('%02d', $i) ?>">
                                        <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <!-- CABANG -->
                        <div class="col-md-3 col-6">
                            <label class="form-label fw-semibold mb-1">Cabang</label>
                            <select id="filter-cabang" class="form-select form-select-sm">
                                <option value="">Semua Cabang</option>
                                <?php foreach ($cabangs as $c): ?>
                                    <option value="<?= esc($c['nama_cabang']) ?>">
                                        <?= esc($c['nama_cabang']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- KATEGORI -->
                        <div class="col-md-3 col-6">
                            <label class="form-label fw-semibold mb-1">Kategori</label>
                            <select id="filter-kategori" class="form-select form-select-sm">
                                <option value="">Semua Kategori</option>
                                <?php foreach ($kategoris as $k): ?>
                                    <option value="<?= esc($k['nama_kategori']) ?>">
                                        <?= esc($k['nama_kategori']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- RESET -->
                        <div class="col-md-2 col-12 d-flex align-items-end">
                            <button id="reset-filter" class="btn btn-outline-danger btn-sm w-100">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </button>
                        </div>

                    </div>
                </div>

            </form>
        </div>

        <div class="card-body px-0">
            <div class="table-responsive rounded mb-3">
                <table id="mytable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kode Aset</th>
                            <th>Jenis Aktiva</th>
                            <th>Cabang</th>
                            <th>Kategori</th>
                            <th>Harga Perolehan</th>
                            <th>Bulan Perolehan</th>
                            <th>Tarif Penyusutan</th>
                            <th>Umur Ekonomis</th>
                            <th>Bulan Penyusutan</th>
                            <th>Beban Penyusutan</th>
                            <th>Akumulasi</th>
                            <th>Nilai Sisa Buku</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>

                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?= esc($item['kode_aset']); ?></td>
                                <td><?= esc($item['nama_aset']); ?></td>
                                <td><?= esc($item['nama_cabang']); ?></td>
                                <td><?= esc($item['nama_kategori']); ?></td>


                                <td><?= number_format($item['harga_perolehan'], 0, ',', '.'); ?></td>
                                <td>
                                    <?php if (!empty($item['periode_perolehan'])): ?>
                                        <?= date('m/Y', strtotime($item['periode_perolehan'])); ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>

                                <td><?= rtrim(rtrim(number_format($item['tarif_persen_th'], 2, ',', '.'), '0'), ','); ?>%</td>
                                <td><?= $item['umur_tahun']; ?> tahun</td>
                                <td><?= sprintf('%02d/%d', $item['bulan'], $item['tahun']); ?></td>

                                <td><?= number_format($item['beban_penyusutan_bulan'], 3, ',', '.'); ?></td>
                                <td><?= number_format($item['akumulasi_sampai_bulan_ini'], 3, ',', '.'); ?></td>
                                <td><?= number_format($item['nilai_buku'], 3, ',', '.'); ?></td>
                                <td>
                                    <a href="<?= site_url('superadmin/penyusutan/detail/' . $item['id_aset']); ?>" class="btn btn-sm btn-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>

                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    <?= $this->endSection(); ?>

    <?= $this->section('scripts'); ?>
    <script>
        $(document).ready(function() {

            let table = $('#mytable').DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    zeroRecords: "Tidak ada data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    paginate: {
                        previous: "‹",
                        next: "›"
                    }
                },

                // ===============================
                // HIDE COLUMN KODE, CABANG, KATEGORI
                // ===============================
                columnDefs: [{
                    targets: [0, 2, 3, 10],
                    visible: false,
                    searchable: true
                }]
            });

            // ==== FILTER BULAN (kolom 8: Bulan Penyusutan) ====
            $('#filter-bulan').on('change', function() {
                let bulan = this.value;

                if (bulan === "") {
                    table.column(8).search("").draw();
                } else {
                    table.column(8).search("^" + bulan + "/", true, false).draw();
                }
            });

            // ==== FILTER CABANG (kolom 2 - hidden) ====
            $('#filter-cabang').on('change', function() {
                table.column(2).search(this.value).draw();
            });

            // ==== FILTER KATEGORI (kolom 3 - hidden) ====
            $('#filter-kategori').on('change', function() {
                table.column(3).search(this.value).draw();
            });

            // ==== RESET FILTER ====
            $('#reset-filter').on('click', function() {
                $('#filter-bulan').val('');
                $('#filter-cabang').val('');
                $('#filter-kategori').val('');

                table.search('').columns().search('').draw();
            });

        });
    </script>

    <?= $this->endSection(); ?>