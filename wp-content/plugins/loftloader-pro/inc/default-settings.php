<?php
// Initialize default customize settings
global $llp_defaults;
$llp_defaults = array(
	'loftloader_pro_main_switch' 							=> 'on',

	'loftloader_pro_show_range' 							=> 'sitewide',
	'loftloader_pro_post_types' 							=> array(),
	'loftloader_pro_selected_post_types' 					=> array(),
	'loftloader_pro_hand_pick_pages' 						=> array(),
	'loftloader_pro_site_wide_exclude_pages'				=> array(),
	'loftloader_pro_all_pages_exclude_pages'				=> array(),
	'loftloader_pro_enable_once_per_session'				=> '',

	'loftloader_bgfilltype' 								=> 'solid',
	'loftloader_pro_bg_color' 								=> '#ffffff',
	'loftloader_bgopacity' 									=> 95,
	'loftloader_pro_bg_animation' 							=> 'split-h',
	'loftloader_pro_bg_image' 								=> '',
	'loftloader_pro_mobile_bg_image' 						=> '',
	'loftloader_pro_bg_image_repeat' 						=> '',
	'loftloader_pro_bg_image_size'							=> 'cover',
	'loftloader_pro_bg_gradient' 							=> '',
	'loftloader_pro_bg_gradient_start_color' 				=> '#ffffff',
	'loftloader_pro_bg_gradient_end_color' 					=> '#ffffff',
	'loftloader_pro_bg_gradient_angel' 						=> 0,

	'loftloader_animation' 									=> 'sun',
	'loftloader_pro_animation_color' 						=> '#248acc',
	'loftloader_pro_custom_loader'							=> '',
	'loftloader_pro_animation_frame_width' 					=> 80,
	'loftloader_pro_animation_frame_height' 				=> 80,
	'loftloader_pro_animation_frame_border_width' 			=> 4,
	'loftloader_pro_animation_crossing_left_color' 			=> '#00ffff',
	'loftloader_pro_animation_crossing_right_color' 		=> '#ff0000',
	'loftloader_pro_animation_rainbow_outer_color' 			=> '#00ffff',
	'loftloader_pro_animation_rainbow_middle_color' 		=> '#ffd700',
	'loftloader_pro_animation_rainbow_inner_color' 			=> '#ff0000',
	'loftloader_customimg' 									=> LOFTLOADERPRO_ASSETS_URI . 'img/loftloader-logo.png',
	'loftloader_imgwidth' 									=> 80,
	'loftloader_responsive_design_max_width'				=> 100,
	'loftloader_looping' 									=> 'forever',
	'loftloader_loaddirection' 								=> 'horizontal',
	'loftloader_rotatedirection' 							=> '2d',
	'loftloader_rotation_2d'								=> '',
	'loftloader_rotate_curve' 								=> '',
	'loftloader_bouncerolling' 								=> '',
	'loftloader_blendmode' 									=> 'lighten',
	'loftloader_custom_image_loading_vertical_direction' 	=> '',

	'loftloader_progress' 									=> 'none',
	'loftloader_barposition' 								=> 'middle',
	'loftloader_barwidth' 									=> 30,
	'loftloader_pro_progress_width_unit' 					=> '',
	'loftloader_barheight' 									=> 10,
	'loftloader_percentageposition' 						=> 'middle',
	'loftloader_percentage_absolute_extra_position'			=> 'top-center',
	'loftloader_percentage_margin'							=> array( '', '', '', '' ),
	'loftloader_progresslayer' 								=> 'front',
	'loftloader_percentagesize' 							=> 16,
	'loftloader_pro_progress_color' 						=> '#248acc',
	'loftloader_pro_progress_bar_enable_gradient_color'		=> '',
	'loftloader_pro_progress_bar_gradient_start_color'		=> '',
	'loftloader_pro_progress_bar_gradient_end_color'		=> '',
	'loftloader_pro_progress_number_enable_google_font'		=> 'on',
	'loftloader_pro_progress_number_font_family' 			=> 'Lato',
	'loftloader_pro_progress_number_font_weight' 			=> 100,
	'loftloader_pro_progress_number_letter_spacing' 		=> '0.1em',

	'loftloader_pro_message_text' 							=> '',
	'loftloader_pro_enable_random_message_text'				=> '',
	'loftloader_pro_random_message_text'					=> '',
	'loftloader_pro_render_random_message_by_js'			=> '',
	'loftloader_pro_message_size' 							=> 16,
	'loftloader_pro_message_position' 						=> 'bottom',
	'loftloader_pro_message_color' 							=> '#248acc',
	'loftloader_pro_message_enable_google_font'				=> 'on',
	'loftloader_pro_message_font_family' 					=> 'Lato',
	'loftloader_pro_message_font_weight' 					=> 400,
	'loftloader_pro_message_letter_spacing' 				=> '0.1em',
	'loftloader_pro_message_line_height'					=> '1.5',

	'loftloader_pro_load_time' 								=> 0,

	'loftloader_pro_max_load_time'							=> 0,

	'loftloader_pro_device' 								=> 'all',

	'loftloader_pro_insite_transition' 						=> '',
	'loftloader_pro_insite_transition_show_all'				=> '',
	'loftloader_pro_insite_transition_show_close_button'	=> '',
	'loftloader_pro_exclude_from_page_transition' 			=> '',
	'loftloader_pro_prevent_elements_from_spt_trigger'		=> '',

	'loftloader_pro_insite_transition_display'				=> '',
	'loftloader_pro_insite_transition_reverse_bg_animation'	=> '',

	'loftloader_pro_insite_transition_initial_percentage'	=> 60,
	'loftloader_pro_insite_transition_initial_timer'		=> 900,

	'loftloader_pro_insite_transition_buttons'				=> '',

	'loftloader_pro_disable_page_scrolling'					=> '',

	'loftloader_pro_enable_close_button'					=> 'on',
	'loftloader_pro_show_close_timer' 						=> 15,
	'loftloader_pro_show_close_tip'							=> '',

	'loftloader_pro_inner_elements_entrance_animation'		=> '',
	'loftloader_pro_inner_elements_exit_animation'			=> '',

	'loftloader_pro_detect_elements'						=> 'image',
	'loftloader_pro_detect_autoplay_video'					=> '',

	'loftloader_pro_adaptive_loading_screen_height_on_mobile' => '',

	'loftloader_pro_css_in_file' 							=> 'inline',

	'loftloader_pro_scripts_loading_priority'				=> 'normal',

	'loftloader_pro_inject_html_in_action_init'				=> '',

	'loftloader_pro_enable_any_page' 						=> '',
	'loftloader_pro_any_page_post_types'					=> array( 'page' ),
	'loftloader_pro_custom_css'								=> '',

	'loftloader_pro_disable_loader_by_url_parameter'		=> ''
);
