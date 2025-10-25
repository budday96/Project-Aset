<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Models\AsetModel;
use App\Models\CabangModel;
use App\Models\KategoriAsetModel;
use App\Models\AtributModel;
use App\Models\MasterAsetModel;

class Aset extends BaseController
{
    protected $asetModel, $cabangModel, $kategoriModel, $atributModel, $masterAsetModel;

    // Set true jika ingin kode_aset ikut berubah saat ganti master di update()
    private bool $regenerateKodeOnMasterChange = false;

    public function __construct()
    {
        $this->asetModel       = new AsetModel();
        $this->cabangModel     = new CabangModel();
        $this->kategoriModel   = new KategoriAsetModel();
        $this->atributModel    = new AtributModel();
        $this->masterAsetModel = new MasterAsetModel();
    }

    public function index()
    {
        $builder = $this->asetModel
            ->select(
                'aset.*,
                 cabang.nama_cabang,
                 kategori_aset.nama_kategori,
                 subkategori_aset.nama_subkategori,
                 master_aset.nama_master,
                 master_aset.deleted_at AS master_deleted_at'
            )
            ->join('cabang', 'cabang.id_cabang = aset.id_cabang')
            ->join('kategori_aset', 'kategori_aset.id_kategori = aset.id_kategori')
            ->join('subkategori_aset', 'subkategori_aset.id_subkategori = aset.id_subkategori', 'left')
            // LEFT JOIN supaya aset tetap tampil meskipun master di-soft delete
            ->join('master_aset', 'master_aset.id_master_aset = aset.id_master_aset', 'left')
            ->orderBy('id_aset', 'DESC');

        if (! in_groups('superadmin')) {
            $builder->where('aset.id_cabang', user()->id_cabang);
        }

        $data['title'] = 'Kelola Aset';
        $data['asets'] = $builder->findAll();

        return view('superadmin/aset/index', $data);
    }

    public function create()
    {
        $data['title']   = 'Tambah Aset';
        $data['cabangs'] = in_groups('superadmin')
            ? $this->cabangModel->findAll()
            : [$this->cabangModel->find(user()->id_cabang)];

        // Dropdown master → hanya master aktif (soft delete otomatis tersaring di model)
        $data['masters'] = $this->masterAsetModel->withJoin()->orderBy('nama_master', 'ASC')->findAll();

        // kategori untuk modal "Master Baru"
        $data['kategoris'] = $this->kategoriModel->orderBy('nama_kategori', 'ASC')->findAll();

        return view('superadmin/aset/create', $data);
    }

    private function makeToken(): string
    {
        return bin2hex(random_bytes(16));
    }

    /** Kode: <KODE_KATEGORI>-<TAHUN>-<NNN> */
    protected function generateKodeAsetByMaster(int $id_kategori, string $periode_perolehan /* yyyy-mm-01 */): string
    {
        $kategori     = $this->kategoriModel->find($id_kategori);
        $kodeKategori = $kategori['kode_kategori'] ?? ('KAT' . $id_kategori);

        $tahun = (int) date('Y', strtotime($periode_perolehan ?: 'now'));
        $urut  = $this->nextUrutPerKategoriTahun($id_kategori, $tahun);

        return sprintf('%s-%d-%03d', $kodeKategori, $tahun, $urut);
    }

