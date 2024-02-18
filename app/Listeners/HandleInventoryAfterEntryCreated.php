<?php

namespace App\Listeners;

use App\Events\InventoryLoteCreated;
use App\Models\Inventory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleInventoryAfterEntryCreated
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
    public function handle($event)
    {   
        $newEntry = $event->newEntry;
        $quantity = $newEntry['quantity'];

        $register = Inventory::updateOrCreate(    
        [
            'entity_code' => $newEntry['entity_code'],
            'product_id' => $newEntry['product_id'],
            'lote_number' => $newEntry['lote_number'],
            'condition_id' => $newEntry['condition_id'],
        ],
        [   
            'expiration_date' => $newEntry['expiration_date'],
            'search' => $newEntry['search'],
        ]
         );

        $register->increment('stock',$quantity);
        $register->increment('entries',$quantity);

        $register->touch();

    }
}
