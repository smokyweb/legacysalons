<?php
/**
* Back compat class
* Show error message if the PHP/WP version is low
*/

if ( ! class_exists( 'LoftLoader_Pro_Back_Compat' ) ) {
	class LoftLoader_Pro_Back_Compat {
		/**
		* String minimal PHP version required
		*/
		protected $php_version;
		/**
		* String minimal WP version required
		*/
		protected $wp_version;
		public function __construct( $php, $wp ) {
			$this->php_version = $php;
			$this->wp_version = $wp;

			add_action( 'admin_notices', array( $this, 'error_message' ) );
			add_action( 'admin_menu', array( $this, 'add_admin_menu') );
		}

		/**
		* Print the admin notice message if PHP version challenging failed
		*/
		public function error_message() {
			$low_wp_message = '';
			$low_php_message = '';
			if ( version_compare( $GLOBALS['wp_version'], $this->wp_version, '<' ) ) {
				$low_wp_message = sprintf(
					'<br/><br/>%s',
					sprintf(
						/* translators: 1: html tag start. 2: html tag end */
						esc_html__( '- Your WordPress version is too old. %1$sPlease update to WordPress %3$s or higher%2$s.', 'loftloader-pro' ),
						'<b>',
						'</b>',
						esc_attr( $this->wp_version )
					)
				);
			}
			if ( version_compare( phpversion(), $this->php_version, '<' ) ) {
				$low_php_message = sprintf(
					'<br/><br/>%s',
					sprintf(
						/* translators: 1: html tag start. 2: html tag end */
						esc_html__( '- Your PHP version is too old. %1$sPlease update to PHP %3$s or higher%2$s.', 'loftloader-pro' ),
						'<b>',
						'</b>',
						esc_html( $this->wp_version )
					)
				);
			}
			printf(
				'<div class="error"><p>%1$s%2$s%3$s</p></div>',
				esc_html__( 'Oops! Your site environment does not seem to meet the requirements for using LoftLoader Pro.', 'loftloader-pro' ),
				wp_kses_post( $low_wp_message ),
				wp_kses_post( $low_php_message )
			);
		}
		/**
		* Add admin menu for loftloader pro in setting panel
		*/
		public function add_admin_menu() {
			global $submenu;
			$submenu['options-general.php'][] = array(
				sprintf(
					'%1$s %2$s',
					esc_html__( 'LoftLoader Pro', 'loftloader-pro' ),
					sprintf( '<span class="awaiting-mod" style="white-space: nowrap;"> %s</span>', esc_html__( 'YOUR PHP/WP IS OUTDATED', 'loftloader-pro' ) )
				),
				'manage_options',
				'#'
			);
		}
	}
}
