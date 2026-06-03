<?php
/**
* Define function used globally
*/


/**
* Get setting option value
* @param string option name
* @param boolean whether call the filter
* @return mix the option value
*/
function llp_get_loader_setting( $setting_id, $without_filter = false ) {
	global $llp_defaults, $llp_external_custom_style;

	$setting = get_option( $setting_id, $llp_defaults[ $setting_id ] );
	if ( $llp_external_custom_style ) {
		return $setting;
	}
	return $without_filter ? $setting : apply_filters( 'loftloader_pro_get_loader_setting', $setting, $setting_id );
}
/**
* Test if specific module is enabled
*
* @param string module id
* @return boolean
*/
function llp_module_enabled( $id ) {
	return ( 'on' === esc_attr( llp_get_loader_setting( $id ) ) );
}
/**
* Get random message list
*/
function llp_get_random_message_list() {
	$messages = trim( llp_get_loader_setting( 'loftloader_pro_random_message_text' ) );
	if ( ! empty( $messages ) ) {
		$list = preg_split( '/\r\n|\n|\r/', $messages );
		return array_filter( $list );
	}
	return '';
}
/**
* Get random message
*/
function llp_get_random_message() {
	$list = llp_get_random_message_list();
	if ( empty( $list ) || ! is_array( $list ) ) {
		return '';
	} else {
		$max = count( $list ) - 1;
		return $list[ rand( 0, $max ) ];
	}
}
/**
* Set cookie with given name and value
* @param string cookie id
* @param string cookie value
*/
function llp_set_cookie( $name, $value ){
	setcookie( $name, $value, 0, ( COOKIEPATH ? COOKIEPATH : '/' ), COOKIE_DOMAIN );
}
/**
* @description helper function to check the image url provided
* @param string image url
* @return mix
*/
function llp_check_image_url( $url ) {
	if ( ! empty( $url ) && ( 'false' !== $url ) ) {
		return $url;
	}
	return false;
}
/**
* Get the custom styles wrapped with given id
*
* @param string <style> id
* @param string <style> content
* @return string html tag <style> with given content and id
*/
function llp_generate_style( $id, $style ) {
	return sprintf ( '<style id="%s">%s</style>', $id, $style );
}
/**
* Helper functions: convert hex color to rgba style
*
* @param string color string in hex style
* @param float opacity value
* @return string color string in rgba style
**/
function llp_hex2rgba( $hex, $opacity ) {
	$hex = strtolower( $hex );
	return sprintf(
		'rgba(%s, %s, %s, %s)',
		llp_convert_hex( substr( $hex, 1, 1 ), substr( $hex, 2, 1 ) ),
		llp_convert_hex( substr( $hex, 3, 1 ), substr( $hex, 4, 1 ) ),
		llp_convert_hex( substr( $hex, 5, 1 ), substr( $hex, 6, 1 ) ),
		$opacity
	);
}
/**
* Helper function to convert hex to decimal
*
* @param string first value of hex
* @param string second value of hex
* @return int
*/
function llp_convert_hex($first, $second){
	$hex = array(
		'0' => 0,
		'1' => 1,
		'2' => 2,
		'3' => 3,
		'4' => 4,
		'5' => 5,
		'6' => 6,
		'7' => 7,
		'8' => 8,
		'9' => 9,
		'a' => 10,
		'b' => 11,
		'c' => 12,
		'd' => 13,
		'e' => 14,
		'f' => 15
	);
	return $hex[ $first ] * 16 + $hex[ $second ];
}
/**
* Get plugin customize link
* @return url
*/
function llp_get_plugin_customize_link() {
	$return_url = '';
	if ( ! empty( $_SERVER['REQUEST_URI'] ) ) {
		$return_url = sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) );
	}
	return add_query_arg(
		array(
			'return' => urlencode( $return_url ),
			'plugin' => 'loftloader'
		),
		'customize.php'
	);
}
/**
* Test if specific module is enabled
*
* @param string module id
* @return boolean
*/
function llp_is_module_enabled( $id ) {
	return ( 'on' ===  esc_attr( get_option( $id, '' ) ) );
}
/**
* Plugin Customization sanitize callback functions
*/
// Sanitize checkbox
function llp_sanitize_checkbox( $input ) {
	return empty( $input ) ? 'off' : 'on';
}
// Sanitize float
function llp_sanitize_float( $input ) {
	$val = floatval( $input );
	return max( 0, number_format( $val, 1, '.', '' ) );
}
// Sanitize choices
function llp_sanitize_choice( $input, $setting ) {
	$choices = $setting->manager->get_control( $setting->id )->choices;
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}
// Sanitize multiple choices
function llp_sanitize_choices( $input, $setting ) {
	$choices = $setting->manager->get_control( $setting->id )->choices;
	if ( is_array( $choices ) ) {
		$choices = array_keys( $choices );
	} else {
		return $setting->default;
	}
	foreach( $input as $index => $val ) {
		if ( ! in_array( $val, $choices ) ) {
			return $setting->default;
		}
	}
	return $input;
}
// Sanitize handpick posts value
function llp_sanitize_handpick_posts( $input ) {
	return empty( $input ) ? array() : array_filter( explode( ',', $input ) );
}
// Sanitize color
function llp_sanitize_colors( $input, $setting ) {
	return preg_match( '/^#[0-9a-f]{3}([0-9a-f]{3})?$/', $input ) ? $input : $setting->default;
}
// Sanitize color
function llp_sanitize_html( $input, $setting ) {
	return trim( $input );
}
// Sanitize Message text
function llp_sanitize_message_text( $input ) {
	return wp_kses( $input, array(
		'br' 		=> array(),
		'i' 		=> array(),
		'b'			=> array(),
		'strong' 	=> array(),
	) );
}
// Sanitize URL parameters
function llp_sanitize_url_parameters( $input ) {
	return wp_kses_post( $input );
}
// sanitize array 
function llp_sanitize_margins( $input ) {
	$input = explode( ',', $input );
	return is_array( $input ) && ( count( $input ) === 4 ) ? $input : array( '', '', '', '' ); 
}
// Validate customize setting minimum load time
function llp_validate_min_load_time() {
	return false;
}
// Validate customize setting maximum load time
function llp_validate_max_load_time() {
	return false;
}
// Control active callback function, hide control
function llp_hide_control() {
	return false;
}
/**
* Get current post types supported
* @param boolean if include post
* @param array
*/
function llp_get_post_types( $include_page = true, $include_post = true ) {
	$types = get_post_types( array( 'publicly_queryable' => true, '_builtin' => false ), 'objects' );
	$posts = array();
	if ( $include_post ) {
		$posts['post'] = esc_html__( 'Posts', 'loftloader-pro' );
	}
	if ( $include_page ) {
		$posts['page'] = esc_html__( 'Pages', 'loftloader-pro' );
	}
	foreach ( $types as $t ) {
		$posts[ $t->name ] = $t->label;
	} 
	return $posts;
}
/**
* Condition function test if any cache plugin enabled
* @return boolean
*/
function llp_use_cache_scripts() {
	return apply_filters( 'loftloader_pro_enabled_session', false ) || apply_filters( 'loftloader_pro_page_show_once', false );

	// $normal = defined( 'WP_CACHE' ) && WP_CACHE;
	// $wpfc = defined( 'WPFC_WP_CONTENT_BASENAME' );
	// $swift = class_exists( 'Swift_Performance' ) || class_exists( 'Swift_Performance_Lite' );
	// return $normal || $swift || $wpfc;
}
/**
* Condition function test if is woocommerce shop page
*/
function llp_is_woocommerce_shop() {
	if ( function_exists( 'is_shop' ) && function_exists( 'wc_get_page_id' ) ) {
		$page_id = wc_get_page_id( 'shop' );
		return ( ! empty( $page_id ) ) && ( $page_id > 0 ) && is_shop();
	}
	return false;
}
/**
* Get background image
*/
function llp_get_background_image() {
	$is_mobile = apply_filters( 'loftloader_pro_is_mobile', false );
	$bg = llp_get_loader_setting( 'loftloader_pro_mobile_bg_image' );
	if ( $is_mobile && ! empty( $bg ) ) {
		return $bg;
	}
	return llp_get_loader_setting( 'loftloader_pro_bg_image' );
}
