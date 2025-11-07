<?php

namespace App\Models;

use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'currency',
        'total_amount',
        'billing_address',
        'shipping_address',
        'email',
        'phone',
        'notes',
        'shipped_at',
        'delivered_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'total_amount' => 'decimal:2',
        'billing_address' => 'array',
        'shipping_address' => 'array',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * @param  Builder<Order>  $query
     */
    #[Scope]
    protected function pending(Builder $query): void
    {
        $query->where('status', 'pending');
    }

    /**
     * @param  Builder<Order>  $query
     */
    #[Scope]
    protected function processing(Builder $query): void
    {
        $query->where('status', 'processing');
    }

    /**
     * @param  Builder<Order>  $query
     */
    #[Scope]
    protected function shipped(Builder $query): void
    {
        $query->where('status', 'shipped');
    }

    /**
     * @param  Builder<Order>  $query
     */
    #[Scope]
    protected function delivered(Builder $query): void
    {
        $query->where('status', 'delivered');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function isShipped(): bool
    {
        return $this->status === 'shipped';
    }

    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function generateOrderNumber(): string
    {
        return 'ORD-'.strtoupper(uniqid());
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($order) {
            if (! $order->order_number) {
                $order->order_number = $order->generateOrderNumber();
            }
        });
    }
}
