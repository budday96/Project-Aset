<?= $this->extend('layout/admin_template/index'); ?>

<?= $this->section('content'); ?>

<?php if (session()->getFlashdata('error')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            showToast("<?= esc(session()->getFlashdata('error')) ?>", 'danger');
        });
    </script>
<?php endif; ?>

<?php if (session()->getFlashdata('success')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            showToast("<?= esc(session()->getFlashdata('success')) ?>", 'success');
        });
    </script>
<?php endif; ?>


<div class="card">
    <div class="card-body">
        <div class="card-header bg-light py-3 px-4 border-bottom">
            <!-- TOP BAR: TITLE + ACTION BUTTONS -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">

                <!-- TITLE -->
                <h4 class="fw-bold mb-3 mb-md-0 text-center text-md-start w-100">
                    📦 List Aset Barang
                </h4>

                <!-- BUTTON GROUP -->
                <div class="d-flex flex-wrap gap-2 w-100 w-md-auto justify-content-center justify-content-md-end">

                    <!-- EXPORT PDF -->
                    <button id="btn-export-pdf" class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" title="Export PDF">
                        <i class="bi bi-filetype-pdf fs-5"></i>
                    </button>

                    <!-- EXPORT EXCEL -->
                    <button id="btn-export-excel" class="btn btn-outline-success btn-sm" data-bs-toggle="tooltip" title="Export Excel">
                        <i class="bi bi-filetype-xls fs-5"></i>
                    </button>

                    <!-- REFRESH -->
                    <a href="<?= base_url('/admin/aset'); ?>"
                        class="btn btn-outline-secondary btn-sm"
                        data-bs-toggle="tooltip" title="Refresh">
                        <i class="bi bi-arrow-clockwise fs-5"></i>
                    </a>

                    <!-- TAMBAH ASET -->
                    <a class="btn btn-warning btn-sm fw-semibold d-flex align-items-center justify-content-center px-2"
                        href="<?= base_url('admin/aset/create') ?>">

                        <i class="bi bi-plus-circle me-1 fs-5"></i>
                        <span class="text-truncate" style="max-width: 90px;">Tambah Aset</span>
                    </a>
                </div>
            </div>


            <!-- FILTER SECTION -->
            <div class="p-3 rounded-3 border">
                <div class="row g-3">

                    <!-- Filter Cabang -->
                    <div class="col-md-2 col-6">
                        <label class="form-label fw-semibold mb-1">Cabang</label>
                        <select id="filter-cabang" class="form-select form-select-sm">
                            <?php foreach ($cabangs as $c): ?>
                                <option value="<?= esc($c['nama_cabang']) ?>"><?= esc($c['nama_cabang']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filter Kategori -->
                    <div class="col-md-2 col-6">
                        <label class="form-label fw-semibold mb-1">Kategori</label>
                        <select id="filter-kategori" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            <?php foreach ($kategoris as $k): ?>
                                <option value="<?= esc($k['nama_kategori']) ?>"><?= esc($k['nama_kategori']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filter Kondisi -->
                    <div class="col-md-2 col-6">
                        <label class="form-label fw-semibold mb-1">Kondisi</label>
                        <select id="filter-kondisi" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            <option value="Baik">Baik</option>
                            <option value="Rusak Ringan">Rusak Ringan</option>
                            <option value="Rusak Berat">Rusak Berat</option>
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
        </div>

        <div class="card-body px-0">
            <div class="table-responsive rounded mb-3">
                <table id="mytable" class="table table-hover">
                    <thead class="bg-white">
                        <tr class="light light-data">
                            <th>
                                <div class="checkbox d-inline-block">
                                    <input type="checkbox" class="checkbox-input" id="checkbox1">
                                    <label for="checkbox1" class="mb-0"></label>
                                </div>
                            </th>
                            <th>Gambar</th>
                            <th>Kode Barang</th>
                            <th>Tanggal Input</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Cabang</th>
                            <th>Status</th>
                            <th>Kondisi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($asets as $i => $aset): ?>
                            <tr>
                                <td class="align-middle">
                                    <div class="checkbox d-inline-block">
                                        <?php $checkboxId = 'checkbox_' . $aset['id_aset']; ?>
                                        <input type="checkbox"
                                            class="checkbox-input"
                                            id="<?= $checkboxId ?>"
                                            name="selected[]"
                                            value="<?= $aset['id_aset'] ?>">
                                        <label for="<?= $checkboxId ?>" class="mb-0"></label>
                                    </div>
                                </td>
                                <td style="vertical-align: middle;">
                                    <?php
                                    // Tentukan path gambar (fallback otomatis)
                                    $imgFile = $aset['gambar'] ?: 'no-image.png';
                                    $imgPath = FCPATH . 'uploads/aset/' . $imgFile;

                                    // Jika file tidak ditemukan, fallback ke no-image.png
                                    if (!is_file($imgPath)) {
                                        $imgFile = 'no-image.png';
                                    }
                                    ?>
                                    <img src="<?= base_url('uploads/aset/' . $imgFile) ?>"
                                        alt="Gambar Aset"
                                        class="aset-thumbnail"
                                        onerror="this.src='<?= base_url('uploads/aset/no-image.png') ?>'">
                                </td>
                                <td class="align-middle"><?= esc($aset['kode_aset']) ?></td>
                                <td class="text-start align-middle">
                                    <?= date('Y-m-d', strtotime($aset['created_at'])) ?>
                                </td>
                                <td class="align-middle">
                                    <?= esc($aset['nama_master'] ?? '-') ?>
                                    <?php if (!empty($aset['master_deleted_at'])): ?>
                                        <span class="badge bg-secondary ms-1">arsip</span>
                                    <?php endif ?>
                                </td>
                                <td class="align-middle"><?= esc($aset['nama_kategori']) ?></td>
                                <td class="align-middle"><?= esc($aset['nama_cabang']) ?></td>
                                <td class="align-middle">
                                    <span class="badge bg-<?=
                                                            $aset['status'] == 'Digunakan' ? 'primary' : (
                                                                $aset['status'] == 'Tidak Digunakan' ? 'secondary' : (
                                                                    $aset['status'] == 'Hilang' ? 'danger' : 'light'
                                                                )
                                                            )
                                                            ?>">
                                        <?= esc($aset['status']) ?>
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <span class="badge bg-<?=
                                                            $aset['kondisi'] == 'Baik' ? 'success' : (
                                                                $aset['kondisi'] == 'Rusak Ringan' ? 'warning' : (
                                                                    $aset['kondisi'] == 'Rusak Berat' ? 'danger' : 'secondary'
                                                                )
                                                            )
                                                            ?>">
                                        <?= esc($aset['kondisi']) ?>
                                    </span>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="d-flex justify-content-center align-items-center list-action">

                                        <!-- Tombol View -->
                                        <a href="<?= base_url('admin/aset/detail/' . $aset['id_aset']) ?>"
                                            class="btn btn-sm"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="View">
                                            <i class="bi bi-eye" style="color: #fd7e14;"></i>
                                        </a>

                                        <!-- Tombol Edit -->
                                        <a href="<?= base_url('admin/aset/edit/' . $aset['id_aset']) ?>"
                                            class="btn btn-sm"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Edit">
                                            <i class="bi bi-pen" style="color: #fd7e14;"></i>
                                        </a>

                                        <!-- Tombol Delete -->
                                        <form action="<?= base_url('admin/aset/delete/' . $aset['id_aset']) ?>"
                                            method="post"
                                            class="d-inline"
                                            onsubmit="return confirm('Hapus aset ini?')">
                                            <?= csrf_field() ?>
                                            <button type="submit"
                                                class="btn btn-sm"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="Delete">
                                                <i class="bi bi-trash3" style="color: #fd7e14;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('css'); ?>
<style>
    .aset-thumbnail {
        width: 35px;
        height: 35px;
        border-radius: 6px;
        object-fit: cover;
        background: #f6f8fb;
        display: block;
    }

    .aset-thumbnail.placeholder {
        color: #b0b3bb;
        font-size: 24px;
    }

    .form-label {
        font-size: .75rem;
        font-weight: 600;
        color: #6c757d;
    }

    /* ======== RESPONSIVE FIX FOR MOBILE ======== */

    @media (max-width: 576px) {

        /* Title lebih kecil */
        h4.fw-bold {
            font-size: 1.1rem;
        }

        /* Tombol aksi pada top bar agar tidak overflow */
        .d-flex.flex-wrap.gap-2 button,
        .d-flex.flex-wrap.gap-2 a {
            flex: 1 1 calc(50% - 6px);
            text-align: center;
        }

        /* Filter grid lebih nyaman */
        .form-select-sm,
        .form-control-sm {
            font-size: 0.75rem !important;
        }

        /* Table thumbnail lebih kecil */
        .aset-thumbnail {
            width: 28px !important;
            height: 28px !important;
        }

        /* Aksi table rapat dan kecil */
        #mytable td .btn {
            padding: 2px 5px !important;
        }

        /* Perkecil badge */
        .badge {
            font-size: 0.65rem !important;
        }

        /* Table responsive wrap */
        table.dataTable td {
            white-space: nowrap;
        }

    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {

        // === FILTER RANGE TANGGAL ===
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            let start = $('#filter-tanggal-awal').val();
            let end = $('#filter-tanggal-akhir').val();
            let tanggal = data[3];

            if (!tanggal) return true;
            if (start === "" && end === "") return true;

            let tgl = new Date(tanggal);
            let tglStart = start ? new Date(start) : null;
            let tglEnd = end ? new Date(end) : null;

            return (
                (!tglStart || tgl >= tglStart) &&
                (!tglEnd || tgl <= tglEnd)
            );
        });

        // =====================================================
        // =============== INIT DATATABLES ======================
        // =====================================================

        let table = $('#mytable').DataTable({
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
            },

            buttons: [

                // =====================================================
                // =============== PDF PROFESIONAL ======================
                // =====================================================
                // === PDF (include Kode Aset) ===
                {
                    extend: 'pdfHtml5',
                    className: 'buttons-pdf',
                    filename: 'Laporan_Aset_Barang',
                    orientation: 'landscape',
                    pageSize: 'A4',

                    // sertakan Kode (2), Tanggal (3), Nama (4), Kategori (5), Cabang (6), Status (7), Kondisi (8)
                    exportOptions: {
                        columns: [2, 3, 4, 5, 6, 7, 8]
                    },

                    customize: function(doc) {
                        // margin & dasar
                        doc.pageMargins = [30, 110, 30, 40];
                        doc.defaultStyle.fontSize = 10;
                        doc.styles.tableHeader = {
                            color: 'white',
                            bold: true,
                            alignment: 'center',
                            fontSize: 10
                        };

                        // header tetap seperti milik Anda
                        doc['header'] = function() {
                            return {
                                margin: [0, 20, 0, 10],
                                alignment: 'center',
                                stack: [{
                                        text: "PT MYASSET INDONESIA",
                                        bold: true,
                                        fontSize: 16,
                                        margin: [0, 0, 0, 3]
                                    },
                                    {
                                        text: "ASSET MANAGEMENT DIVISION",
                                        fontSize: 11,
                                        margin: [0, 0, 0, 3]
                                    },
                                    {
                                        text: "Jl. Merdeka No. 123, Jakarta Pusat",
                                        fontSize: 9
                                    },
                                    {
                                        text: "Email: support@myasset.co.id | Telp: (021) 5544 8899",
                                        fontSize: 9,
                                        margin: [0, 0, 0, 5]
                                    },
                                    {
                                        text: "LAPORAN DATA ASET BARANG",
                                        bold: true,
                                        fontSize: 13,
                                        margin: [0, 6, 0, 0]
                                    }
                                ]
                            };
                        };

                        // Ambil data yang terfilter
                        let dt = $('#mytable').DataTable();
                        let rows = dt.rows({
                            search: 'applied'
                        }).data().toArray();

                        // Build body table
                        let body = [];
                        body.push([{
                                text: "Kode Barang",
                                style: "tableHeader"
                            },
                            {
                                text: "Tanggal",
                                style: "tableHeader"
                            },
                            {
                                text: "Nama Barang",
                                style: "tableHeader"
                            },
                            {
                                text: "Kategori",
                                style: "tableHeader"
                            },
                            {
                                text: "Cabang",
                                style: "tableHeader"
                            },
                            {
                                text: "Status",
                                style: "tableHeader"
                            },
                            {
                                text: "Kondisi",
                                style: "tableHeader"
                            }
                        ]);

                        rows.forEach(row => {
                            let kode = $("<div>" + row[2] + "</div>").text().trim();
                            let tanggal = $("<div>" + row[3] + "</div>").text().trim();
                            let namaBarang = $("<div>" + row[4] + "</div>").text().trim();
                            let kategori = $("<div>" + row[5] + "</div>").text().trim();
                            let cabang = $("<div>" + row[6] + "</div>").text().trim();
                            let status = $("<div>" + row[7] + "</div>").text().trim();
                            let kondisi = $("<div>" + row[8] + "</div>").text().trim();

                            body.push([{
                                    text: kode,
                                    alignment: "center",
                                    margin: [6, 8, 6, 8],
                                    fontSize: 9
                                },
                                {
                                    text: tanggal,
                                    alignment: "center",
                                    margin: [6, 8, 6, 8],
                                    fontSize: 9
                                },
                                {
                                    text: namaBarang,
                                    alignment: "left",
                                    margin: [6, 8, 6, 8],
                                    fontSize: 9
                                },
                                {
                                    text: kategori,
                                    alignment: "center",
                                    margin: [6, 8, 6, 8],
                                    fontSize: 9
                                },
                                {
                                    text: cabang,
                                    alignment: "center",
                                    margin: [6, 8, 6, 8],
                                    fontSize: 9
                                },
                                {
                                    text: status,
                                    alignment: "center",
                                    margin: [6, 8, 6, 8],
                                    fontSize: 9
                                },
                                {
                                    text: kondisi,
                                    alignment: "center",
                                    margin: [6, 8, 6, 8],
                                    fontSize: 9
                                }
                            ]);
                        });

                        // Buat node table custom tanpa border body (hanya header diberi background)
                        let customTableNode = {
                            table: {
                                headerRows: 1,
                                widths: [90, 80, '*', 100, 90, 80, 80],
                                body: body
                            },
                            layout: {
                                // hilangkan semua garis vertikal
                                vLineWidth: function(i, node) {
                                    return 0;
                                },
                                // hilangkan garis horizontal antar baris (body), sisakan sedikit garis di atas header dan di bawah tabel
                                hLineWidth: function(i, node) {
                                    // i === 0 -> garis paling atas tabel (biarkan tipis)
                                    // i === node.table.body.length -> garis paling bawah tabel (biarkan tipis)
                                    // lainnya -> 0 (hilangkan)
                                    if (i === 0 || i === node.table.body.length) return 0.4;
                                    return 0;
                                },
                                hLineColor: function(i, node) {
                                    return '#e0e0e0';
                                },
                                paddingLeft: function(i, node) {
                                    return 8;
                                },
                                paddingRight: function(i, node) {
                                    return 8;
                                },
                                paddingTop: function(i, node) {
                                    return 6;
                                },
                                paddingBottom: function(i, node) {
                                    return 6;
                                },
                                // beri background header dan striping sangat halus (opsional)
                                fillColor: function(rowIndex, node, columnIndex) {
                                    if (rowIndex === 0) return '#0b4a6f'; // header gelap
                                    // striping: gunakan null untuk bersih atau warna halus jika mau
                                    return null; // atau: (rowIndex % 2 === 0) ? '#fbfdff' : null;
                                }
                            }
                        };

                        // Ganti seluruh content agar tidak ada blok tersisa (menghindari 'tabel kecil kosong')
                        doc.content = [
                            // spacer untuk memberi jarak antara header pdf dan tabel
                            {
                                text: '\n'
                            },
                            customTableNode
                        ];

                        // Footer (tetap)
                        doc['footer'] = function(page, pages) {
                            return {
                                margin: [30, 10, 30, 0],
                                columns: [{
                                        text: "Generated: " + new Date().toLocaleDateString(),
                                        alignment: "left",
                                        fontSize: 9
                                    },
                                    {
                                        text: "Page " + page + " of " + pages,
                                        alignment: "right",
                                        fontSize: 9
                                    }
                                ]
                            };
                        };
                    }
                },


                // === EXCEL (include Kode Aset) ===
                {
                    extend: 'excelHtml5',
                    className: 'buttons-excel',
                    title: 'Laporan Aset Barang',
                    exportOptions: {
                        columns: [2, 3, 4, 5, 6, 7, 8] // include Kode (2)
                    }
                },

                // =====================================================
                // =============== EXCEL (default) ======================
                // =====================================================
                {
                    extend: 'excelHtml5',
                    className: 'buttons-excel',
                    title: 'Laporan Aset Barang',
                    exportOptions: {
                        columns: [2, 3, 4, 5, 6, 7]
                    }
                }
            ]
        });

        // ==== HIDE KOLOM TANGGAL
        table.column(3).visible(false);

        // ==== FILTER KATEGORI
        $('#filter-kategori').on('change', function() {
            table.column(5).search(this.value).draw();
        });

        // ==== FILTER CABANG
        $('#filter-cabang').on('change', function() {
            table.column(6).search(this.value).draw();
        });

        // ==== FILTER KONDISI
        $('#filter-kondisi').on('change', function() {
            table.column(8).search(this.value).draw();
        });

        // ==== FILTER TANGGAL
        $('#filter-tanggal-awal, #filter-tanggal-akhir').on('change', function() {
            table.draw();
        });

        // ==== RESET FILTER
        $('#reset-filter').on('click', function() {
            $('#filter-kategori').val('');
            $('#filter-kondisi').val('');
            $('#filter-tanggal-awal').val('');
            $('#filter-tanggal-akhir').val('');
            table.search('').columns().search('').draw();
        });

        // ==== CUSTOM BUTTON TRIGGER
        $("#btn-export-pdf").on("click", function() {
            table.button('.buttons-pdf').trigger();
        });
        $("#btn-export-excel").on("click", function() {
            table.button('.buttons-excel').trigger();
        });
        $("#btn-export-print").on("click", function() {
            table.button('.buttons-print').trigger();
        });

    });
</script>

<?= $this->endSection(); ?>