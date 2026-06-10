<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

        .watch-wrapper {
            font-family: 'Outfit', sans-serif !important;
            background-color: #0b0f19;
            color: #f8fafc;
            min-height: calc(100vh - 65px);
        }

        /* Desktop: side-by-side layout */
        .watch-layout {
            display: grid;
            grid-template-columns: 2.2fr 1fr;
            gap: 2rem;
            align-items: start;
        }

        @media (max-width: 1024px) {
            .watch-layout {
                grid-template-columns: 1fr;
                gap: 1.25rem;
            }
        }

        .video-column {
            width: 100%;
        }

        .info-column {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        /* Cinema player */
        .cinema-container {
            background: #000000;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.8);
            position: relative;
        }

        @media (max-width: 640px) {
            .cinema-container {
                border-radius: 12px;
            }
        }

        .cinema-glow {
            position: absolute;
            inset: -30px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.18) 0%, rgba(168, 85, 247, 0.12) 40%, rgba(0,0,0,0) 70%);
            filter: blur(40px);
            z-index: -1;
            pointer-events: none;
        }

        .video-player-wrapper {
            position: relative;
            background: #000000;
            width: 100%;
            aspect-ratio: 16/9;
        }

        video {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* Timer Card */
        .timer-card {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.05) 0%, rgba(220, 38, 38, 0.12) 100%);
            border: 1px solid rgba(239, 68, 68, 0.25);
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 10px 25px rgba(220, 38, 38, 0.08);
        }

        @media (max-width: 640px) {
            .timer-card {
                padding: 1rem;
                border-radius: 14px;
                display: flex;
                align-items: center;
                gap: 1rem;
            }

            .timer-card .timer-body {
                flex: 1;
                min-width: 0;
            }

            .timer-card .timer-header {
                margin-bottom: 0.25rem;
            }
        }

        .countdown-time {
            font-size: 2rem;
            font-weight: 700;
            color: #f87171;
            font-family: monospace, 'Outfit';
            text-shadow: 0 0 12px rgba(248, 113, 113, 0.45);
            letter-spacing: 1px;
        }

        @media (max-width: 640px) {
            .countdown-time {
                font-size: 1.5rem;
            }
        }

        /* Metadata Card */
        .metadata-card {
            background: rgba(17, 24, 39, 0.55);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.35);
        }

        @media (max-width: 640px) {
            .metadata-card {
                padding: 1.25rem;
                border-radius: 14px;
            }

            .metadata-card h1 {
                font-size: 1.25rem !important;
                margin-bottom: 0.75rem !important;
            }
        }

        /* Back Button */
        .btn-back {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #cbd5e1;
            padding: 0.55rem 1.1rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateX(-2px);
        }

        /* Mobile wrapper padding */
        @media (max-width: 640px) {
            .watch-wrapper {
                padding-top: 1.25rem !important;
                padding-bottom: 2rem !important;
            }

            .watch-inner {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .watch-back-row {
                margin-bottom: 1rem;
            }
        }
    </style>
    @endpush

    <div class="watch-wrapper py-8 sm:py-12">
        <div class="watch-inner max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Back Button -->
            <div class="watch-back-row mb-5">
                <a href="{{ route('dashboard') }}" class="btn-back">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
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
                                @if($video->video_path)
                                    <video src="{{ asset('storage/' . $video->video_path) }}" controls autoplay controlsList="nodownload"></video>
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center bg-slate-950 text-slate-500 p-8 text-center">
                                        <svg class="w-16 h-16 mb-4 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
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
                            <div class="timer-header flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-xs uppercase tracking-wider text-red-300 font-semibold">Sisa Waktu Menonton</span>
                            </div>
                            <div class="timer-body">
                                <div id="countdown" class="countdown-time">Menghitung...</div>
                                <span class="text-xs text-red-400 block mt-1 opacity-80">
                                    Batas: {{ $access->valid_until->format('d M Y H:i') }}
                                </span>
                            </div>
                        </div>

                        <!-- Video Metadata Card -->
                        <div class="metadata-card">
                            <span class="text-xs font-semibold text-indigo-400 uppercase tracking-wider block mb-1">E-Learning Video</span>
                            <h1 class="text-xl sm:text-2xl font-bold text-white mb-3 leading-tight">{{ $video->title }}</h1>
                            <hr class="border-slate-800 mb-3">
                            <h3 class="text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wide">Deskripsi Video</h3>
                            <p class="text-slate-300 text-sm leading-relaxed whitespace-pre-line">
                                {{ $video->description ?: 'Tidak ada deskripsi untuk video ini.' }}
                            </p>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Live Real-Time Countdown Timer script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const expiryTime = new Date("{{ $access->valid_until->toIso8601String() }}").getTime();
            const countdownEl = document.getElementById("countdown");

            function updateTimer() {
                const now = new Date().getTime();
                const distance = expiryTime - now;

                if (distance <= 0) {
                    clearInterval(timerInterval);
                    countdownEl.innerHTML = "WAKTU AKSES HABIS";
                    alert("Waktu menonton Anda telah habis!");
                    window.location.href = "{{ route('dashboard') }}";
                    return;
                }

                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                let timeString = "";
                if (hours > 0) timeString += hours + "j ";
                timeString += minutes + "m " + seconds + "s";

                countdownEl.innerHTML = timeString;
            }

            updateTimer();
            const timerInterval = setInterval(updateTimer, 1000);
        });
    </script>
</x-app-layout>
