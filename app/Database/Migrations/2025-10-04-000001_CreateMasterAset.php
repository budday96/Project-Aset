<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMasterAset extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_master_aset' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'kode_master'    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true, 'unique' => true],
            'nama_master'    => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => false],
            'id_kategori'    => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => false],
            'id_subkategori' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => false],
            'expired_default' => ['type' => 'DATE', 'null' => true],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id_master_aset', true);
        $this->forge->addForeignKey('id_kategori', 'kategori_aset', 'id_kategori', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('id_subkategori', 'subkategori_aset', 'id_subkategori', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('master_aset', true);

        // detail atribut default milik master
        $this->forge->addField([
            'id_master_aset' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => false],
            'id_atribut'     => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => false],
            'nilai_default'  => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addKey(['id_master_aset', 'id_atribut'], true);
        $this->forge->addForeignKey('id_master_aset', 'master_aset', 'id_master_aset', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_atribut', 'atribut_aset', 'id_atribut', 'CASCADE', 'CASCADE');
        $this->forge->createTable('master_aset_atribut', true);
    }

    public function down()
    {
        $this->forge->dropTable('master_aset_atribut', true);
        $this->forge->dropTable('master_aset', true);
    }
}
