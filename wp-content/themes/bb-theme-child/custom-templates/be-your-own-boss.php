<?php
/*
Template Name: Be Your Own Boss
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter(
	'body_class',
	static function ( $classes ) {
		$classes[] = 'page-be-your-own-boss';
		return $classes;
	}
);

get_header();

$nonce = wp_create_nonce( 'signup_a_suite_contact' );

$logo_url = '';
$logo_id  = (int) get_theme_mod( 'custom_logo' );

if ( $logo_id ) {
	$logo_url = (string) wp_get_attachment_image_url( $logo_id, 'full' );
}

if ( '' === $logo_url && function_exists( 'legacy_content_upload_url' ) ) {
	$logo_url = legacy_content_upload_url( '2026/01/legacy-logo_02.png' );
} elseif ( '' === $logo_url ) {
	$logo_url = content_url( 'uploads/2026/01/legacy-logo_02.png' );
}

$award_image_url = function_exists( 'legacy_content_upload_url' )
	? legacy_content_upload_url( '2026/04/find-your-suite-scaled-1.png' )
	: content_url( 'uploads/2026/04/find-your-suite-scaled-1.png' );

$features = array(
	array(
		'title'       => __( 'Be Your Own Boss', 'bb-theme-child' ),
		'description' => __( 'Be your own boss and create your own work/life balance.', 'bb-theme-child' ),
	),
	array(
		'title'       => __( 'Express Yourself', 'bb-theme-child' ),
		'description' => __( 'Design your own private salon suite to fit you and your brand\'s style.', 'bb-theme-child' ),
	),
	array(
		'title'       => __( 'Create Your Brand On Social Media', 'bb-theme-child' ),
		'description' => __( 'Share your brand on Instagram, Facebook, Threads, Google, and TikTok.', 'bb-theme-child' ),
	),
	array(
		'title'       => __( 'Services & Retail', 'bb-theme-child' ),
		'description' => __( 'Set your own prices for your services and retail your preferred products.', 'bb-theme-child' ),
	),
);

$benefits = array(
	__( 'Operate your own salon with minimum investment', 'bb-theme-child' ),
	__( 'Market and sell your own preferred products', 'bb-theme-child' ),
	__( 'Make your own schedule', 'bb-theme-child' ),
	__( 'Set your own prices', 'bb-theme-child' ),
	__( 'Design your own space', 'bb-theme-child' ),
);

$amenities = array(
	__( '2 Convenient Salon locations', 'bb-theme-child' ),
	__( '24/7 monitored and secured access', 'bb-theme-child' ),
	__( 'Spacious suites', 'bb-theme-child' ),
	__( 'Complementary WIFI', 'bb-theme-child' ),
	__( 'All utilities included', 'bb-theme-child' ),
	__( 'Onsite laundry', 'bb-theme-child' ),
	__( 'Break room', 'bb-theme-child' ),
	__( 'Comfortable seating', 'bb-theme-child' ),
	__( 'Snack machine', 'bb-theme-child' ),
);
?>

<main class="be-your-own-boss-page">
	<section class="be-your-own-boss-hero">
		<div class="container be-your-own-boss-page__container">
			<h1 class="be-your-own-boss-hero__title">
				<?php esc_html_e( 'The Salon Made for You, to be you', 'bb-theme-child' ); ?>
			</h1>

			<div class="row be-your-own-boss-features g-4">
				<?php foreach ( $features as $feature ) : ?>
					<div class="col-12 col-md-6">
						<article class="be-your-own-boss-features__item">
							<h2 class="be-your-own-boss-features__title"><?php echo esc_html( $feature['title'] ); ?></h2>
							<p class="be-your-own-boss-features__text"><?php echo esc_html( $feature['description'] ); ?></p>
						</article>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="be-your-own-boss-about">
		<div class="container be-your-own-boss-page__container">
			<h2 class="be-your-own-boss-about__title"><?php esc_html_e( 'Why Legacy Salons?', 'bb-theme-child' ); ?></h2>
			<p class="be-your-own-boss-about__intro">
				<?php esc_html_e( 'At Legacy Salons Arlington our goal is for this to be the salon for you, to be you.', 'bb-theme-child' ); ?>
			</p>

			<div class="row g-4 be-your-own-boss-about__lists">
				<div class="col-12 col-lg-6">
					<h3 class="be-your-own-boss-about__subtitle"><?php esc_html_e( 'What this means for YOU', 'bb-theme-child' ); ?></h3>
					<ul class="be-your-own-boss-about__list">
						<?php foreach ( $benefits as $benefit ) : ?>
							<li><?php echo esc_html( $benefit ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="col-12 col-lg-6">
					<h3 class="be-your-own-boss-about__subtitle"><?php esc_html_e( 'Salon Amenities', 'bb-theme-child' ); ?></h3>
					<ul class="be-your-own-boss-about__list">
						<?php foreach ( $amenities as $amenity ) : ?>
							<li><?php echo esc_html( $amenity ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>

			<p class="be-your-own-boss-about__note">
				<?php esc_html_e( 'We customize your service profile to include booking and social media links so clients can connect with you easily.', 'bb-theme-child' ); ?>
			</p>
			<p class="be-your-own-boss-about__cta">
				<?php esc_html_e( 'Ready to join us? Request information for leasing below. We look forward to connecting with you!', 'bb-theme-child' ); ?>
			</p>
		</div>
	</section>

	<section class="be-your-own-boss-form-section" id="request-leasing-information">
		<div class="container be-your-own-boss-page__container">
			<div class="row g-4 g-xl-5 align-items-stretch be-your-own-boss-form-layout">
				<div>
					<div class="be-your-own-boss-form-card">
						<h2 class="be-your-own-boss-form-card__title"><?php esc_html_e( 'Find Your Suite', 'bb-theme-child' ); ?></h2>
						<p class="be-your-own-boss-form-card__intro">
							<?php esc_html_e( 'Ready to grow your business in a private salon suite? Fill out the form below and our team will reach out to help you find the perfect space.', 'bb-theme-child' ); ?>
						</p>
						<?php
						bb_child_render_signup_suite_form(
							array(
								'form_id'      => 'be-your-own-boss-signup-form',
								'field_prefix' => 'byob',
								'nonce'        => $nonce,
								'show_heading' => false,
								'show_intro'   => false,
								'form_source'  => 'be_your_own_boss_page',
							)
						);
						?>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
