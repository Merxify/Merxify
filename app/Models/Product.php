<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $fillable = [
        'name',
        'description',
        'short_description',
        'slug',
        'sku',
        'price',
        'weight',
        'quantity',
        'dimensions',
        'category',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'category_id',
    ];

    protected function casts(): array
    {
        return [
            'dimensions' => 'array',
        ];
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
