<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ProcessFromExcel;

class ProcessExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-excel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Procesa el archivo excel de las obras';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = [
            "sect" => "",
            "dpto" => 0,
            "prov" => 0,
            "dist" => 0,
            "plie" => 16,
            "tipo" => "GR"
        ];

        ProcessFromExcel::dispatch($data, 'update', 'endpoint_excel_1');

        $data = [
            "sect" => "",
            "plie" => 0,
            "dpto" => "16",
            "prov" => 0,
            "dist" => 0,
            "tipo" => "DPTO"
        ];

        ProcessFromExcel::dispatch($data, 'update', 'endpoint_excel_2')->delay(1000);
    }
}
