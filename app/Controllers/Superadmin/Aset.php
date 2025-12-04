<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Models\AsetModel;
use App\Models\CabangModel;
use App\Models\KategoriAsetModel;
use App\Models\AtributModel;
use App\Models\MasterAsetModel;
use App\Models\SubkategoriAsetModel;

class Aset extends BaseController
{
    protected $asetModel, $cabangModel, $kategoriModel, $atributModel, $masterAsetModel, $subkategoriModel;
    // Set true jika ingin kode_aset ikut berubah saat ganti master di update()
    private bool $regenerateKodeOnMasterChange = false;

    public function __construct()
    {
        $this->asetModel       = new AsetModel();
        $this->cabangModel     = new CabangModel();
        $this->kategoriModel   = new KategoriAsetModel();
        $this->atributModel    = new AtributModel();
        $this->masterAsetModel = new MasterAsetModel();
        $this->subkategoriModel = new SubkategoriAsetModel();
    }

    public function index()
    {
        // === Query utama untuk data aset ===
        $builder = $this->asetModel
            ->where('aset.deleted_at IS NULL', null, false)
            ->select('aset.*, cabang.nama_cabang, kategori_aset.nama_kategori, subkategori_aset.nama_subkategori, master_aset.nama_master')
            ->join('cabang', 'cabang.id_cabang = aset.id_cabang')
            ->join('kategori_aset', 'kategori_aset.id_kategori = aset.id_kategori')
            ->join('subkategori_aset', 'subkategori_aset.id_subkategori = aset.id_subkategori', 'left')
            ->join('master_aset', 'master_aset.id_master_aset = aset.id_master_aset', 'left')
            ->orderBy('id_aset', 'DESC');

        // Filter cabang untuk non-superadmin
        if (! in_groups('superadmin')) {
            $builder->where('aset.id_cabang', user()->id_cabang);
        }

        $data['title'] = 'Kelola Aset';
        $data['asets'] = $builder->findAll();

        // ============================================================
        // Tambahan untuk dropdown filter di halaman index
        // ============================================================

        // Load Semua Kategori
        $data['kategoris'] = $this->kategoriModel
            ->orderBy('nama_kategori', 'ASC')
            ->get()->getResultArray();

        // Load Semua Cabang
        // Superadmin -> semua cabang
        // User biasa -> hanya cabangnya
        if (in_groups('superadmin')) {
            $data['cabangs'] = $this->cabangModel
                ->orderBy('nama_cabang', 'ASC')
                ->get()->getResultArray();
        } else {
            $data['cabangs'] = $this->cabangModel
                ->where('id_cabang', user()->id_cabang)
                ->get()->getResultArray();
        }

        // ============================================================

        return view('superadmin/aset/index', $data);
    }


