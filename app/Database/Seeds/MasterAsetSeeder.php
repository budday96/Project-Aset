<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterAsetSeeder extends Seeder
{
    public function run()
    {
        // contoh master
        $this->db->table('master_aset')->insertBatch([
            [
                'kode_master'    => 'EXC-200A7M',
                'nama_master'    => 'Excavator DX200A-7M',
                'id_kategori'    => 1, // sesuaikan id sebenarnya
                'id_subkategori' => 2,
                'expired_default' => null,
            ],
            [
                'kode_master'    => 'FRK-10T',
                'nama_master'    => 'Forklift 10 Ton',
                'id_kategori'    => 1,
                'id_subkategori' => 3,
                'expired_default' => null,
            ],
        ]);

        // contoh mapping atribut default (pakai id_master_aset & id_atribut real)
        $this->db->table('master_aset_atribut')->insertBatch([
            ['id_master_aset' => 1, 'id_atribut' => 10, 'nilai_default' => 'Cummins QSB'],
            ['id_master_aset' => 1, 'id_atribut' => 11, 'nilai_default' => '20000 Kg'],
        ]);
    }
}
