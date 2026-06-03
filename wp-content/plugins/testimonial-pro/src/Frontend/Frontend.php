<?php
/**
 * The Frontend class to manage all public-facing functionality of the plugin.
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\TestimonialPro\Frontend;

use ShapedPlugin\TestimonialPro\Includes\License;
use ShapedPlugin\TestimonialPro\Frontend\Helper;
use ShapedPlugin\TestimonialPro\Frontend\OldForm;
/**
 * The Frontend class to manage all public facing stuffs.
 *
 * @since 2.1.0
 */
class Frontend {

	/**
	 * Initialize the class.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'front_styles' ) );
		add_action( 'wp_loaded', array( $this, 'register_front_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_front_scripts' ) );
		add_shortcode( 'testimonial_pro', array( $this, 'shortcode_render' ) );
		add_shortcode( 'sp_testimonial', array( $this, 'shortcode_render' ) );

		add_shortcode( 'sp_testimonial_form', array( $this, 'frontend_form' ) );
		add_action( 'save_post', array( $this, 'delete_page_testimonial_option_on_save' ) );
		add_action( 'save_post', array( $this, 'form_submission_approval_notification' ), 20, 2 );
		// Ajax Search Member.
		add_action( 'wp_ajax_search_testimonial', array( $this, 'search_testimonial' ) );
		add_action( 'wp_ajax_nopriv_search_testimonial', array( $this, 'search_testimonial' ) );
		add_filter( 'sp_testimonial_review_content', array( $this, 'enable_autoembed_wpautop' ) );
		add_action( 'wp_ajax_testimonial_form_submission', array( $this, 'testimonial_form_submission' ) );
		add_action( 'wp_ajax_nopriv_testimonial_form_submission', array( $this, 'testimonial_form_submission' ) );
		// Old Testimonial form.
		new OldForm();
	}

	/**
	 * Autoembed video or iframe and wpautop in testimonial content.
	 *
	 * @param  string $content testimonial content.
	 * @return string
	 */
	public function enable_autoembed_wpautop( $content ) {
		global $wp_embed;
		$content = $wp_embed->autoembed( $content );
		$content = wpautop( $content );

		return $content;
	}

