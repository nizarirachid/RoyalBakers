<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'question_ar', 'question_fr', 'question_en',
        'answer_ar', 'answer_fr', 'answer_en',
        'category_id', 'sort_order', 'active',
    ];

    protected $casts = ['active' => 'boolean'];

    public function getQuestionAttribute()
    {
        $lang = app()->getLocale();
        return $this->{"question_{$lang}"} ?? $this->question_ar;
    }

    public function getAnswerAttribute()
    {
        $lang = app()->getLocale();
        return $this->{"answer_{$lang}"} ?? $this->answer_ar;
    }
}
