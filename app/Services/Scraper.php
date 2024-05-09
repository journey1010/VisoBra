<?php

namespace App\Services;

use App\Services\Contracts\ScraperInterface;
use Illuminate\Support\Facades\Log;

abstract class Scraper implements ScraperInterface
{
    abstract public function validateFormat(): bool;

    abstract public function proccesData();

    abstract public function store();

    public function logHandler(string $message): void
    {
        
    }
}