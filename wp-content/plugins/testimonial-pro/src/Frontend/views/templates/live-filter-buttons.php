<?php
/**
 * The Testimonial live filter button.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/live-filter-buttons.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

use ShapedPlugin\TestimonialPro\Frontend\Helper;
if ( $testimonial_live_filter ) {
	$filter_button_array = Helper::isotope_button_meta( $testimonial_query_and_ids['all_testimonial_ids'], $shortcode_data, $post_id );
	foreach ( $live_filter_sorter as $sorter_key => $sorter_value ) {
		if ( $sorter_value ) {
			switch ( $sorter_key ) {
				case 'filter_by_group':
						echo '<div class="sp-testimonial-live-filter filter-by-group">';
						require self::sptp_locate_template( 'live-filter-by-groups.php' );
						echo '</div>';
					break;
				case 'filter_by_star_rating':
					if ( $sorter_key === $first_filter && ! $inline_filter_style ) {
						echo '<div class="sp-testimonial-ratings-wrapper">';
						require self::sptp_locate_template( 'average-rating.php' );
					}
						echo '<div class="sp-testimonial-live-filter filter-by-star-rating">';
						require self::sptp_locate_template( 'live-filter-by-star-rating.php' );
						echo '</div>';
					if ( $sorter_key === $first_filter && ! $inline_filter_style ) {
						echo '</div>';
					}
					break;
			}
		}
	}
}
