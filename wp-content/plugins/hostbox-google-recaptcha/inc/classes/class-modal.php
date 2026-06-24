<?php
if (! defined('ABSPATH')) {
  exit;
}

class Hostbox_Recaptcha_Modal {
  private static $instance;
  private $modals = [];
  public $dismiss_action = 'hostbox-modal-dismiss';
  public $onboarding_list_url = 'https://emailoctopus.com/api/1.6/lists/fc474aa8-d3a9-11ef-a3cf-b188a61259ff/contacts';
  public $api_key = 'eo_86d09e7e27e92275ccd0cf90648e8baeec84d9e960b5ffaf1537daf02efeacc7';

  public $action_onboarding = 'hostbox-recaptcha-modal-submit-onboarding';

  public static function instance() {
    if (is_null(self::$instance)) {
      self::$instance = new self();
    }

    self::$instance->hook();

    return self::$instance;
  }

  public function hook() {
    add_action("wp_ajax_{$this->dismiss_action}", [$this, 'dismiss_modal']);
    add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
    add_action('in_admin_footer', [$this, 'output_modal_area']);
    add_action('admin_init', [$this, 'check_activation']);
    add_action("wp_ajax_{$this->action_onboarding}", [$this, 'ajax_action_onboarding']);
  }

  public function admin_enqueue_scripts() {
    $asset_file = require(hostbox_recaptcha_get_include_folder() . '/assets/jsx/build/modal.asset.php');
    $dependencies = array_merge([
      'wp-components',
    ], $asset_file['dependencies']);

    wp_enqueue_style('wp-components');

    wp_enqueue_script(
      'hostbox-recaptcha-modal',
      hostbox_recaptcha_get_plugin_url() . 'inc/assets/jsx/build/modal.js',
      $dependencies,
      $asset_file['version'],
      true
    );

    wp_register_style(
      'hostbox-recaptcha-modal',
      hostbox_recaptcha_get_plugin_url() . 'inc/assets/css/modal.css',
      ['wp-components'],
      $asset_file['version']
    );

    wp_enqueue_style(
      'hostbox-recaptcha-modal',
      hostbox_recaptcha_get_plugin_url() . 'inc/assets/css/modal.css',
      ['hostbox-recaptcha-modal'],
      $asset_file['version']
    );

    wp_localize_script('hostbox-recaptcha-modal', '_hostboxModalSettings', $this->get_script_settings());

    $deps[] = 'hostbox-recaptcha-modal';

    return $deps;
  }

  public function ajax_action_onboarding() {
    // Verify nonce
    if (!check_ajax_referer($this->action_onboarding, 'nonce', false)) {
      wp_send_json_error(['message' => 'Invalid security token']);
      return;
    }

    // Unslash and sanitize email
    $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    $email = trim($email);

    if (empty($email)) {
      wp_send_json_error(['message' => 'Email is required']);
      return;
    }

    // Submit to EmailOctopus with updated API format
    $response = $this->submit_to_emailoctopus($email);

    if (is_wp_error($response)) {
      wp_send_json_error(['message' => $response->get_error_message()]);
      return;
    }

    wp_send_json_success(['message' => 'Successfully subscribed']);
  }

  private function submit_to_emailoctopus($email) {
    $payload = [
      'api_key' => $this->api_key,
      'email_address' => $email,
      'status' => 'SUBSCRIBED'
    ];

    $args = [
      'body' => wp_json_encode($payload),
      'headers' => [
        'Content-Type' => 'application/json'
      ],
      'timeout' => 500,
      'method' => 'POST'
    ];

    $response = wp_remote_post($this->onboarding_list_url, $args);

    if (is_wp_error($response)) {
      return $response;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code !== 200) {
      return new WP_Error(
        'emailoctopus_error',
        sprintf('EmailOctopus API error: %s', $response_body)
      );
    }

    return json_decode($response_body);
  }

  public function get_script_settings() {
    $settings = [
      'actionModalDismiss' => $this->dismiss_action,
      'pluginURL' => hostbox_recaptcha_get_plugin_url(),
      'isJustActivated' => (bool) get_option('hostbox_recaptcha_just_activated'),
      'onboardingModalAction' => $this->action_onboarding,
      'onboardingModalNonce' => wp_create_nonce($this->action_onboarding),
      'onboardingListUrl' => $this->onboarding_list_url
    ];

    $settings = apply_filters('hostbox_recaptch_modal_dashboard_settings', $settings);

    return $settings;
  }

  public function output_modal_area() {
    ?>
	    <div id="wp-modal-area"></div>
	  <?php
  }

  public function activate() {
    add_option('hostbox_recaptcha_just_activated', true);
  }

  public function check_activation() {
    $asset_file = require(hostbox_recaptcha_get_include_folder() . '/assets/jsx/build/modal.asset.php');

    if (get_option('hostbox_recaptcha_just_activated')) {
      delete_option('hostbox_recaptcha_just_activated');
      wp_enqueue_script(
        'hostbox-recaptcha-activation',
        hostbox_recaptcha_get_plugin_url() . 'inc/assets/js/activation.js',
        ['hostbox-recaptcha-modal'],
        $asset_file['version'],
        true
      );
    }
  }

  public function dismiss_modal() {
    if (!current_user_can('manage_options')) {
      wp_die(-1);
    }

    // Verify nonce
    if (!check_ajax_referer($this->dismiss_action, 'nonce', false)) {
      wp_send_json_error(['message' => 'Invalid security token']);
      return;
    }

    $modal_id = isset($_POST['id']) ? sanitize_text_field(wp_unslash($_POST['id'])) : '';

    if ($modal_id) {
      update_option("hostbox_recaptcha_modal_dismissed_{$modal_id}", true);
    }

    wp_die();
  }

  public function cleanup_activation() {
    delete_option('hostbox_recaptcha_just_activated');
  }
}

if (!function_exists('hostbox_recaptcha_get_dashboard_modals')):
  function hostbox_recaptcha_get_dashboard_modals() {
    return Hostbox_Recaptcha_Modal::instance();
  }
endif;

if (defined('ABSPATH') && !function_exists('hostbox_recaptcha_init_modals')) {
  function hostbox_recaptcha_init_modals() {
    hostbox_recaptcha_get_dashboard_modals();
  }
  hostbox_recaptcha_init_modals();
}

