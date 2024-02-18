<?php  

namespace App\Services;

use App\Exceptions\GeneralExceptions;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginService
{	
	private User $userModel;

    public function __construct()
    {
        $this->userModel = new User;
    }

	public function tryLoginOrFail($dataUser)
	{
		if(!Auth::attempt($dataUser))
			throw new GeneralExceptions('Datos incorrectos',401);   
	}

	public function generateToken($dataUser)
	{
		$user = $this->userModel->findForUsername($dataUser['username']);

		$permission = $this->userModel->getPermissions($user->entity_code);

		$token = $user->createToken("api_token",[$permission])->plainTextToken;

		return $token;

	}


}
