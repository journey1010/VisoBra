<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\HttpClient;
use App\Services\ObrasEndpoint;

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
        
    }
}
