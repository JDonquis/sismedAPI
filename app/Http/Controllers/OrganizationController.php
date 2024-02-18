<?php

namespace App\Http\Controllers;

use App\Exceptions\GeneralExceptions;
use App\Filters\OrganizationQueryFilter;
use App\Http\Requests\OrganizationRequest;
use App\Http\Resources\OrganizationCollection;
use App\Models\Organization;
use App\Services\OrganizationService;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{

    private OrganizationService $organizationService;
    private OrganizationQueryFilter $queryFilter;

    public function __construct()
    {
        $this->organizationService = new OrganizationService;
        $this->queryFilter = new OrganizationQueryFilter;

    }

    public function index(Request $request)
    {
        $queryArray = $this->queryFilter->transformParamsToQuery($request);

        $paginateArray = $this->queryFilter->getPaginateValues($request,'organizations');

        $organizations = $this->organizationService->getData($paginateArray,$queryArray);

        $organizationCollection = new OrganizationCollection($organizations);

        $total = $organizations->total();


        return ['data' => $organizationCollection, 'total' => $total, 'message' => 'OK'];

    }

    public function store(OrganizationRequest $request)
    {
        try {
            
            $dataToCreateOrganization = $this->organizationService->convertToSnakeCase($request);
            $response = $this->organizationService->create($dataToCreateOrganization);

            return ['message' => $response['message'] ];
            
        } catch (GeneralExceptions $e) {
            
            if(null !== $e->getCustomCode())
            {
                return response()->json([
                'status' => false,
                'message' => $e->getMessage()
                ], $e->getCustomCode());

            }
            
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(OrganizationRequest $request, Organization $organization)
    {
            $dataToUpdateOrganization = $this->organizationService->convertToSnakeCase($request);

        try {

            $response = $this->organizationService->update($dataToUpdateOrganization,$organization);

            return ['message' => $response['message']];

            
        } catch (GeneralExceptions $e) {
            
            if(null !== $e->getCustomCode())
            {
                return response()->json([
                'status' => false,
                'message' => $e->getMessage()
                ], $e->getCustomCode());

            }
            
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
        
    }

    public function destroy(Organization $organization)
    {
        try {

            $response = $this->organizationService->delete($organization);
            
            return ['message' => $response['message']];

        }catch (GeneralExceptions $e) {
            
            
                return response()->json([
                'status' => false,
                'message' => $e->getMessage()
                ], $e->getCustomCode());

        }

    }
}
