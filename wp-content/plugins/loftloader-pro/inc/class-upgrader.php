<?php
/**
* Upgrader class
* @since 1.2.2
*/
if ( ! class_exists( 'LoftLoader_Pro_Upgrader' ) ) {
	class LoftLoader_Pro_Upgrader {
		/**
		* String plugin current version
		*/
		private $version = '';
		/**
		* String plugin version option name
		*/
		private $version_name = '';
		/**
		* If the previous verion if older than current version,
		*	do the upgrade and update theme version
		*/
		public function __construct() {
			$this->version = LOFTLOADERPRO_VERSION;
			$this->version_name = 'loftloader_pro_version';

			$previous_version = get_option( $this->version_name, '1.2.1' );
			if ( version_compare( $previous_version, $this->version, '<' ) ) {
				$this->update_google_font_settings();
			}
			if ( version_compare( $previous_version, '2.4', '<' ) ) {
				$this->update_spt_settings();
			}
			$this->update_version();
		}
		/**
		* Update google font settings
		*/
		private function update_google_font_settings() {
			$default_font = 'Lato';
			$fonts = array( 'progress_number', 'message' );
			foreach ( $fonts as $font ) {
				$font_option_name = 'loftloader_pro_' . $font . '_font_family';
				$font_enable_name = 'loftloader_pro_' . $font . '_enable_google_font';
				$font = llp_get_loader_setting( $font_option_name );
				if ( empty( $font ) ) {
					update_option( $font_enable_name, 'off' );
					update_option( $font_option_name, $default_font );
				}
			}
		}
		/**
		* Smooth page transition settings update
		*/
		public function update_spt_settings() {
			$exclude1 = get_option( 'loftloader_pro_prevent_elements_from_spt_trigger', '' );
			$exclude2 = get_option( 'loftloader_pro_exclude_from_page_transition', '' );
			if ( ! empty( $exclude1 ) ) {
				$exclude2 = empty( $exclude2 ) ? $exclude1 : $exclude2 . ', ' . $exclude1;
				update_option( 'loftloader_pro_exclude_from_page_transition', $exclude2 );
			}
		}
		/**
		* Update current plugin version
		*/
		private function update_version() {
			update_option( $this->version_name, $this->version );
		}
	}
	new LoftLoader_Pro_Upgrader();
}
