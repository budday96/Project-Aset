<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\AsetModel;
use App\Models\CabangModel;
use App\Models\KategoriAsetModel;
use Faker\Provider\Base;

class Aset extends BaseController
{
    protected $asetModel, $cabangModel, $kategoriModel;

    public function __construct()
    {
        $this->asetModel = new AsetModel();
        $this->cabangModel = new CabangModel();
        $this->kategoriModel = new KategoriAsetModel();
    }

    public function index()
    {
        $data['title'] = 'Data Aset';
        $data['asets'] = $this->asetModel
            ->select('aset.*, cabang.nama_cabang, kategori_aset.nama_kategori')
            ->join('cabang', 'cabang.id_cabang = aset.id_cabang')
            ->join('kategori_aset', 'kategori_aset.id_kategori = aset.id_kategori')
            ->where('aset.id_cabang', user()->id_cabang)
            ->orderBy('id_aset', 'DESC')
            ->findAll();

        return view('user/aset/index', $data);
    }

    public function create()
    {
        $data['title'] = 'Tambah Aset';
        $data['cabangs'] = $this->cabangModel->find(user()->id_cabang);
        $data['kategoris'] = $this->kategoriModel->findAll();

        return view('user/aset/create', $data);
    }

    private function makeToken(): string
    {
        return bin2hex(random_bytes(16)); // 32 hex chars
    }


    protected function generateKodeAset($id_cabang, $id_kategori, $tahun_perolehan)
    {
        $cabang = $this->cabangModel->find($id_cabang);
        $kategori = $this->kategoriModel->find($id_kategori);

        $kodeCabang = $cabang['kode_cabang'];
        $kodeKategori = $kategori['kode_kategori'];

        $count = $this->asetModel
            ->where('id_cabang', $id_cabang)
            ->where('id_kategori', $id_kategori)
            ->where('tahun_perolehan', $tahun_perolehan)
            ->countAllResults();

        $urut = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return "{$kodeCabang}-{$kodeKategori}-{$tahun_perolehan}-{$urut}";
    }

    public function store()
    {
        $id_cabang = user()->id_cabang;
        $id_kategori = $this->request->getPost('id_kategori');
        $tahun_perolehan = $this->request->getPost('tahun_perolehan');

        $kode_aset = $this->generateKodeAset($id_cabang, $id_kategori, $tahun_perolehan);

        $rules = [
            'nama_aset' => 'required',
            'id_kategori' => 'required',
            'tahun_perolehan' => 'required|numeric',
            'kondisi' => 'required',
            'status' => 'required',
            'gambar' => 'uploaded[gambar]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]|max_size[gambar,2048]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $gambar = $this->request->getFile('gambar');
        $namaGambar = null;
        if ($gambar->isValid() && !$gambar->hasMoved()) {
            $namaGambar = $gambar->getRandomName();
            $gambar->move('uploads/aset', $namaGambar);
        }

        $qr_token = $this->makeToken();
        while ($this->asetModel->where('qr_token', $qr_token)->countAllResults() > 0) {
            $qr_token = $this->makeToken();
        }

        $this->asetModel->insert([
            'kode_aset' => $kode_aset,
            'qr_token'       => $qr_token,
            'nama_aset' => $this->request->getPost('nama_aset'),
            'id_kategori' => $id_kategori,
            'id_cabang' => $id_cabang,
            'tahun_perolehan' => $tahun_perolehan,
            'kondisi' => $this->request->getPost('kondisi'),
            'status' => $this->request->getPost('status'),
            'gambar' => $namaGambar,
            'expired_at' => $this->request->getPost('expired_at') ?: null,
            'keterangan' => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to('/user/aset')->with('success', 'Aset berhasil ditambahkan.');
    }

    public function edit($id = null)
    {
        $aset = $this->asetModel->find($id);

        if (!$aset || $aset['id_cabang'] != user()->id_cabang) {
            return redirect()->to('/user/aset')->with('errors', 'Aset tidak ditemukan atau akses ditolak');
        }

        $data['title'] = 'Edit data';
        $data['aset'] =  $aset;
        $data['cabang'] = $this->cabangModel->find(user()->id_cabang);
        $data['kategoris'] = $this->kategoriModel->findAll();

        return view('user/aset/edit', $data);
    }

    public function update($id)
    {
        $aset = $this->asetModel->find($id);

        if (!$aset || $aset['id_cabang'] != user()->id_cabang) {
            return redirect()->to('/user/aset')->with('errors', 'Aset tidak ditemukan atau akses ditolak');
        }

        $rules = [
            'nama_aset' => 'required',
            'id_kategori' => 'required',
            'tahun_perolehan' => 'required|numeric',
            'kondisi' => 'required',
            'status' => 'required',
        ];

        $gambar = $this->request->getFile('gambar');
        if ($gambar && $gambar->isValid() && !$gambar->hasMoved()) {
            $rules['gambar'] = 'is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]|max_size[gambar,2048]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $dataUpdate = [
            'nama_aset' => $this->request->getPost('nama_aset'),
            'id_kategori' => $this->request->getPost('id_kategori'),
            'id_cabang' => user()->id_cabang,
            'tahun_perolehan' => $this->request->getPost('tahun_perolehan'),
            'kondisi' => $this->request->getPost('kondisi'),
            'status' => $this->request->getPost('status'),
            'expired_at' => $this->request->getPost('expired_at') ?: null,
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        if ($gambar && $gambar->isValid() && !$gambar->hasMoved()) {
            $namaGambar = $gambar->getRandomName();
            $gambar->move('uploads/aset', $namaGambar);

            if (!empty($aset['gambar']) && file_exists('uploads/aset/' . $aset['gambar'])) {
                unlink('uploads/aset/' . $aset['gambar']);
            }


            $dataUpdate['gambar'] = $namaGambar;
        }

        $this->asetModel->update($id, $dataUpdate);

        return redirect()->to('/user/aset')->with('success', 'Aset berhasil diperbaharui.');
    }

    public function delete($id)
    {
        $aset = $this->asetModel->find($id);

        if (!$aset || $aset['id_cabang'] != user()->id_cabang) {
            return redirect()->to('/user/aset')->with('errors', 'Aset tidak ditemukan atau akses ditolak');
        }

        if (!empty($aset['gambar']) && file_exists('uploads/aset/' . $aset['gambar'])) {
            unlink('uploads/aset/' . $aset['gambar']);
        }

        $this->asetModel->delete($id);
        return redirect()->to('/user/aset')->with('success', 'Aset berhasil dihapus!.');
    }

    public function detail($id = null)
    {
        $aset = $this->asetModel
            ->select('aset.*, cabang.nama_cabang, kategori_aset.nama_kategori')
            ->join('cabang', 'cabang.id_cabang = aset.id_cabang')
            ->join('kategori_aset', 'kategori_aset.id_kategori = aset.id_kategori')
            ->find($id);

        if (!$aset || $aset['id_cabang'] != user()->id_cabang) {
            return redirect()->to('/user/aset')->with('errors', 'Aset tidak ditemukan atau akses ditolak');
        }

        return view('user/aset/detail', [
            'title' => 'Detail Aset',
            'aset' => $aset,
        ]);
    }
}
