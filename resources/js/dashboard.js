document.addEventListener('DOMContentLoaded', function () {
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

        if (window.matchMedia('(hover: hover)').matches) {
            card.addEventListener('mouseenter', () => {
                hoverTimeout = setTimeout(() => {
                    video.setAttribute('preload', 'auto');
                    video.style.display = 'block';
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
                video.style.display = 'none';
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
        }

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
                        if (progressFill) progressFill.style.width =
                            `${percent * 100}%`;
                        if (progressScrubber) progressScrubber.style.left =
                            `${percent * 100}%`;

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
                            if (cardPreviewTime) cardPreviewTime.textContent =
                                formatTime(time);
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
                        if (progressFill) progressFill.style.width =
                            `${percent * 100}%`;
                        if (progressScrubber) progressScrubber.style.left =
                            `${percent * 100}%`;

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
                            if (cardPreviewTime) cardPreviewTime.textContent =
                                formatTime(time);
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

                document.addEventListener('touchmove', touchMoveHandler, {
                    passive: false
                });
                document.addEventListener('touchend', touchEndHandler);
            }, {
                passive: true
            });

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

// ── Pill Arrow Scroll Logic (YouTube mobile style) ──
(function () {
    const container = document.getElementById('categoryContainer');
    const wrapper = document.getElementById('pillWrapper');
    const btnLeft = document.getElementById('pillArrowLeft');
    const btnRight = document.getElementById('pillArrowRight');

    if (!container || !wrapper || !btnLeft || !btnRight) return;

    function updateArrows() {
        const atStart = container.scrollLeft <= 2;
        const atEnd = container.scrollLeft + container.clientWidth >= container.scrollWidth - 2;

        btnLeft.classList.toggle('hidden', atStart);
        btnRight.classList.toggle('hidden', atEnd);
        wrapper.classList.toggle('at-start', atStart);
        wrapper.classList.toggle('at-end', atEnd);
    }

    window.scrollPills = function (dir) {
        container.scrollBy({
            left: dir * 220,
            behavior: 'smooth'
        });
    };

    container.addEventListener('scroll', updateArrows, {
        passive: true
    });

    // Also update on resize (e.g. orientation change on mobile)
    new ResizeObserver(updateArrows).observe(container);

    // Initial state
    updateArrows();
})();
