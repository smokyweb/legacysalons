<?php
/**
 * The Helper class to manage all public facing stuffs.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package Testimonial_pro.
 * @subpackage Testimonial_Pro/Frontend
 */

namespace ShapedPlugin\TestimonialPro\Frontend;

use ShapedPlugin\TestimonialPro\Includes\SP_Testimonial_Pro_Functions;
use ShapedPlugin\TestimonialPro\Includes\ImageResize;

/**
 * Helper
 */
class Helper {

	/**
	 * Custom Template locator.
	 *
	 * @param  mixed $template_name template name.
	 * @param  mixed $template_path template path.
	 * @param  mixed $default_path default path.
	 * @return string
	 */
	public static function sptp_locate_template( $template_name, $template_path = '', $default_path = '' ) {
		if ( ! $template_path ) {
			$template_path = 'testimonial-pro/templates';
		}

		if ( ! $default_path ) {
			$default_path = SP_TPRO_PATH . 'Frontend/views/templates/';
		}

		$template = locate_template( trailingslashit( $template_path ) . $template_name );

		// Get default template.
		if ( ! $template ) {
			$template = $default_path . $template_name;
		}
		// Return what we found.
		return $template;
	}

	/**
	 * Redirect function.
	 *
	 * @param string $url url.
	 * @param string $hash hash url.
	 * @return void
	 */
	public static function tpro_redirect( $url, $hash = false ) {
		if ( $hash ) {
			$string  = '<script type="text/javascript">';
			$string .= '
			var elmnt = document.getElementById("' . $url . '");
			elmnt.scrollIntoView(true);
			// window.location.hash = "' . $url . '"';
			$string .= '</script>';
		} else {
			$string  = '<script type="text/javascript">';
			$string .= 'window.location = "' . esc_url( $url ) . '"';
			$string .= '</script>';
		}

		echo $string;
	}

	/**
	 * Create reviewer name avatar
	 *
	 * @param string $name Reviewer Name.
	 * @param int    $post_id Post ID.
	 * @return string
	 */
	public static function get_text_avatar( $name, $post_id ) {
		$name_slice = explode( ' ', $name );
		$name_slice = array_filter( $name_slice );
		$initials   = '';
		$initials  .= ( isset( $name_slice[0][0] ) ) ? strtoupper( $name_slice[0][0] ) : '';
		$initials  .= ( isset( $name_slice[ count( $name_slice ) - 1 ][0] ) ) ? strtoupper( $name_slice[ count( $name_slice ) - 1 ][0] ) : '';

		return '<div class="sp-testimonial-text-avatar" style="background-color: ' . self::get_random_color( $post_id ) . '">' . $initials . '</div>';
	}

	/**
	 * Generate random color.
	 *
	 * @return string
	 */
	public static function generate_random_color() {
		return '#' . str_pad( dechex( mt_rand( 0, 0xFFFFFF ) ), 6, '0', STR_PAD_LEFT );
	}

	/**
	 * Get random colors.
	 *
	 * @param int $post_id Post ID.
	 * @return string
	 */
	public static function get_random_color( $post_id ) {
		$colors = get_transient( 'random_colors' . $post_id );

		// If the colors are not cached or the array is empty, generate new colors.
		if ( false === $colors || empty( $colors ) ) {
			$colors = array();
			for ( $i = 0; $i < 20; $i++ ) {
				$colors[] = self::generate_random_color();
			}
			set_transient( 'random_colors' . $post_id, $colors, DAY_IN_SECONDS ); // Cache for 24 hours.
		}

		return array_shift( $colors );
	}

	/**
	 * The social icons.
	 *
	 * @param string $social_name social name.
	 * @return string
	 */
	public static function tpro_social_icon( $social_name ) {
		$function         = new SP_Testimonial_Pro_Functions();
		$social_icon_list = $function->social_profile_list();
		foreach ( $social_icon_list as $social_icon_id => $social_icon ) {
			if ( $social_icon_id == $social_name ) {
				return $social_icon['icon'];
			}
		}
	}

	/**
	 * The Country Flag.
	 *
	 * @param string $country_name country name.
	 * @return string
	 */
	public static function tpro_country_flag( $country_name ) {
		$function  = new SP_Testimonial_Pro_Functions();
		$flag_list = $function->get_country_list();
		foreach ( $flag_list as $flag ) {
			if ( $flag['name'] === $country_name ) {
				return $flag['flag'];
			}
		}
	}

	/**
	 * Image resize function to crop the images with custom width and height.
	 *
	 * @param string  $url The image URL.
	 * @param int     $width The width of the image.
	 * @param int     $height The image height.
	 * @param boolean $crop Hard crop or not.
	 * @param boolean $single  The image.
	 * @param boolean $upscale The upscale.
	 * @return statement
	 */
	public static function sptp_image_resize( $url, $width = null, $height = null, $crop = null, $single = true, $upscale = false ) {
		/*
		WPML Fix
		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
		global $sitepress;
		$url = $sitepress->convert_url( $url, $sitepress->get_default_language() );
		}
		WPML Fix
		*/
		$sptp_image_resize = ImageResize::getInstance();
		return $sptp_image_resize->process( $url, $width, $height, $crop, $single, $upscale );
	}

	/**
	 * Short content of the text.
	 *
	 * @param mixed  $testimonial_strip_tags use allow and strip tags.
	 * @param mixed  $testimonial_content_length_type get word and characters.
	 * @param mixed  $full_content get full content.
	 * @param int    $testimonial_word_limit The number of words to display.
	 * @param int    $testimonial_characters_limit The number of words to display.
	 * @param string $testimonial_read_more_ellipsis The ellipsis at the end of the text.
	 * @return statement
	 */
	public static function short_content( $testimonial_strip_tags, $testimonial_content_length_type, $full_content, $testimonial_word_limit, $testimonial_characters_limit, $testimonial_read_more_ellipsis ) {
		if ( 'characters' === $testimonial_content_length_type ) {
			$short_content = self::limit_content_chr( $full_content, (int) $testimonial_characters_limit, $testimonial_read_more_ellipsis );
		} else {
			$short_content = self::limit_content_words( $full_content, (int) $testimonial_word_limit, $testimonial_read_more_ellipsis );
		}
		return force_balance_tags( $short_content );
	}

	/**
	 * Characters Limit of the text.
	 *
	 * @param mixed  $text The text you want to limit.
	 * @param int    $limit The number of words to display.
	 * @param string $ellipsis The ellipsis at the end of the text.
	 * @return statement
	 */
	public static function limit_content_chr( $text, $limit, $ellipsis = '...' ) {
		$total_length = 0;  // Keeps track of the total number of characters.
		$output       = '';       // Stores the final output.
		$is_tag       = false;    // Checks if we are inside an HTML tag.
		$is_entity    = false; // Checks if we are inside an HTML entity (e.g., &amp;).

		$text_length = mb_strlen( $text ); // Get the length of the text in multi-byte format.

		for ( $i = 0; $i < $text_length; $i++ ) {
			$char = mb_substr( $text, $i, 1 ); // Extract one character at a time, considering multi-byte characters.

			if ( '<' === $char ) {
				$is_tag = true; // Starting an HTML tag.
			} elseif ( '&' === $char ) {
				$is_entity = true; // Starting an HTML entity.
			} elseif ( '>' === $char && $is_tag ) {
				$is_tag = false; // Ending an HTML tag.
			} elseif ( ';' === $char && $is_entity ) {
				$is_entity = false; // Ending an HTML entity.
			} elseif ( ! $is_tag && ! $is_entity ) {
				++$total_length; // Increment the character count.
			}

			$output .= $char;

			// Check if we've reached the character limit.
			if ( $total_length >= $limit ) {
				break;
			}
		}

		// Append ellipsis if the text is longer than the limit.
		if ( $total_length >= $limit && $i < $text_length - 1 ) {
			$output .= $ellipsis;
		}

		return $output;
	}

	/**
	 * Limit the the text.
	 *
	 * @param mixed  $text The text you want to limit.
	 * @param int    $limit The number of words to display.
	 * @param string $ellipsis The ellipsis at the end of the text.
	 * @return statement
	 */
	public static function limit_content_words( $text, $limit, $ellipsis = '...' ) {
		$word_arr = explode( ' ', $text );
		if ( count( $word_arr ) > $limit ) {
			$words = implode( ' ', array_slice( $word_arr, 0, $limit ) ) . $ellipsis;
			return $words;
		} else {
			return $text;
		}
	}

	/**
	 * Minify output
	 *
	 * @param  statement $html output.
	 * @return statement
	 */
	public static function minify_output( $html ) {
		$html = preg_replace( '/<!--(?!s*(?:[if [^]]+]|!|>))(?:(?!-->).)*-->/s', '', $html );
		$html = str_replace( array( "\r\n", "\r", "\n", "\t" ), '', $html );
		while ( stristr( $html, '  ' ) ) {
			$html = str_replace( '  ', ' ', $html );
		}
		return $html;
	}

