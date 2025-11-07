<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            // Billing Address
            'billing_address' => ['required', 'array'],
            'billing_address.first_name' => ['required', 'string', 'max:255'],
            'billing_address.last_name' => ['required', 'string', 'max:255'],
            'billing_address.company' => ['nullable', 'string', 'max:255'],
            'billing_address.address_line_1' => ['required', 'string', 'max:255'],
            'billing_address.address_line_2' => ['nullable', 'string', 'max:255'],
            'billing_address.city' => ['required', 'string', 'max:255'],
            'billing_address.state_province' => ['required', 'string', 'max:255'],
            'billing_address.postal_code' => ['required', 'string', 'max:20'],
            'billing_address.country_code' => ['required', 'string', 'size:2'],

            // Shipping Address
            'shipping_address' => ['required', 'array'],
            'shipping_address.first_name' => ['required', 'string', 'max:255'],
            'shipping_address.last_name' => ['required', 'string', 'max:255'],
            'shipping_address.company' => ['nullable', 'string', 'max:255'],
            'shipping_address.address_line_1' => ['required', 'string', 'max:255'],
            'shipping_address.address_line_2' => ['nullable', 'string', 'max:255'],
            'shipping_address.city' => ['required', 'string', 'max:255'],
            'shipping_address.state_province' => ['required', 'string', 'max:255'],
            'shipping_address.postal_code' => ['required', 'string', 'max:20'],
            'shipping_address.country_code' => ['required', 'string', 'size:2'],

            // Optional
            'notes' => ['nullable', 'string', 'max:1000'],
            'store_id' => ['nullable', 'exists:stores,id'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'billing_address.required' => 'Billing address is required',
            'shipping_address.required' => 'Shipping address is required',
            'billing_address.*.required' => 'This billing address field is required',
            'shipping_address.*.required' => 'This shipping address field is required',
            'payment_method.required' => 'Payment method is required',
            'payment_method.in' => 'Invalid payment method selected',
        ];
    }
}
