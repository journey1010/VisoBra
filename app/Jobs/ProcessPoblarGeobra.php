<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Services\HttpClient;
use App\Services\GeobraEndpoint;
use App\Services\Notify;
use App\Services\Reporting;
use App\Services\Mailer;

class ProcessPoblarGeobra implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
    */
    protected $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
    */

    public function handle(): void
    {
        try{
            for($i = 1; $i <= 2; $i++){
                
            }
        
        }catch(\Exception $e){
            $notifier = new Notify(new Mailer());
            $notifier->clientNotify(
                to: 'ginopalfo001608@gmail.com', 
                message: $e->getMessage(),
                subject: 'Fallo en visoobra al obtener datos');
            Reporting::loggin($e, 100);
        }
    }
}
