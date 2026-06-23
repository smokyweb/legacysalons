<?php
/*
Template Name: Locations
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'bb_child_locations_get_meta' ) ) {
	/**
	 * Read a location field from ACF or post meta.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $key     Field key.
	 * @param mixed  $default Default value.
	 * @return mixed
	 */
	function bb_child_locations_get_meta( $post_id, $key, $default = '' ) {
		if ( function_exists( 'get_field' ) ) {
			$value = get_field( $key, $post_id );
			if ( null !== $value && false !== $value && '' !== $value ) {
				return $value;
			}
		}

		$meta = get_post_meta( $post_id, $key, true );

		return ( '' !== $meta && false !== $meta ) ? $meta : $default;
	}
}

if ( ! function_exists( 'bb_child_locations_format_address' ) ) {
	/**
	 * Build a single-line address string for a location post.
 *
	 * @param int $post_id Post ID.
	 * @return string
	 */
	function bb_child_locations_format_address( $post_id ) {
		$street  = (string) bb_child_locations_get_meta( $post_id, 'street_address' );
		$suite   = (string) bb_child_locations_get_meta( $post_id, 'suite__unit' );
		$city    = (string) bb_child_locations_get_meta( $post_id, 'city' );
		$state   = (string) bb_child_locations_get_meta( $post_id, 'state' );
		$zip     = (string) bb_child_locations_get_meta( $post_id, 'zip_code' );
		$country = (string) bb_child_locations_get_meta( $post_id, 'country' );

		$address = trim( $street );

		if ( '' !== $suite ) {
			$address .= ( '' !== $address ? ' ' : '' ) . $suite;
		}

		$city_state_zip = trim(
			implode(
				', ',
				array_filter(
					array(
						$city,
						trim( $state . ( '' !== $zip ? ' ' . $zip : '' ) ),
					)
				)
			)
		);

		if ( '' !== $city_state_zip ) {
			$address .= ( '' !== $address ? ', ' : '' ) . $city_state_zip;
		}

		if ( '' !== $country ) {
			$address .= ( '' !== $address ? ', ' : '' ) . $country;
		}

		return trim( $address );
	}
}

if ( ! function_exists( 'bb_child_locations_format_phone' ) ) {
	/**
	 * Format a stored phone number for display.
 *
	 * @param string $phone Raw phone digits.
	 * @return string
	 */
	function bb_child_locations_format_phone( $phone ) {
		$phone = preg_replace( '/\D+/', '', (string) $phone );

		if ( 10 === strlen( $phone ) ) {
			return sprintf(
				'(%s) %s-%s',
				substr( $phone, 0, 3 ),
				substr( $phone, 3, 3 ),
				substr( $phone, 6 )
			);
		}

		return $phone;
	}
}

if ( ! function_exists( 'bb_child_locations_get_map_embed' ) ) {
	/**
	 * Resolve map embed HTML for a location.
 *
	 * @param int    $post_id Post ID.
	 * @param string $address Formatted address.
	 * @return string
	 */
	function bb_child_locations_get_map_embed( $post_id, $address ) {
		$query        = '';
		$embed_fields = array( 'google_map_iframe', 'map_embed', 'google_map_embed' );

		foreach ( $embed_fields as $field_key ) {
			$embed = bb_child_locations_get_meta( $post_id, $field_key );
			if ( is_string( $embed ) && '' !== trim( $embed ) ) {
				return wp_kses(
					$embed,
					array(
						'iframe' => array(
							'src'             => true,
							'width'           => true,
							'height'          => true,
							'class'           => true,
							'loading'         => true,
							'referrerpolicy'  => true,
							'allowfullscreen' => true,
							'title'           => true,
							'style'           => true,
							'frameborder'     => true,
						),
					)
				);
			}
		}

		$google_map = bb_child_locations_get_meta( $post_id, 'google_map' );

		if ( is_array( $google_map ) ) {
			if ( ! empty( $google_map['lat'] ) && ! empty( $google_map['lng'] ) ) {
				$query = rawurlencode( $google_map['lat'] . ',' . $google_map['lng'] );
			} elseif ( ! empty( $google_map['address'] ) ) {
				$query = rawurlencode( (string) $google_map['address'] );
			}
		}

		if ( '' === $query ) {
			$lat = bb_child_locations_get_meta( $post_id, 'latitude' );
			$lng = bb_child_locations_get_meta( $post_id, 'longitude' );

			if ( '' !== $lat && '' !== $lng ) {
				$query = rawurlencode( $lat . ',' . $lng );
			} elseif ( '' !== $address ) {
				$query = rawurlencode( $address );
			}
		}

		if ( '' === $query ) {
			return '';
		}

		return sprintf(
			'<iframe class="locations-page__map-frame" src="https://maps.google.com/maps?q=%1$s&amp;output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen title="%2$s"></iframe>',
			esc_attr( $query ),
			esc_attr__( 'Google Map', 'bb-theme-child' )
		);
	}
}

