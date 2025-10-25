<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAtributAset extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_atribut'    => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'id_subkategori' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => false],
            'nama_atribut'  => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'kode_atribut'  => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'tipe_input'    => ['type' => 'ENUM("text","number","date","select","textarea")', 'null' => false, 'default' => 'text'],
            'satuan'        => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'is_required'   => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'options_json'  => ['type' => 'TEXT', 'null' => true],
            'urutan'        => ['type' => 'INT', 'constraint' => 5, 'default' => 0],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id_atribut', true);
        $this->forge->addKey('id_subkategori');
        $this->forge->createTable('atribut_aset');

        $this->db->query("
            ALTER TABLE atribut_aset
              ADD CONSTRAINT fk_atribut_subkategori
              FOREIGN KEY (id_subkategori) REFERENCES subkategori_aset(id_subkategori)
              ON DELETE CASCADE ON UPDATE CASCADE;
        ");
    }

    public function down()
    {
        $this->forge->dropTable('atribut_aset', true);
    }
}
