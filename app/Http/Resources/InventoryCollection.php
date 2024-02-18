<?php

namespace App\Http\Resources;

use App\Http\Resources\InventoryDetail;
use App\Http\Resources\InventoryDetailCollection;
use App\Models\Inventory;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InventoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {   
        //Numbers = Conditions 
        // 1 => Buen estado
        // 2 => Defectuoso
        // 3 => Vencido

        $response = [];

        $productsIds = $this->pluck('product_id')->unique()->toArray();
        $entityCodes = $this->pluck('entity_code')->unique()->toArray();

        $inventories = Inventory::whereIn('product_id',$productsIds)->whereIn('entity_code',$entityCodes)->with('condition')->get();


        foreach ($this as $register)
        {   

            
            $loteGoods = $inventories->where('entity_code',$register->entity_code)->where('product_id',$register->product_id)->where('condition_id',1)->where('stock','>',0);
            $loteBads = $inventories->where('entity_code',$register->entity_code)->where('product_id',$register->product_id)->where('condition_id',2)->where('stock','>',0);
            $loteExpired = $inventories->where('entity_code',$register->entity_code)->where('product_id',$register->product_id)->where('condition_id',3)->where('stock','>',0);


            $response[$register->id] = 
            [       
                    'entityCode' => $register->entity_code,
                    'entityName' => $register->entity_name,
                    'stock' => $register->stock,
                    'stockBad' => $register->stock_bad,
                    'stockExpired' => $register->stock_expired,
                    'entries' => $register->entries,
                    'outputs' => $register->outputs,
                    'productId' => $register->product->id,
                    'code' => $register->product->code,
                    'name' => $register->product->name,
                    'categoryName' => $register->product->category->name,
                    'categoryId' => $register->product->category->id,
                    'typePresentationName' => $register->product->presentation->name,
                    'typePresentationId' => $register->product->presentation->id,
                    'typeAdministrationName' => $register->product->administration->name,
                    'typeAdministrationId' => $register->product->administration->id,
                    'medicamentName' => $register->product->medicament->name,
                    'medicamentId' => $register->product->medicament->id,
                    'unitPerPackage' => $register->product->unit_per_package,
                    'concentrationSize' => $register->product->concentration_size,
                    'minimumStock' => $register->product->minimum_stock,
                    'lots' =>
                    [
                        'good' => new InventoryDetailCollection($loteGoods),
                        'bad' => new InventoryDetailCollection($loteBads),
                        'expired' => new InventoryDetailCollection($loteExpired),

                    ]
            ];
            
        }

        return $response;
    }
}
