<?php
// If uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
  die;
}

// Delete all plugin options
$options = [
  'hostbox_recaptcha_site_key',
  'hostbox_recaptcha_secret_key',
  'hostbox_recaptcha_version',
  'hostbox_recaptcha_min_score',
];

// Loop through each option and delete it
foreach ($options as $option) {
  delete_option($option);
}

// Clean up multisite if needed
if (is_multisite()) {
  $sites = get_sites();
  foreach ($sites as $site) {
    switch_to_blog($site->blog_id);

    // Delete options for each site
    foreach ($options as $option) {
      delete_option($option);
    }

    restore_current_blog();
  }
}

// Clear any cached data
wp_cache_flush();
