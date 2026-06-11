<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->safe()->only(['name', 'email']));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($request->hasFile('profile_photo')) {
            // Hapus foto profil lama jika ada
            if ($user->profile_photo && file_exists(storage_path('app/public/' . $user->profile_photo))) {
                @unlink(storage_path('app/public/' . $user->profile_photo));
            }

            $file = $request->file('profile_photo');
            $filename = 'avatar_' . uniqid() . '.webp';
            $dirPath = storage_path('app/public/profile_photos');

            if (!file_exists($dirPath)) {
                mkdir($dirPath, 0755, true);
            }

            try {
                // Kompres & resize ke 300x300 px menggunakan Intervention Image (v3)
                $image = \Intervention\Image\Laravel\Facades\Image::read($file->getRealPath());
                $image->cover(300, 300);

                $encoded = $image->toWebp(80);
                file_put_contents($dirPath . '/' . $filename, (string) $encoded);

                $user->profile_photo = 'profile_photos/' . $filename;
            } catch (\Exception $e) {
                // Fallback jika terjadi kesalahan
                $user->profile_photo = $file->store('profile_photos', 'public');
            }
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
