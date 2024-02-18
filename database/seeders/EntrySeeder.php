<?php

namespace Database\Seeders;

use App\Events\EntryCreated;
use App\Events\InventoryLoteCreated;
use App\Models\Entry;
use App\Services\EntryService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        $entryService = new EntryService;
        $entries = Entry::factory(50)->create();
        $productsAffected = [];
          foreach ($entries as $entry) 
          {
            
            if(!array_key_exists($entry->entity_code, $productsAffected))
            {
              $productsAffected[$entry->entity_code] = [$entry->product_id];
            }    

            else
            {
              if(!in_array($entry->product_id, $productsAffected[$entry->entity_code]))
                  array_push($productsAffected[$entry->entity_code], $entry->product_id);
            }
           

            EntryCreated::dispatch($entry->toArray());  
          }


          InventoryLoteCreated::dispatch($productsAffected);

    }
}
