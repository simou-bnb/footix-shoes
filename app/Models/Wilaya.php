<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wilaya extends Model
{
    protected $fillable = [
        'code',
        'name',
        'home_delivery_price',
        'stopdesk_delivery_price',
        'is_active',
    ];

    protected $casts = [
        'home_delivery_price' => 'decimal:2',
        'stopdesk_delivery_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function hasStopDesk(): bool
    {
        return $this->stopdesk_delivery_price !== null;
    }
}
