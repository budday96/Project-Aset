<?php

namespace App\Controllers\Auth;

use Myth\Auth\Controllers\AuthController;

class LoginController extends AuthController
{
    public function attemptLogin()
    {
        $rules = [
            'login'    => 'required',
            'password' => 'required',
        ];

        if ($this->config->validFields === ['email']) {
            $rules['login'] .= '|valid_email';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $login    = $this->request->getPost('login');
        $password = $this->request->getPost('password');
        $remember = (bool) $this->request->getPost('remember');

        $type = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        if (! $this->auth->attempt(
            [$type => $login, 'password' => $password],
            $remember
        )) {
            return redirect()->back()
                ->withInput()
                ->with('error', $this->auth->error() ?? lang('Auth.badAttempt'));
        }

        if ($this->auth->user()->force_pass_reset === true) {
            return redirect()
                ->to(route_to('reset-password') . '?token=' . $this->auth->user()->reset_hash)
                ->withCookies();
        }

        // 🔥 INI BAGIAN YANG KITA UBAH
        if (in_groups('superadmin')) {
            return redirect()->to('/superadmin/dashboard')->withCookies();
        }

        if (in_groups('admin')) {
            return redirect()->to('/admin/dashboard')->withCookies();
        }

        if (in_groups('user')) {
            return redirect()->to('/user/dashboard')->withCookies();
        }

        return redirect()->to('/')->withCookies();
    }
}
