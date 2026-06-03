<?php
/**
 * Client location.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/testimonial/client-location.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

$country_name = '';
if ( ! empty( $tpro_country ) ) {
	$before_flag = '';
	switch ( $show_testimonial_client_country ) {
		case 'name':
			$country_name = $tpro_country;
			break;
		case 'country_with_flag':
			$before_flag  = $before_country_flag ? 'before' : '';
			$flag         = self::tpro_country_flag( $tpro_country );
			$country_name = '<span class="country-name">' . esc_html( $tpro_country ) . '</span><span class="country-flag">' . esc_html( $flag ) . '</span>';
			break;
		case 'flag':
			$country_name = self::tpro_country_flag( $tpro_country );
			break;
	}
	$tpro_location = preg_replace( '/[^\w\s]$/', '', $tpro_location );
	$country_name  = 'none' !== $show_testimonial_client_country ? ',<span class="tpro-country-name ' . esc_attr( $before_flag ) . '">' . $country_name . '</span>' : '';
}

?>
<div class="tpro-client-location">
<?php
do_action( 'sptpro_before_testimonial_client_location' );
echo wp_kses_post( $tpro_location . $country_name );
do_action( 'sptpro_after_testimonial_client_location' );
?>
</div>
