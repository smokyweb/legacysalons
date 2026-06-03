<?php
/**
 * The Testimonial Form.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/form.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

echo $popup_opening_button; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo $conditional_tag_before_form; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<div id="testimonial_form_<?php echo esc_attr( $form_id ); ?>" class="sp-tpro-fronted-form" data-form_id='<?php echo esc_attr( $form_id ); ?>' data-video_format= '<?php echo esc_attr( $record_video_format ); ?>' data-form_attr='{"rating_required": "<?php echo esc_attr( $rating_required ); ?>", "hide_form": "<?php echo esc_attr( $hide_form_after_submit ); ?>", "ajax_submission": "<?php echo esc_attr( $ajax_form_submission ); ?>"}'>
<div class="sp-testimonial-form-container">
	<?php if ( $required_notice ) { ?>
		<div class="sp-testimonial-required-message">
			<?php echo wp_kses_post( $required_notice_label ); ?>
		</div>
	<?php } ?>
	<form id="testimonial_form" name="testimonial_form" method="post" action="" enctype="multipart/form-data">
		<?php
		if ( ! empty( $validation_msg ) && 'top' === $form_data['tpro_message_position'] ) {
			include self::sptp_locate_template( 'form/validation-msg.php' );
		}
		foreach ( $form_fields as $field_id => $form_field ) {
			switch ( $field_id ) {
				case 'full_name':
					if ( in_array( 'name', $form_element, true ) ) {
						$full_name_label = isset( $full_name['label'] ) ? $full_name['label'] : '';
						$before          = isset( $full_name['before'] ) ? $full_name['before'] : '';
						$after           = isset( $full_name['after'] ) ? $full_name['after'] : '';
						include self::sptp_locate_template( 'form/full-name.php' );
					}
					break;
				case 'email_address':
					if ( in_array( 'email', $form_element, true ) ) {
						$email_address_label = isset( $email_address['label'] ) ? $email_address['label'] : '';
						$before              = isset( $email_address['before'] ) ? $email_address['before'] : '';
						$after               = isset( $email_address['after'] ) ? $email_address['after'] : '';
						include self::sptp_locate_template( 'form/email.php' );
					}
					break;
				case 'identity_position':
					if ( in_array( 'position', $form_element, true ) ) {
						$identity_position_label = isset( $identity_position['label'] ) ? $identity_position['label'] : '';
						$before                  = isset( $identity_position['before'] ) ? $identity_position['before'] : '';
						$after                   = isset( $identity_position['after'] ) ? $identity_position['after'] : '';
						$required                = isset( $identity_position['required'] ) ? $identity_position['required'] : '';
						include self::sptp_locate_template( 'form/position.php' );
					}
					break;
				case 'company_name':
					if ( in_array( 'company', $form_element, true ) ) {
						$company_name_label = isset( $company_name['label'] ) ? $company_name['label'] : '';
						$before             = isset( $company_name['before'] ) ? $company_name['before'] : '';
						$after              = isset( $company_name['after'] ) ? $company_name['after'] : '';
						include self::sptp_locate_template( 'form/company-name.php' );
					}
					break;
				case 'testimonial_title':
					if ( in_array( 'testimonial_title', $form_element, true ) ) {
						$testimonial_title_label = isset( $testimonial_title['label'] ) ? $testimonial_title['label'] : '';
						$before                  = isset( $testimonial_title['before'] ) ? $testimonial_title['before'] : '';
						$after                   = isset( $testimonial_title['after'] ) ? $testimonial_title['after'] : '';
						$title_length_type       = isset( $testimonial_title['title_length']['title_length_type'] ) ? $testimonial_title['title_length']['title_length_type'] : 'characters';
						$title_word_limit        = isset( $testimonial_title['title_length']['title_word_limit'] ) ? $testimonial_title['title_length']['title_word_limit'] : '';
						$title_char_limit        = isset( $testimonial_title['title_length']['title_char_limit'] ) ? $testimonial_title['title_length']['title_char_limit'] : '';
						include self::sptp_locate_template( 'form/testimonial-title.php' );
					}
					break;
				case 'testimonial':
					if ( in_array( 'testimonial', $form_element, true ) ) {
						$testimonial_label   = isset( $testimonial['label'] ) ? $testimonial['label'] : '';
						$before              = isset( $testimonial['before'] ) ? $testimonial['before'] : '';
						$after               = isset( $testimonial['after'] ) ? $testimonial['after'] : '';
						$content_length_type = isset( $testimonial['content_length']['content_length_type'] ) ? $testimonial['content_length']['content_length_type'] : 'characters';
						$content_word_limit  = isset( $testimonial['content_length']['content_word_limit'] ) ? $testimonial['content_length']['content_word_limit'] : '';
						$content_char_limit  = isset( $testimonial['content_length']['content_char_limit'] ) ? $testimonial['content_length']['content_char_limit'] : '';
						include self::sptp_locate_template( 'form/testimonial-content.php' );
					}
					break;
				case 'groups':
					if ( in_array( 'groups', $form_element, true ) ) {
						$group_label = isset( $groups['label'] ) ? $groups['label'] : '';
						$before      = isset( $groups['before'] ) ? $groups['before'] : '';
						$after       = isset( $groups['after'] ) ? $groups['after'] : '';
						include self::sptp_locate_template( 'form/testimonial-cat.php' );
					}
					break;
				case 'featured_image':
					if ( in_array( 'image', $form_element, true ) ) {
						$featured_image_label = isset( $featured_image['label'] ) ? $featured_image['label'] : '';
						$before               = isset( $featured_image['before'] ) ? $featured_image['before'] : '';
						$after                = isset( $featured_image['after'] ) ? $featured_image['after'] : '';
						include self::sptp_locate_template( 'form/image.php' );
					}
					break;
				case 'location':
					if ( in_array( 'location', $form_element, true ) ) {
						$location_label = isset( $location['label'] ) ? $location['label'] : '';
						$country        = isset( $location['country_selector'] ) ? $location['country_selector'] : '';
						$before         = isset( $location['before'] ) ? $location['before'] : '';
						$after          = isset( $location['after'] ) ? $location['after'] : '';
						include self::sptp_locate_template( 'form/location.php' );
					}
					break;
				case 'phone_mobile':
					if ( in_array( 'phone_mobile', $form_element, true ) ) {
						$phone_mobile_label = isset( $phone_mobile['label'] ) ? $phone_mobile['label'] : '';
						$before             = isset( $phone_mobile['before'] ) ? $phone_mobile['before'] : '';
						$after              = isset( $phone_mobile['after'] ) ? $phone_mobile['after'] : '';
						include self::sptp_locate_template( 'form/phone.php' );
					}
					break;
				case 'website':
					if ( in_array( 'website', $form_element, true ) ) {
						$website_label = isset( $website['label'] ) ? $website['label'] : '';
						$before        = isset( $website['before'] ) ? $website['before'] : '';
						$after         = isset( $website['after'] ) ? $website['after'] : '';
						include self::sptp_locate_template( 'form/website.php' );
					}
					break;
				case 'video_url':
					if ( in_array( 'video_url', $form_element, true ) ) {
						$video_label        = isset( $video_url['label'] ) ? $video_url['label'] : '';
						$before             = isset( $video_url['before'] ) ? $video_url['before'] : '';
						$after              = isset( $video_url['after'] ) ? $video_url['after'] : '';
						$video_record_label = isset( $video_url['record_label'] ) ? $video_url['record_label'] : '';
						$form_video_type    = isset( $video_url['form_video_type'] ) ? $video_url['form_video_type'] : 'record_video';
						$recording_time     = isset( $video_url['recording_time'] ) ? $video_url['recording_time'] : 2;
						$record_btn_text    = isset( $video_url['record_btn_text'] ) ? $video_url['record_btn_text'] : 'Record Video';
						include self::sptp_locate_template( 'form/video-url.php' );
					}
					break;
				case 'social_profile':
					if ( in_array( 'social_profile', $form_element, true ) ) {
						$social_profile_label = isset( $social_profile['label'] ) ? $social_profile['label'] : '';
						$before               = isset( $social_profile['before'] ) ? $social_profile['before'] : '';
						$after                = isset( $social_profile['after'] ) ? $social_profile['after'] : '';
						include self::sptp_locate_template( 'form/social-profile.php' );
					}
					break;
				case 'rating':
					if ( in_array( 'rating', $form_element, true ) ) {
						$rating_label = isset( $rating['label'] ) ? $rating['label'] : '';
						$before       = isset( $rating['before'] ) ? $rating['before'] : '';
						$after        = isset( $rating['after'] ) ? $rating['after'] : '';
						include self::sptp_locate_template( 'form/rating.php' );
					}
					break;
				case 'agree_checkbox':
					if ( in_array( 'agree_checkbox', $form_element, true ) ) {
						$checkbox_label    = isset( $agree_checkbox['label'] ) ? $agree_checkbox['label'] : '';
						$checkbox_required = isset( $agree_checkbox['required'] ) && $agree_checkbox['required'] ? 'required' : '';
						include self::sptp_locate_template( 'form/checkbox.php' );
					}
					break;
				case 'recaptcha':
					if ( in_array( 'recaptcha', $form_element, true ) && '' !== $setting_options['captcha_site_key'] && '' !== $setting_options['captcha_secret_key'] && 'v2' === $captcha_version ) {
						$recaptcha_label = isset( $recaptcha['label'] ) ? $recaptcha['label'] : '';
						include self::sptp_locate_template( 'form/recaptcha.php' );
					}
					break;
				case 'submit_btn':
					include self::sptp_locate_template( 'form/submit-btn.php' );
					break;
			}
		}
		?>
		<script>

		</script>
<?php
if ( ! empty( $validation_msg ) && ( 'bottom' === $form_data['tpro_message_position'] ) ) {
	include self::sptp_locate_template( 'form/validation-msg.php' );
}
?>
	</form>
</div>
</div>
<?php echo $conditional_tag_after_form; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
