<?php
/**
 * Update options for the version 3.0.1
 *
 * @link       https://shapedplugin.com
 *
 * @package    testimonial_pro
 * @subpackage testimonial_pro/Admin/updates
 */

update_option( 'testimonial_pro_version', '3.1.0' );
update_option( 'testimonial_pro_db_version', '3.1.0' );

/**
 * Form shortcode query for id.
 */
$form_args = new WP_Query(
	array(
		'post_type'      => 'spt_testimonial_form',
		'post_status'    => 'any',
		'posts_per_page' => '300',
	)
);
$form_ids  = wp_list_pluck( $form_args->posts, 'ID' );
if ( count( $form_ids ) > 0 ) {
	foreach ( $form_ids as $form_key => $form_id ) {
		$form_data = get_post_meta( $form_id, 'sp_tpro_form_options', true );
		if ( ! is_array( $form_data ) ) {
			continue;
		}

		$form_rating_color                           = isset( $form_data['form_rating_color'] ) ? $form_data['form_rating_color'] : '#d4d4d4';
		$form_data['testimonial_form_rating_styles'] = array(
			'all'              => '16',
			'color'            => $form_rating_color,
			'background_color' => '#f3bb00',
			'hover-color'      => '#de7202',
		);

		// use database updater for the record video & video by URL option.
		$form_fields   = isset( $form_data['form_fields'] ) ? $form_data['form_fields'] : '';
		$form_elements = get_post_meta( $form_id, 'sp_tpro_form_elements_options', true );
		$form_element  = isset( $form_elements['form_elements'] ) ? $form_elements['form_elements'] : array();

		foreach ( $form_fields as $field_id => $form_field ) {
			switch ( $field_id ) {
				case 'video_url':
					$video_url        = isset( $form_fields['video_url'] ) ? $form_fields['video_url'] : '';
					$old_video_by_url = isset( $video_url['video_by_url'] ) ? $video_url['video_by_url'] : true;
					$old_record_video = isset( $video_url['record_video'] ) ? $video_url['record_video'] : false;

					if ( $old_video_by_url && $old_record_video ) {
						$form_data['form_fields']['video_url']['form_video_type'] = 'record_video';
					}

					if ( $old_video_by_url ) {
						$form_data['form_fields']['video_url']['form_video_type'] = 'video_by_url';
					} else {
						$form_data['form_fields']['video_url']['form_video_type'] = 'record_video';
					}
			}
		}

		update_post_meta( $form_id, 'sp_tpro_form_options', $form_data );
	}
}