    public function create()
    {
        $data['title']   = 'Tambah Aset';
        $data['cabangs'] = in_groups('superadmin')
            ? $this->cabangModel->findAll()
            : [$this->cabangModel->find(user()->id_cabang)];

        $data['masters'] = $this->masterAsetModel
            ->withJoin()
            ->orderBy('nama_master', 'ASC')
            ->findAll();

        $data['kategoris'] = $this->kategoriModel
            ->orderBy('nama_kategori', 'ASC')
            ->findAll();

        // ▶️ PATCH BARU: supaya view bisa menerima atribut
        $data['atributDefaults'] = [];

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

    /**
     * Upload & kompres gambar aset dengan aturan ketat.
     * Mengembalikan filename baru atau NULL (jika user tidak upload).
     */
    private function uploadAsetImage(string $fieldName = 'gambar', ?string $oldFile = null): ?string
    {
        $file = $this->request->getFile($fieldName);

        // Jika tidak ada file dipilih
        if (!$file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if (!$file->isValid()) {
            throw new \RuntimeException('Upload gagal: ' . $file->getErrorString());
        }

        // Validasi MIME
        $allowedMime = ['image/jpeg', 'image/png'];
        if (!in_array($file->getMimeType(), $allowedMime, true)) {
            throw new \RuntimeException('Format gambar harus JPG atau PNG.');
        }

        // Validasi ekstensi
        $allowedExt = ['jpg', 'jpeg', 'png'];
        if (!in_array(strtolower($file->getExtension()), $allowedExt, true)) {
            throw new \RuntimeException('Ekstensi gambar tidak diizinkan.');
        }

        // Validasi ukuran maksimal 3 MB
        if ($file->getSize() > 3 * 1024 * 1024) {
            throw new \RuntimeException('Ukuran gambar maksimal 3 MB.');
        }

        // Validasi resolusi
        $info = getimagesize($file->getTempName());
        if (!$info) {
            throw new \RuntimeException('File bukan gambar valid.');
        }

        if ($info[0] > 4000 || $info[1] > 4000) {
            throw new \RuntimeException('Resolusi melebihi batas 4000x4000 px.');
        }

        // Pastikan folder uploads exists
        $uploadDir = FCPATH . 'uploads/aset';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate nama file aman
        $newName = $file->getRandomName();
        $finalPath = $uploadDir . '/' . $newName;

        // Kompres & resize otomatis
        \Config\Services::image()
            ->withFile($file)
            ->resize(1600, 1600, true, 'auto')  // resize only if needed
            ->save($finalPath, 75);             // kualitas 75

        if (!is_file($finalPath)) {
            throw new \RuntimeException('Gagal menyimpan gambar.');
        }

        // Hapus gambar lama jika ada & bukan default
        if ($oldFile && $oldFile !== 'no-image.png') {
            $oldPath = $uploadDir . '/' . $oldFile;
            if (is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        return $newName;
    }


    public function store()
    {
        $db = \Config\Database::connect();

        $id_cabang      = in_groups('superadmin') ? (int)$this->request->getPost('id_cabang') : (int)user()->id_cabang;
        $id_master_aset = (int)$this->request->getPost('id_master_aset');
        $qty            = (int)$this->request->getPost('stock');
        $merge          = (bool)$this->request->getPost('merge_if_exists');

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

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $master = $this->masterAsetModel->find($id_master_aset);
        if (!$master) {
            return redirect()->back()->withInput()->with('error', 'Master aset tidak valid.');
        }

        // Upload gambar
        try {
            $uploaded = $this->uploadAsetImage('gambar');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        // Fallback
        $namaGambar = $uploaded ?: 'no-image.png';

        // Periode default
        $periodeDefault = $master['periode_perolehan_default'] ?: date('Y-m-01');

        // === Cek existing record
        $existing = $this->asetModel
            ->withDeleted()
            ->where('id_master_aset', $id_master_aset)
            ->where('id_cabang', $id_cabang)
            ->first();

        // Case 1: Sudah ada & aktif
        if ($existing && empty($existing['deleted_at'])) {

            // Jika merge diaktifkan
            if ($merge) {
                $dataUpdate = [
                    'stock'      => $existing['stock'] + $qty,
                    'kondisi'    => $this->request->getPost('kondisi'),
                    'status'     => $this->request->getPost('status'),
                    'posisi'     => $this->request->getPost('posisi'),
                    'updated_by' => user()->id ?? null,
                ];

                if ($uploaded) {
                    $dataUpdate['gambar'] = $uploaded;
                }

                $this->asetModel->update($existing['id_aset'], $dataUpdate);

                return redirect()->to('/superadmin/aset')
                    ->with('success', 'Stok ditambahkan pada aset yang sudah ada.');
            }

            return redirect()->back()->withInput()->with('error', 'Aset sudah ada. Aktifkan merge untuk menambah stok.');
        }

        // === CASE 2 : Ada existing tetapi SOFT-DELETED ===
        if ($existing && !empty($existing['deleted_at'])) {

            // kembalikan row dari soft delete
            $this->asetModel->update($existing['id_aset'], [
                'deleted_at' => null,
                'deleted_by' => null,
                'stock'      => $qty,
                'kondisi'    => $this->request->getPost('kondisi'),
                'status'     => $this->request->getPost('status'),
                'posisi'     => $this->request->getPost('posisi'),
                'updated_by' => user()->id ?? null,
            ]);

            // restore atribut default master juga
            $db->table('aset_atribut')->where('id_aset', $existing['id_aset'])->delete();

            $defaults = $this->masterAsetModel->getAtributDefaults($id_master_aset);
            if (!empty($defaults)) {
                $rows = [];
                foreach ($defaults as $d) {
                    $rows[] = [
                        'id_aset'    => $existing['id_aset'],
                        'id_atribut' => $d['id_atribut'],
                        'nilai'      => $d['nilai_default'],
                    ];
                }
                if ($rows) $db->table('aset_atribut')->insertBatch($rows);
            }

            return redirect()->to('/superadmin/aset')
                ->with('success', 'Aset dipulihkan dari arsip dan diperbarui.');
        }


        // === Case 3: Insert Baru ===
        // QR Token
        $qr_token = bin2hex(random_bytes(16));

        // Kode aset
        $kode_aset = $this->generateKodeAsetByMaster((int)$master['id_kategori'], $periodeDefault);

        // Insert
        $newId = $this->asetModel->insert([
            'kode_aset'         => $kode_aset,
            'qr_token'          => $qr_token,
            'id_master_aset'    => $id_master_aset,
            'id_kategori'       => $master['id_kategori'],
            'id_subkategori'    => $master['id_subkategori'],
            'id_cabang'         => $id_cabang,
            'nilai_perolehan'   => $master['nilai_perolehan_default'],
            'periode_perolehan' => $periodeDefault,
            'stock'             => $qty,
            'kondisi'           => $this->request->getPost('kondisi'),
            'status'            => $this->request->getPost('status'),
            'posisi'            => $this->request->getPost('posisi'),
            'gambar'            => $namaGambar,
            'expired_at'        => $master['expired_default'],
            'created_by'        => user()->id ?? null,
            'updated_by'        => user()->id ?? null,
        ]);

        // ▶️ PATCH BARU: Simpan default atribut master ke aset
        if ($newId) {
            $defaults = $this->masterAsetModel->getAtributDefaults($id_master_aset);

            if (!empty($defaults)) {
                $rows = [];
                foreach ($defaults as $a) {
                    $rows[] = [
                        'id_aset'    => $newId,
                        'id_atribut' => $a['id_atribut'],
                        'nilai'      => $a['nilai_default'], // bisa text/number/json
                    ];
                }
                if (!empty($rows)) {
                    $db->table('aset_atribut')->insertBatch($rows);
                }
            }
        }

        return redirect()->to('/superadmin/aset')->with('success', 'Aset berhasil ditambahkan.');
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
        $aset = $this->asetModel->find($id);
        if (!$aset) {
            return redirect()->to('/superadmin/aset')->with('error', 'Data aset tidak ditemukan.');
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

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $oldImage = $aset['gambar'] ?? null;

        // Upload gambar baru
        try {
            $uploaded = $this->uploadAsetImage('gambar', $oldImage);
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        $dataUpdate = [
            'id_master_aset' => (int)$this->request->getPost('id_master_aset'),
            'id_cabang'      => in_groups('superadmin') ? (int)$this->request->getPost('id_cabang') : $aset['id_cabang'],
            'kondisi'        => $this->request->getPost('kondisi'),
            'status'         => $this->request->getPost('status'),
            'posisi'         => $this->request->getPost('posisi'),
            'stock'          => (int)$this->request->getPost('stock'),
            'keterangan'     => $this->request->getPost('keterangan'),
            'updated_by'     => user()->id ?? null,
        ];

        if ($uploaded) {
            $dataUpdate['gambar'] = $uploaded;
        }

        $this->asetModel->update($id, $dataUpdate);

        return redirect()->to('/superadmin/aset')->with('success', 'Aset berhasil diperbarui.');
    }


    public function detail($id)
    {
        $row = $this->asetModel
            ->withDeleted()
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

        $db = \Config\Database::connect();

        // Tentukan tabel definisi atribut
        $defTable = $db->tableExists('atribut') ? 'atribut'
            : ($db->tableExists('atribut_aset') ? 'atribut_aset' : null);

        if ($defTable) {
            // Jika pakai atribut_aset (yang punya deleted_at), tambahkan filter soft-delete di JOIN
            $joinCond = 'def.id_atribut = aa.id_atribut'
                . ($defTable === 'atribut_aset' ? ' AND def.deleted_at IS NULL' : '');

            $atributs = $db->table('aset_atribut AS aa')
                ->select('aa.nilai, def.nama_atribut')
                ->join($defTable . ' AS def', $joinCond, 'left')
                // order by urutan bila ada; kalau tidak ada -> 9999 supaya tetap konsisten
                ->orderBy('COALESCE(def.urutan, 9999)', 'ASC', false)
                ->where('aa.id_aset', (int)$id)
                ->get()->getResultArray();
        } else {
            // Fallback bila tidak ada tabel definisi
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

    // ---------- AJAX: Get Data Master untuk Select2 ----------
    public function getMasterAset()
    {
        $term = $this->request->getGet('term');
        $db   = \Config\Database::connect();

        $builder = $db->table('master_aset');

        // ❗ HANYA tampilkan master yang tidak di-soft delete
        $builder->where('deleted_at IS NULL', null, false);

        if ($term) {
            $builder->like('nama_master', $term);
        }

        $query = $builder
            ->select('id_master_aset, nama_master')
            ->orderBy('nama_master', 'ASC')
            ->limit(20)
            ->get()
            ->getResult();

        $data = [];
        foreach ($query as $row) {
            $data[] = [
                'id'   => $row->id_master_aset,
                'text' => $row->nama_master
            ];
        }

        return $this->response->setJSON(['results' => $data]);
    }


    // ---------- AJAX: Detail Master ----------
    public function detailMaster($id)
    {
        $db = \Config\Database::connect();

        $builder = $db->table('master_aset ma');
        $builder->select('
        ma.id_master_aset,
        ma.nama_master,
        ma.nilai_perolehan_default,
        ma.periode_perolehan_default,
        k.nama_kategori,
        s.nama_subkategori
    ')
            ->join('kategori_aset k', 'k.id_kategori = ma.id_kategori', 'left')
            ->join('subkategori_aset s', 's.id_subkategori = ma.id_subkategori', 'left')
            ->where('ma.id_master_aset', $id)
            ->where('ma.deleted_at IS NULL', null, false);

        $data = $builder->get()->getRow();

        if (!$data) {
            return $this->response->setJSON(['error' => 'Data tidak ditemukan']);
        }

        $bulanTahun = $data->periode_perolehan_default
            ? date('F Y', strtotime($data->periode_perolehan_default))
            : '-';

        return $this->response->setJSON([
            'kategori'               => $data->nama_kategori ?? '-',
            'subkategori'            => $data->nama_subkategori ?? '-',
            'nilai_perolehan'        => number_format($data->nilai_perolehan_default, 0, ',', '.'),
            'bulan_tahun_perolehan'  => $bulanTahun
        ]);
    }
}
