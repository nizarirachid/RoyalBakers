<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'subject', 'message',
        'user_id', 'status', 'admin_reply', 'replied_at', 'ip_address',
    ];

    protected $casts = ['replied_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
