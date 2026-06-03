/**
 * Suites Transformation — video modal (vanilla JS, no jQuery required).
 */
(function () {
    'use strict';

    window.STTransformVideo = window.STTransformVideo || {};

    function resolveModal(btn) {
        var section = btn ? btn.closest('.st-transform-section') : null;
        var id = section ? section.getAttribute('data-st-video-modal-id') : '';
        if (id) {
            return document.getElementById(id);
        }
        return document.querySelector('.st-video-modal');
    }

    function moveModalsToBody() {
        document.querySelectorAll('.st-video-modal').forEach(function (modal) {
            if (modal.parentNode && modal.parentNode !== document.body) {
                document.body.appendChild(modal);
            }
        });
        stripAllToolbarHidden();
    }

    /**
     * Remove native hidden — never set it. Bootstrap reboot uses
     * [hidden] { display: none !important } which blocks the minimize button.
     */
    function stripToolbarHidden(modal) {
        if (!modal) {
            return;
        }
        modal.querySelectorAll('.st-video-modal__toolbar button[hidden]').forEach(function (btn) {
            btn.removeAttribute('hidden');
        });
    }

    function stripAllToolbarHidden() {
        document.querySelectorAll('.st-video-modal').forEach(stripToolbarHidden);
    }

    function closeModal(modal) {
        if (!modal) {
            return;
        }
        var player = modal.querySelector('.st-video-modal__player');
        modal.classList.remove('is-open', 'is-fullscreen');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('st-video-modal-open');
        if (player) {
            player.pause();
            player.removeAttribute('src');
            player.load();
        }
        var source = modal.querySelector('.st-video-modal__player source');
        if (source) {
            source.removeAttribute('src');
        }
        document.dispatchEvent(new CustomEvent('st-video-close'));
    }

    function openModal(btn, videoUrl, mimeType) {
        moveModalsToBody();
        var modal = resolveModal(btn);
        if (!modal || !videoUrl) {
            return false;
        }
        var player = modal.querySelector('.st-video-modal__player');
        var source = modal.querySelector('.st-video-modal__player source');
        if (!player) {
            return false;
        }
        mimeType = mimeType || 'video/mp4';
        player.src = videoUrl;
        if (source) {
            source.src = videoUrl;
            source.type = mimeType;
        }
        player.load();
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('st-video-modal-open');

        function tryPlay() {
            var p = player.play();
            if (p && typeof p.catch === 'function') {
                p.catch(function () {});
            }
        }

        if (player.readyState >= 2) {
            tryPlay();
        } else {
            player.addEventListener('loadeddata', tryPlay, { once: true });
            tryPlay();
        }

        stripToolbarHidden(modal);
        document.dispatchEvent(new CustomEvent('st-video-open'));
        return true;
    }

    /**
     * Called from inline onclick fallback on play control.
     */
    window.STTransformVideo.handlePlayClick = function (btn, e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        if (!btn) {
            return false;
        }
        var videoUrl = btn.getAttribute('data-video-url');
        var mimeType = btn.getAttribute('data-video-type') || 'video/mp4';
        if (!videoUrl) {
            return false;
        }
        openModal(btn, videoUrl, mimeType);
        return false;
    };

    function onPlayActivate(btn) {
        if (!btn || btn.classList.contains('is-playing')) {
            return;
        }
        var videoUrl = btn.getAttribute('data-video-url');
        var mimeType = btn.getAttribute('data-video-type') || 'video/mp4';
        if (!videoUrl) {
            return;
        }
        if (openModal(btn, videoUrl, mimeType)) {
            btn.classList.remove('is-loading');
        }
    }

    function bindEvents() {
        if (window.STTransformVideo.bound) {
            return;
        }
        window.STTransformVideo.bound = true;
        moveModalsToBody();

        document.addEventListener(
            'click',
            function (e) {
                var btn = e.target.closest('.st-video-play-btn');
                if (!btn) {
                    return;
                }
                e.preventDefault();
                e.stopPropagation();
                onPlayActivate(btn);
            },
            true
        );

        document.addEventListener('keydown', function (e) {
            var btn = e.target.closest('.st-video-play-btn');
            if (!btn || (e.key !== 'Enter' && e.key !== ' ')) {
                return;
            }
            e.preventDefault();
            onPlayActivate(btn);
        });

        document.addEventListener('click', function (e) {
            var modal = e.target.closest('.st-video-modal');
            if (!modal || !modal.classList.contains('is-open')) {
                return;
            }
            if (
                e.target.closest('.st-video-modal__close') ||
                e.target.closest('.st-video-modal__backdrop')
            ) {
                closeModal(modal);
            }
            if (e.target.closest('.st-video-modal__fullscreen')) {
                modal.classList.add('is-fullscreen');
                stripToolbarHidden(modal);
            }
            if (e.target.closest('.st-video-modal__minimize')) {
                modal.classList.remove('is-fullscreen');
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape') {
                return;
            }
            var openEl = document.querySelector('.st-video-modal.is-open');
            if (!openEl) {
                return;
            }
            if (openEl.classList.contains('is-fullscreen')) {
                openEl.classList.remove('is-fullscreen');
            } else {
                closeModal(openEl);
            }
        });
    }

    function init() {
        moveModalsToBody();
        stripAllToolbarHidden();
        bindEvents();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    window.addEventListener('load', moveModalsToBody);
})();
