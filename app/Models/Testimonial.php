<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'client_name', 'client_country', 'client_avatar',
        'testimonial_ar', 'testimonial_fr', 'testimonial_en',
        'rating', 'is_featured', 'active',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'active' => 'boolean',
    ];

    public function getTestimonialAttribute()
    {
        $lang = app()->getLocale();
        return $this->{"testimonial_{$lang}"} ?? $this->testimonial_ar;
    }
}
