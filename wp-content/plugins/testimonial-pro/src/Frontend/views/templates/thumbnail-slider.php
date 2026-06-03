<?php
/**
 * Thumbnail slider.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/thumbnail-slider.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<div id="sp-testimonial-pro-wrapper-<?php echo esc_attr( $post_id ); ?>" class="sp-testimonial-pro-wrapper sp-tpro-thumbnail-slider sp_tpro_nav_position_<?php echo esc_attr( $navigation_position ); ?> <?php echo esc_attr( $image_zoom ); ?>">
	<?php
	require self::sptp_locate_template( 'top-section.php' );
	// Thumb slider.
	?>
<div id="sp-testimonial-thumb-wrapper-<?php echo esc_attr( $post_id ); ?>" class="sp-testimonial-thumbs-wrapper">
<div id="sp-testimonial-pro-<?php echo esc_attr( $post_id ); ?>" <?php echo wp_kses_post( $the_rtl ) . ' ' . wp_kses_post( $data_attr ); ?>  class="sp-testimonial-pro-section sp-testimonial-pro-section-thumb" data-thumb_swiper='{"thumbOrientation":"<?php echo esc_attr( $_position ); ?>","slidesToShow": <?php echo esc_attr( $slides_to_show ); ?>, "autoplay": <?php echo esc_attr( $slider_auto_play ); ?>, "autoplaySpeed": <?php echo esc_attr( $slider_auto_play_speed ); ?>, "speed": <?php echo esc_attr( $slider_scroll_speed ); ?>, "arrows": <?php echo esc_attr( $show_navigation ); ?>, "pagination_type":"<?php echo esc_attr( $carousel_pagination_type ); ?>", "dots": <?php echo esc_attr( $pagination_dots ); ?>, "effect": <?php echo esc_attr( $slider_fade_effect_thumb ); ?>, "adaptiveHeight": <?php echo esc_attr( $adaptive_height ); ?>, "pauseOnHover": <?php echo esc_attr( $slider_pause_on_hover_thumb ); ?>, "swipe": <?php echo esc_attr( $slider_swipe_thumb ); ?>, "swipeToSlide": <?php echo esc_attr( $swipe_to_slide ); ?>, "draggable": <?php echo esc_attr( $slider_draggable_thumb ); ?>, "rtl": <?php echo esc_attr( $rtl_mode ); ?>, "slidesPerView": {"lg_desktop": <?php echo esc_attr( $columns_large_desktop ); ?>, "desktop": <?php echo esc_attr( $columns_desktop ); ?>, "laptop": <?php echo esc_attr( $columns_laptop ); ?>, "tablet": <?php echo esc_attr( $columns_tablet ); ?>, "mobile": <?php echo esc_attr( $columns_mobile ); ?>}}' data-arrowicon="angle">
<div class="swiper-wrapper">
	<?php echo $testimonial_items['thumbnail_slider_image_markup']; ?>
</div>
</div>
<!-- Content Slider -->
<div id="sp-testimonial-pro-<?php echo esc_attr( $post_id ); ?>" <?php echo wp_kses_post( $the_rtl ) . ' ' . wp_kses_post( $data_attr ); ?> class="sp-testimonial-pro-section sp-testimonial-pro-read-more tpro-readmore-<?php echo esc_attr( $testimonial_read_more_link_action . '-' . $testimonial_read_more_class ); ?> sp-testimonial-pro-section-content tpro-style-theme-one">
<div class="swiper-wrapper">
<?php echo wp_kses_post( $testimonial_items['thumbnail_slider_content_markup'] ); ?>
</div>
<?php
$pagination_type = '';
if ( 'strokes' === $carousel_pagination_type ) {
	$pagination_type = ' sp_testimonial-pagination-strokes';
} elseif ( 'fraction' === $carousel_pagination_type ) {
	$pagination_type = ' sp_testimonial-pagination-fraction';
}

if ( 'true' === $pagination_dots && 'thumbnail_slider' === $slider_mode && 'scrollbar' !== $carousel_pagination_type ) {
	?>
	<div class="sp-testimonial-pagination swiper-pagination <?php echo esc_attr( $pagination_type ); ?>"></div>
<?php } ?>
	<?php
	if ( $show_navigation && 'thumbnail_slider' === $slider_mode ) {
		switch ( $navigation_icons ) {
			case 'angle':
				$left_arrow  = 'sptpro-icon-angle-left';
				$right_arrow = 'sptpro-icon-angle-right';
				break;
			case 'chevron_open_big':
				$left_arrow  = 'sptpro-icon-left-open-big';
				$right_arrow = 'sptpro-icon-right-open-big';
				break;
			case 'right_open':
				$left_arrow  = 'sptpro-icon-left-open';
				$right_arrow = 'sptpro-icon-right-open';
				break;
			case 'chevron':
				$left_arrow  = 'sptpro-icon-left-open-2';
				$right_arrow = 'sptpro-icon-right-open-1';
				break;
			case 'right_open_3':
				$left_arrow  = 'sptpro-icon-left-open-4';
				$right_arrow = 'sptpro-icon-right-open-3';
				break;
			case 'right_open_outline':
				$left_arrow  = 'sptpro-icon-left-open-outline';
				$right_arrow = 'sptpro-icon-right-open-outline';
				break;
			case 'arrow':
				$left_arrow  = 'sptpro-icon-left';
				$right_arrow = 'sptpro-icon-right';
				break;
			case 'triangle':
				$left_arrow  = 'sptpro-icon-arrow-triangle-left';
				$right_arrow = 'sptpro-icon-arrow-triangle-right';
				break;
		}
		?>
		<div class="tpro-button-next tpro-arrow swiper-button-next <?php echo esc_html( $navigation_position ); ?>"><i class="<?php echo esc_html( $right_arrow ); ?>"></i></div>
		<div class="tpro-button-prev tpro-arrow swiper-button-prev <?php echo esc_html( $navigation_position ); ?>"><i class="<?php echo esc_html( $left_arrow ); ?>"></i></div><?php } ?>
		<?php
		if ( 'true' === $pagination_dots && 'thumbnail_slider' === $slider_mode && 'scrollbar' === $carousel_pagination_type ) {
			?>
		<div class="swiper-scrollbar sp_testimonial-pagination-scrollbar"></div>
		<?php } ?>
</div><!-- /Content Slider -->
</div>
</div>
