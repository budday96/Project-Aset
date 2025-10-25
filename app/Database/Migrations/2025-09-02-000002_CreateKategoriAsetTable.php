<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKategoriAsetTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_kategori' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true, // boleh NULL
            ],
            'nama_kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'deskripsi' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true, // boleh NULL
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        // Primary key
        $this->forge->addKey('id_kategori', true);

        // Unique key untuk kode_kategori (unik jika tidak NULL; MySQL membolehkan banyak NULL pada UNIQUE)
        $this->forge->addKey('kode_kategori', false, true);

        $attributes = [
            'ENGINE' => 'InnoDB',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ];

        $this->forge->createTable('kategori_aset', false, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('kategori_aset', true);
    }
}
