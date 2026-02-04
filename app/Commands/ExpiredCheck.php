<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Services\ExpiredNotifier;

class ExpiredCheck extends BaseCommand
{
    protected $group = 'Notifikasi';
    protected $name = 'notif:expired';
    protected $description = 'Cek aset expired & kirim notifikasi';

    public function run(array $params)
    {
        CLI::write('Checking expired assets...', 'yellow');

        (new ExpiredNotifier())->run();

        CLI::write('Done!', 'green');
    }
}
