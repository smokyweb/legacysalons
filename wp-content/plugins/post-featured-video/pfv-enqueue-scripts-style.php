<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
add_action('admin_enqueue_scripts','pfvideo_featued_enqueue_scripts');
if (!function_exists('pfvideo_featued_enqueue_scripts')){
	function pfvideo_featued_enqueue_scripts(){
		wp_register_script(
			'pfv_vid_uploader',
			PFVIDEO_POST_FEATURED_PLUGINURL . 'includes/js/pfv-uploader-script.js',
			array(),
			PFVIDEO_PLUGIN_VERSION,
			false // or false if you want to load the script in the header
		);
	  	wp_register_style('pfv_backend_style', PFVIDEO_POST_FEATURED_PLUGINURL.'includes/css/pfv-backend-style.css', array(), PFVIDEO_PLUGIN_VERSION);
	}
}
/*enqueue script for frontend*/
add_action('wp_enqueue_scripts','pfvideo_popup_lightbox_en');
if (!function_exists('pfvideo_popup_lightbox_en')){
	function pfvideo_popup_lightbox_en(){
		wp_register_script('pfv_lightbox_scrpt', PFVIDEO_POST_FEATURED_PLUGINURL.'includes/js/pfv-videopopup.js', array('jquery'), PFVIDEO_PLUGIN_VERSION, true);

		wp_register_style('pfv_frontnd_style', PFVIDEO_POST_FEATURED_PLUGINURL . 'includes/css/pfv-videopopup.css', array(), PFVIDEO_PLUGIN_VERSION);
	}
}