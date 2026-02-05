<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'MyAsset' ?></title>

    <link rel="icon" type="image/png" href="<?= base_url(); ?>/img/logoipsum.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url(); ?>/vendor/bootstrap/css/bootstrap.min.css">

    <!-- DataTables Bootstrap 5 -->
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css"> -->
    <link rel="stylesheet" href="<?= base_url(); ?>/vendor/datatables/css/datatables.min.css">
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css"> -->
    <link rel="stylesheet" href="<?= base_url(); ?>/vendor/datatables/css/responsive.bootstrap5.min.css">
    <!-- DataTables Buttons -->
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css"> -->
    <link rel="stylesheet" href="<?= base_url(); ?>/vendor/datatables/css/buttons.bootstrap5.min.css">

    <!-- Select2 + Bootstrap 5 Theme -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="<?= base_url(); ?>/vendor/select2/css/select2.min.css">
    <!-- <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="<?= base_url(); ?>/vendor/select2/css/select2-bootstrap-5-theme.min.css">

    <!-- Bootstrap Icons -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"> -->
    <link rel="stylesheet" href="<?= base_url(); ?>/vendor/icons/bootstrap-icons.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url(); ?>/vendor/style.css">

    <!-- Section CSS -->
    <?= $this->renderSection('css'); ?>
</head>

<body>
    <?= $this->include('layout/admin_template/sidebar'); ?>
    <?= $this->include('layout/admin_template/topbar'); ?>

    <main>
        <div class="c2g-container">
            <?= $this->renderSection('content'); ?>
        </div>
    </main>

    <footer class="c2g-footer">
        <div class="c2g-footer-inner">
            <span class="c2g-brand">MyAsset © <?= date('Y'); ?></span>
        </div>
    </footer>

    <!-- Toast Notification -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1080">
        <div id="global-toast" class="toast align-items-center border-0 text-bg-primary" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="global-toast-msg">Notifikasi umum</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="<?= base_url(); ?>/vendor/jquery/jquery-3.7.1.js"></script>

    <!-- Bootstrap Bundle -->
    <script src="<?= base_url(); ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables Core -->
    <!-- <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script> -->
    <!-- <script src="<?= base_url(); ?>/vendor/datatables/js/jquery.dataTables.min.js"></script> -->
    <!-- <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script> -->
    <script src="<?= base_url(); ?>/vendor/datatables/js/datatables.min.js"></script>
    <!-- <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script> -->
    <script src="<?= base_url(); ?>/vendor/datatables/js/dataTables.responsive.min.js"></script>
    <!-- <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script> -->
    <script src="<?= base_url(); ?>/vendor/datatables/js/responsive.bootstrap5.min.js"></script>
    <!-- DataTables Buttons -->
    <!-- <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script> -->
    <!-- <script src="<?= base_url(); ?>/vendor/datatables/js/dataTables.buttons.min.js"></script> -->
    <!-- <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script> -->
    <!-- <script src="<?= base_url(); ?>/vendor/datatables/js/buttons.html5.min.js"></script> -->

    <!-- Export Dependencies -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script> -->
    <script src="<?= base_url(); ?>/vendor/datatables/js/jszip.min.js"></script>

    <!-- FIX PDF Export → gunakan versi KOMPATIBEL -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/pdfmake.min.js"></script> -->
    <script src="<?= base_url(); ?>/vendor/pdfmake/pdfmake.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.js"></script> -->
    <script src="<?= base_url(); ?>/vendor/pdfmake/vfs_fonts.js"></script>

    <!-- Select2 -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->
    <script src="<?= base_url(); ?>/vendor/select2/js/select2.min.js"></script>

    <!-- Custom App -->
    <script src="<?= base_url(); ?>/vendor/app.js" defer></script>

    <!-- Section JS -->
    <?= $this->renderSection('scripts'); ?>

    <!-- Toast Function -->
    <script>
        window.showToast = function(message, type = 'success') {
            const toastEl = document.getElementById('global-toast');
            const msgEl = document.getElementById('global-toast-msg');

            toastEl.className = `toast align-items-center border-0 text-bg-${type}`;
            msgEl.textContent = message;

            const toast = new bootstrap.Toast(toastEl, {
                delay: 3000
            });
            toast.show();
        };
    </script>

    <?php
    $flashSuccess = session()->getFlashdata('success');
    $flashError   = session()->getFlashdata('error');
    $flashWarning = session()->getFlashdata('warning');
    $flashInfo    = session()->getFlashdata('info');
    ?>

    <!-- Auto Toast -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            <?php if ($flashSuccess): ?> showToast("<?= esc($flashSuccess) ?>", 'success');
            <?php endif; ?>
            <?php if ($flashError): ?> showToast("<?= esc($flashError) ?>", 'danger');
            <?php endif; ?>
            <?php if ($flashWarning): ?> showToast("<?= esc($flashWarning) ?>", 'warning');
            <?php endif; ?>
            <?php if ($flashInfo): ?> showToast("<?= esc($flashInfo) ?>", 'info');
            <?php endif; ?>
        });
    </script>

</body>

</html>