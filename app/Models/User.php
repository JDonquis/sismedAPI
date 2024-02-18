<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Exceptions\GeneralExceptions;
use App\Models\HierarchyEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'entity_code',
        'username',
        'name',
        'last_name',
        'ci',
        'phone_number',
        'address',
        'email',
        'password',
        'charge'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];


    public function hierarchy()
    {
        return $this->belongsTo(HierarchyEntity::class, 'entity_code', 'code');
    }

    public function findForUsername($username)
    {
        return self::where('username',$username)->first();
    }

    public function getPermissions($entity_code)
    {
        return $entity_code == '1'?'origin':'branch';
    }

    public function verifiIfExistsID($id)
    {
        if (!self::where('id', $id)->exists()) 
        {
            throw new GeneralExceptions('El id no existe',404);  

        }
    }

    public function generateNewRandomPassword()
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $newPassword = substr(str_shuffle($permitted_chars), 0, 15);
        return $newPassword;
    }
}
