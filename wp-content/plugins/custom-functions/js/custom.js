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

    function showError(inputName, message) {
        var input = $('[name="' + inputName + '"]');
        input.css('border', '1px solid red');
        if (input.next('.error-msg').length === 0) {
            input.after('<span class="error-msg"></span>');
        }
        
        input.next('.error-msg').text(message);
    }

    // Match btn click event
    $('.btn-matching').on('click', function(e) {
        e.preventDefault();

        var profession = $('[name="profession"]').val();
        var location   = $('[name="location_preference"]').val();
        var timeline   = $('[name="timeline"]').val();
        var budget     = $('[name="budget_weekly"]').val();

        $('.error-msg').text('');
        $('input').css('border', '');
        var isValid = true;

        if (!profession) {
            showError('profession', 'Please select your profession');
            isValid = false;
        }
        if (!location) {
            showError('location_preference', 'Please select your location');
            isValid = false;
        }
        if (!timeline) {
            showError('timeline', 'Please select timeline');
            isValid = false;
        }
        if (!budget) {
            showError('budget_weekly', 'Please select your budget');
            isValid = false;
        }
        if (!isValid) return;
        $('#personalInfoModal').fadeIn();
    });
    
    // pop-up suite match submit event
    $('.submit-btn').on('click', function(e) {
        e.preventDefault();
        var isValid = true;

        $('.error-msg').text('');
        $('input').css('border', '');

        function showError(input, message) {
            input.css('border', '1px solid red');
            if (input.next('.error-msg').length === 0) {
                input.after('<span class="error-msg"></span>');
            }

            input.next('.error-msg').text(message);
            isValid = false;
        }

        var name  = $('#name');
        var email = $('#email');
        var phone = $('#phone');
        var contact = $('input[name="contact"]:checked');
        console.log(phone);

        // Validation
        if (!name.val()) {
            showError(name, 'Please enter your name');
        }

        if (!email.val()) {
            showError(email, 'Please enter your email');
        } else {
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email.val())) {
                showError(email, 'Please enter valid email');
            }
        }

         if (!phone.val()) {
            showError(phone, 'Please enter your phone number');
        } else {
            var phonePattern = /^[0-9]{10}$/;
            if (!phonePattern.test(phone.val())) {
                showError(phone, 'Enter valid 10 digit phone');
            }
        }

        if (contact.length === 0) {
            $('.contact-error').remove();
            $('.labelWrap').append('<span class="error-msg contact-error">Please select contact method</span>');
            isValid = false;
        } else {
            $('.contact-error').remove();
        }

        if (!isValid) return;


        $.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                action: "match_salon_suites",
                profession: $('[name="profession"]').val(),
                location: $('[name="location_preference"]').val(),
                timeline: $('[name="timeline"]').val(),
                budget: $('[name="budget_weekly"]').val(),
                name: name.val(),
                email: email.val(),
                phone: phone.val(),
                contact_method: contact.val()
            },
            success: function(response) {
                if(response.success){
                    $('#personalInfoModal').fadeOut();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.data.message,
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/'; // redirect to home
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

    // Close modal
    $('.close-modal').on('click', function() {
        $('#personalInfoModal').fadeOut();
    });

    // Close when clicking outside
    $(window).on('click', function(e) {
        if ($(e.target).is('#personalInfoModal')) {
            $('#personalInfoModal').fadeOut();
        }
    });

    // Other field for suite  matching
    const professionSelect = document.getElementById("cw-profession");
    const otherField = document.getElementById("cw-profession-other");

    professionSelect.addEventListener("change", function () {
        if (this.value === "Other") {
            otherField.style.display = "block";
            otherField.setAttribute("required", "required");
        } else {
            otherField.style.display = "none";
            otherField.removeAttribute("required");
            otherField.value = "";
        }
    });
    
    // Featured Suite Video Play
    $('.video-card').on('click', function(){
        var videoUrl = $(this).data('video');

        $('#modalVideo source').attr('src', videoUrl);
        $('#modalVideo')[0].load();

        $('#videoModal').fadeIn();
    });

    $('.close-video').on('click', function(){
        $('#videoModal').fadeOut();
        $('#modalVideo')[0].pause();
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