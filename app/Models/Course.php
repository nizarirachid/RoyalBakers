<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'teacher_id', 'title_ar', 'title_fr', 'title_en', 'slug',
        'description_ar', 'description_fr', 'description_en', 'thumbnail',
        'type', 'level', 'calligraphy_style', 'price', 'duration_hours',
        'max_students', 'enrolled_count', 'status', 'start_date', 'end_date',
        'meeting_link', 'location', 'curriculum',
    ];

    protected $casts = [
        'curriculum' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'price' => 'decimal:2',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function lessons()
    {
        return $this->hasMany(CourseLesson::class)->orderBy('sort_order');
    }

    public function getTitleAttribute()
    {
        $lang = app()->getLocale();
        return $this->{"title_{$lang}"} ?? $this->title_ar;
    }

    public function hasAvailableSlots(): bool
    {
        if (!$this->max_students) return true;
        return $this->enrolled_count < $this->max_students;
    }
}
