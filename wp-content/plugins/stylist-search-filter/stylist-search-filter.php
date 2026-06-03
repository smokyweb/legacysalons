<?php
/**
 * Plugin Name: Custom Widget
 * Description: Custom widget plugin. Includes a Search Stylist shortcode (Location + Service Type + keyword) and can be extended with more widgets.
 * Version: 1.0.0
 * Author: Custom AI Assistant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shortcode: [stylist_search_bar]
 * Outputs a search form to filter stylists by location, service type, and keyword.
 */
function ssf_stylist_search_bar_shortcode( $atts ) {
	// Parse shortcode attributes in case you want to extend later.
	$atts = shortcode_atts(
		array(
			'post_type' => 'stylist',
		),
		$atts,
		'stylist_search_bar'
	);

	$post_type = $atts['post_type'];

	// Current values from query so form stays populated after submit.
	$current_location     = isset( $_GET['stylist_location'] ) ? sanitize_text_field( wp_unslash( $_GET['stylist_location'] ) ) : '';
	$current_service_type = isset( $_GET['stylist_service'] ) ? sanitize_text_field( wp_unslash( $_GET['stylist_service'] ) ) : '';
	$current_search       = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';

	// Get taxonomy terms.
	$location_terms = get_terms(
		array(
			'taxonomy'   => 'locations',
			'hide_empty' => true,
		)
	);

	$service_terms = get_terms(
		array(
			'taxonomy'   => 'stylist_service',
			'hide_empty' => true,
		)
	);

	ob_start();
	?>
	<form class="stylist-search-bar" method="get" action="">
	

		<div class="stylist-search-bar__inner">
			<div class="stylist-search-bar__field stylist-search-bar__field--location">
				<label class="screen-reader-text" for="stylist-search-location"><?php esc_html_e( 'Location', 'stylist-search-filter' ); ?></label>
				<select id="stylist-search-location" name="stylist_location">
					<option value=""><?php esc_html_e( 'All Locations', 'stylist-search-filter' ); ?></option>
					<?php
					if ( ! is_wp_error( $location_terms ) && ! empty( $location_terms ) ) :
						foreach ( $location_terms as $term ) :
							?>
							<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $current_location, $term->slug ); ?>>
								<?php echo esc_html( $term->name ); ?>
							</option>
							<?php
						endforeach;
					endif;
					?>
				</select>
			</div>

			<div class="stylist-search-bar__field stylist-search-bar__field--service-type">
				<label class="screen-reader-text" for="stylist-search-service"><?php esc_html_e( 'Service Type', 'stylist-search-filter' ); ?></label>
				<select id="stylist-search-service" name="stylist_service">
					<option value=""><?php esc_html_e( 'All Services', 'stylist-search-filter' ); ?></option>
					<?php
					if ( ! is_wp_error( $service_terms ) && ! empty( $service_terms ) ) :
						foreach ( $service_terms as $term ) :
							?>
							<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $current_service_type, $term->slug ); ?>>
								<?php echo esc_html( $term->name ); ?>
							</option>
							<?php
						endforeach;
					endif;
					?>
				</select>
			</div>

			<div class="stylist-search-bar__field stylist-search-bar__field--keyword">
				<label class="screen-reader-text" for="stylist-search-keyword"><?php esc_html_e( 'Search by name or specialty', 'stylist-search-filter' ); ?></label>
				<input
					id="stylist-search-keyword"
					type="text"
					name="keyword"
					placeholder="<?php esc_attr_e( 'e.g.  business_name,city,state,specialization,suite,experience,short_bio,contact'
); ?>"
					value="<?php echo esc_attr( $current_search ); ?>"
				/>
			</div>

			<div class="stylist-search-bar__field stylist-search-bar__field--submit">
				<button type="submit">
					<?php esc_html_e( 'Apply Filters', 'stylist-search-filter' ); ?>
				</button>
			</div>
		</div>
	</form>
	<?php

	return ob_get_clean();
}
add_shortcode( 'stylist_search_bar', 'ssf_stylist_search_bar_shortcode' );

/**
 * Adjust the main search query for stylists based on form filters.
 */
function ssf_filter_stylist_search_query( $query ) {
    
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}



	$post_type = isset( $_GET['pt'] ) ? sanitize_text_field( wp_unslash( $_GET['pt'] ) ) : '';
	if ( 'stylist' !== $post_type ) {
		return;
	}

	$location     = isset( $_GET['stylist_location'] ) ? sanitize_text_field( wp_unslash( $_GET['stylist_location'] ) ) : '';
	$service_type = isset( $_GET['stylist_service'] ) ? sanitize_text_field( wp_unslash( $_GET['stylist_service'] ) ) : '';

	$tax_query = array();

	if ( $location ) {
		$tax_query[] = array(
			'taxonomy' => 'locations',
			'field'    => 'slug',
			'terms'    => $location,
		);
	}

	if ( $service_type ) {
		$tax_query[] = array(
			'taxonomy' => 'stylist_service',
			'field'    => 'slug',
			'terms'    => $service_type,
		);
	}



	$query->set( 'post_type', 'stylist' );
}
//add_action( 'pre_get_posts', 'ssf_filter_stylist_search_query' );

// Register CPT to store suite match requests in wp-admin.
function cw_register_suite_match_request_cpt() {
	register_post_type(
		'suite_match_request',
		array(
			'labels'       => array(
				'name'          => __( 'Match Requests', 'custom-widget' ),
				'singular_name' => __( 'Match Request', 'custom-widget' ),
			),
			'public'       => false,
			'show_ui'      => true,
			'show_in_menu' => true,
			'menu_icon'    => 'dashicons-email-alt',
			'supports'     => array( 'title' ),
		)
	);
}
add_action( 'init', 'cw_register_suite_match_request_cpt' );