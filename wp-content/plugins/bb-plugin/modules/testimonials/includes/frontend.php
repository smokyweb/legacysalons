<?php

$testimonials_class = 'fl-testimonials-wrap ' . sanitize_html_class( $settings->layout );

if ( '' == $settings->heading && 'compact' == $settings->layout ) {
	$testimonials_class .= ' fl-testimonials-no-heading';
}

?>
<div class="<?php echo $testimonials_class; ?>">

	<?php if ( ( 'wide' != $settings->layout ) && ! empty( $settings->heading ) ) : ?>
		<h3 class="fl-testimonials-heading"><?php echo $settings->heading; ?></h3>
	<?php endif; ?>

	<?php if ( ( 'compact' == $settings->layout && $settings->arrows ) || ( 'wide' == $settings->layout && $settings->dots ) ) : ?>
		<div class="fl-slider-prev"></div>
		<div class="fl-slider-next"></div>
	<?php endif; ?>
	<<?php echo 1 === $module->version ? 'div' : 'ul'; ?> class="fl-testimonials">
		<?php

		for ( $i = 0; $i < count( $settings->testimonials ); $i++ ) :

			if ( ! is_object( $settings->testimonials[ $i ] ) ) {
				continue;
			}

			$testimonials = $settings->testimonials[ $i ];

			?>
		<<?php echo 1 === $module->version ? 'div' : 'li'; ?> class="fl-testimonial">
			<blockquote><?php echo $testimonials->testimonial; ?></blockquote>
		</<?php echo 1 === $module->version ? 'div' : 'li'; ?>>
		<?php endfor; ?>
	</<?php echo 1 === $module->version ? 'div' : 'ul'; ?>>
</div>
<?php
