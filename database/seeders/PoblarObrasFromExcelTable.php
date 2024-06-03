<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Jobs\ProcessFromExcel;




class PoblarObrasFromExcelTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            "sect" => "",
            "dpto" => 0,
            "prov" => 0,
            "dist" => 0,
            "plie" => 16,
            "tipo" => "GR"
        ];

        ProcessFromExcel::dispatch($data, 'store', 'endpoint_excel_1');

        $data = [
            "sect" => "",
            "plie" => 0,
            "dpto" => "16",
            "prov" => 0,
            "dist" => 0,
            "tipo" => "DPTO"
        ];

        ProcessFromExcel::dispatch($data, 'store', 'endpoint_excel_2');

    }
}