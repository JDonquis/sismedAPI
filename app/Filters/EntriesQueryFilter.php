<?php 

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;


class EntriesQueryFilter extends ApiFilter
{
    protected $mainTable = 'entries';

    protected $allowedParams = [

        'entries' => ['entityCode','entryCode','quantity','guide','loteNumber','authorityFullname','authorityCi','day','month','year','description'],
        'entity' => ['code','name'],
        'product' => ['code','name','unitPerPackage','concentrationSize','search'],
        'typeAdministration' => ['name'],
        'typePresentation' => ['name'],
        'medicament' => ['name'],
        'organization' => ['name'],
        'condition' => ['id','name'],
        'category' => ['name'],
        'search' =>['all'],
    ];
    

    protected $tableMap = [

        'entries' => 'entries',
    	'entity' => 'hierarchy_entities',
    	'product' => 'products',
        'organization' => 'organizations',
        'condition' => 'conditions',
        'category' => 'categories',
        'typeAdministration' => 'type_administrations',
        'typePresentation' => 'type_presentations',
        'medicament' => 'medicaments'

     ];

     protected $columnMap = [

        'entityCode' => 'entity_code',
        'entryCode' => 'entry_code',
        'loteNumber' => 'lote_number',
        'authorityFullname' => 'authority_fullname',
        'authorityCi' => 'authority_ci',
        'unitPerPackage' => 'unit_per_package',
        'concentrationSize' => 'concentration_size',
     ];

     protected $orderByMap = 
     [
        'guide' => 'entries.guide',
        'arrivalDate' => 'entries.created_at',
        'arrivalTime' => 'entries.arrivalTime',
        'organizationName' => 'organizations.name',
        'productCode' => 'products.code',
        'productName' => 'products.name',
        'categoryName' => 'categories.name',
        'typePresentationName' => 'type_presentationstations.name',
        'typeAdministrationName' => 'type_administrations.name',
        'unitPerPackage' => 'products.unit_per_package',
        'concentrationSize' => 'products.concentration_size',
        'conditionName' => 'conditions.name',
        'expirationDate' => 'entries.expiration_date',
        'quantity' => 'entries.quantity',
        'authorityFullname' => 'entries.authority_fullname',
        'authorityCi' => 'entries.authority_ci',
     ];

}
