<?php

namespace App\Services\Endpoint;

use App\Models\Obras as ObrasTable;
use App\Services\Contracts\DataHandler;

class Obras implements DataHandler
{

    public $dataHoped =  [
        '',
        'Codigo',
        'Nombre',
        'MontoAlternativa',
        'Funcion',
        'Programa',
        'Subprograma',
        'Situacion', 
        'Nivel',
        'Sector',
        'Pliego',

        
    
    ];
    

    public function validateFormat(): bool
    {
        return true;
    }

    public function proccesData()
    {
        
    }

    public function store()
    {
        
    }
}
