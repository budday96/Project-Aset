<?= $this->extend('layout/admin_template/index') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-body">
        <div class="card-header bg-white py-3 px-4">
            <!-- TOP BAR: TITLE + ACTION BUTTONS -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">

                <!-- TITLE -->
                <h4 class="fw-bold mb-3 mb-md-0 text-center text-md-start w-100">
                    List Subkategori
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
                    <a href="<?= base_url('/admin/subkategori'); ?>"
                        class="btn btn-outline-secondary btn-sm"
                        data-bs-toggle="tooltip" title="Refresh">
                        <i class="bi bi-arrow-clockwise fs-5"></i>
                    </a>

                    <!-- TAMBAH Subkategori -->
                    <a class="btn btn-warning btn-sm fw-semibold d-flex align-items-center justify-content-center px-2"
                        href="<?= base_url('admin/subkategori/create') ?>">

                        <i class="bi bi-plus-circle me-1 fs-5"></i>
                        <span class="text-truncate">Tambah Subkategori</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body px-0">
            <div class="table-responsive rounded mb-3">
                <table class="table table-hover datatable-myasset">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>
                                <div class="checkbox d-inline-block">
                                    <input type="checkbox" class="checkbox-input" id="checkbox1">
                                    <label for="checkbox1" class="mb-0"></label>
                                </div>
                            </th>
                            <th>Kategori</th>
                            <th>Subkategori</th>
                            <th>Atribut</th>
                            <th style="width:160px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($subkategoris as $s): ?>
                            <tr>
                                <td class="align-middle">
                                    <div class="checkbox d-inline-block">
                                        <?php $checkboxId = 'checkbox_' . $s['id_subkategori']; ?>
                                        <input type="checkbox"
                                            class="checkbox-input"
                                            id="<?= $checkboxId ?>"
                                            name="selected[]"
                                            value="<?= $s['id_subkategori'] ?>">
                                        <label for="<?= $checkboxId ?>" class="mb-0"></label>
                                    </div>
                                </td>
                                <td><?= esc($s['nama_kategori']) ?></td>
                                <td><?= esc($s['nama_subkategori']) ?></td>
                                <td><a class="btn btn-outline-secondary btn-sm" href="<?= base_url('admin/atribut/' . $s['id_subkategori']) ?>">Kelola</a></td>
                                <td class="text-center align-middle">
                                    <div class="d-flex justify-content-center align-items-center list-action">

                                        <!-- Tombol Edit -->
                                        <a href="<?= base_url('admin/subkategori/' . $s['id_subkategori'] . '/edit') ?>"
                                            class="btn btn-sm"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Edit">
                                            <i class="bi bi-pen" style="color: #fd7e14;"></i>
                                        </a>

                                        <!-- Tombol Delete -->
                                        <form action="<?= base_url('admin/subkategori/' . $s['id_subkategori'] . '/delete') ?>"
                                            method="post"
                                            class="d-inline"
                                            onsubmit="return confirm('Hapus subkategori ini?')">
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

<?= $this->endSection() ?>