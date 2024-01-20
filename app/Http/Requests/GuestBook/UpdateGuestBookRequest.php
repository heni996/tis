<?php

namespace App\Http\Requests\GuestBook;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGuestBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_first_name' => 'required|string',
            'client_last_name' => 'required|string',
            'email' => 'required|email',
            'phone_number' => 'required|string',
            'extra_comment' => 'nullable|string',
            'hotel_id' => 'required|exists:hotels,id',
            'language' => 'required|string',
            'country' => 'required|string',
            // Add other rules as needed
        ];
    }
}
