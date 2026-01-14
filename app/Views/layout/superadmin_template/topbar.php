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

            <span class="c2g-v-sep" aria-hidden="true"></span>
            <div class="c2g-user-meta">
                <div class="c2g-user-name mb-1"><?= user()->full_name; ?></div>
                <div class="c2g-user-sub">
                    <span class="c2g-user-role badge bg-warning text-white px-2 py-1 rounded-pill">
                        <i class="bi bi-shield-check me-1"></i>
                        <?= get_user_role_name() ?>
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
                    <li role="none"><a role="menuitem" href="<?= base_url('superadmin/profile'); ?>">
                            <i class="bi bi-person-gear"></i>
                            <span>Profile</span></a>
                    </li>
                    <li role="none"><a role="menuitem" href="<?= base_url('superadmin/profile'); ?>">
                            <i class="bi bi-gear"></i>
                            <span>Setting</span></a>
                    </li>
                    <li role="none"><a role="menuitem" href="<?= base_url('superadmin/profile'); ?>">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Logout</span></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>