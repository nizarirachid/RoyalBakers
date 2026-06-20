<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialPrice extends Model
{
    protected $fillable = ['name_ar', 'name_fr', 'name_en', 'price_per_unit', 'unit', 'active'];

    protected $casts = [
        'active' => 'boolean',
        'price_per_unit' => 'decimal:2',
    ];

    public function getNameAttribute()
    {
        $lang = app()->getLocale();
        return $this->{"name_{$lang}"} ?? $this->name_ar;
    }
}
