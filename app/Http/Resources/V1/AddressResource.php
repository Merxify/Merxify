<?php

namespace App\Http\Resources\V1;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Address */
class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'address',
            'id' => $this->id,
            'attributes' => [
                'user_id' => $this->user_id,
                'type' => $this->type,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'company' => $this->company,
                'address_line_1' => $this->address_line_1,
                'address_line_2' => $this->address_line_2,
                'city' => $this->city,
                'state' => $this->state,
                'postal_code' => $this->postal_code,
                'country_code' => $this->country_code,
                'is_default' => $this->is_default,
            ],
            'relationships' => [
                'user' => [
                    'data' => [
                        'type' => 'user',
                        'id' => $this->user_id,
                        'attributes' => [
                            'first_name' => $this->user->first_name,
                        ],
                    ],
                    'links' => [
                        'self' => route('users.show', ['user', $this->user->id]),
                    ],
                ],
            ],
            'links' => [
                'self' => route('addresses.show', ['address' => $this->id]),
            ],
        ];
    }
}
