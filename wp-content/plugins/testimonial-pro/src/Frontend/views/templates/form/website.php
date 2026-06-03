<?php
/**
 * Website.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/form/website.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<div class="sp-tpro-form-field">
<div class="sp-testimonial-label-section">
	<?php if ( $website_label ) { ?>
	<label for="tpro_client_website<?php echo esc_attr( $form_id ); ?>"><?php echo esc_html( $website_label ); ?></label>
	<?php } if ( $website_required ) { ?>
		<span class="sp-required-asterisk-symbol">*</span>
	<?php } ?>
</div> <!-- end of sp-testimonial-label-section -->
<div class="sp-testimonial-input-field">
	<?php if ( ! empty( $before ) ) { ?>
		<span class="tpro_client_before"><?php echo esc_html( $before ); ?></span>  
	<?php } ?>
	<input type="text" name="tpro_client_website" id="tpro_client_website<?php echo esc_attr( $form_id ); ?>" <?php echo esc_html( $website_required ); ?> placeholder="<?php echo esc_attr( $website['placeholder'] ); ?>" />
	<?php if ( ! empty( $after ) ) { ?>
		<span class="tpro_client_after"><?php echo esc_html( $after ); ?></span>
	<?php } ?>
</div> <!-- end of sp-testimonial-input-field -->
</div> <!-- end of sp-tpro-form-field -->
