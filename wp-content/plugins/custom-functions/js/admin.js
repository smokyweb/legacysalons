    /**
     * -----------------------------------------------------------------------------
     * Admin scripts — Custom Functions plugin
     * -----------------------------------------------------------------------------
     */
    (function ($) {

        'use strict';

        /* -------------------------------------------------------------------------
        * Google Places autocomplete (ACF location field)
        * ------------------------------------------------------------------------- */
        function initAutocomplete($el) {

            var input = $el.find('#acf-field_69f9d8a03e9ca')[0];

            if (!input || typeof google === 'undefined' || !google.maps || !google.maps.places) {
                return;
            }

            var autocomplete = new google.maps.places.Autocomplete(input, {
                types: ['address']
            });

            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();

                if (place.formatted_address) {
                    $(input).val(place.formatted_address);
                }
            });
        }

        if (typeof acf !== 'undefined') {
            acf.add_action('ready append', function ($el) {
                if (typeof google !== 'undefined') {
                    initAutocomplete($el);
                }
            });
        }

        /* -------------------------------------------------------------------------
        * Suites Transformation — WordPress media uploader (before/after images)
        * ------------------------------------------------------------------------- */
        var stMediaFrames = {};

        function stUpdateImageField(target, attachment) {
            var id = attachment.id;
            var url = attachment.sizes && attachment.sizes.medium
                ? attachment.sizes.medium.url
                : attachment.url;

            $('#st_' + target + '_image_id').val(id);

            var $preview = $('#st_' + target + '_preview');
            $preview.addClass('has-image').html(
                '<img src="' + url + '" alt="" />'
            );

            $('[data-st-image-field="' + target + '"] .st-remove-image').show();
        }

        function stClearImageField(target) {
            $('#st_' + target + '_image_id').val('');

            var $preview = $('#st_' + target + '_preview');
            $preview.removeClass('has-image').html(
                '<span class="st-image-placeholder">No image selected</span>'
            );

            $('[data-st-image-field="' + target + '"] .st-remove-image').hide();
        }

        function stOpenMediaFrame(target) {
            if (stMediaFrames[target]) {
                stMediaFrames[target].open();
                return;
            }

            stMediaFrames[target] = wp.media({
                title: target === 'before' ? 'Select Before Image' : 'Select After Image',
                button: { text: 'Use this image' },
                library: { type: 'image' },
                multiple: false
            });

            stMediaFrames[target].on('select', function () {
                var attachment = stMediaFrames[target].state().get('selection').first().toJSON();
                stUpdateImageField(target, attachment);
            });

            stMediaFrames[target].open();
        }

        var stVideoFrame = null;

        function stUpdateVideoField(attachment) {
            var id = attachment.id;
            var url = attachment.url;
            var title = attachment.title || attachment.filename || 'Video';

            $('#st_video_id').val(id);

            var $preview = $('#st_video_preview');
            $preview.addClass('has-video').html(
                '<video src="' + url + '" controls preload="metadata"></video>' +
                '<p class="st-video-filename">' + title + '</p>'
            );

            $('.st-remove-video').show();
        }

        function stClearVideoField() {
            $('#st_video_id').val('');

            var $preview = $('#st_video_preview');
            $preview.removeClass('has-video').html(
                '<span class="st-video-placeholder">No video selected</span>'
            );

            $('.st-remove-video').hide();
        }

        function stOpenVideoFrame() {
            if (typeof wp === 'undefined' || !wp.media) {
                return;
            }

            if (stVideoFrame) {
                stVideoFrame.open();
                return;
            }

            stVideoFrame = wp.media({
                title: 'Select Transformation Video',
                button: { text: 'Use this video' },
                library: { type: 'video' },
                multiple: false
            });

            stVideoFrame.on('select', function () {
                var attachment = stVideoFrame.state().get('selection').first().toJSON();
                stUpdateVideoField(attachment);
            });

            stVideoFrame.open();
        }

        $(function () {
            if (!$('.st-transform-metabox').length) {
                return;
            }

            $(document).on('click', '.st-upload-image', function (e) {
                e.preventDefault();
                var target = $(this).data('target');
                if (target) {
                    stOpenMediaFrame(target);
                }
            });

            $(document).on('click', '.st-remove-image', function (e) {
                e.preventDefault();
                var target = $(this).data('target');
                if (target) {
                    stClearImageField(target);
                }
            });

            $(document).on('click', '.st-upload-video', function (e) {
                e.preventDefault();
                stOpenVideoFrame();
            });

            $(document).on('click', '.st-remove-video', function (e) {
                e.preventDefault();
                stClearVideoField();
            });
        });

    })(jQuery);
