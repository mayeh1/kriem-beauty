<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'catalog_id', 'sku', 'model_no', 'name', 'slug',
        'category_id', 'brand_id', 'description', 'price_cents',
        'weight_g', 'unit_size_cm', 'carton_qty', 'carton_size_cm',
        'stock', 'is_featured', 'is_active', 'image_path',
    ];

    protected $casts = [
        'price_cents' => 'integer',
        'stock' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'weight_g' => 'decimal:1',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function priceFormatted(): string
    {
        return '$' . number_format($this->price_cents / 100, 2);
    }

    public function isLowStock(): bool
    {
        return $this->stock < 10;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
