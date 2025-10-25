<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SubkategoriAtributSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // Pastikan kategori Elektronik ada
        $kategori = $db->table('kategori_aset')->where('nama_kategori', 'Elektronik')->get()->getRowArray();
        if (!$kategori) {
            $db->table('kategori_aset')->insert([
                'kode_kategori' => 'EL',
                'nama_kategori' => 'Elektronik',
                'created_at'    => date('Y-m-d H:i:s'),
            ]);
            $kategoriId = $db->insertID();
        } else {
            $kategoriId = $kategori['id_kategori'];
        }

        // Subkategori Laptop
        $db->table('subkategori_aset')->insert([
            'id_kategori'      => $kategoriId,
            'nama_subkategori' => 'Laptop',
            'slug'             => 'laptop',
            'created_at'       => date('Y-m-d H:i:s'),
        ]);
        $subId = $db->insertID();

        // Atribut untuk Laptop
        $attrs = [
            ['nama_atribut' => 'Merek', 'kode_atribut' => 'merek', 'tipe_input' => 'text', 'is_required' => 1, 'urutan' => 1],
            ['nama_atribut' => 'Tipe',  'kode_atribut' => 'tipe', 'tipe_input' => 'text', 'is_required' => 1, 'urutan' => 2],
            ['nama_atribut' => 'RAM (GB)', 'kode_atribut' => 'ram', 'tipe_input' => 'number', 'satuan' => 'GB', 'is_required' => 1, 'urutan' => 3],
            ['nama_atribut' => 'SSD (GB)', 'kode_atribut' => 'ssd', 'tipe_input' => 'number', 'satuan' => 'GB', 'urutan' => 4],
            ['nama_atribut' => 'Layar (inch)', 'kode_atribut' => 'layar', 'tipe_input' => 'number', 'satuan' => 'inch', 'urutan' => 5],
            ['nama_atribut' => 'Kondisi Baterai', 'kode_atribut' => 'baterai', 'tipe_input' => 'select', 'options_json' => json_encode(['Baik', 'Sedang', 'Menurun']), 'urutan' => 6],
            ['nama_atribut' => 'Catatan', 'kode_atribut' => 'catatan', 'tipe_input' => 'textarea', 'urutan' => 99],
        ];

        foreach ($attrs as $a) {
            $a['id_subkategori'] = $subId;
            $a['created_at']     = date('Y-m-d H:i:s');
            $db->table('atribut_aset')->insert($a);
        }
    }
}
