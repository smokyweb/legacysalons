<?php
/**
* Load loftloader pro main switcher related functions
*
* @since version 1.0.6
*/

if ( ! class_exists( 'LoftLoader_Pro_Section_Main' ) ) {
	class LoftLoader_Pro_Section_Main extends LoftLoader_Pro_Customize_Base {
		public function register_customize_elements( $wp_customize ) {	
			global $llp_defaults;

			// Add Section
			$wp_customize->add_section( new LoftLoader_Customize_Switch_Section( $wp_customize, 'loftloader_pro_switch', array(
				'title' 	=> esc_html__( 'Enable LoftLoader', 'loftloader-pro' ),
				'priority'	=> 1
			) ) );

			// Add Setting
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_main_switch', array(
				'default'   		=> $llp_defaults['loftloader_pro_main_switch'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_checkbox'
			) ) );

			// Add Control
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_main_switch_control', array(
				'type' 		=> 'check',
				'label' 	=> esc_html__( 'Enable LoftLoader', 'loftloader-pro' ),
				'choices' 	=> array( 'on' => '' ),
				'section' 	=> 'loftloader_pro_switch',
				'settings' 	=> 'loftloader_pro_main_switch'
			) ) );
		}
	}
	new LoftLoader_Pro_Section_Main();
}