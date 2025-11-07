<?php

namespace App\Http\Resources\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'group' => $this->group,
            'is_active' => $this->is_active,
            'email_verified_at' => (bool) $this->email_verified_at,
            'lastLoginAt' => $this->last_login_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Conditional relationships
            'addresses' => AddressResource::collection($this->whenLoaded('addresses')),
        ];
    }
}
