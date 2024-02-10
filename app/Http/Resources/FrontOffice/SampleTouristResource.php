<?php

namespace App\Http\Resources\FrontOffice;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SampleTouristResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'hotels' => HotelResource::collection($this->hotels)
        ];
    }
}
