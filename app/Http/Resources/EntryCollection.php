<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Carbon\Carbon;

class EntryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $response = [];
        
        foreach ($this as $register)
        {
            if(array_key_exists($register->entry_code, $response))
            {
                $response[$register->entry_code]['products'][] = [

                            "id" => $register->product_id,
                            "loteNumber" =>$register->lote_number,
                            "quantity" => $register->quantity,
                            "expirationDate" => $register->expiration_date,
                            "description" =>$register->description,
                            "conditionId" =>$register->condition_id,
                            "conditionName" => $register->condition->name,
                            "code" => $register->product->code,
                            "name" => $register->product->name,
                            "categoryName" => $register->product->category->name,
                            "categoryId" => $register->product->category->id,
                            "typePresentationName" => $register->product->presentation->name,
                            "typePresentationId" => $register->product->presentation->id,
                            "typeAdministrationName" => $register->product->administration->name,
                            "typeAdministrationId" => $register->product->presentation->id,
                            "medicamentName" => $register->product->medicament->name,
                            "medicamentId" => $register->product->medicament->id,
                            "unitPerPackage"=> $register->product->unit_per_package,
                            "concentrationSize"=> $register->product->concentration_size,     


                ];

            }
            else
            {   
                
                $response[$register->entry_code] = 
                [   

                    "userId" => $register->user->id,
                    "entityName" => $register->entity->name,
                    "entityCode" => $register->entity->code,
                    "fullName" => $register->user->name . ' ' . $register->user->last_name,
                    "entryCode" => $register->entry_code,
                    "authorityFullname"=> $register->authority_fullname,
                    "authorityCi"=> $register->authority_ci,
                    "arrivalDate"=> Carbon::parse($register->created_at)->format('Y-m-d'),
                    "arrivalTime"=>$register->arrival_time,
                    "organizationId"=> $register->organization->id,
                    "organizationName"=> $register->organization->name,
                    "guide" => $register->guide,
                    "status" => $register->status,
                    'products' => [ 
                        [
                            "id" => $register->product_id,
                            "loteNumber" =>$register->lote_number,
                            "quantity" => $register->quantity,
                            "expirationDate" => $register->expiration_date,
                            "description" =>$register->description,
                            "conditionId" =>$register->condition_id,
                            "conditionName" => $register->condition->name,
                            "code" => $register->product->code,
                            "name" => $register->product->name,
                            "categoryName" => $register->product->category->name,
                            "categoryId" => $register->product->category->id,
                            "typePresentationName" => $register->product->presentation->name,
                            "typePresentationId" => $register->product->presentation->id,
                            "typeAdministrationName" => $register->product->administration->name,
                            "typeAdministrationId" => $register->product->presentation->id,
                            "medicamentName" => $register->product->medicament->name,
                            "medicamentId" => $register->product->medicament->id,
                            "unitPerPackage"=> $register->product->unit_per_package,
                            "concentrationSize"=> $register->product->concentration_size,
                        ]
                    ],
                ];

            }
        }
        return $response;    
    }
}

