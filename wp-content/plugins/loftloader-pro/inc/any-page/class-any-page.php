<?php
// Parse loader any page extension
if ( ! class_exists( 'LoftLoaderPro_Any_Page' ) ) {
	class LoftLoaderPro_Any_Page {
		/**
		* Construct function
		*/
		public function __construct() {
			if ( ! is_admin() ) {
				$this->adjust_loftloader_settings();
			}
			$this->load_editor_panel();
		}
		/**
		* Load editor panel for wp classic editor or gutenberg
		*/
		protected function load_editor_panel() {
			// Support for metabox
			require_once LOFTLOADERPRO_INC . 'any-page/class-any-page-metabox.php';
			// Support for gutenberg
			if ( function_exists( 'register_block_type' ) ) {
				require_once LOFTLOADERPRO_INC . 'any-page/gutenberg/class-gutenberg-any-page.php';
			}
		}
		/**
		* Initial LoftLoader Pro Shortcode actions
		*/
		protected function adjust_loftloader_settings() {
			require_once LOFTLOADERPRO_INC . '/any-page/class-any-page-filter.php';
			new LoftLoaderPro_Any_Page_Filter();
		}
	}
	new LoftLoaderPro_Any_Page();
}
