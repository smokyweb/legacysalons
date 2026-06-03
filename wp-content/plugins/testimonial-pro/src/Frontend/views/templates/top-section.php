<?php
/**
 * Top section of each layouts.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/top-section.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

require self::sptp_locate_template( 'preloader.php' );
if ( $show_testimonial_text && 'content_with_limit' === $testimonial_content_type && $testimonial_read_more && 'expand' === $testimonial_read_more_link_action ) {
	echo '<div class="sp-tpro-rm-config"  ' . wp_kses_post( $read_more_config ) . '></div>';
}
$filter_all_btn_text['taxonomy'] = isset( $shortcode_data['all_tab_text'] ) ? $shortcode_data['all_tab_text'] : 'All';
$filter_all_btn_switch           = isset( $shortcode_data['all_tab'] ) ? $shortcode_data['all_tab'] : true;


$live_filter_sorter             = isset( $shortcode_data['live_filter_sorter'] ) ? $shortcode_data['live_filter_sorter'] : array(
	'filter_by_star_rating' => false,
	'filter_by_group'       => true,
);
$filter_by_groups               = isset( $live_filter_sorter['filter_by_group'] ) ? $live_filter_sorter['filter_by_group'] : true;
$filter_by_star_rating          = isset( $live_filter_sorter['filter_by_star_rating'] ) ? $live_filter_sorter['filter_by_star_rating'] : true;
$filter_type                    = isset( $shortcode_data['live_filter_type'] ) ? $shortcode_data['live_filter_type'] : 'filter_button';
$star_rating_filter_as_dropdown = isset( $shortcode_data['star_rating_filter_as_dropdown'] ) ? $shortcode_data['star_rating_filter_as_dropdown'] : true;
$filter_rating_type             = $star_rating_filter_as_dropdown && 'filter_button' === $filter_type ? 'filter_dropdown' : $filter_type;
$inline_filter_style            = isset( $shortcode_data['enable_inline_filter_style'] ) && $shortcode_data['enable_inline_filter_style'] && 'filter_dropdown' === $filter_rating_type ? true : false;

$first_filter = array_key_first( $live_filter_sorter );

require self::sptp_locate_template( 'section-title.php' );
require self::sptp_locate_template( 'search-form.php' );

if ( ! $testimonial_live_filter || 'filter_by_group' === $first_filter || $inline_filter_style ) {
	require self::sptp_locate_template( 'average-rating.php' );
}
if ( ! ( 'thumbnail_slider' === $layout || 'filter' === $layout ) ) {
	if ( $inline_filter_style ) {
		echo '<div class ="sp-testimonial-inline-filter">';
	}

	require self::sptp_locate_template( 'live-filter-buttons.php' );
	if ( $inline_filter_style ) {
		echo '</div>';
	}
}
