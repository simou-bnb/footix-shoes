<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'size',
        'color',
        'sku',
        'stock',
        'price_override',
        'is_active',
    ];

    protected $casts = [
        'stock' => 'integer',
        'price_override' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getEffectivePriceAttribute(): float
    {
        return (float) ($this->price_override ?? $this->product->base_price);
    }

    public function getLabelAttribute(): string
    {
        return collect([$this->size, $this->color])->filter()->implode(' / ') ?: 'Standard';
    }

    public function inStock(): bool
    {
        return $this->is_active && $this->stock > 0;
    }
}
