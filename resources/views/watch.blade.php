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
                                @if ($video->video_path)
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
                                        class="w-full h-full flex flex-col items-center justify-center bg-slate-950 text-slate-500 p-8 text-center">
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

    <!-- Custom Video Player Controls & Countdown Timer script -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // ================= COUNTDOWN TIMER LOGIC =================
            @if (auth()->user() && auth()->user()->role === 'admin')
                // Akses Penuh Admin: Tidak perlu logika countdown timer
            @else
                const expiryTime = new Date("{{ $access->valid_until->toIso8601String() }}").getTime();
                const countdownEl = document.getElementById("countdown");

                function updateTimer() {
                    const now = new Date();
                    const expiry = new Date(expiryTime);
                    const distance = expiry.getTime() - now.getTime();

                    if (distance <= 0) {
                        clearInterval(timerInterval);
                        countdownEl.innerHTML = "WAKTU AKSES HABIS";
                        alert("Waktu menonton Anda telah habis!");
                        window.location.href = "{{ route('dashboard') }}";
                        return;
                    }

                    // Hitung selisih kalender (tahun, bulan, hari, jam, menit, detik)
                    let years = expiry.getFullYear() - now.getFullYear();
                    let months = expiry.getMonth() - now.getMonth();
                    let days = expiry.getDate() - now.getDate();
                    let hours = expiry.getHours() - now.getHours();
                    let minutes = expiry.getMinutes() - now.getMinutes();
                    let seconds = expiry.getSeconds() - now.getSeconds();

                    if (seconds < 0) {
                        minutes--;
                        seconds += 60;
                    }
                    if (minutes < 0) {
                        hours--;
                        minutes += 60;
                    }
                    if (hours < 0) {
                        days--;
                        hours += 24;
                    }
                    if (days < 0) {
                        months--;
                        // Dapatkan jumlah hari dari bulan sebelumnya
                        const prevMonth = new Date(expiry.getFullYear(), expiry.getMonth(), 0);
                        days += prevMonth.getDate();
                    }
                    if (months < 0) {
                        years--;
                        months += 12;
                    }

                    let html = `
                        <div class="countdown-row">
                            <div class="countdown-segment">
                                <span class="countdown-number">${String(years).padStart(2, '0')}</span>
                                <span class="countdown-label">Thn</span>
                            </div>
                            <div class="countdown-segment">
                                <span class="countdown-number">${String(months).padStart(2, '0')}</span>
                                <span class="countdown-label">Bln</span>
                            </div>
                            <div class="countdown-segment">
                                <span class="countdown-number">${String(days).padStart(2, '0')}</span>
                                <span class="countdown-label">Hari</span>
                            </div>
                        </div>
                        <div class="countdown-row">
                            <div class="countdown-segment">
                                <span class="countdown-number">${String(hours).padStart(2, '0')}</span>
                                <span class="countdown-label">Jam</span>
                            </div>
                            <div class="countdown-segment">
                                <span class="countdown-number">${String(minutes).padStart(2, '0')}</span>
                                <span class="countdown-label">Mnt</span>
                            </div>
                            <div class="countdown-segment">
                                <span class="countdown-number">${String(seconds).padStart(2, '0')}</span>
                                <span class="countdown-label">Detik</span>
                            </div>
                        </div>
                    `;

                    countdownEl.innerHTML = html;
                }

                updateTimer();
                const timerInterval = setInterval(updateTimer, 1000);
            @endif

            // ================= CUSTOM PLAYER LOGIC =================
            const videoPlayer = document.getElementById('videoPlayer');
            const mainVideo = document.getElementById('mainVideo');

            if (!videoPlayer || !mainVideo) return;

            const playPauseBtn = document.getElementById('playPauseBtn');
            const playIcon = playPauseBtn.querySelector('.play-icon');
            const pauseIcon = playPauseBtn.querySelector('.pause-icon');
            const rewindBtn = document.getElementById('rewindBtn');
            const forwardBtn = document.getElementById('forwardBtn');
            const muteBtn = document.getElementById('muteBtn');
            const volumeUpIcon = muteBtn.querySelector('.volume-up-icon');
            const volumeMuteIcon = muteBtn.querySelector('.volume-mute-icon');
            const volumeSlider = document.getElementById('volumeSlider');
            const currentTimeEl = document.getElementById('currentTime');
            const durationTimeEl = document.getElementById('durationTime');
            const speedBtn = document.getElementById('speedBtn');
            const speedOptions = document.getElementById('speedOptions');
            const speedItems = document.querySelectorAll('.speed-item');
            const fullscreenBtn = document.getElementById('fullscreenBtn');
            const fullscreenEnter = fullscreenBtn.querySelector('.fullscreen-enter');
            const fullscreenExit = fullscreenBtn.querySelector('.fullscreen-exit');
            const progressArea = document.getElementById('progressArea');
            const hoverTime = document.getElementById('hoverTime');
            const bufferProgress = document.getElementById('bufferProgress');
            const currentProgress = document.getElementById('currentProgress');
            const scrubberDot = document.getElementById('scrubberDot');
            const playPauseOverlay = document.getElementById('playPauseOverlay');
            const spinner = document.getElementById('spinner');
            const previewTooltip = document.getElementById('previewTooltip');
            const previewVideo = document.getElementById('previewVideo');
            const speedIndicatorOverlay = document.getElementById('speedIndicatorOverlay');

            let isDragging = false;

            // 1. Play / Pause Control
            function togglePlay() {
                if (mainVideo.paused) {
                    mainVideo.play();
                } else {
                    mainVideo.pause();
                }
            }

            playPauseBtn.addEventListener('click', togglePlay);

            // ================= 2X SPEED ON HOLD LOGIC =================
            let holdTimeout;
            let isHolding = false;
            let originalPlaybackRate = 1;
            let preventClick = false;

            const startHold = (e) => {
                // Only left click for mouse, or touch events
                if (e.type === 'mousedown' && e.button !== 0) return;

                // Check click/touch position relative to the video width
                const rect = mainVideo.getBoundingClientRect();
                const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                const clickX = clientX - rect.left;
                const percentage = clickX / rect.width;

                // Only trigger hold-to-speed-up if click/touch is on the left (< 40%) or right (> 60%)
                if (percentage >= 0.4 && percentage <= 0.6) {
                    return;
                }

                isHolding = false;
                originalPlaybackRate = mainVideo.playbackRate;

                holdTimeout = setTimeout(() => {
                    isHolding = true;
                    preventClick = true;
                    mainVideo.playbackRate = 2.0;
                    if (speedIndicatorOverlay) {
                        speedIndicatorOverlay.classList.add('active');
                    }
                    // Temporarily update speed button text
                    speedBtn.textContent = '2.0x';
                }, 450); // 450ms long press
            };

            const endHold = () => {
                clearTimeout(holdTimeout);
                if (isHolding) {
                    isHolding = false;
                    mainVideo.playbackRate = originalPlaybackRate;
                    if (speedIndicatorOverlay) {
                        speedIndicatorOverlay.classList.remove('active');
                    }
                    // Restore speed button text
                    const currentActiveSpeedItem = document.querySelector('.speed-item.active');
                    if (currentActiveSpeedItem) {
                        const speed = parseFloat(currentActiveSpeedItem.dataset.speed);
                        speedBtn.textContent = speed === 1 ? 'Normal' : `${speed}x`;
                    }
                }
            };

            mainVideo.addEventListener('mousedown', startHold);
            mainVideo.addEventListener('mouseup', endHold);
            mainVideo.addEventListener('mouseleave', endHold);

            mainVideo.addEventListener('touchstart', startHold, {
                passive: true
            });
            mainVideo.addEventListener('touchend', endHold);
            mainVideo.addEventListener('touchcancel', endHold);

            mainVideo.addEventListener('click', (e) => {
                if (preventClick) {
                    preventClick = false;
                    return;
                }
                togglePlay();
            });

            mainVideo.addEventListener('play', () => {
                playIcon.classList.add('hidden');
                pauseIcon.classList.remove('hidden');
                showOverlayIcon('play');
                showControls();
            });

            mainVideo.addEventListener('pause', () => {
                playIcon.classList.remove('hidden');
                pauseIcon.classList.add('hidden');
                showOverlayIcon('pause');
                showControls();
            });

            // Play/Pause Center Animation
            function showOverlayIcon(type) {
                const iconContainer = playPauseOverlay.querySelector('.overlay-icon');
                if (type === 'play') {
                    iconContainer.innerHTML =
                        `<svg viewBox="0 0 24 24" class="w-8 h-8"><path d="M8 5v14l11-7z"/></svg>`;
                } else {
                    iconContainer.innerHTML =
                        `<svg viewBox="0 0 24 24" class="w-8 h-8"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>`;
                }
                playPauseOverlay.classList.remove('animate');
                void playPauseOverlay.offsetWidth; // Trigger reflow
                playPauseOverlay.classList.add('animate');
            }

            // 2. Format Time Helper
            function formatTime(seconds) {
                if (isNaN(seconds) || seconds === Infinity) return "0:00";
                const hrs = Math.floor(seconds / 3600);
                const mins = Math.floor((seconds % 3600) / 60);
                const secs = Math.floor(seconds % 60);

                let result = "";
                if (hrs > 0) {
                    result += hrs + ":" + (mins < 10 ? "0" : "");
                }
                result += mins + ":" + (secs < 10 ? "0" : "") + secs;
                return result;
            }

            // 3. Time Update & Buffer Progress
            mainVideo.addEventListener('timeupdate', () => {
                if (isDragging) return;
                const current = mainVideo.currentTime;
                const duration = mainVideo.duration || 0;
                currentTimeEl.textContent = formatTime(current);

                if (duration > 0) {
                    const percent = (current / duration) * 100;
                    currentProgress.style.width = `${percent}%`;
                    scrubberDot.style.left = `${percent}%`;
                }
            });

            mainVideo.addEventListener('loadedmetadata', () => {
                durationTimeEl.textContent = formatTime(mainVideo.duration);
            });

            // Set duration immediately if metadata is already loaded
            if (mainVideo.readyState >= 1) {
                durationTimeEl.textContent = formatTime(mainVideo.duration);
            }

            mainVideo.addEventListener('progress', () => {
                const duration = mainVideo.duration || 0;
                if (duration > 0 && mainVideo.buffered.length > 0) {
                    let bufferedEnd = 0;
                    for (let i = 0; i < mainVideo.buffered.length; i++) {
                        if (mainVideo.buffered.start(i) <= mainVideo.currentTime && mainVideo.buffered.end(
                                i) >= mainVideo.currentTime) {
                            bufferedEnd = mainVideo.buffered.end(i);
                            break;
                        }
                    }
                    if (bufferedEnd === 0 && mainVideo.buffered.length > 0) {
                        bufferedEnd = mainVideo.buffered.end(mainVideo.buffered.length - 1);
                    }
                    const percent = (bufferedEnd / duration) * 100;
                    bufferProgress.style.width = `${percent}%`;
                }
            });

            // 4. Seek / Scrubbing logic (Drag & Slide)
            function getTimelinePosition(e) {
                const rect = progressArea.getBoundingClientRect();
                const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                let x = clientX - rect.left;
                x = Math.max(0, Math.min(x, rect.width));
                return x / rect.width;
            }

            function seek(e) {
                const percent = getTimelinePosition(e);
                const time = percent * mainVideo.duration;
                if (!isNaN(time)) {
                    mainVideo.currentTime = time;
                }
            }

            // Timeline Click
            progressArea.addEventListener('click', seek);

            // Timeline Drag/Slide (Mouse)
            progressArea.addEventListener('mousedown', (e) => {
                isDragging = true;
                progressArea.classList.add('dragging');
                seek(e);

                const moveHandler = (moveEvent) => {
                    if (isDragging) {
                        const percent = getTimelinePosition(moveEvent);
                        currentProgress.style.width = `${percent * 100}%`;
                        scrubberDot.style.left = `${percent * 100}%`;

                        const time = percent * mainVideo.duration;
                        if (!isNaN(time)) {
                            currentTimeEl.textContent = formatTime(time);
                            if (hoverTime) hoverTime.textContent = formatTime(time);

                            const rect = progressArea.getBoundingClientRect();
                            const clientX = moveEvent.clientX;
                            let x = clientX - rect.left;
                            x = Math.max(0, Math.min(x, rect.width));
                            if (previewTooltip) {
                                previewTooltip.style.left = `${x}px`;
                                previewTooltip.classList.add('active');
                            }
                            if (previewVideo) previewVideo.currentTime = time;
                        }
                    }
                };

                const upHandler = (upEvent) => {
                    if (isDragging) {
                        seek(upEvent);
                        isDragging = false;
                        progressArea.classList.remove('dragging');
                        if (previewTooltip) previewTooltip.classList.remove('active');
                        document.removeEventListener('mousemove', moveHandler);
                        document.removeEventListener('mouseup', upHandler);
                    }
                };

                document.addEventListener('mousemove', moveHandler);
                document.addEventListener('mouseup', upHandler);
            });

            // Timeline Drag/Slide (Touch Mobile)
            progressArea.addEventListener('touchstart', (e) => {
                isDragging = true;
                progressArea.classList.add('dragging');
                seek(e);

                const touchMoveHandler = (moveEvent) => {
                    if (isDragging) {
                        const percent = getTimelinePosition(moveEvent);
                        currentProgress.style.width = `${percent * 100}%`;
                        scrubberDot.style.left = `${percent * 100}%`;

                        const time = percent * mainVideo.duration;
                        if (!isNaN(time)) {
                            currentTimeEl.textContent = formatTime(time);
                            if (hoverTime) hoverTime.textContent = formatTime(time);

                            const rect = progressArea.getBoundingClientRect();
                            const clientX = moveEvent.touches[0].clientX;
                            let x = clientX - rect.left;
                            x = Math.max(0, Math.min(x, rect.width));
                            if (previewTooltip) {
                                previewTooltip.style.left = `${x}px`;
                                previewTooltip.classList.add('active');
                            }
                            if (previewVideo) previewVideo.currentTime = time;
                        }
                    }
                };

                const touchEndHandler = () => {
                    if (isDragging) {
                        isDragging = false;
                        progressArea.classList.remove('dragging');
                        if (previewTooltip) previewTooltip.classList.remove('active');
                        const duration = mainVideo.duration || 0;
                        const currentPct = parseFloat(currentProgress.style.width) / 100;
                        const time = currentPct * duration;
                        if (!isNaN(time)) {
                            mainVideo.currentTime = time;
                        }
                        document.removeEventListener('touchmove', touchMoveHandler);
                        document.removeEventListener('touchend', touchEndHandler);
                    }
                };

                document.addEventListener('touchmove', touchMoveHandler, {
                    passive: false
                });
                document.addEventListener('touchend', touchEndHandler);
            }, {
                passive: true
            });

            // Timeline Hover Time Tooltip
            progressArea.addEventListener('mousemove', (e) => {
                const rect = progressArea.getBoundingClientRect();
                let x = e.clientX - rect.left;
                x = Math.max(0, Math.min(x, rect.width));
                const percent = x / rect.width;
                const time = percent * mainVideo.duration;
                if (!isNaN(time)) {
                    if (hoverTime) hoverTime.textContent = formatTime(time);
                    if (previewTooltip) {
                        previewTooltip.style.left = `${x}px`;
                        previewTooltip.classList.add('active');
                    }
                    if (previewVideo) {
                        previewVideo.currentTime = time;
                    }
                }
            });

            progressArea.addEventListener('mouseleave', () => {
                if (previewTooltip) {
                    previewTooltip.classList.remove('active');
                }
            });

            // 5. Playback Speed Control
            speedBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                speedOptions.classList.toggle('active');
            });

            document.addEventListener('click', () => {
                speedOptions.classList.remove('active');
            });

            speedItems.forEach(item => {
                item.addEventListener('click', (e) => {
                    const speed = parseFloat(e.target.dataset.speed);
                    mainVideo.playbackRate = speed;
                    speedBtn.textContent = speed === 1 ? 'Normal' : `${speed}x`;

                    speedItems.forEach(i => i.classList.remove('active'));
                    e.target.classList.add('active');
                });
            });

            // 6. Volume controls
            function setVolume(val) {
                mainVideo.volume = val;
                volumeSlider.value = val;
                if (val == 0) {
                    volumeUpIcon.classList.add('hidden');
                    volumeMuteIcon.classList.remove('hidden');
                    mainVideo.muted = true;
                } else {
                    volumeUpIcon.classList.remove('hidden');
                    volumeMuteIcon.classList.add('hidden');
                    mainVideo.muted = false;
                }
            }

            volumeSlider.addEventListener('input', (e) => {
                setVolume(e.target.value);
            });

            muteBtn.addEventListener('click', () => {
                if (mainVideo.muted) {
                    setVolume(volumeSlider.value || 1);
                } else {
                    mainVideo.muted = true;
                    volumeUpIcon.classList.add('hidden');
                    volumeMuteIcon.classList.remove('hidden');
                }
            });

            // 7. Rewind / Forward (10 seconds)
            rewindBtn.addEventListener('click', () => {
                mainVideo.currentTime = Math.max(0, mainVideo.currentTime - 10);
            });

            forwardBtn.addEventListener('click', () => {
                mainVideo.currentTime = Math.min(mainVideo.duration, mainVideo.currentTime + 10);
            });

            // 8. Fullscreen Control
            function toggleFullscreen() {
                if (!document.fullscreenElement) {
                    videoPlayer.requestFullscreen().then(() => {
                        fullscreenEnter.classList.add('hidden');
                        fullscreenExit.classList.remove('hidden');
                    }).catch(err => {
                        console.error('Error entering fullscreen:', err);
                    });
                } else {
                    document.exitFullscreen().then(() => {
                        fullscreenEnter.classList.remove('hidden');
                        fullscreenExit.classList.add('hidden');
                    });
                }
            }

            fullscreenBtn.addEventListener('click', toggleFullscreen);
            mainVideo.addEventListener('dblclick', toggleFullscreen);

            document.addEventListener('fullscreenchange', () => {
                if (document.fullscreenElement === videoPlayer) {
                    fullscreenEnter.classList.add('hidden');
                    fullscreenExit.classList.remove('hidden');
                } else {
                    fullscreenEnter.classList.remove('hidden');
                    fullscreenExit.classList.add('hidden');
                }
            });

            // 9. Spinner Buffering Loader
            mainVideo.addEventListener('waiting', () => {
                spinner.classList.add('active');
            });

            mainVideo.addEventListener('playing', () => {
                spinner.classList.remove('active');
            });

            mainVideo.addEventListener('seeked', () => {
                spinner.classList.remove('active');
            });

            // 10. Autohide Controls
            let controlsTimeout;

            function showControls() {
                videoPlayer.classList.remove('hide-controls');
                clearTimeout(controlsTimeout);
                if (!mainVideo.paused) {
                    controlsTimeout = setTimeout(() => {
                        videoPlayer.classList.add('hide-controls');
                    }, 3000);
                }
            }

            videoPlayer.addEventListener('mousemove', showControls);
            videoPlayer.addEventListener('click', showControls);

            // 11. Keyboard Hotkeys Control
            document.addEventListener('keydown', (e) => {
                if (document.activeElement.tagName === 'INPUT' || document.activeElement.tagName ===
                    'TEXTAREA') {
                    return;
                }

                const key = e.key.toLowerCase();
                if (key === ' ' || key === 'k') {
                    e.preventDefault();
                    togglePlay();
                } else if (key === 'f') {
                    e.preventDefault();
                    toggleFullscreen();
                } else if (key === 'm') {
                    e.preventDefault();
                    muteBtn.click();
                } else if (key === 'l' || key === 'arrowright') {
                    e.preventDefault();
                    mainVideo.currentTime = Math.min(mainVideo.duration, mainVideo.currentTime + 10);
                } else if (key === 'j' || key === 'arrowleft') {
                    e.preventDefault();
                    mainVideo.currentTime = Math.max(0, mainVideo.currentTime - 10);
                } else if (key === 'arrowup') {
                    e.preventDefault();
                    const newVol = Math.min(1, mainVideo.volume + 0.1);
                    setVolume(newVol);
                } else if (key === 'arrowdown') {
                    e.preventDefault();
                    const newVol = Math.max(0, mainVideo.volume - 0.1);
                    setVolume(newVol);
                }
            });

            // Initial controls activation
            showControls();

            // Explicit play trigger on load (autoload)
            const startPlay = () => {
                const playPromise = mainVideo.play();
                if (playPromise !== undefined) {
                    playPromise.catch(error => {
                        console.log('Unmuted autoplay blocked by browser. Trying muted autoplay...',
                            error);
                        // Mute and try playing again
                        setVolume(0);
                        mainVideo.play().catch(err => {
                            console.log('Muted autoplay also blocked:', err);
                        });
                    });
                }
            };

            if (mainVideo.readyState >= 1) {
                startPlay();
            } else {
                mainVideo.addEventListener('loadedmetadata', startPlay, {
                    once: true
                });
            }

            // Fallback: play on first interaction on the page if still paused
            const playOnInteraction = () => {
                if (mainVideo.paused) {
                    mainVideo.play().catch(err => console.log('Interaction play blocked:', err));
                }
                document.removeEventListener('click', playOnInteraction);
                document.removeEventListener('keydown', playOnInteraction);
            };
            document.addEventListener('click', playOnInteraction);
            document.addEventListener('keydown', playOnInteraction);
        });
    </script>
</x-app-layout>
