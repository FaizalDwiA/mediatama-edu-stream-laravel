<x-guest-layout>
    @push('styles')
        <style>
            body.auth-body {
                background-image: var(--bg-dark-overlay), url("{{ asset('img/background/login.webp') }}");
            }
        </style>
    @endpush

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password Row -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center auth-checkbox-label">
                <input id="remember_me" type="checkbox" name="remember">
                <span class="ms-2 text-sm auth-checkbox-text">{{ __('Ingat Saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="auth-link underline text-sm" href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="mt-6">
            <button type="submit" class="w-full auth-btn flex items-center justify-center" style="width: 100% !important; text-align: center;">
                {{ __('Masuk Sekarang') }}
            </button>
        </div>
    </form>

    <div class="auth-footer-text">
        {{ __("Belum punya akun?") }}
        <a href="{{ route('register') }}">
            {{ __('Daftar') }}
        </a>
    </div>
</x-guest-layout>
