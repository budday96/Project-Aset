<?= $this->extend('layout/admin_template/index'); ?>
<?= $this->section('content'); ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= esc(session()->getFlashdata('error')); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('admin/mutasi/store'); ?>" method="post" autocomplete="off">
            <?= csrf_field(); ?>
            <!-- Pilih Aset -->
            <div class="mb-3">
                <label for="id_aset" class="form-label">Aset <span class="text-danger">*</span></label>
                <select name="id_aset" id="id_aset" class="form-select" required>
                    <option value="" disabled <?= old('id_aset') ? '' : 'selected'; ?>>Pilih aset...</option>
                    <?php foreach ($asets as $a): ?>
                        <option value="<?= esc($a['id_aset']); ?>" <?= old('id_aset') == $a['id_aset'] ? 'selected' : ''; ?>>
                            <?= esc($a['nama_aset']); ?> (Kode: <?= esc($a['kode_aset'] ?? $a['id_aset']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text">Hanya aset milik cabang Anda yang bisa dimutasi.</div>
            </div>

            <!-- Cabang Tujuan -->
            <div class="mb-3">
                <label for="ke_cabang" class="form-label">Cabang Tujuan <span class="text-danger">*</span></label>
                <select name="ke_cabang" id="ke_cabang" class="form-select" required>
                    <option value="" disabled <?= old('ke_cabang') ? '' : 'selected'; ?>>Pilih cabang tujuan...</option>
                    <?php foreach ($cabangs as $c): ?>
                        <option value="<?= esc($c['id_cabang']); ?>" <?= old('ke_cabang') == $c['id_cabang'] ? 'selected' : ''; ?>>
                            <?= esc($c['nama_cabang']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Keterangan -->
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="3" class="form-control" placeholder="Alasan mutasi atau catatan lain..."><?= esc(old('keterangan')); ?></textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="<?= base_url('admin/mutasi'); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-1"></i> Ajukan Mutasi
                </button>
            </div>
        </form>
    </div>
    <div class="card-footer">
        <small class="text-muted">
            Setelah diajukan, status akan <strong>pending</strong>.
            Cabang Anda dapat <em>kirim</em> atau <em>batalkan</em>.
            Cabang tujuan dapat <em>terima</em> saat status <strong>pending/dikirim</strong>.
        </small>
    </div>
</div>

<?= $this->endSection(); ?>