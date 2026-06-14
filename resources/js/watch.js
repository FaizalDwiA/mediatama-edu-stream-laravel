document.addEventListener("DOMContentLoaded", function() {
    // ================= CONFIGURATION =================
    const config = window.WatchConfig || {
        isAdmin: false,
        expiryTime: '',
        dashboardUrl: '/'
    };

    // ================= COUNTDOWN TIMER LOGIC =================
    if (!config.isAdmin && config.expiryTime) {
        initCountdownTimer(config.expiryTime, config.dashboardUrl);
    }

    // ================= CUSTOM PLAYER LOGIC =================
    initVideoPlayer();
});

function initCountdownTimer(expiryTimeString, dashboardUrl) {
    const expiryTime = new Date(expiryTimeString).getTime();
    const countdownEl = document.getElementById("countdown");
    if (!countdownEl) return;

    function updateTimer() {
        const now = new Date();
        const expiry = new Date(expiryTime);
        const distance = expiry.getTime() - now.getTime();

        if (distance <= 0) {
            clearInterval(timerInterval);
            countdownEl.innerHTML = "WAKTU AKSES HABIS";
            alert("Waktu menonton Anda telah habis!");
            window.location.href = dashboardUrl;
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
}

function initVideoPlayer() {
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
        if (document.activeElement.tagName === 'INPUT' || document.activeElement.tagName === 'TEXTAREA') {
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
}
