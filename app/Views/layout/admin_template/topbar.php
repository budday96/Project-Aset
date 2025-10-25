<?php helper(['auth', 'cabang']); ?>
<header id="c2g-topbar">
    <div class="c2g-topbar-inner c2g-profilebar">
        <div class="c2g-topbar-left">
            <h1 class="c2g-page-title"><?= $title; ?></h1>
        </div>

        <div class="c2g-topbar-right">

            <!-- Tombol Fullscreen -->
            <button id="fullscreenBtn" class="c2g-fullscreen-btn" title="Fullscreen">
                <i class="bi bi-arrows-fullscreen"></i>
            </button>

            <span class="c2g-v-sep" aria-hidden="true"></span>
            <div class="c2g-user-meta">
                <div class="c2g-user-name"><?= user()->full_name; ?></div>
                <div class="c2g-user-sub">
                    <i class="bi bi-geo-alt-fill" style="color: #fd7e14;"></i>
                    <span class="c2g-user-role"><?= esc(nama_cabang_login() ?? 'Tidak Ada Cabang') ?></span>
                </div>
            </div>

            <div class="c2g-user-menu" id="c2g-userMenu">
                <button class="c2g-user-trigger c2g-only-avatar" id="c2g-userTrigger" aria-haspopup="menu" aria-expanded="false" title="Akun">
                    <span class="c2g-avatar">
                        <img class="img-profile rounded-circle"
                            src="<?= base_url(); ?>/img/<?= user()->user_image; ?>">
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