<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

enum StatusEnum: string
{
    case Active = 'Active';
    case Inactive = 'Inactive';
}

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sku' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'weight' => 'required|numeric|min:0',
            'dimension' => 'nullable|json',
            'status' => ['required', new Enum(StatusEnum::class)],
            'notes' => 'nullable|string',
        ];
    }
}
