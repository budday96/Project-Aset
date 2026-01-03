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
                                <td class="text-center">
                                    <!-- Tombol View -->
                                    <a href="<?= site_url('superadmin/penyusutan/detail/' . $item['id_aset']); ?>"
                                        class="btn btn-sm"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        title="Detail Penyusutan">
                                        <i class="bi bi-eye" style="color: #fd7e14;"></i>
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
                processing: true,
                autoWidth: false,

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

                columnDefs: [{
                    targets: [0, 2, 3],
                    visible: false,
                    searchable: true
                }],

                buttons: [

                    // PDF (gaya aset tapi disesuaikan agar semua kolom muat)
                    {
                        extend: 'pdfHtml5',
                        className: 'buttons-pdf',
                        filename: 'Laporan_Penyusutan_Aset',
                        orientation: 'landscape',
                        pageSize: 'A4',

                        exportOptions: {
                            modifier: {
                                search: "applied"
                            },
                            columns: [1, 4, 5, 6, 7, 8, 9, 10, 11]
                        },

                        customize: function(doc) {
                            // Kurangi tinggi header sedikit & font yang lebih kecil agar lebih banyak ruang
                            doc.pageOrientation = 'landscape';
                            doc.pageSize = 'A4';
                            doc.pageMargins = [30, 90, 30, 40]; // top sedikit dikurangi
                            doc.defaultStyle.fontSize = 9; // turunkan font sedikit

                            // HEADER muncul di setiap halaman
                            doc.header = function(currentPage, pageCount) {
                                return {
                                    margin: [0, 12, 0, 6], // lebih ramping
                                    alignment: 'center',
                                    stack: [{
                                            text: "PT MYASSET INDONESIA",
                                            bold: true,
                                            fontSize: 14
                                        },
                                        {
                                            text: "ASSET MANAGEMENT DIVISION",
                                            fontSize: 10
                                        },
                                        {
                                            text: "Jl. Merdeka No. 123, Jakarta Pusat",
                                            fontSize: 8
                                        },
                                        {
                                            text: "Email: support@myasset.co.id | Telp: (021) 5544 8899",
                                            fontSize: 8
                                        },
                                        {
                                            text: "LAPORAN PENYUSUTAN ASET",
                                            bold: true,
                                            fontSize: 12,
                                            margin: [0, 6, 0, 0]
                                        }
                                    ]
                                };
                            };

                            // Footer tetap
                            doc.footer = function(page, pages) {
                                return {
                                    margin: [30, 10, 30, 0],
                                    columns: [{
                                            text: "Generated: " + new Date().toLocaleDateString(),
                                            alignment: 'left',
                                            fontSize: 8
                                        },
                                        {
                                            text: "Page " + page + " of " + pages,
                                            alignment: 'right',
                                            fontSize: 8
                                        }
                                    ]
                                };
                            };

                            // Ambil baris yang terfilter
                            let dt = $('#mytable').DataTable();
                            let rowIndexes = dt.rows({
                                search: 'applied'
                            }).indexes().toArray();
                            let colsToExport = [1, 4, 5, 6, 7, 8, 9, 10, 11];

                            // Header tabel (sesuai gaya aset)
                            let body = [];
                            body.push([{
                                    text: "Jenis Aktiva",
                                    style: "tableHeader"
                                },
                                {
                                    text: "Harga Perolehan",
                                    style: "tableHeader"
                                },
                                {
                                    text: "Bulan Perolehan",
                                    style: "tableHeader"
                                },
                                {
                                    text: "Tarif Penyusutan",
                                    style: "tableHeader"
                                },
                                {
                                    text: "Umur Ekonomis",
                                    style: "tableHeader"
                                },
                                {
                                    text: "Bulan Penyusutan",
                                    style: "tableHeader"
                                },
                                {
                                    text: "Beban Penyusutan",
                                    style: "tableHeader"
                                },
                                {
                                    text: "Akumulasi",
                                    style: "tableHeader"
                                },
                                {
                                    text: "Nilai Sisa Buku",
                                    style: "tableHeader"
                                }
                            ]);

                            // Isi body
                            rowIndexes.forEach(function(rowIdx) {
                                let cells = colsToExport.map(function(colIdx) {
                                    let c = dt.cell(rowIdx, colIdx).data();
                                    return $('<div>').html(c === undefined ? '-' : c).text().trim();
                                });

                                body.push([{
                                        text: cells[0] || '-',
                                        alignment: 'left',
                                        margin: [6, 6, 6, 6],
                                        fontSize: 8
                                    },
                                    {
                                        text: cells[1] || '-',
                                        alignment: 'center',
                                        margin: [6, 6, 6, 6],
                                        fontSize: 8
                                    },
                                    {
                                        text: cells[2] || '-',
                                        alignment: 'center',
                                        margin: [6, 6, 6, 6],
                                        fontSize: 8
                                    },
                                    {
                                        text: cells[3] || '-',
                                        alignment: 'center',
                                        margin: [6, 6, 6, 6],
                                        fontSize: 8
                                    },
                                    {
                                        text: cells[4] || '-',
                                        alignment: 'center',
                                        margin: [6, 6, 6, 6],
                                        fontSize: 8
                                    },
                                    {
                                        text: cells[5] || '-',
                                        alignment: 'center',
                                        margin: [6, 6, 6, 6],
                                        fontSize: 8
                                    },
                                    {
                                        text: cells[6] || '-',
                                        alignment: 'center',
                                        margin: [6, 6, 6, 6],
                                        fontSize: 8
                                    },
                                    {
                                        text: cells[7] || '-',
                                        alignment: 'center',
                                        margin: [6, 6, 6, 6],
                                        fontSize: 8
                                    },
                                    {
                                        text: cells[8] || '-',
                                        alignment: 'center',
                                        margin: [6, 6, 6, 6],
                                        fontSize: 8
                                    }
                                ]);
                            });

                            // Lebar kolom diatur lebih konservatif: gunakan '*' fleksibel untuk nama dan angka lebih kecil
                            let customTableNode = {
                                table: {
                                    headerRows: 1,
                                    widths: ['*', 70, 60, 60, 70, 70, 70, 70, 70],
                                    body: body
                                },
                                layout: {
                                    vLineWidth: function(i) {
                                        return 0;
                                    },
                                    hLineWidth: function(i, node) {
                                        if (i === 0 || i === node.table.body.length) return 0.35;
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

                            doc.styles.tableHeader = {
                                color: 'white',
                                bold: true,
                                fontSize: 10,
                                alignment: 'center'
                            };

                            // Pastikan content hanya tabel agar tak ada blok sisa
                            doc.content = [{
                                text: '\n'
                            }, customTableNode];
                        }
                    },

                    // =====================================================
                    // =============== EXCEL (default) ======================
                    // =====================================================
                    {
                        extend: 'excelHtml5',
                        className: 'buttons-excel',
                        title: 'Laporan Penyusutan Aset',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                        }
                    }
                ]
            });

            // ==== HIDE KOLOM TANGGAL
            table.column(10).visible(false);

            // FILTER BULAN
            $('#filter-bulan').on('change', function() {
                let bulan = this.value;
                if (bulan === "") {
                    table.column(8).search("").draw();
                } else {
                    table.column(8).search("^" + bulan + "/", true, false).draw();
                }
            });

            // FILTER CABANG
            $('#filter-cabang').on('change', function() {
                table.column(2).search(this.value).draw();
            });

            // FILTER KATEGORI
            $('#filter-kategori').on('change', function() {
                table.column(3).search(this.value).draw();
            });

            // RESET FILTER
            $('#reset-filter').on('click', function(e) {
                e.preventDefault();
                $('#filter-bulan').val('');
                $('#filter-cabang').val('');
                $('#filter-kategori').val('');
                table.search('').columns().search('').draw();
            });

            // MANUAL TRIGGER EXPORT
            $("#btn-export-pdf").on("click", function() {
                table.button('.buttons-pdf').trigger();
            });
            $("#btn-export-excel").on("click", function() {
                table.button('.buttons-excel').trigger();
            });

        });
    </script>
    <?= $this->endSection(); ?>