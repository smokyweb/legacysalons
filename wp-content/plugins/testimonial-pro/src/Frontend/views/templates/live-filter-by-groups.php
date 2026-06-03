<?php
/**
 * The Testimonial live filter by groups button.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/live-filter-buttons.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

if ( $filter_by_groups ) :
	if ( 'filter_button' === $filter_type ) {
		?>
<div class="button-group filters-button-group testimonial-ajax-live-filter" data-filter-group="taxonomy">
		<?php if ( $filter_all_btn_switch && ! empty( $filter_all_btn_text['taxonomy'] ) ) { ?>
			<button class="button is-checked" data-slug=""><?php echo esc_html( $filter_all_btn_text['taxonomy'] ); ?></button>
				<?php
		}
		?>
		<?php
		$first_key        = key( $filter_button_array['category'] );
		$first_button_key = $filter_button_array['category'] [ $first_key ]['slug'];
		foreach ( $filter_button_array['category'] as $testimonial_group ) :
			$button_checked = ( $first_button_key === $testimonial_group['slug'] && ( '0' === $filter_all_btn_switch ) || strtolower( $first_button_key ) === $testimonial_group['slug'] && ( '1' === $filter_all_btn_switch && empty( $filter_all_btn_text['taxonomy'] ) ) ) ? 'is-checked' : '';
			?>
			<?php
			if ( isset( $testimonial_group['name'] ) && ! empty( $testimonial_group['name'] ) ) {
				?>
		<button class="button fltr-controls <?php echo esc_attr( $button_checked ); ?> <?php echo esc_attr( $testimonial_group['slug'] ); ?>" data-slug="<?php echo esc_attr( $testimonial_group['slug'] ); ?>"><?php echo esc_html( $testimonial_group['name'] ); ?></button>
				<?php } ?>
				<?php
				endforeach;
		?>
</div>

		<?php
	}
	if ( 'filter_dropdown' === $filter_type ) {
		?>
	<div class="sp-testimonial-select testimonial-ajax-live-filter">
		<select class="filterSelect" data-filter-group="taxonomy">
		<?php if ( $filter_all_btn_switch ) : ?>
			<option value="all"><?php echo esc_html( $filter_all_btn_text['taxonomy'] ); ?></option>
				<?php
				endif;
		foreach ( $filter_button_array['category'] as $testimonial_cat ) :
			?>
			<option value="<?php echo esc_attr( $testimonial_cat['slug'] ); ?>"><?php echo esc_html( $testimonial_cat['name'] ); ?></option>
				<?php
				endforeach;
		?>
		</select>
	</div>

		<?php
	}
endif;
