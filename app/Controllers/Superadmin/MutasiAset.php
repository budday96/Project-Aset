<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Models\MutasiAsetModel;
use App\Models\AsetModel;
use App\Models\CabangModel;
use App\Models\KategoriAsetModel;
use App\Models\MutasiAsetLogModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class MutasiAset extends BaseController
{
    protected $db;
    protected $mutasiModel;
    protected $asetModel;
    protected $cabangModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->db           = db_connect();
        // Pastikan SEMUA model memakai koneksi yang sama selama transaksi
        $this->mutasiModel  = model(MutasiAsetModel::class, true, $this->db);
        $this->asetModel    = model(AsetModel::class, true, $this->db);
        $this->cabangModel  = model(CabangModel::class, true, $this->db);
        $this->kategoriModel = model(KategoriAsetModel::class, true, $this->db);

        helper('auth', 'mutasi_log'); // user(), user_id(), in_groups() dan logMutasiAset()
    }

    /** Utility: QR token unik (cek ke tabel aset). */
    private function makeUniqueToken(): string
    {
        do {
            $token  = bin2hex(random_bytes(16));
            $exists = $this->asetModel->where('qr_token', $token)->countAllResults();
        } while ($exists > 0);
        return $token;
    }

    /** Utility: nomor urut per (id_kategori, tahun) secara atomic untuk generator kode aset. */
    private function nextUrutPerKategoriTahun(int $idKategori, int $tahun): int
    {
        $this->db->transException(true)->transStart();
        // init baris jika belum ada
        $this->db->query(
            'INSERT INTO aset_counter (id_kategori, tahun, last_no)
             VALUES (?, ?, 0)
             ON DUPLICATE KEY UPDATE last_no = last_no',
            [$idKategori, $tahun]
        );
        // atomic increment
        $this->db->query(
            'UPDATE aset_counter
             SET last_no = LAST_INSERT_ID(last_no + 1)
             WHERE id_kategori = ? AND tahun = ?',
            [$idKategori, $tahun]
        );
        $row = $this->db->query('SELECT LAST_INSERT_ID() AS urut')->getRowArray();
        $this->db->transComplete();
        return (int)($row['urut'] ?? 1);
    }

    /** Utility: generator kode aset (format: <KODE_KATEGORI>-<TAHUN>-<NNN>) */
    private function generateKodeAsetByMaster(int $idKategori, ?string $periode /* yyyy-mm-01 */): string
    {
        $kategori     = $this->kategoriModel->find($idKategori);
        $kodeKategori = $kategori['kode_kategori'] ?? ('KAT' . $idKategori);
        $tahun        = (int) date('Y', strtotime($periode ?: 'now'));
        $urut         = $this->nextUrutPerKategoriTahun($idKategori, $tahun);
        return sprintf('%s-%d-%03d', $kodeKategori, $tahun, $urut);
    }

    /** Qty mutasi aktif (pending/dikirim) → untuk mencegah overbooking. */
    private function activeOutgoingQty(int $idAset): int
    {
        $row = $this->mutasiModel
            ->select('COALESCE(SUM(qty),0) AS qty_out')
            ->where('id_aset', $idAset)
            ->whereIn('status', ['pending', 'dikirim'])
            ->first();
        return (int)($row['qty_out'] ?? 0);
    }

    /** List mutasi (keluar & masuk). */
    public function index()
    {
        $baseSelect =
            'mutasi_aset.*,
             aset.kode_aset, aset.stock,
             master_aset.nama_master,
             c1.nama_cabang AS cabang_asal,
             c2.nama_cabang AS cabang_tujuan';

        if (in_groups('superadmin')) {
            $keluar = $this->mutasiModel
                ->select($baseSelect)
                ->join('aset', 'aset.id_aset = mutasi_aset.id_aset')
                ->join('master_aset', 'master_aset.id_master_aset = aset.id_master_aset', 'left')
                ->join('cabang c1', 'c1.id_cabang = mutasi_aset.dari_cabang')
                ->join('cabang c2', 'c2.id_cabang = mutasi_aset.ke_cabang')
                ->orderBy('mutasi_aset.id_mutasi', 'DESC')
                ->findAll();
            $masuk = $keluar;
        } else {
            $idCabang = (int) user()->id_cabang;

            $keluar = $this->mutasiModel
                ->select($baseSelect)
                ->join('aset', 'aset.id_aset = mutasi_aset.id_aset')
                ->join('master_aset', 'master_aset.id_master_aset = aset.id_master_aset', 'left')
                ->join('cabang c1', 'c1.id_cabang = mutasi_aset.dari_cabang')
                ->join('cabang c2', 'c2.id_cabang = mutasi_aset.ke_cabang')
                ->where('mutasi_aset.dari_cabang', $idCabang)
                ->orderBy('mutasi_aset.id_mutasi', 'DESC')
                ->findAll();

            $masuk = $this->mutasiModel
                ->select($baseSelect)
                ->join('aset', 'aset.id_aset = mutasi_aset.id_aset')
                ->join('master_aset', 'master_aset.id_master_aset = aset.id_master_aset', 'left')
                ->join('cabang c1', 'c1.id_cabang = mutasi_aset.dari_cabang')
                ->join('cabang c2', 'c2.id_cabang = mutasi_aset.ke_cabang')
                ->where('mutasi_aset.ke_cabang', $idCabang)
                ->orderBy('mutasi_aset.id_mutasi', 'DESC')
                ->findAll();
        }

        return view('superadmin/mutasi/index', [
            'title'  => 'Mutasi Aset',
            'keluar' => $keluar,
            'masuk'  => $masuk,
        ]);
    }

    /** Form create mutasi (qty). */
    public function create()
    {
        $data['title'] = 'Mutasi Aset Baru';

        if (in_groups('superadmin')) {
            $data['cabangs'] = $this->cabangModel->findAll();
            $data['asets']   = []; // diisi via AJAX setelah pilih cabang asal
        } else {
            $idCabang        = (int) user()->id_cabang;
            $data['cabangs'] = $this->cabangModel->where('id_cabang !=', $idCabang)->findAll();
            $data['asets']   = $this->asetModel
                ->select('aset.*, master_aset.nama_master')
                ->join('master_aset', 'master_aset.id_master_aset = aset.id_master_aset', 'left')
                ->where('aset.id_cabang', $idCabang)
                ->where('aset.stock >', 0)
                ->orderBy('master_aset.nama_master', 'ASC')
                ->findAll();
        }

        return view('superadmin/mutasi/create', $data);
    }

    /** AJAX: aset by cabang (untuk superadmin pilih asal dulu). */
    public function assetsByCabang($id_cabang)
    {
        if (! in_groups('superadmin')) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Forbidden']);
        }

        $id = (int) $id_cabang;
        $asets = $this->asetModel
            ->select('aset.id_aset, aset.kode_aset, aset.stock, master_aset.nama_master')
            ->join('master_aset', 'master_aset.id_master_aset = aset.id_master_aset', 'left')
            ->where('aset.id_cabang', $id)
            ->where('aset.stock >', 0)
            ->orderBy('master_aset.nama_master', 'ASC')
            ->findAll();

        return $this->response->setJSON($asets);
    }

    /** Simpan pengajuan mutasi. */
    public function store()
    {
        if (in_groups('superadmin')) {
            $dariCabang = (int) $this->request->getPost('dari_cabang');
            $keCabang   = (int) $this->request->getPost('ke_cabang');
            $idAset     = (int) $this->request->getPost('id_aset');
        } else {
            $dariCabang = (int) user()->id_cabang;
            $keCabang   = (int) $this->request->getPost('ke_cabang');
            $idAset     = (int) $this->request->getPost('id_aset');
        }

        $qty = (int) $this->request->getPost('qty');
        $ket = (string) ($this->request->getPost('keterangan') ?? '');

        if (! $idAset || ! $dariCabang || ! $keCabang) {
            return redirect()->back()->withInput()->with('error', 'Aset, cabang asal, dan cabang tujuan wajib diisi.');
        }
        if ($keCabang === $dariCabang) {
            return redirect()->back()->withInput()->with('error', 'Cabang tujuan tidak boleh sama dengan cabang asal.');
        }
        if ($qty < 1) {
            return redirect()->back()->withInput()->with('error', 'Qty mutasi minimal 1.');
        }

        // Validasi cabang
        if (! $this->cabangModel->find($dariCabang)) {
            return redirect()->back()->withInput()->with('error', 'Cabang asal tidak valid.');
        }
        if (! $this->cabangModel->find($keCabang)) {
            return redirect()->back()->withInput()->with('error', 'Cabang tujuan tidak valid.');
        }

        // Validasi kepemilikan & stok
        $aset = $this->asetModel->find($idAset);
        if (! $aset || (int) $aset['id_cabang'] !== $dariCabang) {
            return redirect()->back()->withInput()->with('error', 'Aset tidak ditemukan atau bukan milik cabang asal.');
        }

        $stock     = (int) ($aset['stock'] ?? 0);
        $reserved  = $this->activeOutgoingQty($idAset);
        $available = max(0, $stock - $reserved);

        if ($qty > $available) {
            return redirect()->back()->withInput()->with(
                'error',
                "Qty melebihi stok tersedia. Stok: {$stock}, sedang diajukan/dikirim: {$reserved}, sisa: {$available}."
            );
        }

        // Simpan mutasi
        $ok = $this->mutasiModel->insert([
            'id_aset'        => $idAset,
            'dari_cabang'    => $dariCabang,
            'ke_cabang'      => $keCabang,
            'qty'            => $qty,
            'keterangan'     => $ket,
            'status'         => 'pending',
            'dibuat_oleh'    => user_id(),
            'tanggal_mutasi' => date('Y-m-d H:i:s'),
        ], true); // return id

        if (! $ok) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan pengajuan mutasi.');
        }

        $idMutasiBaru = $ok; // karena pakai insert(..., true)

        // Tulis LOG: create
        log_mutasi_create($idMutasiBaru, [
            'qty'            => $qty,
            'id_aset_sumber' => $idAset,
            'dari_cabang'    => $dariCabang,
            'ke_cabang'      => $keCabang,
            'message'        => $ket ?: 'Pengajuan mutasi dibuat',
        ]);

        return redirect()->to('/superadmin/mutasi')->with('success', 'Mutasi berhasil diajukan.');
    }

    /** Ubah status → dikirim. */
    public function kirim(int $id)
    {
        if (in_groups('superadmin')) {
            $this->mutasiModel
                ->where(['id_mutasi' => $id, 'status' => 'pending'])
                ->set(['status' => 'dikirim'])
                ->update();
        } else {
            $idCabang = (int) user()->id_cabang;
            $this->mutasiModel
                ->where([
                    'id_mutasi'   => $id,
                    'dari_cabang' => $idCabang,
                    'status'      => 'pending',
                ])->set(['status' => 'dikirim'])
                ->update();
        }

        if ($this->db->affectedRows() < 1) {
            return redirect()->back()->with('error', 'Mutasi tidak dapat dikirim. Pastikan status masih pending.');
        }

        // Ambil snapshot untuk log
        $m = $this->mutasiModel->find($id);
        if ($m) {
            log_mutasi_send($id, [
                'qty'            => (int) $m['qty'],
                'id_aset_sumber' => (int) $m['id_aset'],
                'dari_cabang'    => (int) $m['dari_cabang'],
                'ke_cabang'      => (int) $m['ke_cabang'],
                'message'        => 'Mutasi dikirim',
            ]);
        }

        return redirect()->back()->with('success', 'Mutasi telah dikirim ke cabang tujuan.');
    }

    /**
     * Terima mutasi (atomic, satu koneksi):
     * - Kurangi stok di cabang asal.
     * - Tambah stok di cabang tujuan (restore jika baris tujuan ter-arsip).
     * - Jika baris tujuan belum ada → buat baris baru dengan kode_aset BARU (hindari bentrok UNIQUE).
     */
    public function terima(int $id)
    {
        $this->db->transBegin();
        try {
            // 1) Ubah status -> diterima (role-aware)
            if (in_groups('superadmin')) {
                $this->mutasiModel
                    ->where('id_mutasi', $id)
                    ->whereIn('status', ['pending', 'dikirim'])
                    ->set([
                        'status'        => 'diterima',
                        'diterima_oleh' => user_id(),
                        'diterima_pada' => date('Y-m-d H:i:s'),
                    ])->update();
            } else {
                $idCabang = (int) user()->id_cabang;
                $this->mutasiModel
                    ->where(['id_mutasi' => $id, 'ke_cabang' => $idCabang])
                    ->whereIn('status', ['pending', 'dikirim'])
                    ->set([
                        'status'        => 'diterima',
                        'diterima_oleh' => user_id(),
                        'diterima_pada' => date('Y-m-d H:i:s'),
                    ])->update();
            }

            if ($this->db->affectedRows() < 1) {
                throw new \RuntimeException('Mutasi tidak valid atau sudah diproses.');
            }

            // 2) Ambil mutasi & aset sumber
            $mutasi = $this->mutasiModel->find($id);
            $qty    = (int) ($mutasi['qty'] ?? 0);
            if ($qty < 1) {
                throw new \RuntimeException('Qty mutasi tidak valid.');
            }

            $aset = $this->asetModel->find((int) $mutasi['id_aset']);
            if (! $aset) {
                throw new \RuntimeException('Aset sumber tidak ditemukan.');
            }

            // 3) Guard stok asal saat ini
            $stockAsal = (int) $aset['stock'];
            if ($stockAsal < $qty) {
                throw new \RuntimeException('Stok asal tidak mencukupi saat konfirmasi.');
            }

            // Kurangi/soft delete di A (sesuai patch sebelumnya)
            $stokBaruA = $stockAsal - $qty;
            if ($stokBaruA > 0) {
                if (! $this->asetModel->update((int)$aset['id_aset'], ['stock' => $stokBaruA, 'updated_by' => user_id()])) {
                    throw new \RuntimeException('Gagal mengurangi stok asal.');
                }
            } else {
                $this->asetModel->update((int)$aset['id_aset'], ['stock' => 0, 'updated_by' => user_id()]);
                $this->asetModel->delete((int)$aset['id_aset']); // soft delete
            }


            // 5) Tambah stok ke cabang tujuan
            // Cari baris tujuan (master sama + cabang tujuan), termasuk yang soft-deleted
            // Tambah ke B (restore jika perlu / buat baru)
            $asetTujuan = $this->asetModel
                ->withDeleted()
                ->where([
                    'id_master_aset' => (int) $aset['id_master_aset'],
                    'id_cabang'      => (int) $mutasi['ke_cabang'],
                ])->first();

            $idAsetTujuan = null;

            if ($asetTujuan) {
                if (! empty($asetTujuan['deleted_at'])) {
                    $this->asetModel->update((int)$asetTujuan['id_aset'], ['deleted_at' => null, 'deleted_by' => null]);
                }
                if (! $this->asetModel->update((int)$asetTujuan['id_aset'], [
                    'stock'      => (int) $asetTujuan['stock'] + $qty,
                    'updated_by' => user_id(),
                ])) {
                    throw new \RuntimeException('Gagal menambah stok tujuan.');
                }
                $idAsetTujuan = (int) $asetTujuan['id_aset'];
            } else {
                // Buat baris baru dengan kode_aset BARU (hindari unique clash)
                $periodeBasis = $aset['periode_perolehan'] ?: date('Y-m-01');
                $kodeBaru     = $this->generateKodeAsetByMaster((int) $aset['id_kategori'], $periodeBasis);

                $idAsetTujuan = $this->asetModel->insert([
                    'kode_aset'         => $kodeBaru,
                    'qr_token'          => $this->makeUniqueToken(),
                    'id_master_aset'    => (int) $aset['id_master_aset'],
                    'id_kategori'       => (int) $aset['id_kategori'],
                    'id_subkategori'    => (int) $aset['id_subkategori'],
                    'id_cabang'         => (int) $mutasi['ke_cabang'],
                    'nilai_perolehan'   => $aset['nilai_perolehan'],
                    'periode_perolehan' => $aset['periode_perolehan'],
                    'expired_at'        => $aset['expired_at'],
                    'kondisi'           => $aset['kondisi'],
                    'status'            => $aset['status'],
                    'posisi'            => null,
                    'gambar'            => $aset['gambar'],
                    'keterangan'        => $aset['keterangan'],
                    'stock'             => $qty,
                    'created_by'        => user_id(),
                    'updated_by'        => user_id(),
                ], true);

                if (! $idAsetTujuan) {
                    throw new \RuntimeException('Gagal insert aset tujuan: ' . implode('; ', (array)$this->asetModel->errors()));
                }
            }

            // === TULIS LOG (di dalam transaksi)
            log_mutasi_receive($id, [
                'qty'             => $qty,
                'id_aset_sumber'  => (int) $aset['id_aset'],
                'id_aset_tujuan'  => $idAsetTujuan,
                'dari_cabang'     => (int) $mutasi['dari_cabang'],
                'ke_cabang'       => (int) $mutasi['ke_cabang'],
                'message'         => 'Mutasi diterima di cabang tujuan',
                // status_from fleksibel; biarkan default 'dikirim' dari helper atau set sendiri:
                // 'status_from'   => 'dikirim',
            ]);

            $this->db->transCommit();
            return redirect()->back()->with('success', 'Mutasi aset telah diterima.');
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Gagal memproses mutasi: ' . $e->getMessage());
        }
    }

    /** Batalkan mutasi (hanya pending). Superadmin boleh override. */
    public function batalkan(int $id)
    {
        if (in_groups('superadmin')) {
            $this->mutasiModel
                ->where(['id_mutasi' => $id, 'status' => 'pending'])
                ->set(['status' => 'dibatalkan'])
                ->update();
        } else {
            $idCabang = (int) user()->id_cabang;
            $this->mutasiModel
                ->where([
                    'id_mutasi'   => $id,
                    'dari_cabang' => $idCabang,
                    'status'      => 'pending',
                ])->set(['status' => 'dibatalkan'])
                ->update();
        }

        if ($this->db->affectedRows() < 1) {
            return redirect()->back()->with('error', 'Mutasi tidak dapat dibatalkan (bukan pending atau tidak sesuai akses).');
        }

        $m = $this->mutasiModel->find($id);
        if ($m) {
            log_mutasi_cancel($id, [
                'qty'            => (int) $m['qty'],
                'id_aset_sumber' => (int) $m['id_aset'],
                'dari_cabang'    => (int) $m['dari_cabang'],
                'ke_cabang'      => (int) $m['ke_cabang'],
                'message'        => 'Mutasi dibatalkan',
            ]);
        }

        return redirect()->back()->with('success', 'Mutasi dibatalkan.');
    }
}
