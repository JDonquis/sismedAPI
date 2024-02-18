<?php  

namespace App\Services;

use App\Events\InventoryLoteCreated;
use App\Events\OutputCreated;
use App\Exceptions\GeneralExceptions;
use App\Http\Resources\EntryCollection;
use App\Http\Resources\EntryResource;
use App\Http\Resources\OutputCollection;
use App\Models\Entry;
use App\Models\HierarchyEntity;
use App\Models\Inventory;
use App\Models\Organization;
use App\Models\Output;
use Carbon\Carbon;
use DB;

class OutputService extends ApiService
{   

    protected $snakeCaseMap = [

        'departureDate' => 'created_at',
        'productId' => 'product_id',
        'organizationId' => 'organization_id',
        'loteNumber' => 'lote_number',
        'expirationDate' => 'expiration_date',
        'authorityFullname' => 'authority_fullname',
        'authorityCi' => 'authority_ci',      
        'categoryId' => 'category_id',
        'typePresentationId' => 'type_presentation_id',
        'typeAdministrationId' => 'type_administration_id',
        'medicamentId' => 'medicament_id',
        'unitPerPackage' => 'unit_per_package',
        'concentrationSize' => 'concentration_size',
        'departureTime' => 'departure_time',
        'receiverFullname' => 'receiver_fullname',
        'receiverCi' => 'receiver_ci',


    ];

    private Inventory $inventoryModel;
    private Organization $organizationModel;
    private HierarchyEntity $entityModel; 

    public function __construct()
    {
        parent::__construct(new Output);
        $this->inventoryModel = new Inventory;
        $this->organizationModel = new Organization;
        $this->entityModel = new HierarchyEntity;
    }

