<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index(): string
    {
        return view('superadmin/dashboard/index', [
            'title' => 'Dashboard Superadmin',
        ]);
    }
}
