<?php

namespace App\Listeners;

use App\Models\Inventory;
use App\Models\Organization;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleInventoryAfterOutputDeleted
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
        $output = $event->outputDeleted;
        $quantity = $output->quantity;

        $codeEntity = $this->getCodeOrganization($output->organization_id);

        if($codeEntity !== 'nocode')
        {
            $register = Inventory::where('entity_code',$codeEntity)
            ->where('product_id',$output->product_id)
            ->where('lote_number',$output->lote_number)
            ->where('condition_id',$output->condition_id)
            ->first();

            $register->decrement('stock',$quantity);
            $register->decrement('entries',$quantity);

            $register->touch();
        }

        $register = Inventory::where('entity_code',$output->entity_code)
        ->where('product_id',$output->product_id)
        ->where('lote_number',$output->lote_number)
        ->where('condition_id',$output->condition_id)
        ->first();

        $register->increment('stock',$quantity);
        $register->decrement('outputs',$quantity);

        $register->touch();

        
    }

    private function getCodeOrganization($id)
    {
        $organization = Organization::find($id);

        return $organization->code;
    }
}
