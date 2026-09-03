<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artwork extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'category_id', 'title_ar', 'title_fr', 'title_en', 'slug',
        'description_ar', 'description_fr', 'description_en', 'main_image',
        'gallery_images', 'calligraphy_style', 'material', 'width_cm', 'height_cm',
        'price', 'is_for_sale', 'is_sold', 'is_featured', 'status', 'text_content', 'meta',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'meta' => 'array',
        'is_for_sale' => 'boolean',
        'is_sold' => 'boolean',
        'is_featured' => 'boolean',
        'width_cm' => 'decimal:2',
        'height_cm' => 'decimal:2',
        'price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->hasMany(ArtworkTag::class);
    }

    public function getTitleAttribute()
    {
        $lang = app()->getLocale();
        return $this->{"title_{$lang}"} ?? $this->title_ar;
    }

    public function getDescriptionAttribute()
    {
        $lang = app()->getLocale();
        return $this->{"description_{$lang}"} ?? $this->description_ar;
    }

    public function getSizeAttribute()
    {
        if ($this->width_cm && $this->height_cm) {
            return "{$this->width_cm} x {$this->height_cm} cm";
        }
        return null;
    }
}
