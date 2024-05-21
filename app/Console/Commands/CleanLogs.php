<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CleanLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina los registros de logs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $logPath = storage_path('logs');
        
        $files = File::files($logPath);

        foreach ($files as $file) {
            File::delete($file);
        }
    }
}