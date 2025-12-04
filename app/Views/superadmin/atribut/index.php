<?= $this->extend('layout/superadmin_template/index') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-body">
        <div class="card-header bg-white py-3 px-4">
            <!-- TOP BAR: TITLE + ACTION BUTTONS -->
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3 gap-2">

                <!-- TITLE -->
                <div class="order-2 order-md-1 w-100 text-center text-md-start">
                    <h4 class="fw-bold m-0">List <?= $title; ?></h4>
                </div>

                <!-- BUTTON GROUP (Kembali + Tambah Atribut) -->
                <div class="order-1 order-md-2 d-flex flex-wrap gap-2 w-100 w-md-auto justify-content-center justify-content-md-end">

                    <!-- KEMBALI -->
                    <a class="btn btn-light border btn-sm fw-semibold d-flex align-items-center gap-2 shadow-sm hover-lift px-3"
                        href="<?= base_url('superadmin/subkategori') ?>">
                        <i class="bi bi-arrow-left-circle fs-5"></i>
                        <span>Kembali</span>
                    </a>

                    <!-- TAMBAH ATRIBUT -->
                    <a class="btn btn-warning btn-sm fw-semibold d-flex align-items-center justify-content-center px-3"
                        href="<?= base_url('superadmin/atribut/' . $sub['id_subkategori'] . '/create') ?>">
                        <i class="bi bi-plus-circle me-1 fs-5"></i>
                        <span>Tambah Atribut</span>
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
                            <th>Nama</th>
                            <th>Tipe</th>
                            <th>Wajib</th>
                            <th>Satuan</th>
                            <th style="width:160px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($atributs as $a): ?>
                            <tr>
                                <td>
                                    <div class="checkbox d-inline-block">
                                        <?php $checkboxId = 'checkbox_' . $a['id_atribut']; ?>
                                        <input type="checkbox"
                                            class="checkbox-input"
                                            id="<?= $checkboxId ?>"
                                            name="selected[]"
                                            value="<?= $a['id_atribut'] ?>">
                                        <label for="<?= $checkboxId ?>" class="mb-0"></label>
                                    </div>
                                </td>
                                <td><?= esc($a['nama_atribut']) ?></td>
                                <td><?= esc($a['tipe_input']) ?></td>
                                <td><?= $a['is_required'] ? 'Ya' : 'Tidak' ?></td>
                                <td><?= esc($a['satuan']) ?></td>
                                <td class="text-center align-middle">
                                    <div class="d-flex justify-content-center align-items-center list-action">

                                        <!-- Tombol Edit -->
                                        <a href="<?= base_url('superadmin/atribut/edit/' . $a['id_atribut']) ?>"
                                            class="btn btn-sm"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Edit">
                                            <i class="bi bi-pen" style="color: #fd7e14;"></i>
                                        </a>

                                        <!-- Tombol Delete -->
                                        <form action="<?= base_url('superadmin/atribut/delete/' . $a['id_atribut']) ?>"
                                            method="post"
                                            class="d-inline"
                                            onsubmit="return confirm('Hapus atribut ini?')">
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