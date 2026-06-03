<?php
/**
 * Framework box shadow field file.
 *
 * @link https://shapedplugin.com
 * @since 3.0.0
 *
 * @package Testimonial_Pro
 * @subpackage Testimonial_Pro/Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SPFTESTIMONIAL_Field_button_clean' ) ) {
	/**
	 *
	 * Field: button_set
	 *
	 * @since 3.0.0
	 * @version 3.0.0
	 */
	class SPFTESTIMONIAL_Field_button_clean extends SPFTESTIMONIAL_Fields {
		/**
		 * Field constructor.
		 *
		 * @param array  $field The field type.
		 * @param string $value The values of the field.
		 * @param string $unique The unique ID for the field.
		 * @param string $where To where show the output CSS.
		 * @param string $parent The parent args.
		 */
		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}
		/**
		 * Render field
		 *
		 * @return void
		 */
		public function render() {
			$args = wp_parse_args(
				$this->field,
				array(
					'multiple'   => false,
					'options'    => array(),
					'query_args' => array(),
				)
			);

			$value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

			echo wp_kses_post( $this->field_before() );
			if ( isset( $this->field['options'] ) ) {
				$options = $this->field['options'];
				$options = ( is_array( $options ) ) ? $options : array_filter( $this->field_data( $options, false, $args['query_args'] ) );
				if ( is_array( $options ) && ! empty( $options ) ) {
					echo '<div class="spftestimonial-siblings spftestimonial--button-group" data-multiple="' . esc_attr( $args['multiple'] ) . '">';

					foreach ( $options as $key => $option ) {
						$type    = ( $args['multiple'] ) ? 'checkbox' : 'radio';
						$extra   = ( $args['multiple'] ) ? '[]' : '';
						$active  = ( in_array( $key, $value ) || ( empty( $value ) && empty( $key ) ) ) ? ' spftestimonial--active' : '';
						$checked = ( in_array( $key, $value ) || ( empty( $value ) && empty( $key ) ) ) ? ' checked' : '';

						echo '<button class="spftestimonial--sibling spftestimonial-confirm spftestimonial--button' . esc_attr( $active ) . '" data-confirm="' . esc_html__( 'Are you sure to clean cache?', 'testimonial-pro' ) . '">';
						echo $option;
						echo '</button>';
					}
					echo '</div>';
				} else {
					echo ! empty( $this->field['empty_message'] ) ? esc_attr( $this->field['empty_message'] ) : esc_html__( 'No data available.', 'testimonial-pro' );
				}
			}
			echo wp_kses_post( $this->field_after() );
		}
	}
}
