<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = json_decode(file_get_contents(storage_path('app/ubigeo/ubigeo_peru_2016_departamentos.json')), true);
        DB::unprepared('ALTER TABLE departments  AUTO_INCREMENT = 0');
        foreach ($departments as $department) {
            DB::table('departments')->insert([
                'id' => $department['id'],
                'name' => $department['name'],
            ]);
        }

        DB::unprepared('ALTER TABLE departments  AUTO_INCREMENT = 1');
    }
}