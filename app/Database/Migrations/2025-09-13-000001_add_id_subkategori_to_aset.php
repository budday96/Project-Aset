<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIdSubkategoriToAset extends Migration
{
    public function up()
    {
        $this->forge->addColumn('aset', [
            'id_subkategori' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id_kategori',
            ],
        ]);
        $this->forge->addKey('id_subkategori');
    }

    public function down()
    {
        $this->forge->dropColumn('aset', 'id_subkategori');
    }
}
