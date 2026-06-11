<x-guest-layout>
    @push('styles')
        <style>
            body.auth-body {
                background-image: var(--bg-dark-overlay), url("{{ asset('img/background/register.webp') }}");
            }
        </style>
    @endpush

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <!-- Profile Photo -->
        <div class="flex flex-col items-center mb-6">
            <div class="relative group cursor-pointer" id="avatar-trigger" title="Klik untuk mengunggah foto profil">
                <!-- Glowing Outer Ring -->
                <div class="absolute -inset-1 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 opacity-60 group-hover:opacity-100 blur transition-all duration-300"></div>
                
                <!-- Circular Frame -->
                <div class="relative w-24 h-24 rounded-full overflow-hidden bg-[#181824] border-2 border-slate-700/80 flex items-center justify-center transition-all duration-300 group-hover:border-indigo-400">
                    <!-- SVG Placeholder -->
                    <svg class="w-12 h-12 text-slate-500 transition-all duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="avatar-placeholder">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <!-- Preview Image -->
                    <img id="avatar-preview" class="w-full h-full object-cover transition-all duration-300 group-hover:scale-105 hidden" alt="Preview">
                    
                    <!-- Hover Camera Overlay -->
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center text-white text-[10px] font-bold tracking-wider transition-opacity duration-300 select-none">
                        <svg class="w-5 h-5 mb-1 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>UNGGAH FOTO</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-3 text-center">
                <button type="button" id="btn-select-file" class="px-4 py-1.5 text-xs font-semibold rounded-lg bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 hover:text-indigo-300 border border-indigo-500/20 transition-all duration-200 cursor-pointer">
                    Pilih Foto Profil (Opsional)
                </button>
                <div id="selected-filename" class="text-xs text-slate-500 italic mt-1 truncate max-w-[240px]">Belum ada file terpilih</div>
                <!-- Hidden Input -->
                <input id="profile_photo" class="hidden" type="file" name="profile_photo" accept="image/*" />
                <x-input-error :messages="$errors->get('profile_photo')" class="mt-2" />
            </div>
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
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
            // Avatar Preview Logic
            const trigger = document.getElementById('avatar-trigger');
            const btnSelect = document.getElementById('btn-select-file');
            const photoInput = document.getElementById('profile_photo');
            const preview = document.getElementById('avatar-preview');
            const placeholder = document.getElementById('avatar-placeholder');
            const filenameSpan = document.getElementById('selected-filename');

            if (trigger && photoInput) {
                trigger.addEventListener('click', () => photoInput.click());
            }
            if (btnSelect && photoInput) {
                btnSelect.addEventListener('click', () => photoInput.click());
            }

            if (photoInput) {
                photoInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        filenameSpan.textContent = file.name;
                        filenameSpan.classList.remove('text-slate-500');
                        filenameSpan.classList.add('text-indigo-400');

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                            preview.classList.remove('hidden');
                            placeholder.classList.add('hidden');
                        }
                        reader.readAsDataURL(file);
                    } else {
                        preview.src = '';
                        preview.classList.add('hidden');
                        placeholder.classList.remove('hidden');
                        filenameSpan.textContent = 'Belum ada file terpilih';
                        filenameSpan.classList.add('text-slate-500');
                        filenameSpan.classList.remove('text-indigo-400');
                    }
                });
            }

            // Password Strength Logic
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
