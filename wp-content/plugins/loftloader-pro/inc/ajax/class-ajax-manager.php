<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 0 );
}

if ( ! class_exists( 'LoftLoader_Pro_AJAX_Manager' ) ) {
	class LoftLoader_Pro_AJAX_Manager {
		public function __construct() {
            $action = ( isset( $_REQUEST['action'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) : '';
            if ( ! empty( $action ) ) {
                switch ( $action ) {
                    case 'loftloader_pro_query_posts':
                        $this->load_action_query_posts();
                        break;
                }
            }
		}
		/**
		* Load configuration files
		*/
		protected function load_action_query_posts() {
			require_once LOFTLOADERPRO_INC . 'ajax/configs/handpick-posts.php';
		}
	}
	new LoftLoader_Pro_AJAX_Manager();
}
