(function () {
	'use strict';

	var ajaxUrl = (typeof customAjax !== 'undefined' && customAjax.ajax_url)
		? customAjax.ajax_url
		: (typeof ajaxurl !== 'undefined' ? ajaxurl : '');

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

	function resetSelects(form) {
		var reasonSelect = form.querySelector('[name="reason"]');
		if (reasonSelect) {
			reasonSelect.selectedIndex = 0;
		}
		var locationSelect = form.querySelector('[name="preferred_location"]');
		if (locationSelect) {
			locationSelect.selectedIndex = 0;
		}
	}

	function bindSignupForm(form) {
		if (!form || form.dataset.signupBound === '1') {
			return;
		}
		form.dataset.signupBound = '1';

		var feedback = form.querySelector('.signup-a-suite-feedback');
		var submitBtn = form.querySelector('.signup-a-suite-submit');

		form.addEventListener('submit', function (event) {
			event.preventDefault();

			if (!form.checkValidity()) {
				form.reportValidity();
				return;
			}

			if (!ajaxUrl) {
				return;
			}

			submitBtn.disabled = true;
			if (feedback) {
				feedback.textContent = '';
				feedback.className = 'signup-a-suite-feedback';
			}

			fetch(ajaxUrl, {
				method: 'POST',
				body: new FormData(form),
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

						form.reset();
						resetSelects(form);
						setFormDiscount(form, '');
						setPreferredSuite(form, '', false);

						var modal = form.closest('.signup-a-suite-modal');
						if (modal) {
							closeSignupModal(modal);
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
		var fromFeaturedSuite = !!options.fromFeaturedSuite;
		
		var form = modal.querySelector('.signup-a-suite-form');
		if (form) {
			setFormDiscount(form, discountPercentage);
			setPreferredSuite(form, suiteNumber, fromFeaturedSuite);
		}

		modal.classList.add('is-open');
		modal.setAttribute('aria-hidden', 'false');
		document.body.classList.add('signup-a-suite-modal-open');

		var firstField = modal.querySelector('input:not([type="hidden"]), select, textarea');
		if (firstField) {
			window.setTimeout(function () {
				firstField.focus();
			}, 100);
		}
	}

	function closeSignupModal(modal) {
		if (!modal) {
			modal = document.getElementById('signup-a-suite-modal');
		}
		if (!modal) {
			return;
		}

		modal.classList.remove('is-open');
		modal.setAttribute('aria-hidden', 'true');
		document.body.classList.remove('signup-a-suite-modal-open');
	}
	
	function handleModalCloseClick(event, modal) {
		event.preventDefault();
		event.stopPropagation();
		closeSignupModal(modal);
	}
	
	function handleModalCloseClick(event, modal) {
		event.preventDefault();
		event.stopPropagation();
		closeSignupModal(modal);
	}

	function initModalControls() {
		var modal = document.getElementById('signup-a-suite-modal');
		if (!modal) {
			return;
		}

		var closeBtn = modal.querySelector('.signup-a-suite-modal__close');
		if (closeBtn) {
			closeBtn.addEventListener('click', function (event) {
				handleModalCloseClick(event, modal);
			});
		}

		var backdrop = modal.querySelector('.signup-a-suite-modal__backdrop');
		if (backdrop) {
			backdrop.addEventListener('click', function (event) {
				handleModalCloseClick(event, modal);
			});
		}

		modal.addEventListener('click', function (event) {
			if (event.target === modal) {
				handleModalCloseClick(event, modal);
			}
		});

		document.addEventListener('keydown', function (event) {
			if (event.key === 'Escape' && modal.classList.contains('is-open')) {
				closeSignupModal(modal);
			}
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

			var discount = topbar.getAttribute('data-percentage') || '';
			openSignupModal(discount);
		});
	}
	
	function initFeaturedSuitePopupTrigger() {
		document.addEventListener('click', function (event) {
			var trigger = event.target.closest('.js-open-signup-a-suite-modal');
			if (!trigger) {
				return;
			}

			event.preventDefault();

			var suiteNo = trigger.getAttribute('data-suite-no') || '';
			var fromFeaturedSuite = trigger.classList.contains('featured-suite-trigger');
			var discount = trigger.getAttribute('data-signup-discount') || '';

			openSignupModal(discount, {
				suiteNumber: suiteNo,
				fromFeaturedSuite: fromFeaturedSuite
			});
		});
	}

	function initSignupSuite() {
		document.querySelectorAll('.signup-a-suite-form').forEach(bindSignupForm);
		initModalControls();
		initTopbarTrigger();
		initFeaturedSuitePopupTrigger();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initSignupSuite);
	} else {
		initSignupSuite();
	}

	window.legacySignupSuite = {
		openModal: openSignupModal,
		closeModal: closeSignupModal,
		bindForm: bindSignupForm,
		init: initSignupSuite
	};
})();
