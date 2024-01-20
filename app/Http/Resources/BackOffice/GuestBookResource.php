<?php

namespace App\Http\Resources\BackOffice;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GuestBookResource extends JsonResource
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
            'client_first_name' => $this->client_first_name,
            'client_last_name' => $this->client_last_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'extra_comment' => $this->extra_comment,
            'hotel_id' => $this->hotel_id,
            'language' => $this->language,
            'country' => $this->country,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'hotel' => new HotelResource($this->whenLoaded('hotel')),
        ];
    }
}
