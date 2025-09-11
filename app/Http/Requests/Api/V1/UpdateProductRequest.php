<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
     * @return array<string, ValidationRule|string[]|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|string',
            'slug' => 'sometimes|string|unique:products,slug',
            'description' => 'string',
            'price' => 'sometimes|numeric',
            'stock_qty' => 'numeric',
            'sku' => 'string',
            'is_active' => 'boolean',
        ];

    }
}
