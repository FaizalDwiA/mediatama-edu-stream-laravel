<?php

namespace App\Services;

use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Log;

class VideoCompressionService
{
    /**
     * Compress a video file using FFmpeg.
     *
     * @param string $inputPath Absolute path to source video file
     * @param string $outputPath Absolute path to target output video file
     * @return bool True if successful, false otherwise
     */
    public static function compress(string $inputPath, string $outputPath): bool
    {
        // Ensure directory exists
        $dirPath = dirname($outputPath);
        if (!file_exists($dirPath)) {
            mkdir($dirPath, 0755, true);
        }

        try {
            // Extend PHP execution time as video compression can take time
            set_time_limit(300);

            $ffmpegPath = config('services.ffmpeg.path', 'ffmpeg');

            // FFmpeg command parameters:
            // -y: Overwrite output file
            // -i: Input path
            // -vcodec libx264: H.264 video codec for high browser compatibility
            // -crf 24: Good quality with high compression ratio
            // -preset superfast: Fast compression speed to prevent excessive CPU lock
            // -vf scale='min(1280,iw)':-2: Scale down to 720p if width exceeds 1280px, while keeping aspect ratio and divisible by 2
            // -acodec aac: Standard AAC audio codec
            // -b:a 128k: Audio bitrate 128kbps for clear sound
            $result = Process::forever()->run([
                $ffmpegPath,
                '-y',
                '-i', $inputPath,
                '-vcodec', 'libx264',
                '-crf', '28',
                '-preset', 'veryfast',
                '-maxrate', '1.5M',
                '-bufsize', '3M',
                '-vf', "scale='min(1280,iw)':-2",
                '-acodec', 'aac',
                '-b:a', '128k',
                '-threads', '2', // Limit CPU threads to prevent server lag
                $outputPath
            ]);

            if ($result->successful()) {
                Log::info("Video successfully compressed: {$inputPath} -> {$outputPath}");
                return true;
            }

            Log::error("Video compression failed for: {$inputPath}. Error: " . $result->errorOutput());
        } catch (\Exception $e) {
            Log::error("Video compression exception: " . $e->getMessage());
        }

        return false;
    }
}
