<?php 

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;


class UsersQueryFilter extends ApiFilter
{
    
    protected $mainTable = 'users';

    protected $allowedParams = [

        'user' => ['entityCode','username','name','lastName','charge','ci','phoneNumber','address','email'],
        'entity' => ['code','name'],
        'search' =>['all'],
    ];
    

    protected $tableMap = [

        'user' => 'users',
        'entity' => 'hierarchy_entities',

     ];

    protected $columnMap = [

    	'entityCode' => 'entity_code',
    	'lastName' => 'last_name',
    	'phoneNumber' => 'phone_number',
     
     ];

     protected $orderByMap = 
     [
        'entityName' => 'hierarchy_entities.name',
        'name' => 'users.name',
        'lastName' => 'users.last_name',
        'charge' => 'users.charge',
        'ci' => 'users.ci',
        'address' => 'users.address',
        'phoneNumber' => 'users.phone_number',
        'email' => 'users.email'
     ];

    

}

