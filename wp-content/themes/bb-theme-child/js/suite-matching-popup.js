(function () {
	'use strict';

	function getSuiteMatchingModal() {
		return document.getElementById('suite-matching-modal');
	}

	function openSuiteMatchingModal() {
		var modal = getSuiteMatchingModal();
		if (!modal) {
			return;
		}

		if (window.legacySignupSuite && typeof window.legacySignupSuite.closeModal === 'function') {
			window.legacySignupSuite.closeModal(document.getElementById('signup-a-suite-modal'), {
				restoreFocus: false
			});
		}

		document.body.classList.remove('loftloader-disable-scrolling', 'signup-a-suite-modal-open');
		modal.classList.add('is-open');
		modal.setAttribute('aria-hidden', 'false');
		modal.removeAttribute('inert');
	}

	function closeSuiteMatchingModal() {
		var modal = getSuiteMatchingModal();
		if (!modal) {
			return;
		}

		modal.classList.remove('is-open');
		modal.setAttribute('inert', '');

		window.requestAnimationFrame(function () {
			modal.setAttribute('aria-hidden', 'true');
		});
	}

	function initSuiteMatchingPopup() {
		var modal = getSuiteMatchingModal();
		if (!modal || modal.dataset.suiteMatchingInit === '1') {
			return;
		}
		modal.dataset.suiteMatchingInit = '1';

		modal.querySelectorAll('[data-suite-matching-modal-close]').forEach(function (closeEl) {
			closeEl.addEventListener('click', function (event) {
				event.preventDefault();
				closeSuiteMatchingModal();
			});
		});

		document.addEventListener('click', function (event) {
			var trigger = event.target.closest('#contact_form_legacy, a[href="#contact_form_legacy"], a[href="#suite-matching-modal"]');
			if (!trigger) {
				return;
			}
			event.preventDefault();
			openSuiteMatchingModal();
		});

		var professionSelect = modal.querySelector('#suite-popup-profession');
		var otherWrap = modal.querySelector('.suite-popup-profession-other-wrap');
		var otherInput = modal.querySelector('#suite-popup-profession-other');

		if (professionSelect && otherWrap) {
			professionSelect.addEventListener('change', function () {
				var isOther = professionSelect.value === 'Other';
				otherWrap.style.display = isOther ? 'block' : 'none';
				if (otherInput) {
					if (isOther) {
						otherInput.setAttribute('required', 'required');
					} else {
						otherInput.removeAttribute('required');
						otherInput.value = '';
					}
				}
			});
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initSuiteMatchingPopup);
	} else {
		initSuiteMatchingPopup();
	}

	window.legacySuiteMatchingPopup = {
		open: openSuiteMatchingModal,
		close: closeSuiteMatchingModal
	};
})();
