<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Models\MasterAsetModel;
use App\Models\KategoriAsetModel;
use App\Models\KelompokHartaModel;

class MasterAset extends BaseController
{
    protected $masterModel, $kategoriModel, $kelompokModel;

    public function __construct()
    {
        $this->masterModel   = new MasterAsetModel();
        $this->kategoriModel = new KategoriAsetModel();
        $this->kelompokModel = new KelompokHartaModel();
    }

    public function index()
    {
        // Soft delete aktif → otomatis hanya data yang belum dihapus
        $data = [
            'title' => 'Master Aset',
            'items' => $this->masterModel->withJoin()->orderBy('nama_master', 'ASC')->findAll(),
        ];
        return view('superadmin/master_aset/index', $data);
    }

    // Arsip (yang sudah di-soft delete)
    public function trash()
    {
        $items = $this->masterModel->withJoin()
            ->withDeleted()->onlyDeleted()
            ->orderBy('deleted_at', 'DESC')
            ->findAll();

        return view('superadmin/master_aset/trash', [
            'title' => 'Arsip Master Aset',
            'items' => $items,
        ]);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Master Aset',
            'kategoris' => $this->kategoriModel->findAll(),
            'kelompokHarta' => $this->kelompokModel->findAll(),
        ];
        return view('superadmin/master_aset/create', $data);
    }

    public function store()
    {
        $rules = [
            'nama_master' => 'required',
            'id_kategori' => 'required|integer',
            'id_subkategori' => 'required|integer',
            'id_kelompok_harta' => 'required|integer',
            'expired_default' => 'permit_empty|valid_date[Y-m-d]',
            'nilai_perolehan_default' => 'permit_empty|decimal',
            'periode_perolehan_default_month' => 'permit_empty|regex_match[/^\d{4}\-(0[1-9]|1[0-2])$/]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $periodeMonth = trim((string)$this->request->getPost('periode_perolehan_default_month'));
        $periodeDate = $periodeMonth ? ($periodeMonth . '-01') : null;

        $payload = [
            'nama_master' => trim((string)$this->request->getPost('nama_master')),
            'id_kategori' => (int)$this->request->getPost('id_kategori'),
            'id_subkategori' => (int)$this->request->getPost('id_subkategori'),
            'id_kelompok_harta' => (int)$this->request->getPost('id_kelompok_harta'),
            'expired_default' => $this->request->getPost('expired_default') ?: null,
            'nilai_perolehan_default' => $this->request->getPost('nilai_perolehan_default') ?: null,
            'periode_perolehan_default' => $periodeDate,
        ];

        // Guard FK ramah
        if (!$this->kategoriModel->find($payload['id_kategori'])) {
            return redirect()->back()->withInput()->with('error', 'Kategori tidak valid.');
        }
        $db = \Config\Database::connect();
        $existsSub = $db->table('subkategori_aset')->select('id_subkategori')
            ->where('id_subkategori', $payload['id_subkategori'])->get()->getRowArray();
        if (!$existsSub) {
            return redirect()->back()->withInput()->with('error', 'Subkategori tidak valid.');
        }

        $db->transException(true)->transStart();
        try {
            $idMaster = $this->masterModel->insert($payload, true);
            if (!$idMaster) {
                throw new \RuntimeException(implode('; ', (array)$this->masterModel->errors()) ?: 'Insert model gagal tanpa pesan.');
            }

            // Generate kode_master
            $kode = $this->buildKodeMaster((int)$idMaster, (int)$payload['id_kategori'], (int)$payload['id_subkategori']);
            if (!$this->masterModel->update($idMaster, ['kode_master' => $kode])) {
                throw new \RuntimeException('Gagal set kode_master: ' . (implode('; ', (array)$this->masterModel->errors()) ?: 'unknown'));
            }

            // Simpan default atribut (batch)
            $defaults = $this->request->getPost('default_atribut') ?? [];
            if (is_array($defaults) && !empty($defaults)) {
                $rows = [];
                foreach ($defaults as $idAttr => $nilai) {
                    $val = is_array($nilai) ? json_encode($nilai, JSON_UNESCAPED_UNICODE) : trim((string)$nilai);
                    if ($val === '') continue;
                    $rows[] = [
                        'id_master_aset' => (int)$idMaster,
                        'id_atribut'     => (int)$idAttr,
                        'nilai_default'  => $val,
                    ];
                }
                if ($rows) {
                    $ok = $db->table('master_aset_atribut')->insertBatch($rows);
                    if (!$ok) {
                        $err = $db->error()['message'] ?? 'unknown';
                        throw new \RuntimeException('Gagal simpan atribut default: ' . $err);
                    }
                }
            }

            $db->transComplete();
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Insert master_aset gagal: {msg}', ['msg' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Gagal insert master: ' . $e->getMessage());
        }

        return redirect()->to('/superadmin/master-aset')->with('success', 'Master aset dibuat.');
    }

    public function edit($id)
    {
        $row = $this->masterModel->withJoin()->where('id_master_aset', (int)$id)->first();
        if (!$row) {
            return redirect()->to('/superadmin/master-aset')->with('errors', 'Data tidak ditemukan');
        }


        $data = [
            'title' => 'Ubah Master Aset',
            'row' => $row,
            'kategoris' => $this->kategoriModel->findAll(),
            'kelompokHarta' => $this->kelompokModel->findAll(),
        ];
        return view('superadmin/master_aset/edit', $data);
    }

    public function update($id)
    {
        // Ambil data lama (termasuk soft delete)
        $row = $this->masterModel->withDeleted()->find((int)$id);
        if (!$row) {
            return redirect()->to('/superadmin/master-aset')->with('errors', 'Data tidak ditemukan');
        }

        // Validasi input
        $rules = [
            'nama_master'                      => 'required',
            'id_kategori'                      => 'required|integer',
            'id_subkategori'                   => 'required|integer',
            'id_kelompok_harta'                => 'required|integer',
            'expired_default'                  => 'permit_empty|valid_date[Y-m-d]',
            'nilai_perolehan_default'          => 'permit_empty|decimal',
            'periode_perolehan_default_month'  => 'permit_empty|regex_match[/^\d{4}\-(0[1-9]|1[0-2])$/]',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Normalisasi periode default
        $periodeMonth = $this->request->getPost('periode_perolehan_default_month');
        $periodeDate  = $periodeMonth ? ($periodeMonth . '-01') : null;

        // Payload update
        $payload = [
            'nama_master'             => $this->request->getPost('nama_master'),
            'id_kategori'             => (int)$this->request->getPost('id_kategori'),
            'id_subkategori'          => (int)$this->request->getPost('id_subkategori'),
            'id_kelompok_harta'       => (int)$this->request->getPost('id_kelompok_harta'),
            'expired_default'         => $this->request->getPost('expired_default') ?: null,
            'nilai_perolehan_default' => $this->request->getPost('nilai_perolehan_default') ?: null,
            'periode_perolehan_default' => $periodeDate,
        ];

        // Cek perubahan klasifikasi & default
        $changedKlasifikasi =
            ((int)$row['id_kategori'] !== (int)$payload['id_kategori']) ||
            ((int)$row['id_subkategori'] !== (int)$payload['id_subkategori']);

        $changedDefaults =
            ($row['nilai_perolehan_default']   != $payload['nilai_perolehan_default']) ||
            ($row['expired_default']           != $payload['expired_default']) ||
            ($row['periode_perolehan_default'] != $payload['periode_perolehan_default']);

        $db = \Config\Database::connect();
        $db->transException(true)->transStart();

        try {
            // ---------------------------------------------------------------------
            // 1️⃣ UPDATE MASTER
            // ---------------------------------------------------------------------
            $this->masterModel->update((int)$id, $payload);

            // Generate kode_master jika masih kosong
            if (empty($row['kode_master'])) {
                $kode = $this->buildKodeMaster((int)$id, (int)$payload['id_kategori'], (int)$payload['id_subkategori']);
                $this->masterModel->update((int)$id, ['kode_master' => $kode]);
            }

            // ---------------------------------------------------------------------
            // 2️⃣ RESET DEFAULT ATRIBUT MASTER
            // ---------------------------------------------------------------------
            $tb = $db->table('master_aset_atribut');
            $tb->where('id_master_aset', (int)$id)->delete();

            $defaultsInput = $this->request->getPost('default_atribut') ?? [];
            if (is_array($defaultsInput)) {
                $rows = [];

                foreach ($defaultsInput as $idAttr => $nilai) {
                    $val = is_array($nilai) ? json_encode($nilai) : trim((string)$nilai);
                    if ($val === '') continue;

                    $rows[] = [
                        'id_master_aset' => (int)$id,
                        'id_atribut'     => (int)$idAttr,
                        'nilai_default'  => $val,
                    ];
                }

                if (!empty($rows)) {
                    $tb->insertBatch($rows);
                }
            }

            // Ambil ulang default untuk proses sinkron aset
            $defaultAttrs = $this->masterModel->getAtributDefaults((int)$id);

            // ---------------------------------------------------------------------
            // 3️⃣ SINKRON ATRIBUT KE SEMUA ASET (INSERT ONLY ATTRIBUT BARU)
            // ---------------------------------------------------------------------
            $asetList = $db->table('aset')
                ->select('id_aset')
                ->where('id_master_aset', (int)$id)
                ->where('deleted_at IS NULL', null, false)
                ->get()->getResultArray();

            foreach ($asetList as $asetRow) {
                $idAset = (int)$asetRow['id_aset'];

                // Ambil atribut yang sudah dimiliki aset
                $existing = $db->table('aset_atribut')
                    ->select('id_atribut')
                    ->where('id_aset', $idAset)
                    ->get()->getResultArray();

                $existingIDs = array_column($existing, 'id_atribut');

                $batch = [];

                foreach ($defaultAttrs as $d) {
                    $idAttr = (int)$d['id_atribut'];

                    // Jika atribut baru → tambahkan ke aset
                    if (!in_array($idAttr, $existingIDs)) {
                        $batch[] = [
                            'id_aset'    => $idAset,
                            'id_atribut' => $idAttr,
                            'nilai'      => $d['nilai_default'],
                        ];
                    }
                }

                if (!empty($batch)) {
                    $db->table('aset_atribut')->insertBatch($batch);
                }
            }

            // ---------------------------------------------------------------------
            // 4️⃣ SINKRON KLASIFIKASI & DEFAULT NILAI (kode lama kamu)
            // ---------------------------------------------------------------------
            $synced = 0;
            if ($changedKlasifikasi || $changedDefaults) {
                $db->table('aset')
                    ->set([
                        'id_kategori'       => (int)$payload['id_kategori'],
                        'id_subkategori'    => (int)$payload['id_subkategori'],
                        'nilai_perolehan'   => $payload['nilai_perolehan_default'],
                        'periode_perolehan' => $payload['periode_perolehan_default'],
                        'expired_at'        => $payload['expired_default'],
                    ])
                    ->where('id_master_aset', (int)$id)
                    ->update();

                $synced = $db->affectedRows();
            }

            $db->transComplete();

            $note = $synced > 0 ? " (Sinkron ke aset: {$synced} baris)" : '';
            return redirect()->to('/superadmin/master-aset')->with('success', 'Master aset diperbarui.' . $note);
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Master Aset: ' . $e->getMessage());
        }
    }


    public function detail($id)
    {
        $id = (int) $id;

        // Tampilkan juga bila master sedang diarsip (soft-deleted)
        $row = $this->masterModel
            ->withDeleted()
            ->withJoin()
            ->where('id_master_aset', $id)
            ->first();

        if (! $row) {
            return redirect()->to('/superadmin/master-aset')
                ->with('errors', 'Data master tidak ditemukan.');
        }

        // Atribut default master
        $atributDefaults = $this->masterModel->getAtributDefaults($id);

        // Opsional: berapa aset yang memakai master ini (berguna untuk informasi di detail)
        $db = \Config\Database::connect();
        $jumlahDipakai = (int) $db->table('aset')->where('id_master_aset', $id)->countAllResults();

        return view('superadmin/master_aset/detail', [
            'title'           => 'Detail Master Aset',
            'row'             => $row,
            'atributDefaults' => $atributDefaults,
            'jumlahDipakai'   => $jumlahDipakai,
        ]);
    }


    // === SOFT DELETE ===
    public function delete($id)
    {
        try {
            $this->masterModel->delete((int)$id); // soft delete -> isi deleted_at
            return redirect()->to('/superadmin/master-aset')->with('success', 'Master aset diarsipkan.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Tidak dapat mengarsipkan master: ' . $e->getMessage());
        }
    }

    // Kembalikan dari arsip
    public function restore($id)
    {
        $db = \Config\Database::connect();
        $ok = $db->table('master_aset')->set('deleted_at', null, false)
            ->where('id_master_aset', (int)$id)->update();

        if ($ok) {
            return redirect()->to('/superadmin/master-aset/trash')->with('success', 'Master aset dipulihkan.');
        }
        return redirect()->back()->with('error', 'Gagal memulihkan master.');
    }

    // Hapus permanen (hanya jika tidak dipakai aset)
    public function purge($id)
    {
        $db = \Config\Database::connect();
        $used = $db->table('aset')->where('id_master_aset', (int)$id)->countAllResults();
        if ($used > 0) {
            return redirect()->back()->with('error', "Tidak bisa hapus permanen: masih dipakai oleh {$used} aset.");
        }

        $db->transStart();
        // bersihkan atribut default
        $db->table('master_aset_atribut')->where('id_master_aset', (int)$id)->delete();
        // hard delete
        $this->masterModel->delete((int)$id, true); // purge = true
        $db->transComplete();

        return redirect()->to('/superadmin/master-aset/trash')->with('success', 'Master aset dihapus permanen.');
    }

    /**
     * Build kode master: KM-<KAT>-<SUB>-<IDPAD>
     */
    private function buildKodeMaster(int $idMaster, int $idKategori, int $idSub): string
    {
        $db  = \Config\Database::connect();

        $katRow     = $this->kategoriModel->select('kode_kategori')->find($idKategori);
        $katCodeRaw = $katRow['kode_kategori'] ?? ('KAT' . $idKategori);

        $subRow = $db->table('subkategori_aset')->select('slug,nama_subkategori')
            ->where('id_subkategori', $idSub)->get()->getRowArray();
        $subRaw = $subRow['slug'] ?? ($subRow['nama_subkategori'] ?? ('SUB' . $idSub));

        $norm = static function (string $s): string {
            $s = strtoupper($s);
            $s = preg_replace('/[^A-Z0-9]/', '', $s);
            return $s ?: 'NA';
        };

        $katCode = $norm($katCodeRaw);
        $subCode = substr($norm($subRaw), 0, 5);
        $seq     = str_pad((string)$idMaster, 5, '0', STR_PAD_LEFT);

        return "KM-{$katCode}-{$subCode}-{$seq}";
    }

    // Pintasan Tambah Master Cepat (AJAX)
    public function ajaxSubkategori($idKategori)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405);
        }

        $db = \Config\Database::connect();
        $rows = $db->table('subkategori_aset')
            ->select('id_subkategori, nama_subkategori')
            ->where('id_kategori', (int)$idKategori)
            ->orderBy('nama_subkategori', 'ASC')
            ->get()->getResultArray();

        return $this->response->setJSON(['ok' => true, 'items' => $rows]);
    }

    /** Quick create master dari modal kecil (AJAX) */
    public function quickStore()
    {
        if (!$this->request->isAJAX()) {
            return $this->response
                ->setStatusCode(405)
                ->setJSON(['ok' => false, 'message' => 'Method not allowed'])
                ->setHeader('X-CSRF-TOKEN', csrf_hash());
        }

        // Validasi minimal
        $rules = [
            'nama_master'                     => 'required',
            'id_kategori'                     => 'required|integer',
            'id_subkategori'                  => 'required|integer',
            'nilai_perolehan_default'         => 'permit_empty|decimal',
            'expired_default'                 => 'permit_empty|valid_date[Y-m-d]',
            'periode_perolehan_default_month' => 'permit_empty|regex_match[/^\d{4}\-(0[1-9]|1[0-2])$/]',
        ];

        if (!$this->validate($rules)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON([
                    'ok'      => false,
                    'message' => 'Validasi gagal',
                    'errors'  => $this->validator->getErrors()
                ])
                ->setHeader('X-CSRF-TOKEN', csrf_hash());
        }

        $periodeMonth = trim((string)$this->request->getPost('periode_perolehan_default_month'));
        $periodeDate  = $periodeMonth ? ($periodeMonth . '-01') : null;

        $payload = [
            'nama_master'               => trim((string)$this->request->getPost('nama_master')),
            'id_kategori'               => (int)$this->request->getPost('id_kategori'),
            'id_subkategori'            => (int)$this->request->getPost('id_subkategori'),
            'nilai_perolehan_default'   => $this->request->getPost('nilai_perolehan_default') ?: null,
            'periode_perolehan_default' => $periodeDate,
            'expired_default'           => $this->request->getPost('expired_default') ?: null,
        ];

        // FK guard
        if (!$this->kategoriModel->find($payload['id_kategori'])) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['ok' => false, 'message' => 'Kategori tidak valid'])
                ->setHeader('X-CSRF-TOKEN', csrf_hash());
        }

        $db = \Config\Database::connect();
        $subOk = $db->table('subkategori_aset')
            ->where('id_subkategori', $payload['id_subkategori'])
            ->countAllResults() > 0;

        if (!$subOk) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['ok' => false, 'message' => 'Subkategori tidak valid'])
                ->setHeader('X-CSRF-TOKEN', csrf_hash());
        }

        $db->transException(true)->transStart();

        try {
            $idMaster = $this->masterModel->insert($payload, true);
            if (!$idMaster) {
                throw new \RuntimeException(implode('; ', (array)$this->masterModel->errors()) ?: 'Insert master gagal');
            }

            $kode = $this->buildKodeMaster(
                (int)$idMaster,
                (int)$payload['id_kategori'],
                (int)$payload['id_subkategori']
            );

            $this->masterModel->update($idMaster, ['kode_master' => $kode]);

            $kat = $this->kategoriModel
                ->select('nama_kategori')
                ->find((int)$payload['id_kategori']);

            $sub = $db->table('subkategori_aset')
                ->select('nama_subkategori')
                ->where('id_subkategori', (int)$payload['id_subkategori'])
                ->get()->getRowArray();

            $db->transComplete();

            return $this->response
                ->setJSON([
                    'ok'               => true,
                    'id_master_aset'   => (int)$idMaster,
                    'nama_master'      => $payload['nama_master'],
                    'id_kategori'      => (int)$payload['id_kategori'],
                    'id_subkategori'   => (int)$payload['id_subkategori'],
                    'nama_kategori'    => $kat['nama_kategori'] ?? null,
                    'nama_subkategori' => $sub['nama_subkategori'] ?? null,
                    'nilai_default'    => $payload['nilai_perolehan_default'],
                    'periode_default'  => $payload['periode_perolehan_default'],
                    'expired_default'  => $payload['expired_default'],
                ])
                ->setHeader('X-CSRF-TOKEN', csrf_hash());
        } catch (\Throwable $e) {
            $db->transRollback();
            return $this->response
                ->setStatusCode(500)
                ->setJSON(['ok' => false, 'message' => 'Gagal membuat master: ' . $e->getMessage()])
                ->setHeader('X-CSRF-TOKEN', csrf_hash());
        }
    }
}
