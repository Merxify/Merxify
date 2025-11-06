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
            'type' => 'user',
            'id' => $this->id,
            'attributes' => [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'emailVerified' => (bool) $this->email_verified_at,
                'phone' => $this->phone,
                'dateOfBirth' => $this->date_of_birth,
                'gender' => $this->gender,
                'group' => $this->group,
                'isActive' => $this->is_active,
                'lastLoginAt' => $this->last_login_at,
                'registeredAt' => $this->created_at,
            ],
            'links' => [
                'self' => route('users.show', ['user' => $this->id]),
            ],
        ];
    }
}
