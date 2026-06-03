<?php
/**
* Load loftloader pro section more related functions
*
* @since version 1.0.6
*/

if ( ! class_exists( 'LoftLoader_Pro_Panel_More' ) ) {
	class LoftLoader_Pro_Panel_More extends LoftLoader_Pro_Customize_Base {
		/**
		* String panel id
		*/
		protected $panel_id = 'loftloader_pro_more';
		public function register_customize_elements( $wp_customize ) {
			// Add Panel
			$wp_customize->add_panel( $this->panel_id, array(
				'title' 		=> esc_html__( 'More', 'loftloader-pro' ),
				'description' 	=> esc_html__( 'Please note: the options in the More section only show and work on front end.', 'loftloader-pro' ),
				'priority' 		=> 80
			) );

			$this->section_minimum_load_time( $wp_customize );
			$this->section_maximum_load_time( $wp_customize );
			$this->section_devices( $wp_customize );
			$this->section_disable_page_scrolling( $wp_customize );
			$this->section_close_button( $wp_customize );
			$this->section_inner_elements_animation( $wp_customize );
			$this->section_detect_elements( $wp_customize );
			$this->section_loading_screen( $wp_customize );
		}
		/**
		* Section minimum load time
		*/
		protected function section_minimum_load_time( $wp_customize ) {
			global $llp_defaults;

			$wp_customize->add_section( new LoftLoader_Customize_Section( $wp_customize, 'loftloader_pro_more_load_time', array(
				'title' => esc_html__( 'Minimum Load Time', 'loftloader-pro' ),
				'panel' => $this->panel_id
			) ) );

			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_load_time', array(
				'default' 			=> $llp_defaults['loftloader_pro_load_time'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_float'
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_load_time', array(
				'type'     		=> 'slider',
				'label'    		=> esc_html__( 'Minimum Load Time', 'loftloader-pro' ),
				'after_text' 	=> 'second(s)',
				'input_class' 	=> 'loftloader-load-time',
				'section' 	 	=> 'loftloader_pro_more_load_time',
				'settings' 		=> 'loftloader_pro_load_time',
				'input_attrs' 	=> array(
					'data-default' => '0',
					'data-min'     => '0',
					'data-max'     => '30',
					'data-step'    => '0.5'
				)
			) ) );
		}
		/**
		* Section maximum load time
		*/
		protected function section_maximum_load_time( $wp_customize ) {
			global $llp_defaults;

			$wp_customize->add_section( new LoftLoader_Customize_Section( $wp_customize, 'loftloader_pro_more_max_load_time', array(
				'title' => esc_html__( 'Maximum Load Time', 'loftloader-pro' ),
				'panel' => $this->panel_id
			) ) );

			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_max_load_time', array(
				'default' 			=> $llp_defaults['loftloader_pro_max_load_time'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_float'
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_max_load_time', array(
				'type'     			=> 'number-only',
				'label'    			=> esc_html__( 'Maximum Load Time', 'loftloader-pro' ),
				'description'		=> esc_html__( 'Please enter any number greater than 0 to enable this feature.', 'loftloader-pro' ),
				'description_above' => false,
				'after_text' 		=> 'second(s)',
				'section' 	 		=> 'loftloader_pro_more_max_load_time',
				'settings' 			=> 'loftloader_pro_max_load_time',
				'input_attrs' 		=> array( 'min' => '0' )
			) ) );
		}
		/**
		* Section devices
		*/
		protected function section_devices( $wp_customize ) {
			global $llp_defaults;

			$wp_customize->add_section( new LoftLoader_Customize_Section( $wp_customize, 'loftloader_pro_more_devices', array(
				'title' => esc_html__( 'Devices', 'loftloader-pro' ),
				'panel' => $this->panel_id
			) ) );

			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_device', array(
				'default' 			=> $llp_defaults['loftloader_pro_device'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice'
			) ) );

			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_device', array(
				'type' 		=> 'radio',
				'label' 	=> esc_html__( 'Devices', 'loftloader-pro' ),
				'section' 	=> 'loftloader_pro_more_devices',
				'settings' 	=> 'loftloader_pro_device',
				'choices' 	=> array(
					'all' 			=> esc_html__( 'Enable on all devices', 'loftloader-pro' ),
					'notmobile' 	=> esc_html__( 'Hide on mobile', 'loftloader-pro' ),
					'mobileonly' 	=> esc_html__( 'Enable on mobile only', 'loftloader-pro' )
				)
			) ) );
		}
		/**
		* Section disable page scrolling
		*/
		protected function section_disable_page_scrolling( $wp_customize ) {
			global $llp_defaults;

			$wp_customize->add_section( new LoftLoader_Customize_Section( $wp_customize, 'loftloader_pro_more_disable_page_scrolling', array(
				'title' => esc_html__( 'Disable Page Scrolling', 'loftloader-pro' ),
				'panel' => $this->panel_id
			) ) );

			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_disable_page_scrolling', array(
				'default'   		=> $llp_defaults['loftloader_pro_disable_page_scrolling'],
				'transport' 		=> 'postMessage',
				'type'				=> 'option',
				'sanitize_callback' => 'llp_sanitize_checkbox'
			) ) );

			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_disable_page_scrolling', array(
				'type' 		=> 'check',
				'label' 	=> esc_html__( 'Disable Page Scroll while Loading', 'loftloader-pro' ),
				'choices' 	=> array( 'on' => '' ),
				'section' 	=> 'loftloader_pro_more_disable_page_scrolling',
				'settings' 	=> 'loftloader_pro_disable_page_scrolling'
			) ) );
		}

		/**
		* Section close button
		*/
		protected function section_close_button( $wp_customize ) {
			global $llp_defaults;

			$wp_customize->add_section( new LoftLoader_Customize_Section( $wp_customize, 'loftloader_pro_more_close_button', array(
				'title' => esc_html__( 'Close Button', 'loftloader-pro' ),
				'panel' => $this->panel_id
			) ) );

			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_enable_close_button', array(
				'default'   		=> $llp_defaults['loftloader_pro_enable_close_button'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_checkbox'
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_show_close_timer', array(
				'default'   		=> $llp_defaults['loftloader_pro_show_close_timer'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'absint',
				'dependency' 		=> array(
					'loftloader_pro_enable_close_button' => array( 'value' => array( 'on' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_show_close_tip', array(
				'default'   		=> $llp_defaults['loftloader_pro_show_close_tip'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'sanitize_text_field',
				'dependency' 		=> array(
					'loftloader_pro_enable_close_button' => array( 'value' => array( 'on' ) )
				)
			) ) );

			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_enable_close_button', array(
				'type' 		=> 'check',
				'label' 	=> esc_html__( 'Show Close Button', 'loftloader-pro' ),
				'choices' 	=> array( 'on' => '' ),
				'section' 	=> 'loftloader_pro_more_close_button',
				'settings' 	=> 'loftloader_pro_enable_close_button'
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_show_close_timer', array(
				'type'     			=> 'slider',
				'label'    			=> esc_html__( 'Show Close Button after', 'loftloader-pro' ),
				'after_text' 		=> 'second(s)',
				'input_class' 		=> 'loftloader-show-close-timer',
				'section'  			=> 'loftloader_pro_more_close_button',
				'settings' 			=> 'loftloader_pro_show_close_timer',
				'active_callback' 	=> 'llp_customize_control_active_cb',
				'input_attrs' 		=> array(
					'data-default' 	=> '15',
					'data-min' 		=> '5',
					'data-max' 		=> '20',
					'data-step' 	=> '1'
				)
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_show_close_tip', array(
				'type' 				=> 'text',
				'label'				=> esc_html__( 'Description for Close Button', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_more_close_button',
				'active_callback' 	=> 'llp_customize_control_active_cb',
				'settings' 			=> 'loftloader_pro_show_close_tip'
			) ) );
		}
		/**
		* Section inner elements animation
		*/
		protected function section_inner_elements_animation( $wp_customize ) {
			global $llp_defaults;

			$wp_customize->add_section( new LoftLoader_Customize_Section( $wp_customize, 'loftloader_pro_more_inner_elements_animation', array(
				'title' 		=> esc_html__( 'Inner Elements Animation', 'loftloader-pro' ),
				'panel'			=> $this->panel_id,
				'description'	=> sprintf(
					/* translators: 1: html tag start. 2: html tag end */
					esc_html__( 'Here you can control the animation of inner elements such as the loader, progress indicator, and message. For more information please read the %1$sdocumentation%2$s.', 'loftloader-pro' ),
					sprintf( '<a href="%s" target="_blank">', 'http://loftocean.com/doc/loftloader/inner-elements-animation/' ),
					'</a>'
				)
			) ) );

			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_inner_elements_entrance_animation', array(
				'default'   		=> $llp_defaults['loftloader_pro_inner_elements_entrance_animation'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice'
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_inner_elements_exit_animation', array(
				'default'   		=> $llp_defaults['loftloader_pro_inner_elements_exit_animation'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice'
			) ) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'loftloader_pro_inner_elements_entrance_animation', array(
				'type' 		=> 'select',
				'label' 	=> esc_html__( 'Entrance Animation', 'loftloader-pro' ),
				'description' => esc_html__( 'Please select "None" when both "Smooth Page Transition" and "Show all elements when leaving the current page" are enabled.', 'loftloader-pro' ),
				'section' 	=> 'loftloader_pro_more_inner_elements_animation',
				'settings' 	=> 'loftloader_pro_inner_elements_entrance_animation',
				'choices' 	=> array(
					'' 					=> esc_html__( 'None', 'loftloader-pro' ),
					'inner-enter-fade' 	=> esc_html__( 'Fade In', 'loftloader-pro' ),
					'inner-enter-up' 	=> esc_html__( 'Slide Up', 'loftloader-pro' )
				)
			) ) );
			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'loftloader_pro_inner_elements_exit_animation', array(
				'type' 		=> 'select',
				'label' 	=> esc_html__( 'Exit Animation', 'loftloader-pro' ),
				'section' 	=> 'loftloader_pro_more_inner_elements_animation',
				'settings' 	=> 'loftloader_pro_inner_elements_exit_animation',
				'choices' 	=> array(
					'' 				=> esc_html__( 'Fade Out', 'loftloader-pro' ),
					'inner-end-up' 	=> esc_html__( 'Slide Up', 'loftloader-pro' )
				)
			) ) );
		}
		/**
		* Section detect elements
		*/
		protected function section_detect_elements( $wp_customize ) {
			global $llp_defaults;

			$wp_customize->add_section( new LoftLoader_Customize_Section( $wp_customize, 'loftloader_pro_more_detect_elements', array(
				'title' => esc_html__( 'Detect Elements', 'loftloader-pro' ),
				'panel' => $this->panel_id
			) ) );

			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_detect_elements', array(
				'default'   		=> $llp_defaults['loftloader_pro_detect_elements'],
				'transport' 		=> 'postMessage',
				'type'				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice'
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_detect_autoplay_video', array(
				'default'   		=> $llp_defaults['loftloader_pro_detect_autoplay_video'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_checkbox',
				'dependency' 		=> array(
					'loftloader_pro_detect_elements' => array( 'value' => array( 'video', 'media' ) )
				)
			) ) );

			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_detect_elements', array(
				'type' 		=> 'radio',
				'label' 	=> esc_html__( 'Detect Elements', 'loftloader-pro' ),
				'section' 	=> 'loftloader_pro_more_detect_elements',
				'settings' 	=> 'loftloader_pro_detect_elements',
				'choices' 	=> array(
					'all'	=> esc_html__( 'Detect when the browser stops loading', 'loftloader-pro' ),
					'image' => esc_html__( 'Detect Images', 'loftloader-pro' ),
					'video' => esc_html__( 'Detect Videos', 'loftloader-pro' ),
					'media' => esc_html__( 'Detect Images & Videos', 'loftloader-pro' )
				),
				'description' 		=> esc_html__( 'For video detection, LoftLoader only check the videos from youtube/vimeo and media library.', 'loftloader-pro' ),
				'description_above' => false
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_detect_autoplay_video', array(
				'type' 				=> 'check',
				'label'	 			=> esc_html__( 'Also detect autoplay video', 'loftloader-pro' ),
				'description'		=> esc_html__( 'Please note: Enabling this option may increase the display time of the preloader. Only works on desktop devices.', 'loftloader-pro' ),
				'description_above'	=> false,
				'section' 			=> 'loftloader_pro_more_detect_elements',
				'settings' 			=> 'loftloader_pro_detect_autoplay_video',
				'active_callback'	=> 'llp_customize_control_active_cb'
			) ) );
		}
		/**
		* Section loading screen
		*/
		protected function section_loading_screen( $wp_customize ) {
			global $llp_defaults;

			$wp_customize->add_section( new LoftLoader_Customize_Section( $wp_customize, 'loftloader_pro_more_loading_screen', array(
				'title' => esc_html__( 'Height on Mobile', 'loftloader-pro' ),
				'panel' => $this->panel_id
			) ) );

			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_adaptive_loading_screen_height_on_mobile', array(
				'default'   		=> $llp_defaults['loftloader_pro_adaptive_loading_screen_height_on_mobile'],
				'transport' 		=> 'postMessage',
				'type'				=> 'option',
				'sanitize_callback' => 'llp_sanitize_checkbox'
			) ) );

			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_adaptive_loading_screen_height_on_mobile', array(
				'type' 		=> 'check',
				'label'	 	=> esc_html__( 'Adaptive Screen Height for Mobile Devices', 'loftloader-pro' ),
				'section' 	=> 'loftloader_pro_more_loading_screen',
				'settings' 	=> 'loftloader_pro_adaptive_loading_screen_height_on_mobile'
			) ) );
		}
	}
	new LoftLoader_Pro_Panel_More();
}
