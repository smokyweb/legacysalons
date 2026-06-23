<?php
/**
 * Stylist listing card (matches /stylists/ page card design).
 *
 * Expects $stylist_id (int).
 */

if ( ! defined( 'ABSPATH' ) || empty( $stylist_id ) ) {
	return;
}

$post_id      = (int) $stylist_id;
$profile_image = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
$post_title   = get_the_title( $post_id );
$business_name = get_post_meta( $post_id, 'business_name', true );
$specialization = get_post_meta( $post_id, 'specialization', true );
$contact      = get_post_meta( $post_id, 'contact', true );
$city         = get_post_meta( $post_id, 'city', true );
$state        = get_post_meta( $post_id, 'state', true );
$suite        = get_post_meta( $post_id, 'suite', true );
$booking_url  = get_post_meta( $post_id, 'booking_link', true );
$instagram_url = get_post_meta( $post_id, 'instagram', true );
$facebook_url = get_post_meta( $post_id, 'facebook', true );
$appointment_type_array = get_post_meta( $post_id, 'appointment_type', true );
$appointment_type = '';

if ( is_array( $appointment_type_array ) && ! empty( $appointment_type_array[0] ) ) {
	$appointment_type = $appointment_type_array[0];
}

$stylist_suite = '';
if ( ! empty( $suite ) ) {
	$stylist_suite = 'Suite ' . $suite;
}

if ( 'S' === $state ) {
	$state = '';
} elseif ( '' !== $state ) {
	$state = ', ' . $state;
}

$formatted_contact = '';
if ( $contact ) {
	$formatted_contact = sprintf(
		'(%s) %s-%s',
		substr( $contact, 0, 3 ),
		substr( $contact, 3, 3 ),
		substr( $contact, 6 )
	);
}

$default_profile_image = function_exists( 'legacy_content_upload_url' )
	? legacy_content_upload_url( '2026/01/imageprofile.png' )
	: content_url( 'uploads/2026/01/imageprofile.png' );

$profile_url = site_url( '/stylist-profile/?stylist_id=' . $post_id );
?>
<article class="location-stylists-card">
	<div class="salon-list-item p-4 shadow-sm d-flex align-items-center flex-wrap flex-md-nowrap h-100">
		<div class="me-4 mb-3 mb-md-0">
			<div class="profile-thumb bg-secondary-subtle d-flex align-items-center justify-content-center">
				<img
					src="<?php echo esc_url( $profile_image ? $profile_image : $default_profile_image ); ?>"
					alt="<?php echo esc_attr( $post_title ); ?>"
					class="img-fluid"
				/>
				<?php if ( ! empty( $appointment_type ) ) : ?>
					<p class="appointment"><?php echo esc_html( $appointment_type ); ?></p>
				<?php endif; ?>
			</div>
		</div>

		<div class="flex-grow-1 px-3">
			<h5 class="fw-bold mb-0 mt-0"><?php echo esc_html( $post_title ? $post_title : 'N/A' ); ?></h5>
			<div class="mb-2 text-warning">
				<i class="bi bi-star" aria-hidden="true"></i>
				<i class="bi bi-star" aria-hidden="true"></i>
				<i class="bi bi-star" aria-hidden="true"></i>
				<i class="bi bi-star" aria-hidden="true"></i>
				<i class="bi bi-star" aria-hidden="true"></i>
				<span class="rating small">(4.9 | 157 reviews)</span>
			</div>
			<div class="text-muted small mb-0 mt-2">
				<p class="stylist-info">
					<i class="bi bi-person" aria-hidden="true"></i>
					<?php echo esc_html( $specialization ? $specialization : 'N/A' ); ?>
				</p>
				<p>
					<i class="bi bi-building" aria-hidden="true"></i>
					<?php echo esc_html( $stylist_suite ? $stylist_suite : 'N/A' ); ?>
				</p>
				<p>
					<i class="bi bi-geo-alt" aria-hidden="true"></i>
					<?php
					echo esc_html(
						( $business_name && $city )
							? $business_name . ' - ' . $city . $state
							: 'N/A'
					);
					?>
				</p>
				<p>
					<i class="bi bi-phone" aria-hidden="true"></i>
					<?php echo esc_html( $formatted_contact ? $formatted_contact : 'N/A' ); ?>
				</p>
			</div>
			<div class="d-flex gap-2 mb-4 justify-content-center mt-2">
				<?php if ( ! empty( $booking_url ) ) : ?>
					<a href="<?php echo esc_url( $booking_url ); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-book-sm shadow-sm">
						<?php esc_html_e( 'Book Now', 'bb-theme-child' ); ?>
					</a>
				<?php elseif ( ! empty( $instagram_url ) ) : ?>
					<a href="<?php echo esc_url( $instagram_url ); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-book-sm shadow-sm">
						<?php esc_html_e( 'Instagram', 'bb-theme-child' ); ?>
					</a>
				<?php elseif ( ! empty( $facebook_url ) ) : ?>
					<a href="<?php echo esc_url( $facebook_url ); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-book-sm shadow-sm">
						<?php esc_html_e( 'Facebook', 'bb-theme-child' ); ?>
					</a>
				<?php endif; ?>
				<a href="<?php echo esc_url( $profile_url ); ?>" class="btn btn-outline-profile btn-sm">
					<?php esc_html_e( 'View Profile', 'bb-theme-child' ); ?>
				</a>
			</div>
		</div>
	</div>
</article>
