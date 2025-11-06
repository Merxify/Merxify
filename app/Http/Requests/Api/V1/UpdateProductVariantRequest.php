<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductVariantRequest extends FormRequest
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
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'product_id' => ['sometimes', 'exists:products,id'],
            'sku' => ['sometimes', 'string', 'unique:product_variants,sku'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'attribute_values' => ['nullable', 'array'],
        ];
    }
}
