<?php

namespace App\Services;

use App\Services\Contracts\ScraperInterface;

abstract class Scraper implements ScraperInterface
{
    abstract public function validateFormat(): bool;

    abstract public function proccesData();

    abstract public function store();

    public function logHandler(): void
    {

    }
}