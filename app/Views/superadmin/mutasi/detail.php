<?= $this->extend('layout/superadmin_template/index'); ?>
<?= $this->section('content'); ?>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 fw-bold">Detail Mutasi Aset</h4>
            <!-- Export PDF button (Bootstrap5 icon) -->
            <button id="btn-export-pdf-detail" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-filetype-pdf"></i> PDF
            </button>

            <a href="<?= site_url('superadmin/mutasi'); ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-sm-5 text-muted">Kode Mutasi</dt>
                    <dd class="col-sm-7"><?= esc($header['kode_mutasi']); ?></dd>

                    <dt class="col-sm-5 text-muted">Tanggal</dt>
                    <dd class="col-sm-7"><?= date('d M Y H:i', strtotime($header['tanggal_mutasi'])); ?></dd>

                    <dt class="col-sm-5 text-muted">Cabang Asal</dt>
                    <dd class="col-sm-7"><?= esc($header['cabang_asal']); ?></dd>

                    <dt class="col-sm-5 text-muted">Cabang Tujuan</dt>
                    <dd class="col-sm-7"><?= esc($header['cabang_tujuan']); ?></dd>
                </dl>
            </div>
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-sm-5 text-muted">Status</dt>
                    <dd class="col-sm-7">
                        <span class="badge <?= $header['status'] === 'selesai' ? 'bg-success' : 'bg-warning'; ?>">
                            <?= ucfirst($header['status']); ?>
                        </span>
                    </dd>
                    <dt class="col-sm-5 text-muted">Catatan</dt>
                    <dd class="col-sm-7"><?= esc($header['catatan']); ?></dd>
                </dl>
            </div>
        </div>

        <h6 class="fw-semibold mb-3 text-secondary">Detail Aset</h6>
        <div class="table-responsive">
            <table class="table table-borderless">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">No</th>
                        <th>Kode Aset</th>
                        <th>Nama Master</th>
                        <th class="text-center">Qty Mutasi</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($details as $d): ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td class="fw-semibold"><?= esc($d['kode_aset']); ?></td>
                            <td><?= esc($d['nama_master']); ?></td>
                            <td class="text-center"><?= (int)$d['qty']; ?></td>
                            <td><?= esc($d['keterangan']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($details)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">Tidak ada detail mutasi.</td>
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

        // CARI ATAU BUAT tombol export di halaman detail
        let $btn = document.getElementById('btn-export-pdf-detail');
        if (!$btn) {
            // coba cari tombol berdasarkan icon bootstrap (fallback)
            $btn = document.querySelector('.btn:has(.bi-filetype-pdf)');
        }
        if (!$btn) {
            // jika masih belum ada, buat tombol kecil di area header (di samping tombol Kembali)
            const headerArea = document.querySelector('.d-flex.justify-content-between.align-items-center');
            if (headerArea) {
                const btn = document.createElement('button');
                btn.id = 'btn-export-pdf-detail';
                btn.className = 'btn btn-outline-danger btn-sm ms-2';
                btn.innerHTML = '<i class="bi bi-filetype-pdf"></i>&nbsp;<span class="d-none d-md-inline">PDF</span>';
                headerArea.appendChild(btn);
                $btn = btn;
            }
        }

        $btn.addEventListener('click', function(e) {
            e.preventDefault();

            // Ambil data header dari server-side (PHP) via embedding
            const kodeMutasi = '<?= esc($header['kode_mutasi']); ?>';
            const tanggalMutasi = '<?= date('d M Y H:i', strtotime($header['tanggal_mutasi'])); ?>';
            const cabangAsal = '<?= esc($header['cabang_asal']); ?>';
            const cabangTujuan = '<?= esc($header['cabang_tujuan']); ?>';
            const status = '<?= ucfirst($header['status']); ?>';
            const catatan = `<?= esc($header['catatan']); ?>`.trim() || '-';

            // Ambil data detail dari DOM
            const rows = [];
            document.querySelectorAll('.table.table-borderless tbody tr').forEach(function(tr) {
                // jika placeholder "Tidak ada detail" (1 td), skip
                const tds = tr.querySelectorAll('td');
                if (!tds || tds.length < 5) return;
                const no = tds[0].innerText.trim();
                const kode = tds[1].innerText.trim();
                const nama = tds[2].innerText.trim();
                const qty = tds[3].innerText.trim();
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
                        alignment: 'cen',
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

            // -----------------------
            // info header mutasi
            // -----------------------
            const infoBlock = {
                table: {
                    widths: ['50%', '50%'],
                    body: [
                        [
                            // KOTAK KIRI (kode, tanggal, cabang asal)
                            {
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
                                    // border hanya di sisi luar, tidak di tengah
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
                                    // rounded effect (pdfMake doesn't support true rounded, but we can fake with margin)
                                    // so add margin to the block
                                },
                                margin: [0, 0, 8, 0] // space between left and right box
                            },
                            // KOTAK KANAN (tujuan, status, catatan)
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
                                margin: [8, 0, 0, 0] // space between left and right box
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
            pdfMake.createPdf(docDefinition).download('Detail_Mutasi_' + kodeMutasi + '.pdf');
        });
    });
</script>
<?= $this->endSection(); ?>