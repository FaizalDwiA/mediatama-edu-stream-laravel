<?php

namespace App\Jobs;

use App\Models\Video;
use App\Services\VideoCompressionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CompressVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 600; // 10 minutes

    protected Video $video;
    protected ?string $oldVideoPath;

    /**
     * Create a new job instance.
     */
    public function __construct(Video $video, ?string $oldVideoPath = null)
    {
        $this->video = $video;
        $this->oldVideoPath = $oldVideoPath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Re-check status, if not processing, skip
        if ($this->video->status !== 'processing') {
            return;
        }

        $tempPathRelative = $this->video->video_path;
        $tempPathAbsolute = storage_path('app/public/' . $tempPathRelative);

        if (!file_exists($tempPathAbsolute)) {
            Log::error("Temporary video file not found at: {$tempPathAbsolute}");
            $this->video->update(['status' => 'failed']);
            return;
        }

        $finalFilename = 'video_' . uniqid() . '.mp4';
        $finalPathRelative = 'videos/' . $finalFilename;
        $finalPathAbsolute = storage_path('app/public/' . $finalPathRelative);

        Log::info("Starting background compression for Video ID: {$this->video->id}");

        // Perform compression
        $success = VideoCompressionService::compress($tempPathAbsolute, $finalPathAbsolute);

        if ($success) {
            // Delete temporary file
            if (file_exists($tempPathAbsolute)) {
                @unlink($tempPathAbsolute);
            }

            // Delete old video file if exists and is different
            if ($this->oldVideoPath && $this->oldVideoPath !== $tempPathRelative) {
                $oldPathAbsolute = storage_path('app/public/' . $this->oldVideoPath);
                if (file_exists($oldPathAbsolute)) {
                    @unlink($oldPathAbsolute);
                }
            }

            // Update video details in database
            $this->video->update([
                'video_path' => $finalPathRelative,
                'status' => 'ready'
            ]);

            Log::info("Background compression completed for Video ID: {$this->video->id}");
        } else {
            // Clean up temporary file to prevent server from getting full
            if (file_exists($tempPathAbsolute)) {
                @unlink($tempPathAbsolute);
            }
            
            $this->video->update(['status' => 'failed']);
            Log::error("Background compression failed for Video ID: {$this->video->id}");
        }
    }
}
