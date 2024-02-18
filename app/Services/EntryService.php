<?php  

namespace App\Services;

use App\Events\EntryCreated;
use App\Events\InventoryLoteCreated;
use App\Exceptions\GeneralExceptions;
use App\Http\Resources\EntryCollection;
use App\Http\Resources\EntryResource;
use App\Models\Entry;
use App\Models\HierarchyEntity;
use App\Models\Inventory;
use App\Models\Organization;
use App\Models\Product;
use Carbon\Carbon;
use DB;

class EntryService extends ApiService
{   

    protected $snakeCaseMap = [

        'arrivalDate' => 'created_at',
        'productId' => 'product_id',
        'organizationId' => 'organization_id',
        'loteNumber' => 'lote_number',
        'expirationDate' => 'expiration_date',
        'conditionId' => 'condition_id',
        'authorityFullname' => 'authority_fullname',
        'authorityCi' => 'authority_ci',      
        'categoryId' => 'category_id',
        'typePresentationId' => 'type_presentation_id',
        'typeAdministrationId' => 'type_administration_id',
        'medicamentId' => 'medicament_id',
        'unitPerPackage' => 'unit_per_package',
        'concentrationSize' => 'concentration_size',
        'arrivalTime' => 'arrival_time',


    ];

    private Inventory $inventoryModel;
    private Organization $organizationModel;
    private HierarchyEntity $entityModel; 


    public function __construct()
    {
        parent::__construct(new Entry);
        $this->inventoryModel = new Inventory;
        $this->organizationModel = new Organization;
        $this->entityModel = new HierarchyEntity;

    }

