(function () {
	'use strict';

	var ajaxUrl = (typeof customAjax !== 'undefined' && customAjax.ajax_url)
		? customAjax.ajax_url
		: (typeof ajaxurl !== 'undefined' ? ajaxurl : '');

	var SLIDER_NAV_SELECTOR = [
		'.suiteSwiper .swiper-button-prev',
		'.suiteSwiper .swiper-button-next',
		'.suiteSwiper .swiper-pagination',
		'.suiteSwiper .swiper-pagination-bullet',
		'.suite-slider-prev',
		'.suite-slider-next',
		'.slider-prev',
		'.slider-next'
	].join(', ');

	var BOOK_TOUR_NODE_SELECTOR = [
		'.fl-node-ev642klcs5zy',
		'.fl-node-lfw0znmv3pht'
	].join(', ');

	var modalFormState = {
		locationLockedFromSuite: false
	};

	var modalFocusState = {
		returnFocusEl: null
	};

	function getDiscountNote(form) {
		var modal = form.closest('.signup-a-suite-modal');
		return modal ? modal.querySelector('[data-signup-discount-note]') : document.querySelector('[data-signup-discount-note]');
	}

	function getDiscountInput(form) {
		return form.querySelector('[data-signup-discount-input]');
	}

	function setPreferredSuite(form, suiteNumber, showField) {
		if (!form) {
			return;
		}

		var wrapper = form.querySelector('[data-preferred-suite-wrapper]');
		var input = form.querySelector('[name="preferred_suite"]');
		if (!wrapper || !input) {
			return;
		}

		var value = suiteNumber ? String(suiteNumber).trim() : '';
		if (showField && value) {
			wrapper.style.display = 'block';
			input.value = value;
		} else {
			wrapper.style.display = 'none';
			input.value = '';
		}
	}

	function setPreferredLocationLock(form, locked) {
		var locationSelect = form ? form.querySelector('[name="preferred_location"]') : null;
		if (!locationSelect) {
			return;
		}

		modalFormState.locationLockedFromSuite = !!locked;

		if (locked) {
			locationSelect.dataset.lockedFromSuite = '1';
			locationSelect.classList.add('is-location-locked');
			locationSelect.setAttribute('aria-disabled', 'true');
			locationSelect.tabIndex = -1;
			return;
		}

		delete locationSelect.dataset.lockedFromSuite;
		locationSelect.classList.remove('is-location-locked');
		locationSelect.removeAttribute('aria-disabled');
		locationSelect.tabIndex = 0;
	}

	function preventLockedLocationChange(event) {
		var select = event.currentTarget;
		if (select.dataset.lockedFromSuite === '1') {
			event.preventDefault();
			event.stopPropagation();
			if (typeof select.blur === 'function') {
				select.blur();
			}
		}
	}

	function setPreferredLocation(form, locationValue, lockFromSuite) {
		if (!form) {
			return;
		}

		var locationSelect = form.querySelector('[name="preferred_location"]');
		if (!locationSelect) {
			return;
		}

		var value = locationValue ? String(locationValue).trim() : '';
		var hasOption = false;
		Array.prototype.forEach.call(locationSelect.options, function (option) {
			if (option.value === value) {
				hasOption = true;
			}
		});

		if (value && hasOption) {
			locationSelect.value = value;
			setPreferredLocationLock(form, !!lockFromSuite);
			return;
		}

		setPreferredLocationLock(form, false);
		locationSelect.selectedIndex = 0;
	}

	function setFormDiscount(form, percentage) {
		var value = percentage ? String(percentage).replace(/[^\d]/g, '') : '';
		var input = getDiscountInput(form);
		if (input) {
			input.value = value;
		}

		var note = getDiscountNote(form);
		if (!note) {
			return;
		}

		if (value) {
			note.textContent = 'You are claiming ' + value + '% off your first month with this offer.';
			note.hidden = false;
		} else {
			note.textContent = '';
			note.hidden = true;
		}
	}

	function getProfessionServiceInputs(form) {
		if (!form) {
			return [];
		}
		return Array.prototype.slice.call(form.querySelectorAll('input[name="profession_services[]"]'));
	}

	function hasProfessionServiceSelection(form) {
		return getProfessionServiceInputs(form).some(function (input) {
			return input.checked;
		});
	}

	function clearProfessionServicesError(form) {
		if (!form) {
			return;
		}
		var field = form.querySelector('[data-profession-services-field]');
		if (field) {
			field.classList.remove('is-invalid');
		}
		var trigger = form.querySelector('.signup-a-suite-multi-select__trigger');
		if (trigger) {
			trigger.style.removeProperty('border');
		}
		var errorEl = form.querySelector('[data-profession-services-error]');
		if (errorEl) {
			errorEl.textContent = '';
			errorEl.hidden = true;
		}
	}

	function showProfessionServicesError(form, message) {
		if (!form) {
			return;
		}
		var field = form.querySelector('[data-profession-services-field]');
		if (field) {
			field.classList.add('is-invalid');
		}
		var trigger = form.querySelector('.signup-a-suite-multi-select__trigger');
		if (trigger) {
			trigger.style.setProperty('border', '1px solid red', 'important');
		}
		var errorEl = form.querySelector('[data-profession-services-error]');
		if (errorEl) {
			errorEl.textContent = message;
			errorEl.style.color = 'red';
			errorEl.style.fontSize = '13px';
			errorEl.hidden = false;
		}
	}

	function getProfessionMultiSelect(form) {
		return form ? form.querySelector('[data-profession-multi-select]') : null;
	}

	function syncProfessionOptionStates(form) {
		getProfessionServiceInputs(form).forEach(function (input) {
			var option = input.closest('.signup-a-suite-multi-select__option');
			if (option) {
				option.classList.toggle('is-selected', input.checked);
			}
		});
	}

	function updateProfessionServicesTrigger(form) {
		var multiSelect = getProfessionMultiSelect(form);
		if (!multiSelect) {
			return;
		}

		var valueEl = multiSelect.querySelector('[data-profession-services-value]');
		if (!valueEl) {
			return;
		}

		var placeholder = multiSelect.getAttribute('data-placeholder') || 'Select profession/services...';
		var selectedLabels = getProfessionServiceInputs(form)
			.filter(function (input) {
				return input.checked;
			})
			.map(function (input) {
				return input.getAttribute('data-service-label') || input.value;
			});

		if (!selectedLabels.length) {
			valueEl.textContent = placeholder;
			valueEl.classList.add('is-placeholder');
			multiSelect.classList.remove('has-value');
			return;
		}

		var displayText = selectedLabels[0];

		if (selectedLabels.length === 2) {
			displayText = selectedLabels.join(', ');
		} else if (selectedLabels.length > 2) {
			displayText = selectedLabels[0] + ', ' + selectedLabels[1] + ' +' + (selectedLabels.length - 2) + ' more';
		}

		valueEl.textContent = displayText;
		valueEl.setAttribute('title', selectedLabels.join(', '));
		valueEl.classList.remove('is-placeholder');
		multiSelect.classList.add('has-value');
	}

	function onProfessionServiceChange(form) {
		syncProfessionOptionStates(form);
		updateProfessionServicesTrigger(form);

		if (hasProfessionServiceSelection(form)) {
			clearProfessionServicesError(form);
		}
	}

	function setProfessionServicesPanelOpen(form, isOpen) {
		var multiSelect = getProfessionMultiSelect(form);
		if (!multiSelect) {
			return;
		}

		var trigger = multiSelect.querySelector('.signup-a-suite-multi-select__trigger');
		var panel = multiSelect.querySelector('.signup-a-suite-multi-select__panel');
		var fieldWrap = form.querySelector('[data-profession-services-field]');
		var modal = form.closest('.signup-a-suite-modal');

		if (!trigger || !panel) {
			return;
		}

		multiSelect.classList.toggle('is-open', isOpen);
		trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
		panel.hidden = !isOpen;

		if (fieldWrap) {
			fieldWrap.classList.toggle('is-picker-open', isOpen);
		}

		if (modal) {
			modal.classList.toggle('signup-a-suite-modal--services-picker-open', isOpen);
		}

		if (isOpen) {
			if (typeof trigger.blur === 'function') {
				trigger.blur();
			}

			window.requestAnimationFrame(function () {
				if (fieldWrap && typeof fieldWrap.scrollIntoView === 'function') {
					try {
						fieldWrap.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
					} catch (err) {
						fieldWrap.scrollIntoView(false);
					}
				}
			});
		}
	}

	function bindProfessionServicesField(form) {
		if (!form || form.dataset.professionServicesBound === '1') {
			return;
		}
		form.dataset.professionServicesBound = '1';

		var multiSelect = getProfessionMultiSelect(form);
		if (!multiSelect) {
			return;
		}

		var trigger = multiSelect.querySelector('.signup-a-suite-multi-select__trigger');
		var doneButton = multiSelect.querySelector('[data-profession-services-done]');
		var panel = multiSelect.querySelector('.signup-a-suite-multi-select__panel');
		var options = multiSelect.querySelectorAll('.signup-a-suite-multi-select__option');

		if (trigger) {
			trigger.addEventListener('click', function (event) {
				event.preventDefault();
				event.stopPropagation();
				setProfessionServicesPanelOpen(form, !multiSelect.classList.contains('is-open'));
			});
		}

		if (doneButton) {
			doneButton.addEventListener('click', function (event) {
				event.preventDefault();
				event.stopPropagation();
				setProfessionServicesPanelOpen(form, false);
			});
		}

		if (panel) {
			panel.addEventListener('click', function (event) {
				event.stopPropagation();
			});
		}

		options.forEach(function (option) {
			option.addEventListener('click', function (event) {
				event.stopPropagation();
			});
		});

		getProfessionServiceInputs(form).forEach(function (input) {
			input.addEventListener('change', function () {
				onProfessionServiceChange(form);
			});

			input.addEventListener('click', function (event) {
				event.stopPropagation();
			});
		});

		syncProfessionOptionStates(form);

		document.addEventListener('click', function (event) {
			if (!multiSelect.classList.contains('is-open')) {
				return;
			}

			if (multiSelect.contains(event.target)) {
				return;
			}

			setProfessionServicesPanelOpen(form, false);
		});

		document.addEventListener('keydown', function (event) {
			if (event.key === 'Escape' && multiSelect.classList.contains('is-open')) {
				setProfessionServicesPanelOpen(form, false);
			}
		});

		updateProfessionServicesTrigger(form);
	}

	function resetSelects(form) {
		getProfessionServiceInputs(form).forEach(function (input) {
			input.checked = false;
		});
		syncProfessionOptionStates(form);
		updateProfessionServicesTrigger(form);
		setProfessionServicesPanelOpen(form, false);
		clearProfessionServicesError(form);
		var locationSelect = form.querySelector('[name="preferred_location"]');
		if (locationSelect) {
			locationSelect.selectedIndex = 0;
			setPreferredLocationLock(form, false);
		}
	}

	function resetSignupForm(form) {
		if (!form) {
			return;
		}

		form.reset();
		resetSelects(form);
		setFormDiscount(form, '');
		setPreferredSuite(form, '', false);
		setPreferredLocationLock(form, false);

		var phoneInput = form.querySelector('[name="phone"]');
		if (phoneInput) {
			clearPhoneError(phoneInput);
		}

		var feedback = form.querySelector('.signup-a-suite-feedback');
		if (feedback) {
			feedback.textContent = '';
			feedback.className = 'signup-a-suite-feedback';
		}

		var submitBtn = form.querySelector('.signup-a-suite-submit');
		if (submitBtn) {
			submitBtn.disabled = false;
		}

		form.querySelectorAll('.is-invalid').forEach(function (field) {
			field.classList.remove('is-invalid');
			if (typeof field.setCustomValidity === 'function') {
				field.setCustomValidity('');
			}
		});

		clearAllSignupFieldErrors(form);
	}

	function resetModalFormState() {
		modalFormState.locationLockedFromSuite = false;
		var modal = document.getElementById('signup-a-suite-modal');
		if (!modal) {
			return;
		}
		var form = modal.querySelector('.signup-a-suite-form');
		if (form) {
			setPreferredLocationLock(form, false);
		}
	}

	function bindModalLocationLock(form) {
		if (!form || !form.closest('.signup-a-suite-modal')) {
			return;
		}

		var locationSelect = form.querySelector('[name="preferred_location"]');
		if (!locationSelect || locationSelect.dataset.locationLockBound === '1') {
			return;
		}
		locationSelect.dataset.locationLockBound = '1';

		['mousedown', 'keydown', 'focus'].forEach(function (eventName) {
			locationSelect.addEventListener(eventName, preventLockedLocationChange);
		});
	}

	function phoneDigits(value) {
		return String(value || '').replace(/\D/g, '').slice(0, 10);
	}

	/* ------------------------------------------------------------------
	 * Inline field error helpers
	 * ---------------------------------------------------------------- */
	function showFieldInlineError(field, message) {
		var wrap = field.closest('.signup-a-suite-field');
		if (!wrap) { return; }
		field.style.setProperty('border', '1px solid red', 'important');
		var errEl = wrap.querySelector('.las-field-inline-error');
		if (!errEl) {
			errEl = document.createElement('p');
			errEl.className = 'las-field-inline-error';
			wrap.appendChild(errEl);
		}
		errEl.style.cssText = 'color:red;font-size:13px;margin:4px 0 0;display:block;';
		errEl.textContent = message;
	}

	function clearFieldInlineError(field) {
		if (!field) { return; }
		field.style.removeProperty('border');
		var wrap = field.closest ? field.closest('.signup-a-suite-field') : null;
		if (!wrap) { return; }
		var errEl = wrap.querySelector('.las-field-inline-error');
		if (errEl) {
			errEl.textContent = '';
			errEl.style.display = 'none';
		}
	}

	function clearAllSignupFieldErrors(form) {
		if (!form) { return; }
		form.querySelectorAll('.las-field-inline-error').forEach(function(el) {
			el.textContent = '';
			el.style.display = 'none';
		});
		form.querySelectorAll('input, select').forEach(function(el) {
			el.style.removeProperty('border');
		});
	}

	/* ------------------------------------------------------------------
	 * Full inline field validation — returns true if all valid.
	 * All required non-textarea fields validated; CAPTCHA NOT checked here.
	 * ---------------------------------------------------------------- */
	function validateSignupFields(form) {
		if (!form) { return false; }
		var valid = true;

		clearAllSignupFieldErrors(form);
		clearProfessionServicesError(form);

		// Required text / email / select fields (NOT tel, NOT hidden, NOT textarea)
		var requiredInputs = form.querySelectorAll(
			'input[required]:not([type="hidden"]):not([type="tel"]):not([type="checkbox"]):not([type="radio"]), select[required]'
		);
		requiredInputs.forEach(function(input) {
			var value = input.tagName === 'SELECT' ? input.value : input.value.trim();
			if (!value) {
				var labelEl = input.closest('.signup-a-suite-field') &&
					input.closest('.signup-a-suite-field').querySelector('label');
				var fieldName = labelEl ? labelEl.textContent.trim() : 'This field';
				showFieldInlineError(input, fieldName + ' is required.');
				valid = false;
			} else if (input.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
				showFieldInlineError(input, 'Please enter a valid email address.');
				valid = false;
			}
		});

		// Phone — has its own rich validator
		var phoneInput = form.querySelector('[name="phone"]');
		if (phoneInput && !validatePhoneInput(phoneInput, true)) {
			valid = false;
		}

		// Profession / services multi-select
		if (getProfessionServiceInputs(form).length && !hasProfessionServiceSelection(form)) {
			showProfessionServicesError(form, 'Please select at least one profession/service.');
			valid = false;
		}

		return valid;
	}

	function formatUSPhone(digits) {
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

	function getPhoneFieldWrap(input) {
		return input ? input.closest('.signup-a-suite-field') : null;
	}

	function clearPhoneError(input) {
		if (!input) {
			return;
		}
		input.classList.remove('is-invalid');
		input.style.removeProperty('border');
		input.setCustomValidity('');
		var wrap = getPhoneFieldWrap(input);
		if (!wrap) {
			return;
		}
		var errorEl = wrap.querySelector('.phone-error-msg');
		if (errorEl) {
			errorEl.textContent = '';
			errorEl.hidden = true;
		}
	}

	function showPhoneError(input, message) {
		if (!input) {
			return;
		}
		input.classList.add('is-invalid');
		input.style.setProperty('border', '1px solid red', 'important');
		input.setCustomValidity(message);
		var wrap = getPhoneFieldWrap(input);
		if (!wrap) {
			return;
		}
		var errorEl = wrap.querySelector('.phone-error-msg');
		if (!errorEl) {
			errorEl = document.createElement('span');
			errorEl.className = 'signup-a-suite-feedback is-error phone-error-msg';
			errorEl.setAttribute('role', 'alert');
			wrap.appendChild(errorEl);
		}
		errorEl.textContent = message;
		errorEl.style.color = 'red';
		errorEl.style.fontSize = '13px';
		errorEl.hidden = false;
	}

	function validatePhoneInput(input, showErrors) {
		if (!input) {
			return true;
		}

		var digits = phoneDigits(input.value);
		if (!digits.length) {
			if (showErrors) {
				showPhoneError(input, 'Please enter your phone number');
			} else {
				clearPhoneError(input);
			}
			return false;
		}

		if (digits.length !== 10) {
			if (showErrors) {
				showPhoneError(input, 'Enter valid 10 digit phone');
			} else {
				input.setCustomValidity('Enter valid 10 digit phone');
			}
			return false;
		}

		clearPhoneError(input);
		return true;
	}

	function bindPhoneField(input) {
		if (!input || input.dataset.phoneBound === '1') {
			return;
		}
		input.dataset.phoneBound = '1';
		input.setAttribute('inputmode', 'numeric');
		input.setAttribute('autocomplete', 'tel-national');
		input.setAttribute('maxlength', '14');

		input.addEventListener('beforeinput', function (event) {
			if (event.data && /\D/.test(event.data)) {
				event.preventDefault();
			}
		});

		input.addEventListener('keydown', function (event) {
			var allowedKeys = [
				'Backspace', 'Delete', 'Tab', 'Escape', 'Enter',
				'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Home', 'End'
			];
			if (allowedKeys.indexOf(event.key) !== -1 || event.ctrlKey || event.metaKey) {
				return;
			}
			if (!/^\d$/.test(event.key)) {
				event.preventDefault();
			}
		});

		input.addEventListener('input', function () {
			var digits = phoneDigits(input.value);
			var formatted = formatUSPhone(digits);
			if (input.value !== formatted) {
				input.value = formatted;
			}
			if (digits.length) {
				validatePhoneInput(input, false);
			} else {
				clearPhoneError(input);
			}
		});

		input.addEventListener('blur', function () {
			validatePhoneInput(input, true);
		});

		input.addEventListener('paste', function (event) {
			event.preventDefault();
			var pasted = '';
			if (event.clipboardData) {
				pasted = event.clipboardData.getData('text');
			}
			var digits = phoneDigits(pasted);
			input.value = formatUSPhone(digits);
			validatePhoneInput(input);
		});
	}

	function bindSignupForm(form) {
		if (!form || form.dataset.signupBound === '1') {
			return;
		}
		form.dataset.signupBound = '1';

		var feedback = form.querySelector('.signup-a-suite-feedback');
		var submitBtn = form.querySelector('.signup-a-suite-submit');
		var phoneInput = form.querySelector('[name="phone"]');

		bindPhoneField(phoneInput);
		bindModalLocationLock(form);
		bindProfessionServicesField(form);

		// Clear inline errors as user corrects fields
		form.addEventListener('input', function(event) {
			var t = event.target;
			if (t && t.tagName !== 'TEXTAREA') { clearFieldInlineError(t); }
		});
		form.addEventListener('change', function(event) {
			var t = event.target;
			if (t) { clearFieldInlineError(t); }
		});

		form.addEventListener('submit', function (event) {
			event.preventDefault();

			// Step 1: Validate all required fields inline first
			if (!validateSignupFields(form)) {
				return; // Field errors shown — do NOT check CAPTCHA yet
			}

			// Step 2: All fields valid — now check reCAPTCHA
			if (window.lasRecaptcha && form.querySelector('.las-modal-recaptcha-wrap')) {
				var recaptchaToken = window.lasRecaptcha.getToken(form);
				if (!recaptchaToken) {
					window.lasRecaptcha.showError(form, window.lasRecaptcha.errorMsg);
					return;
				}
				window.lasRecaptcha.clearError(form);
				window.lasRecaptcha.injectFormToken(form, recaptchaToken);
			}

			if (!ajaxUrl) {
				return;
			}

			submitBtn.disabled = true;
			if (feedback) {
				feedback.textContent = '';
				feedback.className = 'signup-a-suite-feedback';
			}

			var formData = new FormData(form);
			if (phoneInput) {
				formData.set('phone', formatUSPhone(phoneDigits(phoneInput.value)));
			}

			fetch(ajaxUrl, {
				method: 'POST',
				body: formData,
				credentials: 'same-origin'
			})
				.then(function (response) {
					return response.text().then(function (text) {
						if (!text) {
							throw new Error('Empty response');
						}
						try {
							return JSON.parse(text);
						} catch (err) {
							throw new Error('Invalid server response');
						}
					});
				})
				.then(function (result) {
					if (result && result.success) {
						var successMessage = (result.data && result.data.message)
							? result.data.message
							: 'Thank you! We will get back to you soon.';

						if (typeof Swal !== 'undefined') {
							Swal.fire({
								icon: 'success',
								title: 'Message sent',
								text: successMessage,
								confirmButtonColor: '#ff5792'
							});
						} else if (feedback) {
							feedback.textContent = successMessage;
							feedback.classList.add('is-success');
						}

						var modal = form.closest('.signup-a-suite-modal');
						if (modal) {
							closeSignupModal(modal);
						} else {
							resetSignupForm(form);
						}
						return;
					}

					var errorMessage = (result && result.data && result.data.message)
						? result.data.message
						: 'Something went wrong. Please try again.';

					if (typeof Swal !== 'undefined') {
						Swal.fire({
							icon: 'error',
							title: 'Unable to send',
							text: errorMessage,
							confirmButtonColor: '#ff5792'
						});
					} else if (feedback) {
						feedback.textContent = errorMessage;
						feedback.classList.add('is-error');
					}
				})
				.catch(function () {
					var fallback = 'Submission failed. Please try again.';
					if (typeof Swal !== 'undefined') {
						Swal.fire({
							icon: 'error',
							title: 'Unable to send',
							text: fallback,
							confirmButtonColor: '#ff5792'
						});
					} else if (feedback) {
						feedback.textContent = fallback;
						feedback.classList.add('is-error');
					}
				})
				.finally(function () {
					submitBtn.disabled = false;
				});
		});
	}

	function openSignupModal(discountPercentage, options) {
		var modal = document.getElementById('signup-a-suite-modal');
		if (!modal) {
			return;
		}

		options = options || {};
		var suiteNumber = options.suiteNumber ? String(options.suiteNumber) : '';
		var preferredLocation = options.preferredLocation ? String(options.preferredLocation) : '';
		var fromFeaturedSuite = !!options.fromFeaturedSuite;

		var form = modal.querySelector('.signup-a-suite-form');
		if (form) {
			setFormDiscount(form, discountPercentage);
			setPreferredSuite(form, suiteNumber, fromFeaturedSuite);
			setPreferredLocation(
				form,
				preferredLocation,
				fromFeaturedSuite && !!preferredLocation
			);
		}

		modalFocusState.returnFocusEl = document.activeElement;
		modal.removeAttribute('inert');
		modal.classList.add('is-open');
		modal.setAttribute('aria-hidden', 'false');
		document.body.classList.add('signup-a-suite-modal-open');

		var firstField = modal.querySelector('input:not([type="hidden"]), select, textarea');
		if (firstField && window.matchMedia('(min-width: 992px)').matches) {
			window.setTimeout(function () {
				if (typeof firstField.focus === 'function') {
					try {
						firstField.focus({ preventScroll: true });
					} catch (err) {
						firstField.focus();
					}
				}
			}, 100);
		}
	}

	function releasePageScrollLocks() {
		document.body.classList.remove('signup-a-suite-modal-open', 'loftloader-disable-scrolling');
		document.body.style.removeProperty('overflow');
		document.body.style.removeProperty('position');
		document.body.style.removeProperty('width');
		document.body.style.removeProperty('height');
		document.body.style.removeProperty('max-height');
	}

	function isFocusableElement(element) {
		if (!element || typeof element.focus !== 'function' || !document.contains(element)) {
			return false;
		}

		if (element.disabled) {
			return false;
		}

		var tag = element.tagName ? element.tagName.toLowerCase() : '';
		if (tag === 'input' || tag === 'select' || tag === 'textarea' || tag === 'button' || tag === 'a') {
			return true;
		}

		return element.tabIndex >= 0;
	}

	function moveFocusOutOfModal(modal, preferredTarget) {
		if (!modal) {
			return;
		}

		var active = document.activeElement;
		if (!active || !modal.contains(active)) {
			return;
		}

		var target = preferredTarget;
		if (!isFocusableElement(target) || modal.contains(target)) {
			target = document.querySelector('.btn-matching');
		}
		if (!isFocusableElement(target) || modal.contains(target)) {
			target = modalFocusState.returnFocusEl;
		}
		if (!isFocusableElement(target) || modal.contains(target)) {
			target = null;
		}

		if (target) {
			try {
				target.focus({ preventScroll: true });
			} catch (err) {
				target.focus();
			}
			return;
		}

		if (typeof active.blur === 'function') {
			active.blur();
		}
	}

	function setModalAriaHidden(modal, hidden) {
		if (!modal) {
			return;
		}

		modal.setAttribute('aria-hidden', hidden ? 'true' : 'false');
	}

	function closeSignupModal(modal, options) {
		if (!modal) {
			modal = document.getElementById('signup-a-suite-modal');
		}
		if (!modal) {
			return;
		}

		options = options || {};
		var focusTarget = options.focusTarget || null;
		var restoreFocus = options.restoreFocus !== false;

		moveFocusOutOfModal(modal, focusTarget);

		modal.classList.remove('is-open');
		modal.setAttribute('inert', '');
		releasePageScrollLocks();

		window.requestAnimationFrame(function () {
			window.requestAnimationFrame(function () {
				setModalAriaHidden(modal, true);
			});
		});

		modal.querySelectorAll('.signup-a-suite-form').forEach(function (form) {
			resetSignupForm(form);
		});

		resetModalFormState();

		if (!restoreFocus || focusTarget) {
			modalFocusState.returnFocusEl = null;
			return;
		}

		var returnFocus = modalFocusState.returnFocusEl;
		modalFocusState.returnFocusEl = null;
		if (returnFocus && isFocusableElement(returnFocus) && !modal.contains(returnFocus)) {
			window.setTimeout(function () {
				try {
					returnFocus.focus({ preventScroll: true });
				} catch (err) {
					returnFocus.focus();
				}
			}, 0);
		}
	}

	function handleModalCloseClick(event, modal) {
		if (event) {
			event.preventDefault();
			event.stopPropagation();
		}

		var focusTarget = document.querySelector('.btn-matching');
		closeSignupModal(modal, { focusTarget: focusTarget });
	}

	function initModalControls() {
		var modal = document.getElementById('signup-a-suite-modal');
		if (!modal) {
			return;
		}

		var closeButtons = modal.querySelectorAll('[data-signup-modal-close]');
		closeButtons.forEach(function (closeEl) {
			closeEl.addEventListener('click', function (event) {
				handleModalCloseClick(event, modal);
			});
		});

		modal.addEventListener('click', function (event) {
			if (event.target === modal) {
				handleModalCloseClick(event, modal);
			}
		});

		document.addEventListener('keydown', function (event) {
			if (event.key === 'Escape' && modal.classList.contains('is-open')) {
				closeSignupModal(modal, {
					focusTarget: document.querySelector('.btn-matching')
				});
			}
		});
	}

	function getTopbarDiscountPercentage() {
		var topbar = document.getElementById('topbar');
		if (topbar) {
			var topbarDiscount = topbar.getAttribute('data-percentage');
			if (topbarDiscount) {
				return topbarDiscount;
			}
		}

		var headerNotice = document.querySelector('header .notice[data-percentage]');
		return headerNotice ? (headerNotice.getAttribute('data-percentage') || '') : '';
	}

	/**
	 * Run callback after LoftLoader Pro reaches 100% and finishes its exit animation.
	 * Falls back immediately when the loader is not present on the page.
	 *
	 * @param {Function} callback
	 */
	function runAfterLoftLoaderReady(callback) {
		if (typeof callback !== 'function') {
			return;
		}

		var executed = false;
		function runOnce() {
			if (executed) {
				return;
			}
			executed = true;
			callback();
		}

		var loader = document.getElementById('loftloader-wrapper');
		if (!loader) {
			runOnce();
			return;
		}

		if (document.body.classList.contains('loftloader-loaded')) {
			window.setTimeout(runOnce, 200);
			return;
		}

		if (typeof jQuery !== 'undefined') {
			jQuery(document).one('loftloaderprodone', runOnce);
		} else {
			var observer = new MutationObserver(function () {
				if (!document.body.classList.contains('loftloader-loaded')) {
					return;
				}
				observer.disconnect();
				window.setTimeout(runOnce, 1200);
			});
			observer.observe(document.body, { attributes: true, attributeFilter: ['class'] });
		}

		window.setTimeout(runOnce, 45000);
	}

	function initHomepageAutoOpen() {
		var config = window.legacySignupSuiteConfig || {};
		if (!config.autoOpenOnHome) {
			return;
		}

		var modal = document.getElementById('signup-a-suite-modal');
		if (!modal) {
			return;
		}

		runAfterLoftLoaderReady(function () {
			if (modal.classList.contains('is-open')) {
				return;
			}
			openSignupModal('');
		});
	}

	function initTopbarTrigger() {
		var topbar = document.getElementById('topbar');
		if (!topbar) {
			return;
		}

		topbar.classList.add('signup-topbar-clickable');

		topbar.addEventListener('click', function (event) {
			if (event.target.closest('.notice_close')) {
				return;
			}

			var modal = document.getElementById('signup-a-suite-modal');
			if (modal && modal.classList.contains('is-open')) {
				return;
			}

			openSignupModal(getTopbarDiscountPercentage());
		});
	}

	function getSuiteCard(el) {
		return el ? el.closest('.service-card') : null;
	}

	function readTriggerOptions(trigger) {
		var card = getSuiteCard(trigger);
		var suiteNumber = trigger.getAttribute('data-suite-no')
			|| (card ? card.getAttribute('data-suite-no') : '')
			|| '';
		var preferredLocation = trigger.getAttribute('data-suite-location')
			|| (card ? card.getAttribute('data-suite-location') : '')
			|| '';
		var discount = trigger.getAttribute('data-signup-discount') || '';
		var fromFeaturedSuite = trigger.classList.contains('featured-suite-trigger');

		return {
			suiteNumber: suiteNumber,
			preferredLocation: preferredLocation,
			discount: discount,
			fromFeaturedSuite: fromFeaturedSuite
		};
	}

	function openSignupFromTrigger(trigger) {
		var opts = readTriggerOptions(trigger);
		openSignupModal(opts.discount, {
			suiteNumber: opts.suiteNumber,
			preferredLocation: opts.preferredLocation,
			fromFeaturedSuite: opts.fromFeaturedSuite
		});
	}

	function isSliderNavClick(target) {
		return !!(target && target.closest(SLIDER_NAV_SELECTOR));
	}

	function isPlayButtonClick(target) {
		return !!(target && target.closest('.js-suite-video-play, .play-button'));
	}

	/**
	 * Returns the element that carries suite trigger data, or null.
	 */
	function getSignupModalTrigger(target) {
		if (!target) {
			return null;
		}

		if (isSliderNavClick(target)) {
			return null;
		}

		if (isPlayButtonClick(target)) {
			return null;
		}

		var availableTag = target.closest('.service-card .available-tag');
		if (availableTag) {
			return availableTag;
		}

		var learnMore = target.closest('.service-card .learn-more');
		if (learnMore) {
			return learnMore;
		}

		var imageArea = target.closest('.service-card .suite-image-area.featured-suite-trigger');
		if (imageArea) {
			return imageArea;
		}

		return null;
	}

	function playSuiteVideo(playButton) {
		var videoCard = playButton.closest('.video-card');
		if (!videoCard) {
			return;
		}

		var videoUrl = videoCard.getAttribute('data-video');
		if (!videoUrl) {
			return;
		}

		var videoModal = document.getElementById('videoModal');
		var modalVideo = document.getElementById('modalVideo');
		if (!videoModal || !modalVideo) {
			return;
		}

		var source = modalVideo.querySelector('source');
		if (source) {
			source.setAttribute('src', videoUrl);
		} else {
			modalVideo.setAttribute('src', videoUrl);
		}

		modalVideo.load();

		if (typeof window.jQuery !== 'undefined') {
			window.jQuery(videoModal).fadeIn();
		} else {
			videoModal.style.display = 'block';
		}
	}

	function bindSuiteVideoPlayButtons(root) {
		var scope = root || document;
		scope.querySelectorAll('.js-suite-video-play').forEach(function (playButton) {
			if (playButton.dataset.videoPlayBound === '1') {
				return;
			}
			playButton.dataset.videoPlayBound = '1';

			playButton.addEventListener('click', function (event) {
				event.preventDefault();
				event.stopPropagation();
				playSuiteVideo(playButton);
			});
		});
	}

	function isBookTourTrigger(target) {
		if (!target) {
			return null;
		}

		var tourNode = target.closest(BOOK_TOUR_NODE_SELECTOR);
		if (tourNode) {
			return target.closest('a, button, .fl-button') || tourNode;
		}

		var clickable = target.closest('a, button, .fl-button');
		if (clickable && /book\s+a\s+tour/i.test((clickable.textContent || '').trim())) {
			return clickable;
		}

		return null;
	}

	function initBookTourTriggers() {
		document.addEventListener('click', function (event) {
			var trigger = isBookTourTrigger(event.target);
			if (!trigger) {
				return;
			}

			event.preventDefault();
			openSignupModal('');
		});
	}

	function initSuiteCardInteractions() {
		bindSuiteVideoPlayButtons(document);

		document.addEventListener('click', function (event) {
			if (isSliderNavClick(event.target)) {
				return;
			}

			var trigger = getSignupModalTrigger(event.target);
			if (!trigger) {
				return;
			}

			event.preventDefault();
			openSignupFromTrigger(trigger);
		});
	}

	function initSignupSuite() {
		document.querySelectorAll('.signup-a-suite-form').forEach(bindSignupForm);
		document.querySelectorAll('.signup-a-suite-form [name="phone"]').forEach(bindPhoneField);
		initModalControls();
		initTopbarTrigger();
		initHomepageAutoOpen();
		initBookTourTriggers();
		initSuiteCardInteractions();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initSignupSuite);
	} else {
		initSignupSuite();
	}

	window.legacySignupSuite = {
		openModal: openSignupModal,
		closeModal: closeSignupModal,
		resetForm: resetSignupForm,
		bindForm: bindSignupForm,
		init: initSignupSuite,
		formatUSPhone: formatUSPhone,
		phoneDigits: phoneDigits,
		validateFields: validateSignupFields
	};
})();
