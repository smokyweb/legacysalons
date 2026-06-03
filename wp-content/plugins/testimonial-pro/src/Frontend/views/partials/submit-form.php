<?php
/**
 * Testimonial submit form.
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

use ShapedPlugin\TestimonialPro\Frontend\Helper;

if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] && ! empty( $_POST['action'] ) && 'testimonial_form' . $form_id === $_POST['action'] ) {
	$pid   = false;
	$nonce = isset( $_POST['testimonial_form_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['testimonial_form_nonce'] ) ) : '';
	if ( wp_verify_nonce( $nonce, 'testimonial_form' ) ) {
		$tpro_client_name                    = isset( $_POST['tpro_client_name'] ) ? wp_strip_all_tags( wp_unslash( $_POST['tpro_client_name'] ) ) : '';
		$tpro_client_email                   = isset( $_POST['tpro_client_email'] ) ? sanitize_email( wp_unslash( $_POST['tpro_client_email'] ) ) : '';
		$tpro_client_designation             = isset( $_POST['tpro_client_designation'] ) ? sanitize_text_field( wp_unslash( $_POST['tpro_client_designation'] ) ) : '';
		$tpro_company_name                   = isset( $_POST['tpro_client_company_name'] ) ? sanitize_text_field( wp_unslash( $_POST['tpro_client_company_name'] ) ) : '';
		$tpro_location                       = isset( $_POST['tpro_client_location'] ) ? sanitize_text_field( wp_unslash( $_POST['tpro_client_location'] ) ) : '';
		$tpro_country                        = isset( $_POST['tpro_client_location_country'] ) ? sanitize_text_field( wp_unslash( $_POST['tpro_client_location_country'] ) ) : '';
		$tpro_phone                          = isset( $_POST['tpro_client_phone'] ) ? preg_replace( '/[^0-9+-]/', '', sanitize_text_field( wp_unslash( $_POST['tpro_client_phone'] ) ) ) : '';
		$tpro_website                        = isset( $_POST['tpro_client_website'] ) ? esc_url( sanitize_text_field( wp_unslash( $_POST['tpro_client_website'] ) ) ) : '';
		$tpro_video_url                      = isset( $_POST['tpro_client_video_url'] ) ? esc_url( sanitize_text_field( wp_unslash( $_POST['tpro_client_video_url'] ) ) ) : '';
		$tpro_client_testimonial_cat         = isset( $_POST['tpro_client_testimonial_cat'] ) ? wp_kses_post_deep( wp_unslash( $_POST['tpro_client_testimonial_cat'] ) ) : '';
		$tpro_testimonial_title              = isset( $_POST['tpro_testimonial_title'] ) ? sanitize_text_field( wp_unslash( $_POST['tpro_testimonial_title'] ) ) : '';
		$tpro_testimonial_text               = isset( $_POST['tpro_client_testimonial'] ) ? sanitize_textarea_field( wp_unslash( $_POST['tpro_client_testimonial'] ) ) : '';
		$tpro_rating_star                    = isset( $_POST['tpro_client_rating'] ) ? sanitize_key( $_POST['tpro_client_rating'] ) : '';
		$tpro_client_video_upload            = isset( $_POST['tpro_client_video_upload'] ) ? sanitize_key( $_POST['tpro_client_video_upload'] ) : '';
		$tpro_social_profiles                = isset( $_POST['tpro_social_profiles'] ) ? wp_unslash( $_POST['tpro_social_profiles'] ) : '';
		$tpro_client_checkbox                = isset( $_POST['tpro_client_checkbox'] ) && $_POST['tpro_client_checkbox'] ? '1' : '0';
		$tpro_auto_publish_ratings           = isset( $form_data['tpro_auto_publish_rating'] ) ? $form_data['tpro_auto_publish_rating'] : '';
		$tpro_admin_email                    = isset( $form_data['submission_email_to'] ) ? $form_data['submission_email_to'] : '';
		$tpro_reviewer_awaiting_notification = isset( $form_data['reviewer_awaiting_notification'] ) ? $form_data['reviewer_awaiting_notification'] : '';
		$tpro_approval_notification          = isset( $form_data['approval_notification'] ) ? $form_data['approval_notification'] : '';
		$tpro_admin_email_from               = isset( $form_data['submission_email_from'] ) ? $form_data['submission_email_from'] : '';
		// ADD THE FORM INPUT TO $testimonial_form ARRAY.
		$testimonial_form = array(
			'post_title'   => $tpro_testimonial_title,
			'post_content' => $tpro_testimonial_text,
			'post_status'  => $form_data['testimonial_approval_status'],
			'post_type'    => 'spt_testimonial',
			'meta_input'   => array(
				'sp_tpro_meta_options' => array(
					'tpro_name'                    => $tpro_client_name,
					'tpro_email'                   => $tpro_client_email,
					'tpro_designation'             => $tpro_client_designation,
					'tpro_company_name'            => $tpro_company_name,
					'tpro_phone'                   => $tpro_phone,
					'tpro_website'                 => $tpro_website,
					'tpro_video_url'               => $tpro_video_url,
					'tpro_rating'                  => $tpro_rating_star,
					'tpro_social_profiles'         => $tpro_social_profiles,
					'tpro_client_checkbox'         => $tpro_client_checkbox,
					'tpro_form_id'                 => $form_id,
					'testimonial_clients_location' => array(
						'tpro_location' => $tpro_location,
						'tpro_country'  => $tpro_country,
					),
				),
			),
		);

		$tpro_redirect  = $form_data['tpro_redirect'];
		$response_data2 = false;
		// Empty MSG.
		$validation_msg    = '';
		$captcha_error_msg = '';
		if ( in_array( 'recaptcha', $form_element ) && ( '' !== $setting_options['captcha_site_key'] || '' !== $captcha_site_key_v3 ) && ( '' !== $setting_options['captcha_secret_key'] || '' !== $captcha_secret_key_v3 ) ) {

			if ( isset( $_POST['submit'] ) && ! empty( $_POST['submit'] ) ) {
				// Recaptcha v3.
				if ( 'v3' === $captcha_version ) {
					$response_token = isset( $_POST['token'] ) ? $_POST['token'] : '';
					$secret         = $captcha_secret_key_v3;
				} else {
					$response_token = isset( $_POST['g-recaptcha-response'] ) ? $_POST['g-recaptcha-response'] : '';
					$secret         = $setting_options['captcha_secret_key'];
				}
				if ( ! empty( $response_token ) ) {

					if ( 'based_on_rating_star' === $testimonial_form['post_status'] ) {
						$testimonial_form['post_status'] = 'pending';
						if ( in_array( $testimonial_form['meta_input']['sp_tpro_meta_options']['tpro_rating'], $tpro_auto_publish_ratings, true ) ) {
							// Set the post status to publish.
							$testimonial_form['post_status'] = 'publish';
						}
					}
					$pid = wp_insert_post( $testimonial_form );
					// Get verify response data.
					$verify_response = wp_remote_get( 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $response_token );
					$response_data   = json_decode( $verify_response['body'], true );
					$response_data2  = json_decode( $response_data['success'], true );
					if ( $response_data2 ) {
						// Save The Testimonial.
						if ( $pid ) {
							wp_set_post_terms( $pid, $tpro_client_testimonial_cat, 'testimonial_cat' );

							// Thanks message.
							switch ( $tpro_redirect ) {
								case 'to_a_page':
									self::tpro_redirect( get_page_link( $form_data['tpro_redirect_to_page'] ) );
									break;
								case 'custom_url':
									self::tpro_redirect( esc_url( $form_data['tpro_redirect_custom_url'] ) );
									break;
								default:
									$validation_msg .= $form_data['successful_message'];
									break;
							}
						}
					} else {
						$captcha_error_msg .= esc_html__( 'Robot verification failed, please try again.', 'testimonial-pro' );
					}
				} else {
					$captcha_error_msg .= esc_html__( 'Please click on the reCAPTCHA box.', 'testimonial-pro' );
				}
			}
		} else {

			if ( 'based_on_rating_star' === $testimonial_form['post_status'] ) {
				$testimonial_form['post_status'] = 'pending';
				if ( in_array( $testimonial_form['meta_input']['sp_tpro_meta_options']['tpro_rating'], $tpro_auto_publish_ratings, true ) ) {
					// Set the post status to publish.
					$testimonial_form['post_status'] = 'publish';
				}
			}
			// Save The Testimonial.
			$pid = wp_insert_post( $testimonial_form );

			if ( $pid ) {
				wp_set_post_terms( $pid, $tpro_client_testimonial_cat, 'testimonial_cat' );
				// Thanks message.
				switch ( $tpro_redirect ) {
					case 'to_a_page':
						self::tpro_redirect( get_page_link( $form_data['tpro_redirect_to_page'] ) );
						break;
					case 'custom_url':
						self::tpro_redirect( esc_url( $form_data['tpro_redirect_custom_url'] ) );
						break;
					default:
						$validation_msg .= $form_data['successful_message'];
						self::tpro_redirect( get_page_link() . '#submit' );
						break;
				}
			}
		}

		// Client Image and video.
		if ( ! function_exists( 'wp_generate_attachment_metadata' ) || class_exists( 'Astra_Sites_Importer' ) || ! function_exists( 'media_handle_upload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/image.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
		}

		if ( $pid ) {
			if ( $_FILES ) {
				foreach ( $_FILES as $file => $array ) {
					if ( UPLOAD_ERR_OK !== $_FILES[ $file ]['error'] ) {
					} else {
						$attach_id = media_handle_upload( $file, $pid );
						if ( ! is_wp_error( $attach_id ) && $attach_id > 0 ) {
							// Set post image.
							if ( 'video/mp4' === $array['type'] || 'video/webm' === $array['type'] || 'video/x-matroska' === $array['type'] ) {
								$testimonial_data                   = get_post_meta( $pid, 'sp_tpro_meta_options', true );
								$testimonial_data['tpro_video_url'] = wp_get_attachment_url( $attach_id );
								update_post_meta( $pid, 'sp_tpro_meta_options', $testimonial_data );
							} else {
								update_post_meta( $pid, '_thumbnail_id', $attach_id );
							}
						}
					}
				}
			}
		}

		/**
		 * Email notification.
		 */
		if ( $pid && ( $form_data['submission_email_notification'] || $tpro_reviewer_awaiting_notification || $tpro_approval_notification ) && ( ( in_array( 'recaptcha', $form_element ) && $response_data2 ) || ! in_array( 'recaptcha', $form_element ) ) ) {

			$tpro_category = '';
			if ( $tpro_client_testimonial_cat ) {
				foreach ( $tpro_client_testimonial_cat as $testimonial_cat_term ) {
					$tpro_category_name[] = get_the_category_by_ID( $testimonial_cat_term );
				}
				$tpro_category = implode( ', ', $tpro_category_name );
			}

			$tpro_rating = Helper::generate_star_rating( $tpro_rating_star );

			$admin_email      = get_option( 'admin_email' );
			$site_name        = get_bloginfo( 'name' );
			$site_description = '- ' . get_bloginfo( 'description' );

			// Mail Header.
			if ( ! empty( $tpro_client_email ) ) {
				$email_header = array(
					'Content-Type: text/html; charset=UTF-8',
					'From: "' . $tpro_client_name . '" < ' . $tpro_client_email . ' >',
				);
			} else {
				$email_header = array(
					'Content-Type: text/html; charset=UTF-8',
					'From: "' . $tpro_client_name . '" < ' . $admin_email . ' >',
				);
			}

			// Admin Email Notification.
			if ( $form_data['submission_email_notification'] && $tpro_admin_email ) {
				$email_to_list   = $tpro_admin_email; // mail to.
				$email_to        = explode( ',', $email_to_list );
				$email_from      = $tpro_admin_email_from; // mail from.
				$email_from      = str_replace( '{site_title}', $site_name, $email_from );
				$subject         = $form_data['submission_email_subject']; // subject.
				$subject         = str_replace( '{site_title}', $site_name, $subject );
				$message_content = $form_data['submission_email_body']; // mail body.

				$message = Helper::generate_email_message( $message_content, $site_name, $site_description, $tpro_client_name, $tpro_client_email, $tpro_client_designation, $tpro_company_name, $tpro_location, $tpro_country, $tpro_phone, $tpro_website, $tpro_video_url, $tpro_testimonial_title, $tpro_testimonial_text, $tpro_category, $tpro_rating );

				// Send email to.
				wp_mail( $email_to, $subject, $message, $email_header );
			}

			// Reviewer Awaiting Notifications.
			if ( $tpro_reviewer_awaiting_notification && $tpro_client_email && ! ( 'publish' === $testimonial_form['post_status'] ) ) {
				$email_to        = $tpro_client_email;
				$email_from      = $form_data['reviewer_awaiting_from']; // mail from.
				$email_from      = str_replace( '{site_title}', $site_name, $email_from );
				$subject         = $form_data['reviewer_awaiting_email_subject']; // subject.
				$subject         = str_replace( '{site_title}', $site_name, $subject );
				$message_content = $form_data['reviewer_awaiting_email_body']; // mail body.

				$message = Helper::generate_email_message( $message_content, $site_name, $site_description, $tpro_client_name, $tpro_client_email, $tpro_client_designation, $tpro_company_name, $tpro_location, $tpro_country, $tpro_phone, $tpro_website, $tpro_video_url, $tpro_testimonial_title, $tpro_testimonial_text, $tpro_category, $tpro_rating );

				// Send email to.
				wp_mail( $email_to, $subject, $message, $email_header );
			}

			// Testimonial Approval Notifications.
			if ( $tpro_approval_notification && $tpro_client_email && 'publish' === $testimonial_form['post_status'] ) {
				$email_to        = $tpro_client_email;
				$email_from      = $form_data['approval_email_from']; // mail from.
				$email_from      = str_replace( '{site_title}', $site_name, $email_from );
				$subject         = $form_data['approval_email_subject']; // subject.
				$message_content = $form_data['approval_email_body']; // mail body.

				$message = Helper::generate_email_message( $message_content, $site_name, $site_description, $tpro_client_name, $tpro_client_email, $tpro_client_designation, $tpro_company_name, $tpro_location, $tpro_country, $tpro_phone, $tpro_website, $tpro_video_url, $tpro_testimonial_title, $tpro_testimonial_text, $tpro_category, $tpro_rating );

				// Send email to.
				wp_mail( $email_to, $subject, $message, $email_header );
			}
		}
	} else {
		wp_die( esc_html__( 'Our site is protected!', 'testimonial-pro' ) );
	}
	$_POST = array();
} // END THE IF STATEMENT THAT STARTED THE WHOLE FORM.
