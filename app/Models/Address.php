<?php

namespace App\Models;

use Database\Factories\AddressFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    /** @use HasFactory<AddressFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'first_name',
        'last_name',
        'company',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country_code',
        'is_default',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param  Builder<Address>  $query
     */
    #[Scope]
    protected function default(Builder $query): void
    {
        $query->where('is_default', true);
    }

    /**
     * @param  Builder<Address>  $query
     */
    #[Scope]
    protected function billing(Builder $query): void
    {
        $query->whereIn('type', ['billing', 'both']);
    }

    /**
     * @param  Builder<Address>  $query
     */
    #[Scope]
    protected function shipping(Builder $query): void
    {
        $query->whereIn('type', ['shipping', 'both']);
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function getFullAddressAttribute(): string
    {
        $address = $this->address_line_1;
        if ($this->address_line_2) {
            $address .= ', '.$this->address_line_2;
        }
        $address .= ', '.$this->city.', '.$this->state.' '.$this->postal_code;

        return $address;
    }
}
