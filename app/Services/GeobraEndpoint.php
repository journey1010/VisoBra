<?php

namespace App\Services;

use App\Models\Geobra;
use App\Services\Contracts\DataHandler;

class GeobraEndpoint implements DataHandler
{
    /**
     * Create a new class instance.
    */ 
    protected $dataHoped = [
        'DEPARTAMEN', 
        'PROVINCIA',
        'DISTRITO',
        'X',
        'Y',
    ];

    protected $data = [];


    public function store(array $data)
    {  
        $clean  = $data['features']['attributes'];
        Geobra::create([
            'obras_id' => $data['obras_id'],
            'provincia' => $clean['PROVINCIA'],
            'departamento' => $clean ['DEPARTAMEN'],
            'distrito' =>  $clean['DISTRITO'],
            'coordenada' => [$clean['X'], $clean['Y']],
        ]);
    }

    public function validateFormat(array $data): bool
    {
        if(!is_array($data) || empty($data)){
            return false;
        } 

        foreach ($this->dataHoped as $key => $value){
            if(!array_key_exists($key, $data['features']['attributes'])){
                return false;
            }
        }

        return true;

    }
    
    public function update(int $id, array $data)
    {
        
    }
}