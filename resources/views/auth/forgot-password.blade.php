<x-guest-layout>
    @push('styles')
        <style>
            body.auth-body {
                background-image: var(--bg-dark-overlay), url("{{ asset('img/background/forgot-password.webp') }}");
            }
        </style>
    @endpush

    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-white mb-2">Lupa Kata Sandi?</h2>
        <p class="text-sm text-slate-400 leading-relaxed">
            Jangan khawatir! Masukkan alamat email Anda di bawah ini dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Alamat Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full auth-btn flex items-center justify-center" style="width: 100% !important; text-align: center;">
                {{ __('Kirim Tautan Reset') }}
            </button>
        </div>
    </form>

    <div class="auth-footer-text">
        Kembali ke
        <a href="{{ route('login') }}">
            {{ __('Halaman Login') }}
        </a>
    </div>
</x-guest-layout>
