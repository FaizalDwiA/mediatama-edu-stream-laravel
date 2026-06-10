<x-app-layout>
    @push('styles')
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

            .dashboard-wrapper {
                font-family: 'Outfit', sans-serif !important;
                background-color: #0b0f19;
                /* Deep space dark */
                color: #f8fafc;
                min-height: calc(100vh - 65px);
                transition: all 0.3s ease;
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
                overflow: visible !important;
                /* Allow hovered cards to scale above siblings */
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

            /* Video Card styling - YouTube transparent style */
            .video-card {
                background: transparent;
                border: 1px solid transparent;
                border-radius: 16px !important;
                padding: 0.75rem !important;
                display: flex;
                flex-direction: column;
                box-shadow: none;
                transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1),
                    background 0.25s ease,
                    border-color 0.25s ease,
                    box-shadow 0.25s ease;
                height: 100%;
                position: relative;
                z-index: 1;
                cursor: pointer;
            }

            .video-card:hover {
                transform: scale(1.02);
                z-index: 20;
                background: rgba(30, 41, 59, 0.75) !important;
                border-color: rgba(99, 102, 241, 0.4) !important;
                box-shadow: 0 0 0 1px rgba(99, 102, 241, 0.2),
                    0 8px 32px rgba(0, 0, 0, 0.5),
                    0 0 20px rgba(99, 102, 241, 0.15) !important;
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
            }

            .video-card:hover .video-thumbnail {
                box-shadow: 0 16px 40px rgba(0, 0, 0, 0.6) !important;
            }

            .preview-video {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
                z-index: 2;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.3s ease;
                border-radius: 12px;
            }

            .video-card:hover .preview-video~.play-overlay {
                opacity: 0;
                pointer-events: none;
            }

            .mute-btn {
                position: absolute !important;
                top: 8px !important;
                right: 8px !important;
                z-index: 10 !important;
                background-color: rgba(0, 0, 0, 0.7) !important;
                color: #ffffff !important;
                padding: 6px !important;
                border-radius: 9999px !important;
                opacity: 0;
                transition: opacity 0.3s ease !important;
                border: none !important;
                cursor: pointer !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }

            .mute-btn:hover {
                background-color: rgba(0, 0, 0, 0.9) !important;
            }

            .video-duration-badge {
                position: absolute !important;
                bottom: 8px !important;
                right: 8px !important;
                z-index: 10 !important;
                background-color: rgba(8, 8, 8, 0.85) !important;
                color: #ffffff !important;
                font-size: 0.72rem !important;
                padding: 2px 6px !important;
                border-radius: 3px !important;
                font-family: monospace, sans-serif !important;
                font-weight: 700 !important;
                letter-spacing: 0.04em !important;
                opacity: 1 !important;
                pointer-events: none !important;
                line-height: 1.4 !important;
            }

            /* YouTube-style hover progress bar */
            .video-progress-bar {
                position: absolute !important;
                bottom: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 4px !important;
                background-color: rgba(255, 255, 255, 0.2) !important;
                z-index: 30 !important;
                opacity: 0;
                transition: opacity 0.3s ease, height 0.12s ease !important;
                pointer-events: auto !important;
                cursor: pointer !important;
            }

            .video-card:hover .video-progress-bar {
                opacity: 1;
            }

            .video-progress-bar:hover,
            .video-progress-bar.dragging {
                height: 8px !important;
            }

            .video-progress-fill {
                height: 100% !important;
                width: 0%;
                background: #ef4444 !important; /* YouTube Red */
                transition: width 0.08s linear !important;
            }

            .video-progress-scrubber {
                position: absolute !important;
                top: 50% !important;
                left: 0%;
                transform: translate(-50%, -50%) !important;
                width: 11px !important;
                height: 11px !important;
                border-radius: 50% !important;
                background: #ef4444 !important;
                opacity: 0;
                transition: opacity 0.15s ease !important;
                pointer-events: none !important;
                z-index: 35 !important;
                box-shadow: 0 1px 4px rgba(0, 0, 0, 0.4);
            }

            .video-progress-bar:hover .video-progress-scrubber,
            .video-progress-bar.dragging .video-progress-scrubber {
                opacity: 1;
            }

            /* Card Video Preview Tooltip */
            .card-preview-tooltip {
                position: absolute !important;
                bottom: 18px !important;
                left: 0;
                transform: translateX(-50%) !important;
                width: 110px !important;
                background: rgba(15, 23, 42, 0.95) !important;
                border: 1px solid rgba(255, 255, 255, 0.15) !important;
                border-radius: 6px !important;
                padding: 2px !important;
                box-shadow: 0 6px 16px rgba(0, 0, 0, 0.5) !important;
                opacity: 0;
                pointer-events: none !important;
                transition: opacity 0.15s ease !important;
                z-index: 50 !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                overflow: hidden !important;
            }

            .card-preview-tooltip.active {
                opacity: 1 !important;
            }

            .card-preview-video-container {
                width: 104px !important;
                height: 58px !important; /* 16:9 ratio */
                border-radius: 4px !important;
                overflow: hidden !important;
                background: #000 !important;
            }

            .card-preview-video-container video {
                width: 100% !important;
                height: 100% !important;
                object-fit: cover !important;
            }

            .card-preview-time-badge {
                margin-top: 2px !important;
                color: #ffffff !important;
                font-size: 0.65rem !important;
                font-family: monospace, sans-serif !important;
                font-weight: 600 !important;
            }

            /* Video Thumbnail Card */
            .video-thumbnail {
                width: 100%;
                aspect-ratio: 16 / 9;
                height: auto;
                border-radius: 12px;
                overflow: hidden;
                position: relative;
                margin-bottom: 0.75rem;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
                background: #1e293b;
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
                transform: scale(1.05);
            }

            /* Dynamic Gradients for missing images */
            .grad-1 {
                background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            }

            .grad-2 {
                background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
            }

            .grad-3 {
                background: linear-gradient(135deg, #ec4899 0%, #f43f5e 100%);
            }

            .grad-4 {
                background: linear-gradient(135deg, #f59e0b 0%, #e11d48 100%);
            }

            .grad-5 {
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            }

            .grad-6 {
                background: linear-gradient(135deg, #8b5cf6 0%, #d946ef 100%);
            }

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
                z-index: 20; /* Higher than locked-overlay (10) */
                transition: opacity 0.25s ease, transform 0.25s ease;
            }

            /* Only hide video tag on hover for approved (playable) videos */
            a.video-card:hover .video-tag {
                opacity: 0;
                transform: translateY(-5px);
                pointer-events: none;
            }

            /* Video Details Row (Avatar + Meta) */
            .video-details-row {
                display: flex;
                gap: 0.75rem;
                align-items: flex-start;
                margin-top: 0.5rem;
                border-radius: 10px;
                padding: 0.5rem 0.25rem;
                transition: background 0.25s ease;
            }

            .video-card:hover .video-details-row {
                background: rgba(99, 102, 241, 0.06);
            }

            .video-avatar {
                width: 36px;
                height: 36px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.75rem;
                font-weight: 700;
                color: #ffffff;
                flex-shrink: 0;
                box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.2);
            }

            .video-meta-content {
                flex-grow: 1;
                min-width: 0;
            }

            /* YouTube Title Style */
            .video-title {
                color: #f8fafc !important;
                font-size: 0.95rem !important;
                font-weight: 600 !important;
                line-height: 1.4 !important;
                margin-bottom: 0.25rem !important;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: normal !important;
                transition: color 0.2s ease;
            }

            .video-card:hover .video-title {
                color: #3b82f6 !important;
            }

            .video-channel-name {
                color: #94a3b8 !important;
                font-size: 0.825rem !important;
                font-weight: 400 !important;
                margin: 0 !important;
                line-height: 1.3 !important;
            }

            .video-views-date {
                color: #94a3b8 !important;
                font-size: 0.825rem !important;
                font-weight: 400 !important;
                margin: 0 !important;
                margin-top: 2px !important;
                line-height: 1.3 !important;
            }

            /* Video Actions Wrapper */
            .video-actions-wrapper {
                margin-top: 0.75rem;
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            /* YouTube-like Pill Buttons */
            .yt-pill-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 20px !important;
                padding: 0.5rem 1rem !important;
                font-size: 0.85rem !important;
                font-weight: 600 !important;
                transition: all 0.2s ease !important;
                cursor: pointer;
                text-align: center;
                width: 100%;
                box-sizing: border-box;
                border: none;
            }

            .yt-pill-white {
                background-color: #f8fafc !important;
                color: #0f172a !important;
            }

            .yt-pill-white:hover {
                background-color: #e2e8f0 !important;
                transform: scale(1.02);
            }

            .yt-pill-blue {
                background-color: #2563eb !important;
                color: #ffffff !important;
            }

            .yt-pill-blue:hover {
                background-color: #1d4ed8 !important;
                transform: scale(1.02);
            }

            .yt-pill-orange {
                background-color: rgba(249, 115, 22, 0.1) !important;
                border: 1px solid rgba(249, 115, 22, 0.3) !important;
                color: #f97316 !important;
            }

            .yt-pill-orange:hover {
                background-color: rgba(249, 115, 22, 0.2) !important;
                transform: scale(1.02);
            }

            .yt-pill-red {
                background-color: #ef4444 !important;
                color: #ffffff !important;
            }

            .yt-pill-red:hover {
                background-color: #dc2626 !important;
                transform: scale(1.02);
            }

            .yt-pill-pending {
                background-color: rgba(148, 163, 184, 0.1) !important;
                border: 1px solid rgba(148, 163, 184, 0.2) !important;
                color: #94a3b8 !important;
            }

            /* Access Limit Status Layout */
            .access-status-approved {
                color: #34d399 !important;
                /* Green text */
                font-size: 0.8rem !important;
                font-weight: 600 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: flex-start !important;
                gap: 0.375rem !important;
                margin-top: 0.25rem !important;
            }

            .access-status-expired {
                color: #f87171 !important;
                /* Red text */
                font-size: 0.8rem !important;
                font-weight: 600 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: flex-start !important;
                gap: 0.375rem !important;
                margin-top: 0.25rem !important;
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
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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

            /* Premium Lock/Access Overlays */
            .locked-overlay {
                position: absolute;
                inset: 0;
                background: rgba(15, 23, 42, 0.55) !important; /* Semi-transparent slate-900 */
                backdrop-filter: blur(8px) !important;
                -webkit-backdrop-filter: blur(8px) !important;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                z-index: 10;
                transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
                padding: 1rem;
                text-align: center;
            }

            .video-card:hover .locked-overlay {
                background: rgba(15, 23, 42, 0.7) !important;
                backdrop-filter: blur(12px) !important;
                -webkit-backdrop-filter: blur(12px) !important;
            }

            /* Lock Container & Icon Styling */
            .lock-icon-container {
                width: 44px;
                height: 44px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 0.65rem;
                position: relative;
                transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            }

            /* Different states icon styling */
            .state-request {
                background: rgba(99, 102, 241, 0.12);
                border: 1px solid rgba(99, 102, 241, 0.3);
                color: #818cf8;
                box-shadow: 0 0 15px rgba(99, 102, 241, 0.1);
            }

            .video-card:hover .state-request {
                background: rgba(99, 102, 241, 0.22);
                border-color: rgba(99, 102, 241, 0.6);
                color: #a5b4fc;
                box-shadow: 0 0 25px rgba(99, 102, 241, 0.35);
                transform: translateY(-2px);
            }

            .state-pending {
                background: rgba(245, 158, 11, 0.12);
                border: 1px solid rgba(245, 158, 11, 0.3);
                color: #fbbf24;
                box-shadow: 0 0 15px rgba(245, 158, 11, 0.1);
                animation: pulse-glow-amber 2s infinite ease-in-out;
            }

            .video-card:hover .state-pending {
                background: rgba(245, 158, 11, 0.22);
                border-color: rgba(245, 158, 11, 0.6);
                color: #fcd34d;
                box-shadow: 0 0 25px rgba(245, 158, 11, 0.35);
                transform: translateY(-2px);
            }

            .state-rejected {
                background: rgba(239, 68, 68, 0.12);
                border: 1px solid rgba(239, 68, 68, 0.3);
                color: #f87171;
                box-shadow: 0 0 15px rgba(239, 68, 68, 0.1);
            }

            .video-card:hover .state-rejected {
                background: rgba(239, 68, 68, 0.22);
                border-color: rgba(239, 68, 68, 0.6);
                color: #fca5a5;
                box-shadow: 0 0 25px rgba(239, 68, 68, 0.35);
                transform: translateY(-2px);
            }

            .state-expired {
                background: rgba(244, 63, 94, 0.12);
                border: 1px solid rgba(244, 63, 94, 0.3);
                color: #fb7185;
                box-shadow: 0 0 15px rgba(244, 63, 94, 0.1);
            }

            .video-card:hover .state-expired {
                background: rgba(244, 63, 94, 0.22);
                border-color: rgba(244, 63, 94, 0.6);
                color: #fecdd3;
                box-shadow: 0 0 25px rgba(244, 63, 94, 0.35);
                transform: translateY(-2px);
            }

            @keyframes pulse-glow-amber {
                0%, 100% {
                    box-shadow: 0 0 12px rgba(245, 158, 11, 0.15);
                    border-color: rgba(245, 158, 11, 0.3);
                }
                50% {
                    box-shadow: 0 0 22px rgba(245, 158, 11, 0.4);
                    border-color: rgba(245, 158, 11, 0.6);
                }
            }

            /* Text labels inside overlay */
            .overlay-title {
                font-size: 0.95rem;
                font-weight: 700;
                letter-spacing: -0.01em;
                margin-bottom: 0.15rem;
                transition: color 0.3s ease;
            }
            
            .overlay-subtitle {
                font-size: 0.725rem;
                color: #94a3b8;
                margin-bottom: 0.75rem;
                font-weight: 400;
            }

            /* Styled action buttons inside overlay */
            .overlay-btn {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.45rem 1.125rem;
                border-radius: 9999px;
                font-size: 0.75rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                border: none;
                cursor: pointer;
                transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            }

            .btn-request {
                background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
                color: #ffffff;
                box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
            }

            .btn-request:hover {
                transform: translateY(-1.5px) scale(1.04);
                box-shadow: 0 6px 18px rgba(79, 70, 229, 0.45);
                background: linear-gradient(135deg, #4338ca 0%, #4f46e5 100%);
            }

            .btn-request:active {
                transform: translateY(0) scale(0.96);
            }

            .btn-rejected {
                background: linear-gradient(135deg, #ea580c 0%, #f97316 100%);
                color: #ffffff;
                box-shadow: 0 4px 14px rgba(234, 88, 12, 0.3);
            }

            .btn-rejected:hover {
                transform: translateY(-1.5px) scale(1.04);
                box-shadow: 0 6px 18px rgba(234, 88, 12, 0.45);
                background: linear-gradient(135deg, #c2410c 0%, #ea580c 100%);
            }

            .btn-rejected:active {
                transform: translateY(0) scale(0.96);
            }

            .btn-expired {
                background: linear-gradient(135deg, #e11d48 0%, #f43f5e 100%);
                color: #ffffff;
                box-shadow: 0 4px 14px rgba(225, 29, 72, 0.3);
            }

            .btn-expired:hover {
                transform: translateY(-1.5px) scale(1.04);
                box-shadow: 0 6px 18px rgba(225, 29, 72, 0.45);
                background: linear-gradient(135deg, #be123c 0%, #e11d48 100%);
            }

            .btn-expired:active {
                transform: translateY(0) scale(0.96);
            }

            /* Pending badge styled glassmorphism indicator */
            .pending-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.4rem 0.95rem;
                background: rgba(30, 41, 59, 0.75);
                border: 1px solid rgba(245, 158, 11, 0.25);
                border-radius: 9999px;
                color: #fbbf24;
                font-size: 0.725rem;
                font-weight: 600;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .pending-badge:hover {
                background: rgba(245, 158, 11, 0.18) !important;
                border-color: rgba(245, 158, 11, 0.45) !important;
                transform: scale(1.04);
            }

            .pending-badge:active {
                transform: scale(0.96);
            }
        </style>
    @endpush

    <div class="dashboard-wrapper py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Toast Success / Error Notifications -->
            @if (session('success'))
                <div class="session-alert alert-success">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="session-alert alert-error">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Video Catalog Grid -->
            <div class="video-grid">
                @forelse($videos as $video)
                    @php
                        $userRequest = $requests->get($video->id);
                        $isExpired =
                            $userRequest &&
                            $userRequest->valid_until &&
                            \Carbon\Carbon::now()->gt($userRequest->valid_until);
                        $isApproved = $userRequest && $userRequest->status === 'approved' && !$isExpired;
                        $gradIndex = ($loop->index % 6) + 1;
                    @endphp

                    @if ($isApproved)
                        <a href="{{ route('video.watch', $video->id) }}" class="video-card mx-auto"
                            style="max-width: 85% !important; width: 100% !important;">
                        @else
                            <div class="video-card">
                    @endif
                    <div class="video-thumbnail">
                        @if($video->category)
                            <span class="video-tag">{{ $video->category->name }}</span>
                        @endif
                        @if($video->thumbnail)
                            <div class="video-thumbnail-bg"
                                style="background-image: url('{{ asset('storage/' . $video->thumbnail) }}'); background-size: cover; background-position: center;"></div>
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
                                        <video class="card-preview-video-element" src="{{ route('video.stream', $video->id) }}" muted preload="auto"></video>
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
                                    <form action="{{ route('video.request', $video->id) }}" method="POST" class="w-full h-full flex flex-col items-center justify-center p-2" onsubmit="return confirm('Apakah Anda yakin ingin meminta akses untuk menonton video ini?');">
                                        @csrf
                                        <div class="lock-icon-container state-request">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
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
                                            <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <span class="overlay-title text-amber-400">Menunggu Persetujuan</span>
                                        <span class="overlay-subtitle">Sedang ditinjau oleh Admin</span>
                                        <button type="button" class="pending-badge" onclick="alert('Permintaan akses Anda sedang ditinjau oleh Admin. Mohon bersabar atau hubungi Admin untuk informasi lebih lanjut.');">
                                            <svg class="w-3 h-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 12px; height: 12px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                            </svg>
                                            <span>Diproses</span>
                                        </button>
                                    </div>
                                @elseif($userRequest->status === 'rejected')
                                    <form action="{{ route('video.request', $video->id) }}" method="POST" class="w-full h-full flex flex-col items-center justify-center p-2" onsubmit="return confirm('Apakah Anda yakin ingin mengirim ulang permintaan akses untuk menonton video ini?');">
                                        @csrf
                                        <div class="lock-icon-container state-rejected">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                        <span class="overlay-title text-orange-500">Permintaan Ditolak</span>
                                        <span class="overlay-subtitle">Kirim ulang permintaan akses</span>
                                        <button type="submit" class="overlay-btn btn-rejected">
                                            <span>Minta Ulang</span>
                                        </button>
                                    </form>
                                @elseif($isExpired)
                                    <form action="{{ route('video.request', $video->id) }}" method="POST" class="w-full h-full flex flex-col items-center justify-center p-2" onsubmit="return confirm('Apakah Anda yakin ingin meminta ulang akses untuk menonton video ini?');">
                                        @csrf
                                        <div class="lock-icon-container state-expired">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
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
                            <h3 class="video-title" title="{{ $video->title }}">{{ Str::limit($video->title, 55) }}</h3>
                            <p class="video-views-date">
                                {{ $video->created_at->locale('id')->diffForHumans() }}
                            </p>

                            <!-- Video Actions (Pill Buttons) -->
                            <div class="video-actions-wrapper" style="margin-top: 0.25rem !important;">
                                @if($userRequest && $userRequest->status === 'approved' && !$isExpired)
                                    <div class="access-status-approved">
                                        <svg class="w-3.5 h-3.5 text-green-400 mr-1" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Batas Akses: {{ $userRequest->valid_until->format('d M Y H:i') }}
                                    </div>
                                @elseif($isExpired)
                                    <div class="access-status-expired">
                                        <svg class="w-3.5 h-3.5 text-red-400 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" style="width: 14px; height: 14px;">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const videoCards = document.querySelectorAll('.video-card');

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

            videoCards.forEach(card => {
                const video = card.querySelector('.preview-video');
                const muteBtn = card.querySelector('.mute-btn');
                const durationBadge = card.querySelector('.video-duration-badge');
                const progressBar = card.querySelector('.video-progress-bar');
                const progressFill = card.querySelector('.video-progress-fill');
                const progressScrubber = card.querySelector('.video-progress-scrubber');
                const cardTooltip = card.querySelector('.card-preview-tooltip');
                const cardPreviewVid = card.querySelector('.card-preview-video-element');
                const cardPreviewTime = card.querySelector('.card-preview-time-badge');

                if (!video) return;

                const setDuration = () => {
                    if (durationBadge && video.duration) {
                        durationBadge.textContent = formatTime(video.duration);
                    }
                };

                video.addEventListener('loadedmetadata', setDuration);
                if (video.readyState >= 1) {
                    setDuration();
                }

                let isDragging = false;

                // Update progress bar as video plays
                video.addEventListener('timeupdate', () => {
                    if (isDragging) return;
                    if (progressFill && video.duration) {
                        const pct = (video.currentTime / video.duration) * 100;
                        progressFill.style.width = pct + '%';
                        if (progressScrubber) {
                            progressScrubber.style.left = pct + '%';
                        }
                    }
                });

                let hoverTimeout;

                card.addEventListener('mouseenter', () => {
                    hoverTimeout = setTimeout(() => {
                        video.setAttribute('preload', 'auto');
                        video.style.opacity = '1';
                        if (muteBtn) muteBtn.style.opacity = '1';
                        if (progressBar) progressBar.style.opacity = '1';
                        video.play().catch(error => {
                            console.log('Hover preview play failed:', error);
                        });
                    }, 150);
                });

                card.addEventListener('mouseleave', () => {
                    clearTimeout(hoverTimeout);
                    video.style.opacity = '0';
                    if (muteBtn) muteBtn.style.opacity = '0';
                    if (progressBar) progressBar.style.opacity = '0';
                    if (progressFill) progressFill.style.width = '0%';
                    if (progressScrubber) progressScrubber.style.left = '0%';
                    if (cardTooltip) cardTooltip.classList.remove('active');
                    video.pause();
                    video.currentTime = 0;
                    if (cardPreviewVid) {
                        cardPreviewVid.pause();
                        cardPreviewVid.currentTime = 0;
                    }
                });

                if (muteBtn) {
                    muteBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();

                        video.muted = !video.muted;

                        const iconMuted = muteBtn.querySelector('.icon-muted');
                        const iconUnmuted = muteBtn.querySelector('.icon-unmuted');

                        if (video.muted) {
                            iconMuted.classList.remove('hidden');
                            iconUnmuted.classList.add('hidden');
                        } else {
                            iconMuted.classList.add('hidden');
                            iconUnmuted.classList.remove('hidden');
                        }
                    });
                }

                if (progressBar) {
                    function getTimelinePosition(e) {
                        const rect = progressBar.getBoundingClientRect();
                        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                        let x = clientX - rect.left;
                        x = Math.max(0, Math.min(x, rect.width));
                        return x / rect.width;
                    }

                    function seek(e) {
                        const percent = getTimelinePosition(e);
                        const time = percent * video.duration;
                        if (!isNaN(time)) {
                            video.currentTime = time;
                        }
                    }

                    progressBar.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        seek(e);
                    });

                    progressBar.addEventListener('mousedown', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        isDragging = true;
                        progressBar.classList.add('dragging');
                        seek(e);

                        const moveHandler = (moveEvent) => {
                            if (isDragging) {
                                moveEvent.preventDefault();
                                moveEvent.stopPropagation();
                                const percent = getTimelinePosition(moveEvent);
                                if (progressFill) progressFill.style.width = `${percent * 100}%`;
                                if (progressScrubber) progressScrubber.style.left = `${percent * 100}%`;
                                
                                const time = percent * video.duration;
                                if (!isNaN(time)) {
                                    video.currentTime = time;
                                    
                                    const rect = progressBar.getBoundingClientRect();
                                    const clientX = moveEvent.clientX;
                                    let x = clientX - rect.left;
                                    x = Math.max(0, Math.min(x, rect.width));
                                    if (cardTooltip) {
                                        cardTooltip.style.left = `${x}px`;
                                        cardTooltip.classList.add('active');
                                    }
                                    if (cardPreviewTime) cardPreviewTime.textContent = formatTime(time);
                                    if (cardPreviewVid) cardPreviewVid.currentTime = time;
                                }
                            }
                        };

                        const upHandler = (upEvent) => {
                            if (isDragging) {
                                upEvent.preventDefault();
                                upEvent.stopPropagation();
                                seek(upEvent);
                                isDragging = false;
                                progressBar.classList.remove('dragging');
                                if (cardTooltip) cardTooltip.classList.remove('active');
                                document.removeEventListener('mousemove', moveHandler);
                                document.removeEventListener('mouseup', upHandler);
                            }
                        };

                        document.addEventListener('mousemove', moveHandler);
                        document.addEventListener('mouseup', upHandler);
                    });

                    progressBar.addEventListener('touchstart', (e) => {
                        e.stopPropagation();
                        isDragging = true;
                        progressBar.classList.add('dragging');
                        seek(e);

                        const touchMoveHandler = (moveEvent) => {
                            if (isDragging) {
                                moveEvent.stopPropagation();
                                const percent = getTimelinePosition(moveEvent);
                                if (progressFill) progressFill.style.width = `${percent * 100}%`;
                                if (progressScrubber) progressScrubber.style.left = `${percent * 100}%`;
                                
                                const time = percent * video.duration;
                                if (!isNaN(time)) {
                                    video.currentTime = time;
                                    
                                    const rect = progressBar.getBoundingClientRect();
                                    const clientX = moveEvent.touches[0].clientX;
                                    let x = clientX - rect.left;
                                    x = Math.max(0, Math.min(x, rect.width));
                                    if (cardTooltip) {
                                        cardTooltip.style.left = `${x}px`;
                                        cardTooltip.classList.add('active');
                                    }
                                    if (cardPreviewTime) cardPreviewTime.textContent = formatTime(time);
                                    if (cardPreviewVid) cardPreviewVid.currentTime = time;
                                }
                            }
                        };

                        const touchEndHandler = (endEvent) => {
                            if (isDragging) {
                                endEvent.stopPropagation();
                                isDragging = false;
                                progressBar.classList.remove('dragging');
                                if (cardTooltip) cardTooltip.classList.remove('active');
                                const duration = video.duration || 0;
                                const currentPct = parseFloat(progressFill.style.width) / 100;
                                const time = currentPct * duration;
                                if (!isNaN(time)) {
                                    video.currentTime = time;
                                }
                                document.removeEventListener('touchmove', touchMoveHandler);
                                document.removeEventListener('touchend', touchEndHandler);
                            }
                        };

                        document.addEventListener('touchmove', touchMoveHandler, { passive: false });
                        document.addEventListener('touchend', touchEndHandler);
                    }, { passive: true });

                    progressBar.addEventListener('mousemove', (e) => {
                        const rect = progressBar.getBoundingClientRect();
                        let x = e.clientX - rect.left;
                        x = Math.max(0, Math.min(x, rect.width));
                        const percent = x / rect.width;
                        const time = percent * video.duration;
                        if (!isNaN(time)) {
                            if (cardTooltip) {
                                cardTooltip.style.left = `${x}px`;
                                cardTooltip.classList.add('active');
                            }
                            if (cardPreviewTime) cardPreviewTime.textContent = formatTime(time);
                            if (cardPreviewVid) cardPreviewVid.currentTime = time;
                        }
                    });

                    progressBar.addEventListener('mouseleave', () => {
                        if (cardTooltip) cardTooltip.classList.remove('active');
                    });
                }
            });
        });
    </script>
</x-app-layout>
