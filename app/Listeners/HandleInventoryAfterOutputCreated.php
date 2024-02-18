<?php

namespace App\Listeners;

use App\Models\Inventory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleInventoryAfterOutputCreated
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
        $newOutput = $event->newOutput;
        $quantity = $newOutput['quantity'];

        $register = Inventory::where('entity_code',$newOutput['entity_code'])
        ->where('product_id',$newOutput['product_id'])
        ->where('lote_number',$newOutput['lote_number'])
        ->where('condition_id',$newOutput['condition_id'])
        ->first();    

        $register->decrement('stock',$quantity);
        $register->increment('outputs',$quantity);
        $register->touch();

    }
}
