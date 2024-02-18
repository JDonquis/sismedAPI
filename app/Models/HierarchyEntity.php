<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HierarchyEntity extends Model
{
    use HasFactory;

    protected $table = 'hierarchy_entities';

    protected $fillable = [
        'code',
        'name',
    ];

    public function user()
    {
        return $this->hasMany(User::class, 'entity_code', 'code');
    }   

    public function findCode($id)
    {   
        return self::where('id',$id)->first()->code;
    }

    public function verifiIfExistsID($id)
    {
        if (!self::where('id', $id)->exists()) 
        {
            throw new GeneralExceptions('El id no existe',404);  

        }
    }
  
}
