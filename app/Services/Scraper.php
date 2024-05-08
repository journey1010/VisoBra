<?php

namespace App\Services;

use App\Services\Contracts\ScraperInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

abstract class Scraper implements ScraperInterface
{

    abstract public function validateFormat(): bool;

    abstract public function extractData();

    abstract public function proccesData();

    abstract public function store();

    abstract public function logHandler();
}