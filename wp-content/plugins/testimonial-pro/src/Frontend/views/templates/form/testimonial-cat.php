<?php
/**
 * Testimonial cat.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/form/testimonial-cat.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

$cat_hide_class = is_array( $groups_list ) && 1 === count( $groups_list ) ? 'tpro-category-list-field-hidden' : '';
?>
<div class="sp-tpro-form-field tpro-category-list-field <?php echo esc_attr( $cat_hide_class ); ?>">
<div class="sp-testimonial-label-section">
	<?php if ( $group_label ) { ?>
	<label for="tpro_client_testimonial_cat<?php echo esc_attr( $form_id ); ?>"><?php echo esc_html( $group_label ); ?></label>
	<?php } if ( $groups_required ) { ?>
		<span class="sp-required-asterisk-symbol">*</span>
	<?php } ?>
</div> <!-- end of sp-testimonial-label-section -->
<div class="sp-testimonial-input-field">
	<?php if ( ! empty( $before ) ) { ?>
		<span class="tpro_client_before"><?php echo esc_html( $before ); ?></span>  
	<?php } if ( ! empty( $groups_list ) ) { ?>
	<select name="tpro_client_testimonial_cat[]" id="tpro_client_testimonial_cat<?php echo esc_attr( $form_id ); ?>" class="chosen-select"
		data-placeholder="<?php echo esc_attr( $groups['placeholder'] ); ?>" <?php echo wp_kses_post( $groups_multiple_selection ); ?>
		data-depend-id="tpro_client_testimonial_cat">
		<?php
		foreach ( $groups_list as $group_id ) {
			$cat_term = get_term( $group_id );
			echo '<option value="' . wp_kses_post( $cat_term->term_id ) . '">' . wp_kses_post( $cat_term->name ) . '</option>';
		}
		echo '</select>';
	} else {
		$terms = get_terms(
			'testimonial_cat',
			array(
				'hide_empty' => 0,
			)
		);
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			?>
		<select name="tpro_client_testimonial_cat[]" id="tpro_client_testimonial_cat" class="chosen-select"
			data-placeholder="<?php echo esc_attr( $groups['placeholder'] ); ?>"
			<?php echo wp_kses_post( $groups_multiple_selection ); ?> data-depend-id="tpro_client_testimonial_cat">
			<?php
			foreach ( $terms as $cat_term ) {
				echo '<option value="' . wp_kses_post( $cat_term->term_id ) . '">' . wp_kses_post( $cat_term->name ) . '</option>';
			}
			echo '</select>';
		}
	}
	?>
	<br>
<?php if ( ! empty( $after ) ) { ?>
		<span class="tpro_client_after"><?php echo esc_html( $after ); ?></span>
	<?php } ?>
</div> <!-- end of sp-testimonial-input-field -->
</div> <!-- end of sp-tpro-form-field -->
