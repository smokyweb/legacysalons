<?php
/*
Template Name: SignUp A Suite
*/

add_filter(
	'body_class',
	static function ( $classes ) {
		$classes[] = 'page-signup-a-suite';
		return $classes;
	}
);

get_header();

$nonce = wp_create_nonce( 'signup_a_suite_contact' );

$signup_bg_image = content_url( 'uploads/2026/04/Rectangle-1.png' );
$attachment      = get_page_by_path( 'rectangle-1', OBJECT, 'attachment' );

if ( $attachment instanceof WP_Post ) {
	$attachment_url = wp_get_attachment_url( $attachment->ID );
	if ( $attachment_url ) {
		$signup_bg_image = $attachment_url;
	}
}
?>

<section
	class="signup-a-suite-page"
	style="--signup-bg-image: url('<?php echo esc_url( $signup_bg_image ); ?>');"
>
	<div class="container signup-a-suite-container">
		<div class="row align-items-center justify-content-center signup-a-suite-layout">
			<div class="col-12 col-md-10 col-lg-8 col-xl-7 signup-a-suite-form-col">
				<h1 class="signup-a-suite-heading"><?php esc_html_e( 'SignUp For A Suite', 'bb-theme-child' ); ?></h1>
				<p class="signup-a-suite-subheading">
					<?php esc_html_e( 'Ready to grow your business in a private salon suite? Fill out the form below and our team will reach out to help you find the perfect space.', 'bb-theme-child' ); ?>
				</p>
				<?php
				bb_child_render_signup_suite_form(
					array(
						'form_id'      => 'signup-a-suite-form',
						'field_prefix' => 'signup',
						'nonce'        => $nonce,
						'show_heading' => false,
					)
				);
				?>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
