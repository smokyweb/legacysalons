<?php
/**
 * Update options for the version 3.2.0
 *
 * @link       https://shapedplugin.com
 *
 * @package    testimonial_pro
 * @subpackage testimonial_pro/Admin/updates
 */

update_option( 'testimonial_pro_version', '3.2.0' );
update_option( 'testimonial_pro_db_version', '3.2.0' );

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
		$layout                = isset( $shortcode_data['layout'] ) ? $shortcode_data['layout'] : '';
		$slider_mode           = isset( $shortcode_data['slider_mode'] ) ? $shortcode_data['slider_mode'] : 'standard';
		$carousel_mode         = isset( $shortcode_data['carousel_mode'] ) ? $shortcode_data['carousel_mode'] : 'standard';
		$filter_style          = isset( $shortcode_data['filter_style'] ) ? $shortcode_data['filter_style'] : 'even';
		$theme_style_thumbnail = isset( $shortcode_data['theme_style_thumbnail'] ) ? $shortcode_data['theme_style_thumbnail'] : 'theme-one';
		if ( $layout ) {
			$layouts_data['layout'] = $layout;
		}
		if ( 'slider' === $layout && 'thumbnail_slider' === $slider_mode ) {
			$layouts_data['layout']                = 'thumbnail_slider';
			$layouts_data['theme_style_thumbnail'] = $theme_style_thumbnail;
		}
		if ( 'slider' === $layout ) {
			$layouts_data['carousel_mode'] = 'standard';
		}
		if ( 'carousel' === $layout ) {
			$layouts_data['carousel_mode'] = $carousel_mode;
		}
		if ( 'carousel' === $layout && 'multi_rows' === $carousel_mode ) {
			$layouts_data['layout'] = 'multi_rows';
		}
		if ( 'filter' === $layout ) {
			$layouts_data['filter_style'] = $filter_style;
		}

		$star_icon = isset( $shortcode_data['tpro_star_icon'] ) ? $shortcode_data['tpro_star_icon'] : 'fa fa-star';
		switch ( $star_icon ) {
			case 'fa fa-star':
				$shortcode_data['tpro_star_icon'] = 'rating-star-1';
				break;
			case 'fa fa-heart':
				$shortcode_data['tpro_star_icon'] = 'rating-star-6b';
				break;
			case 'fa fa-thumbs-up':
				$shortcode_data['tpro_star_icon'] = 'rating-star-7b';
				break;
			case 'fa fa-hourglass':
				$shortcode_data['tpro_star_icon'] = 'rating-star-9';
				break;
			case 'fa fa-circle':
				$shortcode_data['tpro_star_icon'] = 'rating-star-10';
				break;
			case 'fa fa-square':
				$shortcode_data['tpro_star_icon'] = 'rating-star-11';
				break;
			case 'fa fa-flag':
				$shortcode_data['tpro_star_icon'] = 'rating-star-12';
				break;
			case 'fa fa-smile-o':
				$shortcode_data['tpro_star_icon'] = 'rating-star-13';
				break;
		}
		$client_rating_color = isset( $shortcode_data['testimonial_client_rating_color'] ) ? $shortcode_data['testimonial_client_rating_color'] : '#ffb900';
		if ( is_string( $client_rating_color ) ) {
			$shortcode_data['testimonial_client_rating_color'] = array(
				'color'       => $client_rating_color,
				'hover-color' => $client_rating_color,
			);
		}
		update_post_meta( $shortcode_id, 'sp_tpro_layout_options', $layouts_data );
		update_post_meta( $shortcode_id, 'sp_tpro_shortcode_options', $shortcode_data );
	}
}