    public function getData($paginateArray, $queryArray, $userEntityCode)
    {   
        $wantSeeOtherEntity = false;
        $codeToSee = $userEntityCode;
         $outputs = Output::select([
            'outputs.*',
            'hierarchy_entities.name as entity_name',
            'products.name as product_name',
            'categories.name as category_name',
            'type_administrations.name as type_administration_name',
            'type_presentations.name as type_presentation_name',
            'medicaments.name as medicament_name',
            'organizations.name as organization_name',
            'users.name as user_name',
        ])
        ->join('hierarchy_entities','outputs.entity_code','=','hierarchy_entities.code')
        ->join('products','outputs.product_id','=','products.id')
        ->join('categories','products.category_id','=','categories.id')
        ->join('type_presentations','products.type_presentation_id','=','type_presentations.id')
        ->join('type_administrations','products.type_administration_id','=','type_administrations.id')
        ->join('medicaments','products.medicament_id','=','medicaments.id')
        ->join('organizations','outputs.organization_id','=','organizations.id')
        ->join('users','outputs.user_id','=','users.id');

        
            foreach ($queryArray as $table => $array )
            {       

                if($table == 'search')
                    $table = 'outputs';
                
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
                            $outputs = $outputs->orWhere($table.'.'.$params[0],$params[1],$params[2]);    
                        else
                            $outputs = $outputs->where($table.'.'.$params[0],$params[1],$params[2]);    
                    }

                }
            }
        
        if($userEntityCode == '1' && $wantSeeOtherEntity == true)
        {   
            if($codeToSee !== '*')
                $outputs = $outputs->where('outputs.entity_code','=',$codeToSee);
        }
        else
            $outputs = $outputs->where('outputs.entity_code','=',$userEntityCode);


        $outputs = $outputs->orderBy($paginateArray['orderBy'],$paginateArray['orderDirection'])
        ->paginate($paginateArray['rowsPerPage'], ['*'], 'page', $paginateArray['page']);

        return $outputs;

    }

    public function create($data)
    {   
        $entityCode = auth()->user()->entity_code;
        
        $userId = auth()->user()->id;
        
        $products = $data['products'];
        
        $newOutputs = [];
        
        $newRegistersToInventory = [];
        

        $date = $this->splitDate($data['created_at']);
        
        $guide = $data['guide'];
        
        $organizations = $this->organizationModel->all();
        
        $organizationsMap = $this->createOrganizationMap($organizations);
        
        $entities = $this->entityModel->all();
        
        $entitiesMap = $this->createEntitiesMap($entities);
        
        $lastOutputCode = $this->model->where('entity_code',$entityCode)->orderBy('output_code','desc')->pluck('output_code')->first();

        if($lastOutputCode == null)
            $lastOutputCode = 0;
        
        $newOutputCode = $lastOutputCode + 1;



        if($guide == 'new')
            $guide = $this->generateNewGuideNumber();

        $lotesRequested = [];

        foreach ($products as $product)
        {   
            
            $lotesRequested[$product['loteNumber']]=$product['quantity'];


            $expirationDate = Carbon::parse($product['expirationDate'])->format('Y-m-d');

            $search = $entityCode . ' '
             . $entitiesMap[$entityCode] . ' ' 
             . $product['name'] . ' ' 
             . $product['categoryName'] . ' ' 
             . $product['typeAdministrationName'] . ' ' 
             . $product['typePresentationName'] . ' ' 
             . $product['medicamentName'] . ' ' 
             . $product['concentrationSize'] . ' ' 
             . $product['quantity'] . ' ' 
             . $organizationsMap[$data['organization_id']] . ' ' 
             . $guide . ' ' 
             . $expirationDate . ' ' 
             . $product['conditionName'] . ' ' 
             . $data['authority_fullname'] . ' ' 
             . $data['authority_ci'] . ' ' 
             . $data['receiver_fullname'] . ' ' 
             . $data['receiver_ci'] . ' ' 
             . $date['day'] . ' ' 
             . $date['month'] . ' ' 
             . $date['year'] . ' ' 
             . $product['loteNumber'] . ' ' 
             . $data['departure_time'] . ' ' 
             . $data['created_at']
             . $newOutputCode;

            $newOutputs[] = 
            [   
                'user_id' => $userId,
                'entity_code' => $entityCode,
                'output_code' => $newOutputCode,
                'product_id' => $product['productId'],
                'condition_id' => $product['conditionId'],
                'quantity' => $product['quantity'],
                'organization_id' => $data['organization_id'],
                'guide' => $guide,
                'authority_fullname' => $data['authority_fullname'],
                'authority_ci' => $data['authority_ci'],
                'receiver_fullname' => $data['receiver_fullname'],
                'receiver_ci' => $data['receiver_ci'],
                'expiration_date' => $expirationDate,
                'day' => $date['day'],
                'month' => $date['month'],
                'year' => $date['year'],
                'description' => $product['description'],
                'lote_number' => $product['loteNumber'],
                'departure_time' => $data['departure_time'],
                'created_at' => $data['created_at'],
                'search' => $search,
                
        ];

        }

        $this->validateQuantityFromLoteNumbers($lotesRequested);

        $this->model->insert($newOutputs);

        $productsAffected = [];

        foreach ($newOutputs as $output)
        {   

            if(!array_key_exists($output['entity_code'], $productsAffected))
            {
              $productsAffected[$output['entity_code']] = [$output['product_id']];
            }    

            else
            {
              if(!in_array($output['product_id'], $productsAffected[ $output['entity_code'] ] ) )
                  array_push($productsAffected[$output['entity_code'] ], $output['product_id'] );
            }

            $isEntity = $organizationsMap[$output['organization_id']] ?? 'nocode';

            OutputCreated::dispatch($output,$isEntity);

            if($isEntity !== 'nocode')
            {

                if(!array_key_exists($isEntity, $productsAffected))
                {
                  $productsAffected[$isEntity] = [$output['product_id']];
                }    

                else
                {
                  if(!in_array($isEntity, $productsAffected[ $isEntity ] ) )
                      array_push($productsAffected[$isEntity ], $output['product_id'] );
                }
                
            }


        }

        InventoryLoteCreated::dispatch($productsAffected);
        
        $outputs = $this->getOutpustWithGuideNumber($guide,$entityCode);
        $outputCollection = new OutputCollection($outputs);

        return ['message' => 'Salidas creadas exitosamente', 'outputs' => $outputCollection];

    }

    public function updateOutput($data,$guide)
    {   
        $organizations = $this->organizationModel->all();

        $organizationsMap = $this->createOrganizationMap($organizations);
        
        $entities = $this->entityModel->all();
        
        $entitiesMap = $this->createEntitiesMap($entities);
        
        $entityCode = auth()->user()->entity_code;

        $userId = auth()->user()->id;

        $products = $data['products'];
        
        $newOutputs = [];
        
        $date = $this->splitDate($data['created_at']);

        $lastOutputCode = $this->model->where('entity_code',$entityCode)->orderBy('output_code','desc')->pluck('output_code')->first();

        if($lastOutputCode == null)
            $lastOutputCode = 0;
        
        $newOutputCode = $lastOutputCode + 1;

        $dataGuide = $this->model->where('guide',$guide)->first();

        $lotesRequested = [];


        foreach ($products as $product)
        {   
            if(!array_key_exists('key', $product))
                continue;

            $expirationDate = Carbon::parse($product['expirationDate'])->format('Y-m-d');

            $lotesRequested[$product['loteNumber']]=$product['quantity'];

            $search = $entityCode . ' '
             . $entitiesMap[$entityCode] . ' ' 
             . $product['name'] . ' ' 
             . $product['categoryName'] . ' ' 
             . $product['typeAdministrationName'] . ' ' 
             . $product['typePresentationName'] . ' ' 
             . $product['medicamentName'] . ' ' 
             . $product['concentrationSize'] . ' ' 
             . $product['quantity'] . ' ' 
             . $organizationsMap[$dataGuide->organization_id] . ' ' 
             . $dataGuide->guide . ' ' 
             . $expirationDate . ' ' 
             . $product['conditionName'] . ' ' 
             . $dataGuide->authority_fullname . ' ' 
             . $dataGuide->authority_ci . ' ' 
             . $dataGuide->receiver_fullname . ' ' 
             . $dataGuide->receiver_ci . ' ' 
             . $dataGuide->day . ' ' 
             . $dataGuide->month . ' ' 
             . $dataGuide->year . ' ' 
             . $product['loteNumber'] . ' ' 
             . $dataGuide->departure_time . ' ' 
             . $dataGuide->created_at;

            $newOutputs[] = 
            [   
                'user_id' => $userId,
                'entity_code' => $entityCode,
                'output_code' => $newOutputCode,
                'product_id' => $product['productId'],
                'condition_id' => $product['conditionId'],
                'quantity' => $product['quantity'],
                'organization_id' => $dataGuide->organization_id,
                'guide' => $dataGuide->guide,
                'authority_fullname' => $dataGuide->authority_fullname,
                'authority_ci' => $dataGuide->authority_ci,
                'receiver_fullname' => $dataGuide->receiver_fullname,
                'receiver_ci' => $dataGuide->receiver_ci,
                'expiration_date' => $expirationDate,
                'day' => $dataGuide->day,
                'month' => $dataGuide->month,
                'year' => $dataGuide->year,
                'description' => $product['description'],
                'lote_number' => $product['loteNumber'],
                'created_at' => $data['created_at'],
                'departure_time' => $dataGuide->departure_time,
                'search' => $search,
                
        ];


        }

        $this->validateQuantityFromLoteNumbers($lotesRequested);

        $this->model->insert($newOutputs);

        $productsAffected = [];
        
        foreach ($newOutputs as $output)
        {   

            if(!array_key_exists($output['entity_code'], $productsAffected))
            {
              $productsAffected[$output['entity_code']] = [$output['product_id']];
            }    

            else
            {
              if(!in_array($output['product_id'], $productsAffected[ $output['entity_code'] ] ) )
                  array_push($productsAffected[$output['entity_code'] ], $output['product_id'] );
            }

            $isEntity = $organizationsMap[$output['organization_id']] ?? 'nocode';

            OutputCreated::dispatch($output,$isEntity);

            if($isEntity !== 'nocode')
            {

                if(!array_key_exists($isEntity, $productsAffected))
                {
                  $productsAffected[$isEntity] = [$output['product_id']];
                }    

                else
                {
                  if(!in_array($isEntity, $productsAffected[ $isEntity ] ) )
                      array_push($productsAffected[$isEntity ], $output['product_id'] );
                }
                
            }


        }

        InventoryLoteCreated::dispatch($productsAffected);

        $outputs = $this->getOutpustWithGuideNumber($dataGuide->guide,$entityCode);
        $outputCollection = new OutputCollection($outputs);
        
        return ['message' => 'Salidas actualizadas exitosamente', 'outputs' => $outputCollection];



    }

    public function splitDate($date)
    {
        $dateParsed = Carbon::parse($date);

        $splitDate['year'] = $dateParsed->year;
        $splitDate['month'] = $dateParsed->month;
        $splitDate['day'] = $dateParsed->day;

        return $splitDate;

    }


    public function insertInventory($outputData,$entityCode = null)
    {   
        if($entityCode == null)
            $entityCode = $outputData['entity_code'];


        $quantity = $outputData['quantity'];

        $register = $this->inventoryModel->updateOrCreate(    
        [
            'entity_code' => $entityCode,
            'product_id' => $outputData['product_id'],
            'lote_number' => $outputData['lote_number'],
            'condition_id' => $outputData['condition_id']
        ],
        [   
            'expiration_date' => $outputData['expiration_date'],
            'search' => $outputData['search'],

        ]
    );

        $register->increment('stock',$quantity);
        $register->increment('entries',$quantity);

    }

    private function createOrganizationMap($organizations)
    {   
        $response = [];
        foreach ($organizations as $organization)
        {
            $response[$organization->id] = $organization->code;
        }

        return $response;
    }

    private function createEntitiesMap($entities)
    {
        $response = [];
        foreach ($entities as $entity)
        {
            $response[$entity->code] = $entity->name;    
        }

        return $response;
    }

    public function getOutpustWithGuideNumber($guide,$entityCode)
    {
        $outputs = $this->model->where('guide',$guide)->where('entity_code',$entityCode)->with('product.category','product.presentation','product.administration','product.medicament','organization')->get();
        
        return $outputs;
    }

    public function generateNewGuideNumber()
    {
        $greatestGuide = $this->model->orderBy('guide','desc')->first();

        if(!isset($greatestGuide->guide))
            return 1;

        return $greatestGuide->guide + 1;
    }

    private function validateQuantityFromLoteNumbers($lotesRequested)
    {
        $lots = array_keys($lotesRequested);

        $inventoryLots = Inventory::whereIn('lote_number',$lots)->get()->pluck('stock','lote_number')->toArray();
        
       foreach ($lotesRequested as $loteNumber => $quantity)
       {
            if($quantity > $inventoryLots[$loteNumber])
                throw new GeneralExceptions('La cantidad solicitada supera a la cantidad del lote', 400);   
       }
    }

}
