<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
class TypeOperationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        $rows = [

            ['name' => 'Entrada'],
            ['name' => 'Salida'],

        ];

       DB::table('type_operations')->insert($rows); 
    }
}
