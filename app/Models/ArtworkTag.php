<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtworkTag extends Model
{
    protected $fillable = ['artwork_id', 'tag'];
    public function artwork() { return $this->belongsTo(Artwork::class); }
}
