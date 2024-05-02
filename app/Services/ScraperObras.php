<?php

namespace App\Services;

use App\Services\Contracts\ScraperInterface;

class ScraperObras implements ScraperInterface
{
    public function config (string $url, string $method, array $headers = false)
    {
        
    }
    
    public function validateFormat(): bool;

    public function extractData();

    public function proccesData();

    public function store();

    public function logHandler();
}