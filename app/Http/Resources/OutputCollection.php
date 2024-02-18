<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Carbon\Carbon;
class OutputCollection extends ResourceCollection
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
            if(array_key_exists($register->output_code, $response))
            {
                $response[$register->output_code]['products'][] = [

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
                
                $response[$register->output_code] = 
                [   

                    "userId" => $register->user->id,
                    "outputCode" => $register->output_code,
                    "entityName" => $register->entity->name,
                    "entityCode" => $register->entity->code,
                    "fullName" => $register->user->name . ' ' . $register->user->last_name,
                    "outputCode" => $register->output_code,
                    "authorityFullname"=> $register->authority_fullname,
                    "authorityCi"=> $register->authority_ci,
                    "receiverFullname"=> $register->receiver_fullname,
                    "receiverCi"=> $register->receiver_ci,
                    "departureDate"=> Carbon::parse($register->created_at)->format('Y-m-d'),
                    "departureTime"=>$register->departure_time,
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
