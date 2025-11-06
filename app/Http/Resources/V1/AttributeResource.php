<?php

namespace App\Http\Resources\V1;

use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Attribute */
class AttributeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'attribute',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'slug' => $this->slug,
                'type' => $this->type,
                'is_required' => $this->is_required,
                'is_filterable' => $this->is_filterable,
                'is_variant' => $this->is_variant,
                'options' => $this->options,
            ],
            'links' => [
                'self' => route('attributes.show', ['attribute' => $this->id]),
            ],
        ];
    }
}
