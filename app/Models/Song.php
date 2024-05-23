<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $fillable = [
        'title',
        'artist',
        'duration',
        'description',
        'category_id',
        'url',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}