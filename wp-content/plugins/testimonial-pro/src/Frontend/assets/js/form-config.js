jQuery(function ($) {
	'use strict';
	/* ==== Chosen ====*/
	$(".chosen-select").chosen({ width: "100%" });
	/* ==== Social Profile Check ====*/
	$(".tpro-social-profile-check").on('change', function () {
		var social_profile_check = $("input[name=tpro_social_profile_check]").attr('checked') ? '1' : '0';
		if (social_profile_check == '1') {
			$('.tpro-social-profile-links').show();
		} else {
			$('.tpro-social-profile-links').hide();
		}
	});
	(function () {
		$("#testimonial_form").validate();

		/*
		 * Functionalities of Words & Character limits of testimonial title and content 
		*/
		$('.sp-tpro-fronted-form').each(function () {
			var formID = '#' + $(this).attr('id');
			var titleID = $(formID).find('.sp-tpro-form-title').attr('id');

			// Function to limit the input to a maximum characters.
			function maximumCharacterLimit(selector, displayCounter, limit) {
				$(selector).on('keydown', function () {
					var input = $(this).val();
					// Truncate if exceeds limit
					if (input.length > limit) {
						input = input.slice(0, limit);
						$(this).val(input);
					}
					$(displayCounter).text(input.length + " characters out of " + limit);
				});
			}
			// Function to limit the input to a maximum words.
			function maximumWordLimit(selector, displayCounter, limit) {
				$(selector).on('keydown', function (e) {
					var input = $(this).val();
					var words = input.trim().split(/\s+/);
					if (words.length > limit) {
						var truncated = words.slice(0, limit).join(" ");
						$(this).val(truncated);
					}
					$(displayCounter).text(words.length + " words out of " + limit);
				});
			}

			titleWordLimit = '';
			titleCharLimit = '';
			titleLimitType = '';
			if ($(formID).find('#sp-maximum_length').length > 0) {
				var spTestimonialLimit = $(formID).find('#sp-maximum_length').data('length_type'),
					titleWordLimit = spTestimonialLimit.words,
					titleCharLimit = spTestimonialLimit.characters,
					titleLimitType = spTestimonialLimit.type;

				// Display the character or word counter dynamic text of testimonial Title.
				if (titleLimitType === 'characters') {
					maximumCharacterLimit('#tpro_testimonial_title' + titleID, '#sp-maximum_length', titleCharLimit);
				} else {
					maximumWordLimit('#tpro_testimonial_title' + titleID, '#sp-maximum_length', titleWordLimit);
				}
			}

			contentWordLimit = '';
			contentCharLimit = '';
			contentLimitType = '';
			if ($(formID).find('#sp-content_maximum_length').length > 0) {
				var spTestimonialContentLimit = $(formID).find('#sp-content_maximum_length').data('length_type'),
					contentWordLimit = spTestimonialContentLimit.words,
					contentCharLimit = spTestimonialContentLimit.characters,
					contentLimitType = spTestimonialContentLimit.type;

				// Display the character or word counter dynamic text of testimonial Content.
				if (contentLimitType === 'characters') {
					maximumCharacterLimit('#tpro_client_testimonial' + titleID, '#sp-content_maximum_length', contentCharLimit);
				} else {
					maximumWordLimit('#tpro_client_testimonial' + titleID, '#sp-content_maximum_length', contentWordLimit);
				}
			}

			// Check If the form display type is popup then execute the popup.
			var formDisplayType = $('.sp-tpro-popup-btn-wrapper-' + titleID).data('display_type');
			if (formDisplayType === 'popup') {
				$('.sp-tpro-open-form-popup').magnificPopup({
					mainClass: 'mfp-fade sp-testimonial-lightbox',
					type: 'inline',
					fixedBgPos: true,
					overflowY: 'auto',
					closeBtnInside: true,
					preloader: false,
					focus: '#name',
					midClick: false,
					removalDelay: 300,
				});
			}

			$(formID).on('submit', '#testimonial_form', function (e) {
				var form_attr = $(formID).data('form_attr'),
					hideForm = form_attr.hide_form,
					required_rating = form_attr.rating_required,
					ajax_form_submission = form_attr.ajax_submission;
				$(this).find('.sp-tpro-form-submit-button #submit').after('<span class="spftestimonial-loading-spinner"><i class="fa fa-spinner" aria-hidden="true"></i></span>');

				if (required_rating) {
					var formValid = false;
					$('input[name="tpro_client_rating"]').each(function () {
						if ($(this).is(':checked')) {
							formValid = true;
							return false; // exit the loop early
						}
					});

					if (!formValid) {
						$('#requiredMessage').show(); // Show required message
						setTimeout(() => {
							$(formID).find('#requiredMessage').remove();
							$(formID).find('.sp-tpro-form-validation-msg').remove();
						}, 5000);
						e.preventDefault();
						return;
					}
				}

				$(this).validate();
				if ($(this).valid()) {
					$(this).find('#submit').css({ "pointer-event": "none", "opacity": 0.75 });
				}

				if (ajax_form_submission == 1) {
					// Function to validate email input
					function isValidEmail(email) {
						// Regular expression for email validation
						var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
						return emailPattern.test(email);
					}
					e.preventDefault(); // Prevent the default form submission

					var generateId = $(formID).data('form_id');
					var email = $('#tpro_client_email' + titleID).val();
					var $container = $(formID).find('.sp-testimonial-form-container');
					if (email && !isValidEmail(email)) {
						return false; // If email is not valid then return.
					}

					var formData = new FormData(this);

					formData.append('action', 'testimonial_form_submission');
					formData.append('generator_id', generateId);
					formData.append('nonce', testimonial_form_vars.nonce);
					formData.append('submit', $(formID + ' input[type="submit"]').val());
					// AJAX request
					$.ajax({
						type: 'POST',
						url: testimonial_form_vars.ajax_url, // WordPress AJAX handler URL
						data: formData,
						contentType: false,
						processData: false,
						success: function (response) {
							// Handle the response (e.g., display success message)
							if ((response.data.redirect_type == 'to_a_page' || response.data.redirect_type == 'custom_url')) {
								// Redirect to the URL provided in the response
								window.location.href = response.data.redirect_url;
							}

							if (response.success) {
								var message = '<div class="sp-testimonial-form-successful-msg">' +
									response.data.successful_message + '</div>'; // keep the message inside a div wrapper.

								// Positions of form submission successful message. 
								if (response.data.message_position == 'top') {
									$container.prepend(message);
								} else {
									$container.append(message);
								}

								$container.find('.spftestimonial-loading-spinner').remove();
								$container.find('#submit').css({ "pointer-event": "default", "opacity": 1 });
								// after being completed form submission, make empty the form data.
								$(formID).find('input:not([type="submit"]), textarea, select').val('');

								if (1 == hideForm) {
									$(formID + ' #testimonial_form,.sp-testimonial-required-message').css('display', 'none');
								}

								setTimeout(() => {
									$container.find('.sp-testimonial-form-successful-msg').remove();
									if (1 == hideForm) {
										$(formID + ' #testimonial_form,.sp-testimonial-required-message').css('display', 'block');
									}
								}, 6000);
							}
						},
						error: function (error, response) {
							var error = JSON.parse(error.responseText);
							$container.append('<div class="sp-testimonial-form-error-msg">' + error.data.error_message + '</div>');
							setTimeout(() => {
								$container.find('.sp-testimonial-form-error-msg').remove();
							}, 6000);
						}
					});
				}
			}); // end ajax form submission functionalities. */
			// Scripts for the select country.
			const countries = testimonial_form_vars?.countries;
			const selects = document.getElementsByClassName('tpro-country-select');

			// Loop through each select element
			for (let i = 0; i < selects.length; i++) {
				const select = selects[i];

				// Add default "None" option
				const defaultOption = document.createElement('option');
				defaultOption.value = ''; // Empty value
				defaultOption.text = 'Select Country'; // Display text
				select.appendChild(defaultOption); // Append to select element

				// Loop through the countries array to add each country as an option
				for (let j = 0; j < countries.length; j++) {
					const option = document.createElement('option');
					option.value = countries[j].name;
					option.text = ` ${countries[j].name} ${countries[j].flag}`;
					select.appendChild(option);
				}
			}
		});
	}());
	jQuery.fn.extend({
		createProfile: function (options = {}) {
			var hasOption = function (optionKey) {
				return options.hasOwnProperty(optionKey);
			};

			var option = function (optionKey) {
				return options[optionKey];
			};

			var generateId = function (string) {
				return string
					.replace(/\[/g, '_')
					.replace(/\]/g, '')
					.toLowerCase();
			};

			var addItem = function (items, key, fresh = true) {
				var itemContent = items;
				var group = itemContent.data("group");
				var item = itemContent;
				var input = item.find('input,select');

				input.each(function (index, el) {
					var attrName = $(el).data('name');
					var skipName = $(el).data('skip-name');
					if (skipName != true) {
						$(el).attr("name", group + "[" + key + "][" + attrName + "]");
					} else {
						if (attrName != 'undefined') {
							$(el).attr("name", attrName);
						}
					}
					if (fresh == true) {
						$(el).attr('value', '');
					}

					$(el).attr('id', generateId($(el).attr('name')));
					$(el).parent().find('label').attr('for', generateId($(el).attr('name')));
				})

				var itemClone = items;

				/* Handling remove btn */
				var removeButton = itemClone.find('.tpro-social-profile-remove');
				if (removeButton) {
					removeButton.attr('onclick', 'jQuery(this).parents(\'.tpro-social-profile-item\').remove()');
				}
				var newItem = $("<div class='tpro-social-profile-item'>" + itemClone.html() + "<div/>");
				newItem.attr('data-index', key)

				newItem.appendTo(repeater);
			};

			/* Find elements */
			var repeater = this;
			var items = repeater.find(".tpro-social-profile-item");
			var key = 0;
			var addButton = $('.tpro-social-profile-wrapper').find('.tpro-add-new-profile-btn');

			items.each(function (index, item) {
				items.remove();
				if (hasOption('showFirstItemToDefault') && option('showFirstItemToDefault') == true) {
					addItem($(item), key);
					key++;
				} else {
					if (items.length > 1) {
						addItem($(item), key);
						key++;
					}
				}
			});
			/* Handle click and add items */
			addButton.on("click", function () {
				addItem($(items[0]), key);
				key++;
			});
		}
	});

	$("#tpro-social-profiles").createProfile({
		showFirstItemToDefault: true,
	});
	// Recorded video scripts for the testimonial from.
	function sp_testimonial_recorded_video(testimonialFormId, video_format) {
		let testimonialForm = document.getElementById(`testimonial_form_${testimonialFormId}`);
		let tpro_preview = testimonialForm.querySelector("#tpro_preview");
		let tpro_recording = testimonialForm.querySelector("#tpro_recording");
		let tpro_timer = testimonialForm.querySelector("#tpro_timer-text");
		let tpro_startButton = testimonialForm.querySelector("#tpro_startButton");
		let tpro_stopButton = testimonialForm.querySelector("#tpro_stopButton");
		let tpro_addButton = testimonialForm.querySelector("#tpro_addButton");
		let modal = testimonialForm.querySelector("#tpro_video_modal");
		let tpro_modal_btn = testimonialForm.querySelector("#tpro_modal_btn");
		let tpro_modal_close = testimonialForm.querySelector(".tpro_modal_close");
		let tpro_no_camera = testimonialForm.querySelector(".tpro_no_camera");
		let tpro_background = testimonialForm.querySelector(".tpro_background");

		if (tpro_modal_close) {
			tpro_modal_close.addEventListener('click', () => {
				modal.style.display = 'none';
				testimonialForm.querySelector('#tpro_startButton_text').innerText = 'Start Recording';
				tpro_recording.style.display = 'none';
				tpro_addButton.style.display = 'none';
			});
		}

		if (tpro_background) {
			tpro_background.addEventListener('click', () => {
				modal.style.display = 'none';
				testimonialForm.querySelector('#tpro_startButton_text').innerText = 'Start Recording';
				tpro_recording.style.display = 'none';
				tpro_addButton.style.display = 'none';
			});
		}

		window.addEventListener('click', (event) => {
			if (event.target === modal) {
				modal.style.display = 'none';
				testimonialForm.querySelector('#tpro_startButton_text').innerText = 'Start Recording';
				tpro_recording.style.display = 'none';
				tpro_addButton.style.display = 'none';
			}
		});

		let videoTimerInterval = null;
		let mediaRecorder;
		let chunks = [];
		let localStream = null;
		let containerType = 'mp4' === video_format ? "video/mp4" : "video/webm";

		function startTimer(duration, tpro_timer) {
			let timer = duration, minutes, seconds;
			videoTimerInterval = setInterval(function () {
				minutes = parseInt(timer / 60, 10);
				seconds = parseInt(timer % 60, 10);
				tpro_timer.innerHTML = (minutes < 10 ? "0" : "") + minutes + ":" + (seconds < 10 ? "0" : "") + seconds;
				tpro_timer.style.display = 'inline-block';
				if (--timer < 0) {
					timer = duration;
					clearInterval(videoTimerInterval);
					tpro_stopButton.click();
				}
			}, 1000);
		}

		tpro_modal_btn.onclick = function (e) {
			e.preventDefault();
			modal.style.display = "block";
			tpro_preview.style.display = 'block';
			tpro_startButton.style.display = 'block';

			if (!navigator.mediaDevices.getUserMedia) {
				alert('navigator.mediaDevices.getUserMedia not supported on your browser.');
				tpro_no_camera.style.display = 'flex';
				$('.tpro_record_video_buttons').css('display', 'none');
			} else if (window.MediaRecorder === undefined) {
				alert('MediaRecorder not supported on your browser.');
				tpro_no_camera.style.display = 'flex';
				$('.tpro_record_video_buttons').css('display', 'none');
			} else {
				navigator.mediaDevices.getUserMedia({
					audio: { noiseSuppression: false },
					video: { width: { min: 480, ideal: 800, max: 1280 }, height: { min: 320, ideal: 470, max: 720 }, framerate: 30 }
				})
					.then(function (stream) {
						localStream = stream;
						tpro_preview.srcObject = localStream;
						tpro_preview.play();
					})
					.catch(function (err) {
						tpro_no_camera.style.display = 'flex';
						$('.tpro_record_video_buttons').css('display', 'none');
						console.log('navigator.getUserMedia error: ' + err);
					});
			}
		}

		function onBtnRecordClicked() {
			if (!localStream) {
				alert('Could not get local stream from mic/camera');
				return;
			}

			tpro_startButton.disabled = true;
			tpro_stopButton.disabled = false;

			chunks = [];
			let options = 'webm' === video_format && MediaRecorder.isTypeSupported('video/webm') ? { mimeType: 'video/webm' } : { mimeType: 'video/mp4', videoBitsPerSecond: 2500000 };
			containerType = options.mimeType;
			mediaRecorder = new MediaRecorder(localStream, options);

			mediaRecorder.ondataavailable = function (e) {
				if (e.data && e.data.size > 0) {
					chunks.push(e.data);
				}
			};

			mediaRecorder.onstart = function () {
				startTimer((1000 * 60 * tpro_timer.getAttribute('data-maxtime')) / 1000, tpro_timer);
			};

			mediaRecorder.onstop = function () {
				let recording = new Blob(chunks, { type: mediaRecorder.mimeType });
				tpro_addButton.href = URL.createObjectURL(recording);
				tpro_recording.src = URL.createObjectURL(recording);
				tpro_recording.controls = true;

				let name = `video-testimonial-${Math.floor(Math.random() * 10000000)}.${containerType.split('/')[1]}`;
				tpro_addButton.setAttribute("download", name);
				tpro_addButton.setAttribute("name", name);

				tpro_addButton.addEventListener("click", function (e) {
					e.preventDefault();
					tpro_recording.style.display = 'none';
					tpro_addButton.style.display = 'none';
					testimonialForm.querySelector('#tpro_startButton_text').innerText = 'Start Recording';
					let file = new File([recording], name, { type: recording.type });
					let container = new DataTransfer();
					container.items.add(file);
					testimonialForm.querySelector('#tpro_client_video_upload').files = container.files;

					testimonialForm.querySelector('.sp-testimonial-video-wrapper video').src = tpro_addButton.href;
					testimonialForm.querySelector('.sp-testimonial-video-wrapper').style.display = 'block';

					tpro_modal_close.click();
				});
			};

			mediaRecorder.start(200);
		}

		tpro_startButton.addEventListener("click", () => {
			tpro_no_camera.style.display = 'none';
			tpro_preview.style.display = 'block';
			tpro_recording.style.display = 'none';
			tpro_startButton.style.display = 'none';
			tpro_stopButton.style.display = 'inline-block';
			onBtnRecordClicked();
		});

		tpro_stopButton.addEventListener("click", () => {
			tpro_addButton.style.display = 'inline-block';
			tpro_preview.style.display = 'none';
			tpro_recording.style.display = 'block';
			tpro_timer.style.display = 'none';
			tpro_stopButton.style.display = 'none';
			tpro_startButton.style.display = 'block';
			testimonialForm.querySelector('#tpro_startButton_text').innerText = 'Record again';
			clearInterval(videoTimerInterval);
			tpro_timer.innerText = '';
			mediaRecorder.stop();
			tpro_startButton.disabled = false;
			tpro_stopButton.disabled = true;
		}, false);
	}
	document.querySelectorAll('.sp-tpro-fronted-form').forEach(function (form) {
		let formID = form.getAttribute('id');
		let video_format = form.getAttribute('data-video_format');
		let testimonialFormId = form.getAttribute('data-form_id');
		if (form.querySelector('.testimonial-record_video')) {
			setTimeout(() => sp_testimonial_recorded_video(testimonialFormId, video_format), 500);
		}
	});

});
