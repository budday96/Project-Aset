<?= $this->extend('layout/admin_template/index'); ?>
<?= $this->section('content'); ?>

<style>
    /* .card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    } */

    /* .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    } */

    /* KPI Cards */
    .card-kpi {
        border-radius: 16px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .card-kpi::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.1);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .card-kpi:hover::before {
        opacity: 1;
    }

    .card-kpi:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .kpi-icon {
        font-size: 40px;
        opacity: 0.8;
        transition: opacity 0.3s ease;
    }

    .card-kpi:hover .kpi-icon {
        opacity: 1;
    }

    /* Status Badges */
    .status-active {
        color: white;
        background: linear-gradient(135deg, #28a745, #20c997);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
    }

    .status-inactive {
        color: white;
        background: linear-gradient(135deg, #dc3545, #fd7e14);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
    }

    /* Charts */
    #chartKondisi {
        max-height: 250px !important;
    }

    .legend-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    /* Tables */
    .table {
        border-radius: 12px;
        overflow: hidden;
    }

    .table thead th {
        background: linear-gradient(135deg, #fd7e14, #fca45b);
        color: white;
        border: none;
        font-weight: 600;
    }

    /* User List */
    .list-group-item {
        border: none;
        background: transparent;
        padding: 12px 0;
        transition: all 0.2s ease;
    }

    .list-group-item:hover {
        background: rgba(0, 123, 255, 0.05);
        border-radius: 8px;
    }

    /* Buttons and Links */
    .btn {
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {

        .col-md-3,
        .col-md-4,
        .col-md-6,
        .col-md-12 {
            margin-bottom: 20px;
        }

        .card-kpi {
            padding: 20px;
        }

        .kpi-icon {
            font-size: 30px;
        }
    }
</style>

<div class="card">
    <div class="card-body">
        <div class="card-body p-4">
            <!-- KPI TOP CARDS -->
            <div class="row g-4 mb-5">
                <div class="col-md-3">
                    <div class="card card-kpi p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Total Aset</h6>
                                <h3 class="fw-bold mb-0">152</h3>
                            </div>
                            <div class="kpi-icon text-white">
                                <i class="bi bi-box-seam-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-kpi p-4" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Total Cabang</h6>
                                <h3 class="fw-bold mb-0">126</h3>
                            </div>
                            <div class="kpi-icon text-white">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-kpi p-4" style="background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Aset Rusak</h6>
                                <h3 class="fw-bold mb-0">13</h3>
                            </div>
                            <div class="kpi-icon text-white">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-kpi p-4" style="background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Mutasi Bulan Ini</h6>
                                <h3 class="fw-bold mb-0">8</h3>
                            </div>
                            <div class="kpi-icon text-white">
                                <i class="bi bi-arrow-left-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CHART ROW -->
            <div class="row mb-5">
                <!-- Chart Kategori -->
                <div class="col-md-8 mb-4">
                    <div class="h-100">
                        <div class="card p-4">
                            <h6 class="fw-bold mb-4 text-center">Mutasi Aset (12 Bulan)</h6>
                            <canvas id="chartMutasi" style="max-height:300px;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- User Login Cabang -->
                <div class="col-md-4 mb-4">
                    <div class="card p-4 h-100">
                        <h6 class="fw-bold mb-4 text-center">Aktifitas Users Cabang</h6>
                        <div class="list-group" style="max-height:320px; overflow-y:auto; padding-right:10px;">
                            <div class="d-flex align-items-center mb-3 p-2 rounded">
                                <img src="https://i.pravatar.cc/50?img=1" class="rounded-circle me-3 shadow" width="50" height="50">
                                <div>
                                    <strong>Admin A</strong><br>
                                    <span class="text-muted">Cabang Jakarta</span>
                                </div>
                                <span class="ms-auto status-active">Online</span>
                            </div>
                            <div class="d-flex align-items-center mb-3 p-2 rounded">
                                <img src="https://i.pravatar.cc/50?img=5" class="rounded-circle me-3 shadow" width="50" height="50">
                                <div>
                                    <strong>Admin B</strong><br>
                                    <span class="text-muted">Cabang Bekasi</span>
                                </div>
                                <span class="ms-auto status-active">Online</span>
                            </div>
                            <div class="d-flex align-items-center mb-3 p-2 rounded">
                                <img src="https://i.pravatar.cc/50?img=13" class="rounded-circle me-3 shadow" width="50" height="50">
                                <div>
                                    <strong>Admin C</strong><br>
                                    <span class="text-muted">Cabang Bandung</span>
                                </div>
                                <span class="ms-auto status-inactive">Offline</span>
                            </div>
                            <div class="d-flex align-items-center mb-3 p-2 rounded">
                                <img src="https://i.pravatar.cc/50?img=20" class="rounded-circle me-3 shadow" width="50" height="50">
                                <div>
                                    <strong>Admin D</strong><br>
                                    <span class="text-muted">Cabang Semarang</span>
                                </div>
                                <span class="ms-auto status-active">Online</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-5">
                <!-- Chart Kategori -->
                <div class="col-md-6 mb-4">
                    <div class="card p-4 h-100 d-flex flex-column justify-content-center">
                        <h6 class="fw-bold mb-4 text-center">Aset Berdasarkan Kategori</h6>
                        <div class="d-flex justify-content-center">
                            <canvas id="chartKategori" style="max-height:250px; width:100%;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Chart Kondisi -->
                <div class="col-md-6 mb-4">
                    <div class="card p-4 h-100">
                        <h6 class="fw-bold mb-2 text-center">Kondisi Aset</h6>
                        <span class="text-muted small text-center d-block mb-4">Distribusi kondisi aset</span>
                        <div class="d-flex align-items-center justify-content-center">
                            <div style="width: 180px; height: 180px;">
                                <canvas id="chartKondisi"></canvas>
                            </div>
                            <div class="ms-4">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="legend-dot me-3" style="background:#198754;"></span>
                                    <span class="me-3">Baik</span>
                                    <span class="fw-bold">78%</span>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <span class="legend-dot me-3" style="background:#ffc107;"></span>
                                    <span class="me-3">Rusak Ringan</span>
                                    <span class="fw-bold">12%</span>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <span class="legend-dot me-3" style="background:#dc3545;"></span>
                                    <span class="me-3">Rusak Berat</span>
                                    <span class="fw-bold">10%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RECENT ASSETS + MAINTENANCE -->
            <div class="row">
                <!-- Aset Terbaru -->
                <div class="col-md-6 mb-4">
                    <div class="card p-4 h-100">
                        <h6 class="fw-bold mb-4">Aset Terbaru Ditambahkan</h6>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Aset</th>
                                        <th>Kategori</th>
                                        <th>Tanggal Input</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>AST-2025-001</td>
                                        <td>Laptop Lenovo Thinkpad</td>
                                        <td>IT</td>
                                        <td>2025-01-16</td>
                                    </tr>
                                    <tr>
                                        <td>AST-2025-002</td>
                                        <td>Kursi Staff</td>
                                        <td>Furniture</td>
                                        <td>2025-01-15</td>
                                    </tr>
                                    <tr>
                                        <td>AST-2025-003</td>
                                        <td>Monitor Dell 24"</td>
                                        <td>Elektronik</td>
                                        <td>2025-01-15</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Aset Akan Expired -->
                <div class="col-md-6 mb-4">
                    <div class="card p-4 h-100">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6>Aset Akan Expired
                                <span class="badge bg-danger"><?= count($expiredSoon) ?></span>
                            </h6>

                            <a href="<?= site_url('admin/aset') ?>" class="small text-decoration-none">
                                Lihat Semua →
                            </a>
                        </div>

                        <div class="table-responsive">

                            <table class="table table-sm table-hover align-middle">

                                <thead class="table-light">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Aset</th>
                                        <th>Tanggal Expired</th>
                                        <th class="text-center">Sisa</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php if (!empty($expiredSoon)): ?>

                                        <?php foreach ($expiredSoon as $a):
                                            $days = ceil((strtotime($a['expired_at']) - time()) / 86400);

                                            if ($days <= 7) {
                                                $badge = 'danger';
                                            } elseif ($days <= 14) {
                                                $badge = 'warning';
                                            } else {
                                                $badge = 'success';
                                            }
                                        ?>

                                            <tr onclick="window.location='<?= site_url('admin/aset/detail/') . $a['id_aset'] ?>'" style="cursor:pointer">

                                                <td><strong><?= esc($a['kode_aset']) ?></strong></td>

                                                <td><?= esc($a['nama_master']) ?></td>

                                                <td><?= date('d M Y', strtotime($a['expired_at'])) ?></td>

                                                <td class="text-center">
                                                    <span class="badge bg-<?= $badge ?>">
                                                        <?= $days ?> hari
                                                    </span>
                                                </td>
                                            </tr>

                                        <?php endforeach; ?>

                                    <?php else: ?>

                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">
                                                Tidak ada aset yang akan expired
                                            </td>
                                        </tr>

                                    <?php endif; ?>

                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Chart Kategori
    new Chart(document.getElementById('chartKategori'), {
        type: 'bar',
        data: {
            labels: ['IT', 'Furniture', 'Kendaraan', 'Elektronik'],
            datasets: [{
                label: 'Jumlah',
                data: [40, 32, 12, 20],
                backgroundColor: ['#0d6efd', '#6f42c1', '#198754', '#fd7e14'],
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: 'white',
                    bodyColor: 'white'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Chart Kondisi
    new Chart(document.getElementById('chartKondisi'), {
        type: 'doughnut',
        data: {
            labels: ['Baik', 'Rusak Ringan', 'Rusak Berat'],
            datasets: [{
                data: [78, 12, 10],
                backgroundColor: ['#198754', '#ffc107', '#dc3545'],
                borderWidth: 0,
                cutout: '70%',
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: 'white',
                    bodyColor: 'white'
                }
            }
        }
    });

    // Chart Mutasi
    new Chart(document.getElementById('chartMutasi'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Mutasi Aset',
                data: [3, 2, 5, 4, 6, 5, 7, 6, 4, 5, 3, 8],
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#0d6efd',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: 'white',
                    bodyColor: 'white'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
<?= $this->endSection(); ?>