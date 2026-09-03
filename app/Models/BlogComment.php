<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    protected $fillable = [
        'post_id', 'user_id', 'author_name', 'author_email',
        'content', 'parent_id', 'status',
    ];

    public function post() { return $this->belongsTo(BlogPost::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function replies() { return $this->hasMany(BlogComment::class, 'parent_id'); }
    public function parent() { return $this->belongsTo(BlogComment::class, 'parent_id'); }
}
