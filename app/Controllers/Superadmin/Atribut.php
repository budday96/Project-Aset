<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Models\AtributModel;
use App\Models\SubkategoriAsetModel;

class Atribut extends BaseController
{
    public function index($idSub)
    {
        $sub = (new SubkategoriAsetModel())->find((int)$idSub);
        if (!$sub) return redirect()->back()->with('error', 'Subkategori tidak ditemukan');

        return view('superadmin/atribut/index', [
            'title'    => 'Atribut: ' . $sub['nama_subkategori'],
            'sub'      => $sub,
            'atributs' => (new AtributModel())->bySubkategori((int)$idSub),
        ]);
    }

    public function create($idSub)
    {
        $sub = (new SubkategoriAsetModel())->find((int)$idSub);
        if (!$sub) return redirect()->back()->with('error', 'Subkategori tidak ditemukan');

        return view('superadmin/atribut/create', [
            'title' => 'Tambah Atribut',
            'sub'   => $sub,
        ]);
    }

    public function store()
    {
        $rules = [
            'id_subkategori' => 'required|integer',
            'nama_atribut'   => 'required|min_length[2]',
            'tipe_input'     => 'required|in_list[text,number,date,select,textarea]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.');
        }

        $options = $this->request->getPost('options');
        $optionsJSON = null;
        if ($this->request->getPost('tipe_input') === 'select' && $options) {
            $arr = array_values(array_filter(array_map('trim', preg_split("/\r\n|\n|\r/", $options))));
            $optionsJSON = json_encode($arr);
        }

        (new AtributModel())->insert([
            'id_subkategori' => (int)$this->request->getPost('id_subkategori'),
            'nama_atribut'   => trim($this->request->getPost('nama_atribut')),
            'kode_atribut'   => $this->request->getPost('kode_atribut') ?: null,
            'tipe_input'     => $this->request->getPost('tipe_input'),
            'satuan'         => $this->request->getPost('satuan') ?: null,
            'is_required'    => $this->request->getPost('is_required') ? 1 : 0,
            'options_json'   => $optionsJSON,
            'urutan'         => (int)($this->request->getPost('urutan') ?: 0),
        ]);

        return redirect()->to(base_url('superadmin/atribut/' . $this->request->getPost('id_subkategori')))
            ->with('success', 'Atribut ditambahkan.');
    }

    public function edit($idAtribut)
    {
        $m   = new AtributModel();
        $row = $m->find((int)$idAtribut);
        if (!$row) return redirect()->back()->with('error', 'Data tidak ditemukan');

        $sub = (new SubkategoriAsetModel())->find((int)$row['id_subkategori']);

        return view('superadmin/atribut/edit', [
            'title'        => 'Edit Atribut',
            'row'          => $row,
            'sub'          => $sub,
            'options_text' => $row['options_json'] ? implode(PHP_EOL, json_decode($row['options_json'], true) ?? []) : '',
        ]);
    }

    public function update($idAtribut)
    {
        $rules = [
            'nama_atribut' => 'required|min_length[2]',
            'tipe_input'   => 'required|in_list[text,number,date,select,textarea]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.');
        }

        $options = $this->request->getPost('options');
        $optionsJSON = null;
        if ($this->request->getPost('tipe_input') === 'select' && $options) {
            $arr = array_values(array_filter(array_map('trim', preg_split("/\r\n|\n|\r/", $options))));
            $optionsJSON = json_encode($arr);
        }

        (new AtributModel())->update((int)$idAtribut, [
            'nama_atribut' => trim($this->request->getPost('nama_atribut')),
            'kode_atribut' => $this->request->getPost('kode_atribut') ?: null,
            'tipe_input'   => $this->request->getPost('tipe_input'),
            'satuan'       => $this->request->getPost('satuan') ?: null,
            'is_required'  => $this->request->getPost('is_required') ? 1 : 0,
            'options_json' => $optionsJSON,
            'urutan'       => (int)($this->request->getPost('urutan') ?: 0),
        ]);

        $idSub = (int)$this->request->getPost('id_subkategori');
        return redirect()->to(base_url('superadmin/atribut/' . $idSub))->with('success', 'Atribut diupdate.');
    }

    public function delete($idAtribut)
    {
        $m = new AtributModel();
        $row = $m->find((int)$idAtribut);
        if ($row) {
            $m->delete((int)$idAtribut);
            return redirect()->to(base_url('superadmin/atribut/' . $row['id_subkategori']))->with('success', 'Atribut dihapus.');
        }
        return redirect()->back()->with('error', 'Data tidak ditemukan');
    }
}
