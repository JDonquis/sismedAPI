<?php

namespace App\Listeners;

use App\Models\Inventory;
use App\Models\InventoryGeneral;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RefreshInventoryGeneral
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {   

        $arrayProduct = $event->arrayProductWithEntityCode;
        
        foreach ($arrayProduct as $entityCode => $arrayProductIds)
        {   
            $inventoriesLote = Inventory::where('entity_code',$entityCode)->whereIn('product_id',$arrayProductIds)->get();


            $dataToInsert = [];


            foreach ($inventoriesLote as $inventory)
            {   
                if(!array_key_exists($inventory->product_id, $dataToInsert))
                {
                    $stock = 0;
                    $stockExpired = 0;
                    $stockBad = 0;
                    $entries = 0;
                    $outputs = 0;

                    if($inventory->condition_id == 1)
                    $stock += $inventory->stock;

                    elseif($inventory->condition_id == 2)
                        $stockBad += $inventory->stock;

                    elseif($inventory->condition_id == 3)
                        $stockExpired += $inventory->stock;

                    $dataToInsert[$inventory->product_id] = ['stock' => $stock, 'stock_expired' => $stockExpired, 'stock_bad' => $stockBad, 'entries' => $inventory->entries, 'outputs' => $inventory->outputs];
                }
                else
                {
                    if($inventory->condition_id == 1)
                    $dataToInsert[$inventory->product_id]['stock'] += $inventory->stock;

                    elseif($inventory->condition_id == 2)
                        $dataToInsert[$inventory->product_id]['stock_bad'] += $inventory->stock;

                    elseif($inventory->condition_id == 3)
                        $dataToInsert[$inventory->product_id]['stock_expired'] += $inventory->stock;


                    $dataToInsert[$inventory->product_id]['entries']+= $inventory->entries;
                    $dataToInsert[$inventory->product_id]['outputs']+= $inventory->outputs;
                    
                }
                
            }

            foreach ($dataToInsert as $productId => $values)
            {   

                $search = Product::select('search')->where('id',$productId)->first();
                $entityCodeToSearch = strval($entityCode);


                $newInventory = InventoryGeneral::firstOrNew([
                    'entity_code' => $entityCodeToSearch,
                    'product_id' => $productId,
                ]);

                $newInventory->stock = $values['stock'];
                $newInventory->stock_bad = $values['stock_bad'];
                $newInventory->stock_expired = $values['stock_expired'];
                $newInventory->entries = $values['entries'];
                $newInventory->outputs = $values['outputs'];
                $newInventory->search = $search->search;

                $newInventory->save();

            }
            


            
        }



        

      

        
    }
}
