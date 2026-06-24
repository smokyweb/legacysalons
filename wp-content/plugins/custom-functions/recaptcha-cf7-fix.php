<?php
/**
 * reCAPTCHA CF7 Fix
 *
 * 1. Repositions the Hostbox reCAPTCHA widget before the submit button.
 * 2. Strips the Hostbox reCAPTCHA error from CF7's invalid_fields JSON
 *    (preventing it from rendering under the first field / globally) and
 *    passes it via a custom `recaptcha_error` key instead.
 * 3. Custom JS reads that key and shows the error only in
 *    .hostbox-recaptcha-error (directly below the widget).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* -----------------------------------------------------------------------
 * 1. Reposition the reCAPTCHA widget before the submit button
 * -------------------------------------------------------------------- */
add_filter( 'wpcf7_form_elements', 'legacy_cf7_recaptcha_reposition', 20 );

function legacy_cf7_recaptcha_reposition( $html ) {

	// Find the reCAPTCHA widget block injected by Hostbox
	if ( ! preg_match(
		'#(<div[^>]+class="[^"]*g-recaptcha[^"]*"[^>]*>.*?</div>\s*(?:<br\s*/?>)?)#is',
		$html,
		$widget_match
	) ) {
		return $html;
	}

	$widget_html = $widget_match[0];

	// Remove it from its current position
	$html = str_replace( $widget_html, '', $html );

	// Build the wrapped block: widget + error placeholder
	$wrapped = '<div class="hostbox-recaptcha-wrap">'
	         . $widget_html
	         . '<div class="hostbox-recaptcha-error"></div>'
	         . '</div>';

	// Insert before the submit span/input
	if ( preg_match( '#<span[^>]+class="[^"]*wpcf7-submit[^"]*"[^>]*>#i', $html ) ) {
		$html = preg_replace(
			'#(<span[^>]+class="[^"]*wpcf7-submit[^"]*"[^>]*>)#i',
			$wrapped . '$1',
			$html,
			1
		);
	} elseif ( preg_match( '#<input[^>]+type=["\']submit["\'][^>]*>#i', $html ) ) {
		$html = preg_replace(
			'#(<input[^>]+type=["\']submit["\'][^>]*>)#i',
			$wrapped . '$1',
			$html,
			1
		);
	} else {
		// Fallback: append before closing </form>
		$html = str_replace( '</form>', $wrapped . '</form>', $html );
	}

	return $html;
}

/* -----------------------------------------------------------------------
 * 2. Strip the Hostbox reCAPTCHA error from CF7's invalid_fields array
 *    and forward it as a dedicated `recaptcha_error` key so our JS can
 *    handle it without CF7 touching any field span.
 * -------------------------------------------------------------------- */
add_filter( 'wpcf7_ajax_json_echo', 'legacy_cf7_strip_recaptcha_from_invalid_fields', 20, 2 );

function legacy_cf7_strip_recaptcha_from_invalid_fields( $items, $submission ) {

	if ( empty( $items['invalid_fields'] ) || ! is_array( $items['invalid_fields'] ) ) {
		return $items;
	}

	$clean_fields    = array();
	$recaptcha_error = null;

	foreach ( $items['invalid_fields'] as $field ) {
		$field_name = isset( $field['field'] ) ? trim( (string) $field['field'] ) : '';
		$message    = isset( $field['message'] ) ? (string) $field['message'] : '';

		/*
		 * Hostbox reCAPTCHA errors are invalidated with an empty field name.
		 * As an extra safety net, also catch any entry whose message mentions
		 * "recaptcha" (case-insensitive).
		 */
		$is_recaptcha = ( $field_name === '' )
		             || ( stripos( $message, 'recaptcha' ) !== false );

		if ( $is_recaptcha ) {
			// Keep the last (or only) reCAPTCHA message for our JS
			if ( $message !== '' ) {
				$recaptcha_error = $message;
			}
		} else {
			$clean_fields[] = $field;
		}
	}

	if ( $recaptcha_error !== null ) {
		// Replace the field list with the clean version (no reCAPTCHA entry)
		$items['invalid_fields'] = $clean_fields;

		// Pass the error to our JS via a dedicated key
		$items['recaptcha_error'] = $recaptcha_error;
	}

	return $items;
}

