<?php

namespace App\Services;

use App\Models\Geobra;
use App\Services\Contracts\DataHandler;

class GeobraEndpoint implements DataHandler
{
    /**
     * Create a new class instance.
    */ 
    protected $dataHoped = [];
    
    /**
     *
    */
    
    protected $data = [];


    public function __construct()
    {
        
    }

    public function store(array $data)
    {
        
    }

    public function validateFormat(array $data): bool
    {
     
        return true;

    }
    
    public function update(int $id, array $data)
    {
        
    }
}