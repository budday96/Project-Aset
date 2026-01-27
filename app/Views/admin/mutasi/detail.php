<?= $this->extend('layout/admin_template/index'); ?>
<?= $this->section('content'); ?>

<div class="card fade-in">
    <div class="card-body">
        <div class="card-header card-header-custom">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold">
                    <i class="bi bi-info-circle-fill me-2"></i>Detail Mutasi Aset
                </h4>
                <div>
                    <button id="btn-export-pdf-detail" class="btn btn-outline-light btn-sm me-2" data-bs-toggle="tooltip" title="Export ke PDF">
                        <i class="bi bi-filetype-pdf"></i> <span class="d-none d-md-inline">PDF</span>
                    </button>
                    <a href="<?= site_url('admin/mutasi'); ?>" class="btn btn-outline-light btn-sm" data-bs-toggle="tooltip" title="Kembali ke Daftar Mutasi">
                        <i class="bi bi-arrow-left"></i> <span class="d-none d-md-inline">Kembali</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Informasi Header Mutasi -->
            <div class="row mb-4">
                <div class="col-lg-6 col-12 mb-3">
                    <div class="info-card p-3">
                        <h6 class="fw-semibold mb-3">
                            <i class="bi bi-card-text me-1"></i>Informasi Mutasi
                        </h6>
                        <div class="row g-2">
                            <div class="col-12">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-tag-fill text-muted me-2"></i>
                                    <strong class="text-muted me-2">Kode Mutasi:</strong>
                                    <span class="fw-semibold"><?= esc($header['kode_mutasi']); ?></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar-event text-muted me-2"></i>
                                    <strong class="text-muted me-2">Tanggal:</strong>
                                    <span><?= date('d M Y H:i', strtotime($header['tanggal_mutasi'])); ?></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-geo-alt-fill text-muted me-2"></i>
                                    <strong class="text-muted me-2">Cabang Asal:</strong>
                                    <span><?= esc($header['cabang_asal']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="info-card p-3">
                        <h6 class="fw-semibold mb-3">
                            <i class="bi bi-check-circle me-1"></i>Status & Detail
                        </h6>
                        <div class="row g-2">
                            <div class="col-12">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-geo-alt-fill text-muted me-2"></i>
                                    <strong class="text-muted me-2">Cabang Tujuan:</strong>
                                    <span><?= esc($header['cabang_tujuan']); ?></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-flag-fill text-muted me-2"></i>
                                    <strong class="text-muted me-2">Status:</strong>
                                    <span class="badge <?= $header['status'] === 'selesai' ? 'bg-success' : 'bg-warning'; ?> fs-6">
                                        <i class="bi <?= $header['status'] === 'selesai' ? 'bi-check-circle' : 'bi-clock'; ?> me-1"></i>
                                        <?= ucfirst($header['status']); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-chat-dots text-muted me-2 mt-1"></i>
                                    <strong class="text-muted me-2">Catatan:</strong>
                                    <span class="flex-grow-1"><?= esc($header['catatan']) ?: '-'; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Aset -->
            <h6 class="fw-semibold mb-3 text-secondary">
                <i class="bi bi-list-ul me-1"></i>Detail Aset
            </h6>
            <div class="table-responsive">
                <table class="table table-hover table-striped border">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%;">No</th>
                            <th style="width: 20%;">Kode Aset</th>
                            <th style="width: 30%;">Nama Master</th>
                            <th class="text-center">
                                <i class="bi bi-hash me-1"></i>Qty Mutasi
                            </th>
                            <th style="width: 30%;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($details as $d): ?>
                            <tr>
                                <td class="text-center fw-semibold"><?= $no++; ?></td>
                                <td class="fw-semibold"><?= esc($d['kode_aset']); ?></td>
                                <td><?= esc($d['nama_master']); ?></td>
                                <td class="text-center">
                                    <span class="badge bg-warning fs-6">
                                        <i class="bi bi-box-seam me-1"></i><?= (int)$d['qty']; ?>
                                    </span>
                                </td>
                                <td class="text-truncate" style="max-width: 200px;" title="<?= esc($d['keterangan']); ?>">
                                    <?= esc($d['keterangan']); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($details)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-info-circle fs-1 text-secondary mb-2"></i>
                                    <br>Tidak ada detail mutasi.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('css'); ?>

<style>
    /* Custom styles for better UX */
    .card-header-custom {
        background: #fd7e14;
        color: white;
        border-radius: 0.375rem 0.375rem 0 0;
    }

    .info-card {
        border: 1px solid #e9ecef;
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease;
    }

    .info-card:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .btn-loading {
        pointer-events: none;
        opacity: 0.6;
    }
</style>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Export PDF with loading spinner
        const btnExport = document.getElementById('btn-export-pdf-detail');
        btnExport.addEventListener('click', function(e) {
            e.preventDefault();

            // Show loading state
            btnExport.classList.add('btn-loading');
            btnExport.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Generating...';

            // Simulate delay for UX (optional, remove if not needed)
            setTimeout(() => {
                // Ambil data header dari server-side (PHP) via embedding
                const kodeMutasi = '<?= esc($header['kode_mutasi']); ?>';
                const tanggalMutasi = '<?= date('d M Y H:i', strtotime($header['tanggal_mutasi'])); ?>';
                const cabangAsal = '<?= esc($header['cabang_asal']); ?>';
                const cabangTujuan = '<?= esc($header['cabang_tujuan']); ?>';
                const status = '<?= ucfirst($header['status']); ?>';
                const catatan = `<?= esc($header['catatan']); ?>`.trim() || '-';

                // Ambil data detail dari DOM
                const rows = [];
                document.querySelectorAll('.table.table-hover tbody tr').forEach(function(tr) {
                    const tds = tr.querySelectorAll('td');
                    if (!tds || tds.length < 5) return;
                    const no = tds[0].innerText.trim();
                    const kode = tds[1].innerText.trim();
                    const nama = tds[2].innerText.trim();
                    const qty = tds[3].innerText.trim().replace(/[^\d]/g, ''); // Extract number from badge
                    const keterangan = tds[4].innerText.trim();
                    rows.push([no, kode, nama, qty, keterangan]);
                });

                // Build body table (header + rows)
                const body = [];
                body.push([{
                        text: 'No',
                        style: 'tableHeader'
                    },
                    {
                        text: 'Kode Aset',
                        style: 'tableHeader'
                    },
                    {
                        text: 'Nama Master',
                        style: 'tableHeader'
                    },
                    {
                        text: 'Qty Mutasi',
                        style: 'tableHeader'
                    },
                    {
                        text: 'Keterangan',
                        style: 'tableHeader'
                    }
                ]);
                rows.forEach(function(r) {
                    body.push([{
                            text: r[0],
                            alignment: 'center',
                            margin: [6, 6, 6, 6],
                            fontSize: 9
                        },
                        {
                            text: r[1],
                            alignment: 'center',
                            margin: [6, 6, 6, 6],
                            fontSize: 9
                        },
                        {
                            text: r[2],
                            alignment: 'center',
                            margin: [6, 6, 6, 6],
                            fontSize: 9
                        },
                        {
                            text: r[3],
                            alignment: 'center',
                            margin: [6, 6, 6, 6],
                            fontSize: 9
                        },
                        {
                            text: r[4],
                            alignment: 'center',
                            margin: [6, 6, 6, 6],
                            fontSize: 9
                        }
                    ]);
                });

                // Custom table node (header biru gelap + tanpa border body)
                const tableNode = {
                    table: {
                        headerRows: 1,
                        widths: [40, 120, '*', 70, 170],
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

                // Info header mutasi
                const infoBlock = {
                    table: {
                        widths: ['50%', '50%'],
                        body: [
                            [{
                                    table: {
                                        widths: ['35%', '*'],
                                        body: [
                                            [{
                                                text: 'Kode Mutasi',
                                                bold: true,
                                                fontSize: 9
                                            }, {
                                                text: kodeMutasi,
                                                fontSize: 9
                                            }],
                                            [{
                                                text: 'Tanggal',
                                                bold: true,
                                                fontSize: 9
                                            }, {
                                                text: tanggalMutasi,
                                                fontSize: 9
                                            }],
                                            [{
                                                text: 'Cabang Asal',
                                                bold: true,
                                                fontSize: 9
                                            }, {
                                                text: cabangAsal,
                                                fontSize: 9
                                            }]
                                        ]
                                    },
                                    layout: {
                                        hLineWidth: function(i, node) {
                                            return (i === 0 || i === node.table.body.length) ? 0.7 : 0;
                                        },
                                        vLineWidth: function(i, node) {
                                            return (i === 0 || i === node.table.widths.length) ? 0.7 : 0;
                                        },
                                        hLineColor: function() {
                                            return '#9aa7b0';
                                        },
                                        vLineColor: function() {
                                            return '#9aa7b0';
                                        },
                                        paddingLeft: function() {
                                            return 8;
                                        },
                                        paddingRight: function() {
                                            return 8;
                                        },
                                        paddingTop: function() {
                                            return 6;
                                        },
                                        paddingBottom: function() {
                                            return 6;
                                        },
                                    },
                                    margin: [0, 0, 8, 0]
                                },
                                {
                                    table: {
                                        widths: ['40%', '*'],
                                        body: [
                                            [{
                                                text: 'Cabang Tujuan',
                                                bold: true,
                                                fontSize: 9
                                            }, {
                                                text: cabangTujuan,
                                                fontSize: 9
                                            }],
                                            [{
                                                text: 'Status',
                                                bold: true,
                                                fontSize: 9
                                            }, {
                                                text: status,
                                                fontSize: 9
                                            }],
                                            [{
                                                text: 'Catatan',
                                                bold: true,
                                                fontSize: 9
                                            }, {
                                                text: (catatan || '-'),
                                                fontSize: 9
                                            }]
                                        ]
                                    },
                                    layout: {
                                        hLineWidth: function(i, node) {
                                            return (i === 0 || i === node.table.body.length) ? 0.7 : 0;
                                        },
                                        vLineWidth: function(i, node) {
                                            return (i === 0 || i === node.table.widths.length) ? 0.7 : 0;
                                        },
                                        hLineColor: function() {
                                            return '#9aa7b0';
                                        },
                                        vLineColor: function() {
                                            return '#9aa7b0';
                                        },
                                        paddingLeft: function() {
                                            return 8;
                                        },
                                        paddingRight: function() {
                                            return 8;
                                        },
                                        paddingTop: function() {
                                            return 6;
                                        },
                                        paddingBottom: function() {
                                            return 6;
                                        }
                                    },
                                    margin: [8, 0, 0, 0]
                                }
                            ]
                        ]
                    },
                    layout: 'noBorders',
                    margin: [0, 6, 0, 12]
                };

                // Doc definition (gaya sama seperti aset/penyusutan)
                const docDefinition = {
                    pageOrientation: 'landscape',
                    pageSize: 'A4',
                    pageMargins: [30, 110, 30, 40],
                    defaultStyle: {
                        fontSize: 10
                    },
                    header: function(currentPage, pageCount) {
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
                                    text: "DETAIL MUTASI: " + kodeMutasi,
                                    bold: true,
                                    fontSize: 13,
                                    margin: [0, 6, 0, 0]
                                }
                            ]
                        };
                    },
                    footer: function(page, pages) {
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
                    },
                    content: [
                        // Info (rata rapi dua kolom)
                        infoBlock,
                        // Spacer kecil
                        {
                            text: '\n'
                        },
                        // Tabel detail
                        tableNode
                    ],
                    styles: {
                        tableHeader: {
                            color: 'white',
                            bold: true,
                            fontSize: 10,
                            alignment: 'center'
                        }
                    }
                };

                // Generate and download PDF
                pdfMake.createPdf(docDefinition).download('Detail_Mutasi_' + kodeMutasi + '.pdf');

                // Reset button after download
                btnExport.classList.remove('btn-loading');
                btnExport.innerHTML = '<i class="bi bi-filetype-pdf"></i> <span class="d-none d-md-inline">PDF</span>';
            }, 1000); // Simulated delay for UX (adjust or remove as needed)
        });
    });
</script>
<?= $this->endSection(); ?>