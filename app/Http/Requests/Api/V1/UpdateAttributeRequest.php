<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttributeRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'max:255'],
            'slug' => ['sometimes', 'string', 'max:255', 'unique:attributes,slug'],
            'type' => ['sometimes', 'string', 'in:text,number,boolean,select,multiselect,date,textarea'],
            'is_required' => ['nullable', 'boolean'],
            'is_filterable' => ['nullable', 'boolean'],
            'is_variant' => ['nullable', 'boolean'],
        ];
    }
}
