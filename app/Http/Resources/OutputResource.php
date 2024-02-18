<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class OutputResource extends JsonResource
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
            "outputCode" => $this->output_code,
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
            "authorityFullname"=> $this->authority_fullname,
            "authorityCi"=> $this->authority_ci,
            "receiverFullname" => $this->receiver_fullname,
            "receiverCi" => $this->receiver_ci,
            "expirationDate" => $this->expiration_date,
            "day"=> $this->day,
            "month"=> $this->month,
            "year"=> $this->year,
            "description"=> $this->description,
            "departureDate"=> Carbon::parse($this->created_at)->format('Y-m-d'),
            "departureTime"=>$this->departure_time,
        ];
    }
}
