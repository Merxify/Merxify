<?php

namespace App\Models;

use Database\Factories\AttributeFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Attribute extends Model
{
    /** @use HasFactory<AttributeFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'type',
        'is_required',
        'is_filterable',
        'is_variant',
        'options',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'is_required' => 'boolean',
        'is_filterable' => 'boolean',
        'is_variant' => 'boolean',
        'options' => 'array',
    ];

    /**
     * @return BelongsToMany<Product, $this>
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_attributes')->withPivot('value');
    }

    /**
     * @param  Builder<Attribute>  $query
     */
    #[Scope]
    protected function variant(Builder $query): void
    {
        $query->where('is_variant', true);
    }

    /**
     * @param  Builder<Attribute>  $query
     */
    #[Scope]
    protected function filterable(Builder $query): void
    {
        $query->where('is_filterable', true);
    }
}