if ( ! function_exists( 'bb_child_locations_get_professionals_url' ) ) {
	/**
	 * Build the View Professionals URL for a location.
 *
	 * @param int $post_id Post ID.
	 * @return string
	 */
	function bb_child_locations_get_professionals_url( $post_id ) {
		$custom_url = bb_child_locations_get_meta( $post_id, 'professionals_url' );

		if ( '' === $custom_url ) {
			$custom_url = bb_child_locations_get_meta( $post_id, 'view_professionals_url' );
		}

		if ( '' !== $custom_url ) {
			return $custom_url;
		}
		
		$page_map = array(
			'cooper'      => '/salon-professionals/?stylist_location=cooper',
			'village'     => '/salon-professionals/?stylist_location=little_road',
			'little-road' => '/salon-professionals/?stylist_location=little_road',
			'little_road' => '/salon-professionals/?stylist_location=little_road',
		);

		$post_slug  = sanitize_title( (string) get_post_field( 'post_name', $post_id ) );
		$title_slug = sanitize_title( (string) get_the_title( $post_id ) );

		foreach ( array( $post_slug, $title_slug ) as $slug ) {
			if ( isset( $page_map[ $slug ] ) ) {
				return home_url( $page_map[ $slug ] );
			}
		}

		$location_slug = (string) bb_child_locations_get_meta( $post_id, 'stylist_location_slug' );

		if ( '' === $location_slug ) {
			$term = get_term_by( 'name', get_the_title( $post_id ), 'locations' );
			if ( $term && ! is_wp_error( $term ) ) {
				$location_slug = $term->slug;
			}
		}
		
		if ( isset( $page_map[ sanitize_title( $location_slug ) ] ) ) {
			return home_url( $page_map[ sanitize_title( $location_slug ) ] );
		}

		$base_url = home_url( '/salon-professionals/' );

		if ( '' !== $location_slug ) {
			return add_query_arg( 'stylist_location', $location_slug, $base_url );
		}

		return $base_url;
	}
}

if ( ! function_exists( 'bb_child_locations_get_social_url' ) ) {
	/**
	 * Get a normalized social profile URL for a location.
	 *
	 * @param int    $post_id  Post ID.
	 * @param string $platform facebook|instagram.
	 * @return string
	 */
	function bb_child_locations_get_social_url( $post_id, $platform ) {
		$key_map = array(
			'facebook'  => array( 'facebook_url', 'facebook' ),
			'instagram' => array( 'instagram_url', 'instagram' ),
		);

		if ( ! isset( $key_map[ $platform ] ) ) {
			return '';
		}

		foreach ( $key_map[ $platform ] as $key ) {
			$url = bb_child_locations_get_meta( $post_id, $key );

			if ( ! is_string( $url ) || '' === trim( $url ) ) {
				continue;
			}

			$url = trim( $url );

			if ( ! preg_match( '#^https?://#i', $url ) ) {
				$url = 'https://' . ltrim( $url, '/' );
			}

			$url = esc_url( $url );

			if ( '' !== $url ) {
				return $url;
			}
		}

		return '';
	}
}

