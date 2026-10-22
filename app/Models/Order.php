<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'number', 'email', 'name',
        'address_line1', 'address_line2', 'city', 'state', 'zip', 'country',
        'subtotal_cents', 'shipping_cents', 'total_cents',
        'status', 'stripe_session_id', 'paid_at', 'shipped_at',
    ];

    protected $casts = [
        'subtotal_cents' => 'integer',
        'shipping_cents' => 'integer',
        'total_cents' => 'integer',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateNumber(): string
    {
        $last = static::max('id') ?? 0;
        return 'KR-' . str_pad($last + 1, 6, '0', STR_PAD_LEFT);
    }

    public function subtotalFormatted(): string
    {
        return '$' . number_format($this->subtotal_cents / 100, 2);
    }

    public function shippingFormatted(): string
    {
        return '$' . number_format($this->shipping_cents / 100, 2);
    }

    public function totalFormatted(): string
    {
        return '$' . number_format($this->total_cents / 100, 2);
    }
}
