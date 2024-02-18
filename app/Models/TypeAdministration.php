<?php

namespace App\Models;

use App\Models\TypeAdministration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeAdministration extends Model
{
    use HasFactory;

    protected $fillable = [
        
        'name',
        'created_at',
        'updated_at'
    ];

}
