<?php

namespace App\Http\Controllers;

use App\Exceptions\GeneralExceptions;
use App\Http\Requests\CancellationRequest;
use App\Services\CancellationService;
use Illuminate\Http\Request;

class CancellationController extends Controller
{      

    private CancellationService $cancellationService;

    public function __construct()
    {
        $this->cancellationService = new CancellationService;
    }

    public function index(CancellationRequest $request, $type)
    {   
        try {
            
            $this->cancellationService->create($request,$type);
            return ['message' => 'OK'];
            
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
        
    }    

