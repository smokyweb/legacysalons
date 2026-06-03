<?php
/**
* Load loftloader pro section message related functions
*
* @since version 1.0.6
*/

if ( ! class_exists( 'LoftLoader_Pro_Section_Message' ) ) {
	class LoftLoader_Pro_Section_Message extends LoftLoader_Pro_Customize_Base {
		public function register_customize_elements( $wp_customize ) {
			global $llp_defaults, $llp_google_fonts;

			// Add Section
			$wp_customize->add_section( new LoftLoader_Customize_Section( $wp_customize, 'loftloader_pro_message', array(
				'title'       => esc_html__( 'Message', 'loftloader-pro' ),
				'description' => '',
				'priority'    => 70
			) ) );

			// Add Settings
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_message_text', array(
				'default'   		=> $llp_defaults['loftloader_pro_message_text'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_message_text'
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_enable_random_message_text', array(
				'default'   		=> $llp_defaults['loftloader_pro_enable_random_message_text'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_checkbox'
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_random_message_text', array(
				'default'   		=> $llp_defaults['loftloader_pro_random_message_text'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_message_text',
				'dependency'		=> array(
					'loftloader_pro_enable_random_message_text' => array( 'value' => array( 'on' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_render_random_message_by_js', array(
				'default'   		=> $llp_defaults['loftloader_pro_render_random_message_by_js'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_checkbox',
				'dependency'		=> array(
					'loftloader_pro_enable_random_message_text' => array( 'value' => array( 'on' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_message_size', array(
				'default'   		=> $llp_defaults['loftloader_pro_message_size'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'absint'
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_message_position', array(
				'default'   		=> $llp_defaults['loftloader_pro_message_position'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice'
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_message_color', array(
				'default'   		=> $llp_defaults['loftloader_pro_message_color'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_colors'
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_message_enable_google_font', array(
				'default'   		=> $llp_defaults['loftloader_pro_message_enable_google_font'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice'
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_message_font_family', array(
				'default'   		=> $llp_defaults['loftloader_pro_message_font_family'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice',
				'dependency' 		=> array(
					'loftloader_pro_message_enable_google_font' => array( 'value' => array( 'on' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_message_font_weight', array(
				'default'   		=> $llp_defaults['loftloader_pro_message_font_weight'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice'
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_message_letter_spacing', array(
				'default'   		=> $llp_defaults['loftloader_pro_message_letter_spacing'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice'
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_message_line_height', array(
				'default'   		=> $llp_defaults['loftloader_pro_message_line_height'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_float'
			) ) );

			// Add Controls
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_message_text', array(
				'type' 				=> 'text',
				'label' 			=> esc_html__( 'Message', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_message',
				'settings' 			=> 'loftloader_pro_message_text',
				'description'		=> esc_html__( 'Supports the following simple HTML markups: <br>, <b>, <i>', 'loftloader-pro' ),
				'description_above' => false,
				'placeholder' 		=> esc_html__( 'Enter your message...', 'loftloader-pro' )
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_enable_random_message_text', array(
				'type' 				=> 'check',
				'label' 			=> esc_html__( 'Enable Random Message Feature', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_message',
				'settings' 			=> 'loftloader_pro_enable_random_message_text'
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_random_message_text', array(
				'type' 				=> 'textarea',
				'section' 			=> 'loftloader_pro_message',
				'settings' 			=> 'loftloader_pro_random_message_text',
				'description'		=> esc_html__( 'Enter messages. Separate each message by a line break. Supports the following simple HTML markups: <br>, <b>, <i>', 'loftloader-pro' ),
				'description_above' => false,
				'active_callback' 	=> 'llp_customize_control_active_cb',
				'placeholder' 		=> esc_html__( 'Enter your messages...', 'loftloader-pro' ),
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_render_random_message_by_js', array(
				'type' 				=> 'check',
				'label' 			=> esc_html__( 'Dynamically render messages on the frontend via JavaScript ', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_message',
				'settings' 			=> 'loftloader_pro_render_random_message_by_js',
				'description'		=> esc_html__( 'Please check this option if your site is using any cache/performance optimization plugin.', 'loftloader-pro' ),
				'description_above' => false,
				'active_callback' 	=> 'llp_customize_control_active_cb',
			) ) );

			$wp_customize->add_control( new LoftLoader_Customize_Horizontal_Radio_Control( $wp_customize, 'loftloader_pro_message_position', array(
				'type' 			=> 'btn',
				'label' 		=> esc_html__( 'Position', 'loftloader-pro' ),
				'show_label' 	=> true,
				'section' 		=> 'loftloader_pro_message',
				'settings' 		=> 'loftloader_pro_message_position',
				'choices' 		=> array(
					'top' 		=> array( 'label' => esc_html__( 'Top', 'loftloader-pro' ) ),
					'middle' 	=> array( 'label' => esc_html__( 'Before Progress', 'loftloader-pro' ) ),
					'bottom' 	=> array( 'label' => esc_html__( 'Bottom', 'loftloader-pro' ) )
				),
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_message_size', array(
				'type' 			=> 'slider',
				'label' 		=> esc_html__( 'Size', 'loftloader-pro' ),
				'after_text' 	=> 'px',
				'input_class' 	=> 'loftloader-message-size',
				'section'  		=> 'loftloader_pro_message',
				'settings' 		=> 'loftloader_pro_message_size',
				'input_attrs' 	=> array(
					'data-default' => 16,
					'data-min'     => 8,
					'data-max'     => 200,
					'data-step'    => 1
				)
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'loftloader_pro_message_color', array(
				'label'    => esc_html__( 'Pick Color', 'loftloader-pro' ),
				'section'  => 'loftloader_pro_message',
				'settings' => 'loftloader_pro_message_color'
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_message_enable_google_font', array(
				'type' 				=> 'select',
				'label' 			=> esc_html__( 'Font', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_message',
				'settings' 			=> 'loftloader_pro_message_enable_google_font',
				'choices' 			=> array(
					'on' 	=> esc_html__( 'Choose a Google Font ', 'loftloader-pro' ),
					'off'	=> esc_html__( 'Use site default font', 'loftloader-pro' )
				)
			) ) );
			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'loftloader_pro_message_font_family', array(
				'type' 		=> 'select',
				'label' 	=> esc_html__( 'Google Font', 'loftloader-pro' ),
				'choices' 	=> $llp_google_fonts,
				'section' 	=> 'loftloader_pro_message',
				'settings' 	=> 'loftloader_pro_message_font_family',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'loftloader_pro_message_font_weight', array(
				'type' 		=> 'select',
				'label' 	=> esc_html__( 'Font Weight', 'loftloader-pro' ),
				'section' 	=> 'loftloader_pro_message',
				'settings' 	=> 'loftloader_pro_message_font_weight',
				'choices' 	=> array(
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
			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'loftloader_pro_message_letter_spacing', array(
				'type' 		=> 'select',
				'label' 	=> esc_html__( 'Letter Spacing', 'loftloader-pro' ),
				'section' 	=> 'loftloader_pro_message',
				'settings' 	=> 'loftloader_pro_message_letter_spacing',
				'choices' 	=> array(
					'0' 	=> 0,
					'0.1em' => esc_html__( '0.1em', 'loftloader-pro' ),
					'0.2em' => esc_html__( '0.2em', 'loftloader-pro' ),
					'0.3em' => esc_html__( '0.3em', 'loftloader-pro' ),
					'0.4em' => esc_html__( '0.4em', 'loftloader-pro' )
				)
			) ) );
			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'loftloader_pro_message_line_height', array(
				'type' 		=> 'select',
				'label' 	=> esc_html__( 'Line Height', 'loftloader-pro' ),
				'section' 	=> 'loftloader_pro_message',
				'settings' 	=> 'loftloader_pro_message_line_height',
				'choices' 	=> array(
					'1.0'	=> esc_html__( '1.0', 'loftloader-pro' ),
					'1.1' 	=> esc_html__( '1.1', 'loftloader-pro' ),
					'1.2' 	=> esc_html__( '1.2', 'loftloader-pro' ),
					'1.3'	=> esc_html__( '1.3', 'loftloader-pro' ),
					'1.4' 	=> esc_html__( '1.4', 'loftloader-pro' ),
					'1.5' 	=> esc_html__( '1.5', 'loftloader-pro' ),
					'1.6' 	=> esc_html__( '1.6', 'loftloader-pro' ),
					'1.7'	=> esc_html__( '1.7', 'loftloader-pro' ),
					'1.8' 	=> esc_html__( '1.8', 'loftloader-pro' ),
					'1.9' 	=> esc_html__( '1.9', 'loftloader-pro' ),
					'2.0' 	=> esc_html__( '2.0', 'loftloader-pro' ),
					'2.1'	=> esc_html__( '2.1', 'loftloader-pro' ),
					'2.2' 	=> esc_html__( '2.2', 'loftloader-pro' ),
					'2.3' 	=> esc_html__( '2.3', 'loftloader-pro' ),
					'2.4' 	=> esc_html__( '2.4', 'loftloader-pro' ),
					'2.5'	=> esc_html__( '2.5', 'loftloader-pro' )
				)
			) ) );
		}
	}
	new LoftLoader_Pro_Section_Message();
}
