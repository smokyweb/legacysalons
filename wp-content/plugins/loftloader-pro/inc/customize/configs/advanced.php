<?php
/**
* Load loftloader pro section advanced related functions
*
* @since version 1.0.6
*/
if ( ! class_exists( 'LoftLoader_Pro_Section_Advanced' ) ) {
	class LoftLoader_Pro_Section_Advanced extends LoftLoader_Pro_Customize_Base {
		public function register_customize_elements( $wp_customize ) {
			global $llp_defaults;

			// Add Panel and Sections
			$wp_customize->add_panel( new WP_Customize_Panel( $wp_customize, 'loftloader_pro_advanced_panel', array(
				'title'       => esc_html__( 'Advanced', 'loftloader-pro' ),
				'description' => '',
				'priority'    => 90
			) ) );

			$this->add_section_save_style( $wp_customize );
			$this->add_section_javascript_loading( $wp_customize );
			$this->add_section_inject_loader_html( $wp_customize );
			$this->add_section_any_page_extension( $wp_customize );
			$this->add_section_custom_css( $wp_customize );
			$this->add_section_exclude_url_parameter( $wp_customize );
		}
		public function add_section_save_style( $wp_customize ) {
			global $llp_defaults;
 			$wp_customize->add_section( new LoftLoader_Customize_Section( $wp_customize, 'loftloader_pro_custom_styles', array(
				'title' => esc_html__( 'Where to Save Styles', 'loftloader-pro' ),
				'panel' => 'loftloader_pro_advanced_panel'
			) ) );

			// Add Settings
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_css_in_file', array(
				'default'   		=> $llp_defaults['loftloader_pro_css_in_file'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice'
			) ) );

			// Add Controls
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_css_in_file', array(
				'type' 				=> 'radio',
				'label' 			=> esc_html__( 'Save customize styles', 'loftloader-pro' ),
				'description_above' => false,
				'hide' 				=> 'inline',
				'description' 		=> esc_html__( 'Please make sure your WordPress has write permission to modify files.', 'loftloader-pro' ),
				'choices' 			=> array(
					'inline' 	=> esc_html__( 'As inline styles in <head>', 'loftloader-pro' ),
					'file' 		=> esc_html__( 'As an external .css file', 'loftloader-pro' )
				),
				'section' 	=> 'loftloader_pro_custom_styles',
				'settings' 	=> 'loftloader_pro_css_in_file'
			) ) );
		}
		public function add_section_javascript_loading( $wp_customize ) {
			global $llp_defaults;
			$wp_customize->add_section( new LoftLoader_Customize_Section( $wp_customize, 'loftloader_pro_scripts_loading', array(
				'title' => esc_html__( 'JavaScript Loading', 'loftloader-pro' ),
				'panel' => 'loftloader_pro_advanced_panel'
			) ) );

			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_scripts_loading_priority', array(
				'default'   		=> $llp_defaults['loftloader_pro_scripts_loading_priority'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice'
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_scripts_loading_priority_description', array(
				'default'   		=> '',
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_checkbox',
				'dependency' 		=> array(
					'loftloader_pro_scripts_loading_priority' => array( 'value' => array( 'high' ) )
				)
			) ) );

			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_scripts_loading_priority', array(
				'type' 		=> 'radio',
				'label'	 	=> esc_html__( 'JavaScript Loading Priority', 'loftloader-pro' ),
				'section' 	=> 'loftloader_pro_scripts_loading',
				'settings' 	=> 'loftloader_pro_scripts_loading_priority',
				'choices' 	=> array(
					'normal' => esc_html__( 'Default', 'loftloader-pro' ),
					'high' => esc_html__( 'Immediately after the preloader HTML', 'loftloader-pro' ),
					'low' => esc_html__( 'At the end of the site footer', 'loftloader-pro' )
				)
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_scripts_loading_priority_description', array(
				'type' 			=> 'description',
				'description' 	=> esc_html__( 'Note: This feature requires WordPress5.2 and above. The theme you use must also support the newly added action "wp_body_open" in WordPress5.2. Otherwise, even if checked, it will not take effect.', 'loftloader-pro' ),
				'section' 		=> 'loftloader_pro_scripts_loading',
				'settings' 		=> 'loftloader_pro_scripts_loading_priority_description',
				'active_callback'	=> 'llp_customize_control_active_cb',
			) ) );
		}
		public function add_section_inject_loader_html( $wp_customize ) {
			global $llp_defaults;
			$wp_customize->add_section( new LoftLoader_Customize_Section( $wp_customize, 'loftloader_pro_section_inject_html_early', array(
				'title' => esc_html__( 'Inject Loader HTML Early', 'loftloader-pro' ),
				'panel' => 'loftloader_pro_advanced_panel'
			) ) );

			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_inject_html_in_action_init', array(
				'default'   		=> $llp_defaults['loftloader_pro_inject_html_in_action_init'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_checkbox'
			) ) );

			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_inject_html_in_action_init', array(
				'type' 				=> 'check',
				'label'	 			=> esc_html__( 'Inject Loader HTML in WordPress Core Action "init"', 'loftloader-pro' ),
				'description_above' => false,
				'description'		=> esc_html__( 'Please try this option if LoftLoader Pro has compatible issue with other plugins on your website.', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_section_inject_html_early',
				'settings' 			=> 'loftloader_pro_inject_html_in_action_init'
			) ) );
		}
		public function add_section_any_page_extension( $wp_customize ) {
			global $llp_defaults;
			$wp_customize->add_section( new LoftLoader_Customize_Section( $wp_customize, 'loftloader_pro_any_page_extension', array(
				'title' => esc_html__( 'Any Page Extension', 'loftloader-pro' ),
				'panel' => 'loftloader_pro_advanced_panel'
			) ) );

			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_enable_any_page', array(
				'default'   		=> $llp_defaults['loftloader_pro_enable_any_page'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_checkbox'
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_enable_any_page', array(
				'default'   		=> $llp_defaults['loftloader_pro_enable_any_page'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_checkbox'
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_any_page_post_types', array(
				'default'   		=> $llp_defaults['loftloader_pro_any_page_post_types'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choices',
				'dependency' 		=> array(
					'loftloader_pro_enable_any_page' => array( 'value' => array( 'on' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_any_page_generation', array(
				'default'   		=> esc_html__( 'Generate', 'loftloader-pro' ),
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
				'type' 				=> 'option',
				'dependency' 		=> array(
					'loftloader_pro_enable_any_page' => array( 'value' => array( 'on' ) )
				)
			) ) );

			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_enable_any_page', array(
				'type' 		=> 'check',
				'label'	 	=> esc_html__( 'Enable Any Page Extension', 'loftloader-pro' ),
				'section' 	=> 'loftloader_pro_any_page_extension',
				'settings' 	=> 'loftloader_pro_enable_any_page'
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_any_page_post_types', array(
				'type' 				=> 'multiple',
				'label'	 			=> esc_html__( 'Enable Any Page on Post Types', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_any_page_extension',
				'settings' 			=> 'loftloader_pro_any_page_post_types',
				'active_callback'	=> 'llp_customize_control_active_cb',
				'choices'			=> llp_get_post_types()
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_any_page_generation', array(
				'type' => 'loftloader-any-page',
				'label' => esc_html__( 'Generate LoftLoader Shortcode', 'loftloader-pro' ),
				'description' => '',
				'section' => 'loftloader_pro_any_page_extension',
				'settings' => 'loftloader_pro_any_page_generation',
				'active_callback' => 'llp_customize_control_active_cb'
			) ) );
		}
		/**
		* Custom CSS section
		*/
		protected function add_section_custom_css( $wp_customize ) {
			global $llp_defaults;
			$section_description  = '<p>';
			$section_description .= esc_html__( 'Add your own CSS code here to customize the appearance and layout of your loader.', 'loftloader-pro' );
			$section_description .= sprintf(
				' <a href="%1$s" class="external-link" target="_blank">%2$s<span class="screen-reader-text"> %3$s</span></a>',
				esc_url( esc_html__( 'https://codex.wordpress.org/CSS', 'loftloader-pro' ) ),
				esc_html__( 'Learn more about CSS', 'loftloader-pro' ),
				/* translators: Accessibility text. */
				esc_html__( '(opens in a new tab)', 'loftloader-pro' )
			);
			$section_description .= '</p>';

			$wp_customize->add_section( new LoftLoader_Customize_Section( $wp_customize, 'loftloader_pro_section_custom_css', array(
				'title' => esc_html__( 'Custom CSS', 'loftloader-pro' ),
				'panel' => 'loftloader_pro_advanced_panel',
				'description' => $section_description,
			) ) );

			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_custom_css', array(
				'default'   		=> $llp_defaults[ 'loftloader_pro_custom_css' ],
				'transport' 		=> 'postMessage',
				'sanitize_callback' => array( $this, 'sanitize_custom_css' ),
				'type' 				=> 'option'
			) ) );
			$wp_customize->add_control( new WP_Customize_Code_Editor_Control( $wp_customize, 'loftloader_pro_custom_css', array(
				'label'       => esc_html__( 'CSS code', 'loftloader-pro' ),
				'section'     => 'loftloader_pro_section_custom_css',
				'settings'    => 'loftloader_pro_custom_css',
				'code_type'   => 'text/css',
				'input_attrs' => array( 'aria-describedby' => 'editor-keyboard-trap-help-1 editor-keyboard-trap-help-2 editor-keyboard-trap-help-3 editor-keyboard-trap-help-4' )
			) ) );
		}
		public function add_section_exclude_url_parameter( $wp_customize ) {
			global $llp_defaults;
			$wp_customize->add_section( new LoftLoader_Customize_Section( $wp_customize, 'loftloader_pro_section_exclude_url_parameters', array(
				'title' => esc_html__( 'URL Parameters', 'loftloader-pro' ),
				'panel' => 'loftloader_pro_advanced_panel'
			) ) );

			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_disable_loader_by_url_parameter', array(
				'default'   		=> $llp_defaults['loftloader_pro_disable_loader_by_url_parameter'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_url_parameters'
			) ) );

			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_disable_loader_by_url_parameter', array(
				'type' 				=> 'textarea',
				'label'				=> esc_html__( 'Disable loader if one of the following URL parameters matched', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_section_exclude_url_parameters',
				'settings' 			=> 'loftloader_pro_disable_loader_by_url_parameter',
				'description'		=> esc_html__( 'Enter key|value pairs. Separate each key|value pair by a line break.', 'loftloader-pro' ),
				'description_above' => false,
				'placeholder' 		=> esc_html__( 'Sample: key1|value1', 'loftloader-pro' ),
			) ) );
		}
		/**
		* Sanitize custom css
		*/
		public function sanitize_custom_css( $value ) {
			return wp_kses_post( $value );
		}
	}
	new LoftLoader_Pro_Section_Advanced();
}
