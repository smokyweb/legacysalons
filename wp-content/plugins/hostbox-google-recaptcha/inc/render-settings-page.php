<?php
/**
 * Admin settings page template for Hostbox reCAPTCHA plugin
 */

// Prevent direct access
if (!defined('ABSPATH')) {
  exit;
}

?>
<div class="wrap">
  <h2><?php esc_html_e('reCAPTCHA Settings', 'hostbox-google-recaptcha'); ?></h2>
  <form method="post" action="options.php">
<?php
settings_fields('hostbox_recaptcha_settings');
do_settings_sections('hostbox_recaptcha_settings');
?>
    <table class="form-table">
      <tr>
        <th><?php esc_html_e('Version', 'hostbox-google-recaptcha'); ?></th>
        <td>
          <select name="hostbox_recaptcha_version">
            <option value="v2" <?php selected($this->version, 'v2'); ?>>
              <?php esc_html_e('reCAPTCHA v2', 'hostbox-google-recaptcha'); ?>
            </option>
            <option value="v3" <?php selected($this->version, 'v3'); ?>>
              <?php esc_html_e('reCAPTCHA v3', 'hostbox-google-recaptcha'); ?>
            </option>
          </select>
        </td>
      </tr>
      <tr>
        <th><?php esc_html_e('Site Key', 'hostbox-google-recaptcha'); ?>*</th>
        <td>
          <input type="text" name="hostbox_recaptcha_site_key"
            value="<?php echo esc_attr($this->site_key); ?>" class="regular-text">
        </td>
      </tr>
      <tr>
        <th><?php esc_html_e('Secret Key', 'hostbox-google-recaptcha'); ?>*</th>
        <td>
          <input type="text" name="hostbox_recaptcha_secret_key"
            value="<?php echo esc_attr($this->secret_key); ?>" class="regular-text">
        </td>
      </tr>
    </table>
    <hr />
    <table class="form-table">
      <tr class="v3-setting">
        <th><?php esc_html_e('Minimum Score', 'hostbox-google-recaptcha'); ?></th>
        <td>
          <select name="hostbox_recaptcha_min_score">
<?php
$scores = ['0.1', '0.2', '0.3', '0.4', '0.5', '0.6', '0.7', '0.8', '0.9'];
foreach ($scores as $score) {
  echo '<option value="' . esc_attr($score) . '" ' .
  selected($this->min_score, $score, false) . '>' .
  esc_html($score) . '</option>';
}
?>
          </select>
          <p class="description">
            <?php esc_html_e('Higher scores are more strict (0.1 - 0.9)', 'hostbox-google-recaptcha'); ?>
          </p>
        </td>
      </tr>
    </table>
    <?php submit_button(); ?>
  </form>
  <div>
    <p>
      <?php printf(
        /* translators: %1$s and %2$s are HTML links */
        esc_html__('%1$s provides blazing fast WordPress hosting services - you can learn more at %2$s.', 'hostbox-google-recaptcha'),
        '<a href="https://www.hostbox.me/" target="_blank">Hostbox</a>',
        '<a href="https://www.hostbox.me/">www.hostbox.me</a>'
      ); ?>
    </p>
  </div>
</div>
