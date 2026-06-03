<?php
/**
 * The Testimonial live filter by star rating button.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/live-filter-buttons.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

if ( $filter_by_star_rating ) :
	$display_testimonials_from   = isset( $shortcode_data['display_testimonials_from'] ) ? $shortcode_data['display_testimonials_from'] : 'latest';
	$filter_by_rating            = isset( $shortcode_data['filter_by_rating'] ) ? $shortcode_data['filter_by_rating'] : '';
	$filter_testimonials_counter = isset( $shortcode_data['filter_testimonials_counter'] ) ? $shortcode_data['filter_testimonials_counter'] : true;
	$filter_ratings_label        = isset( $shortcode_data['filter_ratings_label_type'] ) ? $shortcode_data['filter_ratings_label_type'] : 'text';
	switch ( $filter_ratings_label ) {
		case 'text':
			$filter_star_ratings = array(
				'five_star'  => __( '5 Star', 'testimonial-pro' ),
				'four_star'  => __( '4 Star', 'testimonial-pro' ),
				'three_star' => __( '3 Star', 'testimonial-pro' ),
				'two_star'   => __( '2 Star', 'testimonial-pro' ),
				'one_star'   => __( '1 Star', 'testimonial-pro' ),
			);
			break;
		case 'icon':
			$full_star_icon      = '<i class="fa fa-star" aria-hidden="true"></i>';
			$empty_star_icon     = '<i class="fa fa-star-o sp-empty-star" aria-hidden="true"></i>';
			$filter_star_ratings = array(
				'five_star'  => sprintf( '%1$s%1$s%1$s%1$s%1$s', $full_star_icon ),
				'four_star'  => sprintf( '%1$s%1$s%1$s%1$s%2$s', $full_star_icon, $empty_star_icon ),
				'three_star' => sprintf( '%1$s%1$s%1$s%2$s%2$s', $full_star_icon, $empty_star_icon ),
				'two_star'   => sprintf( '%1$s%1$s%2$s%2$s%2$s', $full_star_icon, $empty_star_icon ),
				'one_star'   => sprintf( '%1$s%2$s%2$s%2$s%2$s', $full_star_icon, $empty_star_icon ),
			);
			break;
		case 'text_with_icon':
			$full_star_icon      = '<i class="fa fa-star" aria-hidden="true"></i>';
			$empty_star_icon     = '<i class="fa fa-star-o sp-empty-star" aria-hidden="true"></i>';
			$filter_star_ratings = array(
				'five_star'  => __( '5 Star ', 'testimonial-pro' ) . sprintf( '%1$s%1$s%1$s%1$s%1$s', $full_star_icon ),
				'four_star'  => __( '4 Star ', 'testimonial-pro' ) . sprintf( '%1$s%1$s%1$s%1$s%2$s', $full_star_icon, $empty_star_icon ),
				'three_star' => __( '3 Star ', 'testimonial-pro' ) . sprintf( '%1$s%1$s%1$s%2$s%2$s', $full_star_icon, $empty_star_icon ),
				'two_star'   => __( '2 Star ', 'testimonial-pro' ) . sprintf( '%1$s%1$s%2$s%2$s%2$s', $full_star_icon, $empty_star_icon ),
				'one_star'   => __( '1 Star ', 'testimonial-pro' ) . sprintf( '%1$s%2$s%2$s%2$s%2$s', $full_star_icon, $empty_star_icon ),
			);
			break;
	}
	$filter_star_ratings = apply_filters( 'sp_testimonial_modify_filter_ratings', $filter_star_ratings );
	// Add count of items based on star rating.
	if ( $filter_testimonials_counter ) {
		$rating_counts = array();
		foreach ( array_keys( $filter_star_ratings ) as $rating ) {
			$meta_query = array(
				'post_type'  => 'spt_testimonial',
				'fields'     => 'ids',
				'post__in'   => $testimonial_query_and_ids['all_testimonial_ids'],
				'meta_query' => array(
					array(
						'key'     => 'sp_tpro_meta_options',
						'value'   => $rating,
						'compare' => 'LIKE',
					),
				),
			);

			$query                    = new WP_Query( $meta_query );
			$rating_counts[ $rating ] = $query->found_posts;
			wp_reset_postdata();
		}
	}


	if ( 'filter_button' === $filter_rating_type ) {
		?>
		<div class="button-group filters-button-group testimonial-ajax-live-filter ajax-filter-button-by-rating" data-filter-group="star-rating">
			<?php if ( $filter_all_btn_switch && ! empty( $filter_all_btn_text['taxonomy'] ) ) { ?>
			<button class="button is-checked" data-slug=""><?php esc_html_e( 'All', 'testimonial-pro' ); ?></button>	
				<?php
			}
			$first_button_key = 'based_on_rating_star' === $display_testimonials_from ? $filter_by_rating[0] : array_key_first( $filter_star_ratings );
			foreach ( $filter_star_ratings as $slug => $label ) :
				$button_checked = ( strtolower( $first_button_key ) === $slug && ( '0' === $filter_all_btn_switch ) || strtolower( $first_button_key ) === $slug && ( '1' === $filter_all_btn_switch && empty( $filter_all_btn_text['taxonomy'] ) ) ) ? 'is-checked' : '';
				$rating_count   = $filter_testimonials_counter ? ' (' . $rating_counts[ $slug ] . ')' : '';
				?>
				<?php
				if ( isset( $label ) && ! empty( $label ) ) {
					?>
		<button class="button fltr-controls <?php echo esc_attr( $button_checked ); ?> <?php echo esc_attr( $slug ); ?>" data-slug="<?php echo esc_attr( $slug ); ?>"><?php echo wp_kses_post( $label . $rating_count ); ?></button>
				<?php } ?>
					<?php
				endforeach;
			?>
</div>
				<?php
	}
	if ( 'filter_dropdown' === $filter_rating_type ) {
		?>
	<div class="sp-testimonial-select testimonial-ajax-live-filter ajax-filter-select-by-rating">
		<div class="sp-testimonial-select-wrapper">
		<div class="sp-testimonial-sort-by"><?php esc_html_e( 'Sort By', 'testimonial-pro' ); ?></div>
	<div class="sp-testimonial-select-dropdown">
		<div href="#" role="button" data-value="" class="sp-testimonial-select-dropdown-button"><span><?php esc_html_e( 'All', 'testimonial-pro' ); ?> </span> <i class="sptpro-icon-right-open sptpro-dropdown"></i>
	</div>
		<ul class="sp-testimonial-select-dropdown-list">
			<?php if ( $filter_all_btn_switch && ! empty( $filter_all_btn_text['taxonomy'] ) ) { ?>
			<li data-value="" class="sp-testimonial-select-dropdown-list-item"><?php esc_html_e( 'All', 'testimonial-pro' ); ?></li>
				<?php
			}
			$first_button_key = 'based_on_rating_star' === $display_testimonials_from ? $filter_by_rating[0] : array_key_first( $filter_star_ratings );
			foreach ( $filter_star_ratings as $slug => $label ) :
				$rating_count   = $filter_testimonials_counter ? ' (' . $rating_counts[ $slug ] . ')' : '';
				$button_checked = ( strtolower( $first_button_key ) === $slug && ( '0' === $filter_all_btn_switch ) || strtolower( $first_button_key ) === $slug && ( '1' === $filter_all_btn_switch && empty( $filter_all_btn_text['taxonomy'] ) ) ) ? 'is-checked' : '';
				$rating_count   = $filter_testimonials_counter ? ' (' . $rating_counts[ $slug ] . ')' : '';
				?>
			<li data-value="<?php echo esc_attr( $slug ); ?>" class="sp-testimonial-select-dropdown-list-item <?php echo esc_attr( $button_checked ); ?>"><?php echo wp_kses_post( $label . $rating_count ); ?></li>
					<?php
				endforeach;
			?>
		</ul>
	</div>
	</div>
	</div>
				<?php
	}
endif;
