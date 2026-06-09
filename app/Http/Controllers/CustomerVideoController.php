<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\AccessRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CustomerVideoController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $videos = Video::all();

        // Ambil semua data request milik user ini untuk dicocokkan di blade
        $requests = AccessRequest::where('user_id', $userId)
            ->get()
            ->keyBy('video_id');

        return view('dashboard', compact('videos', 'requests'));
    }

    public function requestAccess($id)
    {
        $userId = auth()->id();

        // Cek jika sudah pernah request agar tidak duplikat
        $existing = AccessRequest::where('user_id', $userId)->where('video_id', $id)->first();

        if ($existing) {
            // Jika sudah ada (misal status rejected atau expired), reset menjadi pending
            $existing->update([
                'status' => 'pending',
                'valid_until' => null
            ]);
        } else {
            AccessRequest::create([
                'user_id' => $userId,
                'video_id' => $id,
                'status' => 'pending'
            ]);
        }

        return redirect()->back()->with('success', 'Permintaan akses berhasil dikirim ke Admin!');
    }

    public function watch($id)
    {
        $userId = auth()->id();
        $video = Video::findOrFail($id);

        // Cek izin akses di database
        $access = AccessRequest::where('user_id', $userId)
            ->where('video_id', $id)
            ->where('status', 'approved')
            ->first();

        // Validasi Logika Waktu menggunakan Carbon (Inti Batas Waktu Soal)
        if (!$access || !$access->valid_until || Carbon::now()->gt($access->valid_until)) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak! Waktu menonton Anda belum dimulai atau sudah habis.');
        }

        return view('watch', compact('video', 'access'));
    }
}
