<?= $this->extend('layout/superadmin_template/index'); ?>
<?= $this->section('content'); ?>

<div class="card">
    <div class="card-body">
        <div class="card-header bg-light py-3 px-4 border-bottom">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">

                <!-- TITLE -->
                <h4 class="fw-bold mb-3 mb-md-0 text-center text-md-start w-100">
                    List Mutasi Aset
                </h4>

                <!-- BUTTON GROUP -->
                <div class="d-flex flex-wrap gap-2 w-100 w-md-auto justify-content-center justify-content-md-end">

                    <!-- EXPORT PDF -->
                    <button class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" title="Export PDF">
                        <i class="bi bi-filetype-pdf fs-5"></i>
                    </button>

                    <!-- EXPORT EXCEL -->
                    <button class="btn btn-outline-success btn-sm" data-bs-toggle="tooltip" title="Export Excel">
                        <i class="bi bi-filetype-xls fs-5"></i>
                    </button>

                    <!-- REFRESH -->
                    <a href="<?= base_url('/superadmin/mutasi'); ?>"
                        class="btn btn-outline-secondary btn-sm"
                        data-bs-toggle="tooltip" title="Refresh">
                        <i class="bi bi-arrow-clockwise fs-5"></i>
                    </a>

                    <!-- Buat Mutasi -->
                    <a class="btn btn-warning btn-sm fw-semibold d-flex align-items-center justify-content-center px-2"
                        href="<?= site_url('superadmin/mutasi/create'); ?>">

                        <i class="bi bi-plus-circle me-1 fs-5"></i>
                        <span class="text-truncate" style="max-width: 90px;">Buat Mutasi</span>
                    </a>
                </div>
            </div>

            <!-- FILTER SECTION (ala referensi) -->
            <div class="p-3 rounded-3 border mb-3">
                <div class="row g-3">

                    <!-- Filter Cabang Asal -->
                    <div class="col-md-2 col-6">
                        <label class="form-label fw-semibold mb-1">Cabang Asal</label>
                        <select id="filter-asal" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            <?php foreach ($cabangs as $c): ?>
                                <option value="<?= esc($c['nama_cabang']) ?>">
                                    <?= esc($c['nama_cabang']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filter Cabang Tujuan -->
                    <div class="col-md-2 col-6">
                        <label class="form-label fw-semibold mb-1">Cabang Tujuan</label>
                        <select id="filter-tujuan" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            <?php foreach ($cabangs as $c): ?>
                                <option value="<?= esc($c['nama_cabang']) ?>">
                                    <?= esc($c['nama_cabang']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filter Status -->
                    <div class="col-md-2 col-6">
                        <label class="form-label fw-semibold mb-1">Status</label>
                        <select id="filter-status" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            <option value="Pending">Pending</option>
                            <option value="Dikirim">Dikirim</option>
                            <option value="Diterima">Diterima</option>
                            <option value="Dibatalkan">Dibatalkan</option>
                        </select>
                    </div>

                    <!-- Filter Tanggal Mulai -->
                    <div class="col-md-2 col-6">
                        <label class="form-label fw-semibold mb-1">Tanggal Mulai</label>
                        <input type="date" id="filter-tanggal-awal" class="form-control form-control-sm">
                    </div>

                    <!-- Filter Tanggal Akhir -->
                    <div class="col-md-2 col-6">
                        <label class="form-label fw-semibold mb-1">Tanggal Akhir</label>
                        <input type="date" id="filter-tanggal-akhir" class="form-control form-control-sm">
                    </div>

                    <!-- Reset Button -->
                    <div class="col-md-2 col-12 d-flex align-items-end">
                        <button id="reset-filter" class="btn btn-outline-danger btn-sm w-100">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </button>
                    </div>
                </div>
            </div>


            <div class="table-responsive">
                <table class="table table-hover" id="tableHeader">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode Mutasi</th>
                            <th>Tanggal</th>
                            <th>Cabang Asal</th>
                            <th>Cabang Tujuan</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($items as $row): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= esc($row['kode_mutasi']); ?></td>
                                <td><?= date('d M Y H:i', strtotime($row['tanggal_mutasi'])); ?></td>
                                <td><?= esc($row['cabang_asal']); ?></td>
                                <td><?= esc($row['cabang_tujuan']); ?></td>
                                <td>
                                    <?php if ($row['status'] === 'pending' && in_groups('superadmin')): ?>
                                        <form method="post" action="<?= site_url('superadmin/mutasi/kirim-header/' . $row['id_mutasi']); ?>" class="d-inline">
                                            <?= csrf_field(); ?>
                                            <button class="btn btn-sm btn-warning">Kirim</button>
                                        </form>

                                        <form method="post" action="<?= site_url('superadmin/mutasi/batal-header/' . $row['id_mutasi']); ?>" class="d-inline">
                                            <?= csrf_field(); ?>
                                            <button class="btn btn-sm btn-danger">Batal</button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($row['status'] === 'dikirim' && (user()->id_cabang == $row['id_cabang_tujuan'] || in_groups('superadmin'))): ?>
                                        <form method="post" action="<?= site_url('superadmin/mutasi/terima-header/' . $row['id_mutasi']); ?>" class="d-inline">
                                            <?= csrf_field(); ?>
                                            <button class="btn btn-sm btn-success">Terima</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <!-- Tombol View -->
                                    <a href="<?= site_url('superadmin/mutasi/' . $row['id_mutasi']); ?>"
                                        class="btn btn-sm"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        title="Detail Mutasi">
                                        <i class="bi bi-eye" style="color: #fd7e14;"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($items)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted">Belum ada data mutasi.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?= $this->endSection(); ?>


    <?= $this->section('scripts'); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // 🔥 Ambil instance DataTable yang sudah dibuat oleh script global
            const table = $('#tableHeader').DataTable({
                responsive: true,
                processing: true,
                autoWidth: false,

                // === default bootstrap 5 layout ===
                dom: '<"row mb-2"' +
                    '<"col-12 col-md-6 mb-2"l>' +
                    '<"col-12 col-md-6 d-flex justify-content-md-end"f>' +
                    '>' +
                    'rt' +
                    '<"row mt-3"' +
                    '<"col-12 col-md-6"i>' +
                    '<"col-12 col-md-6 d-flex justify-content-md-end mt-2 mt-md-0"p>' +
                    '>',

                // Tambahkan opsi default disini
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
                }
            });


            // ==== FILTER CABANG ASAL (kolom index 3) ====
            $('#filter-asal').on('change', function() {
                table.column(3).search(this.value).draw();
            });

            // ==== FILTER CABANG TUJUAN (kolom index 4) ====
            $('#filter-tujuan').on('change', function() {
                table.column(4).search(this.value).draw();
            });

            // ==== FILTER STATUS (kolom index 5) ====
            $('#filter-status').on('change', function() {
                table.column(5).search(this.value).draw();
            });

            // ==== FILTER TANGGAL (custom search) ====
            $.fn.dataTable.ext.search.push(function(settings, data) {
                if (settings.nTable.id !== 'tableHeader') return true;

                let min = $('#filter-tanggal-awal').val();
                let max = $('#filter-tanggal-akhir').val();
                let tglStr = data[2] || '';

                if (!min && !max) return true;
                if (!tglStr) return false;

                let tgl = new Date(tglStr);

                if (min) {
                    let dMin = new Date(min + ' 00:00:00');
                    if (tgl < dMin) return false;
                }
                if (max) {
                    let dMax = new Date(max + ' 23:59:59');
                    if (tgl > dMax) return false;
                }
                return true;
            });

            $('#filter-tanggal-awal, #filter-tanggal-akhir').on('change', function() {
                table.draw();
            });

            // ==== RESET FILTER ====
            $('#reset-filter').on('click', function() {
                $('#filter-asal').val('');
                $('#filter-tujuan').val('');
                $('#filter-status').val('');
                $('#filter-tanggal-awal').val('');
                $('#filter-tanggal-akhir').val('');

                table.search('').columns().search('').draw();
            });
        });
    </script>


    <?= $this->endSection(); ?>