/* -----------------------------------------------------------------------
 * 3. Inline CSS — only on singular pages to keep it scoped
 * -------------------------------------------------------------------- */
add_action( 'wp_head', 'legacy_cf7_recaptcha_inline_css', 30 );

function legacy_cf7_recaptcha_inline_css() {
	if ( ! is_singular() ) {
		return;
	}
	echo '<style id="hostbox-recaptcha-fix-css">'
	   . '.hostbox-recaptcha-error{display:none;}'
	   . '.hostbox-recaptcha-error:not(:empty){display:block;}'
	   . '.hostbox-recaptcha-wrap{position:relative;z-index:2;-webkit-transform:translateZ(0);transform:translateZ(0);touch-action:manipulation;}'
	   . '.hostbox-recaptcha-wrap iframe{touch-action:manipulation;pointer-events:auto!important;}'
	   . '</style>' . "\n";
}

/* -----------------------------------------------------------------------
 * 4. JS — read `recaptcha_error` from the API response and show it
 *    only in .hostbox-recaptcha-error (never in the global output div)
 * -------------------------------------------------------------------- */
add_action( 'wp_footer', 'legacy_cf7_recaptcha_error_scope_js', 30 );

function legacy_cf7_recaptcha_error_scope_js() {
	if ( ! is_singular() ) {
		return;
	}
	?>
<script id="hostbox-recaptcha-fix-js">
(function () {
	'use strict';

	/**
	 * On form invalid: show recaptcha error under the widget.
	 * Suppress the global banner when recaptcha is the only error.
	 *
	 * NOTE: In CF7 5.5+ `wpcf7submit` fires AFTER `wpcf7invalid`.
	 * The clear logic in `wpcf7submit` must not wipe what we set here.
	 */
	document.addEventListener('wpcf7invalid', function (e) {
		var form     = e.target;
		var api      = e.detail && e.detail.apiResponse;
		var errorDiv = form.querySelector('.hostbox-recaptcha-error');

		// Always reset first so a stale message never lingers on re-submit
		if (errorDiv) {
			errorDiv.textContent = '';
			errorDiv.style.display = 'none';
		}

		if (!api || !api.recaptcha_error) {
			return;
		}

		// Show the reCAPTCHA error under the widget
		if (errorDiv) {
			errorDiv.textContent = api.recaptcha_error;
			errorDiv.style.display = 'block';
		}

		// Suppress the global banner only when recaptcha is the sole failure
		var otherErrors = Array.isArray(api.invalid_fields) ? api.invalid_fields.length : 0;
		if (otherErrors === 0) {
			var banner = form.querySelector('.wpcf7-response-output');
			if (banner) {
				banner.style.visibility = 'hidden';
				banner.setAttribute('data-recaptcha-hidden', '1');
			}
		}
	});

	/**
	 * wpcf7submit fires AFTER wpcf7invalid in CF7 5.5+.
	 * Only clear the custom error div and restore the banner when the
	 * current response does NOT carry a recaptcha error — otherwise we
	 * would immediately wipe the error that wpcf7invalid just displayed.
	 */
	document.addEventListener('wpcf7submit', function (e) {
		var form   = e.target;
		var api    = e.detail && e.detail.apiResponse;
		var banner = form.querySelector('.wpcf7-response-output');

		// This submit result contains our recaptcha error — leave the div alone.
		if (api && api.recaptcha_error) {
			return;
		}

		// Any other outcome (success, other field errors, spam…): clear the custom div
		var errorDiv = form.querySelector('.hostbox-recaptcha-error');
		if (errorDiv) {
			errorDiv.textContent = '';
			errorDiv.style.display = 'none';
		}

		// Restore global banner if we previously hid it
		if (banner && banner.getAttribute('data-recaptcha-hidden') === '1') {
			banner.style.visibility = '';
			banner.removeAttribute('data-recaptcha-hidden');
		}
	});

	/**
	 * On success: clear the reCAPTCHA error div.
	 */
	document.addEventListener('wpcf7mailsent', function (e) {
		var errorDiv = e.target.querySelector('.hostbox-recaptcha-error');
		if (errorDiv) {
			errorDiv.textContent = '';
			errorDiv.style.display = 'none';
		}
	});

}());
</script>
	<?php
}
