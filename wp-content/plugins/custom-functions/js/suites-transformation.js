/**
 * -----------------------------------------------------------------------------
 * Suites Transformation — frontend Swiper + before/after comparison
 * -----------------------------------------------------------------------------
 */
(function ($) {
    'use strict';

    /**
     * Draggable before/after comparison slider.
     */
    function initBeforeAfter($root) {
        $root.find('.st-ba-compare').each(function () {
            var $compare = $(this);
            if ($compare.data('stBaInit')) {
                return;
            }
            $compare.data('stBaInit', true);

            var $afterWrap = $compare.find('.st-ba-after');
            var $handle = $compare.find('.st-ba-handle');
            var dragging = false;

            function setPosition(percent) {
                percent = Math.max(0, Math.min(100, percent));
                $afterWrap.css('clip-path', 'inset(0 ' + (100 - percent) + '% 0 0)');
                $handle.css('left', percent + '%');
            }

            function positionFromEvent(e) {
                var rect = $compare[0].getBoundingClientRect();
                var clientX = e.touches ? e.touches[0].clientX : e.clientX;
                var percent = ((clientX - rect.left) / rect.width) * 100;
                setPosition(percent);
            }

            setPosition(50);

            $handle.on('mousedown touchstart', function (e) {
                dragging = true;
                e.preventDefault();
            });

            $(document).on('mousemove touchmove', function (e) {
                if (!dragging) {
                    return;
                }
                positionFromEvent(e);
            });

            $(document).on('mouseup touchend', function () {
                dragging = false;
            });

            $compare.on('click', function (e) {
                if ($(e.target).closest('.st-ba-handle').length) {
                    return;
                }
                positionFromEvent(e);
            });
        });
    }

    /**
     * Toggle switch between before and after (mobile-friendly alternative).
     */
    function initBaToggle($root) {
        $root.find('.st-ba-toggle').each(function () {
            var $toggle = $(this);
            if ($toggle.data('stToggleInit')) {
                return;
            }
            $toggle.data('stToggleInit', true);

            var $buttons = $toggle.find('.st-ba-toggle-btn');
            var $panels = $toggle.closest('.st-transform-card').find('.st-ba-panel');

            $buttons.on('click', function () {
                var view = $(this).data('view');
                $buttons.removeClass('is-active').attr('aria-pressed', 'false');
                $(this).addClass('is-active').attr('aria-pressed', 'true');
                $panels.removeClass('is-visible');
                $panels.filter('[data-view="' + view + '"]').addClass('is-visible');
            });
        });
    }

    /**
     * Initialize Swiper carousel for transformation slides.
     */
    function initSwiper($root) {
        var $swiperEl = $root.find('.st-transform-swiper');
        if (!$swiperEl.length || typeof Swiper === 'undefined') {
            return;
        }

        $swiperEl.each(function () {
            var $el = $(this);
            if ($el.data('stSwiperInit')) {
                return;
            }
            $el.data('stSwiperInit', true);

            var $section = $el.closest('.st-transform-section');
            var $carousel = $el.closest('.st-transform-carousel');
            var uid = $el.attr('id') || 'st-transform-swiper';
            var slideCount = $el.find('.swiper-slide').length;

            var swiper = new Swiper('#' + uid, {
                slidesPerView: 1,
                spaceBetween: 24,
                loop: slideCount > 3,
                speed: 650,
                grabCursor: true,
                watchOverflow: true,
                preventClicks: false,
                preventClicksPropagation: false,
                touchStartPreventDefault: false,
                noSwipingSelector: '.st-video-play-btn, .st-ba-video-thumb',
                autoplay: slideCount > 1 ? {
                    delay: 5000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true
                } : false,
                pagination: {
                    el: $section.find('.st-transform-pagination')[0],
                    clickable: true
                },
                navigation: {
                    nextEl: $carousel.find('.st-transform-next')[0],
                    prevEl: $carousel.find('.st-transform-prev')[0],
                    disabledClass: 'st-transform-nav--disabled',
                    lockClass: 'st-transform-nav--locked',
                    hiddenClass: 'st-transform-nav--hidden'
                },
                breakpoints: {
                    640: { slidesPerView: 1 },
                    900: { slidesPerView: 2 },
                    1200: { slidesPerView: 3 }
                }
            });

            document.addEventListener('st-video-open', function () {
                if (swiper.autoplay && swiper.autoplay.running) {
                    swiper.autoplay.stop();
                }
            });
            document.addEventListener('st-video-close', function () {
                if (swiper.params.autoplay && swiper.autoplay) {
                    swiper.autoplay.start();
                }
            });
        });
    }

    function boot() {
        $('.st-transform-section').each(function () {
            var $section = $(this);
            initBeforeAfter($section);
            initBaToggle($section);
            initSwiper($section);
        });
    }

    $(document).ready(boot);
    $(window).on('load', boot);

})(jQuery);
