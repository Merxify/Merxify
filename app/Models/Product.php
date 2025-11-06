<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'sku',
        'type',
        'status',
        'price',
        'weight',
        'dimensions',
        'meta_data',
        'options',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'weight' => 'decimal:3',
            'dimensions' => 'array',
            'meta_data' => 'array',
            'options' => 'array',
        ];
    }

    /**
     * @return BelongsToMany<Category, $this>
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    /**
     * @return BelongsToMany<Attribute, $this>
     */
    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'product_attributes')->withPivot('value');
    }

    /**
     * @return HasMany<ProductVariant, $this>
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * @param  Builder<Product>  $query
     */
    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('status', 'active');
    }

    /**
     * @param  Builder<Product>  $query
     */
    #[Scope]
    protected function simple(Builder $query): void
    {
        $query->where('type', 'simple');
    }

    /**
     * @param  Builder<Product>  $query
     */
    #[Scope]
    protected function configurable(Builder $query): void
    {
        $query->where('type', 'configurable');
    }
}
