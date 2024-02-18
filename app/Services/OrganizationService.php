<?php  

namespace App\Services;

use App\Exceptions\GeneralExceptions;
use App\Http\Resources\UserResource;
use App\Models\Entry;
use App\Models\HierarchyEntity;
use App\Models\Organization;
use App\Models\Output;
use App\Models\User;
use App\Models\UserDeleteds;
use App\Services\ApiService;
use DB;
use Illuminate\Support\Facades\Auth;

class OrganizationService extends ApiService
{	

    protected $model;
    protected $snakeCaseMap = [

    'authorityFullname' =>'authority_fullname',
    'authorityCi' => 'authority_ci',
    ];

    public function __construct()
    {
        parent::__construct(new Organization);
    }

    public function getData($paginateArray, $queryArray)
    {   
        $wantSeeOtherEntity = false;
         $organizations = Organization::select(['organizations.*']);
        
            foreach ($queryArray as $table => $array )
            {       

                if($table == 'search')
                    $table = 'organizations';
                
                foreach ($array as $params)
                {   

                        if(isset($params[3]))
                            $organizations = $organizations->orWhere($table.'.'.$params[0],$params[1],$params[2]);    
                        else
                            $organizations = $organizations->where($table.'.'.$params[0],$params[1],$params[2]);    

                }
            }
    

        $organizations = $organizations->orderBy($paginateArray['orderBy'],$paginateArray['orderDirection'])
        ->paginate($paginateArray['rowsPerPage'], ['*'], 'page', $paginateArray['page']);

        return $organizations;

    }

    public function create($dataToCreateOrganization)
    {   


        $dataToCreateOrganization['search'] = 
        $dataToCreateOrganization['name'] . ' ' .
        $dataToCreateOrganization['authority_ci'] . ' ' .
        $dataToCreateOrganization['authority_fullname']; 

        $this->model->fill($dataToCreateOrganization);
        $this->model->save();
        $this->model->fresh();

    	return ['message' => 'Creado Exitosamente'];
    }

    public function update($dataToUpdateOrganization,$organization)
    {
        $dataToUpdateOrganization['search'] = 
        $dataToUpdateOrganization['name'] . ' ' .
        $dataToUpdateOrganization['authority_ci'] . ' ' .
        $dataToUpdateOrganization['authority_fullname'];

        $organization->fill($dataToUpdateOrganization);
        $organization->save();
        $organization->fresh();

        return ['message' => 'Actualizado Exitosamente'];

    }

    public function delete($organization)
    {   
        $entry = Entry::where('organization_id',$organization->id)->first();
        $output = Output::where('organization_id',$organization->id)->first();

        if(isset($entry->id))
            throw new GeneralExceptions('Existe una entrada con esta organizacion no puede ser eliminado',406);

         if(isset($output->id))
            throw new GeneralExceptions('Existe una salida con esta organizacion no puede ser eliminado',406);

        $organization->delete();

        return ['message' => 'Eliminado con exito'];

    }


    public function isCurrentUserDeletingIdMatch($id)
    {
        $userID = Auth::id();
        
        if($userID == $id)
            throw new GeneralExceptions('No puede eliminarse asi mismo',500);  

    }
    

}
