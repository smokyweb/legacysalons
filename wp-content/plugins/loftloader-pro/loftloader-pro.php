<?php
/*
Plugin Name: LoftLoader Pro
Plugin URI: http://www.loftocean.com/
Description: An easy to use plugin to add an animated preloader to your website with fully customisations.
Version: 2.5
Requires at least: 5.0
Tested up to: 6.1
Requires PHP: 7.4
Author: Loft.Ocean
Author URI: http://www.loftocean.com/
Text Domain: loftloader-pro
Domain Path: /languages
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

// Not allowed by directly accessing.
if ( ! defined( 'ABSPATH' ) ) {
	die( 0 );
}


// Let's roll
if ( ! class_exists( 'LoftLoader_Pro' ) ) {
	/**
	 * LoftLoader Pro main class
	 *
	 * @package   LoftLoader Pro
	 * @link	  http://www.loftocean.com/
	 * @author	  Loft.Ocean Team
	 */
	class LoftLoader_Pro {
		/**
		* String minimal PHP version required
		*/
		protected $minimal_php_version = '5.3';
		/**
		* Strig minimal WP version required
		*/
		protected $minimal_wp_version = '5.0';
		/**
		* Construct function
		*/
		public function __construct() {
			$this->define_constants();
			$this->load_textdomain();
			// Challenge the current PHP version, if so stop working and print the admin notice
			if ( version_compare( $GLOBALS['wp_version'], $this->minimal_wp_version, '<' ) || version_compare( phpversion(), $this->minimal_php_version, '<' ) ) {
				require_once LOFTLOADERPRO_INC . 'class-back-compat.php';
				new LoftLoader_Pro_Back_Compat( $this->minimal_php_version, $this->minimal_wp_version );
			} else {
				$this->includes();
				add_action( 'after_setup_theme', array( $this, 'after_theme_setup' ) );
				add_action( 'plugins_loaded', array( $this, 'load_any_page' ) );
				add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

				add_filter( 'customize_loaded_components', array( $this, 'remove_widget_panels' ), 1000 );
				add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'add_customize_links' ) );
			}
		}
		/**
		* Define global constants
		*/
		private function define_constants() {
			$this->define( 'LOFTLOADERPRO_URI', 			plugin_dir_url( __FILE__ ) );
			$this->define( 'LOFTLOADERPRO_ASSETS_URI', 		plugin_dir_url( __FILE__ ) . 'assets/' ); // Define asset base uri
			$this->define( 'LOFTLOADERPRO_OPTION', 			'loftloader_pro_options' ); // Define loader option name
			$this->define( 'LOFTLOADERPRO_ROOT',			dirname( __FILE__ ) . '/' );
			$this->define( 'LOFTLOADERPRO_INC', 			LOFTLOADERPRO_ROOT . 'inc/' ); // Define loader root directory
			$this->define( 'LOFTLOADERPRO_ASSET_VERSION', 	'2024090401' ); // Define assets version
			$this->define( 'LOFTLOADERPRO_VERSION',			'2.5' ); // Plugin version
		}
		/**
		* Load plugin text domain
		*/
		public function load_textdomain() {
			load_plugin_textdomain( 'loftloader-pro' );
		}
		/**
		* Load required files
		*/
		private function includes() {
			$inc = LOFTLOADERPRO_INC;
			// Load gloal default settings
			require_once $inc . 'default-settings.php';
			// Load global functions
			require_once $inc . 'functions.php';
		}
		/**
		* Load after theme setup
		*/
		public function after_theme_setup() {
			$inc = LOFTLOADERPRO_INC;
			// Load upgrade
			require_once $inc . 'class-upgrader.php';
			// Load GDPR suggest message
			require_once $inc . 'class-privacy.php';
			// Load frontend render class
			require_once $inc . 'frontend/class-manager.php';
			// Load customization related features
			if ( $this->is_customize_loader() && $this->is_customize_enabled() ) {
				require_once $inc . 'customize/class-customize-custom.php';
				require_once $inc . 'customize/class-customize-manager.php';
			}
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				require_once $inc . 'ajax/class-ajax-manager.php';
			}
		}
		/**
		* Load any page related feature
		*/
		public function load_any_page() {
			if ( $this->is_any_page_enabled() ) {
				require_once LOFTLOADERPRO_INC . '/any-page/class-any-page.php';
			}
		}
		/**
		* Add admin menu for loftloader pro in setting panel
		*/
		public function add_admin_menu() {
			global $submenu;
			$submenu['options-general.php'][] = array(
				esc_html__( 'LoftLoader Pro', 'loftloader-pro' ),
				'manage_options',
				llp_get_plugin_customize_link()
			);
		}
		/**
		* Add customizaion link for loftloader pro in plugin list page
		*/
		public function add_customize_links( $links ) {
			$action_links = array(
				'settings' => sprintf(
					'<a href="%s" title="%s">%s</a>',
					llp_get_plugin_customize_link(),
					esc_attr__( 'Customize LoftLoader Pro', 'loftloader-pro' ),
					esc_html__( 'Customize', 'loftloader-pro' )
				)
			);
			return array_merge( $action_links, $links );
		}
		/*
		* Remove widget panels
		*/
		public function remove_widget_panels( $components ) {
			if ( ! empty( $_GET['plugin'] ) && ( 'loftloader' === sanitize_text_field( wp_unslash( $_GET['plugin'] ) ) ) ) {
				foreach ( $components as $i => $c ) {
					if ( false !== $i ) {
						unset( $components[$i] );
					}
				}
			}
			return $components;
		}
		/**
		* Help function to test if need load the customize related files
		* @return boolean
		*/
		protected function is_customize_enabled() {
			return class_exists( 'WP_Customize_Setting' ) && class_exists( 'WP_Customize_Section' ) && class_exists( 'WP_Customize_Control' );
		}
		/**
		* Helper function to define constant with given name and value,
		*	if it is not defined yet
		*/
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}
		/**
		* Helper function to determine if currently is in loftloader pro customization page
		* @return boolean
		*/
		private function is_customize_loader() {
			return ( ! empty( $_GET['plugin'] ) && ( 'loftloader' === sanitize_text_field( wp_unslash( $_GET['plugin'] ) ) ) ) || defined( 'DOING_AJAX' );
		}
		/**
		* Help function to test any page extension enabled
		* @return boolean
		*/
		protected function is_any_page_enabled() {
			$post_types = llp_get_loader_setting( 'loftloader_pro_any_page_post_types' );
			return llp_is_module_enabled( 'loftloader_pro_enable_any_page' ) && is_array( $post_types ) && ! empty( $post_types );
		}
	}
	new LoftLoader_Pro();
}
