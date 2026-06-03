<?php
/**
 * Rating.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/testimonial/rating.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

switch ( $star_icon ) {
	case 'rating-star-1':
		$full_star_icon  = '<i class="sptpro-icon-star_fill" aria-hidden="true"></i>';
		$empty_star_icon = '<i class="sptpro-icon-star-border sptpro-empty-rating" aria-hidden="true"></i>';
		break;
	case 'rating-star-2':
		$full_star_icon  = '<i class="sptpro-icon-star_fill" aria-hidden="true"></i>';
		$empty_star_icon = '<i class="sptpro-icon-star_fill sptpro-empty-rating" aria-hidden="true"></i>';
		break;
	case 'rating-star-3':
		$full_star_icon  = '<i class="sptpro-icon-star_round" aria-hidden="true"></i>';
		$empty_star_icon = '<i class="sptpro-icon-star_round sptpro-empty-rating" aria-hidden="true"></i>';
		break;
	case 'rating-star-4':
		$full_star_icon  = '<i class="sptpro-icon-star_squire" aria-hidden="true"></i>';
		$empty_star_icon = '<i class="sptpro-icon-star_squire sptpro-empty-rating" aria-hidden="true"></i>';
		break;
	case 'rating-star-5':
		$full_star_icon  = '<i class="sptpro-icon-star_rounded_fill" aria-hidden="true"></i>';
		$empty_star_icon = '<i class="sptpro-icon-star_rounded_border sptpro-empty-rating" aria-hidden="true"></i>';
		break;
	case 'rating-star-6':
		$full_star_icon  = '<i class="sptpro-icon-love" aria-hidden="true"></i>';
		$empty_star_icon = '<i class="sptpro-icon-love sptpro-empty-rating" aria-hidden="true"></i>';
		break;
	case 'rating-star-6b':
		$full_star_icon  = '<i class="sptpro-icon-love" aria-hidden="true"></i>';
		$empty_star_icon = '<i class="sptpro-icon-love_border-1 sptpro-empty-rating" aria-hidden="true"></i>';
		break;
	case 'rating-star-7':
		$full_star_icon  = '<i class="sptpro-icon-like" aria-hidden="true"></i>';
		$empty_star_icon = '<i class="sptpro-icon-like sptpro-empty-rating" aria-hidden="true"></i>';
		break;
	case 'rating-star-7b':
		$full_star_icon  = '<i class="sptpro-icon-like" aria-hidden="true"></i>';
		$empty_star_icon = '<i class="sptpro-icon-like_border sptpro-empty-rating" aria-hidden="true"></i>';
		break;
	case 'rating-star-8':
		$full_star_icon  = '<i class="sptpro-icon-star_4_fill" aria-hidden="true"></i>';
		$empty_star_icon = '<i class="sptpro-icon-star_4_border sptpro-empty-rating" aria-hidden="true"></i>';
		break;
	case 'rating-star-9':
		$full_star_icon  = '<i class="sptpro-icon-hourglass" aria-hidden="true"></i>';
		$empty_star_icon = '<i class="sptpro-icon-hourglass_border-1 sptpro-empty-rating" aria-hidden="true"></i>';
		break;
	case 'rating-star-10':
		$full_star_icon  = '<i class="sptpro-icon-circle_fill" aria-hidden="true"></i>';
		$empty_star_icon = '<i class="sptpro-icon-circle_border_border-1 sptpro-empty-rating" aria-hidden="true"></i>';
		break;
	case 'rating-star-11':
		$full_star_icon  = '<i class="sptpro-icon-squire_fill" aria-hidden="true"></i>';
		$empty_star_icon = '<i class="sptpro-icon-squire_border-1 sptpro-empty-rating" aria-hidden="true"></i>';
		break;
	case 'rating-star-12':
		$full_star_icon  = '<i class="sptpro-icon-flag" aria-hidden="true"></i>';
		$empty_star_icon = '<i class="sptpro-icon-flag sptpro-empty-rating" aria-hidden="true"></i>';
		break;
	case 'rating-star-13':
		$full_star_icon  = '<i class="sptpro-icon-smile_fill" aria-hidden="true"></i>';
		$empty_star_icon = '<i class="sptpro-icon-smile_border sptpro-empty-rating" aria-hidden="true"></i>';
		break;
}
$full_star_icon  = apply_filters( 'testimonial_client_rating_full_star_icon', $full_star_icon, get_the_ID() );
$empty_star_icon = apply_filters( 'testimonial_client_rating_empty_star_icon', $empty_star_icon, get_the_ID() );

?>
<div class="tpro-client-rating">
<?php do_action( 'sptpro_before_testimonial_client_rating' ); ?>
	<?php
	switch ( $tpro_rating_star ) {
		case 'five_star':
			echo sprintf( '%1$s%1$s%1$s%1$s%1$s', $full_star_icon );
			break;
		case 'four_star':
			echo sprintf( '%1$s%1$s%1$s%1$s%2$s', $full_star_icon, $empty_star_icon );
			break;
		case 'three_star':
			echo sprintf( '%1$s%1$s%1$s%2$s%2$s', $full_star_icon, $empty_star_icon );
			break;
		case 'two_star':
			echo sprintf( '%1$s%1$s%2$s%2$s%2$s', $full_star_icon, $empty_star_icon );
			break;
		case 'one_star':
			echo sprintf( '%1$s%2$s%2$s%2$s%2$s', $full_star_icon, $empty_star_icon );
			break;
	};
	?>
<?php do_action( 'sptpro_after_testimonial_client_rating' ); ?>
</div>
