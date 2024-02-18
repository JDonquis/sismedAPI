<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [

        'name',
        'code',
        'authority_fullname',
        'authority_ci',
        'search'
    ];
}
