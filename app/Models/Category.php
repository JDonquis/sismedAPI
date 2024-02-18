<?php

namespace App\Models;

use App\Models\TypePresentation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        
        'name',
        'created_at',
        'updated_at'
    ];


}
