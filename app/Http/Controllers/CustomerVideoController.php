<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\AccessRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CustomerVideoController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        // 🌟 KUNCI OTOMATISASI: Setiap user buka dashboard, langsung cek & ubah yang kedaluwarsa secara real-time
        AccessRequest::where('user_id', $userId)
            ->where('status', 'approved')
            ->where('valid_until', '<=', Carbon::now()) // Menggunakan nama kolom nyata Anda: valid_until
            ->update(['status' => 'expired']);

        $query = Video::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $videos = $query->get();

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
            // Jika datanya ada tapi waktunya lewat, ubah statusnya di database menjadi expired
            if ($access) {
                $access->update(['status' => 'expired']);
            }

            return redirect()->route('dashboard')->with('error', 'Akses ditolak! Waktu menonton Anda belum dimulai atau sudah habis.');
        }

        return view('watch', compact('video', 'access'));
    }
}
