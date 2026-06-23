<?php
/*
Template Name: Little Road
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter(
	'body_class',
	static function ( $classes ) {
		$classes[] = 'page-location-stylists';
		$classes[] = 'page-little-road-stylists';
		return $classes;
	}
);

get_header();

$location_slug  = 'little_road';
$location_label = __( 'Little Road', 'bb-theme-child' );
$page_heading   = __( 'Salon Suites for Beauty Professionals in Arlington TX', 'bb-theme-child' );

bb_child_render_location_stylists_directory(
	array(
		'location_slug'  => $location_slug,
		'location_label' => $location_label,
		'page_heading'   => $page_heading,
	)
);

get_footer();
