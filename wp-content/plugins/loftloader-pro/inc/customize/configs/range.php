<?php
/**
* Load loftloader pro section range related functions
*
* @since version 1.0.6
*/

if ( ! class_exists( 'LoftLoader_Pro_Section_Range' ) ) {
	class LoftLoader_Pro_Section_Range extends LoftLoader_Pro_Customize_Base {
		public function register_customize_elements( $wp_customize ) {
			global $llp_defaults;

			// Add Section
			$wp_customize->add_section( new LoftLoader_Customize_Section( $wp_customize, 'loftloader_pro_display', array(
				'title' 		=> esc_html__( 'Display on', 'loftloader-pro' ),
				'description' 	=> '',
				'priority' 		=> 30
			) ) );

			// Add Settings
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_show_range', array(
				'default'   		=> $llp_defaults['loftloader_pro_show_range'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choice'
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_hand_pick_pages', array(
				'default'   		=> $llp_defaults['loftloader_pro_hand_pick_pages'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_handpick_posts',
				'dependency' 		=> array(
					'loftloader_pro_show_range' => array( 'value' => array( 'handpick' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_site_wide_exclude_pages', array(
				'default'   		=> $llp_defaults['loftloader_pro_site_wide_exclude_pages'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_handpick_posts',
				'dependency' 		=> array(
					'loftloader_pro_show_range' => array( 'value' => array( 'sitewide' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_all_pages_exclude_pages', array(
				'default'   		=> $llp_defaults['loftloader_pro_all_pages_exclude_pages'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_handpick_posts',
				'dependency' 		=> array(
					'loftloader_pro_show_range' => array( 'value' => array( 'all' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_post_types', array(
				'default'   		=> $llp_defaults['loftloader_pro_post_types'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choices',
				'dependency' 		=> array(
					'loftloader_pro_show_range' => array( 'value' => array( 'post_types' ) )
				)
			) ) );
			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_selected_post_types', array(
				'default'   		=> $llp_defaults['loftloader_pro_selected_post_types'],
				'transport' 		=> 'refresh',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_choices',
				'dependency' 		=> array(
					'loftloader_pro_show_range' => array( 'value' => array( 'selected_post_types' ) )
				)
			) ) );

			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_enable_once_per_session', array(
				'default'   		=> $llp_defaults['loftloader_pro_enable_once_per_session'],
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_checkbox',
				'dependency' 		=> array(
					'loftloader_pro_show_range' => array( 'value' => array( 'all', 'post_types', 'selected_post_types', 'handpick' ) )
				)
			) ) );

			$wp_customize->add_setting( new LoftLoader_Customize_Setting( $wp_customize, 'loftloader_pro_once_per_session_notes', array(
				'default'  			=> '',
				'transport' 		=> 'postMessage',
				'type' 				=> 'option',
				'sanitize_callback' => 'llp_sanitize_checkbox',
				'dependency' 		=> array(
					'loftloader_pro_show_range' => array( 'value' => array( 'once', 'homepage-once', 'all', 'post_types', 'selected_post_types', 'handpick' ) )
				)
			) ) );


			// Add Controls
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_show_range', array(
				'type' 		=> 'radio',
				'label' 	=> '',
				'section' 	=> 'loftloader_pro_display',
				'settings' 	=> 'loftloader_pro_show_range',
				'choices' 	=> array(
					'sitewide' 				=> esc_html__( 'Sitewide', 'loftloader-pro' ),
					'homepage' 				=> esc_html__( 'Homepage only', 'loftloader-pro' ),
					'once' 					=> esc_html__( 'Once per session', 'loftloader-pro' ),
					'homepage-once' 		=> esc_html__( 'Homepage only + Once per session', 'loftloader-pro' ),
					'all' 					=> esc_html__( 'All pages', 'loftloader-pro' ),
					'post_types' 			=> esc_html__( 'Sitewide - Selected Types', 'loftloader-pro' ),
					'selected_post_types' 	=> esc_html__( 'Selected Post Types', 'loftloader-pro' ),
					'handpick' 				=> esc_html__( 'Handpick', 'loftloader-pro' )
				)
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_hand_pick_pages', array(
				'type' 				=> 'loftocean_pro_query_posts', //'loftloader_page_post',
				'label'    			=> esc_html__( 'Choose page/post/custom post types', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_display',
				'settings' 			=> 'loftloader_pro_hand_pick_pages',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_site_wide_exclude_pages', array(
				'type' 				=> 'loftocean_pro_query_posts', //'loftloader_page_post',
				'label'    			=> esc_html__( 'Exclude handpicked pages/posts/custom post types', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_display',
				'settings' 			=> 'loftloader_pro_site_wide_exclude_pages',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_all_pages_exclude_pages', array(
				'type' 				=> 'loftocean_pro_query_posts', //'loftloader_page_post',
				'label'    			=> esc_html__( 'Exclude handpicked pages', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_display',
				'search_attrs'		=> array( 'data-post-type' => 'page' ),
				'settings' 			=> 'loftloader_pro_all_pages_exclude_pages',
				'active_callback' 	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_post_types', array(
				'type' 				=> 'loftloader_post_types',
				'label'    			=> esc_html__( 'Choose Post Types', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_display',
				'settings' 			=> 'loftloader_pro_post_types',
				'active_callback' 	=> 'llp_customize_control_active_cb',
				'choices'			=> llp_get_post_types(),
				'description' 		=> sprintf(
					/* translators: 1: html tag */
					esc_html__( 'Loader will not show for the selected post types.%sPlease use the CTRL key (PC) or COMMAND key (Mac) to select multiple items.', 'loftloader-pro' ),
					'<br />'
				)
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_selected_post_types', array(
				'type' 				=> 'loftloader_post_types',
				'label'    			=> esc_html__( 'Choose Post Types', 'loftloader-pro' ),
				'section' 			=> 'loftloader_pro_display',
				'settings' 			=> 'loftloader_pro_selected_post_types',
				'active_callback' 	=> 'llp_customize_control_active_cb',
				'choices'			=> llp_get_post_types(),
				'description' 		=> sprintf(
					/* translators: 1: html tag */
					esc_html__( 'Loader will show for the selected post types.%sPlease use the CTRL key (PC) or COMMAND key (Mac) to select multiple items.', 'loftloader-pro' ),
					'<br />'
				)
			) ) );

			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_enable_once_per_session', array(
				'type' 				=> 'check',
				'label'	 			=> esc_html__( 'Enable Once Per Session', 'loftloader-pro' ),
				'description_above' => false,
				'section' 			=> 'loftloader_pro_display',
				'settings' 			=> 'loftloader_pro_enable_once_per_session',
				'active_callback'	=> 'llp_customize_control_active_cb'
			) ) );
			$wp_customize->add_control( new LoftLoader_Customize_Control( $wp_customize, 'loftloader_pro_once_per_session_notes', array(
				'type' 				=> 'description',
				'section' 			=> 'loftloader_pro_display',
				'settings' 			=> 'loftloader_pro_once_per_session_notes',
				'active_callback' 	=> 'llp_customize_control_active_cb',
				'description' 		=> sprintf(
					/* translators: 1: html tag start. 2: html tag end */
					esc_html__( 'Please note: "Once per session" feature only works on front end. To preview the result, please always clear your browser cookie first. More information please refer to %1$ssession cookie%2$s.', 'loftloader-pro' ),
					'<a href="http://loftocean.com/doc/loftloader/display-on/#play-once-per-session" target="_blank">',
					'</a>'
				),
			) ) );
		}
	}
	new LoftLoader_Pro_Section_Range();
}
