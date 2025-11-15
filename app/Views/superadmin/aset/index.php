<?= $this->extend('layout/superadmin_template/index'); ?>

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
        <div class="card-header bg-white py-3 px-4">
            <!-- TOP BAR: TITLE + ACTION BUTTONS -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
                <h4 class="fw-bold mb-3 mb-md-0">📦 List Aset Barang</h4>

                <div class="d-flex flex-wrap gap-2">
                    <!-- EXPORT PDF -->
                    <button id="btn-export-pdf" class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" title="Export PDF">
                        <i class="bi bi-filetype-pdf fs-5"></i>
                    </button>

                    <!-- EXPORT EXCEL -->
                    <button id="btn-export-excel" class="btn btn-outline-success btn-sm" data-bs-toggle="tooltip" title="Export Excel">
                        <i class="bi bi-filetype-xls fs-5"></i>
                    </button>
                    <a href="<?= base_url('/superadmin/aset'); ?>" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="Refresh">
                        <i class="bi bi-arrow-clockwise fs-5"></i>
                    </a>
                    <a class="btn btn-warning btn-sm fw-semibold d-flex align-items-center" href="<?= base_url('superadmin/aset/create') ?>">
                        <i class="bi bi-plus-circle me-1 fs-5"></i>
                        <span class="d-none d-sm-inline">Tambah Aset</span>
                        <span class="d-inline d-sm-none">Add</span>
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
                            <option value="">Semua</option>
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
                    <div class="col-md-1 col-12 d-flex align-items-end">
                        <button id="reset-filter" class="btn btn-outline-danger btn-sm w-100">
                            Reset
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
                                        <a href="<?= base_url('superadmin/aset/detail/' . $aset['id_aset']) ?>"
                                            class="btn btn-sm"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="View">
                                            <i class="bi bi-eye" style="color: #fd7e14;"></i>
                                        </a>

                                        <!-- Tombol Edit -->
                                        <a href="<?= base_url('superadmin/aset/edit/' . $aset['id_aset']) ?>"
                                            class="btn btn-sm"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Edit">
                                            <i class="bi bi-pen" style="color: #fd7e14;"></i>
                                        </a>

                                        <!-- Tombol Delete -->
                                        <form action="<?= base_url('superadmin/aset/delete/' . $aset['id_aset']) ?>"
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
            let tanggal = data[2];

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
                {
                    extend: 'pdfHtml5',
                    className: 'buttons-pdf',
                    filename: 'Laporan Aset Barang',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: [2, 3, 4, 5, 6, 7]
                    },

                    customize: function(doc) {

                        // ============================================
                        //   MODE PORTRAIT
                        // ============================================
                        doc.pageOrientation = 'portrait';
                        doc.pageSize = 'A4';

                        // Margin agar header tidak nabrak
                        doc.pageMargins = [30, 100, 30, 40];


                        // ============================================
                        //   HEADER — hanya di halaman pertama
                        // ============================================
                        doc['header'] = function(page) {
                            if (page === 1) {
                                return {
                                    margin: [0, 25, 0, 15],
                                    alignment: 'center',
                                    stack: [{
                                            text: "PT MYASSET INDONESIA",
                                            bold: true,
                                            fontSize: 16,
                                            margin: [0, 0, 0, 2]
                                        },
                                        {
                                            text: "ASSET MANAGEMENT DIVISION",
                                            fontSize: 11,
                                            margin: [0, -1, 0, 2]
                                        },
                                        {
                                            text: "Jl. Merdeka No. 123, Jakarta Pusat",
                                            fontSize: 9
                                        },
                                        {
                                            text: "Email: support@myasset.co.id | Telp: (021) 5544 8899",
                                            fontSize: 9
                                        }
                                    ]
                                };
                            }
                            return {
                                text: ""
                            };
                        };


                        // ============================================
                        //   TITLE – buang elemen bawaan (Kelola Aset)
                        // ============================================

                        // Hapus dulu seluruh title bawaan DataTables agar tidak muncul "Kelola Aset"
                        doc.content = doc.content.filter(item =>
                            !(item.text && item.text.toString().includes("Kelola Aset"))
                        );

                        // Tambahkan title baru yang rapi
                        doc.content.unshift({
                            text: "LAPORAN DATA ASET BARANG",
                            alignment: "center",
                            bold: true,
                            fontSize: 14,
                            margin: [0, 0, 0, 14]
                        });


                        // ============================================
                        //   FOOTER
                        // ============================================
                        doc['footer'] = function(page, pages) {
                            return {
                                margin: [25, 10, 25, 0],
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


                        // ============================================
                        //   BUILD TABLE MANUAL
                        // ============================================
                        let dt = $('#mytable').DataTable();
                        let data = dt.rows({
                            search: 'applied'
                        }).data().toArray();

                        let body = [];

                        // Header
                        body.push([{
                                text: 'Tanggal',
                                style: 'tableHeader'
                            },
                            {
                                text: 'Nama Barang',
                                style: 'tableHeader'
                            },
                            {
                                text: 'Kategori',
                                style: 'tableHeader'
                            },
                            {
                                text: 'Cabang',
                                style: 'tableHeader'
                            },
                            {
                                text: 'Status',
                                style: 'tableHeader'
                            },
                            {
                                text: 'Kondisi',
                                style: 'tableHeader'
                            }
                        ]);

                        // Rows
                        data.forEach(row => {
                            let namaBarang = $("<div>" + row[3] + "</div>").text().trim();
                            let status = $("<div>" + row[6] + "</div>").text().trim();
                            let kondisi = $("<div>" + row[7] + "</div>").text().trim();

                            body.push([{
                                    text: row[2],
                                    alignment: 'center',
                                    fontSize: 9.7,
                                    margin: [3, 4, 3, 4]
                                },
                                {
                                    text: namaBarang,
                                    alignment: 'left',
                                    fontSize: 9.7,
                                    margin: [3, 4, 3, 4]
                                },
                                {
                                    text: row[4],
                                    alignment: 'center',
                                    fontSize: 9.7,
                                    margin: [3, 4, 3, 4]
                                },
                                {
                                    text: row[5],
                                    alignment: 'center',
                                    fontSize: 9.7,
                                    margin: [3, 4, 3, 4]
                                },
                                {
                                    text: status,
                                    alignment: 'center',
                                    fontSize: 9.7,
                                    margin: [3, 4, 3, 4]
                                },
                                {
                                    text: kondisi,
                                    alignment: 'center',
                                    fontSize: 9.7,
                                    margin: [3, 4, 3, 4]
                                }
                            ]);
                        });

                        doc.content[doc.content.length - 1].table = {
                            headerRows: 1,
                            widths: [60, '*', 70, 60, 55, 55],
                            body: body
                        };

                        // Style header tabel
                        doc.styles.tableHeader = {
                            fillColor: '#003366',
                            color: 'white',
                            bold: true,
                            alignment: 'center',
                            fontSize: 10.5,
                            margin: [0, 5, 0, 5]
                        };

                        // Border tabel
                        doc.content[doc.content.length - 1].layout = {
                            hLineWidth: () => 0.7,
                            vLineWidth: () => 0.7,
                            hLineColor: () => '#cccccc',
                            vLineColor: () => '#cccccc',
                            paddingLeft: () => 3,
                            paddingRight: () => 3
                        };
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
        table.column(2).visible(false);

        // ==== FILTER KATEGORI
        $('#filter-kategori').on('change', function() {
            table.column(4).search(this.value).draw();
        });

        // ==== FILTER CABANG
        $('#filter-cabang').on('change', function() {
            table.column(5).search(this.value).draw();
        });

        // ==== FILTER KONDISI
        $('#filter-kondisi').on('change', function() {
            table.column(7).search(this.value).draw();
        });

        // ==== FILTER TANGGAL
        $('#filter-tanggal-awal, #filter-tanggal-akhir').on('change', function() {
            table.draw();
        });

        // ==== RESET FILTER
        $('#reset-filter').on('click', function() {
            $('#filter-kategori').val('');
            $('#filter-cabang').val('');
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