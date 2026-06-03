<?php
/**
 * Update options for the version 3.0.0
 *
 * @link       https://shapedplugin.com
 *
 * @package    testimonial_pro
 * @subpackage testimonial_pro/Admin/updates
 */

update_option( 'testimonial_pro_version', '3.0.0' );
update_option( 'testimonial_pro_db_version', '3.0.0' );

/**
 * Shortcode query for id.
 */
$args          = new WP_Query(
	array(
		'post_type'      => 'spt_shortcodes',
		'post_status'    => 'any',
		'posts_per_page' => '300',
	)
);
$shortcode_ids = wp_list_pluck( $args->posts, 'ID' );
if ( count( $shortcode_ids ) > 0 ) {
	foreach ( $shortcode_ids as $shortcode_key => $shortcode_id ) {
		$shortcode_data = get_post_meta( $shortcode_id, 'sp_tpro_shortcode_options', true );
		if ( ! is_array( $shortcode_data ) ) {
			continue;
		}

		// Set updater for Thumbnail Slider.
		$old_thumbnail_slider = isset( $shortcode_data['thumbnail_slider'] ) ? $shortcode_data['thumbnail_slider'] : 'false';
		if ( $old_thumbnail_slider ) {
			$shortcode_data['layout']      = 'slider';
			$shortcode_data['slider_mode'] = 'thumbnail_slider';
		}

		// Set ticker mode.
		$old_slider_mode = isset( $shortcode_data['slider_mode'] ) ? $shortcode_data['slider_mode'] : 'standard';
		if ( 'ticker' === $old_slider_mode ) {
			$shortcode_data['layout']        = 'carousel';
			$shortcode_data['carousel_mode'] = 'ticker';
		}

		$old_pagination_data = isset( $shortcode_data['pagination'] ) ? $shortcode_data['pagination'] : 'true';
		// Use database updater for the "carousel pagination" and "hide on mobile" options.
		switch ( $old_pagination_data ) {
			case 'true':
				$shortcode_data['spt_carousel_pagination']['pagination']                = '1';
				$shortcode_data['spt_carousel_pagination']['pagination_hide_on_mobile'] = '0';
				break;
			case 'false':
				$shortcode_data['spt_carousel_pagination']['pagination']                = '0';
				$shortcode_data['spt_carousel_pagination']['pagination_hide_on_mobile'] = '0';
				break;
			case 'hide_on_mobile':
				$shortcode_data['spt_carousel_pagination']['pagination']                = '1';
				$shortcode_data['spt_carousel_pagination']['pagination_hide_on_mobile'] = '1';
				break;
		}

		$old_navigation_data = isset( $shortcode_data['navigation'] ) ? $shortcode_data['navigation'] : 'true';
		// Use database updater for the "carousel navigation" and "hide on mobile" options.
		switch ( $old_navigation_data ) {
			case 'true':
				$shortcode_data['spt_carousel_navigation']['navigation']                = '1';
				$shortcode_data['spt_carousel_navigation']['navigation_hide_on_mobile'] = '0';
				break;
			case 'false':
				$shortcode_data['spt_carousel_navigation']['navigation']                = '0';
				$shortcode_data['spt_carousel_navigation']['navigation_hide_on_mobile'] = '0';
				break;
			case 'hide_on_mobile':
				$shortcode_data['spt_carousel_navigation']['navigation']                = '1';
				$shortcode_data['spt_carousel_navigation']['navigation_hide_on_mobile'] = '1';
				break;
		}

		$nav_icons               = isset( $shortcode_data['navigation_icons'] ) ? $shortcode_data['navigation_icons'] : 'angle';
		$old_navigation_position = isset( $shortcode_data['navigation_position'] ) ? $shortcode_data['navigation_position'] : '';

		// Update old carousel navigation icons according to the current enhancement.
		switch ( $nav_icons ) {
			case 'angle-double':
				$shortcode_data['navigation_icons'] = 'right_open_outline';
				break;
			case 'arrow':
				$shortcode_data['navigation_icons'] = 'arrow';
				break;
			case 'long-arrow':
				$shortcode_data['navigation_icons'] = 'arrow';
				break;
			case 'caret':
				$shortcode_data['navigation_icons'] = 'triangle';
				break;
		}

		if ( 'vertical_center_inner_hover' === $old_navigation_position ) {
			$shortcode_data['nav_visible_on_hover'] = '1';
			$shortcode_data['navigation_position']  = 'vertical_inner';
		}

		if ( 'vertical_center_outer_hover' === $old_navigation_position ) {
			$shortcode_data['nav_visible_on_hover'] = '1';
			$shortcode_data['navigation_position']  = 'vertical_outer';
		}

		if ( 'vertical_center_inner' === $old_navigation_position ) {
			$shortcode_data['navigation_position'] = 'vertical_inner';
		}

		if ( 'vertical_center' === $old_navigation_position ) {
			$shortcode_data['navigation_position'] = 'vertical_outer';
		}

		// Update old autoplay option according to the current changes.
		$slider_auto_play_data = isset( $shortcode_data['slider_auto_play'] ) ? $shortcode_data['slider_auto_play'] : 'true';
		switch ( $slider_auto_play_data ) {
			case 'true':
				$shortcode_data['carousel_autoplay']['slider_auto_play']           = true;
				$shortcode_data['carousel_autoplay']['autoplay_disable_on_mobile'] = false;
				break;
			case 'off_on_mobile':
				$shortcode_data['carousel_autoplay']['slider_auto_play']           = true;
				$shortcode_data['carousel_autoplay']['autoplay_disable_on_mobile'] = true;
				break;
			case 'false':
				$shortcode_data['carousel_autoplay']['slider_auto_play']           = false;
				$shortcode_data['carousel_autoplay']['autoplay_disable_on_mobile'] = false;
				break;
		}

		// Use updater for the 'Strip All HTML Content from Testimonial Content' option.
		$testimonial_strip_tags = isset( $shortcode_data['testimonial_strip_tags'] ) ? $shortcode_data['testimonial_strip_tags'] : 'strip_all';
		if ( 'strip_all' === $testimonial_strip_tags ) {
			$shortcode_data['testimonial_strip_tags'] = true;
		} else {
			$shortcode_data['testimonial_strip_tags'] = false;
		}

		// Icon custom color.
		$social_icon_custom_color_old = isset( $shortcode_data['social_icon_custom_color'] ) ? $shortcode_data['social_icon_custom_color'] : '';
		if ( $social_icon_custom_color_old ) {
			$shortcode_data['social_icon_color_type'] = 'custom';
		} else {
			$shortcode_data['social_icon_color_type'] = 'original';
		}

		// Testimonial Title Length.
		$old_title_limit                            = isset( $shortcode_data['testimonial_title_limit'] ) ? $shortcode_data['testimonial_title_limit'] : '';
		$shortcode_data['testimonial_title_length'] = array(
			'testimonial_title_limit' => $old_title_limit,
		);

		// Testimonial content length.
		$testimonial_content_length_type = isset( $shortcode_data['testimonial_content_length_type'] ) ? $shortcode_data['testimonial_content_length_type'] : 'characters';
		$testimonial_word_limit          = isset( $shortcode_data['testimonial_word_limit'] ) ? $shortcode_data['testimonial_word_limit'] : '';
		$testimonial_characters_limit    = isset( $shortcode_data['testimonial_characters_limit'] ) ? $shortcode_data['testimonial_characters_limit'] : '100';

		$shortcode_data['testimonial_content_length'] = array(
			'testimonial_content_length_type' => $testimonial_content_length_type,
			'testimonial_word_limit'          => $testimonial_word_limit,
			'testimonial_characters_limit'    => $testimonial_characters_limit,
		);

		// Custom date format.
		$testimonial_date_format = isset( $shortcode_data['testimonial_client_date_format'] ) ? $shortcode_data['testimonial_client_date_format'] : '';
		if ( $testimonial_date_format ) {
			$shortcode_data['testimonial_date_format_type'] = 'custom';
		}

		// Image Box shadow.
		$client_image_border_shadow = isset( $shortcode_data['client_image_border_shadow'] ) ? $shortcode_data['client_image_border_shadow'] : '';
		if ( 'box_shadow' === $client_image_border_shadow ) {
			$shortcode_data['client_image_border_shadow'] = 'shadow_outset';
		} else {
			$shortcode_data['client_image_border_shadow'] = 'none';
		}

		$slider_row        = isset( $shortcode_data['slider_row'] ) ? $shortcode_data['slider_row'] : '';
		$row_large_desktop = isset( $slider_row['large_desktop'] ) ? $slider_row['large_desktop'] : '1';
		$row_desktop       = isset( $slider_row['desktop'] ) ? $slider_row['desktop'] : '1';
		$row_laptop        = isset( $slider_row['laptop'] ) ? $slider_row['laptop'] : '1';
		$row_tablet        = isset( $slider_row['tablet'] ) ? $slider_row['tablet'] : '1';
		$row_mobile        = isset( $slider_row['mobile'] ) ? $slider_row['mobile'] : '1';
		$slider_animation  = isset( $shortcode_data['slider_animation'] ) ? $shortcode_data['slider_animation'] : '';

		if ( ( $row_large_desktop > 1 || $row_desktop > 1 || $row_laptop > 1 || $row_tablet > 1 || $row_mobile > 1 ) && 'fade' !== $slider_animation ) {
			$shortcode_data['layout']        = 'carousel';
			$shortcode_data['carousel_mode'] = 'multi_rows';
		}

		$item_box_shadow = isset( $shortcode_data['testimonial_box_shadow_property'] ) ? $shortcode_data['testimonial_box_shadow_property'] : array(
			'horizontal'  => '0',
			'vertical'    => '0',
			'blur'        => '6',
			'spread'      => '0',
			'color'       => '#ededed',
			'hover_color' => '#dddddd',
		);
		$theme_style     = isset( $shortcode_data['theme_style'] ) ? $shortcode_data['theme_style'] : 'theme-one';

		// If box shadow exists on the old theme 'Ten' then set the box shadow type to outset.
		if ( ( $item_box_shadow['horizontal'] > 1 || $item_box_shadow['vertical'] > 1 || $item_box_shadow['blur'] > 1 || $item_box_shadow['spread'] > 1 ) && 'theme-ten' === $theme_style ) {
			$shortcode_data['testimonial_shadow_type'] = 'shadow_outset';
		}

		update_post_meta( $shortcode_id, 'sp_tpro_shortcode_options', $shortcode_data );
	}
}

