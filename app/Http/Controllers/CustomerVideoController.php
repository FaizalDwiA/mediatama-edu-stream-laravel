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

        $query = Video::with('category');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhereHas('category', function ($catQuery) use ($search) {
                        $catQuery->where('name', 'like', '%' . $search . '%');
                    });
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
        $video = Video::with('category')->findOrFail($id);
        $user = auth()->user();

        // Jika user adalah admin, bypass pengecekan akses
        if ($user && $user->role === 'admin') {
            $access = null;
            return view('watch', compact('video', 'access'));
        }

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

    public function stream($id)
    {
        $userId = auth()->id();
        $video = Video::findOrFail($id);
        $user = auth()->user();

        // Jika user adalah admin, bypass pengecekan akses
        if (!$user || $user->role !== 'admin') {
            // Cek izin akses di database
            $access = AccessRequest::where('user_id', $userId)
                ->where('video_id', $id)
                ->where('status', 'approved')
                ->first();

            // Validasi akses aktif
            if (!$access || !$access->valid_until || Carbon::now()->gt($access->valid_until)) {
                if ($access) {
                    $access->update(['status' => 'expired']);
                }
                abort(403, 'Akses ditolak!');
            }
        }

        $path = storage_path('app/public/' . $video->video_path);

        if (!file_exists($path)) {
            abort(404, 'File video tidak ditemukan.');
        }

        $stream = fopen($path, 'rb');
        $size   = filesize($path);
        $length = $size;
        $start  = 0;
        $end    = $size - 1;

        $status = 200;
        $headers = [
            'Content-Type'   => 'video/mp4',
            'Accept-Ranges'  => 'bytes',
            'Content-Length' => $size,
        ];

        if (request()->header('Range')) {
            $status = 206;
            list(, $range) = explode('=', request()->header('Range'), 2);
            if (strpos($range, ',') !== false) {
                return response('Requested Range Not Satisfiable', 416, [
                    'Content-Range' => "bytes */$size"
                ]);
            }
            
            if ($range == '-') {
                $c_start = 0;
            } else {
                $range   = explode('-', $range);
                $c_start = intval($range[0]);
                $c_end   = (isset($range[1]) && is_numeric($range[1])) ? intval($range[1]) : $size - 1;
            }
            $c_end = ($c_end > $end) ? $end : $c_end;
            if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size) {
                return response('Requested Range Not Satisfiable', 416, [
                    'Content-Range' => "bytes */$size"
                ]);
            }
            $start  = $c_start;
            $end    = $c_end;
            $length = $end - $start + 1;
            
            $headers['Content-Length'] = $length;
            $headers['Content-Range']  = "bytes $start-$end/$size";
        }

        return response()->stream(function () use ($stream, $start, $length) {
            fseek($stream, $start);
            $buffered = 0;
            $chunkSize = 1024 * 8; // 8KB chunks
            while (!feof($stream) && $buffered < $length) {
                $toRead = min($chunkSize, $length - $buffered);
                echo fread($stream, $toRead);
                flush();
                $buffered += $toRead;
            }
            fclose($stream);
        }, $status, $headers);
    }
}
