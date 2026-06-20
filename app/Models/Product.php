<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'name_ar', 'name_fr', 'name_en', 'slug',
        'description_ar', 'description_fr', 'description_en', 'main_image',
        'gallery_images', 'type', 'price', 'sale_price', 'currency', 'stock',
        'unlimited_stock', 'is_featured', 'is_downloadable', 'download_file',
        'status', 'width_cm', 'height_cm', 'weight_kg', 'meta',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'meta' => 'array',
        'unlimited_stock' => 'boolean',
        'is_featured' => 'boolean',
        'is_downloadable' => 'boolean',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getNameAttribute()
    {
        $lang = app()->getLocale();
        return $this->{"name_{$lang}"} ?? $this->name_ar;
    }

    public function getEffectivePriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    public function isAvailable(): bool
    {
        return $this->status === 'active' && ($this->unlimited_stock || $this->stock > 0);
    }
}
