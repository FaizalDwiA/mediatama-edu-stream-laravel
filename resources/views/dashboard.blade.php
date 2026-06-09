<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

        .dashboard-wrapper {
            font-family: 'Outfit', sans-serif !important;
            background-color: #0b0f19; /* Deep space dark */
            color: #f8fafc;
            min-height: calc(100vh - 65px);
            transition: all 0.3s ease;
        }

        .dashboard-hero {
            background: linear-gradient(135deg, #1e1b4b 0%, #311042 60%, #0b0f19 100%);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 3.5rem 3rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            margin-bottom: 3rem;
        }

        /* Abstract glowing blobs for hero */
        .dashboard-hero::before {
            content: '';
            position: absolute;
            top: -20%;
            right: -10%;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.25) 0%, rgba(0,0,0,0) 70%);
            z-index: 1;
            pointer-events: none;
        }

        .dashboard-hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: 10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(168, 85, 247, 0.2) 0%, rgba(0,0,0,0) 70%);
            z-index: 1;
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 650px;
        }

        /* Section Heading */
        .section-heading {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 2rem;
            letter-spacing: -0.025em;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-heading svg {
            color: #a855f7;
        }

        /* Video Catalog Grid */
        .video-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-top: 1.5rem;
        }

        @media (max-width: 1024px) {
            .video-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .video-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Video Card styling */
        .video-card {
            background: rgba(17, 24, 39, 0.6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            height: 100%;
        }

        .video-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.65), 0 0 20px rgba(99, 102, 241, 0.15);
            border-color: rgba(255, 255, 255, 0.12);
        }

        /* Video Thumbnail Card */
        .video-thumbnail {
            width: 100%;
            height: 180px;
            border-radius: 14px;
            overflow: hidden;
            position: relative;
            margin-bottom: 1.25rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
        }

        /* Hover image Zoom */
        .video-thumbnail-bg {
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .video-card:hover .video-thumbnail-bg {
            transform: scale(1.08);
        }

        /* Dynamic Gradients for missing images */
        .grad-1 { background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); }
        .grad-2 { background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%); }
        .grad-3 { background: linear-gradient(135deg, #ec4899 0%, #f43f5e 100%); }
        .grad-4 { background: linear-gradient(135deg, #f59e0b 0%, #e11d48 100%); }
        .grad-5 { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .grad-6 { background: linear-gradient(135deg, #8b5cf6 0%, #d946ef 100%); }

        /* Glowing Play Overlay */
        .play-overlay {
            position: absolute;
            inset: 0;
            background: rgba(11, 15, 25, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s ease;
        }

        .video-card:hover .play-overlay {
            background: rgba(11, 15, 25, 0.55);
        }

        .play-btn-circle {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .play-btn-circle svg {
            width: 20px;
            height: 20px;
            fill: #ffffff;
            margin-left: 2px;
            transition: transform 0.3s ease;
        }

        .video-card:hover .play-btn-circle {
            background: #ffffff;
            color: #0f172a;
            border-color: #ffffff;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.6);
            transform: scale(1.1);
        }

        .video-card:hover .play-btn-circle svg {
            fill: #0f172a;
            transform: scale(1.05);
        }

        /* E-Learning Tag */
        .video-tag {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(11, 15, 25, 0.75);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #e2e8f0;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 8px;
            letter-spacing: 0.05em;
            z-index: 5;
        }

        /* Text colors inside card */
        .video-title {
            color: #f8fafc !important;
            font-size: 1.15rem !important;
            font-weight: 700 !important;
            margin-bottom: 0.5rem;
            transition: color 0.3s ease;
        }

        .video-card:hover .video-title {
            color: #a855f7 !important; /* Purple accent on hover */
        }

        .video-desc {
            color: #94a3b8 !important;
            font-size: 0.875rem !important;
            line-height: 1.5 !important;
        }

        /* Custom buttons styling */
        .btn-gradient-blue {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.35) !important;
        }
        .btn-gradient-blue:hover {
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.55) !important;
            transform: translateY(-2px);
            filter: brightness(1.1);
        }

        .btn-gradient-green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            box-shadow: 0 4px 15px rgba(5, 150, 105, 0.35) !important;
        }
        .btn-gradient-green:hover {
            box-shadow: 0 6px 20px rgba(5, 150, 105, 0.55) !important;
            transform: translateY(-2px);
            filter: brightness(1.1);
        }

        .btn-gradient-red {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.35) !important;
        }
        .btn-gradient-red:hover {
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.55) !important;
            transform: translateY(-2px);
            filter: brightness(1.1);
        }

        .btn-gradient-orange {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%) !important;
            box-shadow: 0 4px 15px rgba(234, 88, 12, 0.35) !important;
        }
        .btn-gradient-orange:hover {
            box-shadow: 0 6px 20px rgba(234, 88, 12, 0.55) !important;
            transform: translateY(-2px);
            filter: brightness(1.1);
        }

        .btn-pending {
            background: rgba(245, 158, 11 0.1) !important;
            border: 1px solid rgba(245, 158, 11, 0.25) !important;
            color: #f59e0b !important;
        }

        .dashboard-btn {
            border-radius: 12px !important;
            padding: 0.8rem 1.25rem !important;
            font-size: 0.9rem !important;
            font-weight: 600 !important;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
            cursor: pointer;
            text-align: center;
            display: block;
            width: 100%;
            box-sizing: border-box;
            border: none;
            color: #ffffff;
        }

        .dashboard-btn:active {
            transform: translateY(0);
        }

        /* Access Limit Status Layout */
        .access-status-approved {
            color: #34d399 !important; /* Green text */
            font-size: 0.8rem !important;
            font-weight: 600 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0.375rem !important;
            margin-bottom: 0.75rem !important;
            margin-top: 0.5rem !important;
        }

        .access-status-expired {
            color: #f87171 !important; /* Red text */
            font-size: 0.8rem !important;
            font-weight: 600 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0.375rem !important;
            margin-bottom: 0.75rem !important;
            margin-top: 0.5rem !important;
        }

        /* Session Alert Styling */
        .session-alert {
            border-radius: 14px;
            padding: 1rem 1.25rem;
            margin-bottom: 2.5rem;
            display: flex;
            align-items: center;
            font-weight: 500;
            font-size: 0.95rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.25);
            color: #34d399;
        }

        .alert-error {
            background: rgba(244, 63, 94, 0.1);
            border: 1px solid rgba(244, 63, 94, 0.25);
            color: #f43f5e;
        }
    </style>
    @endpush

    <div class="dashboard-wrapper py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Toast Success / Error Notifications -->
            @if(session('success'))
                <div class="session-alert alert-success">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="session-alert alert-error">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Hero Welcome Banner -->
            <div class="dashboard-hero">
                <div class="hero-content">
                    <span class="text-sm font-semibold tracking-wider text-indigo-400 uppercase">E-Learning Platform</span>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-white mt-1 mb-3 leading-tight">
                        Selamat Datang, {{ auth()->user()->name }}! 👋
                    </h1>
                    <p class="text-slate-300 text-base sm:text-lg font-light leading-relaxed">
                        Temukan berbagai video pembelajaran interaktif terbaik untuk melatih dan mengasah keahlian baru Anda sekarang.
                    </p>
                </div>
            </div>

            <!-- Grid Header Title -->
            <h2 class="section-heading text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <span>Katalog Video EduStream</span>
            </h2>

            <!-- Video Catalog Grid -->
            <div class="video-grid">
                @forelse($videos as $video)
                    @php
                        $userRequest = $requests->get($video->id);
                        $isExpired = $userRequest && $userRequest->valid_until && \Carbon\Carbon::now()->gt($userRequest->valid_until);
                        $gradIndex = ($loop->index % 6) + 1;
                    @endphp

                    <div class="video-card">
                        <div>
                            <!-- Video Thumbnail with hover overlay play button -->
                            <div class="video-thumbnail">
                                <div class="video-tag">VIDEO TUTORIAL</div>
                                <div class="video-thumbnail-bg grad-{{ $gradIndex }}"></div>
                                <div class="play-overlay">
                                    <div class="play-btn-circle">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Video Info -->
                            <h3 class="video-title">{{ $video->title }}</h3>
                            <p class="video-desc">{{ Str::limit($video->description, 100) }}</p>
                        </div>

                        <!-- Video Actions -->
                        <div class="mt-6">
                            @if(!$userRequest)
                                <form action="{{ route('video.request', $video->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dashboard-btn btn-gradient-blue">
                                        Minta Akses Menonton
                                    </button>
                                </form>
                            @elseif($userRequest->status === 'pending')
                                <button disabled class="dashboard-btn btn-pending cursor-not-allowed">
                                    Menunggu Persetujuan Admin
                                </button>
                            @elseif($userRequest->status === 'rejected')
                                <form action="{{ route('video.request', $video->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dashboard-btn btn-gradient-orange">
                                        Akses Ditolak - Minta Ulang
                                    </button>
                                </form>
                            @elseif($userRequest->status === 'approved' && !$isExpired)
                                <div class="access-status-approved">
                                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Batas Akses: {{ $userRequest->valid_until->format('d M Y H:i') }}
                                </div>
                                <a href="{{ route('video.watch', $video->id) }}" class="dashboard-btn btn-gradient-green">
                                    Tonton Video
                                </a>
                            @elseif($isExpired)
                                <div class="access-status-expired">
                                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    Waktu Akses Anda Sudah Habis!
                                </div>
                                <form action="{{ route('video.request', $video->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dashboard-btn btn-gradient-red">
                                        Minta Akses Ulang
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 sm:col-span-2 lg:col-span-3 text-center text-slate-400 py-16 bg-slate-900 bg-opacity-40 border border-dashed border-slate-700 border-opacity-65 rounded-2xl">
                        <svg class="w-16 h-16 mx-auto text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                        </svg>
                        <p class="text-base font-semibold">Belum ada video yang tersedia</p>
                        <p class="text-xs text-slate-500 mt-1">Silakan hubungi Admin untuk menambahkan video ke dalam katalog.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>