<?php  

namespace App\Services;

use App\Exceptions\GeneralExceptions;
use App\Http\Resources\UserResource;
use App\Models\HierarchyEntity;
use App\Models\User;
use App\Models\UserDeleteds;
use App\Services\ApiService;
use DB;
use Illuminate\Support\Facades\Auth;

class UserService extends ApiService
{	
	private User $userModel;
    private HierarchyEntity $hierarchyModel;


    protected $snakeCaseMap = [

    'entityCode' =>'entity_code',
    'lastName' => 'last_name',
    'phoneNumber' => 'phone_number',

    ];

    public function __construct()
    {
        $this->userModel = new User;
        $this->userDeletedModel = new UserDeleteds;
        $this->hierarchyModel = new HierarchyEntity;
        parent::__construct(new User);
    }

    public function getData($paginateArray, $queryArray, $userEntityCode)
    {   
        $wantSeeOtherEntity = false;
        $codeToSee = $userEntityCode;
         $users = User::select(['users.*','hierarchy_entities.name as entity_name'])
        ->join('hierarchy_entities','users.entity_code','=','hierarchy_entities.code');
        
            foreach ($queryArray as $table => $array )
            {       

                if($table == 'search')
                    $table = 'users';
                
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
                            $users = $users->orWhere($table.'.'.$params[0],$params[1],$params[2]);    
                        else
                            $users = $users->where($table.'.'.$params[0],$params[1],$params[2]);    
                    }

                }
            }
        
        if($userEntityCode == '1' && $wantSeeOtherEntity == true)
        {   
            if($codeToSee !== '*')
                $users = $users->where('users.entity_code','=',$codeToSee);
        }
        else
            $users = $users->where('users.entity_code','=',$userEntityCode);


        $users = $users->orderBy($paginateArray['orderBy'],$paginateArray['orderDirection'])
        ->paginate($paginateArray['rowsPerPage'], ['*'], 'page', $paginateArray['page']);

        return $users;

    }

    public function createUser($dataToCreateUser)
    {   

        // $password = $this->userModel->generateNewRandomPassword();
        $password = 'admin';
        $dataToCreateUser['username'] = $dataToCreateUser['ci'];
        $dataToCreateUser['password'] = $password;

        $this->userModel->fill($dataToCreateUser);
        $this->userModel->save();
        $this->userModel->fresh();

        $userWithFormat = new UserResource($this->userModel);
        
        //Envio de correo
        //Username  = ostisaludfalcon@gmail.com
        //Password = Ostifalcon01

    	return ['message' => 'Creado Exitosamente', 'newUser' => $userWithFormat];
    }

    public function updateUser($dataToUpdateUser,$user)
    {
        
        $user->fill($dataToUpdateUser);
        $user->save();
        $user->fresh();

        $userWithFormat = new UserResource($user);
        return ['message' => 'Actualizado Exitosamente', 'updatedUser' => $userWithFormat];

    }

    public function deleteUser($id)
    {
        $this->userModel->verifiIfExistsID($id);
        $user = $this->userModel->find($id);
        $user->user_id = $user->id;
        $this->userDeletedModel->fill($user->toArray());
        $this->userDeletedModel->save();

        $user->delete();

        return ['message' => 'Usuario eliminado exitosamente'];
    }


    public function isCurrentUserDeletingIdMatch($id)
    {
        $userID = Auth::id();
        
        if($userID == $id)
            throw new GeneralExceptions('No puede eliminarse asi mismo',500);  

    }
    

}
