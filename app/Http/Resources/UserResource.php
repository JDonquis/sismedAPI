<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {   
        // $paginationData = $this->paginationData();
        
        return [
            'id' => $this->id,
            'entityCode' => $this->hierarchy->code, 
            'entityName' => $this->hierarchy->name,
            'charge'=> $this->charge,
            'username'=> $this->username,
            'name'=> $this->name,
            'lastName'=> $this->last_name,
            'ci'=> $this->ci,
            'phoneNumber'=> $this->phone_number,
            'address'=> $this->address,
            'email'=> $this->email,
        ];
    }

}
