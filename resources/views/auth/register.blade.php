<x-guest-layout>
    @push('styles')
        <style>
            body.auth-body {
                background-image: var(--bg-dark-overlay), url("{{ asset('img/background/register.webp') }}");
            }
        </style>
    @endpush

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />

            <!-- Password Strength Indicator -->
            <div class="mt-2" id="password-strength-container" style="display: none;">
                <div style="height: 5px; width: 100%; background-color: rgba(255,255,255,0.1); border-radius: 3px; overflow: hidden; margin-bottom: 5px;">
                    <div id="password-strength-bar" style="height: 100%; width: 0%; background-color: #f43f5e; transition: all 0.3s ease;"></div>
                </div>
                <span id="password-strength-text" style="font-size: 0.75rem; font-weight: 600; color: #94a3b8;">Sangat Lemah</span>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Terms and Conditions Checklist -->
        <div class="mt-4 auth-checkbox-wrapper">
            <label class="auth-checkbox-label">
                <input type="checkbox" name="terms" id="terms" required>
                <span class="auth-checkbox-text">Saya menyetujui <a href="#" class="underline text-indigo-400" style="display:inline !important;">Syarat & Ketentuan</a> serta <a href="#" class="underline text-indigo-400" style="display:inline !important;">Kebijakan Privasi</a></span>
            </label>
            <x-input-error :messages="$errors->get('terms')" class="mt-2" />
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full auth-btn flex items-center justify-center" style="width: 100% !important; text-align: center;">
                {{ __('Daftar Sekarang') }}
            </button>
        </div>
    </form>

    <div class="auth-footer-text">
        {{ __('Already registered?') }}
        <a href="{{ route('login') }}">
            {{ __('Log in') }}
        </a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const strengthContainer = document.getElementById('password-strength-container');
            const strengthBar = document.getElementById('password-strength-bar');
            const strengthText = document.getElementById('password-strength-text');

            passwordInput.addEventListener('input', function() {
                const val = passwordInput.value;
                if (val.length === 0) {
                    strengthContainer.style.display = 'none';
                    return;
                }

                strengthContainer.style.display = 'block';

                let score = 0;
                if (val.length >= 6) score++;
                if (val.length >= 8) score++;
                if (/[A-Z]/.test(val)) score++;
                if (/[0-9]/.test(val)) score++;
                if (/[^A-Za-z0-9]/.test(val)) score++;

                let width = '0%';
                let color = '#f43f5e'; // Merah
                let text = 'Sangat Lemah';

                if (score === 1) {
                    width = '25%';
                    color = '#f43f5e';
                    text = 'Lemah';
                } else if (score === 2) {
                    width = '50%';
                    color = '#f97316'; // Jingga
                    text = 'Sedang';
                } else if (score === 3 || score === 4) {
                    width = '75%';
                    color = '#eab308'; // Kuning
                    text = 'Kuat';
                } else if (score >= 5) {
                    width = '100%';
                    color = '#10b981'; // Hijau
                    text = 'Sangat Kuat';
                }

                strengthBar.style.width = width;
                strengthBar.style.backgroundColor = color;
                strengthText.textContent = text;
                strengthText.style.color = color;
            });
        });
    </script>
</x-guest-layout>
