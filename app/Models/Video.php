<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Video extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'video',
        'video_url',
        'order_index',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
