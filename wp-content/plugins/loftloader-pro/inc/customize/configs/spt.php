<?php
/**
* Load loftloader pro section smooth page transition related functions
* @since version 2.4
*/

if ( ! class_exists( 'LoftLoader_Pro_Panel_SPT' ) ) {
	class LoftLoader_Pro_Panel_SPT extends LoftLoader_Pro_Customize_Base {
		/**
		* String panel id
		*/
		protected $panel_id = 'loftloader_pro_smooth_page_transition';
		public function register_customize_elements( $wp_customize ) {
			// Add Panel
			$wp_customize->add_panel( $this->panel_id, array(
				'title' 		=> esc_html__( 'Smooth Page Transition', 'loftloader-pro' ),
				'description' 	=> esc_html__( 'Please note: the options in this section only show and work on front end.', 'loftloader-pro' ),
				'priority' 		=> 75
			) );

			$this->section_general( $wp_customize );
			$this->section_elements_not_trigger_spt( $wp_customize );
			$this->section_additional_display_option( $wp_customize );
			$this->section_leaving_current_page( $wp_customize );
			$this->section_trigger_on_buttons( $wp_customize );
		}
		/**
		* Section smooth page transition
		*/
		protected function section_general( $wp_customize ) {
			global $llp_defaults;

			$wp_customize->add_section( new LoftLoader_Customize_Section( $wp_customize, 'loftloader_pro_spt_section_general', array(
				'title' => esc_html__( 'General', 'loftloader-pro' ),
				'panel' => $this->panel_id
			) ) );

			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_insite_transition', array(
				'default' 			=> $llp_defaults['loftloader_pro_insite_transition'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_checkbox'
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_insite_transition_show_all', array(
				'default'   		=> $llp_defaults['loftloader_pro_insite_transition_show_all'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_checkbox',
				'dependency' 		=> array(
					'loftloader_pro_insite_transition' => array( 'value' => array( 'on' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_insite_transition_show_close_button', array(
				'default'   		=> $llp_defaults['loftloader_pro_insite_transition_show_close_button'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_checkbox',
				'dependency' 		=> array(
					'loftloader_pro_insite_transition' => array( 'value' => array( 'on' ) )
				)
			) ) );

            $wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_insite_transition', array(
                'type' 		=> 'check',
                'label' 	=> esc_html__( 'Smooth Page Transition', 'loftloader-pro' ),
                'choices' 	=> array( 'on' => '' ),
                'section' 	=> 'loftloader_pro_spt_section_general',
                'settings' 	=> 'loftloader_pro_insite_transition'
            ) ) );
            $wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_insite_transition_show_all', array(
                'type' 				=> 'check',
                'label' 			=> esc_html__( 'Show All Elements When Leaving The Current Page', 'loftloader-pro' ),
                'description_above'	=> false,
                'description' 		=> esc_html__( 'If not checked, only background will show when leaving the current page.', 'loftloader-pro' ),
                'section' 			=> 'loftloader_pro_spt_section_general',
                'settings' 			=> 'loftloader_pro_insite_transition_show_all',
                'active_callback' 	=> 'llp_customize_control_active_cb'
            ) ) );
            $wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_insite_transition_show_close_button', array(
                'type' 				=> 'check',
                'label' 			=> esc_html__( 'Show Close Button When Leaving The Current Page', 'loftloader-pro' ),
                'description_above'	=> false,
                'section' 			=> 'loftloader_pro_spt_section_general',
                'settings' 			=> 'loftloader_pro_insite_transition_show_close_button',
                'active_callback' 	=> 'llp_customize_control_active_cb'
            ) ) );
        }
		/**
		* Section smooth page transition
		*/
		protected function section_elements_not_trigger_spt( $wp_customize ) {
			global $llp_defaults;

			$wp_customize->add_section( new LoftLoader_Customize_Section( $wp_customize, 'loftloader_pro_spt_section_exclude_elements', array(
				'title' => esc_html__( 'Elements not trigger Smooth Page Transition', 'loftloader-pro' ),
				'panel' => $this->panel_id
			) ) );

			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_exclude_from_page_transition', array(
				'default'   		=> $llp_defaults['loftloader_pro_exclude_from_page_transition'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'sanitize_text_field',
				'dependency' 		=> array(
					'loftloader_pro_insite_transition' => array( 'value' => array( 'on' ) )
				)
			) ) );

			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_exclude_from_page_transition', array(
				'type' 				=> 'textarea',
				'label' 			=> esc_html__( 'Links excluded from Smooth Page Transition', 'loftloader-pro' ),
				'description' 		=> sprintf(
					/* translators: %1$s: html tag start. %2$s: html tag end */
					esc_html__( 'Seperated by comma(,). %1$sPlease check the documentation for more information.%2$s', 'loftloader-pro' ),
					sprintf( '<a href="%s" target="_blank">', 'http://loftocean.com/doc/loftloader/smooth-page-transition/exclude-specific-internal-links/?from_search=1' ),
					'</a>'
				),
				'section' 			=> 'loftloader_pro_spt_section_exclude_elements',
				'settings' 			=> 'loftloader_pro_exclude_from_page_transition',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
        }
		/**
		* Section smooth page transition
		*/
		protected function section_additional_display_option( $wp_customize ) {
			global $llp_defaults;

			$wp_customize->add_section( new LoftLoader_Customize_Section( $wp_customize, 'loftloader_pro_spt_section_additional_display_option', array(
				'title' => esc_html__( 'Additional Display Option', 'loftloader-pro' ),
				'panel' => $this->panel_id
			) ) );

			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_insite_transition_display', array(
				'default'   		=> $llp_defaults['loftloader_pro_insite_transition_display'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice',
				'dependency' 		=> array(
					'loftloader_pro_insite_transition' => array( 'value' => array( 'on' ) )
				)
			) ) );

            $wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_insite_transition_display', array(
                'type' 				=> 'radio',
                'label' 			=> esc_html__( 'Additional Display Option', 'loftloader-pro' ),
                'section' 			=> 'loftloader_pro_spt_section_additional_display_option',
                'settings' 			=> 'loftloader_pro_insite_transition_display',
                'active_callback' 	=> 'llp_customize_control_active_cb',
                'choices' 			=> array(
                    ''	=> esc_html__( 'Default', 'loftloader-pro' ),
                    'between-pages' => esc_html__( 'Only show loader during page transition', 'loftloader-pro' ),
                    'current-page' => esc_html__( 'Show the loader on the current page only when leaving the page', 'loftloader-pro' )
                ),
                'description' 		=> sprintf(
                    // translators: 1: html tag start. 2: html tag end
                    esc_html__( 'Please %1$scheck the documentation%2$s to learn more about this feature.', 'loftloader-pro' ),
                    sprintf( '<a href="%s" target="_blank">', 'https://loftocean.com/doc/loftloader/smooth-page-transition/additional-display-options/?from_search=1' ),
                    '</a>'
                )
            ) ) );
        }
		/**
		* Section smooth page transition
		*/
		protected function section_leaving_current_page( $wp_customize ) {
			global $llp_defaults;

			$wp_customize->add_section( new LoftLoader_Customize_Section( $wp_customize, 'loftloader_pro_spt_section_leaving_current_page', array(
				'title' => esc_html__( 'When Leaving Current Page', 'loftloader-pro' ),
				'panel' => $this->panel_id
			) ) );

			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_insite_transition_reverse_bg_animation', array(
				'default'   		=> $llp_defaults['loftloader_pro_insite_transition_reverse_bg_animation'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_checkbox',
				'dependency' 		=> array(
					'loftloader_pro_insite_transition' => array( 'value' => array( 'on' ) )
				)
			) ) );

			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_insite_transition_initial_percentage', array(
				'default'   		=> $llp_defaults['loftloader_pro_insite_transition_initial_percentage'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'absint',
				'dependency' 		=> array(
					'loftloader_pro_insite_transition' => array( 'value' => array( 'on' ) ),
					'loftloader_pro_insite_transition_show_all' => array( 'value' => array( 'on' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_insite_transition_initial_timer', array(
				'default'   		=> $llp_defaults['loftloader_pro_insite_transition_initial_timer'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'absint',
				'dependency' 		=> array(
					'loftloader_pro_insite_transition' => array( 'value' => array( 'on' ) ),
					'loftloader_pro_insite_transition_show_all' => array( 'value' => array( 'on' ) )
				)
			) ) );

			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_insite_transition_reverse_bg_animation', array(
				'type' 				=> 'check',
				'label' 			=> esc_html__( 'Reverse Background Animation Direction When Reappearing', 'loftloader-pro' ),
				'description_above'	=> false,
				'description' 		=> esc_html__( 'Only works for: Slide to Left, Slide to Right, Slide Up, Slide Down.', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_spt_section_leaving_current_page',
				'settings' 			=> 'loftloader_pro_insite_transition_reverse_bg_animation',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );

			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_insite_transition_initial_percentage', array(
				'type'     			=> 'slider',
				'label'    			=> esc_html__( 'Progress Ends at', 'loftloader-pro' ),
				'section'  			=> 'loftloader_pro_spt_section_leaving_current_page',
				'settings' 			=> 'loftloader_pro_insite_transition_initial_percentage',
				'active_callback' 	=> 'llp_customize_control_active_cb',
				'input_attrs' 		=> array(
					'data-default' 	=> 60,
					'data-min'	 	=> 1,
					'data-max'		=> 99,
					'data-step'		=> 1
				)
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_insite_transition_initial_timer', array(
				'type'     			=> 'slider',
				'label'    			=> esc_html__( 'Hold on Current Page for', 'loftloader-pro' ),
				'section'  			=> 'loftloader_pro_spt_section_leaving_current_page',
				'settings' 			=> 'loftloader_pro_insite_transition_initial_timer',
				'active_callback' 	=> 'llp_customize_control_active_cb',
				'after_text' 		=> esc_html__( '(ms)', 'loftloader-pro' ),
				'input_style'		=> 'width: 50px;',
				'input_attrs' 		=> array(
					'data-default' 	=> 900,
					'data-min'	 	=> 0,
					'data-max'		=> 5000,
					'data-step'		=> 50
				)
			) ) );
        }
		/**
		* Section smooth page transition
		*/
		protected function section_trigger_on_buttons( $wp_customize ) {
			global $llp_defaults;

			$wp_customize->add_section( new LoftLoader_Customize_Section( $wp_customize, 'loftloader_pro_spt_section_buttons', array(
				'title' => esc_html__( 'Trigger on Buttons (BETA)', 'loftloader-pro' ),
				'panel' => $this->panel_id
			) ) );

			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_insite_transition_buttons', array(
				'default'   		=> $llp_defaults['loftloader_pro_insite_transition_buttons'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'sanitize_text_field',
				'dependency' 		=> array(
					'loftloader_pro_insite_transition' => array( 'value' => array( 'on' ) )
				)
			) ) );

			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_insite_transition_buttons', array(
				'type' 				=> 'textarea',
				'label' 			=> esc_html__( 'Buttons that will trigger Smooth Page Transition', 'loftloader-pro' ),
				'description' 		=> sprintf(
					// translators: 1: html tag start. 2: html tag end
					esc_html__( 'Seperated by comma(,).%1$sPlease note:%2$s "Hold on Current Page for" timer will not work on these buttons.', 'loftloader-pro' ),
					'<br><br><strong>',
					'</strong>'
				),
				'section' 			=> 'loftloader_pro_spt_section_buttons',
				'settings' 			=> 'loftloader_pro_insite_transition_buttons',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
        }
	}
	new LoftLoader_Pro_Panel_SPT();
}
