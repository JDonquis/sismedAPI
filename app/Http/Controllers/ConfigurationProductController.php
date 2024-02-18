<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfigurationProductRequest;
use App\Services\ConfigurationProductService;
use Illuminate\Http\Request;

class ConfigurationProductController extends Controller
{
   public function __construct()
   {
        $this->configurationProductService = new ConfigurationProductService;
   }

   public function index()
   {   
        $categories = $this->configurationProductService->getAllCategories();
        $typePresentations = $this->configurationProductService->getAllTypePresentations();
        $typeAdministrations = $this->configurationProductService->getAllTypeAdministrations();
        $medicaments = $this->configurationProductService->getAllTypeMedicaments();

        $data = ['categories' => $categories, 'typePresentations' => $typePresentations, 'typeAdministrations' => $typeAdministrations,'medicaments' => $medicaments];

        return ['data' => $data, 'message' => 'OK'];

   }


   
    public function store(ConfigurationProductRequest $request,$type)
    {
        $data = $this->configurationProductService->create($request->name,$type);
        return ['data' => $data, 'message' => 'Creado exitosamente'];
           
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ConfigurationProductRequest $request,$type,$id)
    {
        $data = $this->configurationProductService->update($request->name,$type,$id);
        return ['data' => $data, 'message' => 'Actualizado exitosamente'];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($type,$id)
    {   
        try 
        {
            
            $this->configurationProductService->delete($id,$type);
            return ['data' => null, 'message' => 'Eliminado exitosamente'];

        } catch (Exception $e) 
        {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
                ], $e->getMessage());    
        }

    }
}
