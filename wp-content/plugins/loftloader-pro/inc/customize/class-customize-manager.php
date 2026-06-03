<?php
/**
 * LoftLoader related customization api classes
 *
 * @package   LoftLoader Pro
 * @link	  http://www.loftocean.com/
 * @author	  Suihai Huang from Loft.Ocean Team
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 0 );
}

if ( ! class_exists( 'LoftLoader_Pro_Customize_Manager' ) ) {
	class LoftLoader_Pro_Customize_Manager {
		public function __construct() {
			$this->includes();

			add_action( 'customize_controls_init', 					array( $this, 'remove_sections' ), 1000 );
			add_action( 'customize_controls_enqueue_scripts', 		array( $this, 'add_customize_scripts'), 1 );
			add_action( 'customize_preview_init', 					array( $this, 'add_preview_script' ) );

			// Remove scripts and styles
			add_action( 'customize_controls_enqueue_scripts', 		array( $this, 'remove_customize_assets' ), 100000 );
			add_action( 'customize_controls_print_styles', 			array( $this, 'remove_customize_assets' ) );
			add_action( 'customize_controls_print_scripts', 		array( $this, 'remove_customize_assets' ), 100000 );
			add_action( 'customize_controls_print_footer_scripts', 	array( $this, 'remove_customize_assets' ), 100000 );

			add_filter( 'loftloader_pro_front_json',				array( $this, 'front_json' ) );

			do_action( 'loftloader_pro_customizer' );
		}
		/**
		* Load configuration files
		*/
		private function includes() {
			$config_dir = LOFTLOADERPRO_INC . 'customize/configs/';

			require_once $config_dir . 'google-fonts.php';
			require_once $config_dir . 'main.php';
			require_once $config_dir . 'range.php';
			require_once $config_dir . 'background.php';
			require_once $config_dir . 'loader.php';
			require_once $config_dir . 'progress.php';
			require_once $config_dir . 'message.php';
			require_once $config_dir . 'spt.php';
			require_once $config_dir . 'more.php';
			require_once $config_dir . 'advanced.php';
		}
		/**
		* Enqueue scripts for customize.php page
		*/
		public function add_customize_scripts() {
			$asset_uri = LOFTLOADERPRO_ASSETS_URI;
			$asset_ver = LOFTLOADERPRO_ASSET_VERSION;
			$customize_js_dep = array( 'jquery', 'wp-color-picker', 'jquery-ui-slider', 'customize-controls', 'loftloader-shortcode-generator', 'media-editor' );

			wp_enqueue_script( 'loftloader-shortcode-generator', $asset_uri . 'js/shortcode-generator.min.js', array( 'jquery' ), $asset_ver );
			wp_register_script( 'loftloader-customize', $asset_uri . 'js/customize.min.js', $customize_js_dep, $asset_ver );
			wp_localize_script( 'loftloader-customize', 'loftloaderProCustomize', array(
				'ajax' => array(
					'action' => 'loftloader_pro_query_posts',
					'url' => admin_url( 'admin-ajax.php' )
				),
				'i18nText' => array(
					'pluginName' => esc_html__( 'LoftLoader Pro', 'loftloader-pro' ),
					'nan' => esc_html__( 'Please enter a number.', 'loftloader-pro' ),
					'nain' => esc_html__( 'Please enter a positive number.', 'loftloader-pro' ),
					// translators: %d number value from realtime
					'minTooLarge' => esc_html__( 'Please select a value that is less than the Maximum Load Time (%d seconds).', 'loftloader-pro' ),
					// translators: %d number value from realtime
					'maxTooSmall' => esc_html__( 'Please enter a value greater than the Minimum Load Time (%d seconds).' , 'loftloader-pro' )
				)
			) );
			wp_enqueue_script( 'loftloader-customize' );

			wp_enqueue_style( 'loftloader-ui', $asset_uri . 'css/jquery-ui.css', array(), $asset_ver );
			wp_enqueue_style( 'loftloader-customize', $asset_uri . 'css/loftloader-settings.min.css', array(), $asset_ver );
		}
		/**
		* Remove scripts and styles from theme currently used to avoid layout issues
		*/
		public function remove_customize_assets() {
			global $wp_scripts, $wp_styles;
			foreach ( $wp_scripts->registered as $h => $o ) {
				if ( false !== strpos( $o->src, 'wp-content/themes' ) ) {
					wp_dequeue_script( $h );
				}
				if ( false !== strpos( $o->src, 'wp-content/plugins/disable-blog/' ) ) {
					wp_dequeue_script( $h );
				}
			};
			foreach ( $wp_styles->registered as $h => $o ) {
				if ( false !== strpos( $o->src, 'wp-content/themes' ) ) {
					wp_dequeue_style( $h );
				}
			};
		}
		/**
		* Remove assets from themes for customize preview
		*/
		public function remove_preview_assets() {
			global $wp_scripts, $wp_styles;
			foreach ( $wp_scripts->registered as $h => $o ) {
				$src = strtolower( $o->src );
				if ( ( false !== strpos( $src, 'wp-content/themes/' ) ) && ( false !== strpos( $src, 'preview' ) ) ) {
					wp_dequeue_script( $h );
				}
			};
			foreach ( $wp_styles->registered as $h => $o ) {
				$src = strtolower( $o->src );
				if ( ( false !== strpos( $src, 'wp-content/themes/' ) ) && ( false !== strpos( $src, 'preview' ) ) ) {
					wp_dequeue_style( $h );
				}
			};
		}
		/**
		* Enqueue scripts for customize preview
		*/
		public function add_preview_script() {
			$asset_uri = LOFTLOADERPRO_ASSETS_URI;
			$asset_ver = LOFTLOADERPRO_ASSET_VERSION;

			wp_enqueue_script( 'loftloader-preview', $asset_uri . 'js/preview.min.js', array( 'jquery', 'customize-preview' ), $asset_ver, true );
			wp_enqueue_style( 'loftloader-preview-style', $asset_uri . 'css/loftloader-preview.min.css', array( 'loftloader-style' ), $asset_ver );

			add_action( 'wp_enqueue_scripts', array( $this, 'remove_preview_assets' ), 100000 );
		}
		/**
		* Remove sectioin except loftloader
		*/
		public function remove_sections() {
			global $wp_customize;
			// Remove none loftloader pro top containers
			foreach ( $wp_customize->containers() as $id => $container ) {
				if ( $container instanceof WP_Customize_Panel ) {
					( false === strpos($id, 'loftloader_pro_') ) ? $wp_customize->remove_panel( $id ) : '';
				} else if ( $container instanceof WP_Customize_Section ) {
					( false === strpos($id, 'loftloader_pro_') ) ? $wp_customize->remove_section( $id ) : '';
				}
			}
			// Remove none loftloader pro controls
			foreach ( $wp_customize->controls() as $id => $control ) {
				( false === strpos($id, 'loftloader_') ) ? $wp_customize->remove_control( $id ) : '';
			}
			// Remove none loftloader pro settings
			foreach ( $wp_customize->settings() as $id => $setting ) {
				( false === strpos($id, 'loftloader_') ) ? $wp_customize->remove_setting( $id ) : '';
			}
		}
		/**
		* Front JSON settings
		*/
		public function front_json( $json ) {
			$json['isLoaderPreview'] = 'on';
			return $json;
		}
	}
	new LoftLoader_Pro_Customize_Manager();
}
