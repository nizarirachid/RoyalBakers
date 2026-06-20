<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'author_id', 'category_id', 'title_ar', 'title_fr', 'title_en', 'slug',
        'excerpt_ar', 'excerpt_fr', 'excerpt_en', 'content_ar', 'content_fr',
        'content_en', 'featured_image', 'gallery', 'status', 'published_at',
        'tags', 'meta_seo',
    ];

    protected $casts = [
        'gallery' => 'array',
        'tags' => 'array',
        'meta_seo' => 'array',
        'published_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class, 'post_id');
    }

    public function getTitleAttribute()
    {
        $lang = app()->getLocale();
        return $this->{"title_{$lang}"} ?? $this->title_ar;
    }

    public function getExcerptAttribute()
    {
        $lang = app()->getLocale();
        return $this->{"excerpt_{$lang}"} ?? $this->excerpt_ar;
    }

    public function getContentAttribute()
    {
        $lang = app()->getLocale();
        return $this->{"content_{$lang}"} ?? $this->content_ar;
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->where('published_at', '<=', now());
    }
}
