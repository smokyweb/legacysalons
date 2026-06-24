<?php
/*
Plugin Name: Hostbox Google reCAPTCHA
Description: Adds Google reCAPTCHA (v2 or v3) to WordPress, compatible with WooCommerce and Contact Form 7.
Version: 0.0.10
Author: Hostbox
Author URI: https://www.hostbox.me
Text Domain: hostbox-google-recaptcha
Domain Path: /languages
License: GPLv2 or later
*/

if (!defined('ABSPATH')) {
  exit;
}

require_once plugin_dir_path(__FILE__) . 'inc/classes/class-modal.php';

class Hostbox_Recaptcha {
  private $site_key;
  private $secret_key;
  private $version;
  private $min_score;
  private $plugin_path;
  private $plugin_url;

  public function __construct() {
    // Set plugin paths
    $this->plugin_path = plugin_dir_path(__FILE__);
    $this->plugin_url = plugin_dir_url(__FILE__);

    // Initialize settings
    $this->site_key = get_option('hostbox_recaptcha_site_key', '');
    $this->secret_key = get_option('hostbox_recaptcha_secret_key', '');
    $this->version = get_option('hostbox_recaptcha_version', 'v2');
    $this->min_score = get_option('hostbox_recaptcha_min_score', '0.5');

    // Add hooks
    add_action('init', [$this, 'init']);
    add_action('admin_menu', [$this, 'add_settings_page']);
    add_action('admin_init', [$this, 'register_settings']);
    add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
    add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_scripts']);
    add_action('login_enqueue_scripts', [$this, 'enqueue_login_scripts']);

    // reCAPTCHA
    add_action('woocommerce_login_form', [$this, 'add_recaptcha']);
    add_action('woocommerce_register_form', [$this, 'add_recaptcha']);
    add_action('woocommerce_lostpassword_form', [$this, 'add_recaptcha']);
    add_action('woocommerce_after_checkout_billing_form', [$this, 'add_recaptcha']);

    add_action('comment_form', [$this, 'add_recaptcha']);
    add_action('login_form', [$this, 'add_recaptcha']);
    add_action('register_form', [$this, 'add_recaptcha']);
    add_action('lostpassword_form', [$this, 'add_recaptcha']);

    add_filter('woocommerce_process_login_errors', [$this, 'verify_wc_recaptcha'], 10, 3);
    add_filter('woocommerce_process_registration_errors', [$this, 'verify_wc_recaptcha'], 10, 4);
    add_action('woocommerce_lost_password_validation', [$this, 'verify_wc_recaptcha']);
    add_action('woocommerce_checkout_process', [$this, 'verify_wc_recaptcha']);

    add_filter('preprocess_comment', [$this, 'verify_comment_captcha'], 10, 2);
    add_filter('authenticate', [$this, 'verify_login_captcha'], 10, 3);
    add_filter('registration_errors', [$this, 'verify_registration_resetpwd_captcha'], 10, 2);
    add_filter('lostpassword_post', [$this, 'verify_registration_resetpwd_captcha'], 10, 1);

    add_filter('wpcf7_form_elements', [$this, 'add_recaptcha_to_cf7']);
    add_filter('wpcf7_validate', [$this, 'verify_cf7_captcha'], 10, 2);

    add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'add_settings_link']);
  }

  /**
   * Initialize plugin
   */
  public function init() {
    // Register scripts and styles
    $this->register_scripts_and_styles();
  }

  /**
   * Add settings link to plugin page
   */
  public function add_settings_link($links) {
    $settings_link = '<a href="' . admin_url('options-general.php?page=hostbox-recaptcha-settings') . '">' . __('Settings', 'hostbox-google-recaptcha') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
  }

  /**
   * Add the settings page to WordPress admin menu
   */
  public function add_settings_page() {
    add_options_page(
      __('reCAPTCHA Settings', 'hostbox-google-recaptcha'),      // Page title
      __('reCAPTCHA', 'hostbox-google-recaptcha'),               // Menu title
      'manage_options',                                          // Capability required
      'hostbox-recaptcha-settings',                              // Menu slug
      [$this, 'render_settings_page']                            // Callback to render the page
    );
  }

  /**
   * Register all scripts and styles
   */
  public function register_settings() {
    register_setting('hostbox_recaptcha_settings', 'hostbox_recaptcha_site_key', 'sanitize_text_field');
    register_setting('hostbox_recaptcha_settings', 'hostbox_recaptcha_secret_key', 'sanitize_text_field');
    register_setting('hostbox_recaptcha_settings', 'hostbox_recaptcha_version', 'sanitize_text_field');
    register_setting('hostbox_recaptcha_settings', 'hostbox_recaptcha_min_score', 'sanitize_text_field');
  }

  public function register_scripts_and_styles() {
    // Register frontend styles
    wp_register_style(
      'hostbox-recaptcha-styles',
      $this->plugin_url . 'css/recaptcha.css',
      [],
      '0.0.7'
    );

    // Register Google reCAPTCHA script
    $recaptcha_url = $this->version === 'v3'
        ? "https://www.google.com/recaptcha/api.js?render={$this->site_key}"
        : 'https://www.google.com/recaptcha/api.js';

    wp_register_script(
      'google-recaptcha',
      $recaptcha_url,
      [],
      '0.0.7',
      ['strategy' => 'defer'],
    );

    // Register main handler script
    wp_register_script(
      'hostbox-recaptcha-handler',
      $this->plugin_url . 'js/recaptcha.js',
      ['jquery', 'google-recaptcha'],
      '0.0.7',
      true
    );

    // Register admin script
    wp_register_script(
      'hostbox-recaptcha-admin',
      $this->plugin_url . 'js/admin.js',
      ['jquery'],
      '0.0.7',
      true
    );
  }

  /**
   * Enqueue frontend scripts and styles
   */
  public function enqueue_frontend_scripts() {
    if (empty($this->site_key)) {
      return;
    }

    // Enqueue frontend styles
    wp_enqueue_style('hostbox-recaptcha-styles');

    // Enqueue reCAPTCHA scripts
    wp_enqueue_script('google-recaptcha');
    wp_enqueue_script('hostbox-recaptcha-handler');

    // Localize script
    wp_localize_script('hostbox-recaptcha-handler', 'recaptchaVars', [
      'siteKey' => $this->site_key,
      'version' => $this->version,
      'ajaxurl' => admin_url('admin-ajax.php'),
      'nonce' => wp_create_nonce('hostbox-recaptcha-nonce')
    ]);
  }

  /**
   * Enqueue login page scripts and styles
   */
  public function enqueue_login_scripts() {
    if (empty($this->site_key)) {
      return;
    }

    $this->enqueue_frontend_scripts();
  }

  /**
   * Enqueue admin scripts and styles
   */
  public function enqueue_admin_scripts($hook) {
    // Only load on plugin settings page
    if ('settings_page_hostbox-recaptcha-settings' !== $hook) {
      return;
    }

    // Enqueue admin script
    wp_enqueue_script('hostbox-recaptcha-admin');
  }

  /**
   * Render the settings page
   */
  public function render_settings_page() {
    if (!current_user_can('manage_options')) {
      wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'hostbox-google-recaptcha'));
    }

    // Include settings template
    require_once $this->plugin_path . 'inc/render-settings-page.php';
  }

  /**
   * Add reCAPTCHA
   */
  public function add_recaptcha() {
    if (empty($this->site_key)) {
      return;
    }

    wp_nonce_field('hostbox_recaptcha_action', 'hostbox_recaptcha_nonce');

    if ($this->version === 'v2') {
      echo '<div class="g-recaptcha" data-sitekey="' . esc_attr($this->site_key) . '"></div>';
    } else {
      echo '<input type="hidden" name="recaptcha_response" class="recaptcha-v3-response" value="">';
    }
  }

  /**
   * Verify WooCommerce login, registration, and lost password captcha
   */
  public function verify_wc_recaptcha($error) {
    if (empty($this->site_key)) {
      return $error;
    }

    if (!$this->verify_recaptcha()) {
      return new WP_Error('captcha_error', $this->verify_recaptcha_error());
    }

    return $error;
  }

  /**
   * Verify reCAPTCHA for comments
   */
  public function verify_comment_captcha($commentdata) {
    if (empty($this->site_key)) {
      return $commentdata;
    }

    if (!$this->verify_recaptcha()) {
      wp_die(esc_html($this->verify_recaptcha_error()), 'Captcha Verification Failed', ['response' => 403, 'back_link' => true]);
    }

    return $commentdata;
  }

  /**
   * Add reCAPTCHA to Contact Form 7
   */
  public function add_recaptcha_to_cf7($elements) {
    if (empty($this->site_key)) {
      return $elements;
    }

    ob_start();
    $this->add_recaptcha();
    $recaptcha_html = ob_get_clean();

    return $elements . $recaptcha_html;
  }

  /**
   * Verify reCAPTCHA for Contact Form 7
   */
  public function verify_cf7_captcha($result, $tags) {
    if (empty($this->site_key)) {
      return $result;
    }
    // Skip nonce verification only for CF7
    $_POST['hostbox_recaptcha_nonce'] = wp_create_nonce('hostbox_recaptcha_action');

    $verified = $this->verify_recaptcha();
    if (!$verified) {
      $result->invalidate('', $this->verify_recaptcha_error());
    }

    return $result;
  }

  /**
   * Verify reCAPTCHA for registration
   */
  public function verify_registration_resetpwd_captcha($errors) {
    if (empty($this->site_key)) {
      return $errors;
    }

    if (!$this->verify_recaptcha()) {
      $errors->add('captcha_error', $this->verify_recaptcha_error());
    }

    return $errors;
  }

  /**
   * Verify reCAPTCHA for login
   */
  public function verify_login_captcha($user, $username, $password) {
    if (empty($this->site_key)) {
      return $user;
    }

    if (isset($_POST['woocommerce-login-nonce'])) {
      if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['woocommerce-login-nonce'], 'woocommerce-login')))) {
        return;
      }
      return $user;
    }

    if (!$this->verify_recaptcha()) {
      remove_action('authenticate', 'wp_authenticate_username_password', 20);
      return new WP_Error('captcha_error', $this->verify_recaptcha_error());
    }

    return $user;
  }

  /**
   * Verify reCAPTCHA response
   */
  private function verify_recaptcha() {
    if (empty($this->secret_key)) {
      return false;
    }

    // Get the appropriate response token based on version
    $response_token = '';
    if ($this->version === 'v3') {
      $response_token = isset($_POST['recaptcha_response']) ? sanitize_text_field(wp_unslash($_POST['recaptcha_response'])) : '';
    } else {
      $response_token = isset($_POST['g-recaptcha-response']) ? sanitize_text_field(wp_unslash($_POST['g-recaptcha-response'])) : '';
    }

    if (empty($response_token) && !empty($_POST)) {
      return false;
    }

    // Verify nonce first
    if (!isset($_POST['hostbox_recaptcha_nonce']) ||
        !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['hostbox_recaptcha_nonce'])), 'hostbox_recaptcha_action')) {
      return false;
    }

    $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
      'body' => [
        'secret' => $this->secret_key,
        'response' => $response_token
      ]
    ]);

    if (is_wp_error($response)) {
      return false;
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);

    if ($this->version === 'v3') {
      return isset($body['success']) && $body['success'] &&
             isset($body['score']) && $body['score'] >= floatval($this->min_score);
    }

    return isset($body['success']) && $body['success'];
  }

  public function verify_recaptcha_error() {
    return esc_html__('reCAPTCHA verification failed. Please try again.', 'hostbox-google-recaptcha');
  }

  public static function activate() {
    // Activate WP Modal
    if (method_exists('Hostbox_Recaptcha_Modal', 'instance')) {
      Hostbox_Recaptcha_Modal::instance()->activate();
    }
  }

  /**
   * Plugin deactivation handler
   */
  public static function deactivate() {
    // Clean up WP Modal
    if (method_exists('Hostbox_Recaptcha_Modal', 'instance')) {
      Hostbox_Recaptcha_Modal::instance()->cleanup_activation();
    }
  }
}

// Initialize the plugin
$hostbox_recaptcha = new Hostbox_Recaptcha();

if (! function_exists('hostbox_recaptcha_get_plugin_url')):
  /**
   * Get the plugin url.
   *
   * @return string The url of the plugin.
   */
  function hostbox_recaptcha_get_plugin_url() {
    return plugin_dir_url(__FILE__);
  }
endif;

if (! function_exists('hostbox_recaptcha_get_include_folder')):
  /**
   * Get the path of the PHP include folder.
   *
   * @return string The path of the PHP include folder.
   */
  function hostbox_recaptcha_get_include_folder() {
    return dirname(__FILE__) . '/inc';
  }
endif;

register_activation_hook(__FILE__, ['Hostbox_Recaptcha', 'activate']);
register_deactivation_hook(__FILE__, ['Hostbox_Recaptcha', 'deactivate']);

