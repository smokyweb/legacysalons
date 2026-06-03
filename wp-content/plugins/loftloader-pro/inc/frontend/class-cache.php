<?php
if ( ! class_exists( 'LoftLoader_Pro_Cache' ) ) {
    class LoftLoader_Pro_Cache {
        /**
        * If head loaded
        */
        protected $site_header_loaded = false;
        /**
        * If footer loaded
        */
        protected $site_footer_loaded = false;
        /**
        * Construct function
        */
        public function __construct() {
            add_action( 'loftloader_pro_init_front', array( $this, 'init_front' ) );
            llp_module_enabled( 'loftloader_pro_inject_html_in_action_init' )
				? add_action( 'init', array( $this, 'start_cache' ), -99 ) :  add_action( 'template_redirect', array( $this, 'start_cache' ), 2 );
        }
		/**
		* Start cache for outputing
		*/
		public function start_cache() {
			// Only for front view
			if ( ! is_admin() && ! wp_doing_ajax() ) {
				// Start cache the output with callback function
				ob_start( array( $this, 'modify_html' ) );
			}
		}
		/**
		* Will be called when flush cache
		*
		* @param string cached string
		* @return string modified cached string
		*/
		public function modify_html( $html ) {
			if ( $this->site_header_loaded && $this->site_footer_loaded ) {
				return apply_filters( 'loftloader_pro_modify_html', $html );
			} else {
				return $html;
			}
		}
        /**
        * Front hooks
        */
        public function init_front() {
            add_action( 'wp_head', array( $this, 'set_site_header_loaded' ), 99 );
            add_action( 'wp_footer', array( $this, 'set_site_footer_loaded' ), 99 );
        }
		/**
		* Set site header loaded to true
		*/
		public function set_site_header_loaded() {
			$this->site_header_loaded = true;
		}
		/**
		* Set site footer loaded to true
		*/
		public function set_site_footer_loaded() {
			$this->site_footer_loaded = true;
		}
    }
    new LoftLoader_Pro_Cache();
}
