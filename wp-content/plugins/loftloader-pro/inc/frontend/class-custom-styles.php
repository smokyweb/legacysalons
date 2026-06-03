<?php
// Not allowed by directly accessing.
if ( ! defined( 'ABSPATH' ) ) {
	die( 0 );
}

if ( ! class_exists( 'LoftLoader_Pro_Frontend_Custom_Styles' ) ) {
	/**
	 * @since version 1.1.9
	 */
	class LoftLoader_Pro_Frontend_Custom_Styles {
		private $custom_style_file 	= '';
		private $custom_style_uri 	= false;
		private $external_styles 	= false;
		public function __construct() {
			$this->init();

			add_action( 'loftloader_pro_init_front', 	array( $this, 'init_front') );
			add_filter( 'loftloader_pro_custom_styles', array( $this, 'generate_custom_styles' ), 10, 2 );
		}
		/**
		* Initialize frontend related functions
		* 	1. Output inline custom styles
		* 	2. Or enqueue external custom styles
		*/
		public function init_front() {
			// Set if the custom styles is stored in external file or inline
			$this->is_external_custom_styles();
			// Add actions to output custom styles
			add_action( 'wp_enqueue_scripts', array( $this, 'load_external_custom_styles' ), 0 );
			add_filter( 'loftloader_pro_method_load_custom_css', array( $this, 'method_load_custom_css' ) );
		}
		/**
		* Enqueue external custom styles if needed
		*/
		public function load_external_custom_styles() {
			if ( $this->external_styles ) {
				wp_enqueue_style(
					'loftloader-custom-style',
					$this->custom_style_uri,
					array( 'loftloader-style' ),
					get_option( 'loftloader_pro_css_in_file_rand_version', LOFTLOADERPRO_ASSET_VERSION )
				);
			} else {
				$custom_styles = apply_filters( 'loftloader_pro_custom_styles', '' );
				if ( ! empty( $custom_styles ) ) {
					wp_add_inline_style( 'loftloader-style', wp_kses( $custom_styles, array( "\'", '\"' ) ) );
				}
			}
		}
		/**
		* @description generate custom styles
		*/
		public function generate_custom_styles( $styles = '', $in_file = false ) {
			$this->check_style_in_file( $in_file );
			// Basic background settings
			$bg_type 	= llp_get_loader_setting( 'loftloader_bgfilltype' );
			$bg_opacity = ( llp_get_loader_setting( 'loftloader_bgopacity' ) / 100 );
			$bg_opacity = number_format( $bg_opacity, 2, '.', '' );
			// Convert hex color to rgba style
			$rgba = llp_hex2rgba( llp_get_loader_setting( 'loftloader_pro_animation_color' ), 0.5 );

			// Gradient background
			$gradient = ( llp_module_enabled( 'loftloader_pro_bg_gradient' ) && ( 'solid' === $bg_type  ) ) ? sprintf(
				'(%sdeg, %s, %s);',
				llp_get_loader_setting( 'loftloader_pro_bg_gradient_angel' ),
				llp_get_loader_setting( 'loftloader_pro_bg_gradient_start_color' ),
				llp_get_loader_setting( 'loftloader_pro_bg_gradient_end_color' )
			) : '';

			$image_url = llp_get_background_image();
			$image_url = llp_check_image_url( $image_url );
			$bg_image  = ( $bg_type === 'image' ) && ! empty( $image_url );

			// Progress bar width unit
			$bar_unit 	= llp_module_enabled( 'loftloader_pro_progress_width_unit' ) ? 'px' : 'vw';
			$bar_width 	= llp_get_loader_setting( 'loftloader_barwidth' );
			if ( 'vw' === $bar_unit ){
				$bar_width = max( min( 100, $bar_width ), 0 );
			}

			$bg_animation = llp_get_loader_setting( 'loftloader_pro_bg_animation' );

			// Background opacity
			$styles .= sprintf(
				"%s { opacity: %s; }",
				'#loftloader-wrapper .loader-bg',
				$bg_opacity
			);
			// Background for split-diagonally-v and split-diagonally-h only
			if ( in_array( $bg_animation, array( 'split-diagonally-v', 'split-diagonally-h' ) ) && ( !is_customize_preview() || $in_file ) ) {
				$split_diagonally_selectors = implode( ', ', array(
					'#loftloader-wrapper.end-split-v.split-diagonally .loader-bg',
					'#loftloader-wrapper.end-split-h.split-diagonally .loader-bg'
				) );
				$styles .= sprintf(
					"%s { background-color: %s; }",
					$split_diagonally_selectors,
					llp_get_loader_setting( 'loftloader_pro_bg_color' )
				);

				if ( $gradient ) {
					$styles .= sprintf(
						'%1$s { background-image: -webkit-linear-gradient%2$s background-image: -o-linear-gradient%2$s background-image: -moz-linear-gradient%2$s background-image: linear-gradient%2$s }',
						$split_diagonally_selectors,
						$gradient
					);
				}

				if ( $bg_image ) {
					$styles .= sprintf(
						"%s { background-image: url(%s); }",
						$split_diagonally_selectors,
						esc_url( $image_url )
					);
				}
			}
			// Background color
			$bg_color = llp_get_loader_setting( 'loftloader_pro_bg_color' );
			if ( ! empty( $bg_color ) ) {
				$styles .= sprintf(
					"%s { background-color: %s; }",
					implode( ', ', array(
						'#loftloader-wrapper.end-split-h .loader-bg:before',
						'#loftloader-wrapper.end-split-h .loader-bg:after',
						'#loftloader-wrapper.end-split-v .loader-bg:before',
						'#loftloader-wrapper.end-split-v .loader-bg:after',
						'#loftloader-wrapper.end-fade .loader-bg',
						'#loftloader-wrapper.end-up .loader-bg',
						'#loftloader-wrapper.end-down .loader-bg',
						'#loftloader-wrapper.end-left .loader-bg',
						'#loftloader-wrapper.end-right .loader-bg',
						'#loftloader-wrapper.end-no-animation .loader-bg',
						'#loftloader-wrapper.end-shrink-fade .loader-bg:before',
						'.loader-bg .loader-bg-half:before '
					) ),
					$bg_color
				);
			}
			// Gradient background
			if ( $gradient ) {
				$styles .= sprintf(
					'%1$s { background-image: -webkit-linear-gradient%2$s background-image: -o-linear-gradient%2$s background-image: -moz-linear-gradient%2$s background-image: linear-gradient%2$s }',
					implode( ', ', array(
						'#loftloader-wrapper.end-split-h .loader-bg:before',
						'#loftloader-wrapper.end-split-h .loader-bg:after',
						'#loftloader-wrapper.end-split-v .loader-bg:before',
						'#loftloader-wrapper.end-split-v .loader-bg:after',
						'#loftloader-wrapper.end-fade .loader-bg',
						'#loftloader-wrapper.end-up .loader-bg',
						'#loftloader-wrapper.end-down .loader-bg',
						'#loftloader-wrapper.end-left .loader-bg',
						'#loftloader-wrapper.end-right .loader-bg',
						'#loftloader-wrapper.end-no-animation .loader-bg',
						'#loftloader-wrapper.end-shrink-fade .loader-bg:before '
					) ),
					$gradient
				);
			}
			// Image background
			if ( $bg_image ) {
				$styles .= sprintf(
					"%s { background-image: url(%s); }",
					implode( ', ', array(
						'#loftloader-wrapper.end-split-h .loader-bg:before',
						'#loftloader-wrapper.end-split-h .loader-bg:after',
						'#loftloader-wrapper.end-split-v .loader-bg:before',
						'#loftloader-wrapper.end-split-v .loader-bg:after',
						'#loftloader-wrapper.end-fade .loader-bg',
						'#loftloader-wrapper.end-up .loader-bg',
						'#loftloader-wrapper.end-down .loader-bg',
						'#loftloader-wrapper.end-left .loader-bg',
						'#loftloader-wrapper.end-right .loader-bg',
						'#loftloader-wrapper.end-no-animation .loader-bg',
						'#loftloader-wrapper.end-shrink-fade .loader-bg:before',
						'.loader-bg .loader-bg-half:before '
					) ),
					esc_url( $image_url )
				);
			}
			// Loader color (single color)
			$styles .= sprintf(
				"%s { color: %s }",
				implode( ', ', array(
					'#loftloader-wrapper .loader-inner #loader',
					'#loftloader-wrapper.loftloader-ducks #loader span'
				) ),
				llp_get_loader_setting( 'loftloader_pro_animation_color' )
			);
			$styles .= sprintf(
				'%1$s { box-shadow: 0 -15px 0 0 %2$s, 15px -15px 0 0 %2$s, 15px 0 0 0 %2$s, 15px 15px 0 0 %2$s, 0 15px 0 0 %2$s, -15px 15px 0 0 %2$s, -15px 0 0 0 %2$s, -15px -15px 0 0 %2$s; }',
				'#loftloader-wrapper.loftloader-crystal #loader span',
				$rgba
			);
			// Loader color (two colors)
			$styles .= sprintf(
				"%s { background: %s }",
				'#loftloader-wrapper.loftloader-crossing #loader span:before',
				llp_get_loader_setting( 'loftloader_pro_animation_crossing_left_color' )
			);
			$styles .= sprintf(
				"%s { background: %s }",
				'#loftloader-wrapper.loftloader-crossing #loader span:after',
				llp_get_loader_setting( 'loftloader_pro_animation_crossing_right_color' )
			);
			// Loader color (three colors)
			$styles .= sprintf(
				"%s { box-shadow: 0 0 0 10px %s, 0 0 0 20px %s, 0 0 0 30px %s; }",
				'#loftloader-wrapper.loftloader-rainbow #loader span:before',
				llp_get_loader_setting( 'loftloader_pro_animation_rainbow_inner_color' ),
				llp_get_loader_setting( 'loftloader_pro_animation_rainbow_middle_color' ),
				llp_get_loader_setting( 'loftloader_pro_animation_rainbow_outer_color' )
			);
			// Loader frame width
			$styles .= sprintf(
				'%s { width: %spx; }',
				'#loftloader-wrapper.loftloader-frame #loader',
				llp_get_loader_setting( 'loftloader_pro_animation_frame_width' )
			);
			// Loader frame height
			$styles .= sprintf(
				'%s { height: %spx; }',
				'#loftloader-wrapper.loftloader-frame #loader',
				llp_get_loader_setting( 'loftloader_pro_animation_frame_height' )
			);
			// Loader frame border width & height
			$styles .= sprintf(
				'%s { width: %spx; }',
				implode( ', ', array(
					'#loftloader-wrapper.loftloader-frame #loader span:after',
					'#loftloader-wrapper.loftloader-frame #loader span:before'
				) ),
				llp_get_loader_setting( 'loftloader_pro_animation_frame_border_width' )
			);
			$styles .= sprintf(
				'%s { height: %spx; }',
				implode( ', ', array(
					'#loftloader-wrapper.loftloader-frame #loader:after',
					'#loftloader-wrapper.loftloader-frame #loader:before'
				) ),
				llp_get_loader_setting( 'loftloader_pro_animation_frame_border_width' )
			);
			// Loader image width
			$styles .= sprintf(
				'%s { width: %spx; }',
				implode( ', ', array(
					'#loftloader-wrapper.loftloader-imgfading #loader img',
					'#loftloader-wrapper.loftloader-imgloading #loader img',
					'#loftloader-wrapper.loftloader-imgrotating #loader img',
					'#loftloader-wrapper.loftloader-imgbouncing #loader img',
					'#loftloader-wrapper.loftloader-imgstatic #loader img'
				) ),
				llp_get_loader_setting( 'loftloader_imgwidth' )
			);
			// Loader image max width for responsive design
			$responsive_max_width = llp_get_loader_setting( 'loftloader_responsive_design_max_width' );
			$styles .= sprintf(
				'%s { max-width: %s%s; }',
				implode( ', ', array(
					'#loftloader-wrapper.loftloader-imgfading .loader-inner #loader',
					'#loftloader-wrapper.loftloader-imgloading .loader-inner #loader',
					'#loftloader-wrapper.loftloader-imgrotating .loader-inner #loader',
					'#loftloader-wrapper.loftloader-imgbouncing .loader-inner #loader',
					'#loftloader-wrapper.loftloader-imgstatic .loader-inner #loader'
				) ),
				number_format( $responsive_max_width, 0, '.', ',' ),
				'%'
			);
			// Progress bar width
			$styles .= sprintf(
				'%s { width: %s; }',
				'#loftloader-wrapper span.bar',
				$bar_width . $bar_unit
			);
			// Progress bar height
			$styles .= sprintf(
				'%s { height: %spx; }',
				'#loftloader-wrapper span.bar',
				llp_get_loader_setting( 'loftloader_barheight' )
			);
			// Progress color
			$styles .= sprintf(
				'%s { color: %s; }',
				'#loftloader-wrapper span.bar, #loftloader-wrapper span.percentage',
				llp_get_loader_setting( 'loftloader_pro_progress_color' )
			);
			// Progress bar gradient color
			$progress_type = llp_get_loader_setting( 'loftloader_progress' );
			if ( in_array( $progress_type, array( 'bar', 'bar-number' ) ) && llp_module_enabled( 'loftloader_pro_progress_bar_enable_gradient_color' ) ) {
				$start_color = llp_get_loader_setting( 'loftloader_pro_progress_bar_gradient_start_color' );
				$end_color = llp_get_loader_setting( 'loftloader_pro_progress_bar_gradient_end_color' );
				if ( ! empty( $start_color ) && ! empty( $end_color ) ) {
					$styles .= sprintf(
						'%1$s { background-image: -webkit-linear-gradient%2$s; background-image: -o-linear-gradient%2$s; background-image: -moz-linear-gradient%2$s; background-image: linear-gradient%2$s; }',
						'#loftloader-wrapper span.bar span.load.gradient-color-enabled',
						sprintf( '(90deg, %1$s, %2$s)', $start_color, $end_color )
					);
				}
			}
			// Progress percentage font family
			$number_font = llp_get_loader_setting( 'loftloader_pro_progress_number_font_family' );
			if ( llp_module_enabled( 'loftloader_pro_progress_number_enable_google_font' ) && ! empty( $number_font ) ) {
				$styles .= sprintf(
					'%s { font-family: %s; }',
					implode( ', ', array(
						'#loftloader-wrapper span.percentage',
						'#loftloader-wrapper span.bar span.load-count'
					) ),
					$number_font
				);
			}
			// Progress percentage font weight
			$styles .= sprintf(
				'%s { font-weight: %s; }',
				implode( ', ', array(
					'#loftloader-wrapper span.percentage',
					'#loftloader-wrapper span.bar span.load-count'
				) ),
				llp_get_loader_setting( 'loftloader_pro_progress_number_font_weight' )
			);
			// Progress percentage letter spacing
			$styles .= sprintf(
				'%s { letter-spacing: %s; }',
				implode(', ', array(
					'#loftloader-wrapper span.percentage',
					'#loftloader-wrapper span.bar span.load-count'
				) ),
				llp_get_loader_setting( 'loftloader_pro_progress_number_letter_spacing' )
			);
			// Progress percentage font size
			$styles .= sprintf(
				'%s { font-size: %spx; }',
				implode( ', ', array(
					'body #loftloader-wrapper span.percentage',
					'body #loftloader-wrapper span.bar span.load-count'
				) ),
				llp_get_loader_setting( 'loftloader_percentagesize' )
			);
			// Message font size
			$styles .= sprintf(
				'%s { font-size: %spx; }',
				'#loftloader-wrapper .loader-message',
				llp_get_loader_setting( 'loftloader_pro_message_size' )
			);
			// Message color
			$styles .= sprintf(
				'%s { color: %s; }',
				'#loftloader-wrapper .loader-message',
				llp_get_loader_setting( 'loftloader_pro_message_color' )
			);
			// Message font family
			$message_font = llp_get_loader_setting( 'loftloader_pro_message_font_family' );
			if ( ! empty( $message_font ) && llp_module_enabled( 'loftloader_pro_message_enable_google_font' ) ) {
				$styles .= sprintf(
					'%s { font-family: %s; }',
					'#loftloader-wrapper .loader-message',
					$message_font
				);
			}
			// Message font weight
			$styles .= sprintf(
				'%s { font-weight: %s; }',
				'#loftloader-wrapper .loader-message',
				llp_get_loader_setting( 'loftloader_pro_message_font_weight' )
			);
			// Message letter spacing
			$styles .= sprintf(
				'%s { letter-spacing: %s; }',
				'#loftloader-wrapper .loader-message',
				llp_get_loader_setting( 'loftloader_pro_message_letter_spacing' )
			);
			// Message line height
			$styles .= sprintf(
				'%s { line-height: %s; }',
				'#loftloader-wrapper .loader-message',
				llp_get_loader_setting( 'loftloader_pro_message_line_height' )
			);
			// Animation transition duration for petals only
			if ( ( 'once' === llp_get_loader_setting('loftloader_looping') ) && ( 'petals' === llp_get_loader_setting( 'loftloader_animation' ) ) ) {
				$styles .= '#loftloader-wrapper.loftloader-once.loftloader-petals #loader span { transition-duration: 0.3s; }';
			}

			// Progress percentage margin
			$percentage_margins = llp_get_loader_setting( 'loftloader_percentage_margin' );
			if ( is_array( $percentage_margins ) && ( count( $percentage_margins ) === 4 ) ) {
				$css_properties = array( 'margin-top', 'margin-right', 'margin-bottom', 'margin-left' );
				$css_margin = '';
				foreach( $percentage_margins as $i => $margin ) {
					if ( is_numeric( $margin ) ) {
						$css_margin .= sprintf( ' %1$s: %2$spx !important;', $css_properties[ $i ], $margin );
					}
				}
				if ( ! empty( $css_margin ) ) {
					$styles .= '#loftloader-wrapper .percentage {' . $css_margin . ' }';
				}
			}

			return $styles;
		}
		/**
		** Initialize settings
		*/
		private function init() {
			$upload_dir = wp_upload_dir();
			$this->custom_style_file = $upload_dir['basedir'] . '/loftloader-pro/custom-styles.css';
			$this->custom_style_uri  = $upload_dir['baseurl'] . '/loftloader-pro/custom-styles.css';
		}
		/**
		* @description check load custom styles in file or inline
		*/
		private function is_external_custom_styles() {
			$in_file = ! is_customize_preview() && ( 'file' === llp_get_loader_setting( 'loftloader_pro_css_in_file' ) ) && file_exists( $this->custom_style_file );
			$this->external_styles = apply_filters( 'loftloader_pro_custom_styles_in_file', $in_file );
		}
		/**
		* Set the global flag to identify whether the custom style should be stored in external file
		*
		* @param boolean true is for external file
		*/
		private function check_style_in_file( $in_file ) {
			global $llp_external_custom_style;
			$llp_external_custom_style = $in_file;
		}
		/**
		* Get the method to load custom css
		*/
		public function method_load_custom_css( $method ) {
			return $this->external_styles ? 'external' : 'inline';
		}
	}
	new LoftLoader_Pro_Frontend_Custom_Styles();
}