if ( ! function_exists( 'bb_child_locations_get_social_icon_markup' ) ) {
	/**
	 * Render a Facebook or Instagram icon for the locations page.
	 *
	 * @param string $platform facebook|instagram.
	 * @return string
	 */
	function bb_child_locations_get_social_icon_markup( $platform ) {
		$icon_paths = array(
			'facebook'  => '2026/02/Frame-1082.png',
			'instagram' => '2026/02/Group-1058.png',
		);

		$labels = array(
			'facebook'  => __( 'Facebook', 'bb-theme-child' ),
			'instagram' => __( 'Instagram', 'bb-theme-child' ),
		);

		if ( ! isset( $icon_paths[ $platform ], $labels[ $platform ] ) ) {
			return '';
		}

		$upload_dir = wp_upload_dir();
		$file_path  = trailingslashit( $upload_dir['basedir'] ) . $icon_paths[ $platform ];
		$file_url   = function_exists( 'legacy_content_upload_url' )
			? legacy_content_upload_url( $icon_paths[ $platform ] )
			: trailingslashit( $upload_dir['baseurl'] ) . $icon_paths[ $platform ];

		if ( file_exists( $file_path ) ) {
			return sprintf(
				'<img class="locations-page__social-icon" src="%1$s" alt="" width="18" height="18" loading="lazy" />',
				esc_url( $file_url )
			);
		}

		if ( 'facebook' === $platform ) {
			return '<svg class="locations-page__social-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" aria-hidden="true"><path fill="currentColor" d="M80 299.3V512H196V299.3h86.6l18-97.8H196V166.9c0-27.6 6.7-41.8 33.8-41.8h89.4V0h-95.4C82.2 0 80 90.1 80 160v41.3H16v97.8h64z"/></svg>';
		}

		return '<svg class="locations-page__social-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" aria-hidden="true"><path fill="currentColor" d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.2 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.5 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.9-26.9 26.9-14.9 0-26.9-12-26.9-26.9s12-26.9 26.9-26.9 26.9 12 26.9 26.9zm76.1 27.2c-1.7-35.3-9.9-66.7-36.2-93C381.3 39.1 349.9 30.9 314.6 29.2c-36.6-1.7-146.5-1.7-183 0-35.3 1.7-66.7 9.9-93 36.2s-34.5 56.6-36.2 93c-1.7 36.6-1.7 146.5 0 183 1.7 35.3 9.9 66.7 36.2 93s57.6 34.5 93 36.2c36.6 1.7 146.5 1.7 183 0 35.3-1.7 66.7-9.9 93-36.2s34.5-56.6 36.2-93c1.7-36.6 1.7-146.5 0-183z"/></svg>';
	}
}

add_filter(
	'body_class',
	static function ( $classes ) {
		$classes[] = 'page-locations';
		return $classes;
	}
);

get_header();

$location_post_type = post_type_exists( 'our_locations' ) ? 'our_locations' : 'our_location';

$locations_query = new WP_Query(
	array(
		'post_type'      => $location_post_type,
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => array(
			'menu_order' => 'ASC',
			'date'       => 'DESC',
		),
		'order'          => 'ASC',
	)
);
?>

