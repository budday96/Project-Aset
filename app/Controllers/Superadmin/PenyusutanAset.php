<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Models\AsetModel;
use App\Models\PenyusutanAsetModel;

class PenyusutanAset extends BaseController
{
    protected $asetModel;
    protected $penyModel;

    public function __construct()
    {
        $this->asetModel = new AsetModel();
        $this->penyModel = new PenyusutanAsetModel();
    }

    /**
     * Generate penyusutan bulanan.
     */
    public function generateBulanan()
    {
        $bulan = (int) date('m');
        $tahun = (int) date('Y');

        // Cek apakah bulan ini sudah digenerate
        $exists = $this->penyModel
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->first();

        if ($exists) {
            return redirect()->back()
                ->with('error', "Penyusutan bulan $bulan-$tahun sudah digenerate.");
        }

        $db = \Config\Database::connect();

        // Ambil seluruh aset + master + kelompok harta
        $asets = $db->table('aset')
            ->select("
            aset.id_aset, aset.nilai_perolehan, aset.periode_perolehan,
            aset.id_master_aset, aset.id_kategori, aset.id_subkategori,
            master_aset.nama_master, master_aset.id_kelompok_harta,
            kelompok_harta.umur_tahun, kelompok_harta.umur_bulan
        ")
            ->join('master_aset', 'master_aset.id_master_aset = aset.id_master_aset')
            ->join('kelompok_harta', 'kelompok_harta.id_kelompok_harta = master_aset.id_kelompok_harta')
            ->where('aset.deleted_at IS NULL', null, false)
            ->get()->getResultArray();

        if (!$asets) {
            return redirect()->back()->with('error', 'Tidak ada aset untuk dihitung.');
        }

        $batch = [];

        foreach ($asets as $item) {

            $umurBulan = (int) $item['umur_bulan'];  // umur ekonomis total (bulan)
            $nilai = (float) $item['nilai_perolehan'];

            if ($umurBulan <= 0 || $nilai <= 0) {
                continue; // tidak dapat disusutkan
            }

            // Hitung beban penyusutan GL
            $beban = round($nilai / $umurBulan, 2);

            // Ambil penyusutan sebelumnya
            $prev = $this->penyModel
                ->where('id_aset', $item['id_aset'])
                ->orderBy('id_penyusutan', 'DESC')
                ->first();

            // Hitung bulan ke-berapa penyusutan ini berjalan
            $bulanKe = $prev ? ($prev['umur_ekonomis_bulan'] > 0
                ? ($prev['umur_ekonomis_bulan'] - $umurBulan) + 1 // tidak dipakai lagi tapi tetap dijaga
                : 1)
                : 1;

            // =============== GUARD PENTING ===============

            // GUARD 1: Jika akumulasi >= nilai perolehan → STOP penyusutan
            if ($prev && $prev['akumulasi_sampai_bulan_ini'] >= $nilai) {
                continue;
            }

            // GUARD 2: Jika jumlah baris penyusutan sudah mencapai umur_bulan → STOP
            $totalSudahPenyusutan = $this->penyModel
                ->where('id_aset', $item['id_aset'])
                ->countAllResults();

            if ($totalSudahPenyusutan >= $umurBulan) {
                continue;
            }

            // =================================================

            // Hitung akumulasi baru
            $akumulasi = $prev
                ? $prev['akumulasi_sampai_bulan_ini'] + $beban
                : $beban;

            // Nilai Buku tidak boleh minus
            $nilai_buku = max($nilai - $akumulasi, 0);

            // Simpan batch
            $batch[] = [
                'id_aset'                     => $item['id_aset'],
                'tahun'                       => $tahun,
                'bulan'                       => $bulan,
                'nilai_perolehan'             => $nilai,
                'umur_ekonomis_bulan'         => $umurBulan,
                'metode_penyusutan'           => 'GARIS_LURUS',
                'beban_penyusutan_bulan'      => $beban,
                'akumulasi_sampai_bulan_ini'  => $akumulasi,
                'nilai_buku'                  => $nilai_buku,
                'created_at'                  => date('Y-m-d H:i:s'),
            ];

            // Update nilai buku ke tabel aset
            $this->asetModel->update(
                $item['id_aset'],
                ['nilai_buku' => $nilai_buku]
            );
        }

        if (!empty($batch)) {
            $this->penyModel->insertBatch($batch);
        }

        return redirect()
            ->to('/superadmin/penyusutan')
            ->with('success', "Penyusutan bulan $bulan-$tahun berhasil digenerate.");
    }
}
