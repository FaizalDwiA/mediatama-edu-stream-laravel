<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerVideoTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_displays_all_videos_by_default(): void
    {
        $user = User::factory()->create();
        
        $video1 = Video::create([
            'title' => 'Belajar Laravel Bagian 1',
            'description' => 'Tutorial dasar Laravel untuk pemula',
            'video_path' => 'videos/laravel1.mp4'
        ]);

        $video2 = Video::create([
            'title' => 'Belajar VueJS Bagian 1',
            'description' => 'Tutorial dasar VueJS untuk pemula',
            'video_path' => 'videos/vue1.mp4'
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Belajar Laravel Bagian 1');
        $response->assertSee('Belajar VueJS Bagian 1');
    }

    public function test_dashboard_filters_videos_by_search_query(): void
    {
        $user = User::factory()->create();
        
        $video1 = Video::create([
            'title' => 'Belajar Laravel Bagian 1',
            'description' => 'Tutorial dasar Laravel untuk pemula',
            'video_path' => 'videos/laravel1.mp4'
        ]);

        $video2 = Video::create([
            'title' => 'Belajar VueJS Bagian 1',
            'description' => 'Tutorial dasar VueJS untuk pemula',
            'video_path' => 'videos/vue1.mp4'
        ]);

        // Cari "Laravel"
        $response = $this
            ->actingAs($user)
            ->get('/dashboard?search=Laravel');

        $response->assertOk();
        $response->assertSee('Belajar Laravel Bagian 1');
        $response->assertDontSee('Belajar VueJS Bagian 1');

        // Cari berdasarkan deskripsi "pemula" (dua-duanya punya)
        $response2 = $this
            ->actingAs($user)
            ->get('/dashboard?search=pemula');

        $response2->assertOk();
        $response2->assertSee('Belajar Laravel Bagian 1');
        $response2->assertSee('Belajar VueJS Bagian 1');
    }

    public function test_dashboard_shows_no_videos_found_message(): void
    {
        $user = User::factory()->create();
        
        Video::create([
            'title' => 'Belajar Laravel Bagian 1',
            'description' => 'Tutorial dasar Laravel untuk pemula',
            'video_path' => 'videos/laravel1.mp4'
        ]);

        // Cari kata kunci acak
        $response = $this
            ->actingAs($user)
            ->get('/dashboard?search=Python');

        $response->assertOk();
        $response->assertDontSee('Belajar Laravel Bagian 1');
        $response->assertSee('Tidak ada video yang cocok dengan pencarian "Python"', false);
    }

    public function test_admin_can_watch_and_stream_videos_without_access_request(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $video = Video::create([
            'title' => 'Video Khusus Admin',
            'description' => 'Bisa ditonton langsung oleh admin',
            'video_path' => 'videos/admin_test.mp4'
        ]);

        // Dashboard should show it as approved/playable (contains link to watch)
        $response = $this
            ->actingAs($admin)
            ->get('/dashboard');

        $response->assertOk();
        $response->assertSee(route('video.watch', $video->id));

        // Watch page should be accessible
        $watchResponse = $this
            ->actingAs($admin)
            ->get(route('video.watch', $video->id));

        $watchResponse->assertOk();

        // Stream page should also be accessible (should return 404 for non-existent file, not 403 access denied)
        $streamResponse = $this
            ->actingAs($admin)
            ->get(route('video.stream', $video->id));

        $streamResponse->assertStatus(404);
    }
}
