jQuery(document).ready(function ($) {
    var $sync1 = $("#sync1");
    var $sync2 = $("#sync2");
    var $originalItems = $sync1.find('.item').clone();

    function initSliders($items) {
        if ($sync1.hasClass('owl-loaded')) {
            $sync1.trigger('destroy.owl.carousel').removeClass('owl-loaded');
            $sync1.html('');
        }
        if ($sync2.hasClass('owl-loaded')) {
            $sync2.trigger('destroy.owl.carousel').removeClass('owl-loaded');
            $sync2.html('');
        }
        if (!$items.length) return;
        $sync1.html($items.clone());
        $sync2.html($items.clone());
        $sync1.owlCarousel({
            items: 1,
            slideSpeed: 1000,
            nav: true,
            dots: false,
            loop: $items.length > 1,
            responsiveRefreshRate: 200,
        }).on('changed.owl.carousel', syncPosition);

        $sync2.owlCarousel({
            items: 4,
            dots: false,
            nav: false,
            margin: 10,
            smartSpeed: 200,
            slideSpeed: 500,
            responsiveRefreshRate: 100
        }).on('click', '.owl-item', function (e) {
            e.preventDefault();
            var index = $(this).index();
            $sync1.trigger('to.owl.carousel', [index, 300, true]);
        });
        
        $sync2.find(".owl-item").eq(0).addClass("current");
    }

    function syncPosition(event) {
        var current = event.item.index;

        $sync2.find(".owl-item").removeClass("current");
        $sync2.find(".owl-item").eq(current).addClass("current");

        $sync2.trigger('to.owl.carousel', [current, 300, true]);
    }
    
    initSliders($originalItems);

    $(document).on('click', '.filter-btn', function (e) {
        e.preventDefault();
        var filter = $(this).data('filter');
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        var $filtered;
        if (filter === 'all') {
            $filtered = $originalItems;
        } else {
            $filtered = $originalItems.filter(function () {
                var cats = ($(this).data('category') || '').toString().split(',');
                return cats.includes(filter);
            });
        }
        initSliders($filtered);
    });


    
    var current = 0;
    var steps = $(".step");

    function validateStep(stepIndex) {
        var step = steps.eq(stepIndex);
        var valid = true;

        step.find("input, select").each(function(){
            if ($(this).prop("required")) {

                if ($(this).is(":checkbox")) {
                    var name = $(this).attr("name");
                    if (step.find('input[name="'+name+'"]:checked').length === 0) {
                        valid = false;
                    }
                } else {
                    if ($(this).val() === "") {
                        valid = false;
                    }
                }

            }
        });

        return valid;
    }

    $(".next_que").click(function(){
        if(validateStep(current)) {
            steps.eq(current).hide();
            current++;
            steps.eq(current).show();
        } else {
            alert("Please complete this step before continuing.");
        }
    });

    $(".prev_que").click(function(){
        steps.eq(current).hide();
        current--;
        steps.eq(current).show();
    });

    // AJAX submit
    $("#wizard-form").on("submit", function(e){
        e.preventDefault();

        if(!validateStep(current)) {
            alert("Please complete this step.");
            return;
        }

        var formData = $(this).serialize();

        $.ajax({
            url: ajaxurl,
            type: "POST",
            data: formData + "&action=match_salon_suites",
            beforeSend: function(){
                $("#wizard-form").hide();
                $("#wizard-results").show().html("<h3>Finding your perfect suite...</h3>");
            },
            success: function(response){
                $("#wizard-results").html(response);
                $("#suite-wizard").addClass('finalMatch');
            }
        });

    });
    

    if (document.body.classList.contains('page-id-44')) {
            
        const swiper = new Swiper('.salon-suites-slider', {
            loop: true,
            spaceBetween: 30,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            }, 
            on: {
            afterInit: function () {
                updateSuiteBtn(this); // ✅ first load safe
            },
            slideChangeTransitionEnd: function () {
                updateSuiteBtn(this); // ✅ after slide fully ready
            }
        }
    
        });
    }
    
    function initFeaturedSuiteSwipers() {
        if (typeof Swiper === 'undefined') {
            return;
        }

        document.querySelectorAll('.suiteSwiper').forEach(function (el) {
            if (el.swiper || el.dataset.suiteSwiperInit === '1') {
                return;
            }

            el.dataset.suiteSwiperInit = '1';

            new Swiper(el, {
                slidesPerView: 1,
                spaceBetween: 20,
                observer: true,
                observeParents: true,
                navigation: {
                    nextEl: el.querySelector('.swiper-button-next'),
                    prevEl: el.querySelector('.swiper-button-prev'),
                    lockClass: 'suite-swiper-nav-lock',
                },
                breakpoints: {
                    320: { slidesPerView: 1 },
                    576: { slidesPerView: 2 },
                    768: { slidesPerView: 3 },
                    1024: { slidesPerView: 4 },
                },
            });
        });
    }

    function refreshFeaturedSuiteSwipers() {
        document.querySelectorAll('.suiteSwiper').forEach(function (el) {
            if (el.swiper) {
                el.swiper.update();
            }
        });
    }

    initFeaturedSuiteSwipers();

    window.addEventListener('load', function () {
        document.querySelectorAll('.suiteSwiper').forEach(function (el) {
            if (!el.swiper) {
                el.dataset.suiteSwiperInit = '';
            }
        });
        initFeaturedSuiteSwipers();
        refreshFeaturedSuiteSwipers();
    });
    
    
    function updateSuiteBtn(swiper) {
        let activeSlide = swiper.el.querySelector('.swiper-slide-active');
    
        if (!activeSlide) return; // ✅ prevent error
    
        let link = activeSlide.getAttribute('data-link');
    
        if (link) {
            $('.suite-btn').attr('href', link);
        }
    }
    
    
    function loadSuites(service){
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'filter_suites',
                    service: service
                },
                dataType: 'json', // Ensure jQuery parses JSON
                success: function(response){
                    if(response.success){
                         history.replaceState({}, '', window.location.pathname);
    
                        // Destroy current Swiper
                        if(window.suiteSwiper){
                            window.suiteSwiper.destroy(true, true);
                        }
    
                        // Replace slides
                        // Use .html() directly, make sure it's valid HTML
                        $('.salon-suites-slider .swiper-wrapper').html(response.data);
    
                        // Re-initialize Swiper
                        window.suiteSwiper = new Swiper('.salon-suites-slider', {
                            loop: true,
                            spaceBetween: 30,
                            navigation: {
                                nextEl: '.swiper-button-next',
                                prevEl: '.swiper-button-prev',
                            },
                          on: {
                            afterInit: function () {
                                updateSuiteBtn(this); // ✅ first load safe
                            },
                            slideChangeTransitionEnd: function () {
                                updateSuiteBtn(this); // ✅ after slide fully ready
                            }
                        }
    
    
                        });
                    }
                },
                error: function(xhr, status, error){
                    console.log("AJAX error:", error);
                    console.log(xhr.responseText);
                }
            });
        }
    
    // On click
    $('#pills-tab .nav-link').on('click', function(){
        var service = $(this).data('service');
    
        // Active class
        $('#pills-tab .nav-link').removeClass('activeNav');
        $(this).addClass('activeNav');
    
        loadSuites(service);
    });
    
    // On page load: check active button
    var activeBtn = $('#pills-tab .nav-link.activeNav');
    if(activeBtn.length){
        loadSuites(activeBtn.data('service'));
    } else {
        var firstBtn = $('#pills-tab .nav-link').first();
        firstBtn.addClass('activeNav');
        loadSuites(firstBtn.data('service'));
    }
    
    // add and remove faq field (admin panel)
    $(document).on('click', '.add-faq', function(){
            $('#faq_table tbody').append('<tr><td><input type="text" name="faq_question[]" style="width:100%"></td><td><textarea name="faq_answer[]" style="width:100%"></textarea></td><td><button type="button" class="remove-faq button">Remove</button></td></tr>');
        });
    $(document).on('click', '.remove-faq', function(){
        $(this).closest('tr').remove();
    });
    $('.faq-question').click(function(){
        $(this).next('.faq-answer').slideToggle();
    });

    /**
     * Legacy Transformations gallery modal (HTML from shortcodes.php).
     * Wrapped in IIFE so exiting early does not stop other handlers in this file.
     */
    (function legacyGalleryModalInit() {
        var MOD_OPEN = 'legacy-gallery-modal--open';
        var BODY_LOCK = 'legacy-gallery-modal-is-open';
        var $modal = $('#legacy-gallery-modal');
        if (!$modal.length) {
            return;
        }

        var $backdrop = $modal.find('.legacy-gallery-modal__backdrop');
        var $dialog = $modal.find('.legacy-gallery-modal__dialog');
        var $slide = $modal.find('.legacy-gallery-modal__slide');
        if (!$slide.length) {
            return;
        }
        var $btnClose = $modal.find('.legacy-gallery-modal__close');
        var $btnFs = $modal.find('.legacy-gallery-modal__fullscreen');
        var $btnPrev = $modal.find('.legacy-gallery-modal__nav--prev');
        var $btnNext = $modal.find('.legacy-gallery-modal__nav--next');

        var galleryItems = [];
        var galleryIndex = 0;

        /**
         * Big play overlay: delegated on #legacy-gallery-modal capture so this runs before
         * BB/theme handlers on window/document that swallow bubbled clicks.
         */
        function legacyGalleryBigPlayActivate(ev) {
            var tg = ev.target;
            if (!tg || typeof tg.closest !== 'function') {
                return;
            }
            var btn = tg.closest('.legacy-gallery-modal__big-play');
            if (!btn || !$modal[0].contains(btn)) {
                return;
            }
            if (ev.type === 'touchend') {
                ev.preventDefault();
            }
            ev.stopPropagation();
            if (ev.stopImmediatePropagation) {
                ev.stopImmediatePropagation();
            }

            var shell = btn.closest('.legacy-gallery-modal__video-shell');
            var videoEl = shell ? shell.querySelector('video.legacy-gallery-modal__video') : null;
            if (!videoEl) {
                return;
            }

            try {
                videoEl.playsInline = true;
                videoEl.setAttribute('playsinline', '');
            } catch (err) {}

            var playAttempt = videoEl.play();
            if (playAttempt && typeof playAttempt.then === 'function') {
                playAttempt.catch(function () {
                    try {
                        videoEl.muted = true;
                        videoEl.play().catch(function () {});
                    } catch (e2) {}
                });
            }
        }

        if ($modal.length && $modal[0]) {
            window.addEventListener('click', legacyGalleryBigPlayActivate, true);
            window.addEventListener('touchend', legacyGalleryBigPlayActivate, { capture: true, passive: false });
        }

        function transitionMs() {
            if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                return 0;
            }
            return 220;
        }

        function exitFullscreenIfNeeded() {
            var d = document;
            if (!d.fullscreenElement && !d.webkitFullscreenElement && !d.msFullscreenElement) {
                return;
            }
            if (d.exitFullscreen) {
                d.exitFullscreen().catch(function () {});
            } else if (d.webkitExitFullscreen) {
                d.webkitExitFullscreen();
            } else if (d.msExitFullscreen) {
                d.msExitFullscreen();
            }
        }

        function stopModalVideos() {
            $slide.find('video').each(function () {
                this.pause();
                try {
                    this.currentTime = 0;
                } catch (e) {}
            });
        }

        function closeModal() {
            exitFullscreenIfNeeded();
            stopModalVideos();
            $slide.empty().css('opacity', 1);
            galleryItems = [];
            galleryIndex = 0;
            $modal.removeClass(MOD_OPEN).attr('aria-hidden', 'true');
            $('body').removeClass(BODY_LOCK);
            $(document).off('keydown.legacyGalleryModal');
        }

        function requestFullscreen(el) {
            if (!el) {
                return Promise.reject();
            }
            if (el.requestFullscreen) {
                return el.requestFullscreen();
            }
            if (el.webkitRequestFullscreen) {
                return Promise.resolve(el.webkitRequestFullscreen());
            }
            if (el.msRequestFullscreen) {
                return Promise.resolve(el.msRequestFullscreen());
            }
            return Promise.reject();
        }

        function collectGalleryItems($root, $clickedCard) {
            var out = [];
            var idx = 0;
            var clickedEl = $clickedCard && $clickedCard.length ? $clickedCard[0] : null;

            $root.find('.gallery-card').each(function () {
                var $c = $(this);
                var type = $c.attr('data-gallery-type');
                var url = $c.attr('data-media-url');
                if (!type || !url) {
                    return;
                }
                if (clickedEl && this === clickedEl) {
                    idx = out.length;
                }
                out.push({
                    type: String(type),
                    url: String(url),
                    poster: $c.attr('data-poster') || '',
                    title: $c.attr('data-title') || '',
                    stylistName: $c.attr('data-stylist-name') || ''
                });
            });
            return { items: out, index: idx };
        }

        function stripBbInlineVideoSizing($vid) {
            var el = $vid && $vid.length ? $vid.get(0) : null;
            if (!el || el.tagName !== 'VIDEO') {
                return;
            }
            function clear() {
                el.removeAttribute('style');
            }
            clear();
            el.addEventListener('loadedmetadata', clear, { once: true });
            window.setTimeout(clear, 0);
            window.setTimeout(clear, 100);
            window.setTimeout(clear, 300);
        }

        function mountSlideMedia(item) {
            if (!item || !item.url) {
                return;
            }
            stopModalVideos();
            $slide.empty();
            var alt = item.title || '';

            if (item.type === 'video') {
                var $shell = $('<div>', { class: 'legacy-gallery-modal__video-shell' });
                var $vid = $('<video>', {
                    class: 'legacy-gallery-modal__media legacy-gallery-modal__video',
                    controls: true,
                    playsInline: true,
                    preload: 'metadata',
                    'aria-label': alt || 'Gallery video'
                });
                if (item.poster) {
                    $vid.attr('poster', item.poster);
                }
                $vid.append($('<source>', { src: item.url, type: 'video/mp4' }));

                var $bigPlay = $('<button>', {
                    type: 'button',
                    class: 'legacy-gallery-modal__big-play',
                    'aria-label': 'Play video'
                });
                $bigPlay.append(
                    $('<span>', { class: 'legacy-gallery-modal__big-play-icon', 'aria-hidden': 'true' })
                );

                $shell.append($vid).append($bigPlay);
                $slide.append($shell);
                stripBbInlineVideoSizing($vid);

                var vidEl = $vid.get(0);

                function syncBigPlayVisibility() {
                    if (!vidEl) {
                        return;
                    }
                    if (vidEl.paused || vidEl.ended) {
                        $shell.removeClass('is-playing');
                    } else {
                        $shell.addClass('is-playing');
                    }
                }

                $vid.on('play playing', syncBigPlayVisibility);
                $vid.on('pause ended', syncBigPlayVisibility);
                syncBigPlayVisibility();
            } else {
                $slide.append(
                    $('<img>', {
                        class: 'legacy-gallery-modal__media legacy-gallery-modal__image',
                        src: item.url,
                        alt: alt
                    })
                );
            }

            if (item.stylistName) {
                $slide.append(
                    $('<div>', {
                        class: 'legacy-gallery-modal__stylist-caption',
                        text: item.stylistName
                    })
                );
            }

            $modal.attr('data-legacy-gallery-index', String(galleryIndex + 1));
            $modal.attr('data-legacy-gallery-count', String(galleryItems.length));
        }

        function goToSlide(newIndex, useTransition) {
            if (!galleryItems.length) {
                return;
            }
            var len = galleryItems.length;
            var idx = ((newIndex % len) + len) % len;
            galleryIndex = idx;

            var ms = useTransition ? transitionMs() : 0;

            stopModalVideos();

            if (ms <= 0) {
                mountSlideMedia(galleryItems[galleryIndex]);
                $slide.css('opacity', 1);
                return;
            }

            $slide.css('opacity', 0);
            window.setTimeout(function () {
                mountSlideMedia(galleryItems[galleryIndex]);
                window.requestAnimationFrame(function () {
                    $slide.css('opacity', 1);
                });
            }, ms);
        }

        function goPrev() {
            goToSlide(galleryIndex - 1, true);
        }

        function goNext() {
            goToSlide(galleryIndex + 1, true);
        }

        function bindModalKeyboard() {
            $(document)
                .off('keydown.legacyGalleryModal')
                .on('keydown.legacyGalleryModal', function (e) {
                    if (e.key === 'Escape' || e.keyCode === 27) {
                        e.preventDefault();
                        closeModal();
                        return;
                    }
                    if (e.key === 'ArrowLeft' || e.keyCode === 37) {
                        e.preventDefault();
                        goPrev();
                        return;
                    }
                    if (e.key === 'ArrowRight' || e.keyCode === 39) {
                        e.preventDefault();
                        goNext();
                        return;
                    }
                });
        }

        function openModalFromCard($card) {
            var $root = $card.closest('.legacy-gallery-root');
            var packed = collectGalleryItems($root, $card);
            galleryItems = packed.items;
            galleryIndex = packed.index;

            if (!galleryItems.length || !galleryItems[galleryIndex]) {
                return;
            }

            $slide.css('opacity', 1);
            mountSlideMedia(galleryItems[galleryIndex]);

            $modal.addClass(MOD_OPEN).attr('aria-hidden', 'false');
            $('body').addClass(BODY_LOCK);
            bindModalKeyboard();
        }

        document.querySelectorAll('.legacy-gallery-root .gallery-card').forEach(function (card) {
            var video = card.querySelector('video.hover-video');
            if (!video) {
                return;
            }
            card.addEventListener('mouseenter', function () {
                video.play().catch(function () {});
            });
            card.addEventListener('mouseleave', function () {
                video.pause();
                video.currentTime = 0;
            });
        });

        $(document).on('click', '.legacy-gallery-root .gallery-card', function (e) {
            e.preventDefault();
            openModalFromCard($(this));
        });

        $btnClose.on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            closeModal();
        });

        $backdrop.on('click', function () {
            closeModal();
        });

        $dialog.on('click', function (e) {
            e.stopPropagation();
        });

        $btnPrev.on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            goPrev();
            if (this && this.blur) {
                this.blur();
            }
        });

        $btnNext.on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            goNext();
            if (this && this.blur) {
                this.blur();
            }
        });

        $btnFs.on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var dlg = $dialog.get(0);
            var d = document;
            var fsEl = d.fullscreenElement || d.webkitFullscreenElement || d.msFullscreenElement;

            if (fsEl) {
                exitFullscreenIfNeeded();
                $btnFs.attr('aria-label', 'Enter fullscreen');
                return;
            }

            if (!dlg) {
                return;
            }

            requestFullscreen(dlg)
                .then(function () {
                    $btnFs.attr('aria-label', 'Exit fullscreen');
                })
                .catch(function () {
                    $btnFs.attr('aria-label', 'Enter fullscreen');
                });
        });

        $(document).on(
            'fullscreenchange webkitfullscreenchange MSFullscreenChange',
            function () {
                var d = document;
                var fsEl = d.fullscreenElement || d.webkitFullscreenElement || d.msFullscreenElement;
                if (!fsEl) {
                    $btnFs.attr('aria-label', 'Enter fullscreen');
                }
            }
        );
    })();

    function getMatchSuiteScope($trigger) {
        if ($trigger && $trigger.length) {
            var $scoped = $trigger.closest('#matchSuite, .fl-node-z86wiqbapmyl, #suite-matching-popup-root');
            if ($scoped.length) {
                return $scoped;
            }
        }

        var $fallback = $('#matchSuite, .fl-node-z86wiqbapmyl, #suite-matching-popup-root').first();
        return $fallback.length ? $fallback : $(document);
    }

    function getNamedFieldValue(name, $scope) {
        var $root = $scope && $scope.length ? $scope : $(document);
        var $checked = $root.find('[name="' + name + '"]:checked');
        if ($checked.length) {
            return ($checked.val() || '').toString().trim();
        }
        var $field = $root.find('[name="' + name + '"]').first();
        return ($field.val() || '').toString().trim();
    }

    function showFieldError(inputName, message, $scope) {
        var $root = $scope && $scope.length ? $scope : $(document);
        var $fields = $root.find('[name="' + inputName + '"]');
        if (!$fields.length) {
            return;
        }

        $fields.css('border', '1px solid red');

        var $anchor = $fields.first();
        if ($fields.first().is(':radio,:checkbox')) {
            $anchor = $fields.closest('label').first();
            if (!$anchor.length) {
                $anchor = $fields.first().parent();
            }
        }

        if ($anchor.next('.error-msg').length === 0) {
            $anchor.after('<span class="error-msg"></span>');
        }

        $anchor.next('.error-msg').text(message);
    }

    function setupPersonalModalElement(modal, titleId) {
        if (!modal || modal.dataset.personalModalInit === '1') {
            return;
        }

        modal.dataset.personalModalInit = '1';
        modal.setAttribute('role', 'dialog');
        modal.setAttribute('aria-modal', 'true');
        modal.setAttribute('aria-hidden', 'true');
        modal.setAttribute('inert', '');

        if (titleId) {
            modal.setAttribute('aria-labelledby', titleId);
            var heading = modal.querySelector('h2');
            if (heading && !heading.id) {
                heading.id = titleId;
            }
        }
    }

    function getPersonalModalConfig($context) {
        var $active = ($context && $context.length)
            ? $context.closest('#suite-matching-personal-modal, #personalInfoModal')
            : $();

        if ($active.is('#suite-matching-personal-modal')) {
            return {
                modal: $active[0],
                name: '#suite-match-name',
                email: '#suite-match-email',
                phone: '#suite-match-phone',
                contact: 'suite_match_contact',
                usesFooterModal: true
            };
        }

        if ($active.is('#personalInfoModal')) {
            return {
                modal: $active[0],
                name: '#name',
                email: '#email',
                phone: '#phone',
                contact: 'contact',
                usesFooterModal: false
            };
        }

        var footerModal = document.getElementById('suite-matching-personal-modal');
        if (footerModal && footerModal.classList.contains('is-open')) {
            return {
                modal: footerModal,
                name: '#suite-match-name',
                email: '#suite-match-email',
                phone: '#suite-match-phone',
                contact: 'suite_match_contact',
                usesFooterModal: true
            };
        }

        var legacyModal = document.getElementById('personalInfoModal');
        if (legacyModal && legacyModal.classList.contains('is-open')) {
            return {
                modal: legacyModal,
                name: '#name',
                email: '#email',
                phone: '#phone',
                contact: 'contact',
                usesFooterModal: false
            };
        }

        if (footerModal) {
            return {
                modal: footerModal,
                name: '#suite-match-name',
                email: '#suite-match-email',
                phone: '#suite-match-phone',
                contact: 'suite_match_contact',
                usesFooterModal: true
            };
        }

        if (legacyModal) {
            return {
                modal: legacyModal,
                name: '#name',
                email: '#email',
                phone: '#phone',
                contact: 'contact',
                usesFooterModal: false
            };
        }

        return {
            modal: null,
            name: '#name',
            email: '#email',
            phone: '#phone',
            contact: 'contact',
            usesFooterModal: false
        };
    }

    function initPersonalInfoModal() {
        setupPersonalModalElement(
            document.getElementById('suite-matching-personal-modal'),
            'suite-match-personal-title'
        );

        var legacyModal = document.getElementById('personalInfoModal');
        setupPersonalModalElement(legacyModal, 'personal-info-modal-title');

        // Force cursor:pointer inline on all close buttons (overrides any theme CSS)
        var closeButtons = document.querySelectorAll('#personalInfoModal .close-modal, #suite-matching-personal-modal .suite-matching-personal-modal__close, [data-suite-matching-personal-close]');
        closeButtons.forEach(function(btn) {
            btn.style.setProperty('cursor', 'pointer', 'important');
        });

        if (document.getElementById('suite-matching-personal-modal') && legacyModal) {
            legacyModal.style.display = 'none';
            legacyModal.setAttribute('aria-hidden', 'true');
            legacyModal.setAttribute('inert', '');
        }
    }

    function moveFocusOutOfElement(container, preferredTarget) {
        if (!container) {
            return;
        }

        var active = document.activeElement;
        if (!active || !container.contains(active)) {
            return;
        }

        if (preferredTarget && typeof preferredTarget.focus === 'function') {
            preferredTarget.focus({ preventScroll: true });
            return;
        }

        if (typeof active.blur === 'function') {
            active.blur();
        }
    }

    function ensureSignupSuiteModalClosed(focusTarget) {
        var modal = document.getElementById('signup-a-suite-modal');
        if (!modal || !modal.classList.contains('is-open')) {
            document.body.classList.remove('loftloader-disable-scrolling', 'signup-a-suite-modal-open');
            return;
        }

        if (window.legacySignupSuite && typeof window.legacySignupSuite.closeModal === 'function') {
            window.legacySignupSuite.closeModal(modal, {
                focusTarget: focusTarget || null,
                restoreFocus: !focusTarget
            });
            return;
        }

        moveFocusOutOfElement(modal, focusTarget);
        modal.classList.remove('is-open');
        modal.setAttribute('inert', '');
        document.body.classList.remove('loftloader-disable-scrolling', 'signup-a-suite-modal-open');

        window.requestAnimationFrame(function () {
            modal.setAttribute('aria-hidden', 'true');
        });
    }

    function closePersonalInfoModalElement(modal) {
        if (!modal) {
            return;
        }

        moveFocusOutOfElement(modal, document.querySelector('.btn-matching'));
        modal.classList.remove('is-open');
        modal.style.display = 'none';
        modal.setAttribute('inert', '');
        modal.setAttribute('aria-hidden', 'true');
    }

    function closePersonalInfoModal() {
        closePersonalInfoModalElement(document.getElementById('personalInfoModal'));
        closePersonalInfoModalElement(document.getElementById('suite-matching-personal-modal'));

        if (window.legacySuiteMatchingPopup && typeof window.legacySuiteMatchingPopup.close === 'function') {
            window.legacySuiteMatchingPopup.close();
        }
    }

    function openPersonalInfoModal() {
        var config = getPersonalModalConfig();
        var modal = config.modal;
        if (!modal) {
            return;
        }

        initPersonalInfoModal();

        var nameField = document.querySelector(config.name);
        ensureSignupSuiteModalClosed(nameField || modal);

        if (modal.parentElement !== document.body) {
            document.body.appendChild(modal);
        }

        modal.removeAttribute('inert');
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        modal.style.display = 'block';
        modal.style.zIndex = '10000003';

        window.requestAnimationFrame(function () {
            if (nameField && typeof nameField.focus === 'function') {
                try {
                    nameField.focus({ preventScroll: true });
                } catch (err) {
                    nameField.focus();
                }
            }
        });
    }

    function closeVideoModal() {
        var $videoModal = $('#videoModal');
        if (!$videoModal.length) {
            return;
        }

        $videoModal.fadeOut();

        var modalVideo = document.getElementById('modalVideo');
        if (modalVideo) {
            modalVideo.pause();
            var source = modalVideo.querySelector('source');
            if (source) {
                source.removeAttribute('src');
            }
        }
    }

    // Find my Suite
    $(document).on('click', '.btn-matching', function (e) {
        e.preventDefault();

        var $scope = getMatchSuiteScope($(this));
        var profession = getNamedFieldValue('profession', $scope);
        var location = getNamedFieldValue('location_preference', $scope);
        var timeline = getNamedFieldValue('timeline', $scope);
        var budget = getNamedFieldValue('budget_weekly', $scope);

        $scope.find('.error-msg').text('');
        $scope.find('input, select').css('border', '');
        var isValid = true;

        if (!profession) {
            showFieldError('profession', 'Please select your profession', $scope);
            isValid = false;
        }
        if (!location) {
            showFieldError('location_preference', 'Please select your location', $scope);
            isValid = false;
        }
        if (!timeline) {
            showFieldError('timeline', 'Please select timeline', $scope);
            isValid = false;
        }
        if (!budget) {
            showFieldError('budget_weekly', 'Please select your budget', $scope);
            isValid = false;
        }
        if (!isValid) {
            return;
        }

        openPersonalInfoModal();
    });

    initPersonalInfoModal();

    window.legacyFindSuite = {
        openPersonalInfoModal: openPersonalInfoModal,
        closePersonalInfoModal: closePersonalInfoModal
    };
    
    // US phone format (123) 456-7890 — popup, CF7 (stylists/contact), and other forms
    function personalPhoneDigits(value) {
        return String(value || '').replace(/\D/g, '').slice(0, 10);
    }

    function personalFormatUSPhone(digits) {
        if (!digits) {
            return '';
        }
        if (digits.length <= 3) {
            return '(' + digits;
        }
        if (digits.length <= 6) {
            return '(' + digits.slice(0, 3) + ') ' + digits.slice(3);
        }
        return '(' + digits.slice(0, 3) + ') ' + digits.slice(3, 6) + '-' + digits.slice(6);
    }

    function validateUSPhoneInput(input) {
        if (!input) {
            return true;
        }
        var digits = personalPhoneDigits(input.value);
        if (!digits.length) {
            input.setCustomValidity('Please enter your phone number');
            return false;
        }
        if (digits.length !== 10) {
            input.setCustomValidity('Enter valid 10 digit phone');
            return false;
        }
        input.setCustomValidity('');
        return true;
    }

    function bindUSPhoneField($input) {
        if (!$input || !$input.length || $input.data('usPhoneBound') === 1) {
            return;
        }
        $input.data('usPhoneBound', 1);

        var placeholder = ($input.attr('placeholder') || '').toString().trim();
        $input
            .attr('maxlength', 14)
            .attr('inputmode', 'numeric')
            .attr('autocomplete', 'tel-national');

        if (!placeholder || placeholder === 'Phone Number') {
            $input.attr('placeholder', '(123) 456-7890');
        }

        $input.on('beforeinput', function (e) {
            if (e.originalEvent && e.originalEvent.data && /\D/.test(e.originalEvent.data)) {
                e.preventDefault();
            }
        });

        $input.on('keydown', function (e) {
            var allowed = ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Home', 'End'];
            if (allowed.indexOf(e.key) !== -1 || e.ctrlKey || e.metaKey) {
                return;
            }
            if (!/^\d$/.test(e.key)) {
                e.preventDefault();
            }
        });

        $input.on('input', function () {
            var digits = personalPhoneDigits($input.val());
            var formatted = personalFormatUSPhone(digits);
            if ($input.val() !== formatted) {
                $input.val(formatted);
            }
            validateUSPhoneInput($input[0]);
            $input.css('border', '');
            $input.next('.error-msg').text('');
        });

        $input.on('blur', function () {
            validateUSPhoneInput($input[0]);
        });

        $input.on('paste', function (e) {
            e.preventDefault();
            var pasted = (e.originalEvent && e.originalEvent.clipboardData)
                ? e.originalEvent.clipboardData.getData('text')
                : '';
            var digits = personalPhoneDigits(pasted);
            $input.val(personalFormatUSPhone(digits));
            validateUSPhoneInput($input[0]);
        });
    }

    function initAllUSPhoneFields($root) {
        ($root || $(document)).find('input[name="phone"]').each(function () {
            bindUSPhoneField($(this));
        });
    }

    initAllUSPhoneFields();

    document.addEventListener('wpcf7domready', function (event) {
        if (event && event.target) {
            initAllUSPhoneFields($(event.target));
        } else {
            initAllUSPhoneFields();
        }
    });

    $(document).on('submit', 'form.wpcf7-form', function () {
        var $phone = $(this).find('input[name="phone"]');
        if (!$phone.length) {
            return;
        }
        var digits = personalPhoneDigits($phone.val());
        if (digits) {
            $phone.val(personalFormatUSPhone(digits));
        }
        validateUSPhoneInput($phone[0]);
    });

    bindUSPhoneField($('#phone'));
    bindUSPhoneField($('#suite-match-phone'));

    /* ------------------------------------------------------------------
     * Shared inline validation for personalInfoModal / suite-matching-personal-modal.
     * Returns true if all fields are valid; shows inline red errors if not.
     * ---------------------------------------------------------------- */
    function validatePersonalModalFields($modalScope, config) {
        var isValid = true;

        // Clear previous errors
        $modalScope.find('.error-msg').each(function() {
            $(this).text('');
        });
        $modalScope.find(config.name + ', ' + config.email + ', ' + config.phone)
            .css('border', '').css('outline', '');

        function showErr($input, message) {
            $input.css('border', '1px solid red');
            if ($input.next('.error-msg').length === 0) {
                $input.after('<span class="error-msg" style="color:red;font-size:13px;display:block;margin-top:4px;"></span>');
            }
            $input.next('.error-msg')
                .text(message)
                .css({ color: 'red', 'font-size': '13px', display: 'block', 'margin-top': '4px' });
            isValid = false;
        }

        var $name    = $modalScope.find(config.name);
        var $email   = $modalScope.find(config.email);
        var $phone   = $modalScope.find(config.phone);
        var $contact = $modalScope.find('input[name="' + config.contact + '"]:checked');

        if (!$name.val() || !$name.val().trim()) {
            showErr($name, 'Please enter your name');
        }

        if (!$email.val() || !$email.val().trim()) {
            showErr($email, 'Please enter your email');
        } else {
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test($email.val().trim())) {
                showErr($email, 'Please enter a valid email');
            }
        }

        var phoneDigitsCheck = personalPhoneDigits($phone.val());
        if (phoneDigitsCheck) {
            $phone.val(personalFormatUSPhone(phoneDigitsCheck));
        }
        if (!phoneDigitsCheck) {
            showErr($phone, 'Please enter your phone number');
        } else if (phoneDigitsCheck.length !== 10) {
            showErr($phone, 'Enter a valid 10-digit phone number');
        }

        if ($contact.length === 0) {
            $modalScope.find('.contact-error').remove();
            $modalScope.find('.labelWrap').append(
                '<span class="error-msg contact-error" style="color:red;font-size:13px;display:block;margin-top:4px;">Please select a contact method</span>'
            );
            isValid = false;
        } else {
            $modalScope.find('.contact-error').remove();
        }

        return isValid;
    }

    // Expose globally so modal-recaptcha.js can call it before CAPTCHA check
    window.lasValidatePersonalModal = function(modal) {
        if (!modal) { return false; }
        var $modal = $(modal);
        var config = getPersonalModalConfig($modal);
        return validatePersonalModalFields($modal, config);
    };

    // pop-up suite match submit event
    $(document).on('click', '.submit-btn, .suite-matching-submit-btn', function(e) {
        e.preventDefault();
        var $submitBtn = $(this);
        var config = getPersonalModalConfig($submitBtn);
        var $modalScope = config.modal ? $(config.modal) : $submitBtn.closest('#suite-matching-personal-modal, #personalInfoModal');
        var modalEl = $modalScope[0];

        // Step 1: Validate all required fields first
        if (!validatePersonalModalFields($modalScope, config)) {
            return; // Field errors shown — do NOT check CAPTCHA yet
        }

        // Step 2: All fields valid — now check reCAPTCHA
        if (window.lasRecaptcha && modalEl) {
            var recaptchaToken = window.lasRecaptcha.getToken(modalEl);
            if (!recaptchaToken) {
                window.lasRecaptcha.showError(modalEl, window.lasRecaptcha.errorMsg);
                return;
            }
            window.lasRecaptcha.clearError(modalEl);
            window.lasRecaptcha.setPendingToken(recaptchaToken);
        }

        var $name        = $modalScope.find(config.name);
        var $email       = $modalScope.find(config.email);
        var $phone       = $modalScope.find(config.phone);
        var $contact     = $modalScope.find('input[name="' + config.contact + '"]:checked');
        var phoneDigitsVal = personalPhoneDigits($phone.val());

        var ajaxPostUrl = (typeof customAjax !== 'undefined' && customAjax.ajax_url)
            ? customAjax.ajax_url
            : (typeof ajaxurl !== 'undefined' ? ajaxurl : '');

        if (!ajaxPostUrl) {
            return;
        }

        $.ajax({
            url: ajaxPostUrl,
            type: 'POST',
            data: {
                action: 'match_salon_suites',
                profession: getNamedFieldValue('profession', getMatchSuiteScope()),
                location: getNamedFieldValue('location_preference', getMatchSuiteScope()),
                timeline: getNamedFieldValue('timeline', getMatchSuiteScope()),
                budget: getNamedFieldValue('budget_weekly', getMatchSuiteScope()),
                name: $name.val(),
                email: $email.val(),
                phone: phoneDigitsVal,
                contact_method: $contact.val()
            },
            success: function(response) {
                if(response.success){
                    closePersonalInfoModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.data.message,
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/';
                        }
                    });
                }
            },
            error: function(xhr) {
                console.log("Error:", xhr.responseText);
            }
        });
    });


    // Remove error on typing
    $(document).on('input change', 'input, select', function() {
        $(this).css('border', '');
        $(this).next('.error-msg').text('');
    });

    // Clear contact-method error when a radio option is selected
    $(document).on('change', 'input[name="contact"], input[name="suite_match_contact"]', function() {
        $(this).closest('#personalInfoModal, #suite-matching-personal-modal').find('.contact-error').remove();
    });

    // Close modal
    $(document).on('click', '.close-modal, [data-suite-matching-personal-close]', function() {
        closePersonalInfoModal();
    });

    // Close when clicking outside
    $(document).on('click', '#personalInfoModal, #suite-matching-personal-modal', function(e) {
        if ($(e.target).is('#personalInfoModal') || $(e.target).is('#suite-matching-personal-modal')) {
            closePersonalInfoModal();
        }
    });

    // Other field for suite matching (wizard page only)
    var professionSelect = document.getElementById('cw-profession');
    var otherField = document.getElementById('cw-profession-other');

    if (professionSelect && otherField) {
        professionSelect.addEventListener('change', function () {
            if (this.value === 'Other') {
                otherField.style.display = 'block';
                otherField.setAttribute('required', 'required');
            } else {
                otherField.style.display = 'none';
                otherField.removeAttribute('required');
                otherField.value = '';
            }
        });
    }

    $(document).on('click', '.close-video', function (e) {
        e.preventDefault();
        e.stopPropagation();
        closeVideoModal();
    });

    $(document).on('click', '#videoModal', function (e) {
        if ($(e.target).is('#videoModal')) {
            closeVideoModal();
        }
    });
    
    // Stylist Gallery
    
    let frame;
    
        function updateHiddenField() {
            let data = [];
    
            $('#gallery-list .gallery-item').each(function(){
                data.push($(this).data('url'));
            });
    
            $('#stylist-gallery-data').val(JSON.stringify(data));
        }
    
        // ADD MEDIA
        $('#add-gallery').on('click', function(e){
            e.preventDefault();
    
            if(frame){
                frame.open();
                return;
            }
    
            frame = wp.media({
                title: 'Select Images or Videos',
                button: { text: 'Add to Gallery' },
                multiple: true
            });
    
            frame.on('select', function(){
                let selection = frame.state().get('selection').toJSON();
    
                selection.forEach(function(file){
    
                    let html = `
                    <li class="gallery-item" data-url="${file.url}" style="position:relative;width:100px;">
                        
                        ${file.type === 'video'
                            ? `<video src="${file.url}" style="width:100px;height:80px;object-fit:cover;"></video>`
                            : `<img src="${file.url}" style="width:100px;height:80px;object-fit:cover;">`
                        }
    
                        <button type="button" class="remove-item"
                            style="position:absolute;top:2px;right:2px;background:red;color:#fff;border:none;border-radius:50%;width:18px;height:18px;font-size:12px;cursor:pointer;">
                            ×
                        </button>
    
                    </li>`;
    
                    $('#gallery-list').append(html);
                });
    
                updateHiddenField();
            });
    
            frame.open();
        });
    
        // REMOVE ITEM
        $(document).on('click', '.remove-item', function(){
            $(this).closest('.gallery-item').remove();
            updateHiddenField();
        });
        



        
        





});