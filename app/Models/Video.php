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
        'status',
    ];

    /**
     * Temporary placeholder to keep track of original video path when changed.
     */
    public ?string $old_video_path_temp = null;

    protected static function booted(): void
    {
        static::saving(function (Video $video) {
            // If the video path changed and points to a temp file, set status to processing
            if ($video->isDirty('video_path') && str_starts_with((string) $video->video_path, 'videos/temp/')) {
                $video->old_video_path_temp = $video->getOriginal('video_path');
                $video->status = 'processing';
            }
        });

        static::saved(function (Video $video) {
            // If the status is processing, dispatch the background compression job
            if ($video->status === 'processing') {
                $oldPath = $video->old_video_path_temp ?? null;
                \App\Jobs\CompressVideoJob::dispatch($video, $oldPath);
            }
        });

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

