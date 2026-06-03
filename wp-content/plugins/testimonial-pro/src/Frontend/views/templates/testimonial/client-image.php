<?php
/**
 * Client Image.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/testimonial/client-image.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

$video_before_data = sprintf( '<a href="%1$s" class="sp-tpro-video">', $video_url );
$video_after_data  = '<i class="fa fa-play-circle-o" aria-hidden="true"></i></a>';
$img_before_data   = sprintf( '<a href="%1$s" class="sp-tpro-img">', $image_full_url[0] );
$img_after_data    = '<i class="fa fa-search" aria-hidden="true"></i></a>';

$video_before = '';
$video_after  = '';
if ( ! empty( $video_url ) && $video_icon ) {
	$video_before = $video_before_data;
	$video_after  = $video_after_data;
} elseif ( $img_lightbox ) {
	$video_before = $img_before_data;
	$video_after  = $img_after_data;
}

$image = '';
if ( 'thumbnail_slider' === $slider_mode ) {
	$image = sprintf( '<img class="tpro-img-tag" %5$s src="%1$s" width ="%2$s" height="%3$s" alt="%4$s">', $tpro_image_src, $img_width, $img_height, $img_alt, $retina_img_attr );
} else {
	$video_play_place = isset( $shortcode_data['video_play_place'] ) ? $shortcode_data['video_play_place'] : 'popup';
	if ( 'default' === $video_play_place ) {
		if ( ! empty( $video_url ) && $video_icon ) {
			$video_type = array( 'youtu', 'vimeo' );
			$match      = ( str_replace( $video_type, '', $video_url ) != $video_url );

			if ( ! $match ) {
				$video_parse     = explode( '.', $video_url );
				$video_extension = is_array( $video_parse ) && ! empty( $video_parse ) ? $video_parse [ count( $video_parse ) - 1 ] : 'mp4';
				$mine_type       = "video/$video_extension";
				$image           = '<div class="sp-testimonial-iframe-wrapper"><video preload="none" poster="' . $tpro_image_src . '"  playsinline controls><source src="' . esc_url( $video_url ) . '" type="' . $mine_type . '"></video></div>';
			} else {
				if ( strpos( $video_url, 'youtu' ) > -1 ) {
					parse_str( $video_url, $video_query_array );
					$video_id = array_values( $video_query_array )[0];
					$http     = is_ssl() ? 'https' : 'http';
					$img_url  = "$http://i.ytimg.com/vi/$video_id/hqdefault.jpg";
					// $tpro_image_src = @getimagesize( $img_url ) ? $img_url : $tpro_image_src;
					// Check if the URL is accessible
					$headers = @get_headers( $img_url );

					if ( $headers && strpos( $headers[0], '200' ) !== false ) {
						// If the image exists, get its size.
						$image_info = getimagesize( $img_url );
						if ( $image_info !== false ) {
							// Use the image URL since it exists.
							$tpro_image_src = $img_url;
						}
					} else {
						// Fallback in case the image does not exist.
						$tpro_image_src = SP_TPRO_URL . 'Frontend/assets/images/placeholder-image.png'; // Replace with your fallback image path.
					}
					$video_type = 'youtube';
				} elseif ( strpos( $video_url, 'vimeo' ) > -1 ) {
					$video_id   = substr( parse_url( $video_url, PHP_URL_PATH ), 1 );
					$data_url   = wp_remote_get( "https://vimeo.com/api/v2/video/$video_id.json" );
					$video_type = 'vimeo';
					if ( isset( $data_url ) ) {
						$thumb_data     = json_decode( wp_remote_retrieve_body( $data_url ), true );
						$tpro_image_src = isset( $thumb_data[0]['thumbnail_large'] ) ? $thumb_data[0]['thumbnail_large'] : null;
					}
				}
				$image = '<div class="sptestimonial-lazy sp-testimonial-iframe-wrapper" data-embed="' . esc_attr( $video_id ) . '" data-type="' . esc_attr( $video_type ) . '"  data-width="600" data-height="400"><img class="iframe-preview" src="' . esc_url( $tpro_image_src ) . '" alt=""></div>';

			}
		} else {
			$image = sprintf( '%1$s<img %8$s src="%2$s" width ="%5$s" height="%6$s" alt="%7$s" class="tpro-img-tag tpro-grayscale-%4$s" >%3$s', $video_before, $tpro_image_src, $video_after, $image_grayscale, $img_width, $img_height, $img_alt, $retina_img_attr );
		}
	} else {
		$image = sprintf( '%1$s<img %8$s src="%2$s" width ="%5$s" height="%6$s" alt="%7$s" class="tpro-img-tag tpro-grayscale-%4$s" >%3$s', $video_before, $tpro_image_src, $video_after, $image_grayscale, $img_width, $img_height, $img_alt, $retina_img_attr );
	}
}

// If post has no thumbnail & reviewer fallback image is set to text avatar, Set avatar image.
if ( 'text_avatar' === $reviewer_fallback_image_type && ! has_post_thumbnail() ) {
	$text_avatar = self::get_text_avatar( $tpro_name, get_the_ID() );
	$image       = sprintf( '<div class="smart-text-avatar">%1$s%2$s%3$s</div>', $video_before, $text_avatar, $video_after );
}

if ( 'text_avatar' === $reviewer_fallback_image_type || 'mystery_person' === $reviewer_fallback_image_type || has_post_thumbnail() ) {
	?><div class="tpro-client-image tpro-image-style-<?php echo esc_attr( $client_image_style ); ?>">
	<?php
	do_action( 'sptpro_before_testimonial_client_image' );
	echo $image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	do_action( 'sptpro_after_testimonial_client_image' );
	?>
	</div>
	<?php
}
