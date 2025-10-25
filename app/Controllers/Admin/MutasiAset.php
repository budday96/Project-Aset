<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MutasiAsetModel;
use App\Models\AsetModel;
use App\Models\CabangModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class MutasiAset extends BaseController
{
    protected $mutasiModel;
    protected $asetModel;
    protected $cabangModel;
    protected $db;

    public function __construct()
    {
        $this->mutasiModel = new MutasiAsetModel();
        $this->asetModel   = new AsetModel();
        $this->cabangModel = new CabangModel();
        $this->db          = db_connect();
        helper('auth'); // pastikan user_id() ada
    }

    /** List: pisahkan keluar/masuk agar fokus ke cabang user */
    public function index()
    {
        $idCabang = user()->id_cabang;

        $keluar = $this->mutasiModel
            ->select('mutasi_aset.*, aset.nama_aset, c1.nama_cabang AS cabang_asal, c2.nama_cabang AS cabang_tujuan')
            ->join('aset',    'aset.id_aset = mutasi_aset.id_aset')
            ->join('cabang c1', 'c1.id_cabang = mutasi_aset.dari_cabang')
            ->join('cabang c2', 'c2.id_cabang = mutasi_aset.ke_cabang')
            ->where('mutasi_aset.dari_cabang', $idCabang)
            ->orderBy('mutasi_aset.id_mutasi', 'DESC')
            ->findAll();

        $masuk = $this->mutasiModel
            ->select('mutasi_aset.*, aset.nama_aset, c1.nama_cabang AS cabang_asal, c2.nama_cabang AS cabang_tujuan')
            ->join('aset',    'aset.id_aset = mutasi_aset.id_aset')
            ->join('cabang c1', 'c1.id_cabang = mutasi_aset.dari_cabang')
            ->join('cabang c2', 'c2.id_cabang = mutasi_aset.ke_cabang')
            ->where('mutasi_aset.ke_cabang', $idCabang)
            ->orderBy('mutasi_aset.id_mutasi', 'DESC')
            ->findAll();

        return view('admin/mutasi/index', [
            'title'  => 'Mutasi Aset',
            'keluar' => $keluar,
            'masuk'  => $masuk,
        ]);
    }

    public function create()
    {
        $idCabang = user()->id_cabang;
        $data['title']   = 'Mutasi Aset Baru';
        $data['asets']   = $this->asetModel->where('id_cabang', $idCabang)->findAll();
        $data['cabangs'] = $this->cabangModel->where('id_cabang !=', $idCabang)->findAll();
        return view('admin/mutasi/create', $data);
    }

    /** Cek ada mutasi aktif (pending/dikirim) untuk aset tsb */
    private function hasActiveMutation(int $idAset): bool
    {
        return (bool) $this->mutasiModel
            ->where('id_aset', $idAset)
            ->whereIn('status', ['pending', 'dikirim'])
            ->first();
    }

    public function store()
    {
        $idCabangPengaju = (int) user()->id_cabang;
        $idAset  = (int) $this->request->getPost('id_aset');
        $keCabang = (int) $this->request->getPost('ke_cabang');
        $ket     = (string) $this->request->getPost('keterangan');

        // Validasi dasar
        if (!$idAset || !$keCabang) {
            return redirect()->back()->withInput()->with('error', 'Aset dan cabang tujuan wajib diisi.');
        }
        if ($keCabang === $idCabangPengaju) {
            return redirect()->back()->withInput()->with('error', 'Cabang tujuan tidak boleh sama dengan cabang asal.');
        }

        // Validasi aset milik cabang pengaju
        $aset = $this->asetModel->find($idAset);
        if (!$aset || (int)$aset['id_cabang'] !== $idCabangPengaju) {
            return redirect()->back()->withInput()->with('error', 'Aset tidak ditemukan atau bukan milik cabang Anda.');
        }

        // Validasi cabang tujuan eksis
        if (!$this->cabangModel->find($keCabang)) {
            return redirect()->back()->withInput()->with('error', 'Cabang tujuan tidak valid.');
        }

        // Cegah dobel mutasi aktif
        if ($this->hasActiveMutation($idAset)) {
            return redirect()->back()->withInput()->with('error', 'Aset ini sudah memiliki mutasi yang belum selesai.');
        }

        $this->mutasiModel->insert([
            'id_aset'       => $idAset,
            'dari_cabang'   => $idCabangPengaju,
            'ke_cabang'     => $keCabang,
            'keterangan'    => $ket,
            'status'        => 'pending',
            'dibuat_oleh'   => user_id(),
            'tanggal_mutasi' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/mutasi')->with('success', 'Mutasi berhasil diajukan.');
    }

    /** (Opsional) Step pengiriman oleh cabang asal */
    public function kirim(int $id)
    {
        $idCabang = (int) user()->id_cabang;

        // Hanya cabang asal & status pending yang bisa "kirim"
        $updated = $this->mutasiModel
            ->where([
                'id_mutasi'   => $id,
                'dari_cabang' => $idCabang,
                'status'      => 'pending',
            ])->set(['status' => 'dikirim'])
            ->update();

        if ($this->mutasiModel->db->affectedRows() < 1) {
            return redirect()->back()->with('error', 'Mutasi tidak dapat dikirim. Pastikan status masih pending dan milik cabang Anda.');
        }
        return redirect()->back()->with('success', 'Mutasi telah dikirim ke cabang tujuan.');
    }

    /** Terima mutasi oleh cabang tujuan — ATOMIK */
    public function terima(int $id)
    {
        $idCabang = (int) user()->id_cabang;

        $this->db->transBegin();
        try {
            // Lock-by-condition: pastikan masih pending/dikirim & milik cabang tujuan
            $ok = $this->mutasiModel
                ->where([
                    'id_mutasi' => $id,
                    'ke_cabang' => $idCabang,
                ])
                ->whereIn('status', ['pending', 'dikirim'])
                ->set([
                    'status'        => 'diterima',
                    'diterima_oleh' => user_id(),
                    'diterima_pada' => date('Y-m-d H:i:s'),
                ])->update();

            if ($this->mutasiModel->db->affectedRows() < 1) {
                $this->db->transRollback();
                return redirect()->back()->with('error', 'Mutasi tidak valid atau sudah diproses.');
            }

            // Ambil row mutasi untuk id_aset & ke_cabang
            $mutasi = $this->mutasiModel->find($id);

            // Pindahkan kepemilikan aset
            $this->asetModel->update($mutasi['id_aset'], ['id_cabang' => $mutasi['ke_cabang']]);

            $this->db->transCommit();
            return redirect()->back()->with('success', 'Mutasi aset telah diterima.');
        } catch (DatabaseException $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Gagal memproses mutasi: ' . $e->getMessage());
        }
    }

    /** (Opsional) Batalkan mutasi oleh cabang asal saat masih pending */
    public function batalkan(int $id)
    {
        $idCabang = (int) user()->id_cabang;

        $this->mutasiModel
            ->where([
                'id_mutasi'   => $id,
                'dari_cabang' => $idCabang,
                'status'      => 'pending',
            ])->set(['status' => 'dibatalkan'])
            ->update();

        if ($this->mutasiModel->db->affectedRows() < 1) {
            return redirect()->back()->with('error', 'Mutasi tidak dapat dibatalkan (bukan milik cabang Anda atau sudah diproses).');
        }
        return redirect()->back()->with('success', 'Mutasi dibatalkan.');
    }
}
