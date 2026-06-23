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
            
            function restrictExperienceToDigits($input) {
                if (!$input || !$input.length || $input.data('legacyDigitsOnly')) {
                    return;
                }
                
                 var maxDigits = 3;
                var maxValue = 999;

                function normalizeExperienceValue(value) {
                    var cleaned = String(value || '').replace(/\D/g, '').slice(0, maxDigits);

                    if (cleaned !== '' && parseInt(cleaned, 10) > maxValue) {
                        cleaned = String(maxValue);
                    }

                    return cleaned;
                }

                $input.data('legacyDigitsOnly', true);
                $input.attr({
                    inputmode: 'numeric',
                    pattern: '[0-9]{1,3}',
                    min: '0',
                    max: String(maxValue),
                    step: '1'
                });
                 
                $input.val(normalizeExperienceValue($input.val()));
                 
                $input.on('input', function () {
                    var cleaned = normalizeExperienceValue($(this).val());

                    if ($(this).val() !== cleaned) {
                        $(this).val(cleaned);
                    }
                });

                $input.on('keydown', function (e) {
                    var allowedKeys = [8, 9, 13, 27, 46, 37, 38, 39, 40];

                    if (allowedKeys.indexOf(e.keyCode) !== -1) {
                        return;
                    }

                    if ((e.ctrlKey || e.metaKey) && [65, 67, 86, 88].indexOf(e.keyCode) !== -1) {
                        return;
                    }

                     if (e.key && e.key.length === 1) {
                        if (!/^\d$/.test(e.key)) {
                            e.preventDefault();
                            return;
                        }

                        var current = String($(this).val() || '');
                        var selectionLength = (this.selectionEnd || 0) - (this.selectionStart || 0);

                        if (selectionLength === 0 && current.replace(/\D/g, '').length >= maxDigits) {
                            e.preventDefault();
                        }
                    }
                });

                $input.on('paste', function (e) {
                      e.preventDefault();
                    var pasted = '';

                    if (e.originalEvent && e.originalEvent.clipboardData) {
                        pasted = e.originalEvent.clipboardData.getData('text');
                    }

                    var current = String($(this).val() || '');
                    var start = this.selectionStart || 0;
                    var end = this.selectionEnd || 0;
                    var merged = current.slice(0, start) + pasted + current.slice(end);
                    var cleaned = normalizeExperienceValue(merged);

                    $(this).val(cleaned);
                });
            }

            acf.add_action('ready_field/name=experience', function ($field) {
                restrictExperienceToDigits($field.find('input'));
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
