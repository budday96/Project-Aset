<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index(): string
    {
        return view('admin/dashboard/index', [
            'title' => 'Dashboard',
        ]);
    }
}