	/**
	 * Testimonial items.
	 *
	 * @param  object $post_query Query.
	 * @param  array  $shortcode_data options.
	 * @param  string $layout layout.
	 * @param  string $slider_mode Slider Mode.
	 * @param  string $carousel_mode Carouse Mode.
	 * @param  int    $post_id shortcode id.
	 * @param  int    $paged_id shortcode id.
	 * @return array
	 */
	public static function testimonial_items( $post_query, $shortcode_data, $layout, $slider_mode, $carousel_mode, $post_id, $paged_id = 1 ) {
		include SP_TPRO_PATH . 'Frontend/views/partials/loop-settings.php';

		$current_page_id = '';
		$grid_pagination = isset( $shortcode_data['grid_pagination'] ) ? $shortcode_data['grid_pagination'] : '';
		// Check that layout is not slider and pagination is enabled and add current page id in cache_key.
		if ( $grid_pagination && ! in_array( $layout, array( 'slider', 'carousel', 'thumbnail_slider', 'multi_rows' ), true ) && get_queried_object_id() ) {
			$current_page_id = get_queried_object_id() . '_';
		}
		$cache_key  = 'sp_testimonial_items_' . $post_id . '_' . $paged_id . '_' . $current_page_id . SP_TPRO_VERSION;
		$cache_data = self::sp_testimonial_get_transient( $cache_key );
		if ( false !== $cache_data ) {
			$testimonial_items = $cache_data;
		} else {
			ob_start();
			$post_ids    = $post_query['all_testimonial_ids'];
			$post_query  = $post_query['query'];
			$total_posts = '';
			if ( $post_query->have_posts() ) {
				$tpro_total_rating   = 0;
				$total_posts         = $post_query->found_posts;
				$per_page_post_count = $post_query->post_count;
				$testimonial_count   = 0;
				$aggregate_rating    = 0;
				while ( $post_query->have_posts() ) :
					$post_query->the_post();
					global $post;
					$testimonial_data  = get_post_meta( get_the_ID(), 'sp_tpro_meta_options', true );
					$tpro_rating_star  = isset( $testimonial_data['tpro_rating'] ) ? $testimonial_data['tpro_rating'] : '';
					$tpro_designation  = isset( $testimonial_data['tpro_designation'] ) ? $testimonial_data['tpro_designation'] : '';
					$tpro_company_logo = isset( $testimonial_data['tpro_company_logo']['id'] ) ? $testimonial_data['tpro_company_logo']['id'] : '';
					$tpro_name         = isset( $testimonial_data['tpro_name'] ) ? $testimonial_data['tpro_name'] : '';
					$tpro_company_name = isset( $testimonial_data['tpro_company_name'] ) ? $testimonial_data['tpro_company_name'] : '';
					$tpro_website      = isset( $testimonial_data['tpro_website'] ) ? $testimonial_data['tpro_website'] : '';
					$tpro_location     = isset( $testimonial_data['testimonial_clients_location']['tpro_location'] ) ? $testimonial_data['testimonial_clients_location']['tpro_location'] : '';
					$tpro_country      = isset( $testimonial_data['testimonial_clients_location']['tpro_country'] ) ? $testimonial_data['testimonial_clients_location']['tpro_country'] : '';
					$tpro_phone        = isset( $testimonial_data['tpro_phone'] ) ? $testimonial_data['tpro_phone'] : '';
					$tpro_email        = isset( $testimonial_data['tpro_email'] ) ? $testimonial_data['tpro_email'] : '';

					$full_content = apply_filters( 'the_content', get_the_content() );
					if ( $testimonial_strip_tags ) {
						$full_content = wp_strip_all_tags( $full_content );
					}
					$short_content = self::short_content( $testimonial_strip_tags, $testimonial_content_length_type, $full_content, $testimonial_word_limit, $testimonial_characters_limit, $testimonial_read_more_ellipsis );
					$count         = strlen( $full_content );
					$review_text   = ( 'full_content' === $testimonial_content_type || $testimonial_read_more && 'expand' === $testimonial_read_more_link_action ) ? $full_content : $short_content;
					$review_text   = apply_filters( 'sp_testimonial_review_content', $review_text, $post_id );
					if ( ! $show_testimonial_text && $testimonial_read_more ) {
						$testimonial_read_more_link_action = 'popup';
					}
					switch ( $testimonial_read_more_link_action ) {
						case 'expand':
							$read_more_data = '<a href="#" class="tpro-read-more"></a>';
							break;
						case 'popup':
							$read_more_data = sprintf( '<a href="#" data-remodal-target="sp-tpro-testimonial-id-%1$s" class="tpro-read-more">%2$s</a>', get_the_ID(), $testimonial_read_more_text );
							break;
					}
					$read_more_link = ( $count >= $testimonial_characters_limit && $testimonial_read_more && 'content_with_limit' === $testimonial_content_type ) ? $read_more_data : '';
					$read_more_link = apply_filters( 'sp_testimonial_read_more_link', $read_more_link, $post_id );

					switch ( $testimonial_title_length_type ) {
						case 'characters':
							$testimonial_title_with_limit = ( '' !== $title_character_limit ) ? wp_html_excerpt( get_the_title(), $title_character_limit, '...' ) : get_the_title();
							break;
						case 'words':
							$testimonial_title_with_limit = ( '' !== $title_character_limit ) ? self::limit_content_words( get_the_title(), (int) $title_word_limit, '...' ) : get_the_title();
							break;
					}

					$t_cat_terms = get_the_terms( get_the_ID(), 'testimonial_cat' );
					if ( $t_cat_terms && ! is_wp_error( $t_cat_terms ) && ! empty( $t_cat_terms ) ) {
						$t_cat_name = array();
						foreach ( $t_cat_terms as $t_cat_term ) {
							$t_cat_name[] = $t_cat_term->name;
						}
						$tpro_itemreviewed = $t_cat_name[0];
					} else {
						$tpro_itemreviewed = $tpro_global_item_name;
					}

					switch ( $layout ) {
						case 'grid':
						case 'masonry':
						case 'filter':
						case 'list':
							$layout_class = sprintf( ( join( ' ', get_post_class( 'tpro-col-xl-%1$s tpro-col-lg-%2$s tpro-col-md-%3$s tpro-col-sm-%4$s tpro-col-xs-%5$s' ) ) ), $columns_large_desktop, $columns_desktop, $columns_laptop, $columns_tablet, $columns_mobile );
							break;
						case 'slider':
						case 'carousel':
						case 'multi_rows':
							if ( 'fade' === $slider_animation ) {
								$layout_class = '';
							} else {
								$layout_class = 'swiper-slide';
							}
							break;
						case 'thumbnail_slider':
							include SP_TPRO_PATH . 'Frontend/views/partials/thumbnail-slider-items.php';
							if ( 'fade' === $slider_animation ) {
								$layout_class = '';
							} else {
								$layout_class = 'swiper-slide';
							}
							break;
						default:
							$layout_class = '';
							break;
					}

					include SP_TPRO_PATH . 'Frontend/views/partials/item.php';

					// Schema markup.
					if ( $tpro_schema_markup ) {
						$tpro_name        = isset( $testimonial_data['tpro_name'] ) ? $testimonial_data['tpro_name'] : '';
						$tpro_rating_star = isset( $testimonial_data['tpro_rating'] ) ? $testimonial_data['tpro_rating'] : '';

						switch ( $tpro_rating_star ) {
							case 'five_star':
								$rating_value = '5';
								break;
							case 'four_star':
								$rating_value = '4';
								break;
							case 'three_star':
								$rating_value = '3';
								break;
							case 'two_star':
								$rating_value = '2';
								break;
							case 'one_star':
								$rating_value = '1';
								break;
							default:
								$rating_value = '5';
						}

						$name         = get_the_title() ? esc_attr( wp_strip_all_tags( get_the_title() ) ) : '';
						$review_body  = get_the_content() ? esc_attr( wp_strip_all_tags( get_the_content() ) ) : '';
						$date         = get_the_date( 'F j, Y' );
						$schema_html .= '{
							"@type": "Review",
							"datePublished": "' . $date . '",
							"name": "' . $name . '",
							"reviewBody": "' . $review_body . '",
							"reviewRating": {
								"@type": "Rating",
								"bestRating": "5",
								"ratingValue": "' . $rating_value . '",
								"worstRating": "1"
							},
							"author": {
								"@type": "Person",
								"name": "' . $tpro_name . '"
							}
						}';

						if ( ++$testimonial_count !== $per_page_post_count ) {
							$schema_html .= ',';
						}
					}
					endwhile;

			} else {
				echo '<h2 class="sp-not-found-any-testimonial">' . esc_html( apply_filters( 'sptpro_not_found_any_testimonial', __( 'No testimonials found', 'testimonial-pro' ) ) ) . '</h2>';
			}
			wp_reset_postdata();
			$output           = ob_get_clean();
			$aggregate_rating = 5;

			$tpro_total_rating = 0;
			foreach ( $post_ids as $id ) {
				$testimonial_data = get_post_meta( $id, 'sp_tpro_meta_options', true );
				$tpro_rating_star = isset( $testimonial_data['tpro_rating'] ) ? $testimonial_data['tpro_rating'] : '';
				switch ( $tpro_rating_star ) {
					case 'five_star':
						$rating_value = '5';
						break;
					case 'four_star':
						$rating_value = '4';
						break;
					case 'three_star':
						$rating_value = '3';
						break;
					case 'two_star':
						$rating_value = '2';
						break;
					case 'one_star':
						$rating_value = '1';
						break;
					default:
						$rating_value = '5';
				}
				$tpro_total_rating += (int) $rating_value;
			}
			$total_posts      = $total_posts > 0 ? $total_posts : 1;
			$aggregate_rating = round( ( $tpro_total_rating / $total_posts ), 2 );

			$testimonial_items = array(
				'output'                          => $output,
				'aggregate_rating'                => $aggregate_rating,
				'schema_html'                     => $schema_html,
				'total_testimonial'               => $total_posts,
				'thumbnail_slider_content_markup' => $thumbnail_slider_content_markup,
				'thumbnail_slider_image_markup'   => $thumbnail_slider_image_markup,
			);
			self::sp_testimonial_set_transient( $cache_key, $testimonial_items );
		}
		return $testimonial_items;
	}

	/**
	 * Testimonial title and content text length counter.
	 *
	 * @param string $specific_id Specific ID.
	 * @param string $char_limit Character Limit.
	 * @param string $word_limit Word Limit.
	 * @param string $length_type Length Type (Character or Words).
	 * @return void
	 * @since 3.0.0
	 */
	public static function render_text_length_counter( $specific_id, $char_limit, $word_limit, $length_type ) {
		$data_attributes = wp_json_encode(
			array(
				'characters' => esc_attr( $char_limit ),
				'words'      => esc_attr( $word_limit ),
				'type'       => esc_attr( $length_type ),
			)
		);

		$display_text = ( 'characters' === $length_type ) ? '0 characters out of ' . esc_html( $char_limit ) : '0 words out of ' . esc_html( $word_limit );

		if ( ( 'characters' === $length_type && $char_limit > 0 ) || ( 'characters' !== $length_type && $word_limit > 0 ) ) {
			echo '<span class="sp-maximum_length" data-length_type=\'' . esc_attr( $data_attributes ) . '\' id="' . esc_attr( $specific_id ) . '">' . esc_html( $display_text ) . '</span>';
		}
	}

	/**
	 * The font variants for the Advanced Typography.
	 *
	 * @param string $sp_tpro_font_variant The typography field ID with.
	 * @param string $font_style font style.
	 * @return string
	 * @since 1.0
	 */
	public static function tpro_the_font_variants( $sp_tpro_font_variant, $font_style = 'normal' ) {
		$filter_style  = isset( $font_style ) && ! empty( $font_style ) ? $font_style : 'normal';
		$filter_weight = isset( $sp_tpro_font_variant ) && ! empty( $sp_tpro_font_variant ) ? $sp_tpro_font_variant : '400';

		return 'font-style: ' . $filter_style . '; font-weight: ' . $filter_weight . ';';
	}

	/**
	 * Advanced Typography Output.
	 *
	 * @param string $tpro_normal_typography The typography array.
	 * @param string $font_load The typography font load conditions.
	 * @return string
	 * @since 1.0
	 */
	public static function tpro_typography_output( $tpro_normal_typography, $font_load ) {
		$typo = '';
		if ( isset( $tpro_normal_typography['color'] ) ) {
			$typo .= 'color: ' . $tpro_normal_typography['color'] . ';';
		}
		if ( isset( $tpro_normal_typography['font-size'] ) ) {
			$typo .= 'font-size: ' . $tpro_normal_typography['font-size'] . 'px;';
		}
		if ( isset( $tpro_normal_typography['line-height'] ) ) {
			$typo .= 'line-height: ' . $tpro_normal_typography['line-height'] . 'px;';
		}
		if ( isset( $tpro_normal_typography['text-transform'] ) ) {
			$typo .= 'text-transform: ' . $tpro_normal_typography['text-transform'] . ';';
		}
		if ( isset( $tpro_normal_typography['letter-spacing'] ) ) {
			$typo .= 'letter-spacing: ' . $tpro_normal_typography['letter-spacing'] . 'px;';
		}
		if ( isset( $tpro_normal_typography['text-align'] ) ) {
			$typo .= 'text-align: ' . $tpro_normal_typography['text-align'] . ';';
		}
		if ( $font_load ) {
			$typo .= ' font-family: ' . $tpro_normal_typography['font-family'] . ';
			' . self::tpro_the_font_variants( $tpro_normal_typography['font-weight'], $tpro_normal_typography['font-style'] );
		}
		return $typo;
	}

	/**
	 * Advanced Typography Output.
	 *
	 * @param string $tpro_normal_typography The typography array.
	 * @param string $font_load The typography font load conditions.
	 * @return string
	 * @since 1.0
	 */
	public static function tpro_typography_modal_output( $tpro_normal_typography, $font_load ) {
		$typo = 'color: #444444; text-align: center;';
		if ( isset( $tpro_normal_typography['font-size'] ) ) {
			$typo .= 'font-size: ' . $tpro_normal_typography['font-size'] . 'px;';
		}
		if ( isset( $tpro_normal_typography['line-height'] ) ) {
			$typo .= 'line-height: ' . $tpro_normal_typography['line-height'] . 'px;';
		}if ( isset( $tpro_normal_typography['text-transform'] ) ) {
			$typo .= 'text-transform: ' . $tpro_normal_typography['text-transform'] . ';';
		}
		if ( isset( $tpro_normal_typography['letter-spacing'] ) ) {
			$typo .= 'letter-spacing: ' . $tpro_normal_typography['letter-spacing'] . 'px;';
		}
		if ( $font_load ) {
			$typo .= 'font-family: ' . $tpro_normal_typography['font-family'] . '; ' . self::tpro_the_font_variants( $tpro_normal_typography['font-weight'], $tpro_normal_typography['font-style'] );
		}
		return $typo;
	}

	/**
	 * Item schema markup
	 *
	 * @param  object $post_query query.
	 * @param  string $tpro_global_item_name Global item name.
	 * @param  string $aggregate_rating ratting.
	 * @param  string $schema_html schema HTML.
	 * @param  int    $total_posts  total post.
	 * @return void
	 */
	public static function testimonials_schema( $post_query, $tpro_global_item_name, $aggregate_rating, $schema_html, $total_posts ) {
		$schema_type = apply_filters( 'sptestimonialpro_schema_type', 'Product' );
		if ( $post_query->have_posts() ) {
			ob_start();
			include self::sptp_locate_template( 'schema-markup.php' );
			echo ob_get_clean();
		}
	}

	/**
	 * Retrieves testimonials based on the provided query parameters.
	 *
	 * @param array  $shortcode_data Shortcode data attributes.
	 * @param array  $layout_data    Layout-related data.
	 * @param int    $post_id        Post ID of the current shortcode instance.
	 * @param string $value          Search value for testimonials.
	 * @param string $group          Group/category slug.
	 * @param string $rating         Rating value to filter testimonials.
	 * @param int    $page           Pagination page number.
	 *
	 * @return array Contains the query and all testimonial IDs.
	 */
	public static function testimonial_query( $shortcode_data, $layout_data, $post_id, $value = '', $group = '', $rating = '', $page = '' ) {
		$layout               = isset( $layout_data['layout'] ) ? $layout_data['layout'] : 'slider';
		$total_testimonial    = isset( $shortcode_data['number_of_total_testimonials'] ) ? $shortcode_data['number_of_total_testimonials'] : '';
		$total_testimonials   = ! empty( $total_testimonial ) ? $total_testimonial : 1000;
		$testimonial_per_page = isset( $shortcode_data['tp_per_page'] ) ? $shortcode_data['tp_per_page'] : '10';

		$random_order              = isset( $shortcode_data['random_order'] ) ? $shortcode_data['random_order'] : '';
		$tpro_order_by             = isset( $shortcode_data['testimonial_order_by'] ) ? $shortcode_data['testimonial_order_by'] : 'menu_order';
		$order_by                  = $random_order ? 'rand' : $tpro_order_by;
		$order                     = isset( $shortcode_data['testimonial_order'] ) ? $shortcode_data['testimonial_order'] : 'DESC';
		$display_testimonials_from = isset( $shortcode_data['display_testimonials_from'] ) ? $shortcode_data['display_testimonials_from'] : 'latest';
		$category_operator         = isset( $shortcode_data['category_operator'] ) ? $shortcode_data['category_operator'] : 'IN';
		$specific_testimonial      = isset( $shortcode_data['specific_testimonial'] ) ? $shortcode_data['specific_testimonial'] : '';
		$filter_by_rating          = isset( $shortcode_data['filter_by_rating'] ) ? $shortcode_data['filter_by_rating'] : '';
		$grid_pagination           = isset( $shortcode_data['grid_pagination'] ) ? $shortcode_data['grid_pagination'] : '';
		$category_list             = isset( $shortcode_data['category_list'] ) ? $shortcode_data['category_list'] : '';
		$exclude_testimonial       = isset( $shortcode_data['exclude_testimonial'] ) ? $shortcode_data['exclude_testimonial'] : '';
		$pagination_type           = isset( $shortcode_data['tp_pagination_type'] ) ? $shortcode_data['tp_pagination_type'] : 'no_ajax';

		$tpargs      = array(
			'post_type'        => 'spt_testimonial',
			'posts_per_page'   => $total_testimonials,
			'fields'           => 'ids',
			'orderby'          => $order_by,
			'order'            => $order,
			'suppress_filters' => false,
		);
		$tax_setting = array();
		if ( 'specific_testimonials' === $display_testimonials_from && ! empty( $specific_testimonial ) ) {
			$order_by           = 'menu_order' === $order_by ? 'post__in' : $order_by;
			$tpargs['post__in'] = $specific_testimonial;
			$tpargs['orderby']  = $order_by;
		} elseif ( 'category' === $display_testimonials_from && ! empty( $category_list ) ) {
			$tax_setting[] = array(
				'taxonomy' => 'testimonial_cat',
				'field'    => 'term_id',
				'terms'    => $category_list,
				'operator' => $category_operator,
			);
			$tpargs        = array_merge( $tpargs, array( 'tax_query' => $tax_setting ) );
		}

		if ( 'filter' === $layout ) {
			$grid_pagination      = isset( $shortcode_data['filter_pagination'] ) ? $shortcode_data['filter_pagination'] : false;
			$testimonial_per_page = isset( $shortcode_data['filter_per_page'] ) ? $shortcode_data['filter_per_page'] : $total_testimonials;
		}
		// if orderBy is random and not ajax pagination then run this block of code .
		if ( 'rand' === $order_by && ( 'slider' !== $layout || 'carousel' !== $layout || 'thumbnail_slider' !== $layout || 'multi_rows' !== $layout ) && $grid_pagination ) {
			$tpargs['orderby'] = 'rand(' . get_transient( 'sp_testimonial_rand_order' . $post_id ) . ')';
		}
		if ( 'exclude' === $display_testimonials_from ) {
			$display_testimonials_from = 'latest';
		}
		if ( 'specific_testimonials' !== $display_testimonials_from && ! empty( $exclude_testimonial ) ) {
			$tpargs['post__not_in'] = $exclude_testimonial;
		}
		// Check if filtering by rating is required and $filter_by_rating is not empty and an array.
		if ( 'based_on_rating_star' === $display_testimonials_from && ! empty( $filter_by_rating ) && is_array( $filter_by_rating ) ) {
			// Loop through each rating .
			foreach ( $filter_by_rating as $key => $star_rating ) {
				$tpargs['meta_query'][] = array(
					'key'     => 'sp_tpro_meta_options',
					'value'   => $star_rating,
					'compare' => 'LIKE',
				);
			}

			// Set the relation if multiple options selected .
			if ( count( $filter_by_rating ) > 1 ) {
				$tpargs['meta_query']['relation'] = 'OR';
			}
		}

		$tpargs       = apply_filters( 'spt_testimonial_pro_query_all_post_arg', $tpargs, $post_id );
		$all_post_ids = get_posts( $tpargs );
		$all_post_ids = $all_post_ids ? $all_post_ids : array( 0 );
		$args         = array(
			'post_type'        => 'spt_testimonial',
			'orderby'          => $order_by,
			'order'            => $order,
			'post__in'         => $all_post_ids,
			'posts_per_page'   => $total_testimonials,
			'suppress_filters' => apply_filters( 'spt_testimonial_pro_suppress_filters', false, $post_id ),
		);

		if ( ( 'grid' === $layout || 'masonry' === $layout || 'list' === $layout || 'filter' === $layout ) && $grid_pagination ) {
			$paged                  = 'paged' . $post_id;
			$paged                  = isset( $_GET[ "$paged" ] ) ? wp_unslash( absint( $_GET[ "$paged" ] ) ) : 1;
			$args['posts_per_page'] = ! empty( $value ) ? $total_testimonials : $testimonial_per_page;
			$args['paged']          = $paged;
			if ( ! empty( $page ) && isset( $page ) ) {
				$args['paged'] = $page;
			}
			// if orderBy is random then run this block of code .
			if ( 'rand' === $order_by ) {
				if ( $paged && 1 === $paged ) {
					set_transient( 'sp_testimonial_rand_order' . $post_id, wp_rand() );
				}
				$args['orderby'] = 'rand(' . get_transient( 'sp_testimonial_rand_order' . $post_id ) . ')';
			}
		}

		// Check if $value is not empty or filtering by rating is required .
		if ( ! empty( $value ) || ! empty( $rating ) || ! empty( $group ) ) {
			if ( isset( $rating ) && ! empty( $rating ) ) {
				$args['meta_query'][] = array(
					'key'     => 'sp_tpro_meta_options',
					'value'   => $rating,
					'compare' => 'LIKE',
				);
			}
			// Check if $value is not empty and add meta query for it .
			if ( ! empty( $value ) ) {
				$args['s'] = $value;
			}
			if ( ! empty( $group ) ) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'testimonial_cat',
						'field'    => 'slug',
						'terms'    => $group,
					),
				);
			}
		}

		$args = apply_filters( 'spt_testimonial_pro_query_args', $args, $post_id );

		// Run the initial query.
		$post_query = new \WP_Query( $args );
		// This block of code use for meta field search.
		if ( ! empty( $value ) && ! $post_query->have_posts() ) {
			unset( $args['s'] );
			$args['meta_query'][] = array(
				'key'     => 'sp_tpro_meta_options',
				'value'   => $value,
				'compare' => 'LIKE',
			);
			$post_query           = new \WP_Query( $args );
		}

		return array(
			'query'               => $post_query,
			'all_testimonial_ids' => $all_post_ids,
		);
	}

	/**
	 * Retrieves the isotope button metadata for testimonials.
	 *
	 * @param array $filter_layout_testimonials The filter layout testimonials array.
	 * @param array $shortcode_data The shortcode data array.
	 * @param int   $id The ID of the post.
	 * @return array The filter groups metadata.
	 */
	public static function isotope_button_meta( $filter_layout_testimonials, $shortcode_data, $id, $filter_layout = false ) {
		$filter_array   = array();
		$group_relation = str_replace( ' ', '', $shortcode_data['category_operator'] );

		// This block of code has been executed for the selected groups.
		// Check if specific category lists are defined and 'display_testimonials_from' is set to 'category'.
		// Also, check that the category operator is not 'NOTIN'.
		if ( isset( $shortcode_data['category_list'] ) && '' !== $shortcode_data['category_list'] && 'category' === $shortcode_data['display_testimonials_from'] && 'NOTIN' !== $group_relation ) {
			$testimonial_groups = $shortcode_data['category_list'];
			if ( ! empty( $testimonial_groups ) ) {
				foreach ( $testimonial_groups as $testimonial_group ) {
					// Get the term object and add it to the filter array.
					if ( term_exists( (int) $testimonial_group, 'testimonial_cat' ) ) {
						$filter_array[] = get_term( $testimonial_group );
					}
				}
			}
		} else {
			// This block of code has been executed for all groups.
			foreach ( $filter_layout_testimonials as $testimonial ) {
				$testimonial_groups = get_the_terms( $testimonial, 'testimonial_cat' );
				if ( ! empty( $testimonial_groups ) ) {
					foreach ( $testimonial_groups as $testimonial_group ) {
						// Add child group to the filter array.
						$filter_array[] = $testimonial_group;
					}
				}
			}
		}

		// Remove duplicates from the filter array.
		$unique_filter_array = array_unique( $filter_array, SORT_REGULAR );
		$group_filter        = array();

		// Process the unique filter array to create the group filter.
		foreach ( $unique_filter_array as $testimonial_group ) {
			if ( ! isset( $testimonial_group->name ) ) {
				continue;
			}
			$group_filter[ $testimonial_group->name ] = array(
				'id'   => isset( $testimonial_group->term_id ) ? $testimonial_group->term_id : $testimonial_group->id,
				'name' => $testimonial_group->name,
				'slug' => $filter_layout && urldecode( $testimonial_group->slug ) !== $testimonial_group->slug ? $testimonial_group->term_id : urldecode( sanitize_title( $testimonial_group->slug ) ),
			);
		}

		// Sort the group filter by name.
		ksort( $group_filter );

		// Create the filter groups array.
		$filter_groups = array(
			'category' => apply_filters( 'sp_testimonial_group_filter_sort', $group_filter, $id ),
		);

		// Return the filter groups.
		return $filter_groups;
	}

	/**
	 * The load google fonts function merge all fonts from shortcodes.
	 *
	 * @param  array $typography store the all shortcode typography.
	 * @return array
	 */
	public static function load_google_fonts( $typography ) {
		$enqueue_fonts = array();
		if ( ! empty( $typography ) ) {
			foreach ( $typography as $font ) {
				if ( isset( $font['type'] ) && 'google' === $font['type'] ) {
					$weight          = isset( $font['font-weight'] ) ? ( ( 'normal' !== $font['font-weight'] ) ? ':' . $font['font-weight'] : ':400' ) : ':400';
					$style           = isset( $font['font-style'] ) ? substr( $font['font-style'], 0, 1 ) : '';
					$enqueue_fonts[] = str_replace( ' ', '+', $font['font-family'] ) . $weight . $style;
				}
			}
		}
		$enqueue_fonts = array_unique( $enqueue_fonts );
		return $enqueue_fonts;
	}

	/**
	 * Gets the existing shortcode-id, page-id and option-key from the current page.
	 *
	 * @return array
	 */
	public static function get_page_data() {
		$current_page_id    = get_queried_object_id();
		$option_key         = 'sp_testimonial_page_id' . $current_page_id;
		$found_generator_id = get_option( $option_key );
		if ( is_multisite() ) {
			$option_key         = 'sp_testimonial_page_id' . get_current_blog_id() . $current_page_id;
			$found_generator_id = get_site_option( $option_key );
		}
		$get_page_data = array(
			'page_id'      => $current_page_id,
			'generator_id' => $found_generator_id,
			'option_key'   => $option_key,
		);
		return $get_page_data;
	}

	/**
	 * If the option does not exist, it will be created.
	 *
	 * It will be serialized before it is inserted into the database.
	 *
	 * @param  string $post_id existing shortcode id.
	 * @param  array  $get_page_data get current page-id, shortcode-id and option-key from the page.
	 * @return void
	 */
	public static function db_options_update( $post_id, $get_page_data ) {
		$found_generator_id = $get_page_data['generator_id'];
		$option_key         = $get_page_data['option_key'];
		$current_page_id    = $get_page_data['page_id'];
		if ( $found_generator_id ) {
			$found_generator_id = is_array( $found_generator_id ) ? $found_generator_id : array( $found_generator_id );
			if ( ! in_array( $post_id, $found_generator_id ) || empty( $found_generator_id ) ) {
				// If not found the shortcode id in the page options.
				array_push( $found_generator_id, $post_id );
				if ( is_multisite() ) {
					update_site_option( $option_key, $found_generator_id );
				} else {
					update_option( $option_key, $found_generator_id );
				}
			}
		} else {
			// If option not set in current page add option.
			if ( $current_page_id ) {
				if ( is_multisite() ) {
					add_site_option( $option_key, array( $post_id ) );
				} else {
					add_option( $option_key, array( $post_id ) );
				}
			}
		}
	}
	/**
	 * Load dynamic style of the existing shortcode id.
	 *
	 * @param  mixed $found_generator_id to push id option for getting how many shortcode in the page.
	 * @param  mixed $shortcode_data to push all options.
	 * @param  mixed $layout_data gets option of the plugin.
	 * @param  mixed $setting_options gets option of the plugin.
	 * @return array dynamic style and typography use in the specific shortcode.
	 */
	public static function load_dynamic_style( $found_generator_id, $shortcode_data = '', $layout_data = '', $setting_options = '' ) {
		$dequeue_google_fonts = isset( $setting_options['tpro_dequeue_google_fonts'] ) ? $setting_options['tpro_dequeue_google_fonts'] : true;
		$outline              = '';
		$tpro_typography      = array();
		// If multiple shortcode found in the page.
		if ( is_array( $found_generator_id ) ) {
			foreach ( $found_generator_id as $post_id ) {
				if ( $post_id && is_numeric( $post_id ) && get_post_status( $post_id ) !== 'trash' ) {
					$setting_options = get_option( 'sp_testimonial_pro_options' );
					$shortcode_data  = get_post_meta( $post_id, 'sp_tpro_shortcode_options', true );
					$layout_data     = get_post_meta( $post_id, 'sp_tpro_layout_options', true );
					include SP_TPRO_PATH . 'Frontend/views/partials/style.php';
				}
			}
		} else {
			// If single shortcode found in the page.
			$post_id = $found_generator_id;
			include SP_TPRO_PATH . 'Frontend/views/partials/style.php';
		}
		// Custom css merge with dynamic style.
		$custom_css = isset( $setting_options['custom_css'] ) ? trim( html_entity_decode( $setting_options['custom_css'] ) ) : '';
		if ( ! empty( $custom_css ) ) {
			$outline .= $custom_css;
		}
		// Google font enqueue dequeue check.
		$tpro_typography = $dequeue_google_fonts ? $tpro_typography : array();
		$dynamic_style   = array(
			'dynamic_css' => self::minify_output( $outline ),
			'typography'  => $tpro_typography,
		);
		return $dynamic_style;
	}

	/**
	 * Custom set transient
	 *
	 * @param  mixed $cache_key Key.
	 * @param  mixed $cache_data data.
	 * @return void
	 */
	public static function sp_testimonial_set_transient( $cache_key, $cache_data ) {
		$setting_options       = get_option( 'sp_testimonial_pro_options' );
		$testimonial_use_cache = isset( $setting_options['testimonial_enable_cache'] ) ? $setting_options['testimonial_enable_cache'] : false;

		if ( $testimonial_use_cache && ! current_user_can( 'manage_options' ) ) {
			$cache_expire_time = apply_filters( 'sp_testimonial_cache_time', DAY_IN_SECONDS ); // 24 hours
			if ( is_multisite() ) {
				set_site_transient( $cache_key, $cache_data, $cache_expire_time );
			} else {
				set_transient( $cache_key, $cache_data, $cache_expire_time );
			}
		}
	}

	/**
	 * Custom get transient.
	 *
	 * @param  mixed $cache_key Cache key.
	 * @return content
	 */
	public static function sp_testimonial_get_transient( $cache_key ) {
		$setting_options       = get_option( 'sp_testimonial_pro_options' );
		$testimonial_use_cache = isset( $setting_options['testimonial_enable_cache'] ) ? $setting_options['testimonial_enable_cache'] : false;
		if ( ! $testimonial_use_cache || current_user_can( 'manage_options' ) ) {
			return false;
		}

		if ( is_multisite() ) {
			$cached_data = get_site_transient( $cache_key );
		} else {
			$cached_data = get_transient( $cache_key );
		}
		return $cached_data;
	}

	/**
	 * Retrieve the CSS gradient background preset for the testimonial.
	 *
	 * @param array $selected_preset Selected gradient preset.
	 * @return string CSS gradient background value or default gradient if preset not found.
	 */
	public static function get_testimonial_gradient_preset( $selected_preset ) {
		$gradient_preset = array(
			'light_blue'        => 'linear-gradient(180deg, #EBF4F5, #B5C6E0)',
			'sea_fog'           => 'linear-gradient(180deg, #C1F0E9, #8BC9E1)',
			'below_content'     => 'linear-gradient(180deg, #F3EFF6, #D2C4EA)',
			'soft_peach'        => 'linear-gradient(180deg, #FFEED9, #FCAB76)',
			'clean_blue'        => 'linear-gradient(180deg, #E5F6FC, #B2C5F1)',
			'muted_green'       => 'linear-gradient(180deg, #F2F7F4, #C0D9CA)',
			'light_grey'        => 'linear-gradient(180deg, #F6F6F6, #B6B6B6)',
			'pastel_rainbow'    => 'linear-gradient(180deg, #FFCADD, #F7B8E5)',
			'neon_glow'         => 'linear-gradient(180deg, #F7F2FE, #C8C0F4)',
			'holographic_dream' => 'linear-gradient(180deg, #F6FAFC, #D0E6FD)',
			'sky_blue'          => 'linear-gradient(180deg, #E7F5FB, #BDE5F7)',
		);

		return isset( $gradient_preset[ $selected_preset ] ) ? $gradient_preset[ $selected_preset ] : 'linear-gradient(180deg, #EBF4F5, #B5C6E0)';
	}

	/**
	 * Full html show.
	 *
	 * @param array $post_id Shortcode ID.
	 * @param array $setting_options get all layout options.
	 * @param array $shortcode_data get all meta options.
	 * @param array $layout_data get all meta options.
	 * @param array $main_section_title section title.
	 */
	public static function sp_tpro_html_show( $post_id, $setting_options, $shortcode_data, $layout_data, $main_section_title ) {

		// Load css file in header or not.
		$style_load_in_header = apply_filters( 'sp_testimonial_style_load_in_header', false );
		//
		// General Settings.
		//
		$layout                    = isset( $layout_data['layout'] ) ? $layout_data['layout'] : 'slider';
		$theme_style               = isset( $shortcode_data['theme_style'] ) ? $shortcode_data['theme_style'] : 'theme-one';
		$display_testimonials_from = isset( $shortcode_data['display_testimonials_from'] ) ? $shortcode_data['display_testimonials_from'] : 'latest';
		$category_list             = isset( $shortcode_data['category_list'] ) ? $shortcode_data['category_list'] : '';
		$preloader                 = isset( $shortcode_data['preloader'] ) ? $shortcode_data['preloader'] : '';
		$testimonial_live_filter   = isset( $shortcode_data['ajax_live_filter'] ) && 'filter' !== $layout ? $shortcode_data['ajax_live_filter'] : false;
		$ajax_testimonial_search   = isset( $shortcode_data['ajax_search'] ) ? $shortcode_data['ajax_search'] : false;
		$search_text               = isset( $shortcode_data['testimonial_search_text'] ) ? $shortcode_data['testimonial_search_text'] : '';
		//
		// Stylization.
		//
		$show_testimonial_text = isset( $shortcode_data['testimonial_text'] ) ? $shortcode_data['testimonial_text'] : '';
		$video_icon            = isset( $shortcode_data['video_icon'] ) ? $shortcode_data['video_icon'] : true;
		$img_lightbox          = isset( $shortcode_data['img_lightbox'] ) && $shortcode_data['img_lightbox'] ? $shortcode_data['img_lightbox'] : 'false';
		$section_title         = isset( $shortcode_data['section_title'] ) ? $shortcode_data['section_title'] : false;
		$average_rating_top    = isset( $shortcode_data['average_rating_top'] ) ? $shortcode_data['average_rating_top'] : false;

		$space_between            = isset( $shortcode_data['testimonial_margin']['all'] ) && $shortcode_data['testimonial_margin']['all'] ? $shortcode_data['testimonial_margin']['all'] : '0';
		$testimonial_margin_value = isset( $shortcode_data['testimonial_margin']['top'] ) && $shortcode_data['testimonial_margin']['top'] ? $shortcode_data['testimonial_margin']['top'] : $space_between;

		// Pagination settings.
		$testimonial_pagination    = isset( $shortcode_data['spt_carousel_pagination']['pagination'] ) ? $shortcode_data['spt_carousel_pagination']['pagination'] : true;
		$pagination_hide_on_mobile = isset( $shortcode_data['spt_carousel_pagination']['pagination_hide_on_mobile'] ) ? $shortcode_data['spt_carousel_pagination']['pagination_hide_on_mobile'] : '';
		$pagination_dots           = 'false';
		$pagination_mobile         = 'false';
		if ( $testimonial_pagination ) {
			$pagination_dots   = 'true';
			$pagination_mobile = 'false';
		}
		if ( $pagination_hide_on_mobile ) {
			$pagination_dots   = 'true';
			$pagination_mobile = 'true';
		}

		$carousel_pagination_type = isset( $shortcode_data['carousel_pagination_type'] ) ? $shortcode_data['carousel_pagination_type'] : 'dots';

		$columns               = isset( $shortcode_data['columns'] ) ? $shortcode_data['columns'] : '';
		$columns_large_desktop = isset( $columns['large_desktop'] ) ? $columns['large_desktop'] : '1';
		$columns_desktop       = isset( $columns['desktop'] ) ? $columns['desktop'] : '1';
		$columns_laptop        = isset( $columns['laptop'] ) ? $columns['laptop'] : '1';
		$columns_tablet        = isset( $columns['tablet'] ) ? $columns['tablet'] : '1';
		$columns_mobile        = isset( $columns['mobile'] ) ? $columns['mobile'] : '1';
		$slider_direction      = isset( $shortcode_data['slider_direction'] ) ? $shortcode_data['slider_direction'] : 'ltr';
		$rtl_mode              = ( 'rtl' === $slider_direction ) ? 'true' : 'false';

		$testimonial_content_length_type = isset( $shortcode_data['testimonial_content_length']['testimonial_content_length_type'] ) ? $shortcode_data['testimonial_content_length']['testimonial_content_length_type'] : 'characters';
		$testimonial_word_limit          = isset( $shortcode_data['testimonial_content_length']['testimonial_word_limit'] ) ? $shortcode_data['testimonial_content_length']['testimonial_word_limit'] : '';
		$testimonial_strip_tags          = isset( $shortcode_data['testimonial_strip_tags'] ) ? $shortcode_data['testimonial_strip_tags'] : false;

		// Schema settings.
		$tpro_schema_markup    = isset( $shortcode_data['tpro_schema_markup'] ) ? $shortcode_data['tpro_schema_markup'] : '';
		$tpro_global_item_name = isset( $shortcode_data['tpro_global_item_name'] ) && ! empty( $shortcode_data['tpro_global_item_name'] ) ? $shortcode_data['tpro_global_item_name'] : get_the_title();

		// Navigation.
		$show_navigation     = isset( $shortcode_data['spt_carousel_navigation']['navigation'] ) ? $shortcode_data['spt_carousel_navigation']['navigation'] : false;
		$nav_hide_on_mobile  = isset( $shortcode_data['spt_carousel_navigation']['navigation_hide_on_mobile'] ) ? $shortcode_data['spt_carousel_navigation']['navigation_hide_on_mobile'] : false;
		$navigation_position = isset( $shortcode_data['navigation_position'] ) ? $shortcode_data['navigation_position'] : 'vertical_center';
		$show_on_hover       = isset( $shortcode_data['nav_visible_on_hover'] ) ? $shortcode_data['nav_visible_on_hover'] : false;

		// Add 'show_on_hover' class to wrapper if $show_on_hover is true and $navigation_position is one of the specified values.
		if ( $show_on_hover && in_array( $navigation_position, array( 'vertical_inner', 'vertical_outer', 'vertical_center' ), true ) ) {
			$navigation_position .= ' show_on_hover';
		}

		$show_testimonial_title = isset( $shortcode_data['testimonial_title'] ) ? $shortcode_data['testimonial_title'] : '';
		/* Slider & carousel Mode */
		$slider_mode      = 'thumbnail_slider' === $layout ? 'thumbnail_slider' : 'standard';
		$carousel_mode    = isset( $layout_data['carousel_mode'] ) ? $layout_data['carousel_mode'] : 'standard';
		$center_mode      = isset( $layout_data['carousel_mode'] ) && 'center' === $layout_data['carousel_mode'] ? 'true' : 'false';
		$thumbnail_slider = 'thumbnail_slider' === $slider_mode ? 'true' : 'false';

		$the_rtl             = ( 'slider' === $layout || 'slider' === $layout || 'multi_rows' === $layout || 'thumbnail_slider' === $layout ) ? 'dir="' . esc_attr( $slider_direction ) . '"' : '';
		$grid_pagination     = isset( $shortcode_data['grid_pagination'] ) ? $shortcode_data['grid_pagination'] : '';
		$pagination_type     = isset( $shortcode_data['tp_pagination_type'] ) ? $shortcode_data['tp_pagination_type'] : 'no_ajax';
		$pagination_type     = $testimonial_live_filter && 'no_ajax' === $pagination_type ? 'ajax_pagination' : $pagination_type;
		$load_more_label     = isset( $shortcode_data['load_more_label'] ) ? $shortcode_data['load_more_label'] : 'Load More';
		$show_social_profile = isset( $shortcode_data['social_profile'] ) ? $shortcode_data['social_profile'] : '';
		$navigation_icons    = isset( $shortcode_data['navigation_icons'] ) ? $shortcode_data['navigation_icons'] : 'angle';

		/**
		 * Image Settings.
		 */
		$show_client_image            = isset( $shortcode_data['client_image'] ) ? $shortcode_data['client_image'] : false;
		$reviewer_fallback_image_type = isset( $shortcode_data['reviewer_fallback_image'] ) ? $shortcode_data['reviewer_fallback_image'] : 'mystery_person';
		$image_sizes                  = isset( $shortcode_data['image_sizes'] ) ? $shortcode_data['image_sizes'] : 'custom';
		$image_custom_size            = isset( $shortcode_data['image_custom_size'] ) ? $shortcode_data['image_custom_size'] : '';
		$client_image_width           = isset( $image_custom_size['width'] ) ? $image_custom_size['width'] : '120';
		$client_image_height          = isset( $image_custom_size['height'] ) ? $image_custom_size['height'] : '120';
		$image_crop                   = isset( $image_custom_size['crop'] ) ? $image_custom_size['crop'] : 'soft-crop';
		$client_image_crop            = ( 'hard-crop' === $image_crop ) ? true : false;
		$image_grayscale              = isset( $shortcode_data['image_grayscale'] ) ? $shortcode_data['image_grayscale'] : 'none';
		$show_2x_image                = isset( $shortcode_data['load_2x_image'] ) ? $shortcode_data['load_2x_image'] : '';
		$image_zoom                   = isset( $shortcode_data['image_zoom_effect'] ) ? $shortcode_data['image_zoom_effect'] : '';

		$star_icon       = isset( $shortcode_data['tpro_star_icon'] ) ? $shortcode_data['tpro_star_icon'] : 'fa fa-star';
		$adaptive_height = isset( $shortcode_data['adaptive_height'] ) && $shortcode_data['adaptive_height'] ? 'true' : 'false';

		$testimonial_characters_limit      = isset( $shortcode_data['testimonial_content_length']['testimonial_characters_limit'] ) ? $shortcode_data['testimonial_content_length']['testimonial_characters_limit'] : '100';
		$testimonial_content_type          = isset( $shortcode_data['testimonial_content_type'] ) ? $shortcode_data['testimonial_content_type'] : '';
		$testimonial_read_more_link_action = isset( $shortcode_data['testimonial_read_more_link_action'] ) ? $shortcode_data['testimonial_read_more_link_action'] : '';
		$testimonial_read_more             = isset( $shortcode_data['testimonial_read_more'] ) ? $shortcode_data['testimonial_read_more'] : '';

		$testimonial_same_height = '';
		if ( 'slider' === $layout || 'thumbnail_slider' === $layout || 'multi_rows' === $layout || 'carousel' === $layout || 'grid' === $layout ) {
			$testimonial_same_height = isset( $shortcode_data['testimonial_same_height'] ) && $shortcode_data['testimonial_same_height'] ? 'data-same-height = true' : ' ';
		}

		if ( $show_testimonial_text && 'content_with_limit' === $testimonial_content_type && $testimonial_read_more && 'expand' === $testimonial_read_more_link_action ) {
			wp_enqueue_script( 'tpro-curtail-min-js' );
			$testimonial_read_more_text     = isset( $shortcode_data['testimonial_read_more_text'] ) ? $shortcode_data['testimonial_read_more_text'] : 'Read More';
			$testimonial_read_less_text     = isset( $shortcode_data['testimonial_read_less_text'] ) ? $shortcode_data['testimonial_read_less_text'] : 'Read Less';
			$testimonial_read_more_ellipsis = isset( $shortcode_data['testimonial_read_more_ellipsis'] ) ? $shortcode_data['testimonial_read_more_ellipsis'] : '...';
			$read_more_config               = 'data-read_more=\'{
				"testimonial_characters_limit": ' . $testimonial_characters_limit . ',
				"testimonial_read_more_text": "' . $testimonial_read_more_text . '",
				"testimonial_read_less_text": "' . $testimonial_read_less_text . '",
				"testimonial_read_more_ellipsis": "' . $testimonial_read_more_ellipsis . '"
			}\'';
		} elseif ( $testimonial_read_more ) {
			wp_enqueue_script( 'tpro-remodal-js' );
		}
		$testimonial_read_more_class = '';
		$paged_id                    = isset( $_GET[ 'paged' . $post_id ] ) ? sanitize_text_field( wp_unslash( $_GET[ 'paged' . $post_id ] ) ) : 1;
		$testimonial_query_and_ids   = self::testimonial_query( $shortcode_data, $layout_data, $post_id );
		$testimonial_items           = self::testimonial_items( $testimonial_query_and_ids, $shortcode_data, $layout, $slider_mode, $carousel_mode, $post_id, $paged_id );
		$post_query                  = $testimonial_query_and_ids['query'];
		$data_attr                   = '';
		$data_attr                  .= 'data-testimonial=\'{ "videoIcon": ' . $video_icon . ', "lightboxIcon": ' . $img_lightbox . ', "thumbnailSlider": ' . $thumbnail_slider . '}\'';
		$data_attr                  .= 'data-preloader=\'' . $preloader . '\'';

		if ( ( $video_icon || $img_lightbox ) ) {
			wp_enqueue_script( 'tpro-magnific-popup-js' );
		}
		if ( $testimonial_read_more ) {
			$testimonial_read_more_class = 'true';
		}

		if ( 'slider' === $layout || 'carousel' === $layout || 'multi_rows' === $layout || 'thumbnail_slider' === $layout ) {
			$slider_swipe           = isset( $shortcode_data['slider_swipe'] ) && $shortcode_data['slider_swipe'] ? 'true' : 'false';
			$slider_draggable       = isset( $shortcode_data['slider_draggable'] ) && $shortcode_data['slider_draggable'] && 'true' === $slider_swipe ? 'true' : 'false';
			$slider_draggable_thumb = isset( $shortcode_data['slider_draggable'] ) && $shortcode_data['slider_draggable'] && 'true' === $slider_swipe ? 'true' : 'false';
			$tpro_swipe_to_slide    = isset( $shortcode_data['swipe_to_slide'] ) ? $shortcode_data['swipe_to_slide'] : 'false';
			$swipe_to_slide         = $tpro_swipe_to_slide && 'true' === $slider_swipe ? 'true' : 'false';
			$slider_swipe_thumb     = isset( $shortcode_data['slider_swipe'] ) && $shortcode_data['slider_swipe'] ? 'true' : 'false';
			// Auto Play.
			$slider_auto_play_data = isset( $shortcode_data['carousel_autoplay']['slider_auto_play'] ) ? $shortcode_data['carousel_autoplay']['slider_auto_play'] : 'true';
			$slider_auto_play      = $slider_auto_play_data ? 'true' : 'false';
			$thumbnail_position    = isset( $layout_data['theme_style_thumbnail'] ) ? $layout_data['theme_style_thumbnail'] : 'theme-one';
			$_position             = 'horizontal';
			if ( 'theme-three' === $thumbnail_position || 'theme-four' === $thumbnail_position ) {
				$_position = 'vertical';
			}
			$autoplay_disable_on_mobile_data = isset( $shortcode_data['carousel_autoplay']['autoplay_disable_on_mobile'] ) ? $shortcode_data['carousel_autoplay']['autoplay_disable_on_mobile'] : 'false';
			$slider_auto_play_mobile         = ( $autoplay_disable_on_mobile_data && $slider_auto_play_data ) ? 'true' : 'false';

			$slider_auto_play_speed        = isset( $shortcode_data['slider_auto_play_speed'] ) ? $shortcode_data['slider_auto_play_speed'] : '3000';
			$slider_scroll_speed           = isset( $shortcode_data['slider_scroll_speed'] ) ? $shortcode_data['slider_scroll_speed'] : '600';
			$slide_to_scroll               = isset( $shortcode_data['slide_to_scroll'] ) ? $shortcode_data['slide_to_scroll'] : '1';
			$slide_to_scroll_large_desktop = isset( $slide_to_scroll['large_desktop'] ) ? $slide_to_scroll['large_desktop'] : '1';
			$slide_to_scroll_desktop       = isset( $slide_to_scroll['desktop'] ) ? $slide_to_scroll['desktop'] : '1';
			$slide_to_scroll_laptop        = isset( $slide_to_scroll['laptop'] ) ? $slide_to_scroll['laptop'] : '1';
			$slide_to_scroll_tablet        = isset( $slide_to_scroll['tablet'] ) ? $slide_to_scroll['tablet'] : '1';
			$slide_to_scroll_mobile        = isset( $slide_to_scroll['mobile'] ) ? $slide_to_scroll['mobile'] : '1';

			$slider_row        = isset( $shortcode_data['slider_row'] ) ? $shortcode_data['slider_row'] : '';
			$row_large_desktop = '0';
			$row_desktop       = '0';
			$row_laptop        = '0';
			$row_tablet        = '0';
			$row_mobile        = '0';
			if ( 'multi_rows' === $layout ) {
				$row_large_desktop = isset( $slider_row['large_desktop'] ) ? $slider_row['large_desktop'] : '2';
				$row_desktop       = isset( $slider_row['desktop'] ) ? $slider_row['desktop'] : '2';
				$row_laptop        = isset( $slider_row['laptop'] ) ? $slider_row['laptop'] : '2';
				$row_tablet        = isset( $slider_row['tablet'] ) ? $slider_row['tablet'] : '1';
				$row_mobile        = isset( $slider_row['mobile'] ) ? $slider_row['mobile'] : '1';
			}

			$slider_pause_on_hover       = isset( $shortcode_data['slider_pause_on_hover'] ) && $shortcode_data['slider_pause_on_hover'] ? 'true' : 'false';
			$slider_pause_on_hover_thumb = isset( $shortcode_data['slider_pause_on_hover'] ) && $shortcode_data['slider_pause_on_hover'] ? 'true' : 'false';
			$slider_infinite             = isset( $shortcode_data['slider_infinite'] ) && $shortcode_data['slider_infinite'] ? 'true' : 'false';
			$slider_animation            = isset( $shortcode_data['slider_animation'] ) ? $shortcode_data['slider_animation'] : '';
			$fade_carousel_class         = '';
			$slider_fade_effect          = 'false';
			switch ( $slider_animation ) {
				case 'slide':
					$slider_fade_effect = 'false';
					break;
				case 'fade':
					$slider_fade_effect  = 'true';
					$fade_carousel_class = 'sp-testimonial-fade-carousel';
					break;
			}

			$slider_fade_effect_thumb = 'fade' === $slider_animation ? 'true' : 'false';
			$flip_vertical            = 'flipVertically' === $slider_animation ? 'flip_vertically' : '';
			wp_enqueue_script( 'tpro-swiper-js' );

			if ( 'thumbnail_slider' === $layout ) {
				wp_enqueue_script( 'tpro-thumbnail-js' );
				$slides_to_show = 5;
				if ( $testimonial_items['total_testimonial'] < 6 && $testimonial_items['total_testimonial'] > 1 ) {
					$slides_to_show = (int) $testimonial_items['total_testimonial'] - 1;
				}
				include self::sptp_locate_template( 'thumbnail-slider.php' );
			} else {
				wp_enqueue_script( 'tpro-swiper-config' );
				if ( 'carousel' === $layout && 'ticker' === $carousel_mode ) {
					wp_enqueue_script( 'tpro-bx_slider' );
					$data_attr .= 'data-bx_ticker=\'{"pauseOnHover": ' . $slider_pause_on_hover . ', "slidesPerView": {"lg_desktop":' . $columns_large_desktop . ' , "desktop": ' . $columns_desktop . ', "laptop":' . $columns_laptop . ' , "tablet": ' . $columns_tablet . ', "mobile": ' . $columns_mobile . '}, "speed": ' . $slider_scroll_speed . '0, "rtl": ' . $rtl_mode . '}\'';
				} else {
					$data_attr .= 'data-arrowicon="' . $navigation_icons . '" data-swiper_slider=\'{"enablePagination": "' . $testimonial_pagination . '","pagination_type": "' . $carousel_pagination_type . '","dots": ' . $pagination_dots . ',"centerMode": ' . $center_mode . ',"spaceBetween":' . $testimonial_margin_value . ', "adaptiveHeight": ' . $adaptive_height . ', "rows": ' . $row_large_desktop . ', "pauseOnHover": ' . $slider_pause_on_hover . ', "animationEffect": "' . $slider_animation . '", "effect": ' . $slider_fade_effect_thumb . ', "slidesToShow": ' . $columns_large_desktop . ', "speed": ' . $slider_scroll_speed . ', "arrows": ' . $show_navigation . ', "autoplay": ' . $slider_auto_play . ', "autoplaySpeed": ' . $slider_auto_play_speed . ', "swipe": ' . $slider_swipe . ', "swipeToSlide": ' . $swipe_to_slide . ', "draggable": ' . $slider_draggable . ', "rtl": ' . $rtl_mode . ', "infinite": ' . $slider_infinite . ', "slidesToScroll": ' . $slide_to_scroll_large_desktop . ', "fade": ' . $slider_fade_effect . ',"slidesPerView": {"lg_desktop":' . $columns_large_desktop . ' , "desktop": ' . $columns_desktop . ', "laptop":' . $columns_laptop . ' , "tablet": ' . $columns_tablet . ', "mobile": ' . $columns_mobile . '},"slideToScroll": {"lg_desktop":' . $slide_to_scroll_large_desktop . '  , "desktop": ' . $slide_to_scroll_desktop . ', "laptop":' . $slide_to_scroll_laptop . ' , "tablet":' . $slide_to_scroll_tablet . ' , "mobile":' . $slide_to_scroll_mobile . ' }, "slidesRow": {"lg_desktop":' . $row_large_desktop . '  , "desktop": ' . $row_desktop . ', "laptop": ' . $row_laptop . ' , "tablet":' . $row_tablet . ', "mobile": ' . $row_mobile . '}, "autoplay_mobile": ' . $slider_auto_play_mobile . '}\'';
				}
				include self::sptp_locate_template( 'slider.php' );
			}
		} elseif ( 'grid' === $layout ) {
			include self::sptp_locate_template( 'grid.php' );
		} elseif ( 'masonry' === $layout ) {
			wp_enqueue_script( 'jquery-masonry' );
			include self::sptp_locate_template( 'masonry.php' );
		} elseif ( 'list' === $layout ) {
			include self::sptp_locate_template( 'list.php' );
		} elseif ( 'filter' === $layout ) {
			$all_tab_text    = isset( $shortcode_data['all_tab_text'] ) ? $shortcode_data['all_tab_text'] : 'All';
			$all_tab_show    = isset( $shortcode_data['all_tab'] ) ? $shortcode_data['all_tab'] : true;
			$filter_style    = isset( $layout_data['filter_style'] ) ? $layout_data['filter_style'] : 'even';
			$grid_pagination = isset( $shortcode_data['filter_pagination'] ) ? $shortcode_data['filter_pagination'] : false;
			$pagination_type = isset( $shortcode_data['filter_pagination_type'] ) ? $shortcode_data['filter_pagination_type'] : 'infinite_scroll';
			$load_more_label = isset( $shortcode_data['filter_load_more_label'] ) ? $shortcode_data['filter_load_more_label'] : 'Load More';
			switch ( $filter_style ) {
				case 'even':
					$filter_mode = 'fitRows';
					break;
				case 'masonry':
					$filter_mode = 'masonry';
					break;
			}
			$tpro_filter_config = $filter_mode;
			wp_enqueue_script( 'tpro-isotope-js' );
			wp_enqueue_script( 'imagesloaded' );
			include self::sptp_locate_template( 'filter.php' );
		}
		if ( $tpro_schema_markup ) {
			ob_start();
			self::testimonials_schema( $post_query, $tpro_global_item_name, $testimonial_items['aggregate_rating'], $testimonial_items['schema_html'], $testimonial_items['total_testimonial'] );
			echo ob_get_clean();
		}
		wp_enqueue_script( 'tpro-scripts' );
	}

	/**
	 * Generate email body message.
	 *
	 * @param string $message_content message content.
	 * @param string $site_name site title.
	 * @param string $site_description site description.
	 * @param string $tpro_client_name client name.
	 * @param string $tpro_client_email client email.
	 * @param string $tpro_client_designation client designation.
	 * @param string $tpro_company_name company name.
	 * @param string $tpro_location client location.
	 * @param string $tpro_country client country.
	 * @param string $tpro_phone client phone.
	 * @param string $tpro_website client website.
	 * @param string $tpro_video_url video URL.
	 * @param string $tpro_testimonial_title testimonial title.
	 * @param string $tpro_testimonial_text testimonial content.
	 * @param string $tpro_category testimonial categories.
	 * @param string $tpro_rating rating star.
	 * @return $message
	 */
	public static function generate_email_message( $message_content, $site_name, $site_description, $tpro_client_name, $tpro_client_email, $tpro_client_designation, $tpro_company_name, $tpro_location, $tpro_country, $tpro_phone, $tpro_website, $tpro_video_url, $tpro_testimonial_title, $tpro_testimonial_text, $tpro_category, $tpro_rating ) {
		$message  = '';
		$message .= '<div style="background-color: #f6f6f6;font-family: Helvetica Neue,Helvetica,Arial,sans-serif;">';
		$message .= '<div style="width: 100%;margin: 0;padding: 70px 0 70px 0;">';
		$message .= '<div style="background-color: #ffffff;border: 1px solid #e9e9e9;border-radius: 2px!important;padding: 20px 20px 10px 20px;width: 520px;margin: 0 auto;">';
		$message .= '<div style="padding: 30px 20px 40px;">';

		// Replace placeholders in message content.
		$message_content = str_replace( '{name}', $tpro_client_name, $message_content );
		$message_content = str_replace( '{email}', $tpro_client_email, $message_content );
		$message_content = str_replace( '{position}', $tpro_client_designation, $message_content );
		$message_content = str_replace( '{company_name}', $tpro_company_name, $message_content );
		$message_content = str_replace( '{location}', $tpro_location, $message_content );
		$message_content = str_replace( '{phone}', $tpro_phone, $message_content );
		$message_content = str_replace( '{website}', $tpro_website, $message_content );
		$message_content = str_replace( '{video_url}', $tpro_video_url, $message_content );
		$message_content = str_replace( '{testimonial_title}', $tpro_testimonial_title, $message_content );
		$message_content = str_replace( '{testimonial_text}', $tpro_testimonial_text, $message_content );
		$message_content = str_replace( '{groups}', $tpro_category, $message_content );
		$message_content = str_replace( '{rating}', $tpro_rating, $message_content );

		$message_content = apply_filters( 'testimonial_pro_email_preview_template_tags', $message_content );
		$message        .= wpautop( $message_content );
		$message        .= '</div>';

		$message .= '<div style="border-top:1px solid #efefef;padding:20px 0;clear:both;text-align:center"><small style="font-size:11px">' . $site_name . ' ' . $site_description . '</small></div>';

		$message .= '</div>';
		$message .= '</div>';
		$message .= '</div>';

		return $message;
	}

	/**
	 * Generate Rating Star
	 *
	 * @param  array $rating_value Rating values.
	 * @return $testimonial_rating
	 */
	public static function generate_star_rating( $rating_value ) {
		$filled_star = '<span style="color: #FF9800;font-size: 17px;">&#x2605;</span>';
		$empty_star  = '<span style="color: #d4d4d4;font-size: 17px;">&#x2605;</span>';

		$testimonial_rating = '';
		// Generate star rating based on the rating value.
		switch ( $rating_value ) {
			case 'one_star':
				$testimonial_rating = $filled_star . $empty_star . $empty_star . $empty_star . $empty_star;
				break;
			case 'two_star':
				$testimonial_rating = $filled_star . $filled_star . $empty_star . $empty_star . $empty_star;
				break;
			case 'three_star':
				$testimonial_rating = $filled_star . $filled_star . $filled_star . $empty_star . $empty_star;
				break;
			case 'four_star':
				$testimonial_rating = $filled_star . $filled_star . $filled_star . $filled_star . $empty_star;
				break;
			case 'five_star':
				$testimonial_rating = $filled_star . $filled_star . $filled_star . $filled_star . $filled_star;
				break;
			default:
				$testimonial_rating = ''; // default empty rating.
				break;
		}

		return $testimonial_rating;
	}

	/**
	 * Frontend form html.
	 *
	 * @param  int   $form_id form id.
	 * @param  array $setting_options options.
	 * @param  array $form_elements element.
	 * @param  array $form_data data.
	 * @return void
	 */
	public static function frontend_form_html( $form_id, $setting_options, $form_elements, $form_data ) {

		$form_element           = isset( $form_elements['form_elements'] ) ? $form_elements['form_elements'] : array();
		$captcha_site_key_v3    = isset( $setting_options['captcha_site_key_v3'] ) ? $setting_options['captcha_site_key_v3'] : '';
		$captcha_version        = isset( $setting_options['captcha_version'] ) ? $setting_options['captcha_version'] : 'v2';
		$captcha_secret_key_v3  = isset( $setting_options['captcha_secret_key_v3'] ) ? $setting_options['captcha_secret_key_v3'] : '';
		$popup_button_style     = isset( $form_data['popup_button_style'] ) ? $form_data['popup_button_style'] : '';
		$popup_button_label     = isset( $form_data['popup_button_label'] ) ? $form_data['popup_button_label'] : '';
		$ajax_form_submission   = isset( $form_data['ajax_form_submission'] ) ? $form_data['ajax_form_submission'] : '';
		$form_display_mode      = isset( $form_data['tpro_form_display_mode'] ) ? $form_data['tpro_form_display_mode'] : 'on_page';
		$hide_form_after_submit = isset( $form_data['tpro_hide_form_after_submit'] ) ? $form_data['tpro_hide_form_after_submit'] : false;

		// Form Scripts and Styles.
		if ( in_array( 'recaptcha', $form_element, true ) && ( '' !== $setting_options['captcha_site_key'] || '' !== $captcha_site_key_v3 ) && ( '' !== $setting_options['captcha_secret_key'] || '' !== $captcha_secret_key_v3 ) ) {
			if ( 'v2' === $captcha_version ) {
				wp_enqueue_script( 'tpro-recaptcha-js' );
			} else {
				wp_enqueue_script( 'tpro-recaptcha-v3-js', '//www.google.com/recaptcha/api.js?render=' . $captcha_site_key_v3, array(), SP_TPRO_VERSION, true );
				wp_add_inline_script(
					'tpro-recaptcha-v3-js',
					'grecaptcha.ready(function() {
						grecaptcha.execute("' . $captcha_site_key_v3 . '", {action: "submit"}).then(function(token) {
							document.getElementById("token").value = token;
						});
					});'
				);
			}
		}
		wp_enqueue_script( 'tpro-chosen-jquery' );
		wp_enqueue_script( 'tpro-chosen-config' );

		$function     = new SP_Testimonial_Pro_Functions();
		$country_list = $function->get_country_list();

		wp_localize_script(
			'tpro-chosen-config',
			'testimonial_form_vars',
			array(
				'ajax_url'             => admin_url( 'admin-ajax.php' ),
				'nonce'                => wp_create_nonce( 'sp-testimonial-form-nonce' ),
				'not_found'            => __( ' No result found ', 'testimonial-pro' ),
				'ajax_form_submission' => $ajax_form_submission,
				'countries'            => $country_list,
			)
		);
		if ( 'popup' === $form_display_mode ) {
			wp_enqueue_style( 'tpro-magnific-popup' );
			wp_enqueue_script( 'tpro-magnific-popup-js' );
		}
		wp_enqueue_style( 'tpro-form' );
		wp_enqueue_style( 'tpro-font-awesome' );
		wp_enqueue_style( 'tpro_tab_and_navigation_icons' );

		$form_fields                = $form_data['form_fields'];
		$full_name                  = $form_fields['full_name'];
		$full_name_required         = $full_name['required'] ? 'required' : '';
		$email_address              = $form_fields['email_address'];
		$email_address_required     = $email_address['required'] ? 'required' : '';
		$identity_position          = $form_fields['identity_position'];
		$identity_position_required = $identity_position['required'] ? 'required' : '';
		$company_name               = isset( $form_fields['company_name'] ) ? $form_fields['company_name'] : '';
		$company_name_required      = ! empty( $company_name['required'] ) ? 'required' : '';
		$testimonial_title          = $form_fields['testimonial_title'];
		$testimonial_title_required = $testimonial_title['required'] ? 'required' : '';
		$testimonial                = $form_fields['testimonial'];
		$testimonial_required       = $testimonial['required'] ? 'required' : '';
		$featured_image             = $form_fields['featured_image'];
		$featured_image_required    = $featured_image['required'] ? 'required' : '';
		$location                   = isset( $form_fields['location'] ) ? $form_fields['location'] : '';
		$location_required          = ! empty( $location['required'] ) ? 'required' : '';
		$phone_mobile               = isset( $form_fields['phone_mobile'] ) ? $form_fields['phone_mobile'] : '';
		$phone_mobile_required      = ! empty( $phone_mobile['required'] ) ? 'required' : '';
		$website                    = isset( $form_fields['website'] ) ? $form_fields['website'] : '';
		$website_required           = ! empty( $website['required'] ) ? 'required' : '';
		$video_url                  = isset( $form_fields['video_url'] ) ? $form_fields['video_url'] : '';
		$record_video_format        = isset( $form_fields['video_url']['record_video_format'] ) ? $form_fields['video_url']['record_video_format'] : 'mp4';
		$video_url_required         = ! empty( $video_url['required'] ) ? 'required' : '';
		$groups                     = isset( $form_fields['groups'] ) ? $form_fields['groups'] : '';
		$groups_required            = ! empty( $groups['required'] ) ? 'required' : '';
		$groups_list                = isset( $groups['groups_list'] ) ? $groups['groups_list'] : '';
		$groups_multiple_selection  = ! empty( $groups['multiple_selection'] ) ? 'multiple="multiple" ' : '';
		$rating                     = isset( $form_fields['rating'] ) ? $form_fields['rating'] : '';
		$rating_required            = isset( $rating['required'] ) ? $rating['required'] : '';
		$preselect_rating           = isset( $rating['tpro_preselect_rating'] ) ? $rating['tpro_preselect_rating'] : '';
		$agree_checkbox             = isset( $form_fields['agree_checkbox'] ) ? $form_fields['agree_checkbox'] : '';
		$recaptcha                  = isset( $form_fields['recaptcha'] ) ? $form_fields['recaptcha'] : '';
		$submit_btn                 = $form_fields['submit_btn'];
		$social_profile             = isset( $form_fields['social_profile'] ) ? $form_fields['social_profile'] : '';
		$required_notice            = isset( $form_data['required_notice'] ) ? $form_data['required_notice'] : '';
		$required_notice_label      = isset( $form_data['notice_label'] ) ? $form_data['notice_label'] : '';
		$social_profile_list        = isset( $social_profile['social_profile_list'] ) && ! empty( $social_profile['social_profile_list'] ) ? $social_profile['social_profile_list'] : '';
		$tpro_function              = new SP_Testimonial_Pro_Functions();

		// Testimonial submit form.
		include SP_TPRO_PATH . 'Frontend/views/partials/submit-form.php';
		// END THE IF STATEMENT THAT STARTED THE WHOLE FORM.
		$form_style = '';
		include SP_TPRO_PATH . 'Frontend/views/partials/form-style.php';
		echo '<style>' . wp_strip_all_tags( $form_style ) . '</style>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		$popup_opening_button        = '';
		$conditional_tag_before_form = '';
		$conditional_tag_after_form  = '';
		if ( 'popup' === $form_display_mode ) {
			$popup_opening_button        = '<div class="sp-tpro-popup-btn-wrapper-' . esc_attr( $form_id ) . '" data-display_type="' . esc_attr( $form_display_mode ) . '"><a href="#sp-tpro-form-popup-' . $form_id . '" class="sp-testimonial-form-popup-' . esc_attr( $popup_button_style ) . ' sp-tpro-open-form-popup">' . esc_html( $popup_button_label ) . '</a></div>';
			$conditional_tag_before_form = '<div id="sp-tpro-form-popup-' . esc_attr( $form_id ) . '" class="sp-tpro-form-popup-section mfp-hide testimonial-from-popup-fade">';
			$conditional_tag_after_form  = '</div>';
		}

		include self::sptp_locate_template( 'form.php' );
	}
}
