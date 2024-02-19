<?php

namespace App\Http\Requests\Tourist;

use Illuminate\Foundation\Http\FormRequest;

class CreateTouristRequest extends FormRequest
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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'nationality' => 'required|string',
            'passport_number' => 'required|string',
            'is_famous' => 'boolean',
            'email' => 'required|email|unique:tourists,email',
            'arrival_date' => 'required|date',
            'departure_date' => 'required|date|after:arrival_date',
            'hotel_ids' => 'nullable',
            'image' => 'nullable|file'
            // 'hotel_ids.*' => 'exists:hotels,id',
            // 'code' => 'required|string|unique:tourists,code',
            // 'is_valid' => 'boolean',
        ];
    }
}
