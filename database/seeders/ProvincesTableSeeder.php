<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvincesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = json_decode(file_get_contents(storage_path('ubigeo/ubigeo_peru_2016_provincias.json')), true);
        foreach ($provinces as $province) {
            $id = (int) $province['department_id'];
            $departmentId = DB::table('departments')->where('id', $id )->first();
            DB::table('provinces')->insert([
                'name' => $province['name'],
                'department_id' => $departmentId->id
            ]);
        }
    }
}
