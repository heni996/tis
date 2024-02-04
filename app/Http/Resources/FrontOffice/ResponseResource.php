<?php

namespace App\Http\Resources\FrontOffice;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResponseResource extends JsonResource
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
            'value' => $this->value,
            'question_id' => $this->question_id,
            'guest_book_id' => $this->guest_book_id,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
            'question' => new QuestionResource($this->whenLoaded('question')),
            'guest_book' => new GuestBookResource($this->whenLoaded('guestBook')),
            // Add other fields or relationships as needed
        ];
    }
}
