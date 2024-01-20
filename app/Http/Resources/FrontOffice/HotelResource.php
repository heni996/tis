<?php

namespace App\Http\Resources\FrontOffice;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelResource extends JsonResource
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
            'email' => $this->email,
            'roles' => $this->roles,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'hotel' => new HotelResource($this->whenLoaded('hotel')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // Add other fields or relationships as needed
        ];
    }
}
