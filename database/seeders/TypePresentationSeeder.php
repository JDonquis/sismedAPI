<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
class TypePresentationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fields = 
        [
            ['name' => 'N/A'],
            ['name' => 'Jarabe'],
            ['name' => 'Comprimidos'],
            ['name' => 'Tabletas'],
            ['name' => 'Capsulas Blandas'],
            ['name' => 'Ampolla'],
            ['name' => 'Viales'],
            ['name' => 'Intradermicos'],
            ['name' => 'Tópicos'],
            ['name' => 'Gotas'],
            ['name' => 'Inhalatoria'],
            ['name' => 'Transdermica'],
            ['name' => 'Suspensión'],
            ['name' => 'Granulados'],
            ['name' => 'Polvo'],
            ['name' => 'Supositorios'],
            ['name' => 'Ovulos'],
            ['name' => 'Pomadas'],
            ['name' => 'Pasta'],
            ['name' => 'Crema'],
            ['name' => 'Emplastos'],
            ['name' => 'Elixir'],
            ['name' => '5x10CM'],
            ['name' => '7/2'],
            ['name' => '8/9'],
            ['name' => '8/10'],
            ['name' => '8/11'],
            ['name' => '8/12'],
            ['name' => 'Barra'],
            ['name' => 'Liquido'],
            ['name' => 'Rollo'],


         ];   

         DB::table('type_presentations')->insert($fields); 
    }
}
