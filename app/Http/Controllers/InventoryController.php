<?php

namespace App\Http\Controllers;

use App\Filters\InventoryQueryFilter;
use App\Http\Resources\InventoryCollection;
use App\Models\HierarchyEntity;
use App\Models\Organization;
use App\Services\ConfigurationProductService;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->inventoryService = new InventoryService;
        $this->queryFilter = new InventoryQueryFilter;
        $this->configurationProductService = new ConfigurationProductService;
    }

    public function index(Request $request)
    {   

        $queryArray = $this->queryFilter->transformParamsToQuery($request);
        
        $paginateArray = $this->queryFilter->getPaginateValues($request,'inventory_generals');

        $userEntityCode = auth()->user()->entity_code;

        $inventories = $this->inventoryService->getData($paginateArray,$queryArray,$userEntityCode);

        $inventoryCollection = new InventoryCollection($inventories);

        $total = $inventories->total();

        $canSeeOthers = $userEntityCode == '1'?true:false;

        $relation = $request->query('relation') ?? "false";
        
        if($relation == "true")
        {   
            if($canSeeOthers)
                $entities = HierarchyEntity::select('name','code')->get();

            $organizations = Organization::orderBy('id','desc')->get();
            $categories = $this->configurationProductService->getAllCategories();
            $typePresentations = $this->configurationProductService->getAllTypePresentations();
            $typeAdministrations = $this->configurationProductService->getAllTypeAdministrations();
            $medicaments = $this->configurationProductService->getAllTypeMedicaments();
        }
        

        return [
            
            'inventories' => $inventoryCollection,
            'categories' => $categories ?? null,
            'typePresentations' => $typePresentations ?? null,
            'typeAdministrations' => $typeAdministrations ?? null,
            'medicaments' => $medicaments ?? null,
            'entities' => $entities ?? null,
            'total' => $total, 
            'canSeeOthers' => $canSeeOthers,
            'message' => 'OK'
        ];

    }
}
