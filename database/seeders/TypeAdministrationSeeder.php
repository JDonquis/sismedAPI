<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
class TypeAdministrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fields = 
        [
            ['name' => 'N/A'],
            ['name' => 'Oral'],
            ['name' => 'Intramuscular'],
            ['name' => 'Intravenoso'],
            ['name' => 'Nasal'],
            ['name' => 'Oftalmica'],
            ['name' => 'Optica'],
            ['name' => 'Topica'],
            ['name' => 'Rectal'],
            ['name' => 'Vaginal'],
            ['name' => 'Cutanea'],
            ['name' => 'Sublingual'],
            ['name' => 'Sonda Nasogastrica'],
            ['name' => 'Enteral'],
            ['name' => 'Intramuscular/Endovenoso'],
            ['name' => 'IM/IV'],
         ];   

         DB::table('type_administrations')->insert($fields); 
    }
}
