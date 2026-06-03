<?php
/**
* Load loftloader pro section progress related functions
*
* @since version 1.0.6
*/
if ( ! class_exists( 'LoftLoader_Pro_Section_Progress' ) ) {
	class LoftLoader_Pro_Section_Progress extends LoftLoader_Pro_Customize_Base {
		public function register_customize_elements( $wp_customize ) {
			global $llp_defaults, $llp_google_fonts;

			// Add Section
			$wp_customize->add_section( new LoftLoader_Customize_Section( $wp_customize, 'loftloader_pro_progress', array(
				'title' 	=> esc_html__( 'Progress', 'loftloader-pro' ),
				'priority' 	=> 60
			) ) );

			// Add Settings
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_progress', array(
				'default'   		=> $llp_defaults['loftloader_progress'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice'
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_barposition', array(
				'default'   		=> $llp_defaults['loftloader_barposition'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice',
				'dependency' 		=> array(
					'loftloader_progress' => array( 'value' => array( 'bar', 'none', 'bar-number' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_barwidth', array(
				'default'   		=> $llp_defaults['loftloader_barwidth'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'absint',
				'dependency' => array(
					'loftloader_progress' => array( 'value' => array( 'bar', 'none', 'bar-number' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_progress_width_unit', array(
				'default'   		=> $llp_defaults['loftloader_pro_progress_width_unit'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_checkbox'
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_barheight', array(
				'default'   		=> $llp_defaults['loftloader_barheight'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'absint',
				'dependency' 		=> array(
					'loftloader_progress' => array( 'value' => array( 'bar', 'none', 'bar-number' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_percentageposition', array(
				'default'   		=> $llp_defaults['loftloader_percentageposition'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice',
				'dependency' 		=> array(
					'loftloader_progress' => array( 'value' => array( 'number' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_percentage_absolute_extra_position', array(
				'default'   		=> $llp_defaults['loftloader_percentage_absolute_extra_position'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice',
				'dependency' 		=> array(
					'loftloader_progress' => array( 'value' => array( 'number' ) ),
					'loftloader_percentageposition' => array( 'value' => array( 'absolute' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_percentage_margin', array(
				'default'   		=> $llp_defaults['loftloader_percentage_margin'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_margins',
				'dependency' 		=> array(
					'loftloader_progress' => array( 'value' => array( 'number' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_progresslayer', array(
				'default'   		=> $llp_defaults['loftloader_progresslayer'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice',
				'dependency' 		=> array(
					'loftloader_progress' => array( 'value' => array( 'number' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_progress_color', array(
				'default'   		=> $llp_defaults['loftloader_pro_progress_color'],
				'transport' 		=> 'postMessage',
				'type'				=> 'option',
				'sanitize_callback' => 'llp_sanitize_colors',
				'dependency' 		=> array(
					'loftloader_progress' => array( 'value' => array( 'number', 'bar', 'none', 'bar-number' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_progress_bar_enable_gradient_color', array(
				'default'   		=> $llp_defaults['loftloader_pro_progress_bar_enable_gradient_color'],
				'transport' 		=> 'postMessage',
				'type'				=> 'option',
				'sanitize_callback' => 'llp_sanitize_checkbox',
				'dependency' 		=> array(
					'loftloader_progress' => array( 'value' => array( 'bar', 'bar-number' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_progress_bar_gradient_start_color', array(
				'default'   		=> $llp_defaults['loftloader_pro_progress_bar_gradient_start_color'],
				'transport' 		=> 'postMessage',
				'type'				=> 'option',
				'sanitize_callback' => 'llp_sanitize_colors',
				'dependency' 		=> array(
					'loftloader_progress' => array( 'value' => array( 'bar', 'bar-number' ) ),
					'loftloader_pro_progress_bar_enable_gradient_color' => array( 'value' => array( 'on' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_progress_bar_gradient_end_color', array(
				'default'   		=> $llp_defaults['loftloader_pro_progress_bar_gradient_end_color'],
				'transport' 		=> 'postMessage',
				'type'				=> 'option',
				'sanitize_callback' => 'llp_sanitize_colors',
				'dependency' 		=> array(
					'loftloader_progress' => array( 'value' => array( 'bar', 'bar-number' ) ),
					'loftloader_pro_progress_bar_enable_gradient_color' => array( 'value' => array( 'on' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_percentagesize', array(
				'default'   		=> $llp_defaults['loftloader_percentagesize'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'absint',
				'dependency' 		=> array(
					'loftloader_progress' => array( 'value' => array( 'number', 'bar-number' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_progress_number_enable_google_font', array(
				'default'   		=> $llp_defaults['loftloader_pro_progress_number_enable_google_font'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice',
				'dependency' 		=> array(
					'loftloader_progress' => array( 'value' => array( 'number', 'bar-number' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_progress_number_font_family', array(
				'default'   		=> $llp_defaults['loftloader_pro_progress_number_font_family'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice',
				'dependency' 		=> array(
					'loftloader_progress' => array( 'value' => array( 'number', 'bar-number' ) ),
					'loftloader_pro_progress_number_enable_google_font' => array( 'value' => array( 'on' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_progress_number_font_weight', array(
				'default'   		=> $llp_defaults['loftloader_pro_progress_number_font_weight'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice',
				'dependency' 		=> array(
					'loftloader_progress' => array( 'value' => array( 'number', 'bar-number' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_progress_number_letter_spacing', array(
				'default'   		=> $llp_defaults['loftloader_pro_progress_number_letter_spacing'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice',
				'dependency' 		=> array(
					'loftloader_progress' => array( 'value' => array( 'number', 'bar-number' ) )
				)
			) ) );

			// Add Controls
			$wp_customize->add_control( new LoftLoader_Customize_Horizontal_Radio_Control( $wp_customize, 'loftloader_progress', array(
				'type' 			=> 'radio',
				'label' 		=> esc_html__( 'Progress', 'loftloader-pro' ),
				'description' 	=> esc_html__( 'Hover on preview area and wait for 3 seconds to see the result.', 'loftloader-pro' ),
				'section' 		=> 'loftloader_pro_progress',
				'settings' 		=> 'loftloader_progress',
				'wrap_id' 		=> 'loftloader_option_progress',
				'choices' 		=> array(
					'none' 			=> array( 'label' => esc_html__( 'None', 'loftloader-pro' ), 'id' => 'loftloader_progressnone' ),
					'bar' 			=> array( 'label' => esc_html__( 'Loading Bar', 'loftloader-pro' ), 'id' => 'loftloader_progressbar' ),
					'number' 		=> array( 'label' => esc_html__( 'Percentage', 'loftloader-pro' ), 'id' => 'loftloader_progresspercentage' ),
					'bar-number' 	=> array( 'label' => esc_html__( 'Bar + Number', 'loftloader-pro' ), 'id' => 'loftloader_progress_bar_number' ),
				)
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Horizontal_Radio_Control( $wp_customize, 'loftloader_barposition', array(
				'type' 				=> 'btn',
				'label' 			=> esc_html__( 'Position', 'loftloader-pro' ),
				'show_label' 		=> true,
				'section' 			=> 'loftloader_pro_progress',
				'settings' 			=> 'loftloader_barposition',
				'wrap_id' 			=> 'loftloader_option_barposition',
				'active_callback' 	=> 'llp_customize_control_active_cb',
				'choices' 			=> array(
					'top' 		=> array( 'label' => esc_html__( 'Top', 'loftloader-pro' ) ),
					'middle' 	=> array( 'label' => esc_html__( 'Middle', 'loftloader-pro' ) ),
					'bottom' 	=> array( 'label' => esc_html__( 'Bottom', 'loftloader-pro' ) )
				)
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_barwidth', array(
				'type' 				=> 'number',
				'label' 			=> esc_html__( 'Width', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_progress',
				'settings' 			=> 'loftloader_barwidth',
				'input_attrs' 		=> array( 'min' => 1 ),
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'loftloader_pro_progress_width_unit', array(
				'type' 				=> 'checkbox',
				'label'			 	=> '',
				'choices'		 	=> array( 'on' => '' ),
				'section' 			=> 'loftloader_pro_progress',
				'settings' 			=> 'loftloader_pro_progress_width_unit',
				'active_callback'	=> 'llp_hide_control'
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Number_Text_Control( $wp_customize, 'loftloader_barheight', array(
				'type' 				=> 'number',
				'label' 			=> esc_html__( 'Height', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_progress',
				'settings' 			=> 'loftloader_barheight',
				'after_text' 		=> 'px',
				'input_class' 		=> 'loaderbarheight',
				'input_wrap_class'	=> 'barheight',
				'input_attrs' 		=> array( 'min' => 1 ),
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Horizontal_Radio_Control( $wp_customize, 'loftloader_percentageposition', array(
				'type' 				=> 'btn',
				'label' 			=> esc_html__( 'Position', 'loftloader-pro' ),
				'show_label' 		=> true,
				'section' 			=> 'loftloader_pro_progress',
				'settings' 			=> 'loftloader_percentageposition',
				'wrap_id' 			=> 'loftloader_option_percentageposition',
				'active_callback' 	=> 'llp_customize_control_active_cb',
				'choices' 			=> array(
					'middle' 	=> array( 'label' => esc_html__( 'Middle', 'loftloader-pro' ) ),
					'below' 	=> array( 'label' => esc_html__( 'Below Loader', 'loftloader-pro' ) ),
					'absolute' 	=> array( 'label' => esc_html__( 'Absolute Position', 'loftloader-pro' ) )
				)
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_percentage_absolute_extra_position', array(
				'type' 				=> 'select',
				'label' 			=> esc_html__( 'Select Position', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_progress',
				'settings' 			=> 'loftloader_percentage_absolute_extra_position',
				'active_callback' 	=> 'llp_customize_control_active_cb',
				'choices' 			=> array(
					'top-center' 	=> esc_html__( 'Top Center', 'loftloader-pro' ),
					'top-left' 		=> esc_html__( 'Top Left', 'loftloader-pro' ),
					'top-right' 	=> esc_html__( 'Top Right', 'loftloader-pro' ),
					'bottom-center' => esc_html__( 'Bottom Center', 'loftloader-pro' ),
					'bottom-left' 	=> esc_html__( 'Bottom Left', 'loftloader-pro' ),
					'bottom-right' 	=> esc_html__( 'Bottom Right', 'loftloader-pro' )
				)
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_percentage_margin', array(
				'type' 				=> 'loftloader_margins',
				'label' 			=> esc_html__( 'Margin (px)', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_progress',
				'settings' 			=> 'loftloader_percentage_margin',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Horizontal_Radio_Control( $wp_customize, 'loftloader_progresslayer', array(
				'type' 				=> 'btn',
				'label' 			=> esc_html__( 'Layer', 'loftloader-pro' ),
				'show_label' 		=> true,
				'section' 			=> 'loftloader_pro_progress',
				'settings' 			=> 'loftloader_progresslayer',
				'wrap_id' 			=> 'loftloader_option_progresslayer',
				'active_callback' 	=> 'llp_customize_control_active_cb',
				'choices' 			=> array(
					'front' => array( 'label' => esc_html__( 'Front', 'loftloader-pro' ) ),
					'back' 	=> array( 'label' => esc_html__( 'Back', 'loftloader-pro' ) )
				)
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'loftloader_pro_progress_color', array(
				'label'    			=> esc_html__( 'Pick Color', 'loftloader-pro' ),
				'section'  			=> 'loftloader_pro_progress',
				'settings' 			=> 'loftloader_pro_progress_color',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_progress_bar_enable_gradient_color', array(
				'type' 		=> 'check',
				'label'	 	=> esc_html__( 'Enable Gradient', 'loftloader-pro' ),
				'section' 	=> 'loftloader_pro_progress',
				'settings' 	=> 'loftloader_pro_progress_bar_enable_gradient_color',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'loftloader_pro_progress_bar_gradient_start_color', array(
				'label'    			=> esc_html__( 'Gradient Start Color', 'loftloader-pro' ),
				'section'  			=> 'loftloader_pro_progress',
				'settings' 			=> 'loftloader_pro_progress_bar_gradient_start_color',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'loftloader_pro_progress_bar_gradient_end_color', array(
				'label'    			=> esc_html__( 'Gradient End Color', 'loftloader-pro' ),
				'section'  			=> 'loftloader_pro_progress',
				'settings' 			=> 'loftloader_pro_progress_bar_gradient_end_color',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_percentagesize', array(
				'type'     			=> 'slider',
				'label'    			=> esc_html__( 'Size', 'loftloader-pro' ),
				'after_text' 		=> 'px',
				'input_class' 		=> 'loaderpercentagesize',
				'section'  			=> 'loftloader_pro_progress',
				'settings' 			=> 'loftloader_percentagesize',
				'active_callback' 	=> 'llp_customize_control_active_cb',
				'input_attrs' 		=> array(
					'data-default' 	=> 16,
					'data-min' 		=> 8,
					'data-max'		=> 200,
					'data-step' 	=> 1
				)
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_progress_number_enable_google_font', array(
				'type' 				=> 'select',
				'label' 			=> esc_html__( 'Font', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_progress',
				'settings' 			=> 'loftloader_pro_progress_number_enable_google_font',
				'active_callback'	=> 'llp_customize_control_active_cb',
				'choices' 			=> array(
					'on' 	=> esc_html__( 'Choose a Google Font ', 'loftloader-pro' ),
					'off'	=> esc_html__( 'Use site default font', 'loftloader-pro' )
				)
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_progress_number_font_family', array(
				'type' 				=> 'select',
				'label' 			=> esc_html__( 'Google Font', 'loftloader-pro' ),
				'choices' 			=> $llp_google_fonts,
				'section'  			=> 'loftloader_pro_progress',
				'settings' 			=> 'loftloader_pro_progress_number_font_family',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_progress_number_font_weight', array(
				'type' 				=> 'select',
				'label' 			=> esc_html__( 'Font Weight', 'loftloader-pro' ),
				'section'  			=> 'loftloader_pro_progress',
				'settings' 			=> 'loftloader_pro_progress_number_font_weight',
				'active_callback' 	=> 'llp_customize_control_active_cb',
				'choices' 			=> array(
					'100' => 100,
					'200' => 200,
					'300' => 300,
					'400' => 400,
					'500' => 500,
					'600' => 600,
					'700' => 700,
					'800' => 800
				)
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_progress_number_letter_spacing', array(
				'type' 				=> 'select',
				'label' 			=> esc_html__( 'Letter Spacing', 'loftloader-pro' ),
				'section'  			=> 'loftloader_pro_progress',
				'settings' 			=> 'loftloader_pro_progress_number_letter_spacing',
				'active_callback' 	=> 'llp_customize_control_active_cb',
				'choices' 			=> array(
					'0' 	=> 0,
					'0.1em' => '0.1em',
					'0.2em' => '0.2em',
					'0.3em' => '0.3em',
					'0.4em' => '0.4em'
				)
			) ) );
		}
	}
	new LoftLoader_Pro_Section_Progress();
}
