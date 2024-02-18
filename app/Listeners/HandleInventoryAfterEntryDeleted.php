<?php

namespace App\Listeners;

use App\Models\Inventory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleInventoryAfterEntryDeleted
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
        $entry = $event->entryDeleted;
        $quantity = $entry->quantity;

        $register = Inventory::where('entity_code',$entry->entity_code)
        ->where('product_id',$entry->product_id)
        ->where('lote_number',$entry->lote_number)
        ->where('condition_id',$entry->condition_id)
        ->first();

        $register->decrement('stock',$quantity);
        $register->decrement('entries',$quantity);

        $register->touch();
    }
}
