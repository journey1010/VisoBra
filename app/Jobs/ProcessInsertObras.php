<?php

namespace App\Jobs;

use App\Exceptions\DataHandlerException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Services\ObrasEndpoint;
use App\Services\HttpClient;
use App\Services\Mailer;
use App\Services\Notify;
use App\Services\Reporting;

class ProcessInsertObras implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{
            $obras = new ObrasEndpoint(new HttpClient());
            $obras->store($this->data);
        }catch(\Exception $e){
            $notifier = new Notify(new Mailer());
            $notifier->configLimiter(3, 'Geobra');
            $notifier->clientNotify(
                to: 'ginopalfo001608@gmail.com',
                message: $e->getMessage(),
                subject: 'Fallo en visoobra al obtener datos'
            );
            Reporting::loggin($e, 100);
        }
   
    }

    public function tags(): array
    {
        return ['Process_obra_insert'];
    }
}
