<?= $this->extend('auth/templates/index'); ?>

<?= $this->section('content'); ?>
<div class="row justify-content-center align-items-center min-vh-100 bg-light">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h1 class="h3 fw-bold" style="color: #fd7e14;"><?= lang('Auth.loginTitle') ?></h1>
                </div>
                <?= view('Myth\Auth\Views\_message_block') ?>
                <form action="<?= url_to('login') ?>" method="post" autocomplete="off">
                    <?= csrf_field() ?>

                    <?php if ($config->validFields === ['email']): ?>
                        <div class="mb-3">
                            <label for="login" class="form-label"><?= lang('Auth.email') ?></label>
                            <input type="email" class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>"
                                id="login" name="login" placeholder="<?= lang('Auth.email') ?>" value="<?= old('login') ?>">
                            <div class="invalid-feedback">
                                <?= session('errors.login') ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="mb-3">
                            <label for="login" class="form-label"><?= lang('Auth.emailOrUsername') ?></label>
                            <input type="text" class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>"
                                id="login" name="login" placeholder="<?= lang('Auth.emailOrUsername') ?>" value="<?= old('login') ?>">
                            <div class="invalid-feedback">
                                <?= session('errors.login') ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="password" class="form-label"><?= lang('Auth.password') ?></label>
                        <input type="password" class="form-control <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>"
                            id="password" name="password" placeholder="<?= lang('Auth.password') ?>">
                        <div class="invalid-feedback">
                            <?= session('errors.password') ?>
                        </div>
                    </div>

                    <?php if ($config->allowRemembering): ?>
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember" <?php if (old('remember')) : ?> checked <?php endif ?>>
                            <label class="form-check-label" for="remember"><?= lang('Auth.rememberMe') ?></label>
                        </div>
                    <?php endif; ?>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-lg" style="background-color: #fd7e14; color: white;">
                            <?= lang('Auth.loginAction') ?>
                        </button>
                    </div>
                </form>
                <hr>
                <?php if ($config->allowRegistration) : ?>
                    <div class="text-center">
                        <a class="small text-decoration-none" href="<?= url_to('register') ?>"><?= lang('Auth.needAnAccount') ?></a>
                    </div>
                <?php endif; ?>
                <?php if ($config->activeResetter): ?>
                    <div class="text-center mt-2">
                        <a class="small text-decoration-none" href="<?= url_to('forgot') ?>"><?= lang('Auth.forgotYourPassword') ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endsection(); ?>