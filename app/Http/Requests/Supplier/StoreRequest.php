<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'nullable|json',
            'email' => 'nullable|string|email',
            'phone_number' => 'nullable|string',
            'status' => 'required|enum:active,inactive',
            'notes' => 'nullable|string',
        ];
    }
}
