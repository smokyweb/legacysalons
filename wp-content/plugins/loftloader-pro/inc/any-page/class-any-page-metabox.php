<?php
/**
* WP metabox for any page if gutenberg is not enabled
*/
if ( ! class_exists( 'LoftLoaderPro_Any_Page_MetaBox' ) ) {
	class LoftLoaderPro_Any_Page_MetaBox {
		/**
		* Array post types supported
		*/
		public $post_types = array();
		/**
		* construct function
		*/
		public function __construct() {
			$this->post_types = llp_get_loader_setting( 'loftloader_pro_any_page_post_types' );
			add_action( 'add_meta_boxes', 	array( $this, 'register_meta_boxes' ) );
			add_action( 'save_post', 		array( $this, 'save_meta' ), 10, 3 );
		}
		/*
		* Register loftloader shortcode meta box
		*/
		public function register_meta_boxes() {
			foreach( $this->post_types as $type ) {
				add_meta_box( 
					'loftloader_pro_any_page_meta', 
					esc_html__( 'LoftLoader Pro Any Page Shortcode', 'loftloader-pro' ), 
					array( $this, 'metabox_callback' ), 
					$type, //'page', 
					'advanced',
					'high',
					array(
						'__block_editor_compatible_meta_box' => true,
						'__back_compat_meta_box' => true
					)
				);
			}
		}
		/*
		* Show meta box html
		*/
		public function metabox_callback( $post ) {
			$shortcode = get_post_meta( $post->ID, 'loftloader_pro_page_shortcode', true );
			$show_once = get_post_meta( $post->ID, 'loftloader_pro_show_once', true ); ?>

			<p>
				<input type="checkbox" name="loftloader_pro_show_once" id="loftloader-pro-show-once" value="once" <?php checked( $show_once, 'on' ); ?> />
				<label for="loftloader-pro-show-once"><?php esc_html_e( 'Display the preloader on the page only once during a visitor session', 'loftloader-pro' ); ?></label>
			</p>
			<textarea name="loftloader_pro_page_shortcode" style="width: 100%;" rows="4"><?php echo esc_textarea( str_replace( '/\\"/g', '\\\\"', $shortcode ) ); ?></textarea>
			<input type="hidden" name="loftloader_pro_any_page_nonce" value="<?php echo esc_attr( wp_create_nonce( 'loftloader_pro_any_page_nonce' ) ); ?>" /> <?php
		}
		/*
		* Save loftloader shortcode meta
		*/
		public function save_meta( $post_id, $post, $update ) {
			if ( empty( $update ) 
				|| ! in_array( $post->post_type, $this->post_types ) 
				|| empty( $_REQUEST['loftloader_pro_any_page_nonce'] ) 
				|| ! empty( $_REQUEST['loftloader_pro_gutenberg_enabled'] ) ) {
				return '';
			} 
			if ( current_user_can( 'edit_post', $post_id ) ) {
				$shortcode = '';
				$show_once = isset( $_REQUEST['loftloader_pro_show_once'] ) ? 'on' : '';
				if ( ! empty( $_REQUEST['loftloader_pro_page_shortcode'] ) ) {
					$shortcode = sanitize_text_field( wp_unslash( $_REQUEST['loftloader_pro_page_shortcode'] ) );
				}
				update_post_meta( $post_id, 'loftloader_pro_page_shortcode', $shortcode );
				update_post_meta( $post_id, 'loftloader_pro_show_once', $show_once );
			}
			return $post_id;
		}
	}
	new LoftLoaderPro_Any_Page_MetaBox();
}