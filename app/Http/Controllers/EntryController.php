<?php

namespace App\Http\Controllers;

use App\Exceptions\GeneralExceptions;
use App\Filters\EntriesQueryFilter;
use App\Http\Requests\EntryRequest;
use App\Http\Resources\EntryCollection;
use App\Models\Condition;
use App\Models\Entry;
use App\Models\HierarchyEntity;
use App\Models\Organization;
use App\Services\ConfigurationProductService;
use App\Services\EntryService;
use Illuminate\Http\Request;

class EntryController extends Controller
{
     public function __construct()
    {
        $this->entryService = new EntryService;
        $this->queryFilter = new EntriesQueryFilter;
        $this->configurationProductService = new ConfigurationProductService;

    }

    public function index(Request $request)
    {   

        $queryArray = $this->queryFilter->transformParamsToQuery($request);

        $paginateArray = $this->queryFilter->getPaginateValues($request,'entries');

        $userEntityCode = auth()->user()->entity_code;

        $entries = $this->entryService->getData($paginateArray,$queryArray,$userEntityCode);

        $entryCollection = new EntryCollection($entries);

        $total = $entries->total();

        $canSeeOthers = $userEntityCode == '1'?true:false;

        $relation = $request->query('relation') ?? "false";
        
        if($relation == "true")
        {   
            if($canSeeOthers)
                $entities = HierarchyEntity::select('name','code')->get();

            $conditions = Condition::orderBy('id','desc')->get();
            $organizations = Organization::orderBy('id','desc')->get();
            $categories = $this->configurationProductService->getAllCategories();
            $typePresentations = $this->configurationProductService->getAllTypePresentations();
            $typeAdministrations = $this->configurationProductService->getAllTypeAdministrations();
            $medicaments = $this->configurationProductService->getAllTypeMedicaments();
            $years  = Entry::orderBy('year','desc')->distinct()->pluck('year');

        }
        

        return [
            
            'entries' => $entryCollection,
            'categories' => $categories ?? null,
            'typePresentations' => $typePresentations ?? null,
            'typeAdministrations' => $typeAdministrations ?? null,
            'medicaments' => $medicaments ?? null,
            'conditions' => $conditions ?? null,
            'organizations' => $organizations ?? null,
            'entities' => $entities ?? null,
            'years' => $years ?? null,
            'total' => $total,
            'canSeeOthers' => $canSeeOthers, 
            'message' => 'OK'
        ];

    }

    public function store(EntryRequest $request)
    {   
         
        try {
            
            $dataToCreateEntries = $this->entryService->convertToSnakeCase($request);

            $response = $this->entryService->create($dataToCreateEntries);

            return ['message' => $response['message'] ];
            
        } catch (GeneralExceptions $e) {
            

                return response()->json([
                'status' => false,
                'message' => $e->getMessage()
                ], $e->getCode());

            
            
        }
    }

    public function update(EntryRequest $request, $guide)
    {
        $dataToUpdateEntries = $this->entryService->convertToSnakeCase($request);

        try {

            $response = $this->entryService->updateEntry($dataToUpdateEntries,$guide);
            

            return ['message' => $response['message']];

            
        } catch (Exception $e) {
            
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
                ], $e->getCode());
        }

    }

    public function destroy(Entry $entry)
    {
        try {

            $response = $this->entryService->delete($entry);
            
            return ['message' => $response['message']];

        }catch (GeneralExceptions $e) {
            
            
                return response()->json([
                'status' => false,
                'message' => $e->getMessage()
                ], $e->getCode());

        }
    }
}
