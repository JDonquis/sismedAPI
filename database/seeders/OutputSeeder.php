<?php

namespace Database\Seeders;

use App\Models\Output;
use App\Services\OutputService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OutputSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $outputService = new OutputService;
        $outputs = Output::factory(10)->create();
        
          foreach ($outputs as $output) 
          {
                $outputService->removeInventory($output);  
          }
    
    }
}
