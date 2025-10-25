<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSubkategoriAset extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_subkategori'   => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'id_kategori'      => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => false],
            'nama_subkategori' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'slug'             => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id_subkategori', true);
        $this->forge->addKey('id_kategori');
        $this->forge->createTable('subkategori_aset');

        // FK ke kategori_aset
        $this->db->query("
            ALTER TABLE subkategori_aset
              ADD CONSTRAINT fk_subkategori_kategori
              FOREIGN KEY (id_kategori) REFERENCES kategori_aset(id_kategori)
              ON DELETE CASCADE ON UPDATE CASCADE;
        ");

        // FK aset → subkategori_aset
        $this->db->query("
            ALTER TABLE aset
              ADD CONSTRAINT fk_aset_subkategori
              FOREIGN KEY (id_subkategori) REFERENCES subkategori_aset(id_subkategori)
              ON DELETE SET NULL ON UPDATE CASCADE;
        ");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE aset DROP FOREIGN KEY fk_aset_subkategori;");
        $this->forge->dropTable('subkategori_aset', true);
    }
}
