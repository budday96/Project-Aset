<?php

namespace App\Controllers\Admin;

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
        $idCabang = (int) user()->id_cabang;

        $items = $this->mutasiModel
            ->select('mutasi_aset.*, c1.nama_cabang AS cabang_asal, c2.nama_cabang AS cabang_tujuan')
            ->join('cabang c1', 'c1.id_cabang = mutasi_aset.id_cabang_asal')
            ->join('cabang c2', 'c2.id_cabang = mutasi_aset.id_cabang_tujuan')
            ->groupStart()
            ->where('mutasi_aset.id_cabang_asal', $idCabang)
            ->orWhere('mutasi_aset.id_cabang_tujuan', $idCabang)
            ->groupEnd()
            ->orderBy('mutasi_aset.tanggal_mutasi', 'DESC')
            ->findAll();

        // Ambil nama cabang user login
        $cabangUser = $this->cabangModel
            ->where('id_cabang', $idCabang)
            ->get()
            ->getRow();

        return view('admin/mutasi/index', [
            'title' => 'Mutasi Aset Antar Cabang',
            'items' => $items,
            'cabangUser'  => $cabangUser
        ]);
    }

    public function create()
    {
        $idCabangAsal = (int) user()->id_cabang;

        $asets = $this->asetModel
            ->where('aset.deleted_at IS NULL', null, false)
            ->where('aset.id_cabang', $idCabangAsal)
            ->select('aset.*, master_aset.nama_master, kategori_aset.nama_kategori')
            ->join('master_aset', 'master_aset.id_master_aset = aset.id_master_aset', 'left')
            ->join('kategori_aset', 'kategori_aset.id_kategori = aset.id_kategori', 'left')
            ->orderBy('aset.kode_aset', 'ASC')
            ->findAll();

        return view('admin/mutasi/create', [
            'title'              => 'Mutasi Aset Antar Cabang',
            'cabangs'            => $this->cabangModel
                ->where('id_cabang !=', $idCabangAsal)
                ->orderBy('nama_cabang', 'ASC')
                ->findAll(),
            'asets'              => $asets,
            'selectedCabangAsal' => $idCabangAsal, // hidden / readonly di view
        ]);
    }


    /** -----------------------------------------------------------
     *  STORE = HANYA BUAT MUTASI. TIDAK MEMINDAHKAN ASET.
     * ----------------------------------------------------------- */
    public function store()
    {
        $idCabangAsal = (int) user()->id_cabang;

        $rules = [
            'id_cabang_tujuan' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $idCabangTujuan = (int) $this->request->getPost('id_cabang_tujuan');

        if ($idCabangAsal === $idCabangTujuan) {
            return redirect()->back()->withInput()->with('error', 'Cabang asal dan tujuan tidak boleh sama.');
        }

        $itemsRaw = $this->request->getPost('items');
        if (!is_array($itemsRaw) || empty($itemsRaw)) {
            return redirect()->back()->withInput()->with('error', 'Tidak ada aset yang dipilih.');
        }

        // Normalisasi + validasi aset milik cabang
        $items = [];
        foreach ($itemsRaw as $idAset => $row) {
            if (!empty($row['checked']) && (int)$row['qty'] > 0) {

                $aset = $this->asetModel
                    ->where('id_aset', (int)$idAset)
                    ->where('id_cabang', $idCabangAsal)
                    ->where('deleted_at IS NULL', null, false)
                    ->first();

                if (!$aset) {
                    return redirect()->back()->withInput()
                        ->with('error', 'Aset tidak valid atau bukan milik cabang Anda.');
                }

                if ((int)$row['qty'] > (int)$aset['stock']) {
                    return redirect()->back()->withInput()
                        ->with('error', 'Qty melebihi stok aset.');
                }

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
        $db->transStart();

        try {
            $idMutasi = $this->mutasiModel->insert([
                'kode_mutasi'       => '',
                'tanggal_mutasi'    => date('Y-m-d H:i:s'),
                'id_cabang_asal'    => $idCabangAsal,
                'id_cabang_tujuan'  => $idCabangTujuan,
                'status'            => 'pending',
                'created_by'        => user()->id,
                'updated_by'        => user()->id,
            ], true);

            $this->mutasiModel->update($idMutasi, [
                'kode_mutasi' => $this->generateKodeMutasi($idMutasi),
            ]);

            foreach ($items as $item) {
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

        return redirect()->to('/admin/mutasi')->with('success', 'Mutasi berhasil dibuat (pending).');
    }

    /** -----------------------------------------------------------
     *  KIRIM = ubah status pending → dikirim
     * ----------------------------------------------------------- */
    public function kirimHeader($id)
    {
        $mutasi = $this->mutasiModel->find($id);
        if ((int)$mutasi['id_cabang_asal'] !== (int)user()->id_cabang) {
            return redirect()->back()->with('error', 'Anda bukan cabang asal.');
        }

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
        if ((int)$mutasi['id_cabang_asal'] !== (int)user()->id_cabang) {
            return redirect()->back()->with('error', 'Anda bukan cabang asal.');
        }

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
        if ((int)user()->id_cabang !== (int)$mutasi['id_cabang_tujuan']) {
            return redirect()->back()->with('error', 'Anda bukan cabang tujuan.');
        }


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
            return redirect()->to('/admin/mutasi')->with('error', 'Data mutasi tidak ditemukan.');
        }

        if (
            (int)$header['id_cabang_asal'] !== (int)user()->id_cabang &&
            (int)$header['id_cabang_tujuan'] !== (int)user()->id_cabang
        ) {
            return redirect()->to('/admin/mutasi')->with('error', 'Akses ditolak.');
        }


        $details = $this->detailModel
            ->select('mutasi_aset_detail.*, aset.kode_aset, master_aset.nama_master AS nama_master')
            ->join('aset', 'aset.id_aset = mutasi_aset_detail.id_aset_asal')
            ->join('master_aset', 'master_aset.id_master_aset = aset.id_master_aset', 'left')
            ->where('id_mutasi', $id)
            ->findAll();

        return view('admin/mutasi/detail', [
            'title'   => 'Detail Mutasi Aset',
            'header'  => $header,
            'details' => $details,
        ]);
    }
}
