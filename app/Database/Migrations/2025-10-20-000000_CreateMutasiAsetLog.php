<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMutasiAsetLog extends Migration
{
    public function up()
    {
        // Catatan kejadian mutasi aset (audit trail)
        $this->forge->addField([
            'id_log' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            // FK ke mutasi_aset (wajib)
            'id_mutasi' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
            ],
            // Snapshot data saat event terjadi (agar jejak tidak hilang)
            'id_aset_sumber' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'id_aset_tujuan' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'dari_cabang' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'ke_cabang' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'qty' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            // Transisi status (mis. pending -> dikirim -> diterima / dibatalkan)
            'status_from' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'status_to' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            // Jenis kejadian (lebih ringkas untuk filtering)
            'event' => [
                'type' => 'ENUM',
                'constraint' => ['create', 'send', 'receive', 'cancel'],
                'null' => false,
            ],
            // Siapa yang melakukan
            'actor_user' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            // Keterangan tambahan (opsional)
            'message' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => 'CURRENT_TIMESTAMP',
            ],
        ]);

        $this->forge->addKey('id_log', true);
        $this->forge->addKey('id_mutasi');
        $this->forge->addKey('created_at');
        $this->forge->addKey(['event', 'status_to']);

        // Foreign Keys (optional ON DELETE behavior; kita pilih CASCADE agar log ikut terhapus jika mutasi dihapus permanen)
        $this->forge->addForeignKey('id_mutasi', 'mutasi_aset', 'id_mutasi', 'CASCADE', 'CASCADE');
        // Tidak wajib FK ke aset/users, karena log harus tetap survive walau aset/users berubah.
        // Jika ingin, bisa aktifkan:
        // $this->forge->addForeignKey('id_aset_sumber', 'aset', 'id_aset', 'SET NULL', 'CASCADE');
        // $this->forge->addForeignKey('id_aset_tujuan', 'aset', 'id_aset', 'SET NULL', 'CASCADE');
        // $this->forge->addForeignKey('actor_user', 'users', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('mutasi_aset_log', true);
    }

    public function down()
    {
        $this->forge->dropTable('mutasi_aset_log', true);
    }
}
