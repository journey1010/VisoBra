<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ProcessObras;
use App\Jobs\ProccessFotos;
use App\Jobs\ProcessContrataciones;
use App\Jobs\ProcessGeobra;

use App\Models\Obras;

use App\Services\HttpClient;
use App\Services\ObrasEndpoint;
use App\Services\Reporting;
use App\Services\Notify;
use App\Services\Mailer;

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
        $obras = new ObrasEndpoint(new HttpClient);
        $obras->configureHttpClient();
        $response = $obras->fetchValidateResponse();
        
        $registros = $obras->isThereNewData($response['PageSize'], $response['TotalRows'], $response['TotalPage']);
        if(!$registros){
            return;
        }
        $obras->changeParams([
            'PageIndex' => $registros,
            'PageSize' => $registros,
        ]);
        $response = $obras->fetchValidateResponse();
        ProcessObras::dispatch(null,$response['Data'],'store');
        list($codeUnique, $codeSnip)  = $this->storeCodes($response);
        foreach($codeUnique as $code){
            ProcessGeobra::dispatch()
        }
    }

    /**
     * Guarda los codigo snip y codigo unico de inversion
     *
     */
    public function storeCodeS(array $data)
    {
        $codeUnique =[]; 
        $codeSnip = []; 
        $pureData  = $data['Data'];
        foreach($pureData as $key){
            $codeUnique[] = $key['CodigoUnico'];
            $codeSnip[] = $key['Codigo'];
        }

        return [$codeUnique, $codeSnip];
    }

    public function findObra(?int $snip = null, ?int $codeUnique = null): ?int
    {
        $id = null;
    
        if ($codeUnique !== null) {
            $obra = Obras::select('id')->where('codigo_unico_inversion', '=', $codeUnique)->first();
        } elseif ($snip !== null) {
            $obra = Obras::select('id')->where('codigo_snip', '=', $snip)->first();
        }
    
        if ($obra !== null) {
            return $obra->id;
        }
    
        return null; 
    }
    
}