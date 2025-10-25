<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql; // <-- penting

class CreateMutasiAsetTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_mutasi' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_aset' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
            ],
            'dari_cabang' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
            ],
            'ke_cabang' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
            ],
            'tanggal_mutasi' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'dikirim', 'diterima', 'dibatalkan'],
                'null'       => true,
                'default'    => 'pending',
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'diterima_oleh' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'diterima_pada' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'dibuat_oleh' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
                // ON UPDATE ditambah setelah createTable
            ],
        ]);

        // Keys
        $this->forge->addKey('id_mutasi', true);
        $this->forge->addKey('id_aset');
        $this->forge->addKey('dari_cabang');
        $this->forge->addKey('ke_cabang');
        $this->forge->addKey('dibuat_oleh');
        $this->forge->addKey('diterima_oleh');

        $attributes = [
            'ENGINE' => 'InnoDB',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ];

        $this->forge->createTable('mutasi_aset', false, $attributes);

        // Foreign keys
        $this->db->query('ALTER TABLE `mutasi_aset`
            ADD CONSTRAINT `fk_mutasi_aset__aset`
                FOREIGN KEY (`id_aset`) REFERENCES `aset`(`id_aset`)
                ON UPDATE CASCADE ON DELETE RESTRICT,
            ADD CONSTRAINT `fk_mutasi_aset__dari_cabang`
                FOREIGN KEY (`dari_cabang`) REFERENCES `cabang`(`id_cabang`)
                ON UPDATE CASCADE ON DELETE RESTRICT,
            ADD CONSTRAINT `fk_mutasi_aset__ke_cabang`
                FOREIGN KEY (`ke_cabang`) REFERENCES `cabang`(`id_cabang`)
                ON UPDATE CASCADE ON DELETE RESTRICT
        ');

        // ON UPDATE CURRENT_TIMESTAMP untuk updated_at
        $this->db->query('ALTER TABLE `mutasi_aset`
            MODIFY `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `mutasi_aset`
            DROP FOREIGN KEY `fk_mutasi_aset__aset`,
            DROP FOREIGN KEY `fk_mutasi_aset__dari_cabang`,
            DROP FOREIGN KEY `fk_mutasi_aset__ke_cabang`
        ');
        $this->forge->dropTable('mutasi_aset', true);
    }
}
