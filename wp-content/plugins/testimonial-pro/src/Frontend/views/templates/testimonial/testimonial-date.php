<?php
/**
 * Testimonial date.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/testimonial/testimonial-date.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<div class="tpro-testimonial-date">
	<?php
	do_action( 'sptpro_before_testimonial_date' );
	if ( 'time_ago' === $testimonial_date_format_type ) {
		echo human_time_diff( date_i18n( 'U', strtotime( get_the_date( $testimonial_date_format ) ) ), current_time( 'timestamp' ) ) . __( ' ago', 'testimonial-pro' ); // phpcs:ignore
	} else {
		echo get_the_date( $testimonial_date_format );
	}
	do_action( 'sptpro_after_testimonial_date' );
	?>
</div>
