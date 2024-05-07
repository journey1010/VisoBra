<?php

namespace App\Services;

use App\Services\Contracts\ScraperInterface;
use Illuminate\Support\Facades\Http;

abstract class Scraper implements ScraperInterface
{
    public function config (string $url, string $method, int $retry = 1,array $headers = false)
    {

    }
    
    abstract public function validateFormat(): bool;

    abstract public function extractData();

    abstract public function proccesData();

    abstract public function store();

    abstract public function logHandler();
}