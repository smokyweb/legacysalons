<?php
/**
 * Location stylists directory grouped by service.
 *
 * Expects:
 * - string $location_slug
 * - string $page_heading
 * - string $location_label
 */

if ( ! defined( 'ABSPATH' ) || empty( $location_slug ) ) {
	return;
}

$location_slugs = is_array( $location_slug )
	? array_values( array_filter( $location_slug ) )
	: array( (string) $location_slug );

if ( empty( $location_slugs ) ) {
	return;
}

$sections = bb_child_get_location_stylist_sections();
$shown_ids = array();
$active_sections = array();

foreach ( $sections as $section ) {
	$query_args = array(
		'post_type'      => 'stylist',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'title',
		'order'          => 'ASC',
		'post__not_in'   => $shown_ids,
		'tax_query'      => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'locations',
				'field'    => 'slug',
				'terms'    => $location_slugs,
				'operator' => 'IN',
			),
			array(
				'taxonomy' => 'stylist_service',
				'field'    => 'slug',
				'terms'    => $section['slugs'],
			),
		),
	);

	if ( function_exists( 'legacy_append_stylist_listing_visibility_meta_query' ) ) {
		legacy_append_stylist_listing_visibility_meta_query( $query_args );
	}

	$query = new WP_Query( $query_args );

	if ( ! $query->have_posts() ) {
		wp_reset_postdata();
		continue;
	}

	$section_posts = array();

	while ( $query->have_posts() ) {
		$query->the_post();
		$post_id = get_the_ID();

		if ( in_array( $post_id, $shown_ids, true ) ) {
			continue;
		}

		$shown_ids[]     = $post_id;
		$section_posts[] = $post_id;
	}

	wp_reset_postdata();

	if ( empty( $section_posts ) ) {
		continue;
	}

	$active_sections[] = array(
		'id'     => $section['id'],
		'title'  => $section['title'],
		'posts'  => $section_posts,
	);
}

$card_partial = get_stylesheet_directory() . '/custom-templates/partials/stylist-listing-card.php';
?>

<main class="location-stylists-page">
	<div class="container location-stylists-page__container">
		<header class="location-stylists-page__header">
			<h1 class="location-stylists-page__title">
				<?php echo esc_html( $page_heading ); ?>
			</h1>
		</header>

		<?php if ( ! empty( $active_sections ) ) : ?>
			<nav class="location-stylists-toc" aria-label="<?php esc_attr_e( 'Table of contents', 'bb-theme-child' ); ?>">
				<button
					type="button"
					class="location-stylists-toc__toggle"
					aria-expanded="false"
					aria-controls="location-stylists-toc-panel"
				>
					<?php esc_html_e( 'Table Of Contents', 'bb-theme-child' ); ?>
					<span class="location-stylists-toc__chevron" aria-hidden="true"></span>
				</button>
				<div id="location-stylists-toc-panel" class="location-stylists-toc__panel" hidden>
					<ul class="location-stylists-toc__list">
						<?php foreach ( $active_sections as $section ) : ?>
							<li>
								<a href="#<?php echo esc_attr( $section['id'] ); ?>">
									<?php echo esc_html( $section['title'] ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</nav>

			<div class="location-stylists-page__sections">
				<?php foreach ( $active_sections as $section ) : ?>
					<section
						id="<?php echo esc_attr( $section['id'] ); ?>"
						class="location-stylists-section"
						aria-labelledby="<?php echo esc_attr( $section['id'] . '-title' ); ?>"
					>
						<h2 id="<?php echo esc_attr( $section['id'] . '-title' ); ?>" class="location-stylists-section__title">
							<?php echo esc_html( $section['title'] ); ?>
						</h2>

						<div class="row location-stylists-grid g-4">
							<?php foreach ( $section['posts'] as $stylist_id ) : ?>
								<div class="col-12 col-md-6 col-lg-4">
									<?php
									if ( file_exists( $card_partial ) ) {
										include $card_partial;
									}
									?>
								</div>
							<?php endforeach; ?>
						</div>
					</section>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<div class="location-stylists-page__empty">
				<p>
					<?php
					printf(
						/* translators: %s: location name */
						esc_html__( 'No professionals are listed for %s at this time.', 'bb-theme-child' ),
						esc_html( $location_label )
					);
					?>
				</p>
			</div>
		<?php endif; ?>
	</div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
	var toggle = document.querySelector('.location-stylists-toc__toggle');
	var panel = document.getElementById('location-stylists-toc-panel');

	if (!toggle || !panel) {
		return;
	}

	toggle.addEventListener('click', function () {
		var isOpen = toggle.getAttribute('aria-expanded') === 'true';
		toggle.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
		panel.hidden = isOpen;
		toggle.classList.toggle('is-open', !isOpen);
	});
});
</script>
