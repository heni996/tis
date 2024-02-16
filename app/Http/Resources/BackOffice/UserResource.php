<?php

namespace App\Http\Resources\BackOffice;

use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // $hotel = Hotel::findOrFail();
        return [
            'id' => $this->id,
            'email' => $this->email,
            'roles' => $this->roles,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'hotel' => $this->hotel?->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
