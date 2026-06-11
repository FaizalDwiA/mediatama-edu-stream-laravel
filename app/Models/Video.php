<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{
    /** @use HasFactory<\Database\Factories\VideoFactory> */
    use HasFactory, LogsActivity;

    protected $fillable = [
        'title',
        'category_id',
        'thumbnail',
        'description',
        'video_path',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Video $video) {
            // Hapus file thumbnail dari storage saat record dihapus
            if ($video->thumbnail) {
                Storage::disk('public')->delete($video->thumbnail);
            }

            // Hapus file video dari storage saat record dihapus
            if ($video->video_path) {
                Storage::disk('public')->delete($video->video_path);
            }
        });
    }

    public function accessRequests(): HasMany
    {
        return $this->hasMany(AccessRequest::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}

