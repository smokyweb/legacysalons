<?php
/**
* Load loftloader pro section loader related functions
*
* @since version 1.0.6
*/
if ( ! class_exists( 'LoftLoader_Pro_Section_Loader' ) ) {
	class LoftLoader_Pro_Section_Loader extends LoftLoader_Pro_Customize_Base {
		public function register_customize_elements( $wp_customize ) {	
			global $llp_defaults;

			// Add Section
			$wp_customize->add_section( new LoftLoader_Customize_Section( $wp_customize, 'loftloader_pro_animation', array(
				'title' 	=> esc_html__( 'Loader', 'loftloader-pro' ),
				'priority'	=> 50
			) ) );

			// Loader settings
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_animation', array(
				'default'   		=> $llp_defaults['loftloader_animation'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice'
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_animation_color', array(
				'default'   		=> $llp_defaults['loftloader_pro_animation_color'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_colors',
				'dependency' 		=> array(
					'loftloader_animation' => array( 'value' => array( 
						'none', 
						'sun', 
						'luminous', 
						'wave', 
						'square', 
						'frame', 
						'ducks', 
						'crystal', 
						'circlefilling', 
						'waterfilling', 
						'petals', 
						'beating', 
						'incomplete-ring',
					) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_animation_frame_width', array(
				'default'   		=> $llp_defaults['loftloader_pro_animation_frame_width'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'absint',
				'dependency' 		=> array(
					'loftloader_animation' => array( 'value' => array( 'frame' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_animation_frame_height', array(
				'default'   		=> $llp_defaults['loftloader_pro_animation_frame_height'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'absint',
				'dependency' 		=> array(
					'loftloader_animation' => array( 'value' => array( 'frame' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_animation_frame_border_width', array(
				'default'   		=> $llp_defaults['loftloader_pro_animation_frame_border_width'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'absint',
				'dependency' 		=> array(
					'loftloader_animation' => array( 'value' => array( 'frame' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_custom_loader', array(
				'default'   		=> $llp_defaults['loftloader_pro_custom_loader'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_html',
				'dependency' 		=> array(
					'loftloader_animation' => array( 'value' => array( 'custom-loader' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_animation_crossing_left_color', array(
				'default'   		=> $llp_defaults['loftloader_pro_animation_crossing_left_color'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_colors',
				'dependency' 		=> array(
					'loftloader_animation' => array( 'value' => array( 'crossing' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_animation_crossing_right_color', array(
				'default'   		=> $llp_defaults['loftloader_pro_animation_crossing_right_color'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_colors',
				'dependency' 		=> array(
					'loftloader_animation' => array( 'value' => array( 'crossing' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_animation_rainbow_outer_color', array(
				'default'   		=> $llp_defaults['loftloader_pro_animation_rainbow_outer_color'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_colors',
				'dependency' 		=> array(
					'loftloader_animation' => array( 'value' => array( 'rainbow' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_animation_rainbow_middle_color', array(
				'default'  	 		=> $llp_defaults['loftloader_pro_animation_rainbow_middle_color'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_colors',
				'dependency' 		=> array(
					'loftloader_animation' => array( 'value' => array( 'rainbow' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_animation_rainbow_inner_color', array(
				'default'   		=> $llp_defaults['loftloader_pro_animation_rainbow_inner_color'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_colors',
				'dependency' 		=> array(
					'loftloader_animation' => array( 'value' => array( 'rainbow' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_customimg', array(
				'default'   		=> $llp_defaults['loftloader_customimg'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'esc_url_raw',
				'dependency' 		=> array(
					'loftloader_animation' => array( 'value' => array( 'frame', 'imgloading', 'imgrotating', 'imgbouncing', 'imgstatic', 'imgfading' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_imgwidth', array(
				'default'   		=> $llp_defaults['loftloader_imgwidth'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'absint',
				'dependency' 		=> array(
					'loftloader_animation' => array( 'value' => array( 'imgloading', 'imgrotating', 'imgbouncing', 'imgstatic', 'imgfading' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_responsive_design_max_width', array(
				'default'   		=> $llp_defaults['loftloader_responsive_design_max_width'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'absint',
				'dependency' 		=> array(
					'loftloader_animation' => array( 'value' => array( 'imgloading', 'imgrotating', 'imgbouncing', 'imgstatic', 'imgfading' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_looping', array(
				'default'   		=> $llp_defaults['loftloader_looping'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice',
				'dependency' 		=> array(
					'loftloader_animation' => array( 'value' => array( 'imgloading', 'rainbow', 'circlefilling', 'waterfilling', 'petals' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_loaddirection', array(
				'default'   		=> $llp_defaults['loftloader_loaddirection'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice',
				'dependency' 		=> array(
					'loftloader_animation' => array( 'value' => array( 'imgloading' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting($wp_customize, 'loftloader_rotatedirection', array(
				'default'   => $llp_defaults['loftloader_rotatedirection'],
				'transport' => 'refresh',
				'type' => 'option',
				'sanitize_callback' => 'llp_sanitize_choice',
				'dependency' => array(
					'loftloader_animation' => array('value' => array('imgrotating'))
				)
			)));
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_rotation_2d', array(
				'default'   => $llp_defaults['loftloader_rotation_2d'],
				'transport' => 'refresh',
				'type' 		=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice',
				'dependency' => array(
					'loftloader_animation' 			=> array( 'value' => array( 'imgrotating' ) ),
					'loftloader_rotatedirection' 	=> array( 'value' => array( '2d' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_rotate_curve', array(
				'default'   => $llp_defaults['loftloader_rotate_curve'],
				'transport' => 'postMessage',
				'type' => 'option',
				'sanitize_callback' => 'llp_sanitize_choice',
				'dependency' => array(
					'loftloader_animation' => array( 'value' => array( 'imgrotating' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_bouncerolling', array(
				'default'   		=> $llp_defaults['loftloader_bouncerolling'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_checkbox',
				'dependency' 		=> array(
					'loftloader_animation' => array( 'value' => array( 'imgbouncing' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_blendmode', array(
				'default'   		=> $llp_defaults['loftloader_blendmode'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice',
				'dependency' 		=> array(
					'loftloader_animation' => array( 'value' => array( 'crossing' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_custom_image_loading_vertical_direction', array(
				'default'   		=> $llp_defaults['loftloader_custom_image_loading_vertical_direction'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice',
				'dependency' 		=> array(
					'loftloader_animation' 		=> array( 'value' => array( 'imgloading' ) ),
					'loftloader_loaddirection' 	=> array( 'value' => array( 'vertical' ) )
				)
			) ) );

			// Controls for section loader
			$wp_customize->add_control( new LoftLoader_Customize_Animation_Types_Control( $wp_customize, 'loftloader_animation', array(
				'type' 			=> 'radio',
				'label' 		=> esc_html__( 'Loader Animation', 'loftloader-pro' ),
				'description' 	=> sprintf(
					/* translators: 1: html tag start. 2: html tag end */
					esc_html__( 'Some support custom image. All animations are looping forever by default. Some support %1$sLoop Once%2$s, working best with progress bar or percentage indicator.', 'loftloader-pro' ), 
					'<strong>', 
					'</strong>'
				),
				'choices' 		=> array(
					'none' 				=> array( 'label' => esc_html__( 'No Animation', 'loftloader-pro' ) ),
					'sun' 				=> array( 'label' => esc_html__( 'Spinning Sun', 'loftloader-pro' ) ),
					'luminous' 			=> array( 'label' => esc_html__( 'Luminous Circles', 'loftloader-pro' ) ),
					'wave' 				=> array( 'label' => esc_html__( 'Wave', 'loftloader-pro' ) ),
					'square' 			=> array( 'label' => esc_html__( 'Spinning Square', 'loftloader-pro' ) ),
					'frame' 			=> array( 'label' => esc_html__( 'Drawing Frame', 'loftloader-pro' ) ),
					'imgloading' 		=> array( 'label' => esc_html__( 'Custom Image Loading', 'loftloader-pro' ) ),
					'imgrotating' 		=> array( 'label' => esc_html__( 'Custom Image Rotating', 'loftloader-pro' ) ),
					'imgbouncing' 		=> array( 'label' => esc_html__( 'Custom Image Bouncing', 'loftloader-pro' ) ),
					'crossing' 			=> array( 'label' => esc_html__( 'Crossing Circles', 'loftloader-pro' ) ),
					'ducks' 			=> array( 'label' => esc_html__( 'Ducks', 'loftloader-pro' ) ),
					'rainbow' 			=> array( 'label' => esc_html__( 'Rainbow', 'loftloader-pro' ) ),
					'circlefilling' 	=> array( 'label' => esc_html__( 'Circle Filling', 'loftloader-pro' ) ),
					'waterfilling' 		=> array( 'label' => esc_html__( 'Water Filling', 'loftloader-pro' ) ),
					'crystal' 			=> array( 'label' => esc_html__( 'Crystal', 'loftloader-pro' ) ),
					'petals' 			=> array( 'label' => esc_html__( 'Petals', 'loftloader-pro' ) ),
					'imgstatic' 		=> array( 'label' => esc_html__( 'Static Image', 'loftloader-pro' ) ),
					'beating' 			=> array( 'label' => esc_html__( 'Beating', 'loftloader-pro' ) ),
					'imgfading' 		=> array( 'label' => esc_html__( 'Custom Image Fading', 'loftloader-pro' ) ),
					'incomplete-ring' 	=> array( 'label' => esc_html__( 'Incomplete Ring', 'loftloader-pro' ) ),
					'custom-loader'		=> array( 'label' => esc_html__( 'Custom HTML Loader', 'loftloader-pro' ) )
				),
				'section' 		=> 'loftloader_pro_animation',
				'settings' 		=> 'loftloader_animation'
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_custom_loader', array(
				'type'				=> 'textarea',
				'label'    			=> esc_html__( 'Add the code of your custom HTML loader', 'loftloader-pro' ),
				'description'		=> esc_html__( 'Please note: It is NOT recommended enabling the "Smooth Page Transition" feature when using a custom HTML loader.', 'loftloader-pro' ),
				'description_above'	=> false,
				'input_attrs'		=> array( 'rows' => 10 ),
				'section'  			=> 'loftloader_pro_animation',
				'settings' 			=> 'loftloader_pro_custom_loader',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'loftloader_pro_animation_color', array(
				'label'    			=> esc_html__( 'Pick Color', 'loftloader-pro' ),
				'section'  			=> 'loftloader_pro_animation',
				'settings' 			=> 'loftloader_pro_animation_color',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Number_Text_Control( $wp_customize, 'loftloader_pro_animation_frame_width', array(
				'type' 				=> 'number',
				'label' 			=> esc_html__( 'Frame Width', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_animation',
				'settings' 			=> 'loftloader_pro_animation_frame_width',
				'after_text' 		=> 'px',
				'input_class' 		=> 'loaderframewidth',
				'input_wrap_class' 	=> 'frame-width',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Number_Text_Control( $wp_customize, 'loftloader_pro_animation_frame_height', array(
				'type' 				=> 'number',
				'label' 			=> esc_html__( 'Frame Height', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_animation',
				'settings' 			=> 'loftloader_pro_animation_frame_height',
				'after_text' 		=> 'px',
				'input_class' 		=> 'loaderframeheight',
				'input_wrap_class' 	=> 'frame-height',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Number_Text_Control( $wp_customize, 'loftloader_pro_animation_frame_border_width', array(
				'type' 				=> 'number',
				'label' 			=> esc_html__( 'Frame Border', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_animation',
				'settings' 			=> 'loftloader_pro_animation_frame_border_width',
				'after_text' 		=> 'px',
				'input_class' 		=> 'loaderframeborderwidth',
				'input_wrap_class' 	=> 'border-width',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'loftloader_pro_animation_crossing_left_color', array(
				'label'    			=> esc_html__( 'Pick Left Color', 'loftloader-pro' ),
				'section'  			=> 'loftloader_pro_animation',
				'settings'			=> 'loftloader_pro_animation_crossing_left_color',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'loftloader_pro_animation_crossing_right_color', array(
				'label'    			=> esc_html__( 'Pick Right Color', 'loftloader-pro' ),
				'section'  			=> 'loftloader_pro_animation',
				'settings' 			=> 'loftloader_pro_animation_crossing_right_color',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'loftloader_pro_animation_rainbow_outer_color', array(
				'label'    			=> esc_html__( 'Pick Outer Color', 'loftloader-pro' ),
				'section'  			=> 'loftloader_pro_animation',
				'settings' 			=> 'loftloader_pro_animation_rainbow_outer_color',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'loftloader_pro_animation_rainbow_middle_color', array(
				'label'    			=> esc_html__( 'Pick Middle Color', 'loftloader-pro' ),
				'section'  			=> 'loftloader_pro_animation',
				'settings' 			=> 'loftloader_pro_animation_rainbow_middle_color',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'loftloader_pro_animation_rainbow_inner_color', array(
				'label'    			=> esc_html__( 'Pick Inner Color', 'loftloader-pro' ),
				'section'  			=> 'loftloader_pro_animation',
				'settings' 			=> 'loftloader_pro_animation_rainbow_inner_color',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			)));
			$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'loftloader_customimg', array(
				'type' 				=> 'image',
				'label' 			=> esc_html__( 'Upload Image', 'loftloader-pro' ),
				'description' 		=> '',
				'section' 			=> 'loftloader_pro_animation',
				'settings' 			=> 'loftloader_customimg',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Number_Text_Control( $wp_customize, 'loftloader_imgwidth', array(
				'type' 				=> 'number',
				'label' 			=> esc_html__( 'Image Width', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_animation',
				'settings' 			=> 'loftloader_imgwidth',
				'after_text' 		=> 'px',
				'input_class' 		=> 'loaderimgwidth',
				'input_wrap_class' 	=> 'imgwidth',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Number_Text_Control( $wp_customize, 'loftloader_responsive_design_max_width', array(
				'type' 				=> 'number',
				'label' 			=> esc_html__( 'Max Width for Responsive Design', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_animation',
				'settings' 			=> 'loftloader_responsive_design_max_width',
				'after_text' 		=> '%',
				'input_attrs'		=> array( 'min' => 1, 'max' => 100 ),
				'input_class' 		=> 'loaderimgwidth',
				'input_wrap_class' 	=> 'imgwidth',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Horizontal_Radio_Control( $wp_customize, 'loftloader_looping', array(
				'type' 				=> 'btn',
				'label'		 		=> esc_html__( 'Looping', 'loftloader-pro' ),
				'description' 		=> esc_html__( 'Hover on preview area and wait for 3 seconds to see the result.', 'loftloader-pro' ),
				'show_label' 		=> true,
				'description_above' => false,
				'hide' 				=> 'forever',
				'section' 			=> 'loftloader_pro_animation',
				'settings' 			=> 'loftloader_looping',
				'wrap_id' 			=> 'loftloader_option_looping',
				'active_callback' 	=> 'llp_customize_control_active_cb',
				'choices' 			=> array(
					'forever' 	=> array( 'label' => esc_html__( 'Forever', 'loftloader-pro' ) ),
					'once' 		=> array( 'label' => esc_html__( 'Once', 'loftloader-pro' ) ),
				)
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Horizontal_Radio_Control( $wp_customize, 'loftloader_loaddirection', array(
				'type' 				=> 'btn',
				'label' 			=> esc_html__( 'Direction', 'loftloader-pro' ),
				'show_label' 		=> true,
				'section' 			=> 'loftloader_pro_animation',
				'settings' 			=> 'loftloader_loaddirection',
				'wrap_id' 			=> 'loftloader_option_loaddirection',
				'active_callback' 	=> 'llp_customize_control_active_cb',
				'choices' 			=> array(
					'horizontal' 	=> array( 'label' => esc_html__( 'Horizontal', 'loftloader-pro' ) ),
					'vertical' 		=> array( 'label' => esc_html__( 'Vertical', 'loftloader-pro' ) ),
				)
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Horizontal_Radio_Control( $wp_customize, 'loftloader_rotatedirection', array(
				'type' 				=> 'btn',
				'label' 			=> esc_html__( 'Direction', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_animation',
				'settings' 			=> 'loftloader_rotatedirection',
				'wrap_id' 			=> 'loftloader_option_rotatedirection',
				'active_callback' 	=> 'llp_customize_control_active_cb',
				'choices' 			=> array(
					'2d' => 	array( 'label' => esc_html__( '2D', 'loftloader-pro' ), 'id' => 'loftloader_2d' ),
					'3d-y' => 	array( 'label' => esc_html__( '3D Y Axis', 'loftloader-pro' ), 'id' => 'loftloader_3d_y' ),
					'3d-x' => 	array( 'label' => esc_html__( '3D X Axis', 'loftloader-pro' ), 'id' => 'loftloader_3d_x' )
				)
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_rotation_2d', array(
				'type' 				=> 'select',
				'label' 			=> esc_html__( 'Rotation', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_animation',
				'settings' 			=> 'loftloader_rotation_2d',
				'active_callback' 	=> 'llp_customize_control_active_cb',
				'choices' 			=> array(
					'' 					=> esc_html__( 'Clockwise', 'loftloader-pro' ),
					'counterclockwise' 	=> esc_html__( 'Counterclockwise', 'loftloader-pro' )
				)
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_rotate_curve', array(
				'type' 				=> 'select',
				'label'				=> esc_html__('Speed Curve', 'loftloader-pro'),
				'section' 			=> 'loftloader_pro_animation',
				'settings' 			=> 'loftloader_rotate_curve',
				'active_callback' 	=> 'llp_customize_control_active_cb',
				'choices' 			=> array(
					'' 			=> esc_html__( 'Linear', 'loftloader-pro' ),
					'ease-back' => esc_html__( 'Ease Out & Back', 'loftloader-pro' )
				)
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_bouncerolling', array(
				'type' 				=> 'check',
				'label' 			=> esc_html__( 'Also Rolling?', 'loftloader-pro' ),
				'choices' 			=> array( 'on' => '' ),
				'section' 			=> 'loftloader_pro_animation',
				'settings' 			=> 'loftloader_bouncerolling',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Horizontal_Radio_Control( $wp_customize, 'loftloader_blendmode', array(
				'type' 				=> 'btn',
				'label' 			=> esc_html__( 'Blend Mode', 'loftloader-pro' ),
				'show_label' 		=> true,
				'section' 			=> 'loftloader_pro_animation',
				'settings' 			=> 'loftloader_blendmode',
				'wrap_id' 			=> 'loftloader_option_blendmode',
				'active_callback' 	=> 'llp_customize_control_active_cb',
				'choices' 			=> array(
					'none' 		=> array( 'label' => esc_html__( 'None', 'loftloader-pro' ), 'class' => 'no-blend-mode' ),
					'lighten' 	=> array( 'label' => esc_html__( 'Lighten', 'loftloader-pro' ) ),
					'darken' 	=> array( 'label' => esc_html__( 'Darken', 'loftloader-pro' ) ),
				)
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_custom_image_loading_vertical_direction', array(
				'type' 				=> 'select',
				'label' 			=> esc_html__( 'Vertical Direction', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_animation',
				'settings' 			=> 'loftloader_custom_image_loading_vertical_direction',
				'active_callback' 	=> 'llp_customize_control_active_cb',
				'choices' 			=> array(
					'' 				=> esc_html__( 'Bottom to Top', 'loftloader-pro' ),
					'top-to-bottom' => esc_html__( 'Top to Bottom', 'loftloader-pro' )
				)
			) ) );
		}
	}
	new LoftLoader_Pro_Section_Loader();
}