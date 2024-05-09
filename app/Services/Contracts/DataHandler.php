<?php

namespace App\Services\Contracts;

interface DataHandler {
    
    public function validateFormat(): bool;

    public function proccesData();

    public function store();

}