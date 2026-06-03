( function( api, $ ) {
	"use strict";
	/**
	* Convert to string 'on' if current value is boolean true (not other value equals to true)
	* @param value mix, the value to check
	* @return mix, string 'on' if current value equals to boolean true, otherwise return the original value.
	*/
	function llp_check_boolean( value ) {
		return ( value === true ) ? 'on' : value;
	}

	/**
	* Get customize setting value
	* @param id string, the setting id
	* @return mix, return dirty value/setting value or false (the id not exists)
	**/
	function llp_get_setting_value( id ) {
		var settings = api.get(), //settings.settings, dirty_values = api.dirtyValues(),
			value = ( undefined === settings[ id ] ) ? false : settings[ id ]; //dirty_values[id] ? dirty_values[id] : (settings[id] ? settings[id]['value'] : false);
		if ( typeof value === 'string' ) {
			value = value.trim();
			if ( [ 'loftloader_pro_progress_number_font_family', 'loftloader_pro_message_font_family', 'loftloader_customimg' ].indexOf( id ) !== -1 ) {
				value = '"' + value + '"';
			} else if ( ( [ 'loftloader_pro_message_text', 'loftloader_pro_random_message_text', 'loftloader_pro_show_close_tip' ].indexOf( id ) === -1 ) &&  ( 0 === value.length ) ) {
				value = '""';
			}
		}
		return llp_check_boolean( value );
	}

	function llp_encode_text( text ) {
		return text ? ( '"' + btoa( unescape( encodeURIComponent( text ) ) ) + '"' ) : '""';
	}

	// Generate loftloader parameters
	api.loftloader_pro_generate_parameters = function() {
		var dependency = {
				'loftloader_bgfilltype': {
					'solid': [
						'loftloader_pro_bg_color',
						'loftloader_bgopacity',
						'loftloader_pro_bg_animation',
						'loftloader_pro_bg_gradient'
					],
					'image': [
						'loftloader_pro_bg_color',
						'loftloader_bgopacity',
						'loftloader_pro_bg_animation',
						'loftloader_pro_bg_image',
						'loftloader_pro_mobile_bg_image',
						'loftloader_pro_bg_image_repeat',
						'loftloader_pro_bg_image_size'
					],
					'none': []
				},
				'loftloader_pro_bg_gradient': {
					'on': [
						'loftloader_pro_bg_gradient_start_color',
						'loftloader_pro_bg_gradient_end_color',
						'loftloader_pro_bg_gradient_angel'
					],
				},
				'loftloader_animation': {
					'none': [],
					'sun': [
						'loftloader_pro_animation_color'
					],
					'luminous': [
						'loftloader_pro_animation_color'
					],
					'wave': [
						'loftloader_pro_animation_color'
					],
					'square': [
						'loftloader_pro_animation_color'
					],
					'frame': [
						'loftloader_pro_animation_color',
						'loftloader_pro_animation_frame_width',
						'loftloader_pro_animation_frame_height',
						'loftloader_pro_animation_frame_border_width',
						'loftloader_customimg'
					],
					'imgloading': [
						'loftloader_customimg',
						'loftloader_imgwidth',
						'loftloader_responsive_design_max_width',
						'loftloader_looping',
						'loftloader_loaddirection',
						'loftloader_custom_image_loading_vertical_direction'
					],
					'imgstatic': [
						'loftloader_customimg',
						'loftloader_imgwidth',
						'loftloader_responsive_design_max_width'
					],
					'imgrotating': [
						'loftloader_customimg',
						'loftloader_imgwidth',
						'loftloader_responsive_design_max_width',
						'loftloader_rotatedirection',
						'loftloader_rotate_curve',
						'loftloader_rotation_2d'
					],
					'imgbouncing': [
						'loftloader_customimg',
						'loftloader_imgwidth',
						'loftloader_responsive_design_max_width',
						'loftloader_bouncerolling'
					],
					'crossing': [
						'loftloader_pro_animation_crossing_left_color',
						'loftloader_pro_animation_crossing_right_color',
						'loftloader_blendmode'
					],
					'ducks': [
						'loftloader_pro_animation_color',
					],
					'rainbow': [
						'loftloader_pro_animation_rainbow_outer_color',
						'loftloader_pro_animation_rainbow_middle_color',
						'loftloader_pro_animation_rainbow_inner_color',
						'loftloader_looping'
					],
					'circlefilling': [
						'loftloader_pro_animation_color',
						'loftloader_looping'
					],
					'waterfilling': [
						'loftloader_pro_animation_color',
						'loftloader_looping'
					],
					'crystal': [
						'loftloader_pro_animation_color'
					],
					'petals': [
						'loftloader_pro_animation_color',
						'loftloader_looping'
					],
					'beating': [
						'loftloader_pro_animation_color'
					],
					'imgfading': [
						'loftloader_customimg',
						'loftloader_imgwidth',
						'loftloader_responsive_design_max_width'
					],
					'incomplete-ring': [
						'loftloader_pro_animation_color'
					],
					'custom-loader': [
						'loftloader_pro_custom_loader'
					]
				},
				'loftloader_progress': {
					'none': [],
					'bar': [
						'loftloader_barposition',
						'loftloader_barwidth',
						'loftloader_pro_progress_width_unit',
						'loftloader_barheight',
						'loftloader_pro_progress_color',
						'loftloader_pro_progress_bar_enable_gradient_color',
						'loftloader_pro_progress_bar_gradient_start_color',
						'loftloader_pro_progress_bar_gradient_end_color'
					],
					'number': [
						'loftloader_percentageposition',
						'loftloader_percentage_absolute_extra_position',
						'loftloader_percentage_margin',
						'loftloader_progresslayer',
						'loftloader_percentagesize',
						'loftloader_pro_progress_color',
						'loftloader_pro_progress_number_enable_google_font',
						'loftloader_pro_progress_number_font_family',
						'loftloader_pro_progress_number_font_weight',
						'loftloader_pro_progress_number_letter_spacing'
					],
					'bar-number': [
						'loftloader_barposition',
						'loftloader_barwidth',
						'loftloader_pro_progress_width_unit',
						'loftloader_barheight',
						'loftloader_pro_progress_color',
						'loftloader_pro_progress_bar_enable_gradient_color',
						'loftloader_pro_progress_bar_gradient_start_color',
						'loftloader_pro_progress_bar_gradient_end_color',
						'loftloader_percentagesize',
						'loftloader_pro_progress_number_enable_google_font',
						'loftloader_pro_progress_number_font_family',
						'loftloader_pro_progress_number_font_weight',
						'loftloader_pro_progress_number_letter_spacing'
					]
				},
				'loftloader_pro_message_text': {
					'': [],
					'text': [
						'loftloader_pro_message_position',
						'loftloader_pro_message_size',
						'loftloader_pro_message_color',
						'loftloader_pro_message_enable_google_font',
						'loftloader_pro_message_font_family',
						'loftloader_pro_message_font_weight',
						'loftloader_pro_message_letter_spacing',
						'loftloader_pro_message_line_height'
					],
					'random': [
						'loftloader_pro_render_random_message_by_js',
						'loftloader_pro_message_position',
						'loftloader_pro_message_size',
						'loftloader_pro_message_color',
						'loftloader_pro_message_enable_google_font',
						'loftloader_pro_message_font_family',
						'loftloader_pro_message_font_weight',
						'loftloader_pro_message_letter_spacing',
						'loftloader_pro_message_line_height'
					]
				},
				'loftloader_pro_load_time': { },
				'loftloader_pro_max_load_time': { },
				'loftloader_pro_device': { },
				'loftloader_pro_disable_page_scrolling': { },
				'loftloader_pro_enable_close_button': { },
				'loftloader_pro_show_close_timer': { },
				'loftloader_pro_show_close_tip': { },
				'loftloader_pro_inner_elements_entrance_animation': { },
				'loftloader_pro_inner_elements_exit_animation': { },
				'loftloader_pro_adaptive_loading_screen_height_on_mobile': { },
				'loftloader_pro_custom_css': { },
				'loftloader_pro_detect_elements': {
					'video': [
						'loftloader_pro_detect_autoplay_video'
					],
					'media': [
						'loftloader_pro_detect_autoplay_video'
					]
				}
			};
		var loftloader = '', type_value = '', loop = [],
			randomMessageEnabled = ( 'on' == llp_get_setting_value( 'loftloader_pro_enable_random_message_text' ) );
		if( llp_get_setting_value( 'loftloader_pro_main_switch' ) === 'on' ) {
			loftloader = 'loftloader_pro_main_switch=on loftloader_pro_show_range=sitewide';
			loftloader += ' loftloader_pro_enable_random_message_text="' + ( randomMessageEnabled ? 'on' : '' ) + '"';
			for( var id in dependency ) {
				type_value = llp_get_setting_value( id );
				switch( id ) {
					case 'loftloader_pro_message_text':
						if ( randomMessageEnabled ) {
							var randomMessage = llp_get_setting_value( 'loftloader_pro_random_message_text' );
							type_value = llp_encode_text( randomMessage );
							loop = type_value ? dependency[id]['random'] : [];
							id = 'loftloader_pro_random_message_text';
						} else {
							loop = type_value ? dependency[id]['text'] : [];
							type_value = llp_encode_text( type_value );
						}
						break;
					case 'loftloader_pro_show_close_tip':
					case 'loftloader_pro_custom_css':
						type_value = llp_encode_text( type_value );
					case 'loftloader_pro_load_time':
					case 'loftloader_pro_device':
						loop = [];
						break;
					default:
						loop = dependency[id][type_value] ? dependency[id][type_value] : [];
				}
				loftloader += ( 'loftloader_pro_bg_gradient' === id ) ? '' : ( ' ' + id + '=' + type_value );
				if ( loop ) {
					for( var j in loop ) {
						var loopName = loop[j], loopValue = llp_get_setting_value( loopName );
						if ( 'loftloader_pro_custom_loader' == loopName ) {
							loopValue = llp_encode_text( loopValue );
						}
						loftloader += ' ' + loopName + '=' + loopValue;
					}
				}
			}
		} else {
			loftloader = 'loftloader_pro_main_switch=false';
		}
		return loftloader;
	}
} )( wp.customize, jQuery );
