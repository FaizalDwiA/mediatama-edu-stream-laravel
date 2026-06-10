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
                background: radial-gradient(circle, rgba(99, 102, 241, 0.18) 0%, rgba(168, 85, 247, 0.12) 40%, rgba(0, 0, 0, 0) 70%);
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

            /* Custom Video Player Styles (YouTube Inspired) */
            .custom-video-player {
                position: relative;
                width: 100%;
                height: 100%;
                background-color: #000;
                overflow: hidden;
                user-select: none;
                -webkit-user-select: none;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .custom-video-player video {
                width: 100%;
                height: 100%;
                object-fit: contain;
                cursor: pointer;
            }

            /* Play/Pause Center Animation Overlay */
            .play-pause-overlay {
                position: absolute;
                inset: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                pointer-events: none;
                z-index: 5;
                background: rgba(0, 0, 0, 0);
                opacity: 0;
            }

            .play-pause-overlay.animate {
                animation: overlay-fade-scale 0.5s ease-out;
            }

            .overlay-icon {
                background: rgba(15, 23, 42, 0.75);
                border: 1px solid rgba(255, 255, 255, 0.1);
                color: #ffffff;
                padding: 1.25rem;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(4px);
                -webkit-backdrop-filter: blur(4px);
            }

            .overlay-icon svg {
                width: 32px;
                height: 32px;
                fill: currentColor;
            }

            @keyframes overlay-fade-scale {
                0% {
                    opacity: 0;
                    transform: scale(0.6);
                }

                30% {
                    opacity: 1;
                    transform: scale(1.1);
                }

                80% {
                    opacity: 1;
                    transform: scale(1);
                }

                100% {
                    opacity: 0;
                    transform: scale(0.9);
                }
            }

            /* Spinner Loader */
            .spinner-container {
                position: absolute;
                inset: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                background: rgba(0, 0, 0, 0.4);
                z-index: 4;
                pointer-events: none;
                opacity: 0;
                transition: opacity 0.2s ease;
            }

            .spinner-container.active {
                opacity: 1;
            }

            .player-spinner {
                width: 50px;
                height: 50px;
                border: 4px solid rgba(255, 255, 255, 0.25);
                border-radius: 50%;
                border-top-color: #ef4444;
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                to {
                    transform: rotate(360deg);
                }
            }

            /* Controls Container */
            .player-controls {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                background: linear-gradient(to top, rgba(8, 10, 16, 0.95) 0%, rgba(8, 10, 16, 0.6) 60%, transparent 100%);
                padding: 0.65rem 1rem 0.85rem 1rem;
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
                z-index: 10;
                transition: opacity 0.30s cubic-bezier(0.4, 0, 0.2, 1), transform 0.30s cubic-bezier(0.4, 0, 0.2, 1);
                pointer-events: auto;
            }

            .custom-video-player.hide-controls .player-controls {
                opacity: 0;
                transform: translateY(12px);
                pointer-events: none;
            }

            /* Progress Area / Seek Bar */
            .progress-area {
                height: 4px;
                width: 100%;
                cursor: pointer;
                display: flex;
                align-items: center;
                position: relative;
                transition: height 0.1s ease;
            }

            .progress-area:hover {
                height: 8px;
            }

            /* Preview Tooltip Styles */
            .player-preview-tooltip {
                position: absolute;
                bottom: 22px;
                left: 0;
                transform: translateX(-50%);
                width: 140px;
                background: rgba(15, 23, 42, 0.95);
                border: 1px solid rgba(255, 255, 255, 0.15);
                border-radius: 8px;
                padding: 3px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6);
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.15s ease;
                z-index: 50;
                display: flex;
                flex-direction: column;
                align-items: center;
                overflow: hidden;
            }

            .player-preview-tooltip.active {
                opacity: 1;
            }

            .preview-video-container {
                width: 134px;
                height: 75px;
                /* 16:9 ratio */
                border-radius: 6px;
                overflow: hidden;
                background: #000;
            }

            .preview-video-container video {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .preview-time-badge {
                margin-top: 4px;
                color: #ffffff;
                font-size: 0.72rem;
                font-family: monospace, sans-serif;
                font-weight: 600;
            }

            .progress-bar-wrapper {
                height: 100%;
                width: 100%;
                position: relative;
                background: rgba(255, 255, 255, 0.25);
                border-radius: 4px;
                overflow: visible;
            }

            .progress-bg {
                position: absolute;
                inset: 0;
            }

            .buffer-progress {
                position: absolute;
                left: 0;
                top: 0;
                height: 100%;
                width: 0%;
                background: rgba(255, 255, 255, 0.4);
                border-radius: 4px;
                transition: width 0.15s ease;
            }

            .current-progress {
                position: absolute;
                left: 0;
                top: 0;
                height: 100%;
                width: 0%;
                background: #ef4444;
                /* YT Red */
                border-radius: 4px;
            }

            .scrubber-dot {
                position: absolute;
                left: 0%;
                top: 50%;
                transform: translate(-50%, -50%);
                width: 13px;
                height: 13px;
                border-radius: 50%;
                background: #ef4444;
                pointer-events: none;
                opacity: 0;
                transition: opacity 0.15s ease, transform 0.15s ease;
                z-index: 12;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.4);
            }

            .progress-area:hover .scrubber-dot,
            .progress-area.dragging .scrubber-dot {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1.15);
            }

            /* Bottom Controls Row */
            .controls-bar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                width: 100%;
            }

            .left-controls,
            .right-controls {
                display: flex;
                align-items: center;
                gap: 0.85rem;
            }

            .control-btn {
                background: transparent;
                border: none;
                color: #e2e8f0;
                cursor: pointer;
                padding: 5px;
                border-radius: 6px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: color 0.15s ease, background-color 0.15s ease;
            }

            .control-btn:hover {
                color: #ffffff;
                background-color: rgba(255, 255, 255, 0.1);
            }

            .control-btn svg {
                width: 20px;
                height: 20px;
            }

            /* Volume slider slide-out action */
            .volume-container {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .volume-slider {
                width: 0;
                opacity: 0;
                transition: width 0.25s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.25s cubic-bezier(0.4, 0, 0.2, 1);
                cursor: pointer;
                height: 4px;
                accent-color: #ef4444;
                outline: none;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 2px;
            }

            .volume-container:hover .volume-slider,
            .volume-slider:focus,
            .volume-slider:active {
                width: 65px;
                opacity: 1;
            }

            .time-display {
                font-size: 0.825rem;
                color: #cbd5e1;
                font-family: monospace, sans-serif;
                margin-left: 0.25rem;
            }

            /* Playback Speed settings popup */
            .speed-container {
                position: relative;
            }

            .speed-btn {
                font-size: 0.825rem;
                font-weight: 700;
                color: #cbd5e1;
                background: rgba(255, 255, 255, 0.08);
                border: 1px solid rgba(255, 255, 255, 0.12);
                padding: 4px 8px;
                border-radius: 6px;
                cursor: pointer;
                transition: all 0.15s ease;
            }

            .speed-btn:hover {
                color: #ffffff;
                background-color: rgba(255, 255, 255, 0.18);
            }

            .speed-options {
                position: absolute;
                bottom: 38px;
                right: 0;
                background: rgba(15, 23, 42, 0.96);
                border: 1px solid rgba(255, 255, 255, 0.15);
                border-radius: 8px;
                overflow: hidden;
                width: 100px;
                display: flex;
                flex-direction: column;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.55);
                opacity: 0;
                pointer-events: none;
                transform: translateY(8px);
                transition: opacity 0.15s ease, transform 0.15s ease;
                z-index: 20;
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
            }

            .speed-options.active {
                opacity: 1;
                pointer-events: auto;
                transform: translateY(0);
            }

            .speed-item {
                padding: 8px 12px;
                font-size: 0.775rem;
                color: #cbd5e1;
                cursor: pointer;
                text-align: center;
                transition: all 0.15s ease;
                font-weight: 500;
            }

            .speed-item:hover {
                background-color: rgba(255, 255, 255, 0.1);
                color: #ffffff;
            }

            .speed-item.active {
                color: #ef4444;
                font-weight: 700;
                background-color: rgba(239, 68, 68, 0.08);
            }

            /* Timer Card & Metadata Card - Unified Glassmorphism Style */
            .timer-card,
            .metadata-card {
                background: rgba(17, 24, 39, 0.45) !important;
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border: 1px solid rgba(255, 255, 255, 0.06) !important;
                border-radius: 20px !important;
                padding: 1.5rem !important;
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.35) !important;
                transition: all 0.3s ease;
            }

            .timer-card:hover,
            .metadata-card:hover {
                border-color: rgba(255, 255, 255, 0.12) !important;
                box-shadow: 0 20px 45px rgba(0, 0, 0, 0.45) !important;
            }

            .timer-icon-wrapper {
                background: rgba(239, 68, 68, 0.1);
                padding: 6px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                border: 1px solid rgba(239, 68, 68, 0.2);
            }

            @media (max-width: 640px) {
                .timer-card,
                .metadata-card {
                    padding: 1.25rem !important;
                    border-radius: 14px !important;
                }

                .metadata-card h1 {
                    font-size: 1.25rem !important;
                    margin-bottom: 0.75rem !important;
                }
            }

            .countdown-container {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
                margin-top: 0.5rem;
                margin-bottom: 0.25rem;
                width: 100%;
            }

            .countdown-row {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 0.35rem;
                width: 100%;
            }

            .countdown-segment {
                background: rgba(15, 23, 42, 0.55);
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 12px;
                padding: 0.6rem 0.5rem;
                display: flex;
                flex-direction: column;
                align-items: center;
                box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.4), 0 4px 10px rgba(0, 0, 0, 0.25);
                transition: all 0.2s ease;
            }

            .countdown-segment:hover {
                border-color: rgba(239, 68, 68, 0.35);
                background: rgba(15, 23, 42, 0.7);
                transform: translateY(-2px);
                box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.4), 0 6px 16px rgba(239, 68, 68, 0.12);
            }

            .countdown-number {
                font-size: 1.45rem;
                font-weight: 800;
                background: linear-gradient(135deg, #ff8787, #ef4444);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                font-family: monospace, 'Outfit';
                line-height: 1.1;
            }

            .countdown-label {
                font-size: 0.58rem;
                text-transform: uppercase;
                letter-spacing: 0.06em;
                color: #94a3b8;
                font-weight: 700;
                margin-top: 4px;
            }

            /* Expiry Badge Style */
            .expiry-badge {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.45rem;
                margin-top: 1rem;
                background: rgba(239, 68, 68, 0.06);
                border: 1px solid rgba(239, 68, 68, 0.15);
                color: #f87171;
                font-size: 0.8rem;
                font-weight: 600;
                padding: 0.5rem 1rem;
                border-radius: 30px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }

            /* Video Description Box Style */
            .video-description-box {
                background: rgba(15, 23, 42, 0.4);
                border: 1px solid rgba(255, 255, 255, 0.05);
                border-radius: 12px;
                padding: 1.1rem;
                color: #cbd5e1;
                font-size: 0.875rem;
                line-height: 1.6;
                box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.3);
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
            /* 2x Speed Indicator Overlay */
            .speed-indicator-overlay {
                position: absolute;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                background: rgba(15, 23, 42, 0.85);
                border: 1px solid rgba(255, 255, 255, 0.15);
                color: #ffffff;
                padding: 6px 14px;
                border-radius: 30px;
                font-size: 0.85rem;
                font-weight: 600;
                display: flex;
                align-items: center;
                gap: 6px;
                z-index: 15;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.2s ease, transform 0.2s ease;
            }

            .speed-indicator-overlay.active {
                opacity: 1;
                transform: translate(-50%, 4px);
            }
        </style>
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
                                        <video id="mainVideo" src="{{ route('video.stream', $video->id) }}"
                                            autoplay preload="auto" playsinline></video>

                                        <!-- 2x Speed Banner Overlay -->
                                        <div class="speed-indicator-overlay" id="speedIndicatorOverlay">
                                            <svg class="w-4.5 h-4.5" fill="currentColor" viewBox="0 0 20 20" style="width: 18px; height: 18px;">
                                                <path d="M4.555 5.168A1 1 0 003 6v8a1 1 0 001.555.832l6-4a1 1 0 000-1.664l-6-4zM11.555 5.168A1 1 0 0010 6v8a1 1 0 001.555.832l6-4a1 1 0 000-1.664l-6-4z"/>
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
                        </div>

                        <!-- Video Metadata Card -->
                        <div class="metadata-card">
                            @if($video->category)
                                <div class="mb-4">
                                    <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-widest text-indigo-100 bg-gradient-to-r from-indigo-500/15 to-purple-500/5 border border-indigo-500/30 shadow-lg shadow-indigo-500/5 transition-all duration-300 hover:border-indigo-400/50 hover:shadow-indigo-500/10">
                                        <span class="relative flex h-2 w-2">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                                        </span>
                                        <span class="text-indigo-400 font-extrabold">#</span>
                                        <span>{{ $video->category->name }}</span>
                                    </span>
                                </div>
                            @endif
                            <h1 class="text-xl sm:text-2xl font-extrabold text-white mb-3 leading-tight">{{ $video->title }}
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
 
             mainVideo.addEventListener('touchstart', startHold, { passive: true });
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
                        console.log('Unmuted autoplay blocked by browser. Trying muted autoplay...', error);
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
                mainVideo.addEventListener('loadedmetadata', startPlay, { once: true });
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
