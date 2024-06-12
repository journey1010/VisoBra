<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ProccessFotos;
use App\Jobs\ProcessContrataciones;
use App\Jobs\ProcessGeobra;

use App\Models\Obras;

use App\Services\HttpClient;
use App\Services\ObrasEndpoint;
use App\Services\Reporting;
use App\Services\Notify;
use App\Services\Mailer;
use Exception;

class SearchNewObras extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:search-new-obras';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando encargado de buscar nuevas obras para ser agredada a la base de datos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $obras = new ObrasEndpoint(new HttpClient());
            $obras->configureHttpClient();
            $response = $obras->fetchValidateResponse();
            
            $registros = $obras->isThereNewData($response['PageSize'], $response['TotalRows'], $response['TotalPage']);
            if (!$registros) {
                return;
            }

            $obras->changeParams([
                'PageIndex' => $registros,
                'PageSize' => $registros,
            ]);
            $response = $obras->fetchValidateResponse();
            $obras->store($response);
            
            list($codeUnique, $codeSnip) = $this->storeCodes($response);

            $this->insertGeobra($codeUnique);
            $this->insertContrataciones($codeSnip);
            $this->insertFotos($codeUnique);

        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    private function insertGeobra(array $codeUnique)
    {
        foreach ($codeUnique as $code) {
            $id = $this->findObraByCodeUnique($code);
            if ($id) {
                ProcessGeobra::dispatch($id, $code);
            }
        }
    }

    private function insertContrataciones(array $codeSnip): void
    {
        foreach ($codeSnip as $code) {
            $id = $this->findObraBySnip($code);
            if ($id) {
                ProcessContrataciones::dispatch($id, $code, 'store');
            }
        }
    }

    private function insertFotos(array $codeUnique)
    {
        foreach ($codeUnique as $code) {
            $id = $this->findObraByCodeUnique($code);
            if ($id) {
                ProccessFotos::dispatch($id, $code, 'store');
            }
        }
    }

    /**
     * Guarda los códigos snip y códigos únicos de inversión.
     */
    private function storeCodes(array $data): array
    {
        $codeUnique = [];
        $codeSnip = [];
        $pureData = $data['Data'];

        foreach ($pureData as $key) {
            $codeUnique[] = $key['CodigoUnico'];
            $codeSnip[] = $key['Codigo'];
        }

        return [$codeUnique, $codeSnip];
    }

    private function findObraByCodeUnique(int $codeUnique): ?int
    {
        $obra = Obras::select('id')->where('codigo_unico_inversion', $codeUnique)->first();
        return $obra ? $obra->id : null;
    }

    private function findObraBySnip(int $snip): ?int
    {
        $obra = Obras::select('id')->where('codigo_snip', $snip)->first();
        return $obra ? $obra->id : null;
    }

    private function handleException(Exception $e)
    {
        $notifier = new Notify(new Mailer());
        $notifier->configLimiter(3, 'Geobra');
        $notifier->clientNotify(
            'soporteapps@regionloreto.gob.pe',
            $e->getMessage(),
            'Fallo en visoobra al obtener datos'
        );
        Reporting::loggin($e, 100);
    }
}