    public function store()
    {
        $db = \Config\Database::connect();

        $id_cabang      = in_groups('superadmin') ? (int)$this->request->getPost('id_cabang') : (int)user()->id_cabang;
        $id_master_aset = (int)$this->request->getPost('id_master_aset');
        $qty            = (int)$this->request->getPost('stock');
        $merge          = (bool)$this->request->getPost('merge_if_exists'); // checkbox opsional

        $rules = [
            'id_master_aset' => 'required|integer',
            'kondisi'        => 'required',
            'status'         => 'required',
            'posisi'         => 'permit_empty|max_length[120]',
            'stock'          => 'required|integer|greater_than_equal_to[1]',
        ];

        // Validasi gambar (opsional)
        $gambar = $this->request->getFile('gambar');
        if ($gambar && $gambar->isValid() && ! $gambar->hasMoved()) {
            $rules['gambar'] = 'is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]|max_size[gambar,2048]';
        }
        if (in_groups('superadmin')) {
            $rules['id_cabang'] = 'required|integer';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Master & Cabang valid?
        $master = $this->masterAsetModel->find($id_master_aset);
        if (! $master) {
            return redirect()->back()->withInput()->with('error', 'Master aset tidak valid.');
        }
        if (! $this->cabangModel->find($id_cabang)) {
            return redirect()->back()->withInput()->with('error', 'Cabang tidak valid.');
        }

        // Upload (opsional)
        $namaGambar = null;
        if ($gambar && $gambar->isValid() && ! $gambar->hasMoved()) {
            if (!is_dir(FCPATH . 'uploads/aset')) {
                @mkdir(FCPATH . 'uploads/aset', 0777, true);
            }
            $namaGambar = $gambar->getRandomName();
            $gambar->move(FCPATH . 'uploads/aset', $namaGambar);
        }

        // === CEK apakah sudah ada baris untuk (id_master_aset, id_cabang) termasuk yang diarsip ===
        $existing = $this->asetModel
            ->withDeleted()
            ->where('id_master_aset', $id_master_aset)
            ->where('id_cabang', $id_cabang)
            ->first();

        // Periode default (YYYY-MM-01) → dipakai untuk kode & penyimpanan.
        $periodeDefault = $master['periode_perolehan_default'] ?: date('Y-m-01');

        // === CASE 1: SUDAH ADA & AKTIF
        if ($existing && empty($existing['deleted_at'])) {
            if ($merge) {
                // Tambah stok pada baris yang sudah ada + update beberapa field praktis
                $dataUpdate = [
                    'stock'      => (int)$existing['stock'] + $qty,
                    'kondisi'    => (string)$this->request->getPost('kondisi'),
                    'status'     => (string)$this->request->getPost('status'),
                    'posisi'     => $this->request->getPost('posisi'),
                    'updated_by' => user()->id ?? null,
                ];
                if ($namaGambar) {
                    // replace gambar bila upload baru
                    $dataUpdate['gambar'] = $namaGambar;
                    if (!empty($existing['gambar']) && is_file(FCPATH . 'uploads/aset/' . $existing['gambar'])) {
                        @unlink(FCPATH . 'uploads/aset/' . $existing['gambar']);
                    }
                }

                $this->asetModel->update((int)$existing['id_aset'], $dataUpdate);
                return redirect()->to('/superadmin/aset')->with('success', 'Stok aset ditambahkan pada aset yang sudah terdaftar pastikan untuk memeriksa kembali atributnya.');
            }

            // merge dimatikan → tolak insert
            return redirect()->back()->withInput()->with(
                'error',
                'Aset master ini sudah ada di cabang ini. Aktifkan “Tambah stok jika sudah ada” untuk menggabungkan.'
            );
        }

        // === CASE 2: ADA TAPI DIARSIP
        if ($existing && !empty($existing['deleted_at'])) {
            if ($merge) {
                // Pulihkan baris & isi stok baru (atau jadikan + stok lama jika mau)
                $dataUpdate = [
                    'deleted_at'        => null,
                    'deleted_by'        => null,
                    'stock'             => $qty, // pakai stok baru; ubah ke ($existing['stock'] + $qty) jika ingin akumulasi
                    'nilai_perolehan'   => $master['nilai_perolehan_default'],
                    'periode_perolehan' => $periodeDefault,
                    'expired_at'        => $master['expired_default'],
                    'kondisi'           => (string)$this->request->getPost('kondisi'),
                    'status'            => (string)$this->request->getPost('status'),
                    'posisi'            => $this->request->getPost('posisi'),
                    'keterangan'        => $this->request->getPost('keterangan'),
                    'updated_by'        => user()->id ?? null,
                ];
                if ($namaGambar) {
                    $dataUpdate['gambar'] = $namaGambar;
                }

                $this->asetModel->update((int)$existing['id_aset'], $dataUpdate);
                return redirect()->to('/superadmin/aset')->with('success', 'Baris aset dipulihkan & stok ditambahkan.');
            }

            return redirect()->back()->withInput()->with(
                'error',
                'Ada baris aset yang diarsip untuk master ini. Aktifkan “Tambah stok jika sudah ada” untuk memulihkan & menambah stok.'
            );
        }

        // === CASE 3: TIDAK ADA → INSERT BARU
        // Siapkan QR token unik
        $qr_token = $this->makeToken();
        while ($this->asetModel->where('qr_token', $qr_token)->countAllResults() > 0) {
            $qr_token = $this->makeToken();
        }

        // Kode aset baru
        $kode_aset = $this->generateKodeAsetByMaster((int)$master['id_kategori'], $periodeDefault);

        $db->transException(true)->transStart();
        try {
            // INSERT aset (tanpa nama_aset; tampilkan via JOIN master_aset)
            $idAset = $this->asetModel->insert([
                'kode_aset'         => $kode_aset,
                'qr_token'          => $qr_token,
                'id_master_aset'    => (int)$id_master_aset,
                'id_kategori'       => (int)$master['id_kategori'],
                'id_subkategori'    => (int)$master['id_subkategori'],
                'id_cabang'         => (int)$id_cabang,
                'nilai_perolehan'   => $master['nilai_perolehan_default'],
                'periode_perolehan' => $periodeDefault,
                'stock'             => $qty,
                'kondisi'           => (string)$this->request->getPost('kondisi'),
                'status'            => (string)$this->request->getPost('status'),
                'posisi'            => $this->request->getPost('posisi'),
                'gambar'            => $namaGambar,
                'expired_at'        => $master['expired_default'],
                'keterangan'        => $this->request->getPost('keterangan'),
                'created_by'        => user()->id ?? null,
                'updated_by'        => user()->id ?? null,
            ], true);

            if (!$idAset) {
                throw new \RuntimeException(implode('; ', (array)$this->asetModel->errors()) ?: 'Insert aset gagal tanpa pesan.');
            }

            // Clone atribut default → aset_atribut
            $defaults = $this->masterAsetModel->getAtributDefaults((int)$id_master_aset);
            if (!empty($defaults)) {
                $rows = [];
                foreach ($defaults as $d) {
                    $rows[] = [
                        'id_aset'    => (int)$idAset,
                        'id_atribut' => (int)$d['id_atribut'],
                        'nilai'      => $d['nilai_default'],
                    ];
                }
                if ($rows) {
                    $ok = $db->table('aset_atribut')->insertBatch($rows);
                    if (!$ok) {
                        $err = $db->error()['message'] ?? 'unknown';
                        throw new \RuntimeException('Gagal menyimpan atribut aset: ' . $err);
                    }
                }
            }

            $db->transComplete();
        } catch (\Throwable $e) {
            $db->transRollback();

            // Tangani duplikasi (race) dengan pesan ramah
            $msg = $e->getMessage();
            if (stripos($msg, 'Duplicate entry') !== false || strpos($msg, '1062') !== false) {
                if ($merge) {
                    return redirect()->back()->withInput()->with(
                        'error',
                        'Baris aset untuk master ini baru saja dibuat. Ulangi dengan opsi “Tambah stok jika sudah ada” atau coba lagi.'
                    );
                }
                return redirect()->back()->withInput()->with(
                    'error',
                    'Aset master ini sudah ada di cabang ini. Aktifkan “Tambah stok jika sudah ada” untuk menggabungkan.'
                );
            }

            log_message('error', 'Insert aset gagal: {msg}', ['msg' => $msg]);
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan aset: ' . $msg);
        }

        return redirect()->to('/superadmin/aset')->with('success', 'Aset berhasil ditambahkan dari master.');
    }


    public function edit($id)
    {
        // Ambil data aset untuk diedit
        $row = $this->asetModel
            ->select(
                'aset.*,
                 cabang.nama_cabang,
                 kategori_aset.nama_kategori,
                 subkategori_aset.nama_subkategori,
                 master_aset.nama_master,
                 master_aset.deleted_at AS master_deleted_at'
            )
            ->join('cabang', 'cabang.id_cabang = aset.id_cabang')
            ->join('kategori_aset', 'kategori_aset.id_kategori = aset.id_kategori')
            ->join('subkategori_aset', 'subkategori_aset.id_subkategori = aset.id_subkategori', 'left')
            ->join('master_aset', 'master_aset.id_master_aset = aset.id_master_aset', 'left')
            ->where('aset.id_aset', (int)$id)
            ->first();

        if (! $row) {
            return redirect()->to('/superadmin/aset')->with('errors', 'Data aset tidak ditemukan');
        }
        if (! in_groups('superadmin') && (int)$row['id_cabang'] !== (int)user()->id_cabang) {
            return redirect()->to('/superadmin/aset')->with('errors', 'Akses ditolak');
        }

        // Masters aktif
        $masters = $this->masterAsetModel->withJoin()->orderBy('nama_master', 'ASC')->findAll();

        // Jika master saat ini ter-arsip, tambahkan supaya tetap terlihat di dropdown (selected)
        if (!empty($row['id_master_aset'])) {
            $current = $this->masterAsetModel
                ->withDeleted()
                ->withJoin()
                ->where('id_master_aset', (int)$row['id_master_aset'])
                ->first();
            if ($current) {
                $exists = false;
                foreach ($masters as $m) {
                    if ((int)$m['id_master_aset'] === (int)$current['id_master_aset']) {
                        $exists = true;
                        break;
                    }
                }
                if (!$exists) {
                    // sisipkan di awal agar mudah terlihat
                    array_unshift($masters, $current);
                }
            }
        }

        $data = [
            'title'   => 'Ubah Aset',
            'row'     => $row,
            'masters' => $masters,
            'cabangs' => in_groups('superadmin')
                ? $this->cabangModel->findAll()
                : [$this->cabangModel->find(user()->id_cabang)],
        ];

        return view('superadmin/aset/edit', $data);
    }

    public function update($id)
    {
        $aset = $this->asetModel->find((int)$id);
        if (! $aset) {
            return redirect()->to('/superadmin/aset')->with('errors', 'Data aset tidak ditemukan');
        }
        if (! in_groups('superadmin') && (int)$aset['id_cabang'] !== (int)user()->id_cabang) {
            return redirect()->to('/superadmin/aset')->with('errors', 'Akses ditolak');
        }

        $rules = [
            'id_master_aset' => 'required|integer',
            'kondisi'        => 'required',
            'status'         => 'required',
            'posisi'         => 'permit_empty|max_length[120]',
            'stock'          => 'required|integer|greater_than_equal_to[1]',
        ];
        if (in_groups('superadmin')) {
            $rules['id_cabang'] = 'required|integer';
        }

        $gambar = $this->request->getFile('gambar');
        if ($gambar && $gambar->isValid() && ! $gambar->hasMoved()) {
            $rules['gambar'] = 'is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]|max_size[gambar,2048]';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transException(true)->transStart();

        try {
            $id_cabang    = in_groups('superadmin') ? (int)$this->request->getPost('id_cabang') : (int)user()->id_cabang;
            $newMasterId  = (int)$this->request->getPost('id_master_aset');
            $master       = null;
            $syncFromMaster = false;

            if ((int)$aset['id_master_aset'] !== $newMasterId) {
                // ganti master → full sync
                $master = $this->masterAsetModel->find($newMasterId);
                if (! $master) {
                    throw new \RuntimeException('Master aset tidak valid.');
                }
                $syncFromMaster = true;
            }

            $dataUpdate = [
                'id_master_aset' => $newMasterId,
                'id_cabang'      => $id_cabang,
                'kondisi'        => (string)$this->request->getPost('kondisi'),
                'status'         => (string)$this->request->getPost('status'),
                'posisi'         => $this->request->getPost('posisi'),
                'keterangan'     => $this->request->getPost('keterangan'),
                'stock'          => (int)$this->request->getPost('stock'),
                'updated_by'     => user()->id ?? null,
            ];

            // Jika perlu (ganti master) → sinkron klasifikasi + defaults (tanpa tahun_perolehan)
            if ($syncFromMaster) {
                $dataUpdate['id_kategori']       = (int)$master['id_kategori'];
                $dataUpdate['id_subkategori']    = (int)$master['id_subkategori'];
                $dataUpdate['nilai_perolehan']   = $master['nilai_perolehan_default'];
                $dataUpdate['periode_perolehan'] = $master['periode_perolehan_default'] ?: null;
                $dataUpdate['expired_at']        = $master['expired_default'];

                if ($this->regenerateKodeOnMasterChange) {
                    $periodeBasis = $master['periode_perolehan_default'] ?: date('Y-m-01');
                    $dataUpdate['kode_aset'] = $this->generateKodeAsetByMaster((int)$master['id_kategori'], $periodeBasis);
                }
            }

            // Replace gambar (opsional)
            if ($gambar && $gambar->isValid() && ! $gambar->hasMoved()) {
                if (!is_dir(FCPATH . 'uploads/aset')) {
                    @mkdir(FCPATH . 'uploads/aset', 0777, true);
                }
                $namaGambar = $gambar->getRandomName();
                $gambar->move(FCPATH . 'uploads/aset', $namaGambar);
                if (! empty($aset['gambar']) && is_file(FCPATH . 'uploads/aset/' . $aset['gambar'])) {
                    @unlink(FCPATH . 'uploads/aset/' . $aset['gambar']);
                }
                $dataUpdate['gambar'] = $namaGambar;
            }

            $this->asetModel->update((int)$id, $dataUpdate);

            // Jika master berubah → reset atribut & clone default baru
            if ($syncFromMaster) {
                $tb = $db->table('aset_atribut');
                $tb->where('id_aset', (int)$id)->delete();

                $defaults = $this->masterAsetModel->getAtributDefaults($newMasterId);
                if (!empty($defaults)) {
                    $rows = [];
                    foreach ($defaults as $d) {
                        $rows[] = [
                            'id_aset'    => (int)$id,
                            'id_atribut' => (int)$d['id_atribut'],
                            'nilai'      => $d['nilai_default'],
                        ];
                    }
                    if ($rows) {
                        $ok = $tb->insertBatch($rows);
                        if (!$ok) {
                            $err = $db->error()['message'] ?? 'unknown';
                            throw new \RuntimeException('Gagal menyimpan atribut aset: ' . $err);
                        }
                    }
                }
            }

            $db->transComplete();
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Update aset gagal: {msg}', ['msg' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui aset: ' . $e->getMessage());
        }

        return redirect()->to('/superadmin/aset')->with('success', 'Aset diperbarui.');
    }

    // Detail
    public function detail($id)
    {
        $row = $this->asetModel
            ->withDeleted() // untuk bisa baca data yang terhapus
            ->select('aset.*, cabang.nama_cabang, kategori_aset.nama_kategori, subkategori_aset.nama_subkategori,
              master_aset.nama_master, master_aset.deleted_at AS master_deleted_at')
            ->join('cabang', 'cabang.id_cabang = aset.id_cabang')
            ->join('kategori_aset', 'kategori_aset.id_kategori = aset.id_kategori')
            ->join('subkategori_aset', 'subkategori_aset.id_subkategori = aset.id_subkategori', 'left')
            ->join('master_aset', 'master_aset.id_master_aset = aset.id_master_aset', 'left')
            ->where('aset.id_aset', (int)$id)
            ->first();

        if (!$row) {
            return redirect()->to('/superadmin/aset')->with('errors', 'Data aset tidak ditemukan');
        }
        if (!in_groups('superadmin') && (int)$row['id_cabang'] !== (int)user()->id_cabang) {
            return redirect()->to('/superadmin/aset')->with('errors', 'Akses ditolak');
        }

        // Ambil atribut aset (adaptif: atribut vs atribut_aset)
        $db = \Config\Database::connect();
        $atributs = [];

        // Tentukan tabel definisi atribut yang tersedia
        $defTable = $db->tableExists('atribut')
            ? 'atribut'
            : ($db->tableExists('atribut_aset') ? 'atribut_aset' : null);

        if ($defTable) {
            // Join ke tabel definisi untuk ambil nama_atribut (+ urutan jika ada)
            $atributs = $db->table('aset_atribut AS aa')
                ->select('aa.nilai, def.nama_atribut')
                ->join($defTable . ' AS def', 'def.id_atribut = aa.id_atribut', 'left')
                // order by urutan jika kolomnya ada; kalau tidak ada, COALESCE akan jadi 9999
                ->orderBy('COALESCE(def.urutan, 9999)', 'ASC', false)
                ->where('aa.id_aset', (int)$id)
                ->get()->getResultArray();
        } else {
            // Fallback: tabel definisi tidak ada → tampilkan tetap dengan placeholder nama
            $atributs = $db->table('aset_atribut AS aa')
                ->select('aa.nilai, CONCAT("Atribut #", aa.id_atribut) AS nama_atribut', false)
                ->where('aa.id_aset', (int)$id)
                ->orderBy('aa.id_atribut', 'ASC')
                ->get()->getResultArray();
        }

        return view('superadmin/aset/detail', [
            'title'    => 'Detail Aset',
            'row'      => $row,
            'atributs' => $atributs,
        ]);
    }

    public function delete($id)
    {
        $row = $this->asetModel->find((int)$id);
        if (! $row) return redirect()->to('/superadmin/aset')->with('errors', 'Data aset tidak ditemukan');
        if (! in_groups('superadmin') && (int)$row['id_cabang'] !== (int)user()->id_cabang)
            return redirect()->to('/superadmin/aset')->with('errors', 'Akses ditolak');

        $this->asetModel->update((int)$id, ['deleted_by' => user()->id ?? null]); // opsional
        $this->asetModel->delete((int)$id); // soft delete → set deleted_at

        return redirect()->to('/superadmin/aset')->with('success', 'Aset diarsipkan.');
    }



    // AJAX: pratinjau detail master
    public function ajaxMasterDetail($idMaster)
    {
        $row = $this->masterAsetModel
            ->withDeleted()   // agar detail tetap bisa diambil bila master diarsip
            ->withJoin()
            ->where('id_master_aset', (int)$idMaster)
            ->first();

        if (!$row) {
            return $this->response->setJSON(['ok' => false, 'message' => 'Master tidak ditemukan'])
                ->setStatusCode(404);
        }

        $attrDefaults = $this->masterAsetModel->getAtributDefaults((int)$idMaster);
        $attrs = array_map(static function ($a) {
            $opts = [];
            if (!empty($a['options_json'])) {
                $decoded = json_decode($a['options_json'], true);
                $opts = is_array($decoded) ? $decoded : [];
            }
            return [
                'id_atribut'   => (int)$a['id_atribut'],
                'nama_atribut' => (string)$a['nama_atribut'],
                'tipe_input'   => (string)$a['tipe_input'],
                'satuan'       => $a['satuan'],
                'is_required'  => (int)$a['is_required'],
                'urutan'       => (int)$a['urutan'],
                'options'      => $opts,
                'nilai'        => $a['nilai_default'],
            ];
        }, $attrDefaults);

        return $this->response->setJSON([
            'ok'               => true,
            'id_master_aset'   => (int)$row['id_master_aset'],
            'nama_master'      => $row['nama_master'],
            'id_kategori'      => (int)$row['id_kategori'],
            'id_subkategori'   => (int)$row['id_subkategori'],
            'nama_kategori'    => $row['nama_kategori'] ?? null,
            'nama_subkategori' => $row['nama_subkategori'] ?? null,
            'expired_default'  => $row['expired_default'],
            'nilai_default'    => $row['nilai_perolehan_default'],
            'periode_default'  => $row['periode_perolehan_default'], // YYYY-MM-01
            'is_archived'      => !empty($row['deleted_at']),
            'atribut'          => $attrs,
        ]);
    }

    /**
     * Ambil nomor urut berikutnya per (id_kategori, tahun) secara atomic.
     * Menggunakan tabel aset_counter (PK: id_kategori, tahun).
     */
    private function nextUrutPerKategoriTahun(int $idKategori, int $tahun): int
    {
        $db = \Config\Database::connect();
        $db->transException(true)->transStart();
        try {
            // init baris jika belum ada
            $db->query(
                'INSERT INTO aset_counter (id_kategori, tahun, last_no)
                 VALUES (?, ?, 0)
                 ON DUPLICATE KEY UPDATE last_no = last_no',
                [$idKategori, $tahun]
            );
            // atomic increment
            $db->query(
                'UPDATE aset_counter
                 SET last_no = LAST_INSERT_ID(last_no + 1)
                 WHERE id_kategori = ? AND tahun = ?',
                [$idKategori, $tahun]
            );
            $row = $db->query('SELECT LAST_INSERT_ID() AS urut')->getRowArray();
            $db->transComplete();
            return (int)($row['urut'] ?? 1);
        } catch (\Throwable $e) {
            $db->transRollback();
            throw $e;
        }
    }

    public function trash()
    {
        $items = $this->asetModel
            ->withDeleted()->onlyDeleted()
            ->select('aset.*, cabang.nama_cabang, master_aset.nama_master')
            ->join('cabang', 'cabang.id_cabang = aset.id_cabang')
            ->join('master_aset', 'master_aset.id_master_aset = aset.id_master_aset', 'left')
            ->orderBy('deleted_at', 'DESC')
            ->findAll();

        return view('superadmin/aset/trash', ['title' => 'Arsip Aset', 'items' => $items]);
    }

    public function restore($id)
    {
        $db = \Config\Database::connect();
        $ok = $db->table('aset')->set('deleted_at', null, false)
            ->set('deleted_by', null, false)
            ->where('id_aset', (int)$id)->update();

        return $ok
            ? redirect()->to('/superadmin/aset/trash')->with('success', 'Aset dipulihkan.')
            : redirect()->back()->with('error', 'Gagal memulihkan aset.');
    }

    public function purge($id)
    {
        // Hapus permanen + bereskan anak tabel & file
        $db = \Config\Database::connect();
        $row = $this->asetModel->withDeleted()->find((int)$id);
        if (! $row) return redirect()->back()->with('error', 'Data aset tidak ditemukan');

        $db->transStart();
        if ($db->tableExists('aset_atribut')) {
            $db->table('aset_atribut')->where('id_aset', (int)$id)->delete();
        }
        if ($db->tableExists('mutasi_aset')) {
            $db->table('mutasi_aset')->where('id_aset', (int)$id)->delete();
        }
        $this->asetModel->delete((int)$id, true); // purge = true
        $db->transComplete();

        if (!empty($row['gambar'])) {
            $path = FCPATH . 'uploads/aset/' . $row['gambar'];
            if (is_file($path)) @unlink($path);
        }

        return redirect()->to('/superadmin/aset/trash')->with('success', 'Aset dihapus permanen.');
    }
}
