<?php
/**
 * Modal reCAPTCHA Fix
 *
 * Adds v2 reCAPTCHA to:
 *   - signup-a-suite-modal  (and the static page form)
 *   - personalInfoModal
 *   - suite-matching-personal-modal (if present)
 *
 * Approach:
 *   1. PHP injects the widget + error container into the signup-a-suite
 *      form partial (before the submit button).
 *   2. JS injects the widget into personalInfoModal / suite-matching-personal-modal
 *      at runtime (because those forms live in BB page content, not PHP templates).
 *   3. JS uses a document-level capture-phase listener so it fires BEFORE
 *      signup-a-suite.js's form listener AND before jQuery's delegated click
 *      listener in custom.js — no modifications to either file required.
 *   4. Server-side verification hooks at priority 5, ahead of the real AJAX
 *      handlers, so they exit early on failure without touching handler logic.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* -----------------------------------------------------------------------
 * 1. Enqueue reCAPTCHA API + our JS on all front-end pages
 * -------------------------------------------------------------------- */
add_action( 'wp_enqueue_scripts', 'las_modal_recaptcha_enqueue', 30 );

function las_modal_recaptcha_enqueue() {
	$site_key = get_option( 'hostbox_recaptcha_site_key', '' );
	if ( empty( $site_key ) ) {
		return;
	}

	/*
	 * Ensure the reCAPTCHA API is loaded on every front-end page.
	 * Hostbox already registers the handle 'google-recaptcha' for CF7 pages.
	 * If it has already been registered (same handle), wp_register_script
	 * is a no-op, so there's no duplicate load.
	 */
	if ( ! wp_script_is( 'google-recaptcha', 'registered' ) ) {
		wp_register_script(
			'google-recaptcha',
			'https://www.google.com/recaptcha/api.js',
			array(),
			null,
			false // load in <head> so grecaptcha is available before body scripts
		);
	}
	wp_enqueue_script( 'google-recaptcha' );

	// Our modal JS
	$js_path = plugin_dir_path( __FILE__ ) . 'js/modal-recaptcha.js';
	if ( ! file_exists( $js_path ) ) {
		return;
	}

	wp_enqueue_script(
		'las-modal-recaptcha',
		plugin_dir_url( __FILE__ ) . 'js/modal-recaptcha.js',
		array( 'jquery' ),
		(string) filemtime( $js_path ),
		true // footer
	);

	wp_localize_script(
		'las-modal-recaptcha',
		'lasModalRecaptcha',
		array(
			'siteKey'  => $site_key,
			'errorMsg' => __( 'Please complete the reCAPTCHA verification.', 'custom-functions' ),
		)
	);
}

/* -----------------------------------------------------------------------
 * 2. Inline spacing — margin between the field above and the widget
 * -------------------------------------------------------------------- */
add_action( 'wp_head', 'las_modal_recaptcha_inline_css', 30 );

function las_modal_recaptcha_inline_css() {
	echo '<style id="las-modal-recaptcha-css">
		/* ---- Modal reCAPTCHA wrap (personalInfoModal, suite-matching, signup-a-suite) ---- */
		.las-modal-recaptcha-wrap {
			margin-top: 16px;
			position: relative;
			z-index: 2;
			-webkit-transform: translateZ(0);
			transform: translateZ(0);
			touch-action: manipulation;
		}
		.las-modal-recaptcha-wrap iframe {
			touch-action: manipulation;
			pointer-events: auto !important;
		}
		/* ---- Universal fix: every Google reCAPTCHA widget on the site ---- */
		.g-recaptcha,
		.hostbox-recaptcha-wrap {
			position: relative;
			z-index: 2;
			-webkit-transform: translateZ(0);
			transform: translateZ(0);
			touch-action: manipulation;
		}
		.g-recaptcha iframe,
		.hostbox-recaptcha-wrap iframe,
		.las-modal-recaptcha-widget iframe {
			touch-action: manipulation;
			pointer-events: auto !important;
		}
		/* ---- Override momentum-scroll on known reCAPTCHA scroll ancestors ---- */
		/* signup-a-suite-modal: both dialog (overflow:auto) and body  */
		/* (-webkit-overflow-scrolling:touch) trap iOS touch before the iframe */
		.signup-a-suite-modal__dialog,
		.signup-a-suite-modal__body {
			-webkit-overflow-scrolling: auto !important;
		}
	</style>' . "\n";
}

/* -----------------------------------------------------------------------
 * 3. Server-side reCAPTCHA verification — shared utility
 * -------------------------------------------------------------------- */
function las_modal_verify_recaptcha( $token ) {
	$secret = get_option( 'hostbox_recaptcha_secret_key', '' );
	if ( empty( $secret ) || empty( $token ) ) {
		return false;
	}

	$response = wp_remote_post(
		'https://www.google.com/recaptcha/api/siteverify',
		array(
			'body'    => array(
				'secret'   => $secret,
				'response' => $token,
			),
			'timeout' => 10,
		)
	);

	if ( is_wp_error( $response ) ) {
		return false;
	}

	$body = json_decode( wp_remote_retrieve_body( $response ), true );
	return ! empty( $body['success'] );
}

/* -----------------------------------------------------------------------
 * 3. Verify reCAPTCHA before signup_a_suite_contact handler (priority 5)
 *    The real handler runs at priority 10; if we exit here it never fires.
 * -------------------------------------------------------------------- */
add_action( 'wp_ajax_signup_a_suite_contact',        'las_modal_recaptcha_check_signup', 5 );
add_action( 'wp_ajax_nopriv_signup_a_suite_contact', 'las_modal_recaptcha_check_signup', 5 );

function las_modal_recaptcha_check_signup() {
	$token = sanitize_text_field( wp_unslash( $_POST['g_recaptcha_response'] ?? '' ) );
	if ( ! las_modal_verify_recaptcha( $token ) ) {
		wp_send_json_error(
			array( 'message' => __( 'Please complete the reCAPTCHA verification.', 'custom-functions' ) ),
			400
		);
		// wp_send_json_error calls wp_die() — handler at priority 10 never runs.
	}
	// Token valid: fall through so the real handler at priority 10 runs.
}

/* -----------------------------------------------------------------------
 * 4. Verify reCAPTCHA before match_salon_suites handler (priority 5)
 * -------------------------------------------------------------------- */
add_action( 'wp_ajax_match_salon_suites',        'las_modal_recaptcha_check_match', 5 );
add_action( 'wp_ajax_nopriv_match_salon_suites', 'las_modal_recaptcha_check_match', 5 );

function las_modal_recaptcha_check_match() {
	$token = sanitize_text_field( wp_unslash( $_POST['g_recaptcha_response'] ?? '' ) );
	if ( ! las_modal_verify_recaptcha( $token ) ) {
		wp_send_json_error(
			array( 'message' => __( 'Please complete the reCAPTCHA verification.', 'custom-functions' ) ),
			400
		);
	}
}
