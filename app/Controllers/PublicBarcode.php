<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;

class PublicBarcode extends BaseController
{
    public function qr(string $token)
    {
        // URL publik yang dituju saat QR discan
        $url = base_url('p/aset/' . $token);

        $qrCode = new QrCode(
            $url,
            new Encoding('UTF-8'),
            ErrorCorrectionLevel::Low,
            200, // size
            10   // margin
        );

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        return $this->response
            ->setHeader('Cache-Control', 'public, max-age=31536000, immutable')
            ->setContentType('image/png')
            ->setBody($result->getString());
    }
}
