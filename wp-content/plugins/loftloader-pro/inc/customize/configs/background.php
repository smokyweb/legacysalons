<?php
/**
* Load loftloader pro section background related functions
*
* @since version 1.0.6
*/

if ( ! class_exists( 'LoftLoader_Pro_Section_Background' ) ) {
	class LoftLoader_Pro_Section_Background extends LoftLoader_Pro_Customize_Base {
		public function register_customize_elements( $wp_customize ) {
			global $llp_defaults;

			// Add Section
			$wp_customize->add_section( new LoftLoader_Customize_Section( $wp_customize, 'loftloader_pro_background', array(
				'title'       => esc_html__( 'Background', 'loftloader-pro' ),
				'description' => '',
				'priority'    => 40
			) ) );

			// Add settings
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_bgfilltype', array(
				'default'   		=> $llp_defaults['loftloader_bgfilltype'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice'
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_bg_color', array(
				'default'   		=> $llp_defaults['loftloader_pro_bg_color'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_colors'
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_bgopacity', array(
				'default'   		=> $llp_defaults['loftloader_bgopacity'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'absint'
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_bg_animation', array(
				'default'   		=> $llp_defaults['loftloader_pro_bg_animation'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice'
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_bg_image', array(
				'default'   		=> $llp_defaults['loftloader_pro_bg_image'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'esc_url_raw',
				'dependency' 		=> array(
					'loftloader_bgfilltype' => array( 'value' => array( 'image' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_mobile_bg_image', array(
				'default'   		=> $llp_defaults['loftloader_pro_mobile_bg_image'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'esc_url_raw',
				'dependency' 		=> array(
					'loftloader_bgfilltype' => array( 'value' => array( 'image' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_bg_image_repeat', array(
				'default'   		=> $llp_defaults['loftloader_pro_bg_image_repeat'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice',
				'dependency' 		=> array(
					'loftloader_bgfilltype' 	=> array( 'value' => array( 'image' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_bg_image_size', array(
				'default'   		=> $llp_defaults['loftloader_pro_bg_image_size'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice',
				'dependency' 		=> array(
					'loftloader_bgfilltype' 			=> array( 'value' => array( 'image' ) ),
					'loftloader_pro_bg_image_repeat'	=> array( 'value' => array( 'tile'), 'operator' => 'not in' )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_bg_gradient', array(
				'default'   		=> $llp_defaults['loftloader_pro_bg_gradient'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_checkbox',
				'dependency' 		=> array(
					'loftloader_bgfilltype' => array( 'value' => array( 'solid' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_bg_gradient_start_color', array(
				'default'   		=> $llp_defaults['loftloader_pro_bg_gradient_start_color'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_colors',
				'dependency' 		=> array(
					'loftloader_bgfilltype' 		=> array( 'value' => array( 'solid' ) ),
					'loftloader_pro_bg_gradient' 	=> array( 'value' => array( 'on' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_bg_gradient_end_color', array(
				'default'   		=> $llp_defaults['loftloader_pro_bg_gradient_end_color'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_colors',
				'dependency' 		=> array(
					'loftloader_bgfilltype' 		=> array( 'value' => array( 'solid' ) ),
					'loftloader_pro_bg_gradient' 	=> array( 'value' => array( 'on' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_bg_gradient_angel', array(
				'default'   		=> $llp_defaults['loftloader_pro_bg_gradient_angel'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'absint',
				'dependency' 		=> array(
					'loftloader_bgfilltype' 		=> array( 'value' => array( 'solid' ) ),
					'loftloader_pro_bg_gradient' 	=> array( 'value' => array( 'on' ) )
				)
			) ) );

			// Controls for section background
			$wp_customize->add_control( new LoftLoader_Customize_Horizontal_Radio_Control( $wp_customize, 'loftloader_bgfilltype', array(
				'type' 		=> 'radio',
				'label' 	=> esc_html__( 'Fill Type', 'loftloader-pro' ),
				'section' 	=> 'loftloader_pro_background',
				'settings' 	=> 'loftloader_bgfilltype',
				'choices' 	=> array(
					'solid' => array( 'label' => esc_html__( 'Solid', 'loftloader-pro' ), 'id' => 'loftloader_bgsolid' ),
					'image' => array( 'label' => esc_html__( 'Image', 'loftloader-pro' ), 'id' => 'loftloader_bgimage' ),
					'none' 	=> array( 'label' => esc_html__( 'None', 'loftloader-pro' ), 'id' => 'loftloader_bgnocolor' )
				)
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'loftloader_pro_bg_color', array(
				'label'    => esc_html__( 'Pick Color', 'loftloader-pro' ),
				'section'  => 'loftloader_pro_background',
				'settings' => 'loftloader_pro_bg_color'
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_bgopacity', array(
				'type'     		=> 'slider',
				'label'    		=> esc_html__( 'Opacity', 'loftloader-pro' ),
				'input_class' 	=> 'loaderbgopacity',
				'section'  		=> 'loftloader_pro_background',
				'settings' 		=> 'loftloader_bgopacity',
				'input_attrs' 	=> array(
					'data-default' 	=> 100,
					'data-min'	 	=> 0,
					'data-max'		=> 100,
					'data-step'		=> 5
				)
			) ) );
			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'loftloader_pro_bg_animation', array(
				'type' 			=> 'select',
				'label' 		=> esc_html__( 'Ending Animation', 'loftloader-pro' ),
				'description' 	=> esc_html__( 'Hover on preview area to see the result.', 'loftloader-pro' ),
				'section' 		=> 'loftloader_pro_background',
				'settings' 		=> 'loftloader_pro_bg_animation',
				'choices' 		=> array(
					'fade' 					=> esc_html__( 'Fade', 'loftloader-pro' ),
					'split-h' 				=> esc_html__( 'Slide Left & Right', 'loftloader-pro' ),
					'left' 					=> esc_html__( 'Slide to Left', 'loftloader-pro' ),
					'right' 				=> esc_html__( 'Slide to Right', 'loftloader-pro' ),
					'up' 					=> esc_html__( 'Slide Up', 'loftloader-pro' ),
					'down'					=> esc_html__( 'Slide Down', 'loftloader-pro' ),
					'split-v' 				=> esc_html__( 'Slide Up & Down', 'loftloader-pro' ),
					'shrink-fade' 			=> esc_html__( 'Shrink & Fade', 'loftloader-pro' ),
					'split-reveal-v' 		=> esc_html__( 'Split & Reveal Vertically', 'loftloader-pro' ),
					'split-reveal-h' 		=> esc_html__( 'Split & Reveal Horizontally', 'loftloader-pro' ),
					'split-diagonally-v' 	=> esc_html__( 'Split Diagonally - Vertically', 'loftloader-pro' ),
					'split-diagonally-h' 	=> esc_html__( 'Split Diagonally - Horizontally', 'loftloader-pro' ),
					'no-animation' 			=> esc_html__( 'No Animation', 'loftloader-pro' )
				)
			) ) );
			$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'loftloader_pro_bg_image', array(
				'type' 				=> 'image',
				'label' 			=> esc_html__( 'Upload Image', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_background',
				'settings' 			=> 'loftloader_pro_bg_image',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'loftloader_pro_mobile_bg_image', array(
				'type' 				=> 'image',
				'label' 			=> esc_html__( 'Upload Mobile Background Image (optional)', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_background',
				'settings' 			=> 'loftloader_pro_mobile_bg_image',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_bg_image_repeat', array(
				'type' 				=> 'radio',
				'label' 			=> esc_html__( 'Background Repeat', 'loftloader-pro' ),
				'description' 		=> esc_html__( 'Please choose "No Repeat" if use a full size background image; choose "Tile" if use a small repeating pattern.', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_background',
				'settings' 			=> 'loftloader_pro_bg_image_repeat',
				'active_callback' 	=> 'llp_customize_control_active_cb',
				'choices' 			=> array(
					'' 		=> esc_html__( 'No Repeat', 'loftloader-pro' ),
					'tile' 	=> esc_html__( 'Tile', 'loftloader-pro' )
				)
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_bg_image_size', array(
				'type' 				=> 'radio',
				'label' 			=> esc_html__( 'Background Size', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_background',
				'settings' 			=> 'loftloader_pro_bg_image_size',
				'active_callback' 	=> 'llp_customize_control_active_cb',
				'choices' 			=> array(
					'cover' 	=> esc_html__( 'Cover', 'loftloader-pro' ),
					'contain' 	=> esc_html__( 'Contain', 'loftloader-pro' )
				)
			) ) );

			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_bg_gradient', array(
				'type' 				=> 'check',
				'label' 			=> esc_html__( 'Enable Gradient', 'loftloader-pro' ),
				'choices' 			=> array( 'on' => '' ),
				'section' 			=> 'loftloader_pro_background',
				'settings' 			=> 'loftloader_pro_bg_gradient',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'loftloader_pro_bg_gradient_start_color', array(
				'label'    			=> esc_html__( 'Start Color', 'loftloader-pro' ),
				'section'  			=> 'loftloader_pro_background',
				'settings' 			=> 'loftloader_pro_bg_gradient_start_color',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'loftloader_pro_bg_gradient_end_color', array(
				'label'    			=> esc_html__( 'End Color', 'loftloader-pro' ),
				'section'  			=> 'loftloader_pro_background',
				'settings' 			=> 'loftloader_pro_bg_gradient_end_color',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_bg_gradient_angel', array(
				'type'     			=> 'slider',
				'label'    			=> esc_html__( 'Angel', 'loftloader-pro' ),
				'after_text' 		=> '&deg;',
				'section'  			=> 'loftloader_pro_background',
				'settings' 			=> 'loftloader_pro_bg_gradient_angel',
				'active_callback' 	=> 'llp_customize_control_active_cb',
				'input_attrs' 		=> array(
					'data-default' => 0,
					'data-min'     => 0,
					'data-max'     => 360,
					'data-step'    => 1
				)
			) ) );
		}
	}
	new LoftLoader_Pro_Section_Background();
}
