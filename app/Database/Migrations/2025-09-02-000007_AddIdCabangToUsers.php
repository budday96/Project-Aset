<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIdCabangToUsers extends Migration
{
    public function up()
    {
        // 1) Tambah kolom id_cabang (NULLABLE) setelah kolom 'active'
        $this->forge->addColumn('users', [
            'id_cabang' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'active', // posisikan setelah kolom 'active'
            ],
        ]);

        // 2) Tambah index dan 3) FK ke cabang(id_cabang)
        $this->db->query('ALTER TABLE `users`
            ADD INDEX `idx_users_id_cabang` (`id_cabang`),
            ADD CONSTRAINT `fk_users_cabang`
                FOREIGN KEY (`id_cabang`)
                REFERENCES `cabang`(`id_cabang`)
                ON UPDATE CASCADE
                ON DELETE SET NULL
        ');
    }

    public function down()
    {
        // Hapus FK & index lebih dulu, lalu kolom
        $this->db->query('ALTER TABLE `users`
            DROP FOREIGN KEY `fk_users_cabang`,
            DROP INDEX `idx_users_id_cabang`
        ');

        $this->forge->dropColumn('users', 'id_cabang');
    }
}
