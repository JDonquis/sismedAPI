<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fields = 
        [
            ['name' => 'Medicamentos'],
            ['name' => 'Insumos Medicos'],
         ];   

         DB::table('categories')->insert($fields); 
    }

}
