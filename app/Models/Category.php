<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name_ar', 'name_fr', 'name_en', 'slug', 'description_ar',
        'description_fr', 'description_en', 'image', 'type', 'sort_order',
        'active', 'parent_id',
    ];

    protected $casts = ['active' => 'boolean'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function artworks()
    {
        return $this->hasMany(Artwork::class);
    }

    public function getNameAttribute()
    {
        $lang = app()->getLocale();
        return $this->{"name_{$lang}"} ?? $this->name_ar;
    }
}
