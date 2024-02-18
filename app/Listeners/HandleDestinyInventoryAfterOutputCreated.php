<?php

namespace App\Listeners;

use App\Models\Inventory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleDestinyInventoryAfterOutputCreated
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
        if($event->destiny !== 'nocode')
        {
            $newEntry = $event->newOutput;

            $entityCode = $event->destiny;
            $quantity = $newEntry['quantity'];

            $register = Inventory::updateOrCreate(    
            [
                'entity_code' => $entityCode,
                'product_id' => $newEntry['product_id'],
                'lote_number' => $newEntry['lote_number'],
                'condition_id' => $newEntry['condition_id']
            ],
            [   
                'expiration_date' => $newEntry['expiration_date'],
                'search' => $newEntry['search'],

            ]
        );

            $register->increment('stock',$quantity);
            $register->increment('entries',$quantity);
        }

        
    }
}
