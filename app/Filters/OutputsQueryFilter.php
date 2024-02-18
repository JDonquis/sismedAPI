<?php 

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;


class OutputsQueryFilter extends ApiFilter
{
    protected $mainTable = 'outputs';

    protected $allowedParams = [

        'outputs' => ['entityCode','outputCode','quantity','guide','loteNumber','authorityFullname','authorityCi','day','month','year','description','receiverFullname','receiverCi'],
        'entity' => ['code','name'],
        'product' => ['code','name','unitPerPackage','concentrationSize'],
        'typeAdministration' => ['name'],
        'typePresentation' => ['name'],
        'medicament' => ['name'],
        'organization' => ['name'],
        'category' => ['name'],
        'search' =>['all'],
    ];
    

    protected $tableMap = [

        'outputs' => 'outputs',
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
        'outputCode' => 'output_code',
        'loteNumber' => 'lote_number',
        'authorityFullname' => 'authority_fullname',
        'authorityCi' => 'authority_ci',
        'unitPerPackage' => 'unit_per_package',
        'concentrationSize' => 'concentration_size',
        'receiverFullname' => 'receiver_fullname',
        'receiverCi' => 'receiver_ci',

     ];

     protected $orderByMap = 
     [
        'guide' => 'outputs.guide',
        'departureDate' => 'outputs.created_at',
        'departureTime' => 'outputs.departureTime',
        'organizationName' => 'organizations.name',
        'productCode' => 'products.code',
        'productName' => 'products.name',
        'categoryName' => 'categories.name',
        'typePresentationName' => 'type_presentationstations.name',
        'typeAdministrationName' => 'type_administrations.name',
        'unitPerPackage' => 'products.unit_per_package',
        'concentrationSize' => 'products.concentration_size',
        'conditionName' => 'conditions.name',
        'expirationDate' => 'outputs.expiration_date',
        'quantity' => 'outputs.quantity',
        'receiverFullname' => 'outputs.receiver_fullname',
        'receiverCi' => 'outputs.receiver_ci',
        'authorityFullname' => 'outputs.authority_fullname',
        'authorityCi' => 'outputs.authority_ci',
     ];

}
