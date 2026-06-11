<x-guest-layout>
    @push('styles')
        <style>
            body.auth-body {
                background-image: var(--bg-dark-overlay), url("{{ asset('img/background/verify-email.webp') }}");
            }
            .verify-card-header {
                text-align: center;
                margin-bottom: 1.5rem;
            }
            .verify-icon-container {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                background: rgba(99, 102, 241, 0.12);
                border: 1px solid rgba(99, 102, 241, 0.25);
                color: #a855f7;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1rem auto;
                box-shadow: 0 0 15px rgba(99, 102, 241, 0.2);
            }
            .verify-icon-container svg {
                width: 28px;
                height: 28px;
            }
            .verify-actions-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-top: 2rem;
                width: 100%;
                gap: 1.5rem;
            }
        </style>
    @endpush

    <div class="verify-card-header">
        <div class="verify-icon-container">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l8-4.666a2 2 0 012.22 0l8 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-2.25-1.5a2 2 0 00-2.22 0l-2.25 1.5"></path>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-white mb-1">Verifikasi Email Anda</h2>
    </div>

    <div class="mb-4 text-sm text-gray-600 auth-info-text text-center" style="margin-bottom: 1.5rem !important;">
        {{ __('Terima kasih telah mendaftar! Sebelum memulai, silakan verifikasi alamat email Anda dengan mengeklik tautan yang baru saja kami kirimkan ke email Anda. Jika Anda tidak menerima email tersebut, kami dengan senang hati akan mengirimkan ulang.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600 auth-status-success text-center" style="margin-bottom: 1.25rem !important;">
            {{ __('Tautan verifikasi baru telah berhasil dikirim ke alamat email yang Anda daftarkan saat registrasi.') }}
        </div>
    @endif

    <div class="verify-actions-row">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <div>
                <button type="submit" class="auth-btn">
                    {{ __('Kirim Ulang Email Verifikasi') }}
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="auth-link underline">
                {{ __('Keluar') }}
            </button>
        </form>
    </div>
</x-guest-layout>
