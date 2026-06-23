<?php
/**
 * SignUp A Suite form partial.
 *
 * @var string $form_id
 * @var string $field_prefix
 * @var string $nonce
 * @var bool   $show_heading
 * @var string $discount_value
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$form_id        = isset( $form_id ) ? $form_id : 'signup-a-suite-form';
$field_prefix   = isset( $field_prefix ) ? $field_prefix : 'signup';
$nonce          = isset( $nonce ) ? $nonce : '';
$show_heading   = ! empty( $show_heading );
$discount_value = isset( $discount_value ) ? (string) $discount_value : '';

$location_options = array(
    '' => __( 'Select preferred location...', 'bb-theme-child' ),
);

if ( function_exists( 'signup_a_suite_get_location_labels' ) ) {
	$location_options = array_merge( $location_options, signup_a_suite_get_location_labels() );
} else {
	$location_options['little_road'] = __( 'Little Road', 'bb-theme-child' );
	$location_options['cooper']      = __( 'Cooper', 'bb-theme-child' );
}

$profession_service_options = array();

if ( function_exists( 'signup_a_suite_get_profession_service_options' ) ) {
	$profession_service_options = signup_a_suite_get_profession_service_options();
}
?>

<?php if ( $show_heading ) : ?>
	<h2 class="signup-a-suite-heading"><?php esc_html_e( 'SignUp For A Suite', 'bb-theme-child' ); ?></h2>
<?php endif; ?>

<div class="left">
<?php if ( $show_heading || ! empty( $show_intro ) ) : ?>
	<p class="signup-a-suite-subheading">
		<?php esc_html_e( 'Ready to grow your business in a private salon suite? Fill out the form below and our team will reach out to help you find the perfect space.', 'bb-theme-child' ); ?>
	</p>
<?php endif; ?>

<p class="signup-a-suite-discount-note" data-signup-discount-note hidden></p>

<form id="<?php echo esc_attr( $form_id ); ?>" class="signup-a-suite-form" method="post" novalidate>
    
   <div class="gridWrap">     
    
	<input type="hidden" name="action" value="signup_a_suite_contact" />
	<input type="hidden" name="nonce" value="<?php echo esc_attr( $nonce ); ?>" />
	<input type="hidden" name="discount_percentage" value="<?php echo esc_attr( $discount_value ); ?>" data-signup-discount-input />
	<input type="hidden" name="source" value="<?php echo esc_attr( isset( $form_source ) ? $form_source : 'signup_form' ); ?>" />

	<div class="signup-a-suite-field">
		<label for="<?php echo esc_attr( $field_prefix ); ?>-first-name"><?php esc_html_e( 'First Name', 'bb-theme-child' ); ?></label>
		<input type="text" id="<?php echo esc_attr( $field_prefix ); ?>-first-name" name="first_name" placeholder="<?php esc_attr_e( 'First Name', 'bb-theme-child' ); ?>" required autocomplete="given-name" />
	</div>

	<div class="signup-a-suite-field">
		<label for="<?php echo esc_attr( $field_prefix ); ?>-last-name"><?php esc_html_e( 'Last Name', 'bb-theme-child' ); ?></label>
		<input type="text" id="<?php echo esc_attr( $field_prefix ); ?>-last-name" name="last_name" placeholder="<?php esc_attr_e( 'Last Name', 'bb-theme-child' ); ?>" required autocomplete="family-name" />
	</div>

	<div class="signup-a-suite-field">
		<label for="<?php echo esc_attr( $field_prefix ); ?>-email"><?php esc_html_e( 'Email', 'bb-theme-child' ); ?></label>
		<input type="email" id="<?php echo esc_attr( $field_prefix ); ?>-email" name="email" placeholder="<?php esc_attr_e( 'Email Address', 'bb-theme-child' ); ?>" required autocomplete="email" />
	</div>

	<div class="signup-a-suite-field">
		<label for="<?php echo esc_attr( $field_prefix ); ?>-phone"><?php esc_html_e( 'Phone Number', 'bb-theme-child' ); ?></label>
		<input type="tel" id="<?php echo esc_attr( $field_prefix ); ?>-phone" name="phone" placeholder="(123) 456-7890" required autocomplete="tel-national" />
	</div>
	
	<div class="signup-a-suite-field preferred-suite-wrapper" data-preferred-suite-wrapper style="display:none;">
		<label for="<?php echo esc_attr( $field_prefix ); ?>-preferred-suite"><?php esc_html_e( 'Preferred Suite', 'bb-theme-child' ); ?></label>
		<input type="text" id="<?php echo esc_attr( $field_prefix ); ?>-preferred-suite" name="preferred_suite" value="" readonly />
	</div>

	<div class="signup-a-suite-field">
		<label for="<?php echo esc_attr( $field_prefix ); ?>-preferred-location"><?php esc_html_e( 'Preferred Location', 'bb-theme-child' ); ?></label>
		<select id="<?php echo esc_attr( $field_prefix ); ?>-preferred-location" name="preferred_location" required>
			<?php foreach ( $location_options as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>"<?php echo '' === $value ? ' disabled selected' : ''; ?>>
					<?php echo esc_html( $label ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</div>

	<div class="signup-a-suite-field signup-a-suite-field--services" data-profession-services-field>
		<label for="<?php echo esc_attr( $field_prefix ); ?>-profession-services-trigger">
			<?php esc_html_e( 'Profession/ Services', 'bb-theme-child' ); ?>
		</label>
		<div
			class="signup-a-suite-multi-select"
			data-profession-multi-select
			data-placeholder="<?php echo esc_attr__( 'Select profession/services...', 'bb-theme-child' ); ?>"
		>
			<button
				type="button"
				id="<?php echo esc_attr( $field_prefix ); ?>-profession-services-trigger"
				class="signup-a-suite-multi-select__trigger"
				aria-haspopup="listbox"
				aria-expanded="false"
				aria-labelledby="<?php echo esc_attr( $field_prefix ); ?>-profession-services-trigger-label"
			>
				<span
					id="<?php echo esc_attr( $field_prefix ); ?>-profession-services-trigger-label"
					class="signup-a-suite-multi-select__value is-placeholder"
					data-profession-services-value
				>
					<?php esc_html_e( 'Select profession/services...', 'bb-theme-child' ); ?>
				</span>
			</button>
			<div class="signup-a-suite-multi-select__panel" role="listbox" aria-multiselectable="true" hidden>
				<div class="signup-a-suite-multi-select__panel-header">
					<span><?php esc_html_e( 'Select services', 'bb-theme-child' ); ?></span>
					<button type="button" class="signup-a-suite-multi-select__done" data-profession-services-done>
						<?php esc_html_e( 'Done', 'bb-theme-child' ); ?>
					</button>
				</div>
				<div class="signup-a-suite-multi-select__options">
					<?php foreach ( $profession_service_options as $value => $label ) : ?>
						<label class="signup-a-suite-multi-select__option">
							<input
								type="checkbox"
								class="signup-a-suite-service-input"
								name="profession_services[]"
								value="<?php echo esc_attr( $value ); ?>"
								data-service-label="<?php echo esc_attr( $label ); ?>"
							/>
							<span class="signup-a-suite-multi-select__option-row">
								<span class="signup-a-suite-multi-select__option-text"><?php echo esc_html( $label ); ?></span>
								<span class="signup-a-suite-multi-select__check" aria-hidden="true"></span>
							</span>
						</label>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<p class="signup-a-suite-field-error" data-profession-services-error hidden role="alert"></p>
	</div>
	
	</div>

	<div>
	    <div class="signup-a-suite-field">
    		<label for="<?php echo esc_attr( $field_prefix ); ?>-message"><?php esc_html_e( 'Message', 'bb-theme-child' ); ?></label>
    		<textarea id="<?php echo esc_attr( $field_prefix ); ?>-message" name="message" rows="5" placeholder="<?php esc_attr_e( 'Type something here..', 'bb-theme-child' ); ?>" required></textarea>
    	</div>
    
    	<button type="submit" class="signup-a-suite-submit">
    		<?php esc_html_e( 'Send', 'bb-theme-child' ); ?>
    	</button>
	</div>

	<div class="signup-a-suite-feedback" role="status" aria-live="polite"></div>
</form>
</div>
<div class="right">
    <img src="/wp-content/uploads/2026/06/legacy-salons-arlington-plaque-award.webp" /> 
</div>