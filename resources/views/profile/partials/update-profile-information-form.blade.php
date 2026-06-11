<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Profile Photo -->
        <div class="flex flex-col md:flex-row items-center gap-6 p-5 rounded-2xl bg-[#0f0f15]/50 border border-white/5 shadow-inner">
            <!-- Left: Circular Image Area -->
            <div class="relative group cursor-pointer" id="avatar-trigger" title="Klik untuk mengubah foto profil">
                <!-- Glowing Outer Ring -->
                <div class="absolute -inset-1 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 opacity-60 group-hover:opacity-100 blur transition-all duration-300"></div>
                
                <!-- Circular Frame -->
                <div class="relative w-24 h-24 rounded-full overflow-hidden bg-[#181824] border-2 border-slate-700/80 flex items-center justify-center transition-all duration-300 group-hover:border-indigo-400">
                    <!-- SVG Placeholder -->
                    <svg class="w-12 h-12 text-slate-500 transition-all duration-300 group-hover:scale-110 {{ $user->profile_photo ? 'hidden' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="avatar-placeholder">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <!-- Preview Image -->
                    <img id="avatar-preview" class="w-full h-full object-cover transition-all duration-300 group-hover:scale-105 {{ $user->profile_photo ? '' : 'hidden' }}" src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : '' }}" alt="Preview">
                    
                    <!-- Hover Camera Overlay -->
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center text-white text-[10px] font-bold tracking-wider transition-opacity duration-300 select-none">
                        <svg class="w-5 h-5 mb-1 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>GANTI FOTO</span>
                    </div>
                </div>
            </div>

            <!-- Right: Instructions and Input -->
            <div class="flex-grow text-center md:text-left space-y-2">
                <h3 class="text-sm font-semibold text-white tracking-wide">Foto Profil Anda</h3>
                <p class="text-xs text-slate-400 leading-relaxed max-w-md">
                    Unggah foto formal atau kasual Anda. Gambar akan dipotong & dikompresi menjadi ukuran 300x300 px berformat WebP secara otomatis. (Maksimal 1 MB).
                </p>
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 pt-1">
                    <button type="button" id="btn-select-file" class="px-4 py-1.5 text-xs font-semibold rounded-lg bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 hover:text-indigo-300 border border-indigo-500/20 transition-all duration-200 cursor-pointer">
                        Pilih File Gambar
                    </button>
                    <span id="selected-filename" class="text-xs text-slate-500 italic truncate max-w-[220px]">
                        {{ $user->profile_photo ? 'Menggunakan foto saat ini' : 'Belum ada file terpilih' }}
                    </span>
                </div>
                <!-- Hidden Input -->
                <input id="profile_photo" class="hidden" type="file" name="profile_photo" accept="image/*" />
                <x-input-error :messages="$errors->get('profile_photo')" class="mt-1" />
            </div>
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const trigger = document.getElementById('avatar-trigger');
            const btnSelect = document.getElementById('btn-select-file');
            const photoInput = document.getElementById('profile_photo');
            const preview = document.getElementById('avatar-preview');
            const placeholder = document.getElementById('avatar-placeholder');
            const filenameSpan = document.getElementById('selected-filename');

            const originalPhoto = "{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : '' }}";

            // Click triggers
            if (trigger && photoInput) {
                trigger.addEventListener('click', () => photoInput.click());
            }
            if (btnSelect && photoInput) {
                btnSelect.addEventListener('click', () => photoInput.click());
            }

            // Input change event
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
                            if (placeholder) {
                                placeholder.classList.add('hidden');
                            }
                        }
                        reader.readAsDataURL(file);
                    } else {
                        // Revert to default/original
                        if (originalPhoto) {
                            preview.src = originalPhoto;
                            preview.classList.remove('hidden');
                            if (placeholder) {
                                placeholder.classList.add('hidden');
                            }
                            filenameSpan.textContent = 'Menggunakan foto saat ini';
                            filenameSpan.classList.add('text-slate-500');
                            filenameSpan.classList.remove('text-indigo-400');
                        } else {
                            preview.src = '';
                            preview.classList.add('hidden');
                            if (placeholder) {
                                placeholder.classList.remove('hidden');
                            }
                            filenameSpan.textContent = 'Belum ada file terpilih';
                            filenameSpan.classList.add('text-slate-500');
                            filenameSpan.classList.remove('text-indigo-400');
                        }
                    }
                });
            }
        });
    </script>
</section>
