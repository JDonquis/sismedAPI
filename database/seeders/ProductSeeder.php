<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fields = [

            ['code' => 1, 'name' => 'Acetaminofen', 'category_id' => 1, 'type_presentation_id' => 4, 'type_administration_id' => 2, 'medicament_id' => 4, 'unit_per_package' => 30,'concentration_size' =>  '100mg', 'search' =>'Acetaminofen 30 Tabletas 100mg', 'minimum_stock' => 10],
            [ 'code' => 2, 'name' => 'Guantes', 'category_id' => 2, 'type_presentation_id' => 1, 'type_administration_id' => 1, 'medicament_id' => 1,'unit_per_package' => 2,'concentration_size' =>  '10cm', 'search' => 'Guantes 2 N/A 10cm', 'minimum_stock' => 10],
            
        ];
        
        DB::table('products')->insert($fields);

    }
}