	/**
	 * Autoembed video or iframe and wpautop in testimonial content.
	 *
	 * @return statement
	 */
	public function testimonial_form_submission() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( wp_unslash( $nonce ), 'sp-testimonial-form-nonce' ) ) {
			return false;
		}
		$form_id             = isset( $_POST['generator_id'] ) ? sanitize_text_field( wp_unslash( $_POST['generator_id'] ) ) : '';
		$setting_options     = get_option( 'sp_testimonial_pro_options' );
		$shortcode_data      = get_post_meta( $form_id, 'sp_tpro_shortcode_options', true );
		$form_settings       = get_post_meta( $form_id, 'sp_tpro_form_options', true );
		$form_elements       = get_post_meta( $form_id, 'sp_tpro_form_elements_options', true );
		$captcha_site_key_v3 = isset( $setting_options['captcha_site_key_v3'] ) ? $setting_options['captcha_site_key_v3'] : '';
		$form_element        = isset( $form_elements['form_elements'] ) ? $form_elements['form_elements'] : array();

		// Handle form submission. Don't worry, The form data is sanitized below.
		$form_data = isset( $_POST ) ? wp_unslash( $_POST ) : ''; // phpcs:ignore
		$pid               = false;
		$nonce             = isset( $form_data['testimonial_form_nonce'] ) ? sanitize_text_field( wp_unslash( $form_data['testimonial_form_nonce'] ) ) : '';
		$tpro_client_name  = isset( $form_data['tpro_client_name'] ) ? wp_strip_all_tags( wp_unslash( $form_data['tpro_client_name'] ) ) : '';
		$tpro_client_email = isset( $form_data['tpro_client_email'] ) ? wp_unslash( $form_data['tpro_client_email'] ) : '';

		$tpro_company_name           = isset( $form_data['tpro_client_company_name'] ) ? sanitize_text_field( wp_unslash( $form_data['tpro_client_company_name'] ) ) : '';
		$tpro_client_designation     = isset( $form_data['tpro_client_designation'] ) ? sanitize_text_field( wp_unslash( $form_data['tpro_client_designation'] ) ) : '';
		$tpro_location               = isset( $form_data['tpro_client_location'] ) ? sanitize_text_field( wp_unslash( $form_data['tpro_client_location'] ) ) : '';
		$tpro_country                = isset( $form_data['tpro_client_location_country'] ) ? sanitize_text_field( wp_unslash( $form_data['tpro_client_location_country'] ) ) : '';
		$tpro_phone                  = isset( $form_data['tpro_client_phone'] ) ? preg_replace( '/[^0-9+-]/', '', sanitize_text_field( wp_unslash( $form_data['tpro_client_phone'] ) ) ) : '';
		$tpro_website                = isset( $form_data['tpro_client_website'] ) ? esc_url( sanitize_text_field( wp_unslash( $form_data['tpro_client_website'] ) ) ) : '';
		$tpro_video_url              = isset( $form_data['tpro_client_video_url'] ) ? esc_url( sanitize_text_field( wp_unslash( $form_data['tpro_client_video_url'] ) ) ) : '';
		$tpro_client_testimonial_cat = isset( $form_data['tpro_client_testimonial_cat'] ) ? wp_unslash( $form_data['tpro_client_testimonial_cat'] ) : '';
		$tpro_testimonial_title      = isset( $form_data['tpro_testimonial_title'] ) ? sanitize_text_field( wp_unslash( $form_data['tpro_testimonial_title'] ) ) : '';
		$tpro_testimonial_text       = isset( $form_data['tpro_client_testimonial'] ) ? sanitize_textarea_field( wp_unslash( $form_data['tpro_client_testimonial'] ) ) : '';
		$tpro_rating_star            = isset( $form_data['tpro_client_rating'] ) ? sanitize_key( $form_data['tpro_client_rating'] ) : '';
		$tpro_client_video_upload    = isset( $form_data['tpro_client_video_upload'] ) ? sanitize_key( $form_data['tpro_client_video_upload'] ) : '';
		$tpro_social_profiles        = isset( $form_data['tpro_social_profiles'] ) ? wp_unslash( $form_data['tpro_social_profiles'] ) : '';
		$tpro_client_checkbox        = isset( $form_data['tpro_client_checkbox'] ) && $form_data['tpro_client_checkbox'] ? '1' : '0';
		$tpro_auto_publish_ratings   = isset( $form_settings['tpro_auto_publish_rating'] ) ? $form_settings['tpro_auto_publish_rating'] : '';
		$testimonial_approval_status = isset( $form_settings['testimonial_approval_status'] ) ? $form_settings['testimonial_approval_status'] : '';
		$captcha_version             = isset( $setting_options['captcha_version'] ) ? $setting_options['captcha_version'] : 'v2';
		$captcha_secret_key_v3       = isset( $setting_options['captcha_secret_key_v3'] ) ? $setting_options['captcha_secret_key_v3'] : '';

		// ADD THE FORM INPUT TO $testimonial_form ARRAY.
		$testimonial_form = array(
			'post_title'   => $tpro_testimonial_title,
			'post_content' => $tpro_testimonial_text,
			'post_status'  => $testimonial_approval_status,
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

		$tpro_redirect  = isset( $form_settings['tpro_redirect'] ) ? $form_settings['tpro_redirect'] : '';
		$response_data2 = false;
		// Empty MSG.
		$validation_msg    = '';
		$redirect_url      = '';
		$captcha_error_msg = '';
		if ( in_array( 'recaptcha', $form_element ) && ( '' !== $setting_options['captcha_site_key'] || '' !== $captcha_site_key_v3 ) && ( '' !== $setting_options['captcha_secret_key'] || '' !== $captcha_secret_key_v3 ) ) {
			if ( isset( $form_data['submit'] ) && ! empty( $form_data['submit'] ) ) {
				// Recaptcha v3.
				if ( 'v3' === $captcha_version ) {
					$response_token = isset( $form_data['token'] ) ? $form_data['token'] : '';
					$secret         = $captcha_secret_key_v3;
				} else {
					$response_token = isset( $form_data['g-recaptcha-response'] ) ? $form_data['g-recaptcha-response'] : '';
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
									$redirect_url .= get_page_link( $form_settings['tpro_redirect_to_page'] );
									break;
								case 'custom_url':
									$redirect_url .= esc_url( $form_settings['tpro_redirect_custom_url'] );
									break;
								default:
									$validation_msg .= $form_settings['successful_message'];
									break;
							}
						}
					} else {
						$captcha_error_msg .= esc_html__( 'Robot verification failed, please try again.', 'testimonial-pro' );
					}
				} else {
					$captcha_error_msg .= esc_html__( 'Please click on the reCAPTCHA box.', 'testimonial-pro' );
				}
				if ( ! empty( $captcha_error_msg ) ) {
					$form_settings['error_message'] = $captcha_error_msg;
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
						$redirect_url .= get_page_link( $form_settings['tpro_redirect_to_page'] );
						break;
					case 'custom_url':
						$redirect_url .= esc_url( $form_settings['tpro_redirect_custom_url'] );
						break;
					default:
						$validation_msg .= $form_settings['successful_message'];
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
		$admin_email_notification       = isset( $form_settings['submission_email_notification'] ) ? $form_settings['submission_email_notification'] : '';
		$reviewer_awaiting_notification = isset( $form_settings['reviewer_awaiting_notification'] ) ? $form_settings['reviewer_awaiting_notification'] : '';
		$approval_notification          = isset( $form_settings['approval_notification'] ) ? $form_settings['approval_notification'] : '';
		if ( $pid && ( $admin_email_notification || $reviewer_awaiting_notification || $approval_notification ) && ( ( in_array( 'recaptcha', $form_element, true ) && $response_data2 && ! $captcha_error_msg ) || ! in_array( 'recaptcha', $form_element, true ) ) ) {
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
			// Email Header.
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
			if ( $admin_email_notification && $form_settings['submission_email_to'] ) {
				$email_to_list   = $form_settings['submission_email_to']; // mail to.
				$email_to        = explode( ',', $email_to_list );
				$email_from      = $form_settings['submission_email_from']; // mail to.
				$email_from      = str_replace( '{site_title}', $site_name, $email_from );
				$subject         = $form_settings['submission_email_subject']; // subject.
				$subject         = str_replace( '{site_title}', $site_name, $subject );
				$message_content = $form_settings['submission_email_body']; // mail body.

				$message = Helper::generate_email_message( $message_content, $site_name, $site_description, $tpro_client_name, $tpro_client_email, $tpro_client_designation, $tpro_company_name, $tpro_location, $tpro_country, $tpro_phone, $tpro_website, $tpro_video_url, $tpro_testimonial_title, $tpro_testimonial_text, $tpro_category, $tpro_rating );

				// Send email to.
				wp_mail( $email_to, $subject, $message, $email_header );
			}

			// Reviewer Awaiting Notifications.
			if ( $reviewer_awaiting_notification && $tpro_client_email && ! ( 'publish' === $testimonial_form['post_status'] ) ) {
				$email_to        = $tpro_client_email;
				$email_from      = $form_settings['reviewer_awaiting_from']; // mail from.
				$email_from      = str_replace( '{site_title}', $site_name, $email_from );
				$subject         = $form_settings['reviewer_awaiting_email_subject']; // subject.
				$subject         = str_replace( '{site_title}', $site_name, $subject );
				$message_content = $form_settings['reviewer_awaiting_email_body']; // mail body.

				$message = Helper::generate_email_message( $message_content, $site_name, $site_description, $tpro_client_name, $tpro_client_email, $tpro_client_designation, $tpro_company_name, $tpro_location, $tpro_country, $tpro_phone, $tpro_website, $tpro_video_url, $tpro_testimonial_title, $tpro_testimonial_text, $tpro_category, $tpro_rating );

				// Send email to.
				wp_mail( $email_to, $subject, $message, $email_header );
			}

			// Testimonial Approval Notifications.
			if ( $approval_notification && $tpro_client_email && 'publish' === $testimonial_form['post_status'] ) {
				$email_to        = $tpro_client_email;
				$email_from      = $form_settings['approval_email_from']; // mail from.
				$email_from      = str_replace( '{site_title}', $site_name, $email_from );
				$subject         = $form_settings['approval_email_subject']; // subject.
				$message_content = $form_settings['approval_email_body']; // mail body.

				$message = Helper::generate_email_message( $message_content, $site_name, $site_description, $tpro_client_name, $tpro_client_email, $tpro_client_designation, $tpro_company_name, $tpro_location, $tpro_country, $tpro_phone, $tpro_website, $tpro_video_url, $tpro_testimonial_title, $tpro_testimonial_text, $tpro_category, $tpro_rating );

				// Send email to.
				wp_mail( $email_to, $subject, $message, $email_header );

			}
		}

		$response_data = array(
			'redirect_url'       => $redirect_url,
			'redirect_type'      => $tpro_redirect,
			'successful_message' => $validation_msg,
			'error_message'      => $form_settings['error_message'],
			'message_position'   => $form_settings['tpro_message_position'],

		);
		if ( $pid && is_int( $pid ) ) {
			// Send JSON response.
			wp_send_json_success( $response_data, 200 );
		} else {
			wp_send_json_success( $response_data, 400 );
		}
		wp_die(); // Always include this at the end to terminate the script.
	}

	/**
	 * Delete page shortcode ids array option on save
	 *
	 * @param  int $post_ID current post id.
	 * @return void
	 */
	public function delete_page_testimonial_option_on_save( $post_ID ) {
		if ( is_multisite() ) {
			$option_key = 'sp_testimonial_page_id' . get_current_blog_id() . $post_ID;
			if ( get_site_option( $option_key ) ) {
				delete_site_option( $option_key );
			}
		} elseif ( get_option( 'sp_testimonial_page_id' . $post_ID ) ) {
				delete_option( 'sp_testimonial_page_id' . $post_ID );
		}
		if ( get_option( 'sp_testimonial_page_id0' ) ) {
			delete_option( 'sp_testimonial_page_id0' );
		}
	}

	/**
	 * Delete page shortcode ids array option on save
	 *
	 * @param  int    $post_id current post id.
	 * @param  object $post post Object.
	 * @return void
	 */
	public function form_submission_approval_notification( $post_id, $post ) {
		if ( 'spt_testimonial' === get_post_type( $post_id ) && 'publish' === get_post_status( $post_id ) ) {
			$testimonial_data = get_post_meta( $post_id, 'sp_tpro_meta_options', true );

			if ( ! empty( $testimonial_data['tpro_form_id'] ) && is_numeric( $testimonial_data['tpro_form_id'] ) ) {
				$tpro_testimonial_title  = $post->post_title;
				$tpro_testimonial_text   = $post->post_content;
				$tpro_client_name        = $testimonial_data['tpro_name'];
				$tpro_client_email       = $testimonial_data['tpro_email'];
				$tpro_client_designation = $testimonial_data['tpro_designation'];
				$tpro_company_name       = $testimonial_data['tpro_company_name'];
				$tpro_location           = $testimonial_data['testimonial_clients_location']['tpro_location'];
				$tpro_country            = $testimonial_data['testimonial_clients_location']['tpro_country'];
				$tpro_phone              = $testimonial_data['tpro_phone'];
				$tpro_website            = $testimonial_data['tpro_website'];
				$tpro_video_url          = $testimonial_data['tpro_video_url'];
				$tpro_rating_star        = isset( $testimonial_data['tpro_rating'] ) ? $testimonial_data['tpro_rating'] : '';

				$tpro_category               = '';
				$tpro_client_testimonial_cat = get_the_terms( $post_id, 'testimonial_cat' );
				if ( $tpro_client_testimonial_cat ) {
					foreach ( $tpro_client_testimonial_cat as $testimonial_cat_term ) {
						$tpro_category_name[] = $testimonial_cat_term->name;
					}
					$tpro_category = implode( ', ', $tpro_category_name );
				}

				$tpro_rating = Helper::generate_star_rating( $tpro_rating_star );

				$form_data = get_post_meta( $testimonial_data['tpro_form_id'], 'sp_tpro_form_options', true );
				// Testimonial Approval Notifications.
				$tpro_approval_notification = isset( $form_data['approval_notification'] ) ? $form_data['approval_notification'] : '';
				if ( $tpro_approval_notification && $tpro_client_email && 'publish' !== $form_data['testimonial_approval_status'] ) {

					$email_to         = $tpro_client_email;
					$email_from       = $form_data['approval_email_from']; // mail from.
					$subject          = $form_data['approval_email_subject']; // subject.
					$admin_email      = get_option( 'admin_email' );
					$site_name        = get_bloginfo( 'name' );
					$site_description = '- ' . get_bloginfo( 'description' );
					$message_content  = $form_data['approval_email_body']; // mail body.

					$message = Helper::generate_email_message( $message_content, $site_name, $site_description, $tpro_client_name, $tpro_client_email, $tpro_client_designation, $tpro_company_name, $tpro_location, $tpro_country, $tpro_phone, $tpro_website, $tpro_video_url, $tpro_testimonial_title, $tpro_testimonial_text, $tpro_category, $tpro_rating );

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
					// Send email to.
					$mail_status = wp_mail( $email_to, $subject, $message, $email_header );

					if ( $mail_status ) {
						$testimonial_data['tpro_form_id'] = $testimonial_data['tpro_form_id'] . '|Mail Sent';
						update_post_meta( $post_id, 'sp_tpro_meta_options', $testimonial_data );
					}
				}
			}
		}
	}


	/**
	 * Plugin all Scripts and Styles registering.
	 *
	 * @return void
	 */
	public function register_front_scripts() {
		$setting_options                = get_option( 'sp_testimonial_pro_options' );
		$dequeue_swiper_css             = isset( $setting_options['tpro_dequeue_swiper_css'] ) ? $setting_options['tpro_dequeue_swiper_css'] : true;
		$dequeue_bx_css                 = isset( $setting_options['tpro_dequeue_bx_css'] ) ? $setting_options['tpro_dequeue_bx_css'] : true;
		$dequeue_font_awesome_css       = isset( $setting_options['tpro_dequeue_fa_css'] ) ? $setting_options['tpro_dequeue_fa_css'] : true;
		$dequeue_magnific_popup_css     = isset( $setting_options['tpro_dequeue_magnific_popup_css'] ) ? $setting_options['tpro_dequeue_magnific_popup_css'] : true;
		$tpro_dequeue_magnific_popup_js = isset( $setting_options['tpro_dequeue_magnific_popup_js'] ) ? $setting_options['tpro_dequeue_magnific_popup_js'] : true;
		$dequeue_swiper_js              = isset( $setting_options['tpro_dequeue_swiper_js'] ) ? $setting_options['tpro_dequeue_swiper_js'] : true;
		$dequeue_bx_js                  = isset( $setting_options['tpro_dequeue_bx_js'] ) ? $setting_options['tpro_dequeue_bx_js'] : true;
		$dequeue_isotope_js             = isset( $setting_options['tpro_dequeue_isotope_js'] ) ? $setting_options['tpro_dequeue_isotope_js'] : true;
		$custom_js                      = isset( $setting_options['custom_js'] ) ? $setting_options['custom_js'] : '';

		// CSS Files.
		if ( $dequeue_swiper_css ) {
			wp_register_style( 'tpro-swiper', SP_TPRO_URL . 'Frontend/assets/css/swiper.min.css', array(), SP_TPRO_VERSION );
		}
		if ( $dequeue_bx_css ) {
			wp_register_style( 'tpro-bx_slider', SP_TPRO_URL . 'Frontend/assets/css/bxslider.min.css', array(), SP_TPRO_VERSION );
		}
		if ( $dequeue_font_awesome_css ) {
			wp_register_style( 'tpro-font-awesome', SP_TPRO_URL . 'Frontend/assets/css/font-awesome.min.css', array(), SP_TPRO_VERSION );
		}
		if ( $dequeue_magnific_popup_css ) {
			wp_register_style( 'tpro-magnific-popup', SP_TPRO_URL . 'Frontend/assets/css/magnific-popup.min.css', array(), SP_TPRO_VERSION );
		}
		wp_register_style( 'tpro-remodal', SP_TPRO_URL . 'Frontend/assets/css/remodal.min.css', array(), SP_TPRO_VERSION );
		wp_register_style( 'tpro-fontello', SP_TPRO_URL . 'Admin/assets/css/fontello.min.css', array(), SP_TPRO_VERSION );
		wp_register_style( 'tpro-style', SP_TPRO_URL . 'Frontend/assets/css/style.min.css', array(), SP_TPRO_VERSION );
		wp_register_style( 'tpro-form', SP_TPRO_URL . 'Frontend/assets/css/form.min.css', array(), SP_TPRO_VERSION );
		wp_register_style( 'tpro_tab_and_navigation_icons', SP_TPRO_URL . 'Admin/assets/css/fontello.min.css', array(), SP_TPRO_VERSION );

		// JS Files.
		if ( $dequeue_swiper_js ) {
			wp_register_script( 'tpro-swiper-js', SP_TPRO_URL . 'Frontend/assets/js/swiper.min.js', array( 'jquery' ), SP_TPRO_VERSION, true );
		}
		if ( $dequeue_isotope_js ) {
			wp_register_script( 'tpro-isotope-js', SP_TPRO_URL . 'Frontend/assets/js/jquery.isotope.min.js', array( 'jquery' ), SP_TPRO_VERSION, true );
		}
		wp_register_script( 'tpro-validate-js', SP_TPRO_URL . 'Frontend/assets/js/jquery.validate.min.js', array( 'jquery' ), SP_TPRO_VERSION, true );
		if ( $tpro_dequeue_magnific_popup_js ) {
			wp_register_script( 'tpro-magnific-popup-js', SP_TPRO_URL . 'Frontend/assets/js/jquery.magnific-popup.min.js', array( 'jquery' ), SP_TPRO_VERSION, true );
		}
		wp_register_script( 'tpro-remodal-js', SP_TPRO_URL . 'Frontend/assets/js/remodal.min.js', array( 'jquery' ), SP_TPRO_VERSION, true );
		if ( $dequeue_bx_js ) {
			wp_register_script( 'tpro-bx_slider', SP_TPRO_URL . 'Frontend/assets/js/bxslider.min.js', array( 'jquery' ), SP_TPRO_VERSION, true );
		}
		wp_register_script( 'sprtp_logo_custom_color', SP_TPRO_URL . 'Admin/assets/js/custom-logo-color.min.js', array(), SP_TPRO_VERSION, true );
		wp_register_script( 'tpro-curtail-min-js', SP_TPRO_URL . 'Frontend/assets/js/jquery.curtail.min.js', array( 'jquery' ), SP_TPRO_VERSION, true );
		wp_register_script( 'tpro-chosen-jquery', SP_TPRO_URL . 'Frontend/assets/js/chosen.jquery.min.js', array( 'jquery' ), SP_TPRO_VERSION, true );
		wp_register_script( 'tpro-chosen-config', SP_TPRO_URL . 'Frontend/assets/js/form-config.js', array( 'jquery' ), SP_TPRO_VERSION, true );
		wp_register_script( 'tpro-recaptcha-js', '//www.google.com/recaptcha/api.js', array( 'jquery' ), SP_TPRO_VERSION, true );
		wp_register_script( 'tpro-scripts', SP_TPRO_URL . 'Frontend/assets/js/scripts.min.js', array( 'jquery' ), SP_TPRO_VERSION, true );
		wp_register_script( 'tpro-thumbnail-js', SP_TPRO_URL . 'Frontend/assets/js/thumbnail-slide.js', array( 'jquery' ), SP_TPRO_VERSION, true );
		wp_add_inline_script( 'tpro-scripts', $custom_js );
		wp_localize_script(
			'tpro-scripts',
			'testimonial_vars',
			array(
				'ajax_url'  => admin_url( 'admin-ajax.php' ),
				'nonce'     => wp_create_nonce( 'sp-testimonial-nonce' ),
				'not_found' => __( ' No result found ', 'testimonial-pro' ),
			)
		);
	}

	/**
	 * Plugin Styles.
	 *
	 * @return void
	 */
	public function front_styles() {
		$get_page_data      = Helper::get_page_data();
		$found_generator_id = $get_page_data['generator_id'];
		if ( $found_generator_id ) {
			wp_enqueue_style( 'tpro-remodal' );
			wp_enqueue_style( 'tpro-swiper' );
			wp_enqueue_style( 'tpro-bx_slider' );
			wp_enqueue_style( 'tpro-font-awesome' );
			wp_enqueue_style( 'tpro-magnific-popup' );
			wp_enqueue_style( 'tpro-fontello' );
			wp_enqueue_style( 'tpro-style' );

			// Load dynamic style.
			$dynamic_style = Helper::load_dynamic_style( $found_generator_id );
			// Google font link enqueue.
			$enqueue_fonts = Helper::load_google_fonts( $dynamic_style['typography'] );
			wp_add_inline_style( 'tpro-style', $dynamic_style['dynamic_css'] );
			/**
			 * Google font link enqueue
			 */
			if ( ! empty( $enqueue_fonts ) ) {
				wp_enqueue_style( 'sp-tpro-google-fonts', 'https://fonts.googleapis.com/css?family=' . implode( '|', $enqueue_fonts ), array(), SP_TPRO_VERSION );
			}
		}
	}

	/**
	 * Plugin Scripts and Styles for live preview.
	 *
	 * @return void
	 */
	public function admin_front_scripts() {
		// CSS Files.
		$wpscreen = get_current_screen();
		if ( 'spt_testimonial_form' === $wpscreen->post_type || 'spt_shortcodes' === $wpscreen->post_type || 'spt_testimonial' === $wpscreen->post_type ) {
			wp_enqueue_style( 'tpro-swiper' );
			wp_enqueue_style( 'tpro-bx_slider' );
			wp_enqueue_style( 'tpro-font-awesome' );
			wp_enqueue_style( 'tpro-magnific-popup' );
			wp_enqueue_style( 'tpro-form' );
			wp_enqueue_style( 'tpro-remodal' );
			wp_enqueue_style( 'tpro-fontello' );
			wp_enqueue_style( 'tpro-style' );

			// JS Files.
			wp_enqueue_script( 'tpro-swiper-js' );
			wp_enqueue_script( 'tpro-bx_slider' );
			wp_enqueue_script( 'tpro-isotope-js' );
			wp_enqueue_script( 'tpro-validate-js' );
			wp_enqueue_script( 'tpro-magnific-popup-js' );
			wp_enqueue_script( 'tpro-curtail-min-js' );
			wp_enqueue_script( 'tpro-chosen-jquery' );
			wp_enqueue_script( 'tpro-chosen-config' );
			wp_enqueue_script( 'tpro-recaptcha-js' );
			wp_enqueue_script( 'jquery-masonry' );
			wp_enqueue_script( 'tpro-scripts' );
			wp_localize_script(
				'tpro-scripts',
				'testimonial_vars',
				array(
					'ajax_url'  => admin_url( 'admin-ajax.php' ),
					'nonce'     => wp_create_nonce( 'sp-testimonial-nonce' ),
					'not_found' => __( ' No result found ', 'testimonial-pro' ),
				)
			);

		}
	}

	/**
	 * Shortcode render.
	 *
	 * @param  array $attributes id.
	 * @return statement
	 * @since 2.0
	 */
	public function shortcode_render( $attributes ) {
		$manage_license     = new License( SP_TESTIMONIAL_PRO_FILE, SP_TPRO_VERSION, 'ShapedPlugin', SP_TPRO_STORE_URL, SP_TPRO_ITEM_ID, SP_TPRO_ITEM_SLUG );
		$license_key_status = $manage_license->get_license_status();
		$license_status     = ( is_object( $license_key_status ) && $license_key_status ? $license_key_status->license : '' );
		$if_license_status  = array( 'valid', 'expired' );
		$first_version      = get_option( 'testimonial_pro_first_version' );
		if ( ! in_array( $license_status, $if_license_status ) && 1 === version_compare( $first_version, '2.2.3' ) ) {
			$activation_notice = '';
			if ( current_user_can( apply_filters( 'testimonial_pro_user_role_permission', 'manage_options' ) ) ) {
				$activation_notice = sprintf(
					'<div class="testimonial-pro-license-notice" style="background: #ffebee;color: #444;padding: 18px 16px;border: 1px solid #d0919f;border-radius: 4px;font-size: 18px;line-height: 28px;">Please <strong><a href="%1$s">activate</a></strong> the license key to get the output of the <strong>Real Testimonials Pro</strong> plugin.</div>',
					esc_url( admin_url( 'edit.php?post_type=spt_testimonial&page=tpro_settings#tab=license-key' ) )
				);
			}
			return $activation_notice;
		}

		$post_id = esc_attr( (int) $attributes['id'] );
		// Check the shortcode status.
		if ( empty( $post_id ) || 'spt_shortcodes' !== get_post_type( $post_id ) || 'trash' === get_post_status( $post_id ) ) {
			return;
		}

		ob_start();
		$setting_options = get_option( 'sp_testimonial_pro_options' );
		$shortcode_data  = get_post_meta( $post_id, 'sp_tpro_shortcode_options', true );
		$layout_data     = get_post_meta( $post_id, 'sp_tpro_layout_options', true );
		// Stylesheet loading problem solving here. Shortcode id to push page id option for getting how many shortcode in the page.
		$get_page_data      = Helper::get_page_data();
		$found_generator_id = $get_page_data['generator_id'];
		// This shortcode id not in page id option. Enqueue stylesheets in the shortcode.
		if ( ! is_array( $found_generator_id ) || ! $found_generator_id || ! in_array( $post_id, $found_generator_id ) ) {
			wp_enqueue_style( 'tpro-swiper' );
			wp_enqueue_style( 'tpro-bx_slider' );
			wp_enqueue_style( 'tpro-font-awesome' );
			wp_enqueue_style( 'tpro-magnific-popup' );
			wp_enqueue_style( 'tpro-remodal' );
			wp_enqueue_style( 'tpro-fontello' );
			wp_enqueue_style( 'tpro-style' );

			// Load dynamic style.
			$dynamic_style = Helper::load_dynamic_style( $post_id, $shortcode_data, $layout_data, $setting_options );
			echo '<style id="sp_testimonial_dynamic_css' . esc_attr( $post_id ) . '">' . wp_strip_all_tags( $dynamic_style['dynamic_css'] ) . '</style>';
			// Google font link enqueue.
			$enqueue_fonts = Helper::load_google_fonts( $dynamic_style['typography'] );
			if ( ! empty( $enqueue_fonts ) ) {
				wp_enqueue_style( 'sp-tpro-google-fonts' . esc_attr( $post_id ), 'https://fonts.googleapis.com/css?family=' . implode( '|', $enqueue_fonts ), array(), SP_TPRO_VERSION );
			}
		}

		// Update options if the existing shortcode id option not found.
		Helper::db_options_update( $post_id, $get_page_data );
		$main_section_title = get_the_title( $post_id );
		Helper::sp_tpro_html_show( $post_id, $setting_options, $shortcode_data, $layout_data, $main_section_title );
		return Helper::minify_output( ob_get_clean() );
	}

	/**
	 * Ajax-Search functionality of the plugin.
	 *
	 * @return statement
	 */
	public function search_testimonial() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( wp_unslash( $nonce ), 'sp-testimonial-nonce' ) ) {
			return false;
		}
		$page   = isset( $_POST['page'] ) ? sanitize_text_field( wp_unslash( $_POST['page'] ) ) : '';
		$value  = isset( $_POST['value'] ) ? sanitize_text_field( wp_unslash( $_POST['value'] ) ) : '';
		$group  = isset( $_POST['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : '';
		$rating = isset( $_POST['rating'] ) ? sanitize_text_field( wp_unslash( $_POST['rating'] ) ) : '';
		if ( 'all' === $group ) {
			$group = '';
		}
		if ( 'all' === $rating ) {
			$rating = '';
		}
		$post_id         = isset( $_POST['generator_id'] ) ? sanitize_text_field( wp_unslash( $_POST['generator_id'] ) ) : '';
		$setting_options = get_option( 'sp_testimonial_pro_options' );
		$shortcode_data  = get_post_meta( $post_id, 'sp_tpro_shortcode_options', true );
		$layout_data     = get_post_meta( $post_id, 'sp_tpro_layout_options', true );

		$grid_pagination           = isset( $shortcode_data['grid_pagination'] ) ? $shortcode_data['grid_pagination'] : '';
		$layout                    = isset( $layout_data['layout'] ) ? $layout_data['layout'] : 'slider';
		$carousel_mode             = isset( $layout_data['carousel_mode'] ) ? $layout_data['carousel_mode'] : 'standard';
		$slider_mode               = 'thumbnail_slider' === $layout ? 'thumbnail_slider' : 'standard';
		$testimonial_query_and_ids = Helper::testimonial_query( $shortcode_data, $layout_data, $post_id, $value, $group, $rating, $page );
		$testimonial_items         = Helper::testimonial_items( $testimonial_query_and_ids, $shortcode_data, $layout, $slider_mode, $carousel_mode, $post_id, $page );
		echo $testimonial_items['output'];
		$post_query = $testimonial_query_and_ids['query'];
		echo "<div class='filter-pagination hidden'>";
		if ( $grid_pagination && $post_query->max_num_pages > 1 ) {
			$paged_var  = 'paged' . $post_id;
			$args       = array(
				'format'    => '?paged' . $post_id . '=%#%',
				'total'     => $post_query->max_num_pages,
				'current'   => isset( $_GET[ "$paged_var" ] ) ? sanitize_text_field( wp_unslash( $_GET[ "$paged_var" ] ) ) : 1,
				'prev_next' => false,
				'next_text' => '<i class="fa fa-angle-right"></i>',
				'prev_text' => '<i class="fa fa-angle-left"></i>',
				'type'      => 'array',
				'show_all'  => true,
			);
			$page_links = (array) paginate_links( $args );
			$prev_link  = '<a class="next page-numbers" href="#"><i class="fa fa-angle-right"></i></a>';
			$next_link  = '<a class="prev page-numbers current" href="#"><i class="fa fa-angle-left"></i></a>';
			array_unshift( $page_links, $next_link );
			$page_links[] = $prev_link;
			$html         = '';
			$p_num        = 0;
			foreach ( $page_links as $page_link ) {
				$class = 'page-numbers ';
				if ( strpos( $page_link, 'current' ) !== false ) {
					$class .= 'current ';
				}
				if ( strpos( $page_link, 'next' ) !== false ) {
					$data_page = 'data-page="next"';
					$class    .= 'next ';
				} elseif ( strpos( $page_link, 'prev' ) !== false ) {
					$data_page = 'data-page="prev"';
					$class    .= ' prev';
				} else {
					$data_page = 'data-page="' . $p_num . '"';
				}
				$page_link = preg_replace( '/<span[^>]*>/', '<a href="#" class="' . $class . '" ' . $data_page . '>', $page_link );
				$page_link = preg_replace( '/<a [^>]*>/', '<a href="#" class="' . $class . '" ' . $data_page . '>', $page_link );
				$page_link = str_replace( '</span>', '</a>', $page_link );
				$html     .= $page_link;
				++$p_num;
			}
			echo $html;
			echo '</div>';
		}
			wp_die();
	}

	/**
	 * Frontend form.
	 *
	 * @param array $attributes attributes.
	 *
	 * @return string
	 * @since 2.0
	 */
	public function frontend_form( $attributes ) {
		$form_id = esc_attr( (int) $attributes['id'] );
		if ( empty( $form_id ) || 'spt_testimonial_form' !== get_post_type( $form_id ) || 'trash' === get_post_status( $form_id ) ) {
			return;
		}
		$setting_options = get_option( 'sp_testimonial_pro_options' );
		$form_elements   = get_post_meta( $form_id, 'sp_tpro_form_elements_options', true );
		$form_data       = get_post_meta( $form_id, 'sp_tpro_form_options', true );
		if ( ! is_array( $form_data ) ) {
			return;
		}
		wp_enqueue_script( 'tpro-validate-js' );
		ob_start();
		Helper::frontend_form_html( $form_id, $setting_options, $form_elements, $form_data );
		return ob_get_clean();
	}
}
