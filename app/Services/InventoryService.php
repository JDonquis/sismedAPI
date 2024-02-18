<?php  

namespace App\Services;

use App\Exceptions\GeneralExceptions;
use App\Http\Resources\EntryCollection;
use App\Http\Resources\EntryResource;
use App\Models\Entry;
use App\Models\HierarchyEntity;
use App\Models\Inventory;
use App\Models\InventoryGeneral;
use App\Models\Product;
use Carbon\Carbon;
use DB;

class InventoryService extends ApiService
{   

    protected $snakeCaseMap = [

        'productId' => 'product_id',
        'loteNumber' => 'lote_number',
        'categoryId' => 'category_id',
        'typePresentationId' => 'type_presentation_id',
        'typeAdministrationId' => 'type_administration_id',
        'medicamentId' => 'medicament_id',
        'unitPerPackage' => 'unit_per_package',
        'concentrationSize' => 'concentration_size',

    ];



    public function __construct()
    {
        parent::__construct(new InventoryGeneral);
    }

    public function getData($paginateArray, $queryArray, $userEntityCode)
    {   
        $wantSeeOtherEntity = false;
        $codeToSee = $userEntityCode;
        $inventories = InventoryGeneral::select([
            'inventory_generals.*',
            'hierarchy_entities.name as entity_name',
            'products.name as product_name',
            'categories.name as category_name',
            'type_administrations.name as type_administration_name',
            'type_presentations.name as type_presentation_name',
            'medicaments.name as medicament_name',
        ])
        ->join('hierarchy_entities','inventory_generals.entity_code','=','hierarchy_entities.code')
        ->join('products','inventory_generals.product_id','=','products.id')
        ->join('categories','products.category_id','=','categories.id')
        ->join('type_presentations','products.type_presentation_id','=','type_presentations.id')
        ->join('type_administrations','products.type_administration_id','=','type_administrations.id')
        ->join('medicaments','products.medicament_id','=','medicaments.id');



        foreach ($queryArray as $table => $array )
        {       

            if($table == 'search')
                $table = 'inventory_generals';
            
            foreach ($array as $params)
            {   
                if($params[0] == 'entity_code')
                {
                    $wantSeeOtherEntity = true;
                    $codeToSee = $params[2];
                }
                else
                {

                    if(isset($params[3]))
                        $inventories = $inventories->orWhere($table.'.'.$params[0],$params[1],$params[2]);    
                    else
                        $inventories = $inventories->where($table.'.'.$params[0],$params[1],$params[2]);    
                }

            }
        }
    
        if($userEntityCode == '1' && $wantSeeOtherEntity == true)
        {   
            if($codeToSee !== '*')
            {
                $inventories = $inventories->where('inventory_generals.entity_code','=',$codeToSee);
            }
        }
        else
            $inventories = $inventories->where('inventory_generals.entity_code','=',$userEntityCode);


        $inventories = $inventories->orderBy($paginateArray['orderBy'],$paginateArray['orderDirection'])
        ->paginate($paginateArray['rowsPerPage'], ['*'], 'page', $paginateArray['page']);

        return $inventories;

    }

    public static function refreshAllInventories()
    {
        $entities = HierarchyEntity::all();

        foreach ($entities as $entity)
        {
            $inventoriesLote = Inventory::where('entity_code',$entity->code)->get();
            $dataToInsert = [];
            if(count($inventoriesLote) == 0)
                continue;

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
                InventoryGeneral::updateOrCreate([

                    'entity_code' => $entity->code,
                    'product_id' => $productId,

                ],
                [
                    'stock' => $values['stock'],
                    'stock_bad' => $values['stock_bad'],
                    'stock_expired' => $values['stock_expired'],
                    'entries' => $values['entries'],
                    'outputs' => $values['outputs'],
                    'search' => $search->search,
                ]
             );

            }        
        
        }
    }
}