    public function getData($paginateArray, $queryArray, $userEntityCode)
    {   
        $wantSeeOtherEntity = false;
        $codeToSee = $userEntityCode;
         $entries = Entry::select([
            'entries.*',
            'hierarchy_entities.name as entity_name',
            'products.name as product_name',
            'categories.name as category_name',
            'type_administrations.name as type_administration_name',
            'type_presentations.name as type_presentation_name',
            'medicaments.name as medicament_name',
            'organizations.name as organization_name',
            'conditions.name as condition_name',
            'users.name as user_name',
        ])
        ->join('hierarchy_entities','entries.entity_code','=','hierarchy_entities.code')
        ->join('products','entries.product_id','=','products.id')
        ->join('categories','products.category_id','=','categories.id')
        ->join('type_presentations','products.type_presentation_id','=','type_presentations.id')
        ->join('type_administrations','products.type_administration_id','=','type_administrations.id')
        ->join('medicaments','products.medicament_id','=','medicaments.id')
        ->join('organizations','entries.organization_id','=','organizations.id')
        ->join('conditions','entries.condition_id','=','conditions.id')
        ->join('users','entries.user_id','=','users.id');
        
            foreach ($queryArray as $table => $array )
            {       

                if($table == 'search')
                    $table = 'entries';
                
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
                            $entries = $entries->orWhere($table.'.'.$params[0],$params[1],$params[2]);    
                        else
                            $entries = $entries->where($table.'.'.$params[0],$params[1],$params[2]);    
                    }

                }
            }
        
        if($userEntityCode == '1' && $wantSeeOtherEntity == true)
        {   
            if($codeToSee !== '*')
                $entries = $entries->where('entries.entity_code','=',$codeToSee);
        }
        else
            $entries = $entries->where('entries.entity_code','=',$userEntityCode);


        $entries = $entries->orderBy($paginateArray['orderBy'],$paginateArray['orderDirection'])
        ->paginate($paginateArray['rowsPerPage'], ['*'], 'page', $paginateArray['page']);

        return $entries;

    }


    public function create($data)
    {   

        $entityCode = auth()->user()->entity_code;
        $userId = auth()->user()->id;
        $products = $data['products'];
        $newEntries = [];
        $newRegistersToInventory = [];
        $date = $this->splitDate($data['created_at']);

        $organizations = $this->organizationModel->all();
        $organizationsMap = $this->createOrganizationMap($organizations);
        $entities = $this->entityModel->all();
        $entitiesMap = $this->createEntitiesMap($entities);
        $lastEntryCode = $this->model->where('entity_code',$entityCode)->orderBy('entry_code','desc')->pluck('entry_code')->first();

        if($lastEntryCode == null)
            $lastEntryCode = 0;
        
        $newEntryCode = $lastEntryCode + 1;

        foreach ($products as $product)
        {   


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
            . $data['guide'] . ' ' 
            . $product['expirationDate'] . ' ' 
            . $product['conditionName'] . ' ' 
            . $data['authority_fullname'] . ' ' 
            . $data['authority_ci'] . ' ' 
            . $date['day'] . ' ' 
            . $date['month'] . ' ' 
            . $date['year'] . ' ' 
            . $product['description'] . ' ' 
            . $product['loteNumber'] . ' ' 
            . $data['arrival_time'] . ' ' 
            . $data['created_at'] . ' ' 
            . $newEntryCode;



            $newEntries[] = 
            [   
                'user_id' => $userId,
                'entity_code' => $entityCode,
                'entry_code' => $newEntryCode,
                'product_id' => $product['id'],
                'quantity' => $product['quantity'],
                'organization_id' => $data['organization_id'],
                'guide' => $data['guide'],
                'expiration_date' => $product['expirationDate'],
                'condition_id' => $product['conditionId'],
                'authority_fullname' => $data['authority_fullname'],
                'authority_ci' => $data['authority_ci'],
                'day' => $date['day'],
                'month' => $date['month'],
                'year' => $date['year'],
                'description' => $product['description'],
                'lote_number' => $product['loteNumber'],
                'arrival_time' => $data['arrival_time'],
                'created_at' => $data['created_at'],
                'search' => $search,
            ];

            
        }



        $this->model->insert($newEntries);

        $productsAffected = [];

        foreach ($newEntries as $entry)
        {   
            if(!array_key_exists($entry['entity_code'], $productsAffected))
            {
              $productsAffected[$entry['entity_code']] = [$entry['product_id']];
            }    

            else
            {
              if(!in_array($entry['product_id'], $productsAffected[ $entry['entity_code'] ] ) )
                  array_push($productsAffected[$entry['entity_code'] ], $entry['product_id'] );
            }

            EntryCreated::dispatch($entry);
        }

        InventoryLoteCreated::dispatch($productsAffected);


        return ['message' => 'Entradas creadas exitosamente'];

    }

    public function updateEntry($data,$guide)
    {   
        $organizations = $this->organizationModel->all();
        $organizationsMap = $this->createOrganizationMap($organizations);
        $entities = $this->entityModel->all();
        $entitiesMap = $this->createEntitiesMap($entities);
        $entityCode = auth()->user()->entity_code;
        $userId = auth()->user()->id;
        $products = $data['products'];
        $date = $this->splitDate($data['created_at']);
        $dataGuide = $this->model->where('guide',$guide)->first();

        $lastEntryCode = $this->model->where('entity_code',$entityCode)->orderBy('entry_code','desc')->pluck('entry_code')->first();

        if($lastEntryCode == null)
            $lastEntryCode = 0;
        
        $newEntryCode = $lastEntryCode + 1;

        $newEntries = [];
        foreach ($products as $product)
        {   
            if(!array_key_exists('key', $product))
                continue;

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
             . $organizationsMap[$dataGuide->organization_id] . ' ' 
             . $dataGuide->guide . ' ' 
             . $expirationDate . ' ' 
             . $product['conditionName'] . ' ' 
             . $dataGuide->authority_fullname . ' ' 
             . $dataGuide->authority_ci . ' ' 
             . $dataGuide->day . ' ' 
             . $dataGuide->month . ' ' 
             . $dataGuide->year . ' ' 
             . $product['description'] . ' ' 
             . $product['loteNumber'] . ' ' 
             . $dataGuide->arrival_time . ' ' 
             . $dataGuide->created_at
             . $newEntryCode;

            $newEntries[] = 
            [   
                'user_id' => $userId,
                'entity_code' => $entityCode,
                'entry_code' => $newEntryCode,
                'product_id' => $product['id'],
                'quantity' => $product['quantity'],
                'organization_id' => $dataGuide->organization_id,
                'guide' => $dataGuide->guide,
                'authority_fullname' => $dataGuide->authority_fullname,
                'authority_ci' => $dataGuide->authority_ci,
                'expiration_date' => $expirationDate,
                'condition_id' => $product['conditionId'],
                'day' => $dataGuide->day,
                'month' => $dataGuide->month,
                'year' => $dataGuide->year,
                'description' => $product['description'],
                'lote_number' => $product['loteNumber'],
                'arrival_time' => $dataGuide->arrival_time,
                'search' => $search,
                
        ];


        }


        $this->model->insert($newEntries);

        $productsAffected = [];

        foreach ($newEntries as $entry)
        {   

            if(!array_key_exists($entry['entity_code'], $productsAffected))
            {
              $productsAffected[$entry['entity_code']] = [$entry['product_id']];
            }    

            else
            {
              if(!in_array($entry['product_id'], $productsAffected[ $entry['entity_code'] ] ) )
                  array_push($productsAffected[$entry['entity_code'] ], $entry['product_id'] );
            }

            EntryCreated::dispatch($entry);

        }

        InventoryLoteCreated::dispatch($productsAffected);
        
        return ['message' => 'Entradas actualizadas exitosamente'];



    }

    
    public function delete($entry)
    {   
        $this->deleteInventory($entry);
        $entry->delete();
        return ['message' => 'Entrada eliminada exitosamente'];
    }
    
    public function splitDate($date)
    {
        $dateParsed = Carbon::parse($date);

        $splitDate['year'] = $dateParsed->year;
        $splitDate['month'] = $dateParsed->month;
        $splitDate['day'] = $dateParsed->day;

        return $splitDate;

    }

    private function deleteInventory($entryData)
    {
        $register = $this->inventoryModel
        ->where('entity_code',$entryData->entity_code)
        ->where('product_id',$entryData->product_id)
        ->where('lote_number',$entryData->lote_number)
        ->first();

        $register->decrement('stock',$entryData->quantity);
        $register->decrement('entries',$entryData->quantity);
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

}
