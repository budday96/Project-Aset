<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index(): string
    {
        return view('user/dashboard/index', [
            'title' => 'Dashboard',
        ]);
    }
}
