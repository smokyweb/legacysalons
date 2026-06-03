<?php
if ( ! class_exists( 'LoftLoaderPro_Any_Page_Filter' ) ) {
	class LoftLoaderPro_Any_Page_Filter {
		private $defaults = array();
		private $page_settings = array();
		private $post_types = array();
		private $page_enabled = false;
		private $page_id = false;
		private $is_customize = false;
		private $is_show_once = false;
		public function __construct() {
			$this->post_types = llp_get_loader_setting( 'loftloader_pro_any_page_post_types' );
			add_filter( 'loftloader_pro_loader_enabled', 		array( $this, 'loader_enabled' ), 100 );
			add_filter( 'loftloader_pro_get_loader_setting', 	array( $this, 'get_loader_setting' ), 10, 2 );
			add_filter( 'loftloader_pro_page_show_once', 		array( $this, 'is_page_show_once') );
			add_action( 'loftloader_pro_pre_init', 				array( $this, 'loader_settings' ) );
		}
		/**
		* @description get the plugin settings
		*/
		public function loader_settings() {
			global $wp_customize, $llp_defaults;
			$this->is_customize = isset( $wp_customize ) ? true : false;
			if ( $this->is_any_page_extension_enabled() ) {
				$page = $this->get_queried_object();
				if ( ( false !== ( $atts = $this->get_loader_attributes( $page->ID ) ) ) ) {
					$this->page_id = $page->ID;
					add_filter( 'loftloader_pro_front_json', array( $this, 'loader_attributes' ), 99 );
					add_filter( 'loftloader_pro_custom_styles_in_file', array( $this, 'custom_styles_in_file' ) );
					if ( isset( $atts['loftloader_pro_message_text'] ) ) {
						$atts['loftloader_pro_message_text'] = base64_decode( $atts['loftloader_pro_message_text'] );
					}
					if ( isset( $atts['loftloader_pro_random_message_text'] ) ) {
						$atts['loftloader_pro_random_message_text'] = base64_decode( $atts['loftloader_pro_random_message_text'] );
					}
					if ( isset( $atts['loftloader_pro_show_close_tip'] ) ) {
						$atts['loftloader_pro_show_close_tip'] = base64_decode( $atts['loftloader_pro_show_close_tip'] );
					}
					if ( isset( $atts['loftloader_pro_custom_css'] ) ) {
						$atts['loftloader_pro_custom_css'] = base64_decode( $atts['loftloader_pro_custom_css'] );
					}
					if ( isset( $atts['loftloader_pro_custom_loader'] ) ) {
						$atts['loftloader_pro_custom_loader'] = base64_decode( $atts['loftloader_pro_custom_loader'] );
					}
					if ( isset( $atts[ 'loftloader_percentage_margin' ] ) ) {
						$atts[ 'loftloader_percentage_margin' ] = explode( ',', $atts[ 'loftloader_percentage_margin' ] );
					}
					$this->page_settings = array_merge( $llp_defaults, $atts );
					$this->page_enabled = ( 'on' === $atts['loftloader_pro_main_switch'] );
				}
			}
		}
		/**
		* @description helper function to get shortcode attributes
		*/
		private function get_loader_attributes( $page_id ) {
			$loader = get_post_meta( $page_id, 'loftloader_pro_page_shortcode', true );
			$loader = trim( $loader );
			if ( ! empty( $loader ) ) {
				$loader = substr( $loader, 1, -1 );
				return shortcode_parse_atts( $loader );
			}
			return false;
		}
		/**
		* Helper function to test whether show loftloader
		* @return boolean return true if loftloader enabled and display on current page, otherwise false
		*/
		public function loader_enabled( $enabled ) {
			if ( ! $this->is_customize && $this->page_id ) {
				$cookie_name = 'loftloader_pro_any_page_id_' . $this->page_id;
				if ( $this->page_enabled ) {
					$once = get_post_meta( $this->page_id, 'loftloader_pro_show_once', true );
					if ( 'on' === $once ) {
						$this->is_show_once = true;
						if ( ! empty( $_COOKIE[ $cookie_name ] ) && ( 'on' === sanitize_text_field( wp_unslash( $_COOKIE[ $cookie_name ] ) ) ) ) {
							return false;
						} else {
							llp_set_cookie( $cookie_name, 'on' );
							return true;
						}
					}
				} else {
					return false;
				}
			}
			return $enabled;
		}
		/**
		* If current page set show once
		*/
		public function is_page_show_once( $once ) {
			return $this->is_show_once;
		}
		/**
		* Change custom style to html inline
		*/
		public function custom_styles_in_file( $in_file ) {
			return is_customize_preview() ? $in_file : false;
		}
		/**
		* Helper function get setting option
		*/
		public function get_loader_setting( $setting_value, $setting_id ) {
			return ( $this->page_enabled && ! $this->is_customize && isset( $this->page_settings[ $setting_id ] ) ) ?
				$this->page_settings[$setting_id] :
				$setting_value;
		}
		/**
		* Help function to test if any page extension enabled on current page
		*/
		protected function is_any_page_extension_enabled() {
			if ( in_array( 'page', $this->post_types ) && ( is_front_page() || is_home() ) && ( 'page' === get_option( 'show_on_front', false ) ) ) {
				return true;
			} else if ( in_array( 'page', $this->post_types ) && llp_is_woocommerce_shop() ) {
				return true;
			} else if ( is_singular( $this->post_types ) ) {
				return true;
			}
			return false;
		}
		/**
		* Loader wrapper attributes
		*/
		public function loader_attributes( $json ) {
			$json['AnyPageExtensionEnabled'] = 'on';
			return $json;
		}
		/**
		* Get queried page object
		*/
		protected function get_queried_object() {
			if ( llp_is_woocommerce_shop() ) {
				$page_id = wc_get_page_id( 'shop' );
				return get_page( $page_id );
			} else {
				return get_queried_object();
			}
		}
	}
}
