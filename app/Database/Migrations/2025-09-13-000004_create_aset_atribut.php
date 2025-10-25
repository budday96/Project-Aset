<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAsetAtribut extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_aset'    => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => false],
            'id_atribut' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => false],
            'nilai'      => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addKey(['id_aset', 'id_atribut'], true);
        $this->forge->addKey('id_atribut');
        $this->forge->createTable('aset_atribut');

        $this->db->query("
            ALTER TABLE aset_atribut
              ADD CONSTRAINT fk_asetatribut_aset
              FOREIGN KEY (id_aset) REFERENCES aset(id_aset)
              ON DELETE CASCADE ON UPDATE CASCADE;
        ");
        $this->db->query("
            ALTER TABLE aset_atribut
              ADD CONSTRAINT fk_asetatribut_atribut
              FOREIGN KEY (id_atribut) REFERENCES atribut_aset(id_atribut)
              ON DELETE CASCADE ON UPDATE CASCADE;
        ");
    }

    public function down()
    {
        $this->forge->dropTable('aset_atribut', true);
    }
}
