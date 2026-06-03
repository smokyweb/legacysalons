<?php
/**
 * Location.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/form/location.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<div class="sp-tpro-form-field">
<div class="sp-testimonial-label-section">
	<?php if ( $location_label ) { ?>
		<label for="tpro_client_location<?php echo esc_attr( $form_id ); ?>"><?php echo esc_html( $location_label ); ?></label>
		<?php } if ( $location_required ) { ?>
		<span class="sp-required-asterisk-symbol">*</span>
	<?php } ?>
</div> <!-- end of sp-testimonial-label-section -->
<div class="sp-testimonial-input-field">
	<?php if ( ! empty( $before ) ) { ?>
		<span class="tpro_client_before"><?php echo esc_html( $before ); ?></span>  
	<?php } ?>
	<div class="tpro-location-wrapper">
	<input type="text" name="tpro_client_location" id="tpro_client_location<?php echo esc_attr( $form_id ); ?>" <?php echo esc_html( $location_required ); ?> placeholder="<?php echo esc_attr( $location['placeholder'] ); ?>" />
	<?php if ( $country ) : ?>
	<select name="tpro_client_location_country" id="tpro_client_location_country<?php echo esc_attr( $form_id ); ?>" class="tpro-country-select"></select>
	<?php endif; ?>
	</div>
	<?php if ( ! empty( $after ) ) { ?>
		<span class="tpro_client_after"><?php echo esc_html( $after ); ?></span>
	<?php } ?>
</div> <!-- end of sp-testimonial-input-field -->
</div> <!-- end of sp-tpro-form-field -->
