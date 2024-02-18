<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class EntryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {   

        return [
            "id"=> $this->id,
            "userId" => $this->user->id,
            "fullName" => $this->user->name . ' ' . $this->user->last_name,
            "entryCode" => $this->entry_code,
            "productId"=> $this->product->id,
            "productName"=> $this->product->name,
            "productCode"=> $this->product->code,
            "unitPerPackage"=> $this->product->unit_per_package,
            "concentrationSize"=> $this->product->concentration_size,
            "categoryId"=> $this->product->category->id,
            "categoryName"=> $this->product->category->name,
            "typePresentationId" => $this->product->presentation->id,
            "typePresentationName" => $this->product->presentation->name,
            "typeAdministrationId" => $this->product->administration->id,
            "typeAdministrationName" => $this->product->administration->name,
            "medicamentId" => $this->product->medicament->id,
            "medicamentName" => $this->product->medicament->name,
            "quantity"=> $this->quantity,
            "organizationId"=> $this->organization->id,
            "organizationName"=> $this->organization->name,
            "guide"=> $this->guide,
            "loteNumber"=> $this->lote_number,
            "expirationDate"=> $this->expiration_date,
            "conditionId"=> $this->condition_id,
            "conditionName"=> $this->condition->name,
            "authorityFullname"=> $this->authority_fullname,
            "authorityCi"=> $this->authority_ci,
            "day"=> $this->day,
            "month"=> $this->month,
            "year"=> $this->year,
            "description"=> $this->description,
            "arrivalDate"=> Carbon::parse($this->created_at)->format('Y-m-d'),
            "arrivalTime"=>$this->arrival_time,
        ];

    }
}