/**
 * Form shortcode query for id.
 */
$form_args = new WP_Query(
	array(
		'post_type'      => 'spt_testimonial_form',
		'post_status'    => 'any',
		'posts_per_page' => '300',
	)
);
$form_ids  = wp_list_pluck( $form_args->posts, 'ID' );
if ( count( $form_ids ) > 0 ) {
	foreach ( $form_ids as $form_key => $form_id ) {
		$form_data = get_post_meta( $form_id, 'sp_tpro_form_options', true );
		if ( ! is_array( $form_data ) ) {
			continue;
		}

		/**
		 * Updater for the testimonial form options.
		 */
		$old_email_to_list = isset( $form_data['submission_email_notification_to'] ) ? $form_data['submission_email_notification_to'] : '';
		$old_heading       = isset( $form_data['submission_email_heading'] ) ? $form_data['submission_email_heading'] : '';

		$old_message_body                   = isset( $form_data['submission_email_body'] ) ? $form_data['submission_email_body'] : '';
		$form_data['submission_email_to']   = $old_email_to_list;
		$form_data['submission_email_body'] = '<h2 style="text-align: center;font-size: 24px;">' . $old_heading . '</h2>
		' . $old_message_body;

		// Set title character & word limit = 0; for old version user.
		$form_data['form_fields']['testimonial_title']['title_length'] = array(
			'title_char_limit'  => '0',
			'title_word_limit'  => '0',
			'title_length_type' => 'characters',
		);

		// Set content character & word limit = 0; for old version user.
		$form_data['form_fields']['testimonial']['content_length'] = array(
			'content_char_limit'  => '0',
			'content_word_limit'  => '0',
			'content_length_type' => 'characters',
		);

		// Set default border width = 0 for old users.
		$form_data['testimonial_form_border'] = array(
			'all'    => '0',
			'style'  => 'solid',
			'color'  => '#444444',
			'radius' => '6',
			'unit'   => '%',
		);
		$form_data['form_background_color']   = 'transparent';

		update_post_meta( $form_id, 'sp_tpro_form_options', $form_data );
	}
}
