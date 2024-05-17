<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Services\HttpClient;
use App\Services\Notify;
use App\Services\Reporting;
use App\Services\Mailer;

class PoblarGeobra implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
    */
    protected $url = '';
    protected $params = '';
    protected $a = '';

    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
    */

    public function handle(): void
    {
        //
    }
}
