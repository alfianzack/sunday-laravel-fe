<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = [
        'title',
        'description',
        'price',
        'thumbnail',
        'preview_video',
        'preview_video_url',
    ];

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class)->orderBy('order_index');
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }
}
