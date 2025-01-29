<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'address' => 'nullable|json',
            'email' => 'nullable|string|email',
            'phone_number' => 'nullable|string',
            'status' => 'nullable|enum:active,inactive',
            'notes' => 'nullable|string',
        ];
    }
}
