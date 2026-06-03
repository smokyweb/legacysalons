<?php
// Not allowed by directly accessing.
if ( ! defined( 'ABSPATH' ) ) {
	die( 0 );
}

if ( ! class_exists( 'LoftLoader_Pro_Frontend_Manager' ) ) {
	/**
	 * @since version 1.0
	 */
	class LoftLoader_Pro_Frontend_Manager {
		/**
		* Boolean to tell if enable session
		*/
		protected $enabled_session = false;
		/**
		* Boolean to tell if currently in mobile mode
		*/
		protected $is_mobile = false;
		/**
		*  Boolean to tell if current device is mobile only
		*/
		public $is_device_mobile = false;
		/**
		* Boolean to tell if scripts loaded early, just in case current WP does not support wp_body_open
		*/
		public $is_scripts_loaded = false;
		/**
		* String script loading priority
		*/
		public $scripts_loading_priority = 'normal';
		/**
		* Contruct function
		*/
		public function __construct() {
			$this->includes();

			add_action( 'template_redirect', array( $this, 'init' ), 1 );
			add_action( 'loftloader_pro_pre_init', array( $this, 'set_devices' ) );
			add_filter( 'loftloader_pro_loader_enabled', array( $this, 'is_loader_enabled' ) );
			add_filter( 'loftloader_pro_loader_enabled', array( $this, 'detect_url_switcher' ), 999 );
			add_filter( 'loftloader_pro_loader_enabled', array( $this, 'detect_page_builder' ), 999999 );
			add_filter( 'loftloader_pro_smooth_page_transition_enabled', array( $this, 'smooth_page_transition_enabled' ) );
		}
		/**
		* Setup devices based on current request from
		*/
		public function set_devices() {
			$detect = new LoftOcean_Mobile_Detect();
			$this->is_device_mobile = $detect->isMobile();
			$this->is_mobile = $detect->isMobile() || $detect->isTablet();
		}
		/**
		* Initialize loader
		*/
		public function init() {
			// Initialize any page settings if needed
			do_action( 'loftloader_pro_pre_init' );

			// Only run if loader enabled and on frontend
			if ( ! is_admin() ) {
				add_filter( 'loftloader_pro_enabled_session', array( $this, 'is_session_enabled' ) );

				if ( apply_filters( 'loftloader_pro_loader_enabled', false ) ) {
					$this->init_front();

					add_action( 'wp_head', array( $this, 'enqueue_styles' ), 0 );
					add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );

					$this->scripts_loading_priority = llp_get_loader_setting( 'loftloader_pro_scripts_loading_priority' );
					if ( 'high' == $this->scripts_loading_priority ) {
						add_action( 'wp_body_open', array( $this, 'enqueue_scripts_early' ), -999 );
					} else if ( 'low' == $this->scripts_loading_priority ) {
						add_action( 'wp_footer', array( $this, 'enqueue_scripts_late' ), 999 );
					}
					add_action( 'wp_footer', array( $this, 'enqueue_scripts_normal' ) );

					add_filter( 'loftloader_pro_is_mobile', array( $this, 'is_mobile_only' ) );
					add_filter( 'loftloader_pro_enabled_session', array( $this, 'is_session_enabled' ) );

					add_filter( 'rocket_exclude_defer_js', array( $this, 'exclude_script_files' ) );
					add_filter( 'rocket_exclude_async_css', array( $this, 'exclude_style_files' ) );
					add_filter( 'loftloader_pro_smooth_page_transition_display_option', array( $this, 'spt_display_option' ) );
				} else {
					do_action( 'loftloader_pro_clear_front' );
					if ( llp_use_cache_scripts() ) {
						add_action( 'wp_head', array( $this, 'keep_once_per_session' ), 0 );
					}
				}
			}
		}
		/**
		* Include files required
		*/
		protected function includes() {
			$inc = LOFTLOADERPRO_INC . 'frontend/';
			require_once $inc . 'class-cache.php';
			require_once $inc . 'class-inline-assets.php';
			require_once $inc . 'class-custom-styles.php';
			require_once $inc . 'class-loader-html.php';
			if ( ! class_exists( 'LoftOcean_Mobile_Detect' ) ) {
				require_once LOFTLOADERPRO_INC . 'vender/Mobile_Detect.php';
			}
		}
		/**
		* Check if loader disabled by URL parameters
		* @param boolean
		* @return boolean
		*/
		public function detect_url_switcher( $status ) {
			if ( isset( $_GET['pageloader'] ) && ( 'false' === sanitize_text_field( wp_unslash( $_GET['pageloader'] ) ) ) ) {
				return false;
			} else {
				$url_parameters = llp_get_loader_setting( 'loftloader_pro_disable_loader_by_url_parameter' );
				$url_parameters = trim( $url_parameters );
				if ( ! empty( $url_parameters ) ) {
					$list = preg_split( '/\r\n|\n|\r/', $url_parameters );
					$list = array_filter( $list );
					foreach ( $list as $up ) {
						$up = trim( $up );
						$pair = explode( '|', $up );
						if ( is_array( $pair ) && ( count( $pair ) === 2 ) && ( ! empty( $pair[ 0 ] ) ) && isset( $_GET[ $pair[ 0 ] ] ) && ( $pair[ 1 ] == $_GET[ $pair[ 0 ] ] ) ) {
							return false;
						}
					}
				}
				return $status;
			}
		}
		/**
		* Test if currently in page builder edit mode
		*    1. For Elementor only
		* @param boolean
		* @return boolean
		*/
		public function detect_page_builder( $status ) {
			if ( ( isset( $_GET['elementor-preview'] ) && ! empty( sanitize_text_field( wp_unslash( $_GET['elementor-preview'] ) ) ) ) && defined( 'ELEMENTOR_PATH' ) ) {
				return false;
			} else if ( isset( $_GET['fl_builder'] ) && class_exists( 'FLBuilderLoader' ) ) {
				return false;
			} else if ( ! empty( $_GET['vc_editable'] ) && defined( 'WPB_VC_VERSION' ) ) {
				return false;
			} else if ( is_customize_preview() && ( empty( $_GET['plugin'] ) || ( 'loftloader' != sanitize_text_field( wp_unslash( $_GET['plugin'] ) ) ) ) ) {
				return false;
			}
			return $status;
		}
		/**
		* Initialize loader front
		*/
		protected function init_front() {
			do_action( 'loftloader_pro_init_front', $this->is_mobile );
		}
		public function enqueue_styles() {
			require_once LOFTLOADERPRO_INC . 'frontend/class-google-font.php';
			// Enqueue the main loader style file
			wp_enqueue_style( 'loftloader-style', LOFTLOADERPRO_ASSETS_URI . 'css/loftloader.min.css', array(), LOFTLOADERPRO_ASSET_VERSION );
		}
		/**
		 * Register scripts for frontend
		 */
		public function register_scripts() {
			$asset_uri = LOFTLOADERPRO_ASSETS_URI;
			$asset_version = LOFTLOADERPRO_ASSET_VERSION;
			$waitformedia_deps = array( 'jquery' );
			$elements = llp_get_loader_setting( 'loftloader_pro_detect_elements' );
			$detectAutoplayVideo = llp_module_enabled( 'loftloader_pro_detect_autoplay_video' ) && ! $this->is_mobile;

			// Register the require jquery plugin
			wp_register_script( 'jquery-waitformedias', $asset_uri . 'js/jquery.waitformedias.min.js', $waitformedia_deps, $asset_version, true );
			wp_localize_script(
				'jquery-waitformedias',
				'loftloaderProWaitForMediaSettings',
				array(
					'detectElement' => esc_js( $elements ),
					'detectAutoplayVideo' => $detectAutoplayVideo
				)
			);
			// Enqueue the main loader javascript
			wp_register_script( 'loftloader-front-main', $asset_uri . 'js/loftloader.min.js', array( 'jquery-waitformedias' ), $asset_version, true );
			wp_localize_script( 'loftloader-front-main', 'loftloaderPro', $this->get_front_script_json() );
		}
		/**
		* Print scripts for frontend ASAP
		**/
		public function enqueue_scripts_early() {
			$this->is_scripts_loaded = true;
			$this->print_loader_scripts();
		}
		/**
		* Print scripts for frontend normally
		**/
		public function enqueue_scripts_normal() {
			if ( ( 'normal' == $this->scripts_loading_priority ) || ( ( 'high' == $this->scripts_loading_priority ) && ( ! $this->is_scripts_loaded ) ) ) {
				$this->print_loader_scripts();
			}
		}
		/**
		* Print scripts for frontend late
		**/
		public function enqueue_scripts_late() {
			$this->print_loader_scripts();
		}
		/**
		* Print loader scripts
		*/
		public function print_loader_scripts() {
			wp_print_scripts( array( 'loftloader-front-main' ) );
		}
		/**
		* Test whether show loftloader
		* @return boolean return true if loftloader enabled and display on current page, otherwise false
		*/
		public function is_loader_enabled() {
			if ( ( 'on' === esc_attr( llp_get_loader_setting( 'loftloader_pro_main_switch') ) ) && $this->device_test() ) {
				$this->check_session();
				$queried = get_queried_object();
				$range = llp_get_loader_setting( 'loftloader_pro_show_range' );
				switch ( $range ) {
					// Home only + once per session
					case 'homepage-once':
						return is_front_page();
						break;
					case 'once':
						return true;
						break;
					case 'post_types': // Sitewide - selected post types
						$types = ( array )llp_get_loader_setting( 'loftloader_pro_post_types' );
						return empty( $types ) || ( ! $this->check_post_types( $types ) ); // ! is_singular( $types );
						break;
					case 'selected_post_types': // Selected post types
						$types = (array) llp_get_loader_setting( 'loftloader_pro_selected_post_types' );
						return ( ! empty( $types ) ) && $this->check_post_types( $types ) ; // is_singular( $types );
						break;
					case 'sitewide':
						$exclued_pages = llp_get_loader_setting( 'loftloader_pro_site_wide_exclude_pages' );
						return empty( $exclued_pages ) || ( ! $this->check_pages( $exclued_pages ) );
						break;
					case 'homepage':
						return is_front_page();
						break;
					case 'all':
						if ( llp_is_woocommerce_shop() || is_home() || ( $this->is_singles( $queried ) && ( $queried->post_type === 'page' ) ) ) {
							$exclued_pages = llp_get_loader_setting( 'loftloader_pro_all_pages_exclude_pages' );
							return empty( $exclued_pages ) || ( ! $this->check_pages( $exclued_pages ) );
						}
						break;
					case 'handpick': // Handpick
						$pages = llp_get_loader_setting( 'loftloader_pro_hand_pick_pages' );
						return ( ! empty( $pages ) ) && $this->check_pages( $pages );
				}
			}
			return false;
		}
		/**
		* Set cookies if once per session enabled
		*/
		protected function check_session() {
			if ( ! is_customize_preview() ) {
				$range = llp_get_loader_setting( 'loftloader_pro_show_range', true );
				if ( in_array( $range, array( 'homepage-once', 'once' ) ) ) {
					if ( ( 'once' === $range ) || ( ( 'homepage-once' === $range ) && is_front_page() ) ) {
						$this->enabled_session = true;
					}
				} else if ( in_array( $range, array( 'all', 'post_types', 'selected_post_types', 'handpick' ) ) ) {
					$this->enabled_session = llp_module_enabled( 'loftloader_pro_enable_once_per_session' );
				}
			}
		}
		/**
		* Test current device and check whether to show loftloader
		* @return boolean
		*/
		protected function device_test() {
			// Always return true when in customizer page.
			if ( is_customize_preview() ) {
				return true;
			}

			$device = llp_get_loader_setting( 'loftloader_pro_device' );
			switch ( $device ) {
				case 'all':
					return true;
				case 'notmobile':
					return ! $this->is_mobile;
				case 'mobileonly':
					return $this->is_mobile;
				defaults:
					return false;
			}
		}
		/**
		* Test if current request is for single page not archive page
		* @param mix
		* @return boolean
		*/
		protected function is_singles( $query ) {
			return is_object( $query ) && ( 'WP_Post' === get_class( $query ) );
		}
		/**
		* Is mobile device only
		*/
		public function is_mobile_only( $is ) {
			return $this->is_device_mobile;
		}
		/**
		* If in once per session
		*/
		public function is_session_enabled( $enabled ) {
			return $this->enabled_session;
		}
		/**
		* Check pages if currently accessed
		*/
		protected function check_pages( $pages ) {
			if ( empty( $pages ) || ( ! is_array( $pages ) ) ) {
				return true;
			}
			$queried = get_queried_object();
			return ( llp_is_woocommerce_shop() && in_array( wc_get_page_id( 'shop' ), $pages ) ) || ( $this->is_singles( $queried ) && in_array( $queried->ID, $pages ) );
		}
		/**
		* Check post types
		*/
		protected function check_post_types( $types ) {
			if ( in_array( 'page', $types ) ) {
				$queried = get_queried_object();
				if ( llp_is_woocommerce_shop() || is_home() || ( $this->is_singles( $queried ) && ( $queried->post_type === 'page' ) ) ) {
					return true;
				}
			}
			return is_singular( $types );
		}
		/**
		* JavaScript variables
		*/
		protected function get_front_script_json() {
			$list = llp_get_random_message_list();
			$display_option = apply_filters( 'loftloader_pro_smooth_page_transition_display_option', false );
			$json = array(
				'leavingProgressMax' => intval( llp_get_loader_setting( 'loftloader_pro_insite_transition_initial_percentage' ) ),
				'leavingTimer' => intval( llp_get_loader_setting( 'loftloader_pro_insite_transition_initial_timer' ) ),
				'insiteTransitionShowAll' => llp_module_enabled( 'loftloader_pro_insite_transition_show_all' ),
				'insiteTransitionShowCloseButton' => llp_module_enabled( 'loftloader_pro_insite_transition_show_close_button' ),
				'insiteTransitionURLExcluded' => $this->get_excluded_page_urls(),
				'insiteTransitionDisplayOption' => esc_js( $display_option ),
				'insiteTransitionDisplayOnCurrent' => ( 'current-page' == $display_option ),
				'insiteTransitionButtons' => llp_get_loader_setting( 'loftloader_pro_insite_transition_buttons' )
			);
			if ( llp_module_enabled( 'loftloader_pro_enable_random_message_text' ) && llp_module_enabled( 'loftloader_pro_render_random_message_by_js' ) && ! empty( $list ) ) {
				$json[ 'randomMessage' ] = $list;
			}
			return apply_filters( 'loftloader_pro_front_json', $json );
		}
		/**
		* Exclude page urls
		*/
		protected function get_excluded_page_urls() {
			$range = llp_get_loader_setting( 'loftloader_pro_show_range' );
			$urls = array( admin_url(), wp_login_url() );
			if ( in_array( $range, array( 'sitewide', 'all' ) ) ) {
				$pages = llp_get_loader_setting( ( ( 'all' == $range ) ? 'loftloader_pro_all_pages_exclude_pages' : 'loftloader_pro_site_wide_exclude_pages' ) );
				if ( is_array( $pages ) && ( ! empty( $pages ) ) ) {
					return array_merge( $urls, array_map( function( $page ) {
						return get_permalink( $page );
					}, $pages ) );
				}
			}
			return $urls;
		}
		/**
		* Exclude JavaScript
		*/
		public function exclude_script_files( $exclude = array() ) {
			$exclude[] = '/wp-content/plugins/loftloader-pro/assets/js/loftloader.min.js';
			$exclude[] = '/wp-content/plugins/loftloader-pro/assets/js/jquery.waitformedias.min.js';
			$exclude[] = '/(.*)/loftloader.min.js';
			$exclude[] = '/(.*)/jquery.waitformedias.min.js';
			$exclude[] = '/loftloader.min.js';
			$exclude[] = '/jquery.waitformedias.min.js';

			return $exclude;

		}
		/**
		* Exclude style files
		*/
		public function exclude_style_files( $exclude = array() ) {
			$exclude[] = '/wp-content/plugins/loftloader-pro/assets/css/loftloader.min.css';
			$exclude[] = '/wp-content/uploads/loftloader-pro/custom-styles.css';
			$exclude[] = '/(.*)/loftloader.min.css';
			$exclude[] = '/loftloader.min.css';

			return $exclude;
		}
		/**
		* If smooth page transition enabled
		*/
		public function smooth_page_transition_enabled( $enabled ) {
			$onces = array( 'once', 'homepage-once' );
			return llp_module_enabled( 'loftloader_pro_insite_transition' ) && ! in_array( llp_get_loader_setting( 'loftloader_pro_show_range'), $onces );
		}
		/**
		* Smooth page transition display option
		*/
		public function spt_display_option( $option ) {
			if ( apply_filters( 'loftloader_pro_smooth_page_transition_enabled', false ) && ! is_customize_preview() ) {
				return llp_get_loader_setting( 'loftloader_pro_insite_transition_display' );
			}
			return false;
		}
		/**
		* Keep once per session for loftocean disabled pages
		*/
		public function keep_once_per_session() {
			require_once LOFTLOADERPRO_ROOT . 'assets/js/loftloader-keep-once-per-session.php';
		}
	}
	new LoftLoader_Pro_Frontend_Manager();
}
