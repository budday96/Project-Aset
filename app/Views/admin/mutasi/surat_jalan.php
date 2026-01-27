<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Jalan - <?= esc($header['nomor_surat_jalan']) ?></title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
            margin: 30px;
        }

        .kop {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kop h2 {
            margin: 0;
            font-size: 18px;
            letter-spacing: 1px;
        }

        .kop p {
            margin: 2px 0;
            font-size: 11px;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            font-size: 15px;
            margin: 20px 0 10px;
            text-decoration: underline;
        }

        .meta {
            margin-bottom: 20px;
        }

        .meta table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta td {
            padding: 4px;
            vertical-align: top;
        }

        table.detail {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.detail th,
        table.detail td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 11px;
        }

        table.detail th {
            background: #f0f0f0;
            text-align: center;
        }

        .ttd {
            margin-top: 40px;
            width: 100%;
        }

        .ttd td {
            width: 33%;
            text-align: center;
            vertical-align: top;
        }

        .ttd .nama {
            margin-top: 60px;
            font-weight: bold;
            text-decoration: underline;
        }

        .watermark {
            position: fixed;
            top: 40%;
            left: 20%;
            font-size: 60px;
            color: rgba(0, 0, 0, 0.08);
            transform: rotate(-30deg);
            z-index: -1;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="watermark">SURAT JALAN</div>

    <!-- KOP SURAT -->
    <div class="kop">
        <h2>PT MYASSET INDONESIA</h2>
        <p>ASSET MANAGEMENT DIVISION</p>
        <p>Jl. Merdeka No. 123, Jakarta Pusat</p>
        <p>Email: support@myasset.co.id | Telp: (021) 5544 8899</p>
    </div>

    <div class="judul">SURAT JALAN</div>

    <!-- META -->
    <div class="meta">
        <table>
            <tr>
                <td width="15%">Nomor</td>
                <td width="35%">: <?= esc($header['nomor_surat_jalan']) ?></td>
                <td width="15%">Tanggal</td>
                <td width="35%">: <?= date('d M Y', strtotime($header['tanggal_mutasi'])) ?></td>
            </tr>
            <tr>
                <td>Cabang Asal</td>
                <td>: <?= esc($header['cabang_asal']) ?></td>
                <td>Cabang Tujuan</td>
                <td>: <?= esc($header['cabang_tujuan']) ?></td>
            </tr>
            <tr>
                <td>Status</td>
                <td colspan="3">: DIKIRIM</td>
            </tr>
            <tr>
                <td width="15%">Metode Kirim</td>
                <td>: <?= esc($header['metode_pengiriman']); ?></td>
                <td width="15%">Pengantar</td>
                <td>: <?= esc($header['nama_pengantar']); ?></td>
            </tr>
            <tr>
                <td>Nomor Kendaraan</td>
                <td>: <?= esc($header['nomor_kendaraan'] ?: '-') ?></td>
            </tr>

        </table>
    </div>

    <!-- DETAIL ASET -->
    <table class="detail">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Kode Aset</th>
                <th>Nama Aset</th>
                <th width="10%">Qty</th>
                <th width="25%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($details as $d): ?>
                <tr>
                    <td align="center"><?= $no++ ?></td>
                    <td><?= esc($d['kode_aset']) ?></td>
                    <td><?= esc($d['nama_master']) ?></td>
                    <td align="center"><?= (int)$d['qty'] ?></td>
                    <td><?= esc($d['keterangan'] ?? '-') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- TANDA TANGAN -->
    <p style="margin-top:20px; font-size:12px;">
        Surat Jalan ini dibuat sebagai bukti pengiriman aset antar cabang PT MYASSET INDONESIA.
        Seluruh aset yang tercantum telah diserahkan kepada pihak pengantar untuk dikirimkan
        ke cabang tujuan sesuai dengan ketentuan yang berlaku.
    </p>

    <table width="100%" style="margin-top:30px; font-size:12px; text-align:center;">
        <tr>
            <td>
                Dikirim Oleh<br>
                <strong><?= esc($header['cabang_asal']); ?></strong><br><br><br>
                ( __________________ )
            </td>
            <td>
                Diketahui<br>
                <strong>Manajemen</strong><br><br><br>
                ( __________________ )
            </td>
            <td>
                Diterima Oleh<br>
                <strong><?= esc($header['cabang_tujuan']); ?></strong><br><br><br>
                ( __________________ )
            </td>
        </tr>
    </table>


</body>

</html>