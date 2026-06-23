<?php
/**
 * Plugin Name: Custom Functions
 * Description: Handles all shortcodes and AJAX functions.
 * Version: 1.0
 */
 
 
if (!defined('ABSPATH')) exit;

// Include shortcode and AJAX files
require_once plugin_dir_path(__FILE__) . 'shortcodes.php';
require_once plugin_dir_path(__FILE__) . 'handlers.php';
require_once plugin_dir_path(__FILE__) . 'recaptcha-cf7-fix.php';
require_once plugin_dir_path(__FILE__) . 'modal-recaptcha-fix.php';
// function custom_functions_enqueue_scripts() {
//     wp_enqueue_script(
//         'custom-all-js',
//         plugin_dir_url(__FILE__) . 'js/custom.js',
//         array('jquery'),
//         '1.1.4',
//         true
//     );
// }

/**
 * Legacy gallery modal CSS loads after child theme stylesheet so live/minified stacks do not override flex/nav layout.
 */
function custom_functions_enqueue_legacy_gallery_modal_styles() {
    $path = plugin_dir_path(__FILE__) . 'css/legacy-gallery-modal.css';
    if (! file_exists($path)) {
        return;
    }
    $deps = wp_style_is('child-style', 'registered') ? array('child-style') : array();
    wp_enqueue_style(
        'legacy-gallery-modal',
        plugin_dir_url(__FILE__) . 'css/legacy-gallery-modal.css',
        $deps,
        (string) filemtime($path)
    );
}

// add_action('wp_enqueue_scripts', 'custom_functions_enqueue_scripts');
add_action('wp_enqueue_scripts', 'custom_functions_enqueue_legacy_gallery_modal_styles', 25);