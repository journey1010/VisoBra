<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = json_decode(file_get_contents(storage_path('ubigeo/ubigeo_peru_2016_distritos.json')), true);
        foreach ($districts as $district) {
            $idDepartament = (int) $district['department_id'];

            $departmentId = DB::table('departments')->where('id', $idDepartament )->first();
            $districtId = DB::table('provinces')->where('department_id', $departmentId->id)->first();

            DB::table('districts')->insert([
                'name' => $district['name'],
                'department_id' => $departmentId->id,
                'province_id' =>  $districtId->id
            ]);
        }
    }
}