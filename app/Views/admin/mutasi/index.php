<?= $this->extend('layout/admin_template/index'); ?>
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
                    <a href="<?= base_url('/admin/mutasi'); ?>"
                        class="btn btn-outline-secondary btn-sm"
                        data-bs-toggle="tooltip" title="Refresh">
                        <i class="bi bi-arrow-clockwise fs-5"></i>
                    </a>

                    <!-- Buat Mutasi -->
                    <a class="btn btn-warning btn-sm fw-semibold d-flex align-items-center justify-content-center px-2"
                        href="<?= site_url('admin/mutasi/create'); ?>">

                        <i class="bi bi-plus-circle me-1 fs-5"></i>
                        <span class="text-truncate" style="max-width: 90px;">Buat Mutasi</span>
                    </a>
                </div>
            </div>

            <!-- FILTER SECTION (ala referensi) -->
            <div class="p-3 rounded-3 border mb-3">
                <div class="row g-3">

                    <!-- Filter Cabang asal -->
                    <div class="col-md-2 col-6">
                        <label class="form-label fw-semibold mb-1">Cabang Asal</label>
                        <select id="filter-asal"
                            class="form-select form-select-sm"
                            readonly>
                            <option value="<?= esc($cabangUser->nama_cabang) ?>" selected>
                                <?= esc($cabangUser->nama_cabang) ?>
                            </option>
                        </select>

                        <!-- hidden input agar nilainya tetap terkirim -->
                        <input type="hidden" id="filter-asal-hidden"
                            value="<?= esc($cabangUser->nama_cabang) ?>">
                    </div>


                    <!-- Filter Cabang Tujuan -->
                    <div class="col-md-2 col-6">
                        <label class="form-label fw-semibold mb-1">Cabang Tujuan</label>
                        <select id="filter-tujuan" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            <?php
                            $tujuans = array_unique(array_column($items, 'cabang_tujuan'));
                            foreach ($tujuans as $nama):
                            ?>
                                <option value="<?= esc($nama) ?>"><?= esc($nama) ?></option>
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
                                    <?php if ($row['status'] === 'pending' && in_groups('admin')): ?>
                                        <!-- Pending: Kirim / Batal -->
                                        <form method="post" action="<?= site_url('admin/mutasi/kirim-header/' . $row['id_mutasi']); ?>" class="d-inline">
                                            <?= csrf_field(); ?>
                                            <button class="btn btn-sm btn-warning">
                                                <i class="bi bi-send"></i> Kirim
                                            </button>
                                        </form>

                                        <form method="post" action="<?= site_url('admin/mutasi/batal-header/' . $row['id_mutasi']); ?>" class="d-inline">
                                            <?= csrf_field(); ?>
                                            <button class="btn btn-sm btn-danger">
                                                <i class="bi bi-x-circle"></i> Batal
                                            </button>
                                        </form>

                                    <?php elseif ($row['status'] === 'dikirim' && (user()->id_cabang == $row['id_cabang_tujuan'] || in_groups('admin'))): ?>
                                        <!-- Dikirim: Terima -->
                                        <form method="post" action="<?= site_url('admin/mutasi/terima-header/' . $row['id_mutasi']); ?>" class="d-inline">
                                            <?= csrf_field(); ?>
                                            <button class="btn btn-sm btn-success">
                                                <i class="bi bi-check-circle"></i> Terima
                                            </button>
                                        </form>

                                    <?php elseif ($row['status'] === 'selesai'): ?>
                                        <!-- Selesai: hanya icon -->
                                        <span class="text-success fw-bold">
                                            <i class="bi bi-check2-circle fs-5"></i> Selesai
                                        </span>

                                    <?php else: ?>
                                        <!-- Jika tidak ada aksi lain -->
                                        <span class="text-secondary">
                                            <i class="bi bi-check-circle text-success"></i>
                                        </span>

                                    <?php endif; ?>
                                </td>

                                <td class="text-center">
                                    <!-- Tombol View -->
                                    <a href="<?= site_url('admin/mutasi/' . $row['id_mutasi']); ?>"
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

            // Inisialisasi DataTable untuk tableHeader (mutasi)
            const table = $('#tableHeader').DataTable({
                responsive: true,
                processing: true,
                autoWidth: false,
                pageLength: 10,

                // HATI-HATI: kita HAPUS 'B' dari dom sehingga DataTables tidak menaruh tombol default
                dom: '<"row mb-2"' +
                    '<"col-12 col-md-6 mb-2"l>' +
                    '<"col-12 col-md-6 d-flex justify-content-md-end"f>' +
                    '>' +
                    'rt' +
                    '<"row mt-3"' +
                    '<"col-12 col-md-6"i>' +
                    '<"col-12 col-md-6 d-flex justify-content-md-end mt-2 mt-md-0"p>' +
                    '>',

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

                columnDefs: [
                    // Anda punya kolom No (0), Kode (1), Tanggal (2), Asal (3), Tujuan (4), Status (5), Aksi (6)
                    // tetap seperti semula; ini hanya contoh jika ingin hide
                    {
                        targets: [],
                        visible: true
                    }
                ],

                // Kita tetap daftarkan buttons agar dapat dipanggil via API, namun container tidak ditampilkan
                buttons: [{
                        extend: 'pdfHtml5',
                        className: 'buttons-pdf',
                        filename: 'Laporan_Mutasi_Aset',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                            modifier: {
                                search: 'applied'
                            },
                            // columns yang diexport: Kode Mutasi(1), Tanggal(2), Cabang Asal(3), Cabang Tujuan(4), Status(5)
                            columns: [1, 2, 3, 4, 5]
                        },
                        customize: function(doc) {
                            // dasar & margin sama gaya aset
                            doc.pageOrientation = 'landscape';
                            doc.pageSize = 'A4';
                            doc.pageMargins = [30, 110, 30, 40];
                            doc.defaultStyle.fontSize = 10;

                            // Header perusahaan + judul — tampil di setiap halaman
                            doc.header = function(currentPage, pageCount) {
                                return {
                                    margin: [0, 18, 0, 10],
                                    alignment: 'center',
                                    stack: [{
                                            text: "PT MYASSET INDONESIA",
                                            bold: true,
                                            fontSize: 16
                                        },
                                        {
                                            text: "ASSET MANAGEMENT DIVISION",
                                            fontSize: 11
                                        },
                                        {
                                            text: "Jl. Merdeka No. 123, Jakarta Pusat",
                                            fontSize: 9
                                        },
                                        {
                                            text: "Email: support@myasset.co.id | Telp: (021) 5544 8899",
                                            fontSize: 9
                                        },
                                        {
                                            text: "LAPORAN MUTASI ASET",
                                            bold: true,
                                            fontSize: 13,
                                            margin: [0, 6, 0, 0]
                                        }
                                    ]
                                };
                            };

                            // Footer (tanggal + page)
                            doc.footer = function(page, pages) {
                                return {
                                    margin: [30, 10, 30, 0],
                                    columns: [{
                                            text: "Generated: " + new Date().toLocaleDateString(),
                                            alignment: 'left',
                                            fontSize: 9
                                        },
                                        {
                                            text: "Page " + page + " of " + pages,
                                            alignment: 'right',
                                            fontSize: 9
                                        }
                                    ]
                                };
                            };

                            // Ambil data terfilter dan bangun tabel custom
                            let dt = $('#tableHeader').DataTable();
                            let rowIndexes = dt.rows({
                                search: 'applied'
                            }).indexes().toArray();
                            let colsToExport = [1, 2, 3, 4, 5]; // sesuai exportOptions

                            // Table header (biru gelap + teks putih)
                            let body = [];
                            body.push([{
                                    text: "Kode Mutasi",
                                    style: "tableHeader"
                                },
                                {
                                    text: "Tanggal",
                                    style: "tableHeader"
                                },
                                {
                                    text: "Cabang Asal",
                                    style: "tableHeader"
                                },
                                {
                                    text: "Cabang Tujuan",
                                    style: "tableHeader"
                                },
                                {
                                    text: "Status",
                                    style: "tableHeader"
                                }
                            ]);

                            // Isi baris
                            rowIndexes.forEach(function(rowIdx) {
                                let cells = colsToExport.map(function(colIdx) {
                                    let c = dt.cell(rowIdx, colIdx).data();
                                    return $('<div>').html(c === undefined ? 'Selesai' : c).text().trim();
                                });

                                body.push([{
                                        text: cells[0] || 'Selesai',
                                        alignment: 'center',
                                        margin: [6, 6, 6, 6],
                                        fontSize: 9
                                    },
                                    {
                                        text: cells[1] || 'Selesai',
                                        alignment: 'center',
                                        margin: [6, 6, 6, 6],
                                        fontSize: 9
                                    },
                                    {
                                        text: cells[2] || 'Selesai',
                                        alignment: 'center',
                                        margin: [6, 6, 6, 6],
                                        fontSize: 9
                                    },
                                    {
                                        text: cells[3] || 'Selesai',
                                        alignment: 'center',
                                        margin: [6, 6, 6, 6],
                                        fontSize: 9
                                    },
                                    {
                                        text: cells[4] || 'Selesai',
                                        alignment: 'center',
                                        margin: [6, 6, 6, 6],
                                        fontSize: 9
                                    }
                                ]);
                            });

                            // Node tabel custom: header biru gelap, body tanpa border antar baris
                            let customTableNode = {
                                table: {
                                    headerRows: 1,
                                    widths: [120, 120, '*', '*', 80],
                                    body: body
                                },
                                layout: {
                                    vLineWidth: function(i) {
                                        return 0;
                                    },
                                    hLineWidth: function(i, node) {
                                        if (i === 0 || i === node.table.body.length) return 0.4;
                                        return 0;
                                    },
                                    hLineColor: function() {
                                        return '#e0e0e0';
                                    },
                                    paddingLeft: function() {
                                        return 6;
                                    },
                                    paddingRight: function() {
                                        return 6;
                                    },
                                    paddingTop: function() {
                                        return 6;
                                    },
                                    paddingBottom: function() {
                                        return 6;
                                    },
                                    fillColor: function(rowIndex) {
                                        if (rowIndex === 0) return '#0b4a6f';
                                        return null;
                                    }
                                }
                            };

                            // header style
                            doc.styles.tableHeader = {
                                color: 'white',
                                bold: true,
                                fontSize: 10,
                                alignment: 'center'
                            };

                            // Pastikan doc.content hanya berisi spacer + tabel custom (hindari blok sisa)
                            doc.content = [{
                                text: '\n'
                            }, customTableNode];
                        }
                    },

                    // Excel export — simple, kolomnya sama
                    {
                        extend: 'excelHtml5',
                        className: 'buttons-excel',
                        filename: 'Laporan_Mutasi_Aset',
                        title: 'Laporan Mutasi Aset',
                        exportOptions: {
                            modifier: {
                                search: 'applied'
                            },
                            columns: [1, 2, 3, 4]
                        }
                    }
                ] // end buttons
            }); // end DataTable init

            // -----------------------------
            // Hook tombol PDF/Excel di UI Anda
            // Jika tombol punya id, ubah selector ke '#btn-export-pdf' / '#btn-export-excel'
            // Jika tidak punya id, kita cari berdasarkan kelas dan ikon (cara ini aman)
            // -----------------------------
            // cari tombol PDF: btn-outline-danger yang memiliki icon bi-filetype-pdf
            const $pdfBtn = $('.btn.btn-outline-danger:has(.bi-filetype-pdf)');
            const $excelBtn = $('.btn.btn-outline-success:has(.bi-filetype-xls)');

            // jika tidak ditemukan, coba selector generik (fallback)
            const $pdfTrigger = $pdfBtn.length ? $pdfBtn : $('.btn:contains("PDF")');
            const $excelTrigger = $excelBtn.length ? $excelBtn : $('.btn:contains("Excel")');

            // Pasang event: trigger ekspor melalui API DataTables
            $pdfTrigger.on('click', function(e) {
                e.preventDefault();
                table.button('.buttons-pdf').trigger();
            });
            $excelTrigger.on('click', function(e) {
                e.preventDefault();
                table.button('.buttons-excel').trigger();
            });

            // ===========================
            // FILTERS (sudah ada) — tetap bekerja
            // ===========================
            $('#filter-asal').on('change', function() {
                table.column(3).search(this.value).draw();
            });
            $('#filter-tujuan').on('change', function() {
                table.column(4).search(this.value).draw();
            });
            $('#filter-status').on('change', function() {
                table.column(5).search(this.value).draw();
            });

            // DATE RANGE FILTER (sudah ada)
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

            // RESET FILTER
            $('#reset-filter').on('click', function(e) {
                e.preventDefault();
                $('#filter-asal').val('');
                $('#filter-tujuan').val('');
                $('#filter-status').val('');
                $('#filter-tanggal-awal').val('');
                $('#filter-tanggal-akhir').val('');
                table.search('').columns().search('').draw();
            });

        }); // DOMContentLoaded
    </script>
    <?= $this->endSection(); ?>