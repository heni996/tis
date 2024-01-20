<?php

namespace App\Http\Resources\BackOffice;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TouristResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'nationality' => $this->nationality,
            'passport_number' => $this->passport_number,
            'is_famous' => $this->is_famous,
            'email' => $this->email,
            'arrival_date' => $this->arrival_date,
            'departure_date' => $this->departure_date,
            'code' => $this->code,
            'is_valid' => $this->is_valid,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'hotels' => HotelResource::collection($this->whenLoaded('hotels')),
            // Add other fields or relationships as needed
        ];
    }
}
