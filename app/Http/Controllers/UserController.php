<?php

namespace App\Http\Controllers;

use App\Exceptions\GeneralExceptions;
use App\Filters\UsersQueryFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\HierarchyCollection;
use App\Http\Resources\HierarchyResource;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\HierarchyEntity;
use App\Models\User;
use App\Services\LoginService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;





class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private LoginService $loginService;
    private UserService $userService;
    private UsersQueryFilter $queryFilter;

    public function __construct()
    {
        $this->loginService = new LoginService;
        $this->userService = new UserService;
        $this->queryFilter = new UsersQueryFilter;

    }

    public function index(Request $request)
    {   
        $queryArray = $this->queryFilter->transformParamsToQuery($request);

        $paginateArray = $this->queryFilter->getPaginateValues($request,'users');

        $userEntityCode = auth()->user()->entity_code;

        $users = $this->userService->getData($paginateArray,$queryArray,$userEntityCode);

        $userCollection = new UserCollection($users);

        $total = $users->total();

        $canSeeOthers = $userEntityCode == '1'?true:false;

        $hierarchies = [];

        
        if($canSeeOthers)
        {
            $hierarchies = new HierarchyCollection(HierarchyEntity::all());

        }
        else
        {   
            $hierarchy = new HierarchyResource(HierarchyEntity::where('code',$userEntityCode)->first());
            array_push($hierarchies, $hierarchy);
        }




        return ['data' => $userCollection, 'total' => $total, 'entities' => $hierarchies, 'message' => 'OK'];

    }

    
    public function store(CreateUserRequest $request)
    {   
         
        try {
            
            $dataToCreateUser = $this->userService->convertToSnakeCase($request);
            $response = $this->userService->createUser($dataToCreateUser);

            return ['message' => $response['message'] ];
            
        } catch (GeneralExceptions $e) {
            
            if(null !== $e->getCustomCode())
            {
                return response()->json([
                'status' => false,
                'message' => $e->getMessage()
                ], $e->getCustomCode());

            }
            
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(User $user)
    {
        return new UserResource($user->with('hierarchy')->first());
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $dataToUpdateUser = $this->userService->convertToSnakeCase($request);

        try {

            $response = $this->userService->updateUser($dataToUpdateUser,$user);

            return ['message' => $response['message']];

            
        } catch (Exception $e) {
            
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
                ], $e->getCode());
        }
        
    }

    public function destroy($id)
    {
        try {

            $this->userService->isCurrentUserDeletingIdMatch($id);
            $response = $this->userService->deleteUser($id);
            
            return ['message' => $response['message']];

        }catch (GeneralExceptions $e) {
            
            
                return response()->json([
                'status' => false,
                'message' => $e->getMessage()
                ], $e->getCustomCode());

        }

    }

    public function login(LoginRequest $request)
    {
        try {

            $dataUser = ['username' => $request->username,'password' => $request->password];

            $this->loginService->tryLoginOrFail($dataUser);

            $token = $this->loginService->generateToken($dataUser);

            $name = auth()->user()->name;
            $lastName = auth()->user()->last_name;
            $entityCode = auth()->user()->entity_code;


            return response()->json([
                'status' => true,
                'message' => 'OK',
                'token' => $token,
                'userData' => ['name' => $name, 'lastName' => $lastName, 'entityCode' => $entityCode, 'username' => $dataUser['username']] 
            ], 200);

        }catch (GeneralExceptions $e)
        {
            if($e->getCustomCode() == 401)
            {
                return response()->json([
                'status' => false,
                'message' => $e->getMessage()
                ], 401);

            }
            
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function username()
    {
        return 'username';
    }

    public function failLogin()
    {
        return 'No tiene los permisos para ingresar a esta url';
    }

}

