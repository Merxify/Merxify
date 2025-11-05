<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'slug' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'short_description' => ['nullable', 'string'],
            'meta_title' => ['required', 'string'],
            'meta_description' => ['required', 'string'],
            'meta_keywords' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'sku' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'weight' => ['required', 'numeric'],
            'quantity' => ['required', 'numeric'],
            'dimensions' => ['required', 'array'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
        ];
    }
}
