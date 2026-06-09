<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Videos extends Model
{
    /** @use HasFactory<\Database\Factories\VideosFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'video_path',
    ];

    public function accessRequests(): HasMany
    {
        return $this->hasMany(AccessRequest::class);
    }
}
