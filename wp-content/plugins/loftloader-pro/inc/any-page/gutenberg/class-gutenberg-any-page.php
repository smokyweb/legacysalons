<?php
/**
* Gutenberg Manager class
*/

if ( ! class_exists( 'LoftLoaderPro_Gutenberg_Any_Page' ) ) {
	class LoftLoaderPro_Gutenberg_Any_Page {
		/**
		* Array of post meta name list
		*/
		public $page_meta_list = array(
			'loftloader_pro_show_once' => 'string',
			'loftloader_pro_page_shortcode' => 'string'
		);
		/**
		* Array post types supported
		*/
		public $post_types = array();
		/**
		* Construct function
		*/
		public function __construct() {
			$this->post_types = llp_get_loader_setting( 'loftloader_pro_any_page_post_types' );
			add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_assets' ) );
			$this->register_metas();
		}
		/**
		* Enqueue editor assets
		*/
		public function enqueue_editor_assets() {
			if ( in_array( $this->get_current_post_type(), $this->post_types ) ) {
				wp_enqueue_script(
					'loftloader-pro-gutenberg-any-page-script',
					LOFTLOADERPRO_URI. 'inc/any-page/gutenberg/plugin.js',
					array( 'wp-blocks', 'wp-element', 'wp-i18n' ),
					LOFTLOADERPRO_ASSET_VERSION,
					true
				);
			}
		}
		/**
		* Register metas for gutenberg
		*/
		public function register_metas() {
			foreach( $this->page_meta_list as $id => $type ) {
				register_meta( 'post', $id, array(
					'auth_callback' => array( $this, 'permission_check' ),
					'show_in_rest' 	=> true,
					'single' 		=> true,
					'type' 			=> $type
				) );
			}
		}
		/**
		* Check permission for meta registration
		*/
		public function permission_check( $arg1, $meta_name, $post_id ) {
			return current_user_can( 'edit_post', $post_id );
		}
		/**
		* Get current post type
		* @return mix post type string or boolean false
		*/
		protected function get_current_post_type() {
			global $post;
			if ( is_admin() && ! empty( $post ) && ! empty( $post->post_type ) ) {
				return $post->post_type;
			} else {
				return false;
			}
		}
	}
	function llp_init_gutenberg_any_page() {
		new LoftLoaderPro_Gutenberg_Any_Page();
	}
	add_action( 'init', 'llp_init_gutenberg_any_page' );
}