<main class="locations-page">
	<div class="container locations-page__container">
		<?php if ( $locations_query->have_posts() ) : ?>
			<div class="locations-page__list">
				<?php
				while ( $locations_query->have_posts() ) :
					$locations_query->the_post();

					$post_id           = get_the_ID();
					$title             = get_the_title();
					$address           = bb_child_locations_format_address( $post_id );
					$phone_raw         = (string) bb_child_locations_get_meta( $post_id, 'contact' );
					$phone_display     = bb_child_locations_format_phone( $phone_raw );
					$facebook_url      = bb_child_locations_get_social_url( $post_id, 'facebook' );
					$instagram_url     = bb_child_locations_get_social_url( $post_id, 'instagram' );
					$map_embed          = bb_child_locations_get_map_embed( $post_id, $address );
					$professionals_url  = bb_child_locations_get_professionals_url( $post_id );
					$featured_image_url = get_the_post_thumbnail_url( $post_id, 'large' );
					$has_social_links   = ( '' !== $facebook_url || '' !== $instagram_url );
					?>
					<article <?php post_class( 'locations-page__item' ); ?> id="location-<?php echo esc_attr( (string) $post_id ); ?>">
						<!--<header class="locations-page__header">-->
						<!--	<h2 class="locations-page__title"><?php //echo esc_html( $title ); ?></h2>-->
						<!--</header>-->

						

						<div class="locations-page__details row">
							<div class="locations-page__info col-12 col-lg-6">
								<h2 class="locations-page__title"><?php echo esc_html( $title ); ?></h2>

								<?php if ( '' !== $address ) : ?>
									<p class="locations-page__address">
										<i class="fa-solid fa-location-dot" aria-hidden="true"></i>
										<span><?php echo esc_html( $address ); ?></span>
									</p>
								<?php endif; ?>

								<?php if ( '' !== $phone_raw ) : ?>
									<p class="locations-page__phone">
										<i class="fa-solid fa-phone" aria-hidden="true"></i>
										<a href="<?php echo esc_url( 'tel:+1' . preg_replace( '/\D+/', '', $phone_raw ) ); ?>">
											<?php echo esc_html( $phone_display ); ?>
										</a>
									</p>
								<?php endif; ?>

								<?php if ( $has_social_links ) : ?>
									<div class="locations-page__social">
										<?php if ( '' !== $facebook_url ) : ?>
											<a
												class="locations-page__social-link locations-page__social-link--facebook"
												href="<?php echo esc_url( $facebook_url ); ?>"
												target="_blank"
												rel="noopener noreferrer"
												aria-label="<?php echo esc_attr( sprintf( __( 'Facebook — %s', 'bb-theme-child' ), $title ) ); ?>"
											>
												<?php
												// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG/icon markup is generated safely in helper.
												echo bb_child_locations_get_social_icon_markup( 'facebook' );
												?>
											</a>
										<?php endif; ?>

										<?php if ( '' !== $instagram_url ) : ?>
											<a
												class="locations-page__social-link locations-page__social-link--instagram"
												href="<?php echo esc_url( $instagram_url ); ?>"
												target="_blank"
												rel="noopener noreferrer"
												aria-label="<?php echo esc_attr( sprintf( __( 'Instagram — %s', 'bb-theme-child' ), $title ) ); ?>"
											>
												<?php
												// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG/icon markup is generated safely in helper.
												echo bb_child_locations_get_social_icon_markup( 'instagram' );
												?>
											</a>
										<?php endif; ?>
									</div>
								<?php endif; ?>

								<a class="locations-page__button" href="<?php echo esc_url( $professionals_url ); ?>">
									<?php esc_html_e( 'View Professionals', 'bb-theme-child' ); ?>
								</a>
							</div>

							<?php if ( '' !== $map_embed ) : ?>
								<div class="locations-page__map col-12 col-lg-6">
									<?php if ( $featured_image_url ) : ?>
										<div class="locations-page__media">
											<img
												class="locations-page__image"
												src="<?php echo esc_url( $featured_image_url ); ?>"
												alt="<?php echo esc_attr( $title ); ?>"
												loading="lazy"
											/>
										</div>
									<?php endif; ?>
									<div class="locations-page__map-inner">
										<?php
										// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- iframe HTML is built with escaped attributes.
										echo $map_embed;
										?>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
		<?php else : ?>
			<div class="locations-page__empty">
				<p><?php esc_html_e( 'No locations are available at this time. Please check back soon.', 'bb-theme-child' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</main>

<?php
wp_reset_postdata();
get_footer();
