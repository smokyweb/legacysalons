<?php
if ( ! class_exists( 'LoftLoader_Pro_Inline_Assets' ) ) {
    class LoftLoader_Pro_Inline_Assets {
        /**
        * Boolean to tell if disable page scrolling funtion during loading
        */
        protected $disable_page_scroll = false;
        /**
        * Construct function
        */
        public function __construct() {
            add_action( 'loftloader_pro_init_front', array( $this, 'init_front' ) );
            add_action( 'loftloader_pro_clear_front', array( $this, 'destroy_front' ) );
        }
        /**
        * Init front hooks
        */
        public function init_front() {
			// Set the page scroll while loading setting
			$this->disable_page_scroll = llp_module_enabled( 'loftloader_pro_disable_page_scrolling' ) && ! is_customize_preview();
            add_filter( 'body_class', array( $this, 'set_body_class' ) );
            add_action( 'wp_head', array( $this, 'early_inline_assets' ), 0 );
            add_action( 'wp_head', array( $this, 'add_inline_assets' ), 9999999 );
			add_filter( 'loftloader_pro_front_json', array( $this, 'loader_attributes' ), 99 );
        }
        /**
        * Destroy frontend hooks
        */
        public function destroy_front() {
            add_action( 'wp_head', array( $this, 'clear_localstorage' ), 99 );
        }
		/**
		* Check if enable the option disbale page scroll while loading, if so add class to <body>
		*
		* @param array class list
		* @return array refined class list
		*/
		public function set_body_class( $class ) {
			array_push( $class, 'loftloader-pro-enabled' );
			// If page scroll while loading is disabled, add the class to <body>
			if ( $this->disable_page_scroll ) {
				array_push( $class, 'loftloader-disable-scrolling' );
			}
			return $class;
		}
		/**
		* Add inline styles and scripts
		*/
        public function add_inline_assets() { ?>
            <noscript><style>#loftloader-wrapper { display: none !important; }</style></noscript>
            <style> html.loftloader-pro-hide #loftloader-wrapper, html.loftloader-pro-spt-hide #loftloader-wrapper { display: none !important; } html.loftloader-pro-spt-hide #loftloader-wrapper.split-diagonally .loader-bg { background: none !important; } </style><?php
            do_action( 'loftloader_pro_inline_assets' );
            if ( $this->disable_page_scroll ) : ?>
                <style id="loftloader-pro-disable-scrolling"> body.loftloader-disable-scrolling { overflow: hidden !important; max-height: 100vh !important; height: 100%; position: fixed !important; width: 100%; } </style>
                <style id="loftloader-pro-always-show-scrollbar"> html { overflow-y: scroll !important; } </style><?php
            endif;
            $this->inline_scripts();
			$additional_css = llp_get_loader_setting( 'loftloader_pro_custom_css' );
			if ( ! empty( $additional_css ) ) : ?>
				<style type="text/css" id="loftloader-custom-inline-style"><?php echo strip_tags( $additional_css ); ?></style><?php
			endif;

        }
        /**
        * Add smooth transition style as early as possible
        */
        public function early_inline_assets() {
            // Add insite transition related inline styles
            if ( $this->test_insite_transition() ) :
                $styles = $this->get_html_styles();
                if ( ! empty( $styles ) ) :
                	$spt_display_option = apply_filters( 'loftloader_pro_smooth_page_transition_display_option', false );
                	$spt_display_default = empty( $spt_display_option ) ? 1 : 0;
                	$spt_display_current_page = ( 'current-page' == $spt_display_option ); ?>
                    <script type="text/javascript">
                    	var loftloaderHTML = document.documentElement,
                            loftLoaderProEarlySessionStorage = { getItem: function( name ) { try { return sessionStorage.getItem( name ); } catch( msg ) { return false; } } },
                    		loftloaderProHasSesstion = ( loftLoaderProEarlySessionStorage.getItem( 'loftloader-pro-smooth-transition' ) && ( 'on' === loftLoaderProEarlySessionStorage.getItem( 'loftloader-pro-smooth-transition' ) ) );
                		<?php if ( ! $spt_display_current_page ) : ?>
			            	if ( <?php echo $spt_display_default; ?> || loftloaderProHasSesstion ) {
	                        	loftloaderHTML.classList.add( 'loftloader-smooth-transition' );
			                    loftloaderHTML.setAttribute( 'data-original-styles', loftloaderHTML.getAttribute( 'style' ) || '' );
			                    loftloaderHTML.setAttribute( 'style', '<?php echo wp_kses( $styles, array( "\'", '\"' ) ); ?>' );
			                } else {
			                	loftloaderHTML.classList.add( 'loftloader-pro-spt-hide' );
			                }
                		<?php else : ?>
                			loftloaderHTML.classList.add( 'loftloader-pro-spt-hide' );
                		<?php endif; ?>
	                </script><?php
                endif;
            endif;
            $loader_image_url = llp_check_image_url( llp_get_loader_setting( 'loftloader_customimg' ) );
            if ( ! empty( $loader_image_url ) ) {
                $loader_type = llp_get_loader_setting( 'loftloader_animation' );
                if ( in_array( $loader_type, array( 'frame', 'imgloading', 'imgrotating', 'imgbouncing', 'imgstatic', 'imgfading' ) ) ) : ?>
                    <link rel="preload" as="image" href="<?php echo esc_url( $loader_image_url ); ?>"><?php
                endif;
            }
            $background_type = llp_get_loader_setting( 'loftloader_bgfilltype' );
			if ( 'image' == $background_type ) {
                $background_image_url = llp_get_background_image();
    			$background_image_url = llp_check_image_url( $background_image_url );
                if ( ! empty( $background_image_url ) ) : ?>
                    <link rel="preload" as="image" href="<?php echo esc_url( $background_image_url ); ?>"><?php
                endif;
            }
        }
		/**
		* Add insite transition attributes to loader wrapper
		*
		* @param array original attributes
		* @return array modified attributes
		*/
		public function loader_attributes( $json ) {
			if ( $this->test_insite_transition() ) {
				$exclude = llp_get_loader_setting( 'loftloader_pro_exclude_from_page_transition' );
				$json['insiteTransition' ] = 'on';
				$json['siteRootURL'] = esc_url( get_home_url() );

				if ( ! empty( $exclude ) ) {
					$json['insiteTransitionCustomExcluded'] = esc_js( $exclude );
				}
			}
			return $json;
		}
		/**
		* Cache script
		*/
		public function inline_scripts() {
			if ( ! is_customize_preview() ) {
				$assets = LOFTLOADERPRO_ROOT . 'assets/js/';
				// Cache related script
				if ( llp_use_cache_scripts() ) {
					require_once $assets . 'loftloader-cache.php';
				}
				require_once $assets . 'loftloader-global.php';
			}
		}
		/**
		* Check if enable insite page transition
		* @return boolean
		*/
		protected function test_insite_transition() {
			return apply_filters( 'loftloader_pro_smooth_page_transition_enabled', false );

		}
		/**
		* Get initial styles for <html>
		*/
		protected function get_html_styles() {
            if ( 'none' == llp_get_loader_setting( 'loftloader_bgfilltype' ) ) return false;

			$bg_type 				= llp_get_loader_setting( 'loftloader_bgfilltype' );
			$bg_color 				= llp_get_loader_setting( 'loftloader_pro_bg_color' );
			$gradient_angel 		= llp_get_loader_setting( 'loftloader_pro_bg_gradient_angel' );
			$gradient_start_color 	= llp_get_loader_setting( 'loftloader_pro_bg_gradient_start_color' );
			$gradient_end_color 	= llp_get_loader_setting( 'loftloader_pro_bg_gradient_end_color' );
			$image_url 				= llp_get_background_image();
			$bg_repeat 				= llp_get_loader_setting( 'loftloader_pro_bg_image_repeat' );
			$bg_size 				= llp_get_loader_setting( 'loftloader_pro_bg_image_size' );
			$styles 				= sprintf( 'background-color: %s;', llp_get_loader_setting( 'loftloader_pro_bg_color' ) );


			// $bg_opacity = ( llp_get_loader_setting( 'loftloader_bgopacity' ) / 100 );
			// $bg_opacity = number_format( $bg_opacity, 2, '.', '' );
            // $styles .= sprintf( ' opacity: %s !important;', $bg_opacity );

			switch ( $bg_type ) {
				case 'solid':
					if ( llp_module_enabled( 'loftloader_pro_bg_gradient' ) ) {
						$gradient 	= sprintf( '(%sdeg, %s, %s);', $gradient_angel, $gradient_start_color, $gradient_end_color );
						$prefix 	= array( '-webkit-linear-gradient', '-o-linear-gradient', '-moz-linear-gradient', 'linear-gradient' );
						foreach( $prefix as $p ) {
							$styles	.= sprintf( ' background-image: %s%s', $p, $gradient );
						}
					}
					if ( ! empty( $bg_color ) ) {
						$styles .= sprintf( ' background-color: %s', $bg_color );
					}
					break;
				case 'image':
					if ( ! empty( $image_url ) ) {
						$styles .= sprintf( ' background-image: url(%1$s); background-position: %2$s;', esc_url( $image_url ), '50% 50%' );
						$styles .= sprintf( ' background-repeat: %s;', ( 'tile' == $bg_repeat ? 'repeat' : 'no-repeat' ) );
						if ( 'tile' != $bg_repeat ) {
							$styles .= sprintf( ' background-size: %s;', $bg_size );
						}
					}
					break;
			}
			return $styles;
		}
		/**
		* Clear browser local storage
		*/
		public function clear_localstorage() {
			if ( $this->test_insite_transition() ) : ?>
			<script type="text/javascript">
                var LoftLoaderProEarlySessionStorage = {
                    getItem: function( name ) {
                        try {
                            return sessionStorage.getItem( name );
                        } catch( msg ) {
                            return false;
                        }
                    },
                    setItem: function( name, value ) {
                        try {
                            sessionStorage.setItem( name, value );
                        } catch( msg ) {}
                    }
                };
				if ( LoftLoaderProEarlySessionStorage.getItem( 'loftloader-pro-smooth-transition' ) && ( 'on' === LoftLoaderProEarlySessionStorage.getItem( 'loftloader-pro-smooth-transition' ) ) ) {
					LoftLoaderProEarlySessionStorage.setItem( 'loftloader-pro-smooth-transition', 0 );
				}
			</script> <?php
			endif;
		}
    }
    new LoftLoader_Pro_Inline_Assets();
}
