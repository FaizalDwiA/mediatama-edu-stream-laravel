<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['accepted'],
            'profile_photo' => ['nullable', 'image', 'max:1024'],
        ]);

        $profilePhotoPath = null;
        if ($request->hasFile('profile_photo')) {
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
                
                $profilePhotoPath = 'profile_photos/' . $filename;
            } catch (\Exception $e) {
                // Fallback
                $profilePhotoPath = $file->store('profile_photos', 'public');
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_photo' => $profilePhotoPath,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
