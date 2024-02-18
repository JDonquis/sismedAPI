<?php 

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;


class ProductsQueryFilter extends ApiFilter
{   

    protected $mainTable = 'products';

    protected $allowedParams = [

        'product' => ['code','name','unitPerPackage','concentrationSize'],
        'typeAdministration' => ['name'],
        'medicament' => ['name'],
        'typePresentation' => ['name'],
        'organization' => ['name'],
        'condition' => ['id','name'],
        'category' => ['name'],
        'search' =>['all'],
    ];

    protected $tableMap = [

        'product' => 'products',
        'condition' => 'conditions',
        'category' => 'categories',
        'typeAdministration' => 'type_administrations',
        'typePresentation' => 'type_presentations',
        'medicament' => 'medicaments',
     ];

    protected $columnMap = [

        'unitPerPackage' => 'unit_per_package',
        'concentrationSize' => 'concentration_size',
     ];

     protected $orderByMap = 
     [
        'code' => 'products.code',
        'name' => 'products.name',
        'categoryName' => 'categories.name',
        'medicamentName' => 'medicaments.name',
        'typePresentationName' => 'type_presentations.name',
        'typeAdministrationName' => 'type_administrations.name',
        'unitPerPackage' => 'products.unit_per_package',
        'concentrationSize' => 'products.concentration_size'
     ];


}

