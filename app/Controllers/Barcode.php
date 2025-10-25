<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AsetModel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;

class Barcode extends BaseController
{
    protected AsetModel $asetModel;

    public function __construct()
    {
        $this->asetModel = new AsetModel();
    }

    public function qr(int $id_aset)
    {
        $row = $this->asetModel->select('qr_token')->find((int)$id_aset);
        if (!$row || empty($row['qr_token'])) {
            return $this->response->setStatusCode(404)->setBody('QR token not found');
        }

        // Arahkan ke halaman publik
        $url = base_url('p/aset/' . $row['qr_token']);

        $qrCode = new QrCode(
            $url,
            new Encoding('UTF-8'),
            ErrorCorrectionLevel::Low,
            200, // size
            10 // margin
        );

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        return $this->response
            ->setHeader('Cache-Control', 'public, max-age=31536000, immutable')
            ->setContentType('image/png')
            ->setBody($result->getString());
    }
}
