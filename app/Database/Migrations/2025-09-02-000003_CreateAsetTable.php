<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAsetTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_aset' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_aset' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
            ],
            'qr_token' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => true, // boleh NULL, tetap unique
            ],
            'nama_aset' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'id_kategori' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
            ],
            'id_cabang' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
            ],
            'tahun_perolehan' => [
                // MySQL/MariaDB YEAR(4)
                'type'       => 'YEAR',
                'constraint' => 4,
                'null'       => true,
            ],
            'kondisi' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
                'default'    => 'Digunakan',
            ],
            'gambar' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'expired_at' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
        ]);

        // Primary key
        $this->forge->addKey('id_aset', true);

        // Uniques
        $this->forge->addKey('kode_aset', false, true);
        $this->forge->addKey('qr_token', false, true);

        // Indexes (sesuai MUL)
        $this->forge->addKey('id_kategori');
        $this->forge->addKey('id_cabang');

        // Buat tabel
        $attributes = [
            'ENGINE' => 'InnoDB',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ];
        $this->forge->createTable('aset', false, $attributes);

        // Foreign Keys
        // Catatan: pastikan tabel 'kategori_aset' dan 'cabang' sudah ada terlebih dahulu.
        $this->db->query('ALTER TABLE `aset` 
            ADD CONSTRAINT `fk_aset_kategori`
                FOREIGN KEY (`id_kategori`) REFERENCES `kategori_aset`(`id_kategori`)
                ON UPDATE CASCADE ON DELETE RESTRICT,
            ADD CONSTRAINT `fk_aset_cabang`
                FOREIGN KEY (`id_cabang`) REFERENCES `cabang`(`id_cabang`)
                ON UPDATE CASCADE ON DELETE RESTRICT
        ');
    }

    public function down()
    {
        // Hapus FK dulu agar drop table mulus
        $this->db->query('ALTER TABLE `aset` 
            DROP FOREIGN KEY `fk_aset_kategori`,
            DROP FOREIGN KEY `fk_aset_cabang`');

        $this->forge->dropTable('aset', true);
    }
}
