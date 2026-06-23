/**
 * modal-recaptcha.js
 *
 * Adds reCAPTCHA v2 widgets to:
 *   - .signup-a-suite-form  (signup-a-suite-modal + static page form)
 *   - #personalInfoModal
 *   - #suite-matching-personal-modal  (if present on the page)
 *
 * How it intercepts without touching existing JS files:
 *
 *   signup-a-suite forms:
 *     A document-level CAPTURE-phase 'submit' listener fires before
 *     signup-a-suite.js's form-level listener.  If reCAPTCHA is empty we
 *     prevent default + stopImmediatePropagation so signup-a-suite.js never
 *     runs.  If valid, we write the token to a hidden input so signup-a-suite.js's
 *     FormData picks it up automatically.
 *
 *   personalInfoModal / suite-matching-personal-modal:
 *     A document-level CAPTURE-phase 'click' listener intercepts .submit-btn
 *     before jQuery's delegated bubble-phase listener in custom.js.  If
 *     reCAPTCHA is empty we stop the click.  If valid, we stash the token
 *     in a module variable and inject it into the $.ajax call via ajaxSend.
 */
(function ($) {
	'use strict';

	/* -----------------------------------------------------------------
	 * Guard: config object must be present (set by wp_localize_script)
	 * --------------------------------------------------------------- */
	if ( typeof lasModalRecaptcha === 'undefined' ) { return; }

	var SITE_KEY  = lasModalRecaptcha.siteKey;
	var ERROR_MSG = lasModalRecaptcha.errorMsg || 'Please complete the reCAPTCHA verification.';

	/* -----------------------------------------------------------------
	 * DOM helpers
	 * --------------------------------------------------------------- */
	function getWidgetEl( scope ) {
		return scope.querySelector( '.las-modal-recaptcha-widget' );
	}

	function getErrorEl( scope ) {
		return scope.querySelector( '.las-modal-recaptcha-error' );
	}

	function showError( scope, msg ) {
		var el = getErrorEl( scope );
		if ( ! el ) { return; }
		el.textContent = msg || ERROR_MSG;
		el.style.display = 'block';
	}

	function clearError( scope ) {
		var el = getErrorEl( scope );
		if ( ! el ) { return; }
		el.textContent = '';
		el.style.display = 'none';
	}

	/* -----------------------------------------------------------------
	 * Widget ID storage (plain property on the element)
	 * --------------------------------------------------------------- */
	function getWidgetId( scope ) {
		var el = getWidgetEl( scope );
		return ( el && el._lasWidgetId !== undefined ) ? el._lasWidgetId : null;
	}

	function getToken( scope ) {
		var wid = getWidgetId( scope );
		if ( wid === null ) { return ''; }
		try { return grecaptcha.getResponse( wid ) || ''; } catch ( e ) { return ''; }
	}

	function resetWidget( scope ) {
		var wid = getWidgetId( scope );
		if ( wid === null ) { return; }
		try { grecaptcha.reset( wid ); } catch ( e ) {}
		clearError( scope );
	}

	/* -----------------------------------------------------------------
	 * Build and inject the widget + error container into a scope element
	 * --------------------------------------------------------------- */
	function buildWrap() {
		var wrap   = document.createElement( 'div' );
		var widget = document.createElement( 'div' );
		var err    = document.createElement( 'div' );

		wrap.className   = 'las-modal-recaptcha-wrap';
		widget.className = 'las-modal-recaptcha-widget';
		err.className    = 'las-modal-recaptcha-error';
		err.style.display = 'none';

		wrap.appendChild( widget );
		wrap.appendChild( err );
		return wrap;
	}

	/**
	 * Insert the wrap into a .signup-a-suite-form — before the submit button.
	 * The PHP partial already injects the wrap markup, so this is a JS fallback
	 * for any dynamically-added form instances or in case the partial wasn't
	 * modified yet.
	 */
	function ensureWrapInForm( form ) {
		if ( form.querySelector( '.las-modal-recaptcha-wrap' ) ) { return; }
		var btn = form.querySelector( 'button[type="submit"]' );
		if ( ! btn ) { return; }
		btn.parentNode.insertBefore( buildWrap(), btn );
	}

	/**
	 * Insert the wrap into a personal modal — before .modal-buttons (or before
	 * .submit-btn as fallback).  These modals have no <form> element; their HTML
	 * lives in Beaver Builder page content so we inject at runtime.
	 */
	function ensureWrapInModal( modal ) {
		if ( modal.querySelector( '.las-modal-recaptcha-wrap' ) ) { return; }
		var anchor = modal.querySelector( '.modal-buttons' ) ||
		             modal.querySelector( '.submit-btn' );
		if ( ! anchor ) { return; }
		anchor.parentNode.insertBefore( buildWrap(), anchor );
	}

	/* -----------------------------------------------------------------
	 * Render a grecaptcha widget into scope's .las-modal-recaptcha-widget
	 * --------------------------------------------------------------- */
	function renderWidget( scope ) {
		var widgetEl = getWidgetEl( scope );
		if ( ! widgetEl || widgetEl._lasWidgetId !== undefined ) { return; }

		try {
			var wid = grecaptcha.render( widgetEl, {
				sitekey: SITE_KEY,
				callback: function () {
					clearError( scope );
				},
				'expired-callback': function () {
					clearError( scope );
				},
				'error-callback': function () {
					clearError( scope );
				}
			} );
			widgetEl._lasWidgetId = wid;
		} catch ( e ) {
			// grecaptcha.render can throw if the element was already rendered.
			// No-op: the widget is functional, just skip double-render.
		}
	}

	/* =================================================================
	 * SIGNUP-A-SUITE FORMS
	 * Intercept the 'submit' event at document level in CAPTURE phase.
	 * This fires before signup-a-suite.js's listener on the form element.
	 * ================================================================ */
	document.addEventListener( 'submit', function ( e ) {
		var form = e.target;
		if ( ! form || ! form.classList.contains( 'signup-a-suite-form' ) ) { return; }

		// Widget not yet rendered (e.g. grecaptcha API not loaded yet)
		if ( ! form.querySelector( '.las-modal-recaptcha-wrap' ) ) { return; }

		var token = getToken( form );

		if ( ! token ) {
			e.preventDefault();
			e.stopImmediatePropagation();
			showError( form, ERROR_MSG );
			return;
		}

		// Valid — write token into a hidden input so FormData includes it
		clearError( form );
		var hidden = form.querySelector( 'input[name="g_recaptcha_response"]' );
		if ( ! hidden ) {
			hidden       = document.createElement( 'input' );
			hidden.type  = 'hidden';
			hidden.name  = 'g_recaptcha_response';
			form.appendChild( hidden );
		}
		hidden.value = token;
	}, true ); /* capture = true */

	/* =================================================================
	 * PERSONAL INFO MODALS
	 * Intercept .submit-btn click at document level in CAPTURE phase.
	 * This fires before jQuery's delegated bubble-phase listener.
	 * ================================================================ */
	var pendingMatchToken = '';

	document.addEventListener( 'click', function ( e ) {
		var btn = e.target;
		if ( ! btn || ! btn.classList.contains( 'submit-btn' ) ) { return; }

		var modal = btn.closest( '#personalInfoModal, #suite-matching-personal-modal' );
		if ( ! modal ) { return; }

		// Widget not yet rendered
		if ( ! modal.querySelector( '.las-modal-recaptcha-wrap' ) ) { return; }

		var token = getToken( modal );

		if ( ! token ) {
			e.stopImmediatePropagation();
			e.preventDefault();
			showError( modal, ERROR_MSG );
			return;
		}

		// Valid — stash the token; ajaxSend will inject it
		clearError( modal );
		pendingMatchToken = token;
	}, true ); /* capture = true */

	/* -----------------------------------------------------------------
	 * Inject the stashed token into every match_salon_suites AJAX call.
	 * By ajaxSend, jQuery has already serialised the data to a string.
	 * --------------------------------------------------------------- */
	if ( $ ) {
		$( document ).on( 'ajaxSend', function ( event, jqXHR, settings ) {
			if ( ! pendingMatchToken ) { return; }
			if ( typeof settings.data !== 'string' ) { return; }
			if ( settings.data.indexOf( 'action=match_salon_suites' ) === -1 ) { return; }
			settings.data += '&g_recaptcha_response=' + encodeURIComponent( pendingMatchToken );
			pendingMatchToken = '';
		} );

		/* Reset personal-modal widgets after the AJAX call completes */
		$( document ).on( 'ajaxComplete', function ( event, jqXHR, settings ) {
			if ( typeof settings.data !== 'string' ) { return; }
			if ( settings.data.indexOf( 'match_salon_suites' ) === -1 &&
			     settings.data.indexOf( 'g_recaptcha_response' ) === -1 ) { return; }
			[ 'personalInfoModal', 'suite-matching-personal-modal' ].forEach( function ( id ) {
				var m = document.getElementById( id );
				if ( m ) { resetWidget( m ); }
			} );
		} );
	}

	/* =================================================================
	 * MODAL CLOSE — reset widget + clear error on every dismiss
	 *
	 * Both modals close by removing the 'is-open' class (confirmed from
	 * signup-a-suite.js and custom.js source).  A MutationObserver on the
	 * modal element's 'class' attribute catches every close path:
	 * button, backdrop click, Escape key, and programmatic close.
	 * ================================================================ */

	/**
	 * Watch a modal element and call resetFn whenever 'is-open' is removed.
	 * Safe to call multiple times — uses a guard flag on the element.
	 *
	 * @param {Element}  modalEl   The modal root element to observe.
	 * @param {Function} resetFn   Called with no arguments when modal closes.
	 */
	function observeModalClose( modalEl, resetFn ) {
		if ( ! modalEl || modalEl._lasCloseObserved ) { return; }
		modalEl._lasCloseObserved = true;

		var wasOpen = modalEl.classList.contains( 'is-open' );

		var observer = new MutationObserver( function () {
			var isOpen = modalEl.classList.contains( 'is-open' );
			if ( wasOpen && ! isOpen ) {
				// Modal just closed — reset reCAPTCHA
				resetFn();
			}
			wasOpen = isOpen;
		} );

		observer.observe( modalEl, { attributes: true, attributeFilter: [ 'class' ] } );
	}

	/* =================================================================
	 * INITIALISE
	 * Wait for grecaptcha.render to be available (API may load async),
	 * then inject wraps + render widgets.
	 * ================================================================ */
	function initWidgets() {
		// Signup-a-suite forms (static page + modal instances)
		document.querySelectorAll( '.signup-a-suite-form' ).forEach( function ( form ) {
			ensureWrapInForm( form );
			renderWidget( form );
		} );

		// Personal info modals
		[ 'personalInfoModal', 'suite-matching-personal-modal' ].forEach( function ( id ) {
			var modal = document.getElementById( id );
			if ( ! modal ) { return; }
			ensureWrapInModal( modal );
			renderWidget( modal );
		} );

		// ---- Close observers ----

		// signup-a-suite-modal: reset each .signup-a-suite-form inside it
		var suiteModal = document.getElementById( 'signup-a-suite-modal' );
		if ( suiteModal ) {
			observeModalClose( suiteModal, function () {
				suiteModal.querySelectorAll( '.signup-a-suite-form' ).forEach( function ( form ) {
					resetWidget( form );
				} );
			} );
		}

		// personalInfoModal + suite-matching-personal-modal: reset widget in modal
		[ 'personalInfoModal', 'suite-matching-personal-modal' ].forEach( function ( id ) {
			var modal = document.getElementById( id );
			if ( ! modal ) { return; }
			observeModalClose( modal, function () {
				resetWidget( modal );
			} );
		} );
	}

	var initAttempts = 0;

	function waitForGrecaptcha() {
		if ( window.grecaptcha && typeof window.grecaptcha.render === 'function' ) {
			initWidgets();
		} else if ( initAttempts < 100 ) { // give up after ~10 s
			initAttempts++;
			setTimeout( waitForGrecaptcha, 100 );
		}
	}

	// Start waiting as soon as the DOM is ready
	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', waitForGrecaptcha );
	} else {
		waitForGrecaptcha();
	}

	// Also try on window.load (covers deferred API load)
	window.addEventListener( 'load', waitForGrecaptcha );

}( typeof jQuery !== 'undefined' ? jQuery : null ));
