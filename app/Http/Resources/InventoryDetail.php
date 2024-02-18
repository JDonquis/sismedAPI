<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryDetail extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {   

        return [
            'id' => $this->id,
            'entityCode' => $this->entity_code,
            'productId' => $this->product_id,
            'loteNumber' => $this->lote_number,
            'expirationDate' => $this->expiration_date,
            'conditionId' => $this->condition_id,
            'conditionName' => $this->condition->name,
            'stock' => $this->stock,
            'entries' => $this->entries,
            'outputs' => $this->outputs,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'loteKey' => $this->lote_number . '-' .$this->condition_id,


        ];
    }
}
