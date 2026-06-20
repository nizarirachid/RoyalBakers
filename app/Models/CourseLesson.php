<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseLesson extends Model
{
    protected $fillable = [
        'course_id', 'title_ar', 'title_fr', 'title_en',
        'content_ar', 'content_fr', 'content_en',
        'video_url', 'attachment', 'sort_order', 'duration_minutes', 'is_free_preview',
    ];

    protected $casts = ['is_free_preview' => 'boolean'];

    public function course() { return $this->belongsTo(Course::class); }

    public function getTitleAttribute()
    {
        $lang = app()->getLocale();
        return $this->{"title_{$lang}"} ?? $this->title_ar;
    }
}
