<?php

namespace App\Service;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;


class Qr{
    /**
     * Create a new class instance.
     */

    protected $url  = 'https://ofi5.mef.gob.pe/inviertews/Repseguim/ResumF12B?codigo=';

    public function __construct(public $cui, public string $img)
    {
        $this->url = $cui;
    }

    public function  make()
    {
        $result = Builder::create()
                            ->writer(new PngWriter())
                            ->writerOptions([])
                            ->data('Custom QR code contents')
                            ->encoding(new Encoding('UTF-8'))
                            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
                            ->size(300)
                            ->margin(10)
                            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
                            ->logoPath($this->img)
                            ->logoResizeToWidth(50)
                            ->logoPunchoutBackground(true)
                            ->labelText('This is the label')
                            ->labelFont(new NotoSans(20))
                            ->labelAlignment(LabelAlignment::Center)
                            ->validateResult(false)
                            ->build();
        
         $a = $result->saveToFile(storage_path('app') . 'qr.png');
    }
}