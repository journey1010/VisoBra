<?php

namespace App\Services\Contracts;

interface DataHandler {
    
    public function validateFormat(array $data): bool;

    public function update(int $id, array $data);

    public function store(array $data);

}