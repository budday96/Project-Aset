<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDefaultsToMasterAset extends Migration
{
    public function up()
    {
        $this->forge->addColumn('master_aset', [
            'nilai_perolehan_default DECIMAL(18,2) NULL AFTER `id_subkategori`',
            'periode_perolehan_default DATE NULL AFTER `nilai_perolehan_default`',
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('master_aset', ['nilai_perolehan_default', 'periode_perolehan_default']);
    }
}
