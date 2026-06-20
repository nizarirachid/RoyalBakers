<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseEnrollment extends Model
{
    protected $fillable = [
        'course_id', 'user_id', 'price_paid', 'payment_status', 'progress',
        'completion_percentage', 'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'price_paid' => 'decimal:2',
    ];

    public function course() { return $this->belongsTo(Course::class); }
    public function user() { return $this->belongsTo(User::class); }
}
