<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class HierarchyEntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fields = 
        [
            ['name' => 'Secretaría de Salud', 'code' => '1'],
            ['name' => 'Hospital Cruz Verde', 'code' => '1-1'],
            ['name' => 'Hospital Velitas', 'code' => '1-2'],
            ['name' => 'Hospital Chimpire', 'code' => '1-3'],
            ['name' => 'Hospital Universitario Dr. Alfredo Van Grieken', 'code' => '1-4'],
         ];   

         DB::table('hierarchy_entities')->insert($fields); 
    }
}
