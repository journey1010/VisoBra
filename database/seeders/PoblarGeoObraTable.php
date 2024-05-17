<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Obras;

class PobblarGeoObraTable extends Seeder
{
    /**
     * Run the database seeds.
    */
    protected $url = '';
    protected $params = [];

    public function run(): void
    {
        $registros = Obras::select('codigo_unico_inversion')
                    ->whereNotNull('codigo_unico_inversion')
                    ->get();
        foreach($registros as $registro){
            
        }
    }
}
