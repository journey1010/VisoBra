<?php

namespace App\Services;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;


class Qr{

    /** For roundBlockSizeMode;
     *  case Enlarge = 'enlarge';
     *  case Margin = 'margin';
     *  case None = 'none';
     *  case Shrink = 'shrink';
     */
    protected $margin = 10;
    protected $backgroundColor = [255, 0, 0]; 
    protected $logoResizeToWidth = 50;
    protected $logoPunchoutBackground = true;
    protected $blockColor = [0, 0, 0];

    public function __construct(  public string $data, public string $img, public ?int $size = 400){}

    /**
     * @return string path file of Qr Code
     */
    public function  make(): string
    {
        $result = Builder::create()
                            ->writer(new PngWriter())
                            ->writerOptions([])
                            ->data($this->data)
                            ->encoding(new Encoding('UTF-8'))
                            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
                            ->size($this->size)
                            ->margin(10)
                            ->roundBlockSizeMode(RoundBlockSizeMode::Shrink)
                            ->foregroundColor(new Color($this->blockColor[0], $this->blockColor[1], $this->blockColor[2]))
                            ->backgroundColor(new Color($this->backgroundColor[0],$this->backgroundColor[1], $this->backgroundColor[2]))
                            ->logoPath($this->img)
                            ->logoPunchoutBackground($this->logoPunchoutBackground)
                            ->logoResizeToWidth($this->logoResizeToWidth)
                            ->validateResult(false)
                            ->build();

         $filePath = storage_path('app/public/'. random_int(2330,4666) . '-qr.png');
         $result->saveToFile($filePath);
     
        return $filePath;
    }

    public function configure(array $data)
    {
        foreach($data as $key => $value){
            $this->{$key} = $value;
        }
    }
}