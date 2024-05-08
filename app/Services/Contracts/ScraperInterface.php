<?php

namespace App\Services\Contracts;

interface ScraperInterface {
    
    public function validateFormat(): bool;

    public function extractData();

    public function proccesData();

    public function store();

    public function logHandler();
}