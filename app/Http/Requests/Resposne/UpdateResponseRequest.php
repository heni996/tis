<?php

namespace App\Http\Requests\Resposne;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResponseRequest extends FormRequest
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
            'value' => 'required|string',
            'question_id' => 'required|exists:questions,id',
            'guest_book_id' => 'required|exists:guest_books,id',
        ];
    }
}
