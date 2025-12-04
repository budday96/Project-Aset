<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Models\MutasiAsetModel;
use App\Models\MutasiAsetDetailModel;
use App\Models\AsetModel;
use App\Models\CabangModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class MutasiAset extends BaseController
{
    protected $mutasiModel;
    protected $detailModel;
    protected $asetModel;
    protected $cabangModel;

    public function __construct()
    {
        $this->mutasiModel  = new MutasiAsetModel();
        $this->detailModel  = new MutasiAsetDetailModel();
        $this->asetModel    = new AsetModel();
        $this->cabangModel  = new CabangModel();
    }

    /** Generate kode mutasi: MT-YYYYMMDD-0001 */
    protected function generateKodeMutasi(int $idMutasi): string
    {
        return sprintf("MT-%s-%04d", date('Ymd'), $idMutasi);
    }

    public function index()
    {
        $items = $this->mutasiModel
            ->select('mutasi_aset.*, c1.nama_cabang AS cabang_asal, c2.nama_cabang AS cabang_tujuan')
            ->join('cabang c1', 'c1.id_cabang = mutasi_aset.id_cabang_asal')
            ->join('cabang c2', 'c2.id_cabang = mutasi_aset.id_cabang_tujuan')
            ->orderBy('mutasi_aset.tanggal_mutasi', 'DESC')
            ->findAll();

        return view('superadmin/mutasi/index', [
            'title'   => 'Mutasi Aset Antar Cabang',
            'items'   => $items,
            'cabangs' => $this->cabangModel->orderBy('nama_cabang', 'ASC')->findAll(),
        ]);
    }



    public function create()
    {
        if (!in_groups('superadmin')) {
            throw new PageNotFoundException('Akses ditolak');
        }

        $idCabangAsal = (int) $this->request->getGet('cabang_asal');
        $asets = [];

        if ($idCabangAsal > 0) {
            $asets = $this->asetModel
                ->where('aset.deleted_at IS NULL', null, false)
                ->where('aset.id_cabang', $idCabangAsal)
                ->select('aset.*, master_aset.nama_master, kategori_aset.nama_kategori, cabang.nama_cabang')
                ->join('master_aset', 'master_aset.id_master_aset = aset.id_master_aset', 'left')
                ->join('kategori_aset', 'kategori_aset.id_kategori = aset.id_kategori', 'left')
                ->join('cabang', 'cabang.id_cabang = aset.id_cabang', 'left')
                ->orderBy('aset.kode_aset', 'ASC')
                ->findAll();
        }

        return view('superadmin/mutasi/create', [
            'title'              => 'Mutasi Aset Antar Cabang',
            'cabangs'            => $this->cabangModel->orderBy('nama_cabang', 'ASC')->findAll(),
            'asets'              => $asets,
            'selectedCabangAsal' => $idCabangAsal,
        ]);
    }

    /** -----------------------------------------------------------
     *  STORE = HANYA BUAT MUTASI. TIDAK MEMINDAHKAN ASET.
     * ----------------------------------------------------------- */
    public function store()
    {
        if (!in_groups('superadmin')) {
            throw new PageNotFoundException('Akses ditolak');
        }

        $rules = [
            'id_cabang_asal'   => 'required|integer',
            'id_cabang_tujuan' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $idCabangAsal   = (int) $this->request->getPost('id_cabang_asal');
        $idCabangTujuan = (int) $this->request->getPost('id_cabang_tujuan');
        // tanggal mutasi dibuat otomatis
        $tanggalMutasi = date('Y-m-d H:i:s');
        $catatan        = $this->request->getPost('catatan');

        if ($idCabangAsal === $idCabangTujuan) {
            return redirect()->back()->withInput()->with('error', 'Cabang asal & tujuan tidak boleh sama.');
        }

        $itemsRaw = $this->request->getPost('items');
        if (!is_array($itemsRaw) || empty($itemsRaw)) {
            return redirect()->back()->withInput()->with('error', 'Tidak ada aset yang dipilih.');
        }

        // Normalisasi item
        $items = [];
        foreach ($itemsRaw as $idAset => $row) {
            if (!empty($row['checked']) && $row['qty'] > 0) {
                $items[] = [
                    'id_aset' => (int)$idAset,
                    'qty'     => (int)$row['qty'],
                    'ket'     => $row['keterangan'] ?? null,
                ];
            }
        }

        if (empty($items)) {
            return redirect()->back()->withInput()->with('error', 'Minimal satu aset harus dipilih.');
        }

        $db = \Config\Database::connect();
        $db->transException(true)->transStart();

        try {
            // HEADER MUTASI
            $idMutasi = $this->mutasiModel->insert([
                'kode_mutasi'    => '',
                'tanggal_mutasi' => $tanggalMutasi,
                'id_cabang_asal' => $idCabangAsal,
                'id_cabang_tujuan' => $idCabangTujuan,
                'status'         => 'pending',
                'catatan'        => $catatan,
                'created_by'     => user()->id ?? null,
                'updated_by'     => user()->id ?? null,
            ], true);

            // KODE MUTASI
            $this->mutasiModel->update($idMutasi, [
                'kode_mutasi' => $this->generateKodeMutasi($idMutasi),
            ]);

            // DETAIL MUTASI SAJA
            foreach ($items as $item) {
                $aset = $this->asetModel
                    ->where('aset.deleted_at IS NULL', null, false)
                    ->where('id_aset', $item['id_aset'])
                    ->where('id_cabang', $idCabangAsal)
                    ->first();

                if (!$aset) {
                    throw new \RuntimeException("Aset tidak valid: {$item['id_aset']}");
                }

                if ($item['qty'] > (int)$aset['stock']) {
                    throw new \RuntimeException("Qty mutasi melebihi stok aset {$aset['kode_aset']}.");
                }

                $this->detailModel->insert([
                    'id_mutasi'    => $idMutasi,
                    'id_aset_asal' => $item['id_aset'],
                    'qty'          => $item['qty'],
                    'keterangan'   => $item['ket'],
                ]);
            }

            $db->transComplete();
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->to('/superadmin/mutasi')->with('success', 'Mutasi berhasil dibuat (pending).');
    }

    /** -----------------------------------------------------------
     *  KIRIM = ubah status pending → dikirim
     * ----------------------------------------------------------- */
    public function kirimHeader($id)
    {
        $mutasi = $this->mutasiModel->find($id);
        if (!$mutasi) return redirect()->back()->with('error', 'Mutasi tidak ditemukan.');

        if ($mutasi['status'] !== 'pending')
            return redirect()->back()->with('error', 'Mutasi bukan pending.');

        $this->mutasiModel->update($id, [
            'status'     => 'dikirim',
            'updated_by' => user()->id,
        ]);

        return redirect()->back()->with('success', 'Mutasi dikirim ke cabang tujuan.');
    }

    /** -----------------------------------------------------------
     *  BATAL
     * ----------------------------------------------------------- */
    public function batalHeader($id)
    {
        $mutasi = $this->mutasiModel->find($id);
        if (!$mutasi) return redirect()->back()->with('error', 'Mutasi tidak ditemukan.');

        if ($mutasi['status'] !== 'pending')
            return redirect()->back()->with('error', 'Hanya pending yang bisa dibatalkan.');

        $this->mutasiModel->update($id, [
            'status'     => 'dibatalkan',
            'updated_by' => user()->id,
        ]);

        return redirect()->back()->with('success', 'Mutasi berhasil dibatalkan.');
    }

    /** -----------------------------------------------------------
     *  TERIMA MUTASI = pindahkan aset di sini!
     * ----------------------------------------------------------- */
    public function terimaHeader($id)
    {
        $mutasi = $this->mutasiModel->find($id);
        if (!$mutasi) return redirect()->back()->with('error', 'Mutasi tidak ditemukan.');

        if ($mutasi['status'] !== 'dikirim')
            return redirect()->back()->with('error', 'Mutasi belum dikirim.');

        // hanya cabang tujuan yang boleh menerima
        if (user()->id_cabang != $mutasi['id_cabang_tujuan'] && !in_groups('superadmin'))
            return redirect()->back()->with('error', 'Anda bukan cabang tujuan.');

        $db = \Config\Database::connect();
        $db->transException(true)->transStart();

        try {
            $details = $this->detailModel->where('id_mutasi', $id)->findAll();

            foreach ($details as $d) {
                $asetAsal = $this->asetModel->withDeleted()->find($d['id_aset_asal']);
                $qty      = (int)$d['qty'];

                if (!$asetAsal) {
                    throw new \RuntimeException('Aset asal tidak ditemukan.');
                }

                // CARI ASET TUJUAN (master sama)
                $asetTujuan = $this->asetModel
                    ->withDeleted()
                    ->where('id_master_aset', $asetAsal['id_master_aset'])
                    ->where('id_cabang', $mutasi['id_cabang_tujuan'])
                    ->first();

                /** ------------------
                 * 1. Jika TUJUAN SUDAH PUNYA → tambah stok
                 * ------------------ */
                if ($asetTujuan) {

                    if (!empty($asetTujuan['deleted_at'])) {
                        $this->asetModel->update($asetTujuan['id_aset'], [
                            'deleted_at' => null,
                            'deleted_by' => null
                        ]);
                    }

                    // tambah stok tujuan
                    $this->asetModel->update($asetTujuan['id_aset'], [
                        'stock' => (int)$asetTujuan['stock'] + $qty
                    ]);

                    // kurangi stok asal
                    $newStock = (int)$asetAsal['stock'] - $qty;
                    $this->asetModel->update($asetAsal['id_aset'], ['stock' => $newStock]);

                    // stok habis → arsipkan
                    if ($newStock <= 0) {
                        $this->asetModel->update($asetAsal['id_aset'], [
                            'deleted_at' => date('Y-m-d H:i:s'),
                            'deleted_by' => user()->id,
                        ]);
                    }

                    continue;
                }

                /** ------------------
                 * 2. Jika mutasi penuh (qty == stok asal)
                 * ------------------ */
                if ($qty == $asetAsal['stock']) {

                    // pindah cabang
                    $this->asetModel->update($asetAsal['id_aset'], [
                        'id_cabang' => $mutasi['id_cabang_tujuan'],
                    ]);

                    continue;
                }

                /** ------------------
                 * 3. Mutasi sebagian (buat aset baru)
                 * ------------------ */
                $qrNew   = bin2hex(random_bytes(16));
                $kodeNew = $asetAsal['kode_aset'] . '-S' . $id;

                $newId = $this->asetModel->insert([
                    'kode_aset'      => $kodeNew,
                    'qr_token'       => $qrNew,
                    'id_master_aset' => $asetAsal['id_master_aset'],
                    'id_kategori'    => $asetAsal['id_kategori'],
                    'id_subkategori' => $asetAsal['id_subkategori'],
                    'id_cabang'      => $mutasi['id_cabang_tujuan'],
                    'stock'          => $qty,
                    'kondisi'        => $asetAsal['kondisi'],
                    'gambar'         => $asetAsal['gambar'],
                ], true);

                // copy atribut
                if ($db->tableExists('aset_atribut')) {
                    $rows = $db->table('aset_atribut')->where('id_aset', $asetAsal['id_aset'])->get()->getResultArray();
                    $batch = [];
                    foreach ($rows as $r) {
                        $batch[] = [
                            'id_aset'    => $newId,
                            'id_atribut' => $r['id_atribut'],
                            'nilai'      => $r['nilai']
                        ];
                    }
                    if ($batch) {
                        $db->table('aset_atribut')->insertBatch($batch);
                    }
                }

                // kurangi stok asal
                $this->asetModel->update($asetAsal['id_aset'], [
                    'stock' => (int)$asetAsal['stock'] - $qty
                ]);
            }

            // UPDATE STATUS
            $this->mutasiModel->update($id, [
                'status' => 'diterima'
            ]);

            $db->transComplete();
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Mutasi berhasil diterima.');
    }

    public function show($id)
    {
        $id = (int) $id;
        $header = $this->mutasiModel
            ->select('mutasi_aset.*, c1.nama_cabang AS cabang_asal, c2.nama_cabang AS cabang_tujuan')
            ->join('cabang c1', 'c1.id_cabang = mutasi_aset.id_cabang_asal')
            ->join('cabang c2', 'c2.id_cabang = mutasi_aset.id_cabang_tujuan')
            ->where('id_mutasi', $id)
            ->first();

        if (!$header) {
            return redirect()->to('/superadmin/mutasi')->with('error', 'Data mutasi tidak ditemukan.');
        }

        $details = $this->detailModel
            ->select('mutasi_aset_detail.*, aset.kode_aset, master_aset.nama_master AS nama_master')
            ->join('aset', 'aset.id_aset = mutasi_aset_detail.id_aset_asal')
            ->join('master_aset', 'master_aset.id_master_aset = aset.id_master_aset', 'left')
            ->where('id_mutasi', $id)
            ->findAll();

        return view('superadmin/mutasi/detail', [
            'title'   => 'Detail Mutasi Aset',
            'header'  => $header,
            'details' => $details,
        ]);
    }
}
