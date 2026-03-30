<?= $this->extend('layout/user_template/index'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="mb-1"><strong><?= $laporan['kode_laporan']; ?></strong></h4>
                            <p class="text-muted mb-2">Detail Laporan Kerusakan Aset</p>
                        </div>
                        <span class="badge bg-<?= $laporan['status'] == 'DONE' ? 'success' : ($laporan['status'] == 'PROCESS' ? 'warning' : 'info'); ?> fs-6 px-3 py-2">
                            <?= $laporan['status']; ?>
                        </span>
                    </div>
                    <hr class="my-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Estimasi Biaya</small>
                            <h5 class="mb-0">
                                <?= $laporan['estimasi_biaya']
                                    ? '<strong class="text-success">Rp ' . number_format($laporan['estimasi_biaya'], 0, ',', '.') . '</strong>'
                                    : '<span class="text-muted">-</span>' ?>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Daftar Aset -->
        <div class="col-lg-7">
            <h6 class="mb-3 fw-bold text-dark">Daftar Aset Dilaporkan</h6>
            <div class="space-y-3">
                <?php foreach ($items as $item): ?>
                    <div class="card border-0 shadow-sm hover-shadow transition">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 text-dark">
                                        <strong><?= esc($item['kode_aset']); ?></strong>
                                    </h6>
                                    <p class="mb-0 text-muted small"><?= esc($item['nama_master']); ?></p>
                                </div>
                                <div class="text-end">
                                    <small class="badge bg-light text-dark">
                                        <?= esc($item['kondisi'] ?? 'N/A'); ?>
                                    </small>
                                </div>
                            </div>

                            <div class="alert alert-light mb-3 py-2" role="alert">
                                <small><strong>📍 Lokasi:</strong> <?= esc($item['posisi'] ?? '-'); ?></small>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted d-block mb-2"><strong>Deskripsi Kerusakan:</strong></small>
                                <p class="mb-0 small text-dark"><?= esc($item['deskripsi_kerusakan']); ?></p>
                            </div>

                            <!-- Foto Gallery -->
                            <div class="row g-2">
                                <?php if ($item['foto']): ?>
                                    <div class="col-6">
                                        <div class="position-relative overflow-hidden rounded" style="aspect-ratio: 1;">
                                            <img src="/uploads/laporan/<?= $item['foto']; ?>"
                                                class="img-fluid w-100 h-100 object-fit-cover"
                                                alt="Foto Laporan"
                                                data-bs-toggle="modal"
                                                data-bs-target="#imageModal"
                                                role="button"
                                                title="Klik untuk memperbesar">
                                            <small class="position-absolute bottom-0 start-0 bg-dark text-white px-2 py-1">Laporan</small>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Timeline Progress -->
        <div class="col-lg-5">
            <h6 class="mb-3 fw-bold text-dark">Timeline Progress</h6>
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="timeline">
                        <?php foreach ($progress as $index => $p): ?>
                            <div class="timeline-item <?= $index !== count($progress) - 1 ? 'pb-4' : ''; ?>">
                                <div class="d-flex">
                                    <div class="timeline-marker bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; min-width: 40px;">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                    <div class="ms-3 flex-grow-1">
                                        <h6 class="mb-1 text-dark"><?= $p['status']; ?></h6>
                                        <?php if (!empty($p['nama_vendor'])): ?>
                                            <small class="text-muted d-block mb-1">
                                                🏢 <?= $p['nama_vendor']; ?>
                                            </small>
                                        <?php endif; ?>
                                        <p class="mb-2 small text-dark"><?= $p['catatan']; ?></p>
                                        <small class="text-muted">
                                            📅 <?= date('d M Y H:i', strtotime($p['created_at'])); ?>
                                        </small>
                                    </div>
                                </div>
                                <?php if ($index !== count($progress) - 1): ?>
                                    <div class="ms-3" style="margin-left: 20px !important;">
                                        <div class="border-start border-primary" style="height: 30px; margin-left: 19px;"></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Action Button -->
            <?php if ($laporan['status'] == 'DONE'): ?>
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-body">
                        <form method="post" action="/user/laporan/confirm/<?= $laporan['id_laporan']; ?>">
                            <?= csrf_field(); ?>
                            <button class="btn btn-success w-100" type="submit">
                                <i class="bi bi-check-circle"></i> Konfirmasi Selesai
                            </button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .space-y-3>*+* {
        margin-top: 1rem;
    }

    .hover-shadow {
        transition: box-shadow 0.3s ease;
    }

    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .transition {
        transition: all 0.3s ease;
    }

    .timeline-item {
        position: relative;
    }

    .object-fit-cover {
        object-fit: cover;
    }
</style>

<?= $this->endSection(); ?>