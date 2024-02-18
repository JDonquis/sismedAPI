<?php

namespace App\Http\Controllers;

use App\Exceptions\GeneralExceptions;
use App\Filters\OutputsQueryFilter;
use App\Http\Requests\OutputRequest;
use App\Http\Resources\OutputCollection;
use App\Models\HierarchyEntity;
use App\Models\Organization;
use App\Models\Output;
use App\Services\ConfigurationProductService;
use App\Services\OutputService;
use Illuminate\Http\Request;

class OutputController extends Controller
{
    public function __construct()
    {
        $this->outputService = new OutputService;
        $this->queryFilter = new OutputsQueryFilter;
        $this->configurationProductService = new ConfigurationProductService;
    }

    public function index(Request $request)
    {   

        $queryArray = $this->queryFilter->transformParamsToQuery($request);

        $paginateArray = $this->queryFilter->getPaginateValues($request,'outputs');

        $userEntityCode = auth()->user()->entity_code;

        $outputs = $this->outputService->getData($paginateArray,$queryArray,$userEntityCode);

        $outputCollection = new OutputCollection($outputs);

        $total = $outputs->total();

        $relation = $request->query('relation') ?? "false";

        $canSeeOthers = $userEntityCode == '1'?true:false;
        
        if($relation == "true")
        {   
            if($canSeeOthers)
                $entities = HierarchyEntity::select('name','code')->get();

            $organizations = Organization::orderBy('id','desc')->get();
            $categories = $this->configurationProductService->getAllCategories();
            $typePresentations = $this->configurationProductService->getAllTypePresentations();
            $typeAdministrations = $this->configurationProductService->getAllTypeAdministrations();
            $medicaments = $this->configurationProductService->getAllTypeMedicaments();
            $years  = Output::orderBy('year','desc')->distinct()->pluck('year');
            $greatestGuide = Output::orderBy('guide','desc')->first();

        }
        

        return [
            
            'outputs' => $outputCollection,
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
            'greatestGuide' => $greatestGuide->guide ?? 0,
            'message' => 'OK'
        ];

    }

    public function store(OutputRequest $request)
    {   
         
        try {
            
            $dataToCreateOuputs = $this->outputService->convertToSnakeCase($request);

            $response = $this->outputService->create($dataToCreateOuputs);

            return ['message' => $response['message'], 'outputs' => $response['outputs'] ];
            
        } catch (GeneralExceptions $e) {
            

                return response()->json([
                'status' => false,
                'message' => $e->getMessage()
                ], $e->getCode());

            
            
        }
    }

    public function update(OutputRequest $request, $guide)
    {
        $dataToUpdateOuputs = $this->outputService->convertToSnakeCase($request);

        try {

            $response = $this->outputService->updateOutput($dataToUpdateOuputs,$guide);
            

            return ['message' => $response['message'], 'outputs' => $response['outputs']];

            
        } catch (Exception $e) {
            
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
                ], $e->getCode());
        }

    }

    public function generateOutputOrder($guide)
    {   

        $entityCode = auth()->user()->entity_code;
        $outputs = $this->outputService->getOutpustWithGuideNumber($guide,$entityCode);
        $outputCollection = new OutputCollection($outputs);
        
        return ['message' => 'OK', 'outputs' => $outputCollection];
    }

}
