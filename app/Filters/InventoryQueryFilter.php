<?php 

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;


class InventoryQueryFilter extends ApiFilter
{
    
    protected $mainTable = 'inventory_generals';

    protected $allowedParams = [

        'inventories' => ['entityCode','productId','stockExpired','stockBad','stockGood'],
        'entity' => ['code','name'],
        'product' => ['code','name','unitPerPackage','concentrationSize','search'],
        'typeAdministration' => ['name'],
        'typePresentation' => ['name'],
        'medicament' => ['name'],
        'condition' => ['id','name'],
        'category' => ['name'],
        'search' =>['all'],
    ];
    

    protected $tableMap = [

        'inventories' => 'inventory_generals',
    	'entity' => 'hierarchy_entities',
    	'product' => 'products',
        'condition' => 'conditions',
        'category' => 'categories',
        'typeAdministration' => 'type_administrations',
        'typePresentation' => 'type_presentations',
        'medicament' => 'medicaments'

     ];

     protected $columnMap = [

        'productId' => 'product_id',
        'entityCode' => 'entity_code',
        'stockExpired' => 'stock_expired',
        'stockBad' => 'stock_bad',
        'loteNumber' => 'lote_number',
        'authorityFullname' => 'authority_fullname',
        'authorityCi' => 'authority_ci',
        'unitPerPackage' => 'unit_per_package',
        'concentrationSize' => 'concentration_size',
     ];

     protected $orderByMap = 
     [
        'entries' => 'inventory_generals.entries',
        'outputs' => 'inventory_generals.outputs',
        'stock' => 'inventory_generals.stock',
        'stockBad' => 'inventory_generals.stock_bad',
        'stockExpired' => 'inventory_generals.stock_expired',
        'productCode' => 'products.code',
        'productName' => 'products.name',
        'categoryName' => 'categories.name',
        'typePresentationName' => 'type_presentationstations.name',
        'typeAdministrationName' => 'type_administrations.name',
     ];
}
