<header id="c2g-topbar">
    <div class="c2g-topbar-inner c2g-profilebar">
        <div class="c2g-topbar-left">
            <h1 class="c2g-page-title fw-bold"><?= $title; ?></h1>
        </div>

        <div class="c2g-topbar-right">

            <!-- Tombol Fullscreen -->
            <button id="fullscreenBtn" class="c2g-fullscreen-btn" title="Fullscreen">
                <i class="bi bi-arrows-fullscreen"></i>
            </button>

            <!-- =======================
     NOTIFICATION BELL (BOOTSTRAP 5 STYLE)
======================== -->
            <a href="<?= site_url('admin/notifikasi'); ?>"
                class="btn btn-light position-relative rounded-circle d-flex align-items-center justify-content-center"
                style="width:38px;height:38px"
                data-bs-toggle="tooltip"
                title="Notifikasi">

                <i class="bi bi-bell fs-5"></i>

                <?php if ($notifCount > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?= $notifCount ?>
                        <span class="visually-hidden">unread messages</span>
                    </span>
                <?php endif; ?>

            </a>
            <!-- ======================= -->

            <span class="c2g-v-sep" aria-hidden="true"></span>
            <div class="c2g-user-meta">
                <div class="c2g-user-name mb-1"><?= user()->full_name; ?></div>
                <div class="c2g-user-sub">
                    <span class="c2g-user-role badge bg-warning text-white px-2 py-1 rounded-pill">
                        <i class="bi bi-geo-alt-fill"></i>
                        <?= esc(get_nama_cabang(user()->id_cabang) ?? 'Tidak diketahui') ?>
                    </span>
                </div>
            </div>

            <div class="c2g-user-menu" id="c2g-userMenu">
                <button class="c2g-user-trigger c2g-only-avatar" id="c2g-userTrigger" aria-haspopup="menu" aria-expanded="false" title="Akun">
                    <span class="c2g-avatar">
                        <?php
                        $authUser = user();
                        $filename = $authUser->user_image ?? '';
                        $imgPath = $filename ? FCPATH . 'img/' . $filename : '';

                        if ($filename && file_exists($imgPath)) {
                            $showUrl = base_url('img/' . $filename);
                        } else {
                            $showUrl = base_url('img/default.jpg');
                        }
                        ?>
                        <img class="img-profile rounded-circle"
                            src="<?= esc($showUrl) ?>"
                            alt="<?= esc($authUser->full_name ?? 'Avatar') ?>"
                            width="150" height="150">
                    </span>
                </button>
                <ul class="c2g-menu" role="menu" aria-label="User menu">
                    <li role="none"><a role="menuitem" href="<?= base_url('admin/profile'); ?>">
                            <i class="bi bi-person-gear"></i>
                            <span>Profile</span></a>
                    </li>
                    <li role="none"><a role="menuitem" href="<?= base_url('admin/profile'); ?>">
                            <i class="bi bi-gear"></i>
                            <span>Setting</span></a>
                    </li>
                    <li role="none"><a role="menuitem" href="<?= base_url('admin/profile'); ?>">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Logout</span></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>