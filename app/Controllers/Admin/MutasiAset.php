<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MutasiAsetModel;
use App\Models\MutasiAsetDetailModel;
use App\Models\AsetModel;
use App\Models\CabangModel;
use App\Models\NotifikasiModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use App\Services\NotificationService;

class MutasiAset extends BaseController
{
    protected $mutasiModel;
    protected $detailModel;
    protected $asetModel;
    protected $cabangModel;
    protected $notifModel;
    protected $notificationService;

    public function __construct()
    {
        $this->mutasiModel  = new MutasiAsetModel();
        $this->detailModel  = new MutasiAsetDetailModel();
        $this->asetModel    = new AsetModel();
        $this->cabangModel  = new CabangModel();
        $this->notifModel   = new NotifikasiModel();
        $this->notificationService = new NotificationService();
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

        $catatan        = $this->request->getPost('catatan');
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
                'catatan'        => $catatan,
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

    public function edit($id)
    {
        $id = (int)$id;

        $header = $this->mutasiModel->find($id);
        if (!$header) {
            return redirect()->to('/admin/mutasi')->with('error', 'Data mutasi tidak ditemukan.');
        }

        // Hanya cabang asal & status pending yang boleh edit
        if (
            $header['status'] !== 'pending' ||
            (int)$header['id_cabang_asal'] !== (int)user()->id_cabang
        ) {
            return redirect()->to('/admin/mutasi')
                ->with('error', 'Mutasi tidak dapat diedit.');
        }

        // Detail mutasi
        $details = $this->detailModel
            ->where('id_mutasi', $id)
            ->findAll();

        // Aset cabang asal
        $asets = $this->asetModel
            ->where('aset.deleted_at IS NULL', null, false)
            ->where('aset.id_cabang', user()->id_cabang)
            ->select('aset.*, master_aset.nama_master, kategori_aset.nama_kategori')
            ->join('master_aset', 'master_aset.id_master_aset = aset.id_master_aset', 'left')
            ->join('kategori_aset', 'kategori_aset.id_kategori = aset.id_kategori', 'left')
            ->orderBy('aset.kode_aset', 'ASC')
            ->findAll();

        return view('admin/mutasi/create', [
            'title'   => 'Edit Mutasi Aset',
            'cabangs' => $this->cabangModel
                ->where('id_cabang !=', user()->id_cabang)
                ->orderBy('nama_cabang', 'ASC')
                ->findAll(),
            'asets'   => $asets,
            'header'  => $header,
            'details' => $details,
            'mode'    => 'edit'
        ]);
    }

    public function update($id)
    {
        $id = (int)$id;

        $header = $this->mutasiModel->find($id);
        if (!$header) {
            return redirect()->to('/admin/mutasi')->with('error', 'Data mutasi tidak ditemukan.');
        }

        // Validasi hak edit
        if (
            $header['status'] !== 'pending' ||
            (int)$header['id_cabang_asal'] !== (int)user()->id_cabang
        ) {
            return redirect()->to('/admin/mutasi')
                ->with('error', 'Mutasi tidak dapat diubah.');
        }

        $rules = [
            'id_cabang_tujuan' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $idCabangAsal   = (int) user()->id_cabang;
        $idCabangTujuan = (int) $this->request->getPost('id_cabang_tujuan');
        $catatan        = $this->request->getPost('catatan');

        if ($idCabangAsal === $idCabangTujuan) {
            return redirect()->back()->withInput()
                ->with('error', 'Cabang asal dan tujuan tidak boleh sama.');
        }

        $itemsRaw = $this->request->getPost('items');
        if (!is_array($itemsRaw) || empty($itemsRaw)) {
            return redirect()->back()->withInput()
                ->with('error', 'Tidak ada aset yang dipilih.');
        }

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
                        ->with('error', 'Aset tidak valid.');
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
            return redirect()->back()->withInput()
                ->with('error', 'Minimal satu aset harus dipilih.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {

            // Update header
            $this->mutasiModel->update($id, [
                'id_cabang_tujuan' => $idCabangTujuan,
                'catatan'          => $catatan,
                'updated_by'       => user()->id,
            ]);

            // Hapus detail lama
            $this->detailModel->where('id_mutasi', $id)->delete();

            // Insert detail baru
            foreach ($items as $item) {
                $this->detailModel->insert([
                    'id_mutasi'    => $id,
                    'id_aset_asal' => $item['id_aset'],
                    'qty'          => $item['qty'],
                    'keterangan'   => $item['ket'],
                ]);
            }

            $db->transComplete();
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->withInput()
                ->with('error', $e->getMessage());
        }

        return redirect()->to('/admin/mutasi')
            ->with('success', 'Mutasi berhasil diperbarui (pending).');
    }



    /** -----------------------------------------------------------
     *  KIRIM = ubah status pending → dikirim
     * ----------------------------------------------------------- */
    public function kirimHeader($id)
    {
        $mutasi = $this->mutasiModel->find($id);

        if (!$mutasi) {
            return redirect()->back()->with('error', 'Mutasi tidak ditemukan.');
        }

        if ((int)$mutasi['id_cabang_asal'] !== (int)user()->id_cabang) {
            return redirect()->back()->with('error', 'Anda bukan cabang asal.');
        }

        if ($mutasi['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Mutasi bukan pending.');
        }

        // ===============================
        // DATA PENGIRIMAN (dari dialog / form)
        // ===============================
        $metode    = $this->request->getPost('metode_pengiriman');
        $pengantar = $this->request->getPost('nama_pengantar');
        $kendaraan = $this->request->getPost('nomor_kendaraan');

        if (!$metode || !$pengantar) {
            return redirect()->back()->with('error', 'Data pengiriman belum lengkap.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {

            // Generate nomor surat jalan jika belum ada
            $nomorSJ = $mutasi['nomor_surat_jalan']
                ?: $this->generateNomorSuratJalan($mutasi['id_cabang_asal']);

            // Update header mutasi
            $this->mutasiModel->update($id, [
                'status'            => 'dikirim',
                'nomor_surat_jalan' => $nomorSJ,
                'metode_pengiriman' => $metode,
                'nama_pengantar'    => $pengantar,
                'nomor_kendaraan'   => $kendaraan,
                'updated_by'        => user()->id,
            ]);

            // ===========================
            // KIRIM NOTIFIKASI KE CABANG TUJUAN
            // ===========================

            $usersTujuan = $db->table('users')
                ->where('id_cabang', $mutasi['id_cabang_tujuan'])
                ->get()
                ->getResultArray();

            foreach ($usersTujuan as $u) {
                $this->notificationService->send(
                    $u['id'],
                    'mutasi',
                    'Mutasi Aset Masuk',
                    'Mutasi ' . $mutasi['kode_mutasi'] . ' dari cabang ' . get_nama_cabang(user()->id_cabang) . ' telah dikirim.',
                    'admin/mutasi/' . $id,
                    $id
                );
            }

            $db->transComplete();
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Mutasi dikirim & notifikasi berhasil dikirim.');
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

        // ===============================
        // MARK NOTIFIKASI SEBAGAI SUDAH DIBACA
        // ===============================
        $notifModel = new NotifikasiModel();

        $notifModel
            ->where('id_user', user()->id)
            ->where('url', 'admin/mutasi/' . $id)
            ->set(['is_read' => 1])
            ->update();

        // ===============================

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

    protected function generateNomorSuratJalan(int $idCabang): string
    {
        $db = \Config\Database::connect();

        $bulan = date('Ym');

        // ambil kode cabang
        $cabang = $this->cabangModel
            ->select('kode_cabang')
            ->where('id_cabang', $idCabang)
            ->get()
            ->getRow();

        $kodeCabang = $cabang->kode_cabang ?? 'CBG';

        // hitung SJ bulan ini per cabang
        $count = $db->table('mutasi_aset')
            ->where('id_cabang_asal', $idCabang)
            ->where('DATE_FORMAT(tanggal_mutasi,"%Y%m")', $bulan, false)
            ->where('nomor_surat_jalan IS NOT NULL', null, false)
            ->countAllResults();

        $urut = str_pad($count + 1, 4, '0', STR_PAD_LEFT);

        return "SJ-{$kodeCabang}-{$bulan}-{$urut}";
    }


    public function suratJalan($id)
    {
        $id = (int)$id;

        $header = $this->mutasiModel
            ->select('mutasi_aset.*, c1.nama_cabang AS cabang_asal, c2.nama_cabang AS cabang_tujuan')
            ->join('cabang c1', 'c1.id_cabang = mutasi_aset.id_cabang_asal')
            ->join('cabang c2', 'c2.id_cabang = mutasi_aset.id_cabang_tujuan')
            ->where('id_mutasi', $id)
            ->first();

        if (!$header) {
            throw new PageNotFoundException('Data mutasi tidak ditemukan');
        }

        // akses hanya asal / tujuan
        if (
            (int)$header['id_cabang_asal'] !== (int)user()->id_cabang &&
            (int)$header['id_cabang_tujuan'] !== (int)user()->id_cabang
        ) {
            throw new PageNotFoundException('Akses ditolak');
        }

        $details = $this->detailModel
            ->select('mutasi_aset_detail.*, aset.kode_aset, master_aset.nama_master')
            ->join('aset', 'aset.id_aset = mutasi_aset_detail.id_aset_asal')
            ->join('master_aset', 'master_aset.id_master_aset = aset.id_master_aset')
            ->where('id_mutasi', $id)
            ->findAll();

        return view('admin/mutasi/surat_jalan', [
            'header'  => $header,
            'details' => $details,
        ]);
    }
}
