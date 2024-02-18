<?php  

namespace App\Services;

use App\Events\EntryCreated;
use App\Events\EntryDeleted;
use App\Events\InventoryLoteCreated;
use App\Events\InventoryLoteDeleted;
use App\Events\OutputDeleted;
use App\Exceptions\GeneralExceptions;
use App\Http\Resources\EntryCollection;
use App\Http\Resources\EntryResource;
use App\Models\Cancellation;
use App\Models\Entry;
use App\Models\HierarchyEntity;
use App\Models\Inventory;
use App\Models\Organization;
use App\Models\Output;
use App\Models\Product;
use App\Services\ApiService;
use Carbon\Carbon;
use DB;

class CancellationService
{   
    private $TYPE_ENTRY = 1;
    private $TYPE_OUTPUT = 2;

    public function create($request,$type)
    {   
        $response = null;

        if($type == $this->TYPE_ENTRY)
            $this->handleEntryCancellation($request->code);
        
        elseif ($type == $this->TYPE_OUTPUT)
           $this->handleOutputCancellation($request->code);        
        
        $userId = auth()->user()->id;

        Cancellation::create(['user_id' => $userId, 'type_operation_id' => $type, 'code' => $request->code, 'description' => $request->cancelDescription ]);

        return 0;
    }

    private function handleEntryCancellation($code)
    {   

        $affectedRows = Entry::where('entry_code',$code)->where('status',1)->update(['status' => 2]);
        
        if(!$affectedRows > 0)
            throw new GeneralExceptions('Esta entrada ya ha sido cancelada',404);

        $entries = Entry::where('entry_code',$code)->get();

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

            EntryDeleted::dispatch($entry);    
        }

        InventoryLoteDeleted::dispatch($productsAffected);


    }

    private function handleOutputCancellation($code)
    {
        $affectedRows = Output::where('output_code',$code)->where('status',1)->update(['status' => 2]);
        
        if(!$affectedRows > 0)
            throw new GeneralExceptions('Esta salida ya ha sido cancelada',404);

        $outputs = Output::where('output_code',$code)->get();

        $productsAffected = [];
        
        foreach ($outputs as $output) 
        {
            if(!array_key_exists($output->entity_code, $productsAffected))
            {
              $productsAffected[$output->entity_code] = [$output->product_id];
            }    

            else
            {
              if(!in_array($output->product_id, $productsAffected[$output->entity_code]))
                  array_push($productsAffected[$output->entity_code], $output->product_id);
            }

            OutputDeleted::dispatch($output);    
        }

        InventoryLoteDeleted::dispatch($productsAffected);
    }

    
}