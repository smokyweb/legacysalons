<?php
// Not allowed by directly accessing.
if ( ! defined( 'ABSPATH' ) ) {
	die( 0 );
}

if ( ! class_exists( 'LoftLoader_Pro_Frontend_Loader_HTML' ) ) {
	/**
	 * @since version 1.1.9
	 */
	class LoftLoader_Pro_Frontend_Loader_HTML {
		private $bg_type 	= ''; // Loader background type
		private $type 		= ''; // Loader type
		private $ending 	= ''; // Loader screen end effect
		private $is_mobile  = false; // If currently in mobile mode
		public function __construct() {
			add_action( 'loftloader_pro_init_front', array( $this, 'init_front' ), 1 );
		}
		/**
		* Run after loader enable testing passed
		*/
		public function init_front( $is_mobile ) {
			$this->init();
			$this->is_mobile = $is_mobile;
			// Register cache callback filters
			add_filter( 'loftloader_pro_modify_html', array( $this, 'inject_loader_html' ) );
			add_filter( 'loftloader_pro_loader_classes', array( $this, 'get_loader_classs' ) );
			add_filter( 'loftloader_pro_front_json', array( $this, 'loader_attributes' ), 99 );
		}
		/**
		* Initialize global settings
		*/
		private function init() {
			$this->type 	= llp_get_loader_setting( 'loftloader_animation' );
			$this->bg_type 	= llp_get_loader_setting( 'loftloader_bgfilltype' );
			$this->ending 	= llp_get_loader_setting( 'loftloader_pro_bg_animation' );

			if ( ! class_exists( 'simple_html_dom' ) ) {
				require_once LOFTLOADERPRO_INC . 'vender/simple_html_dom.php';
			}
		}
		/**
		* Inject loader html right after open <body> tag
		*
		* @param string original html string
		* @return string modified html string
		*/
		public function inject_loader_html( $origin ) {
			$origin = $this->modify_html_tag( $origin );

			$regexp ='/(<body[^>]*>)/i';
			$split = preg_split( $regexp, $origin, 3, PREG_SPLIT_DELIM_CAPTURE );
			if ( is_array( $split ) && ( 3 <= count( $split ) ) ) {
				$is_customize_preview 	= is_customize_preview();
				$init_percentage		= $is_customize_preview ? '100%' : '';
				$bg_image_class 		= $this->get_image_bg_class();
				$loader_bg_html 		= $this->check_background() ? '<div class="loader-bg-half"></div><div class="loader-bg-half"></div>' : '';

				$img 				= llp_check_image_url( llp_get_loader_setting( 'loftloader_customimg' ) );
				$html 				= sprintf( '<div id="loftloader-wrapper"%s>', $this->loader_classes() );
				$progress_type 		= llp_get_loader_setting( 'loftloader_progress' );
				$bar_load_inner 	= ( 'bar-number' === $progress_type ) ? sprintf( '<span class="load-count">%1$s</span>', $init_percentage ) : '';
				$bar_position 		= llp_get_loader_setting( 'loftloader_barposition' );
				$message 			= $this->get_message( $is_customize_preview );
				$message_position 	= $this->get_message_position();

				/***** Loader background html ***/
				if ( in_array( $this->bg_type, array( 'solid', 'image' ) ) ) {
					$html .= sprintf( '<div class="loader-bg%s">%s</div>', esc_attr( $bg_image_class ), $loader_bg_html );
				}
				/***** Loader background html end ***/
				$html .= '<div class="loader-inner">';
				$html .= ( 'top' === $message_position ) ? wp_kses_post( $message ) : ''; // If message position === top

				// If progress with percentage and message, wrap the loader and percentage
				$html .= ( ( 'number' === $progress_type  ) && ! empty( $message ) ) ? '<div class="with-percentage">' : '';

				/***** Loader html start *****/
				$html .= '<div id="loader">';
				if ( ! empty( $img ) ) {
					// Only  image loading need the span with background
					if ( in_array( $this->type, array( 'imgloading' ) ) ) {
						$html .= $this->get_loader_type_loading_bg_image( $img );
					}
					if ( in_array( $this->type, array( 'frame', 'imgloading', 'imgrotating', 'imgbouncing', 'imgstatic', 'imgfading' ) ) ) {
						$html .= $this->get_loader_image( $img, $this->type );
						// $html .= sprintf( '<img data-no-lazy="1" class="skip-lazy" alt="%1$s" src="%2$s">', esc_attr__( 'loader image', 'loftloader-pro' ), esc_url( $img ) );
					}
				}

				if ( 'custom-loader' == $this->type ) {
					$html .= sprintf( '<div class="custom-loader-wrapper">%s</div>', llp_get_loader_setting( 'loftloader_pro_custom_loader' ) );
				} else {
					// Image rotating/bouncing/imgloading: no need the span below
					$html .= in_array( $this->type, array( 'imgrotating', 'imgbouncing', 'imgloading', 'imgfading' ) ) ? '' : '<span></span>';
				}
				$html .= '</div>';
				/***** Loader html end  ******/

				$html .= ( ( 'middle' === $message_position ) && ( 'none' === $progress_type ) ) ? wp_kses_post( $message ) : '';

				/***** Progress html start *****/
				// Percentage but not absolute position
				$loader_percentage_position_value = llp_get_loader_setting( 'loftloader_percentageposition' );
				if ( ( 'absolute' != $loader_percentage_position_value ) && ( 'number' === $progress_type ) ) {
					$percentage_position = ( 'middle' == $loader_percentage_position_value ) ? ' middle' : '';
					$percentage_layer = ( 'front' === llp_get_loader_setting('loftloader_progresslayer') ) ? ' front' : '';

					if ( ( 'middle' === $message_position ) && ( '' === $percentage_position ) ) {
						$html .= wp_kses_post( $message );
					}
					$html .= sprintf(
						'<span class="percentage%1$s%2$s">%3$s</span>',
						esc_attr( $percentage_position ),
						esc_attr( $percentage_layer ),
						esc_html( $init_percentage )
					);
				}

				// End the wrap for loader with percentage and message
				$html .= ( ( 'number' === $progress_type ) && ! empty( $message ) ) ? '</div>' : '';

				// When it is a progress bar, and choose middle, then put the html code here.
				$html .= ( in_array( $progress_type, array( 'bar', 'bar-number' ) ) && ( 'middle' === $bar_position ) ) ? sprintf(
					'%1$s<span class="bar"><span class="load%2$s"></span>%3$s</span>',
					( 'middle' === $message_position ) ? wp_kses_post( $message ) : '',
					llp_module_enabled( 'loftloader_pro_progress_bar_enable_gradient_color' ) ? ' gradient-color-enabled' : '',
					$bar_load_inner
				) : '';

				$html .= ( 'bottom' === $message_position ) ? wp_kses_post( $message ) : ''; // if message position === bottom
				$html .= '</div>';
				$html .= ( in_array( $progress_type, array( 'bar', 'bar-number' ) ) && in_array( $bar_position, array( 'top', 'bottom' ) ) ) ? sprintf(
					'<span class="bar %1$s"><span class="load%2$s"></span>%3$s</span>',
					esc_attr( $bar_position ),
					llp_module_enabled( 'loftloader_pro_progress_bar_enable_gradient_color' ) ? ' gradient-color-enabled' : '',
					$bar_load_inner
				) : '';

				// Percentage html code put here, no matter which position selected
				if ( ( 'number' === $progress_type ) && ( 'absolute' == $loader_percentage_position_value ) ) {
					$absolute_percentage_position = 'percentage pos-absolute';
					$percentage_extra_position = llp_get_loader_setting( 'loftloader_percentage_absolute_extra_position' );
					if ( ! empty( $percentage_extra_position ) ) {
						$absolute_percentage_position .= ' ' . str_replace( '-', ' ', $percentage_extra_position );
					}
					$absolute_percentage_position .= ( 'front' === llp_get_loader_setting('loftloader_progresslayer') ) ? ' front' : '';
					$html .= sprintf( '<span class="%1$s">%2$s</span>', $absolute_percentage_position, esc_html( $init_percentage ) );
				}
				/****** Progress html end *****/

				/***** Loader close button *******/
				$show_loader_close_button = llp_module_enabled( 'loftloader_pro_enable_close_button' );
				$show_spt_close_button = llp_module_enabled( 'loftloader_pro_insite_transition' ) && llp_module_enabled( 'loftloader_pro_insite_transition_show_close_button' );
				if ( ! is_customize_preview() && ( $show_loader_close_button || $show_spt_close_button ) ) {
					$close_description = $show_loader_close_button ? llp_get_loader_setting( 'loftloader_pro_show_close_tip' ) : false;
					$html .= sprintf(
						'<div class="loader-close-button" style="display: none;"><span class="screen-reader-text">%s</span>%s</div>',
						esc_html__( 'Close', 'loftloader-pro' ),
						empty( $close_description ) ? '' : sprintf( '<span class="close-des">%s</span>', wp_kses_post( $close_description ) )
					);
				}
				$html .= '</div>';

				$origin = $split[0] . $split[1] . apply_filters( 'loftloader_pro_loader_html', $html ) . implode( '', array_slice( $split, 2 ) );
			}

			$origin = $this->check_autoplay_videos( $origin );
			return $origin;
		}
		/**
		* Helper function to check if currently is mobile
		*
		* @return boolean
		*/
		private function check_background() {
			$ending = $this->ending;
			$repeat = llp_get_loader_setting( 'loftloader_pro_bg_image_repeat' );
			if ( $this->bg_type === 'image' ) {
				if ( in_array( $ending, array( 'split-reveal-v', 'split-reveal-h' ) ) ) {
					return true;
				} else if ( in_array( $ending, array( 'split-h', 'split-v', 'split-diagonally-h', 'split-diagonally-v' ) ) && ( $repeat !== 'tile' ) ) {
					return true;
				}
			}
			return false;
		}
		/**
		* Get loader class
		*/
		public function get_loader_classs( $classes ) {
			$classes 	= array();
			$directions = array( '2d' => 'twod', '3d-y' => 'threed-y', '3d-x' => 'threed-x' );
			$type 		= sprintf( 'loftloader-%s', $this->type );
			$ending 	= $this->ending;
			switch( $ending ) {
				case 'split-reveal-v':
					$ending = 'split-h split-reveal-v';
					break;
				case 'split-reveal-h':
					$ending = 'split-v split-reveal-h';
					break;
				case 'split-diagonally-v':
					$ending = 'split-v split-diagonally';
					break;
				case 'split-diagonally-h':
					$ending = 'split-h split-diagonally';
					break;
			}
			array_push( $classes, sprintf( 'end-%s', $ending ) );
			array_push( $classes, $type );
			// For loader type image bouncing
			if ( 'imgbouncing' === $this->type ) {
				if ( 'on' === llp_get_loader_setting( 'loftloader_bouncerolling' ) ) {
					array_push( $classes, 'loftloader-rolling' );
				}
				if ( is_customize_preview() ) {
					array_push( $classes, 'runshadow' );
				}
			}
			// If set the progress
			if ( 'none' !== llp_get_loader_setting( 'loftloader_progress' ) ) {
				array_push( $classes, 'loftloader-progress' );
			}
			// If set to loop for specific loader types
			if ( in_array( $type, array( 'loftloader-imgloading', 'loftloader-rainbow', 'loftloader-circlefilling', 'loftloader-waterfilling', 'loftloader-petals' ) ) ) {
				array_push( $classes, sprintf( 'loftloader-%s', llp_get_loader_setting( 'loftloader_looping' ) ) );
			}
			// For loader type crossing
			if ( in_array( $type, array( 'loftloader-crossing' ) ) ) {
				array_push( $classes, sprintf( 'loftloader-blendmode-%s', llp_get_loader_setting( 'loftloader_blendmode' ) ) );
			}
			// For loader type image loading
			if ( in_array( $type, array( 'loftloader-imgloading' ) ) ) {
				$img_load_direction = llp_get_loader_setting( 'loftloader_loaddirection' );
				array_push( $classes, sprintf( 'imgloading-%s',  $img_load_direction ) );
				if ( 'vertical' === $img_load_direction ) {
					array_push( $classes, llp_get_loader_setting( 'loftloader_custom_image_loading_vertical_direction' ) );
				}
			}
			// For loader type image rotating
			if ( in_array( $type, array( 'loftloader-imgrotating' ) ) ){
				$img_rotate_direction = llp_get_loader_setting( 'loftloader_rotatedirection' );
				$img_rotate_2d = llp_get_loader_setting( 'loftloader_rotation_2d' );
				array_push( $classes, $directions[ $img_rotate_direction ] );
				array_push( $classes, llp_get_loader_setting( 'loftloader_rotate_curve' ) );
				if ( ( '2d' === $img_rotate_direction ) && ! empty( $img_rotate_2d ) ) {
					array_push( $classes, $img_rotate_2d );
				}
			}
			// Inner Elements Animation
			$entrance_animation = llp_get_loader_setting( 'loftloader_pro_inner_elements_entrance_animation' );
			$exit_animation		= llp_get_loader_setting( 'loftloader_pro_inner_elements_exit_animation' );
			if ( ! empty( $entrance_animation ) ) {
				array_push( $classes, $entrance_animation );
			}
			if ( ! empty( $exit_animation ) ) {
				array_push( $classes, $exit_animation );
			}
			if ( $this->check_background() ) {
				array_push( $classes, 'bg-split' );
			}
			'on' == llp_get_loader_setting( 'loftloader_pro_adaptive_loading_screen_height_on_mobile' ) ? array_push( $classes, 'adaptive-height' ) : '';
			return $classes;
		}
		/**
		* Get classes for loftloader wrap
		*
		* @return string html class attribute
		*/
		private function loader_classes() {
			$classes = apply_filters( 'loftloader_pro_loader_classes', array() );
			// Run filters if any changes from other plugins or theme currently used
			$classes = array_filter( $classes, function( $val ) {
				return ! empty( $val ) && ( 'false' !== $val );
			} );
 			// Returen the class attribute
			return empty( $classes ) ? '' : sprintf( ' class="%s"', esc_attr( implode( ' ', $classes ) ) );
		}
		/**
		* Loader wrapper attributes
		*/
		public function loader_attributes( $json ) {
			$load_time = llp_get_loader_setting( 'loftloader_pro_load_time' );
			if ( ! empty( $load_time ) && is_numeric( $load_time ) ) {
				$load_time = number_format( $load_time, 1, '.', '' );
				$json['minimalLoadTime'] = intval( $load_time * 1000 );
			}
			$show_close_time = llp_get_loader_setting( 'loftloader_pro_show_close_timer' );
			$show_close_time = number_format( $show_close_time, 0, '.', '' );
			$json['showCloseBtnTime'] = intval( $show_close_time * 1000 );


			$max_load_time = llp_get_loader_setting( 'loftloader_pro_max_load_time' );
			$max_load_time = max( 0, number_format( $max_load_time, 1, '.', '' ) );
			if ( ! empty( $max_load_time ) ) {
				$json['maximalLoadTime'] = intval( $max_load_time * 1000 );
			}

			return $json;
		}
		/**
		* Modify attributes of open <html> tag if needed
		*
		* @param html string the original html
		* @return html string the modified html string
		*/
		private function modify_html_tag( $html ) {
			$html_class = $this->get_html_class();
			if ( ! empty( $html ) && ! empty( $html_class ) ) {
				$regexp_html ='/(<html[^>]*)/i';
				$split = preg_split( $regexp_html, $html, 0, PREG_SPLIT_DELIM_CAPTURE );
				if ( is_array( $split ) && ( 3 <= count( $split ) ) ) {
					for( $i = 1; $i < ( count( $split ) - 1 ); $i = ( $i + 2 ) ) {
						$current_html = $split[ $i ];
						if ( ! empty( $current_html ) ) {
							$regexp_class ='/class\s*=\s*["\']([^"\']*)["\']/i';
							$attrs = preg_split( $regexp_class, $current_html, 3, PREG_SPLIT_DELIM_CAPTURE );
							if ( is_array( $attrs ) && ( 3 === count( $attrs ) ) ) {
								$exist_class = empty( $attrs[1] ) ? array() : explode( ' ', $attrs[1] );
								array_push( $exist_class, $html_class );
								$classes = sprintf( 'class="%s"', implode( ' ', $exist_class ) );
								$attrs = $attrs[0] . $classes . $attrs[2];
							} else{
								$attrs = sprintf( '%1$s class="%2$s"', $current_html, $html_class );
							}
							$split[ $i ] = $attrs;
						}
					}
					$html = implode( '', $split );
				}
			}
			return $html;
		}
		/**
		* Get background class list for image background
		*/
		private function get_image_bg_class() {
			$class = array();
			$bg_image = llp_get_background_image();
			if ( ( 'image' === $this->bg_type ) && ! empty( $bg_image ) ) {
				if ( 'tile' === llp_get_loader_setting( 'loftloader_pro_bg_image_repeat') ){
					array_push( $class, 'bg-img pattern' );
				} else {
					if ( 'contain' === llp_get_loader_setting( 'loftloader_pro_bg_image_size' ) ) {
						array_push( $class, 'bg-contain' );
					}
					array_push( $class, 'bg-img' );
					array_push( $class, 'full' );
				}
			}
			if ( llp_module_enabled( 'loftloader_pro_insite_transition' ) && llp_module_enabled( 'loftloader_pro_insite_transition_reverse_bg_animation' ) ) {
				array_push( $class, 'spt-bg-reverse' );
			}
			return empty( $class ) ? '' : sprintf( ' %s', implode( ' ', $class ) );
		}
		/**
		* Background image for loader type loading with custom image
		*
		* @param url image url
		* @return string html
		*/
		private function get_loader_type_loading_bg_image( $image ) {
			return sprintf(
				'<div class="imgloading-container"><span style="background-image: url(%s);" data-no-lazy="1" class="skip-lazy"></span></div>',
				esc_url( $image )
			);
		}
		/**
		* Get loader image
		*/
		protected function get_loader_image( $img, $type ) {
			if ( empty( $img ) ) return '';

			$width = 80;
			$height = 80;
			$is_frame = ( 'frame' == $type );

			$pid = attachment_url_to_postid( $img );
			$has_valid_image_attrs = false;
			$image_attrs = array();
			if ( empty( $pid ) ) {
				$info = getimagesize( $img );
				if ( $has_valid_image_attrs = ( ! empty( $info[1] ) ) && ( $info[0] > 1 ) ) {
					$image_attrs = array( 'width' => $info[0], 'height' => $info[1] );
				}
			} else {
				$image = wp_get_attachment_image_src( $pid, 'full' );
				if ( $has_valid_image_attrs = ( $image[1] > 1 ) ) {
					$image_attrs = array( 'width' => $image[1], 'height' => $image[2] );
				}
			}
			if ( $is_frame ) {
				if ( $has_valid_image_attrs ) {
					$width = $image_attrs['width'];
					$height = $image_attrs['height'];
				}
			} else {
				$width = intval( llp_get_loader_setting( 'loftloader_imgwidth' ) );
				$width = ( $width > 0 ) ? $width : 80;
				$height = $has_valid_image_attrs ? ( $image_attrs['height'] / $image_attrs['width'] * $width ) : $width;
			}
			return sprintf(
					'<img width="%3$s" height="%4$s" data-no-lazy="1" class="skip-lazy" alt="%1$s" src="%2$s">',
					esc_attr__( 'loader image', 'loftloader-pro' ),
					esc_url( $img ),
					esc_attr( intval( $width ) ),
					esc_attr( intval( $height ) )
				);
		}
		/**
		* Get loader message position
		*/
		private function get_message_position() {
			$progress_type 			= llp_get_loader_setting( 'loftloader_progress' );
			$percentage_position 	= llp_get_loader_setting( 'loftloader_percentageposition' );
			$bar_position 			= llp_get_loader_setting( 'loftloader_barposition' );
			$message_position 		= llp_get_loader_setting( 'loftloader_pro_message_position' );

			if ( ( ('bar' === $progress_type ) && ( 'middle' === $bar_position ) )
				|| ( ( 'number' === $progress_type ) && ( 'below' === $percentage_position ) ) ) {
				return $message_position;
			} else {
				return ( 'middle' === $message_position ) ? 'bottom' : $message_position;
			}
		}
		/**
		* Get message text
		*/
		protected function get_message( $is_customize_preview ) {
			$raw_message = '';
			if ( 'on' === llp_get_loader_setting( 'loftloader_pro_enable_random_message_text' ) ) {
				$messages = trim( llp_get_loader_setting( 'loftloader_pro_random_message_text' ) );
				if ( ! empty( $messages ) ) {
					if ( 'on' === llp_get_loader_setting( 'loftloader_pro_render_random_message_by_js' ) ) {
						return '<div class="loader-message">&nbsp;</div>';
					} else {
						$raw_message = llp_get_random_message();
						return empty( $raw_message ) && ! $is_customize_preview ? '' : sprintf(
							'<div class="loader-message">%s</div>',
							wp_kses_post( $raw_message )
						);
					}

				} else {
					return $is_customize_preview ? '<div class="loader-message"></div>' : '';
				}
			} else {
				$raw_message = llp_get_loader_setting( 'loftloader_pro_message_text' );
				return empty( $raw_message ) && ! $is_customize_preview ? '' : sprintf(
					'<div class="loader-message">%s</div>',
					wp_kses_post( $raw_message )
				);
			}
		}
		/**
		* Get html class
		*/
		protected function get_html_class() {
			$global_enabled = apply_filters( 'loftloader_pro_enabled_session', false );
		    $page_enabled = apply_filters( 'loftloader_pro_page_show_once', false );

			if ( llp_use_cache_scripts() && ( $global_enabled || $page_enabled ) ) {
				return 'loftloader-pro-hide';
			}

			return '';
		}
		/**
		* Check autoplay videos
		*/
		protected function check_autoplay_videos( $html ) {
			if ( llp_module_enabled( 'loftloader_pro_detect_autoplay_video' ) && ! $this->is_mobile ) {
				$dom = new simple_html_dom();
				$regex = '#https?://(?:www\.)?(?:youtube\.com/embed|youtu\.be/)#';
				$dom->load( $html );
				foreach ( $dom->find( 'iframe' ) as $iframe ) {
					if ( ! empty( $iframe->src ) && preg_match( $regex, $iframe->src ) && ( false !== strpos( $iframe->src, 'autoplay=1' ) ) ) {
						$src = $iframe->src;
						$allows = $iframe->allow;
						$iframe->enablejsapi = 1;
						if ( false !== strpos( $src, 'enablejsapi' ) ) {
							$src = preg_replace( '/enablejsapi(=\d)?/', 'enablejsapi=1', $src );
						} else {
							$src .= '&enablejsapi=1';
						}
						$iframe->src = $src;
						if ( empty( $allows ) ) {
							$allows = array( 'autoplay' );
						} else {
							$allows = explode( ';', $allows );
							$allows = array_map( 'trim', $allows );
							if ( ! in_array( 'autoplay', $allows ) ) {
								array_push( $allows, 'autoplay' );
							}
						}
						$iframe->allow = implode( '; ', $allows );
					}
				}
				$html = $dom->save();
			}
			return $html;
		}
	}
	new LoftLoader_Pro_Frontend_Loader_HTML();
}
