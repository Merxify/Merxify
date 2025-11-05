<?php

namespace App\Http\Resources\V1;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Category */
class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'category',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description,
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'meta_keywords' => $this->meta_keywords,
                'is_active' => $this->is_active,
                'parent_id' => $this->parent_id,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
            'relationships' => $this->when(
                $this->parent !== null,
                function () {
                    return [
                        'parent' => [
                            'data' => [
                                'type' => 'category',
                                'id' => $this->parent_id,
                                'attributes' => [
                                    'name' => $this->parent->name,
                                ],
                            ],
                            'links' => [
                                'self' => route('categories.show', $this->parent_id),
                            ],
                        ],
                    ];
                }
            ),
            'links' => [
                'self' => route('categories.show', ['category' => $this->id]),
            ],
        ];
    }
}
