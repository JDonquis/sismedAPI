<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fields = [

            ['name' => 'Paciente', 'code' => 'nocode', 'search' => 'Paciente'],
            ['name' => 'MPPS','code'=> 'nocode', 'search' => 'MPPS'],
            ['name' => 'Secretaría de Salud','code'=> '1', 'search' => 'Secretaría de Salud 1'],
            ['name' => 'SEFAR','code'=> 'nocode', 'search' => 'SEFAR'],
            ['name' => 'CARITA','code'=> 'nocode', 'search' => 'CARITA'],
            ['name' => 'OMIS','code'=> 'nocode', 'search' => 'OMIS'],
            ['name' => 'UNICEF','code'=> 'nocode', 'search' => 'UNICEF'],
            ['name' => 'ACNUR','code'=> 'nocode','search' => 'ACNUR'],
            ['name' => 'Donaciones','code'=> 'nocode', 'search' => 'Donaciones'],
            ['name' => 'Hospital Cruz Verde','code'=> '1-1', 'search' => 'Hospital Cruz Verde 1-1'],
            ['name' => 'Hospital Velitas','code'=> '1-2', 'search' => 'Hospital Velitas 1-2'],
            ['name' => 'Hospital Chimpire','code'=> '1-3', 'search' => 'Hospital Chimpire 1-3'],
            ['name' => 'Hospital Universitario Dr. Alfredo Van Grieken','code'=> '1-4', 'search' => 'Hospital Universitario Dr. Alfredo Van Grieken 1-4'],


        ];

        DB::table('organizations')->insert($fields);
    }
}
