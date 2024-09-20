<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Exceptions\SpreedSheetException;
use Exception;
use Illuminate\Support\LazyCollection;
use Carbon\Carbon;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Common\Entity\Style\Color;

/**
 * Class SpreedSheetHandler
 * 
 * Esta clase maneja las operaciones básicas para almacenar, importar y procesar hojas de cálculo en formato Excel.
 */
class SpreedSheetHandler
{
    public $path;
    public $name;

    public function __construct()
    {
        // Constructor vacío
    }

    /**
     * Almacena la hoja de cálculo en el sistema de archivos local.
     *
     * @param string $spreedSheet El contenido de la hoja de cálculo.
     * @return string|null La ruta del archivo guardado o null si falló.
     */
    public function store(string $spreedSheet): string|null 
    {   
        $this->name = random_int(1000, 6000) . '.xlsx';
        $wasSaved = Storage::disk('local')->put($this->name, $spreedSheet);

        if ($wasSaved) {
            $this->path = Storage::disk('local')->path($this->name);
            return $this->path;
        }
        return null; 
    }

    /**
     * Importa la hoja de cálculo desde el archivo especificado.
     *
     * @param string $path La ruta del archivo Excel.
     * @param int $rowHeaders La fila que contiene los encabezados (por defecto es la fila 4).
     * @return LazyCollection Una colección perezosa de las filas importadas.
     */
    public function importSpreedSheet(string $path, int $rowHeaders = 4): LazyCollection
    {
        $rows = SimpleExcelReader::create($path)
            ->trimHeaderRow()
            ->headerOnRow($rowHeaders)
            ->getRows();

        return $rows;
    }

    /**
     * Procesa y ordena los datos de la hoja de cálculo.
     *
     * @param LazyCollection $lazyCollection La colección perezosa de datos de la hoja de cálculo.
     * @param string $order El orden de clasificación ('asc' para ascendente, 'desc' para descendente).
     * @return LazyCollection La colección ordenada de datos procesados.
     * @throws SpreedSheetException Si ocurre un error durante el procesamiento de datos.
     */
    public function processData(LazyCollection $lazyCollection, string $order = 'desc')
    {
        try {        
            $arrayData = $lazyCollection->map(function ($row) {
                return [
                    'CUI' => $row['CUI'] ?? null,
                    'FECHA_REGISTRO' => $row['FECHA REGISTRO'] ?? null,
                    'NIVEL_GOBIERNO' => $row['NIVEL DE GOBIERNO'] ?? null,
                ];
            }); 

            $sortedData = $arrayData->sortBy(function ($item) {
                // Convertimos la fecha de dd/mm/yyyy a un formato que se pueda ordenar correctamente
                $date = Carbon::createFromFormat('d/m/Y', $item['FECHA_REGISTRO']);
                return $date->format('Y-m-d'); // Ordena primero por año, luego por mes, y finalmente por día
            });

            // Si se solicitó orden descendente, invertimos la colección
            if ($order == 'desc') {
                $sortedData = $sortedData->reverse();
            }

            return $sortedData; 

        } catch (Exception $e) {
            throw new SpreedSheetException('Fallo al mapear datos, error al procesar datos, SpreedSheetHandler: ' . $e->getMessage());
        }
    }

    public function makeReport(LazyCollection $collection, string $path)
    {      
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile($path);

        /**Stylign headers */
        $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(11)
            ->setFontColor(Color::BLACK)
            ->setCellAlignment(CellAlignment::CENTER)
            ->setBackgroundColor(Color::YELLOW)
            ->build();

        $headers = WriterEntityFactory::createRowFromArray([
            'Código único de Inversión',
            'Código SNIP',
            'Nombre Inversión',
            'Estado Inversión',
            'Monto Viable',
            'Función',
            'Subprograma',
            'Programa',
            'Sector',
            'Provincia',
            'Distrito'
        ], $style);
        
        $writer->addRow($headers);
        
        $collection->each(function($items) use ($writer){
            $row = WriterEntityFactory::createRowFromArray([
                $items->codigoUnicoInversion,
                $items->codigo_snip,
                $items->nombreInversion,
                $items->estadoInversion,
                $items->montoViable,
                $items->funcion, 
                $items->subprograma,
                $items->programa,
                $items->sector,
                $items->provincia,
                $items->distrito
            ]);
            $writer->addRow($row);
        });
        
        $writer->close();
    }

    /**
     * Elimina el archivo de la hoja de cálculo del sistema de archivos local.
     */
    public function drop(): void
    {
        Storage::disk('local')->delete($this->name);
    }
}