<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogAdminReadActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Hanya catat log jika request bertipe GET dan pengguna terautentikasi adalah admin
        if ($request->isMethod('GET') && Auth::check() && Auth::user()->role === 'admin') {
            $segments = $request->segments();

            // Rute Filament biasanya diawali dengan 'admin'
            if (count($segments) >= 2 && $segments[0] === 'admin') {
                $resource = $segments[1];

                // Abaikan rute sistem internal Filament
                $ignored = ['livewire', 'logout', 'login', 'dashboard'];
                if (!in_array($resource, $ignored)) {
                    $action = 'Read';
                    $targetId = null;

                    // Cek jika membuka halaman edit record (misal: admin/categories/1/edit)
                    if (count($segments) === 4 && $segments[3] === 'edit') {
                        $targetId = $segments[2];
                    } 
                    // Cek jika membuka halaman detail view record (jika ada, misal: admin/categories/1)
                    elseif (count($segments) === 3 && is_numeric($segments[2])) {
                        $targetId = $segments[2];
                    }
                    // Jika halaman create form (misal: admin/categories/create), abaikan pencatatan Read 
                    // karena pembuatan data sesungguhnya akan dicatat oleh Model Observer saat disimpan
                    elseif (count($segments) === 3 && $segments[2] === 'create') {
                        return $response;
                    }

                    // Map resource ke nama menu yang ramah dibaca
                    $menu = match ($resource) {
                        'categories' => 'Category',
                        'videos' => 'Video',
                        'users' => 'User',
                        'access-requests' => 'Access Request',
                        default => ucfirst($resource),
                    };

                    ActivityLog::create([
                        'user_id' => Auth::id(),
                        'user_name' => Auth::user()->name,
                        'menu' => $menu,
                        'action' => $action,
                        'target_id' => $targetId,
                    ]);
                }
            }
        }

        return $response;
    }
}
