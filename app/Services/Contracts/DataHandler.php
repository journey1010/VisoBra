<?php

namespace App\Services\Contracts;

interface DataHandler {
    
    public function validateFormat(array $data): bool;

    public function proccesData();

    public function store();

}