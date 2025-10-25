<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'My asset' ?></title>
    <link rel="stylesheet" href="<?= base_url(); ?>/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>/bootstrap/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>/bootstrap/css/style.css">
</head>

<body>
    <!-- Sidebar -->
    <?= $this->include('layout/superadmin_template/sidebar'); ?>

    <!-- Topbar -->
    <?= $this->include('layout/superadmin_template/topbar'); ?>

    <!-- MAIN (Bebas pakai Bootstrap di sini) -->
    <main>
        <div class="c2g-container">
            <?= $this->rendersection('content'); ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="c2g-footer">
        <div class="c2g-footer-inner">
            <span class="c2g-brand">Myasset © <?= date('Y'); ?></span>
        </div>
    </footer>

    <script type="text/javascript" src="<?= base_url(); ?>/bootstrap/js/jquery-3.7.1.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>/bootstrap/js/dataTables.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>/bootstrap/js/dataTables.bootstrap5.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>/bootstrap/js/app.js" defer></script>

    <script>
        new DataTable('#example');
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>

</body>

</html>