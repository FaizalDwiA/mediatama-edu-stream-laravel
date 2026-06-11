<x-app-layout>
    @push('styles')
        @vite(['resources/css/dashboard.css'])
    @endpush

    <div class="dashboard-wrapper py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Toast Success / Error Notifications -->
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="session-alert alert-success">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            style="width: 20px; height: 20px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button @click="show = false"
                        class="text-emerald-450 hover:text-emerald-250 transition-colors p-1.5 rounded-full hover:bg-emerald-500/10 flex items-center justify-center cursor-pointer"
                        title="Tutup" style="background: transparent; border: none;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            style="width: 16px; height: 16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition class="session-alert alert-error">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            style="width: 20px; height: 20px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button @click="show = false"
                        class="text-rose-450 hover:text-rose-250 transition-colors p-1.5 rounded-full hover:bg-rose-500/10 flex items-center justify-center cursor-pointer"
                        title="Tutup" style="background: transparent; border: none;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            style="width: 16px; height: 16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endif

            <!-- Category Pills Filter (YouTube mobile style) -->
            <div class="pill-wrapper" id="pillWrapper">
                <!-- Left Arrow -->
                <button class="pill-arrow hidden" id="pillArrowLeft" aria-label="Geser kiri" onclick="scrollPills(-1)">
                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <div class="category-container" id="categoryContainer">
                    <a href="{{ route('dashboard', request()->only('search')) }}"
                        class="category-pill {{ !$selectedCategoryId ? 'active' : 'inactive' }}">Semua Video</a>

                    @foreach ($categories as $category)
                        <a href="{{ route('dashboard', array_merge(request()->only('search'), ['category' => $category->id])) }}"
                            class="category-pill {{ $selectedCategoryId == $category->id ? 'active' : 'inactive' }}">{{ $category->name }}</a>
                    @endforeach
                </div>

                <!-- Right Arrow -->
                <button class="pill-arrow" id="pillArrowRight" aria-label="Geser kanan" onclick="scrollPills(1)">
                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <!-- Video Catalog Grid -->
            <div class="video-grid">
                @forelse($videos as $video)
                    @php
                        $userRequest = $requests->get($video->id);
                        $isExpired =
                            $userRequest &&
                            $userRequest->valid_until &&
                            \Carbon\Carbon::now()->gt($userRequest->valid_until);
                        $isApproved =
                            (auth()->user() && auth()->user()->role === 'admin') ||
                            ($userRequest && $userRequest->status === 'approved' && !$isExpired);
                        $gradIndex = ($loop->index % 6) + 1;
                    @endphp

                    @if ($isApproved)
                        <a href="{{ route('video.watch', $video->id) }}" class="video-card mx-auto"
                            style="max-width: 85% !important; width: 100% !important;">
                        @else
                            <div class="video-card">
                    @endif
                    <div class="video-thumbnail">
                        @if ($video->category)
                            <span class="video-tag">{{ $video->category->name }}</span>
                        @endif
                        @if ($video->thumbnail)
                            <div class="video-thumbnail-bg"
                                style="background-image: url('{{ asset('storage/' . $video->thumbnail) }}'); background-size: cover; background-position: center;">
                            </div>
                        @else
                            <div class="video-thumbnail-bg grad-{{ $gradIndex }}"></div>
                        @endif
                        @if ($isApproved && $video->video_path)
                            <video class="preview-video" src="{{ asset('storage/' . $video->video_path) }}" muted loop
                                playsinline preload="metadata"></video>

                            <!-- Mute/Unmute Overlay Button -->
                            <button class="mute-btn" title="Mute/Unmute">
                                <svg class="icon-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    style="width: 1.125rem; height: 1.125rem;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"></path>
                                </svg>
                                <svg class="icon-unmuted hidden" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" style="width: 1.125rem; height: 1.125rem;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z">
                                    </path>
                                </svg>
                            </button>

                            <!-- Duration Badge -->
                            <span class="video-duration-badge">0:00</span>

                            <!-- YouTube-style Progress Bar -->
                            <div class="video-progress-bar">
                                <!-- Card Preview Tooltip -->
                                <div class="card-preview-tooltip">
                                    <div class="card-preview-video-container">
                                        <video class="card-preview-video-element"
                                            src="{{ route('video.stream', $video->id) }}" muted
                                            preload="auto"></video>
                                    </div>
                                    <div class="card-preview-time-badge">0:00</div>
                                </div>
                                <div class="video-progress-fill"></div>
                                <div class="video-progress-scrubber"></div>
                            </div>
                        @endif
                        @if ($isApproved)
                            <div class="play-overlay">
                                <div class="play-btn-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                </div>
                            </div>
                        @else
                            <div class="locked-overlay">
                                @if (!$userRequest)
                                    <form action="{{ route('video.request', $video->id) }}" method="POST"
                                        class="w-full h-full flex flex-col items-center justify-center p-2"
                                        onsubmit="return confirm('Apakah Anda yakin ingin meminta akses untuk menonton video ini?');">
                                        @csrf
                                        <div class="lock-icon-container state-request">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                        </div>
                                        <span class="overlay-title text-slate-100">Akses Terkunci</span>
                                        <span class="overlay-subtitle">Minta akses untuk menonton</span>
                                        <button type="submit" class="overlay-btn btn-request">
                                            <span>Minta Akses</span>
                                        </button>
                                    </form>
                                @elseif($userRequest->status === 'pending')
                                    <div class="w-full h-full flex flex-col items-center justify-center p-2">
                                        <div class="lock-icon-container state-pending">
                                            <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <span class="overlay-title text-amber-400">Menunggu Persetujuan</span>
                                        <span class="overlay-subtitle">Sedang ditinjau oleh Admin</span>
                                        <button type="button" class="pending-badge"
                                            onclick="alert('Permintaan akses Anda sedang ditinjau oleh Admin. Mohon bersabar atau hubungi Admin untuk informasi lebih lanjut.');">
                                            <svg class="w-3 h-3 animate-spin" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" style="width: 12px; height: 12px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                            </svg>
                                            <span>Diproses</span>
                                        </button>
                                    </div>
                                @elseif($userRequest->status === 'rejected')
                                    <form action="{{ route('video.request', $video->id) }}" method="POST"
                                        class="w-full h-full flex flex-col items-center justify-center p-2"
                                        onsubmit="return confirm('Apakah Anda yakin ingin mengirim ulang permintaan akses untuk menonton video ini?');">
                                        @csrf
                                        <div class="lock-icon-container state-rejected">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                        <span class="overlay-title text-orange-500">Permintaan Ditolak</span>
                                        <span class="overlay-subtitle">Kirim ulang permintaan akses</span>
                                        <button type="submit" class="overlay-btn btn-rejected">
                                            <span>Minta Ulang</span>
                                        </button>
                                    </form>
                                @elseif($isExpired)
                                    <form action="{{ route('video.request', $video->id) }}" method="POST"
                                        class="w-full h-full flex flex-col items-center justify-center p-2"
                                        onsubmit="return confirm('Apakah Anda yakin ingin meminta ulang akses untuk menonton video ini?');">
                                        @csrf
                                        <div class="lock-icon-container state-expired">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <span class="overlay-title text-rose-500">Akses Kedaluwarsa</span>
                                        <span class="overlay-subtitle">Batas waktu akses habis</span>
                                        <button type="submit" class="overlay-btn btn-expired">
                                            <span>Minta Ulang</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Video Content Details (YouTube style: Avatar + Info) -->
                    <div class="video-details-row">
                        <!-- Channel/Platform Avatar -->
                        <div class="video-avatar grad-{{ $gradIndex }}">
                            <img src="{{ asset('img/logo/logo2.webp') }}"
                                class="w-full h-full rounded-full object-cover" alt="Logo">
                        </div>

                        <!-- Video Metadata -->
                        <div class="video-meta-content">
                            <h3 class="video-title" title="{{ $video->title }}">{{ Str::limit($video->title, 55) }}
                            </h3>
                            <p class="video-views-date">
                                {{ $video->created_at->locale('id')->diffForHumans() }}
                            </p>

                            <!-- Video Actions (Pill Buttons) -->
                            <div class="video-actions-wrapper" style="margin-top: 0.25rem !important;">
                                @if (auth()->user() && auth()->user()->role === 'admin')
                                    <div class="access-status-approved">
                                        <svg class="w-3.5 h-3.5 text-green-400 mr-1" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24"
                                            style="width: 14px; height: 14px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                            </path>
                                        </svg>
                                        Akses Penuh (Admin)
                                    </div>
                                @elseif($userRequest && $userRequest->status === 'approved' && !$isExpired)
                                    <div class="access-status-approved">
                                        <svg class="w-3.5 h-3.5 text-green-400 mr-1" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24"
                                            style="width: 14px; height: 14px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Batas Akses: {{ $userRequest->valid_until->format('d M Y H:i') }}
                                    </div>
                                @elseif($isExpired)
                                    <div class="access-status-expired">
                                        <svg class="w-3.5 h-3.5 text-red-400 mr-1" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24"
                                            style="width: 14px; height: 14px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                            </path>
                                        </svg>
                                        Waktu Akses Habis!
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if ($isApproved)
                        </a>
                    @else
            </div>
            @endif
        @empty
            <div
                class="col-span-1 sm:col-span-2 lg:col-span-3 text-center text-slate-400 py-16 bg-slate-900 bg-opacity-40 border border-dashed border-slate-700 border-opacity-65 rounded-2xl">
                @if (request('search'))
                    <svg class="w-16 h-16 mx-auto text-slate-600 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <p class="text-base font-semibold">Tidak ada video yang cocok dengan pencarian
                        "{{ request('search') }}"</p>
                    <p class="text-xs text-slate-500 mt-1">Coba kata kunci lain atau <a
                            href="{{ route('dashboard') }}"
                            class="text-indigo-400 hover:text-indigo-300 underline font-medium transition-colors">bersihkan
                            pencarian</a>.</p>
                @else
                    <svg class="w-16 h-16 mx-auto text-slate-600 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z">
                        </path>
                    </svg>
                    <p class="text-base font-semibold">Belum ada video yang tersedia</p>
                    <p class="text-xs text-slate-500 mt-1">Silakan hubungi Admin untuk menambahkan video ke dalam
                        katalog.</p>
                @endif
            </div>
            @endforelse
        </div>

    </div>
    </div>

    @push('scripts')
        @vite(['resources/js/dashboard.js'])
    @endpush
</x-app-layout>
