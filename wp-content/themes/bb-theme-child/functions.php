<?php
/**
 * Beaver Builder Child Theme
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * https://docs.wpbeaverbuilder.com/
 * @version 1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
  exit; 
}


add_action(
  'wp_enqueue_scripts',
  function () {
    wp_enqueue_style(
      'child-style',
      get_stylesheet_uri(),
      array( 'fl-automator-skin' ),
      wp_get_theme()->get( 'Version' )
    );
  }
);

add_action(
  'wp_enqueue_scripts',
  function () {
    $signup_js = get_stylesheet_directory() . '/js/signup-a-suite.js';
    if ( ! file_exists( $signup_js ) ) {
      return;
    }

    $deps = wp_script_is( 'sweetalert2', 'registered' ) ? array( 'sweetalert2' ) : array();

    wp_enqueue_script(
      'bb-child-signup-a-suite',
      get_stylesheet_directory_uri() . '/js/signup-a-suite.js',
      $deps,
      (string) filemtime( $signup_js ),
      true
    );

    wp_localize_script(
      'bb-child-signup-a-suite',
      'legacySignupSuiteConfig',
      array(
        'autoOpenOnHome' => is_front_page(),
      )
    );
  },
  25
);


/**
 * Render the SignUp A Suite contact form.
 *
 * @param array $args Optional. form_id, field_prefix, nonce, show_heading, discount_value.
 */
function bb_child_render_signup_suite_form( $args = array() ) {
  $defaults = array(
    'form_id'        => 'signup-a-suite-form',
    'field_prefix'   => 'signup',
    'nonce'          => wp_create_nonce( 'signup_a_suite_contact' ),
    'show_heading'   => false,
    'show_intro'     => false,
    'discount_value' => '',
  );

  $args = wp_parse_args( $args, $defaults );

  $partial = get_stylesheet_directory() . '/custom-templates/partials/signup-a-suite-form.php';
  if ( ! file_exists( $partial ) ) {
    return;
  }

  // Expose variables to the partial.
  $form_id        = $args['form_id'];
  $field_prefix   = $args['field_prefix'];
  $nonce          = $args['nonce'];
  $show_heading   = (bool) $args['show_heading'];
  $show_intro     = ! empty( $args['show_intro'] );
  $discount_value = (string) $args['discount_value'];
  $form_source    = isset( $args['form_source'] ) ? (string) $args['form_source'] : 'signup_form';

  include $partial;
}

/**
 * Service sections for location stylist directory pages.
 *
 * @return array<int, array{id: string, title: string, slugs: string[]}>
 */
function bb_child_get_location_stylist_sections() {
  return array(
    array(
      'id'    => 'hair-stylists',
      'title' => __( 'Hair Stylists', 'bb-theme-child' ),
      'slugs' => array( 'hair', 'barber', 'braider' ),
    ),
    array(
      'id'    => 'nail-technicians',
      'title' => __( 'Nail Technicians', 'bb-theme-child' ),
      'slugs' => array( 'nails' ),
    ),
    array(
      'id'    => 'estheticians',
      'title' => __( 'Estheticians', 'bb-theme-child' ),
      'slugs' => array( 'skin', 'waxing', 'medspa', 'wellness' ),
    ),
    array(
      'id'    => 'lash-artists',
      'title' => __( 'Lash Artists', 'bb-theme-child' ),
      'slugs' => array( 'lashes', 'permanent-make-up' ),
    ),
  );
}

/**
 * Render stylists for a location, grouped by service category.
 *
 * @param array $args location_slug (string|string[]), location_label, page_heading.
 */
function bb_child_render_location_stylists_directory( $args = array() ) {
  $defaults = array(
    'location_slug'  => '',
    'location_label' => '',
    'page_heading'   => __( 'Salon Suites for Beauty Professionals in Arlington TX', 'bb-theme-child' ),
  );

  $args = wp_parse_args( $args, $defaults );

  $location_slugs = is_array( $args['location_slug'] )
    ? array_values( array_filter( array_map( 'sanitize_title', $args['location_slug'] ) ) )
    : array_values( array_filter( array( sanitize_title( (string) $args['location_slug'] ) ) ) );

  if ( empty( $location_slugs ) ) {
    return;
  }

  if ( '' === $args['location_label'] ) {
    $args['location_label'] = ucwords( str_replace( array( '-', '_' ), ' ', $location_slugs[0] ) );
  }

  $location_slug  = $location_slugs;
  $location_label = (string) $args['location_label'];
  $page_heading   = (string) $args['page_heading'];

  $partial = get_stylesheet_directory() . '/custom-templates/partials/location-stylists-directory.php';
  if ( ! file_exists( $partial ) ) {
    return;
  }

  include $partial;
}

/**
 * Popup modal with SignUp A Suite form (opened from top bar).
 */
function bb_child_render_signup_suite_modal() {
  if ( is_admin() ) {
    return;
  }

  $nonce = wp_create_nonce( 'signup_a_suite_contact' );
  ?>
  <div id="signup-a-suite-modal" class="signup-a-suite-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="signup-a-suite-modal-title">
    <div class="signup-a-suite-modal__backdrop" data-signup-modal-close></div>
    <div class="signup-a-suite-modal__dialog">
     <div class="signup-a-suite-modal__header">
        <h2 id="signup-a-suite-modal-title" class="signup-a-suite-heading"><?php esc_html_e( 'Find A Suite', 'bb-theme-child' ); ?></h2>
        <button type="button" class="signup-a-suite-modal__close" data-signup-modal-close aria-label="<?php esc_attr_e( 'Close', 'bb-theme-child' ); ?>">
          <span class="signup-a-suite-modal__close-icon" aria-hidden="true"></span>
        </button>
      </div>
      <div class="signup-a-suite-modal__body">
        <?php
        bb_child_render_signup_suite_form(
          array(
            'form_id'        => 'signup-a-suite-popup-form',
            'field_prefix'   => 'signup-popup',
            'nonce'          => $nonce,
            'show_heading'   => false,
            'show_intro'     => true,
            'discount_value' => '',
            'form_source'    => 'topbar_popup',
          )
        );
        ?>
      </div>
    </div>
  </div>
  <?php
}
add_action( 'wp_footer', 'bb_child_render_signup_suite_modal', 5 );

