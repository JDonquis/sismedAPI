<?php

namespace App\Models;

use App\Exceptions\GeneralExceptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDeleteds extends Model
{
    use HasFactory;

    protected $fillable = [
        'entity_code',
        'user_id',
        'username',
        'name',
        'last_name',
        'ci',
        'phone_number',
        'address',
        'email',
        'password',
        'status',
        'charge'
    ];

    public function verifiIfExistsID($id)
    {
        if (!self::where('id', $id)->exists()) 
        {
            throw new GeneralExceptions('El id no existe',404);  

        }
    }

    public function hierarchy()
    {
        return $this->belongsTo(HierarchyEntity::class, 'entity_code', 'code');
    }
}
