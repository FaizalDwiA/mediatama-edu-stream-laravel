<x-app-layout>
    @push('styles')
        @vite(['resources/css/watch.css'])
    @endpush

    <div class="watch-wrapper py-8 sm:py-12">
        <div class="watch-inner max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Back Button -->
            <div class="watch-back-row mb-5">
                <a href="{{ route('dashboard') }}" class="btn-back">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>

            <!-- Cinema Mode Ambient Glow -->
            <div style="position: relative; z-index: 1;">
                <div class="cinema-glow"></div>

                <!-- Main Split Layout -->
                <div class="watch-layout">

                    <!-- Video Screen Column -->
                    <div class="video-column">
                        <div class="cinema-container">
                            <div class="video-player-wrapper">
                                @if ($video->status === 'processing')
                                    <div class="w-full h-full flex flex-col items-center justify-center bg-slate-950 text-slate-300 p-8 text-center" style="min-height: 380px; aspect-ratio: 16/9;">
                                        <svg class="animate-spin h-12 w-12 text-indigo-500 mb-4" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <h3 class="text-lg font-bold text-white mb-2">Video Sedang Diproses</h3>
                                        <p class="text-sm text-slate-400 max-w-md">Video sedang dikompresi agar hemat kuota dan server tidak penuh. Silakan muat ulang halaman ini dalam beberapa saat.</p>
                                    </div>
                                @elseif ($video->status === 'failed')
                                    <div class="w-full h-full flex flex-col items-center justify-center bg-slate-950 text-rose-500 p-8 text-center" style="min-height: 380px; aspect-ratio: 16/9;">
                                        <svg class="w-16 h-16 mb-4 text-rose-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        <h3 class="text-lg font-bold text-white mb-2">Gagal Memproses Video</h3>
                                        <p class="text-sm text-slate-400 max-w-md">Terjadi kesalahan teknis saat mengompresi video ini. Harap hubungi Admin.</p>
                                    </div>
                                @elseif ($video->video_path)
                                    <div class="custom-video-player" id="videoPlayer">
                                        <video id="mainVideo" src="{{ route('video.stream', $video->id) }}" autoplay
                                            preload="auto" playsinline></video>

                                        <!-- 2x Speed Banner Overlay -->
                                        <div class="speed-indicator-overlay" id="speedIndicatorOverlay">
                                            <svg class="w-4.5 h-4.5" fill="currentColor" viewBox="0 0 20 20"
                                                style="width: 18px; height: 18px;">
                                                <path
                                                    d="M4.555 5.168A1 1 0 003 6v8a1 1 0 001.555.832l6-4a1 1 0 000-1.664l-6-4zM11.555 5.168A1 1 0 0010 6v8a1 1 0 001.555.832l6-4a1 1 0 000-1.664l-6-4z" />
                                            </svg>
                                            <span>2x Kecepatan</span>
                                        </div>

                                        <!-- Play/Pause Overlay Animation -->
                                        <div class="play-pause-overlay" id="playPauseOverlay">
                                            <div class="overlay-icon">
                                                <!-- SVG icon injected by JS -->
                                            </div>
                                        </div>

                                        <!-- Spinner Loader -->
                                        <div class="spinner-container" id="spinner">
                                            <div class="player-spinner"></div>
                                        </div>

                                        <!-- Player Controls -->
                                        <div class="player-controls">
                                            <!-- Progress Area (Seekbar) -->
                                            <div class="progress-area" id="progressArea">
                                                <!-- Custom Preview Tooltip -->
                                                <div class="player-preview-tooltip" id="previewTooltip">
                                                    <div class="preview-video-container">
                                                        <video id="previewVideo"
                                                            src="{{ route('video.stream', $video->id) }}" muted
                                                            preload="auto"></video>
                                                    </div>
                                                    <div class="preview-time-badge" id="hoverTime">0:00</div>
                                                </div>
                                                <div class="progress-bar-wrapper">
                                                    <div class="progress-bg"></div>
                                                    <div class="buffer-progress" id="bufferProgress"></div>
                                                    <div class="current-progress" id="currentProgress"></div>
                                                    <div class="scrubber-dot" id="scrubberDot"></div>
                                                </div>
                                            </div>

                                            <!-- Controls Buttons Row -->
                                            <div class="controls-bar">
                                                <div class="left-controls">
                                                    <!-- Play/Pause Button -->
                                                    <button class="control-btn" id="playPauseBtn"
                                                        title="Play/Pause (k)">
                                                        <svg class="play-icon" viewBox="0 0 24 24" fill="currentColor">
                                                            <path d="M8 5v14l11-7z" />
                                                        </svg>
                                                        <svg class="pause-icon hidden" viewBox="0 0 24 24"
                                                            fill="currentColor">
                                                            <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />
                                                        </svg>
                                                    </button>

                                                    <!-- Rewind 10s -->
                                                    <button class="control-btn" id="rewindBtn"
                                                        title="Mundur 10 detik (j)">
                                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                                            <path
                                                                d="M12 5V1L7 6l5 5V7c3.31 0 6 2.69 6 6s-2.69 6-6 6-6-2.69-6-6H4c0 4.42 3.58 8 8 8s8-3.58 8-8-3.58-8-8-8zm-1.33 9.47c-.1-.38-.28-.68-.53-.89s-.57-.32-.95-.32c-.28 0-.53.07-.74.2s-.37.31-.48.54c-.11.23-.17.49-.17.78 0 .28.05.53.16.75s.26.39.46.52.43.19.69.19c.35 0 .63-.1.84-.3s.35-.49.42-.85h-.93v-.82h1.76v1.9c-.18.33-.45.6-.79.8s-.75.31-1.21.31c-.55 0-1.02-.15-1.42-.45s-.7-.72-.9-1.25-.3-1.14-.3-1.83.1-1.3.31-1.82.52-.94.92-1.23 1-.44 1.54-.44c.48 0 .9.11 1.25.32s.62.5.8.87c.18.37.28.8.29 1.29h-.92c-.01-.29-.07-.53-.16-.71s-.23-.3-.42-.37-.41-.1-.66-.1c-.38 0-.7.13-.94.39s-.41.63-.48 1.12c-.08.49-.07 1.02.02 1.6.09.58.28.98.57 1.2s.64.33.95.33c.33 0 .61-.08.83-.23s.38-.37.47-.64c.09-.27.13-.58.13-.93H10.67z" />
                                                        </svg>
                                                    </button>

                                                    <!-- Fast Forward 10s -->
                                                    <button class="control-btn" id="forwardBtn"
                                                        title="Maju 10 detik (l)">
                                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                                            <path
                                                                d="M12 5V1l5 5-5 5V7c-3.31 0-6 2.69-6 6s2.69 6 6 6 6-2.69 6-6h2c0 4.42-3.58 8-8 8s-8-3.58-8-8 3.58-8 8-8zm1.33 9.47c-.1-.38-.28-.68-.53-.89s-.57-.32-.95-.32c-.28 0-.53.07-.74.2s-.37.31-.48.54c-.11.23-.17.49-.17.78 0 .28.05.53.16.75s.26.39.46.52.43.19.69.19c.35 0 .63-.1.84-.3s.35-.49.42-.85h-.93v-.82h1.76v1.9c-.18.33-.45.6-.79.8s-.75.31-1.21.31c-.55 0-1.02-.15-1.42-.45s-.7-.72-.9-1.25-.3-1.14-.3-1.83.1-1.3.31-1.82.52-.94.92-1.23 1-.44 1.54-.44c.48 0 .9.11 1.25.32s.62.5.8.87c.18.37.28.8.29 1.29h-.92c-.01-.29-.07-.53-.16-.71s-.23-.3-.42-.37-.41-.1-.66-.1c-.38 0-.7.13-.94.39s-.41.63-.48 1.12c-.08.49-.07 1.02.02 1.6.09.58.28.98.57 1.2s.64.33.95.33c.33 0 .61-.08.83-.23s.38-.37.47-.64c.09-.27.13-.58.13-.93H13.67z" />
                                                        </svg>
                                                    </button>

                                                    <!-- Volume Controls -->
                                                    <div class="volume-container">
                                                        <button class="control-btn" id="muteBtn" title="Muted (m)">
                                                            <svg class="volume-up-icon" viewBox="0 0 24 24"
                                                                fill="currentColor">
                                                                <path
                                                                    d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z" />
                                                            </svg>
                                                            <svg class="volume-mute-icon hidden" viewBox="0 0 24 24"
                                                                fill="currentColor">
                                                                <path
                                                                    d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.21.05-.42.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z" />
                                                            </svg>
                                                        </button>
                                                        <input type="range" class="volume-slider" id="volumeSlider"
                                                            min="0" max="1" step="0.05" value="1">
                                                    </div>

                                                    <!-- Time Label -->
                                                    <div class="time-display">
                                                        <span id="currentTime">00:00</span> / <span
                                                            id="durationTime">00:00</span>
                                                    </div>
                                                </div>

                                                <div class="right-controls">
                                                    <!-- Playback Speed Button -->
                                                    <div class="speed-container">
                                                        <button class="speed-btn" id="speedBtn"
                                                            title="Kecepatan Putar">1.0x</button>
                                                        <div class="speed-options" id="speedOptions">
                                                            <div class="speed-item" data-speed="0.5">0.5x</div>
                                                            <div class="speed-item active" data-speed="1">Normal</div>
                                                            <div class="speed-item" data-speed="1.25">1.25x</div>
                                                            <div class="speed-item" data-speed="1.5">1.5x</div>
                                                            <div class="speed-item" data-speed="2">2.0x</div>
                                                            <div class="speed-item" data-speed="3">3.0x</div>
                                                            <div class="speed-item" data-speed="4">4.0x</div>
                                                        </div>
                                                    </div>

                                                    <!-- Fullscreen Button -->
                                                    <button class="control-btn" id="fullscreenBtn"
                                                        title="Layar Penuh (f)">
                                                        <svg class="fullscreen-enter" viewBox="0 0 24 24"
                                                            fill="currentColor">
                                                            <path
                                                                d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z" />
                                                        </svg>
                                                        <svg class="fullscreen-exit hidden" viewBox="0 0 24 24"
                                                            fill="currentColor">
                                                            <path
                                                                d="M5 16h3v3h2v-5H5v2zm3-8H5v2h5V5H8v3zm6 11h2v-3h3v-2h-5v5zm2-11V5h-2v5h5V8h-3z" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div
                                        class="w-full h-full flex flex-col items-center justify-center bg-slate-950 text-slate-500 p-8 text-center" style="min-height: 380px; aspect-ratio: 16/9;">
                                        <svg class="w-16 h-16 mb-4 text-slate-700" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <p class="font-bold">File video tidak ditemukan</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Info and Countdown Column -->
                    <div class="info-column">

                        <!-- Watch Timer / Countdown Card -->
                        <div class="timer-card">
                            @if (auth()->user() && auth()->user()->role === 'admin')
                                <div class="timer-header flex items-center gap-2 mb-3">
                                    <div class="timer-icon-wrapper"
                                        style="background: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.2);">
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                            </path>
                                        </svg>
                                    </div>
                                    <span class="text-xs uppercase tracking-wider text-slate-300 font-bold">Status
                                        Akses</span>
                                </div>
                                <div class="timer-body">
                                    <div class="countdown-container text-center py-4">
                                        <span class="text-lg font-bold text-emerald-400">Akses Penuh (Admin)</span>
                                    </div>
                                    <div class="expiry-badge"
                                        style="background: rgba(16, 185, 129, 0.06); border-color: rgba(16, 185, 129, 0.15); color: #34d399;">
                                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <span>Tidak Ada Batas Waktu</span>
                                    </div>
                                </div>
                            @else
                                <div class="timer-header flex items-center gap-2 mb-3">
                                    <div class="timer-icon-wrapper">
                                        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs uppercase tracking-wider text-slate-300 font-bold">Sisa Waktu
                                        Menonton</span>
                                </div>
                                <div class="timer-body">
                                    <div id="countdown" class="countdown-container">Menghitung...</div>
                                    <div class="expiry-badge">
                                        <svg class="w-3.5 h-3.5 text-red-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <span>Batas Akses: {{ $access->valid_until->format('d M Y H:i') }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Video Metadata Card -->
                        <div class="metadata-card">
                            @if ($video->category)
                                <div class="mb-4">
                                    <span
                                        class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-widest text-indigo-100 bg-gradient-to-r from-indigo-500/15 to-purple-500/5 border border-indigo-500/30 shadow-lg shadow-indigo-500/5 transition-all duration-300 hover:border-indigo-400/50 hover:shadow-indigo-500/10">
                                        <span class="relative flex h-2 w-2">
                                            <span
                                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                            <span
                                                class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                                        </span>
                                        <span class="text-indigo-400 font-extrabold">#</span>
                                        <span>{{ $video->category->name }}</span>
                                    </span>
                                </div>
                            @endif
                            <h1 class="text-xl sm:text-2xl font-extrabold text-white mb-3 leading-tight">
                                {{ $video->title }}
                            </h1>
                            <hr class="border-slate-800/80 mb-4">
                            <div class="description-section">
                                <div class="description-header flex items-center gap-2 mb-2">
                                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h7"></path>
                                    </svg>
                                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide">Deskripsi
                                        Video</h3>
                                </div>
                                <div class="video-description-box">
                                    {{ $video->description ?: 'Tidak ada deskripsi untuk video ini.' }}
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            window.WatchConfig = {
                isAdmin: {{ (auth()->user() && auth()->user()->role === 'admin') ? 'true' : 'false' }},
                expiryTime: "{{ $access ? $access->valid_until->toIso8601String() : '' }}",
                dashboardUrl: "{{ route('dashboard') }}"
            };
        </script>
        @vite(['resources/js/watch.js'])
    @endpush
</x-app-layout>
