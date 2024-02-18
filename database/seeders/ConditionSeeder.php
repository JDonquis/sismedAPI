<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
class ConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fields = [

            ['name' => 'Buen estado'],
            ['name' => 'Defectuoso'],
            ['name' => 'Vencido'],

        ];

        DB::table('conditions')->insert($fields);
    }
}
