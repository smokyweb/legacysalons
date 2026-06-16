<?php
/**
 * Suite matching form for footer popup modal.
 *
 * @package bb-theme-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<h2 class="suite-matching-popup__title"><?php esc_html_e( 'Find Your Perfect Suite Match', 'bb-theme-child' ); ?></h2>
<div class="cw-grid">
	<div class="cw-field">
		<label for="suite-popup-profession"><?php esc_html_e( 'Profession/Services', 'bb-theme-child' ); ?></label>
		<select id="suite-popup-profession" name="profession" required>
			<option value=""><?php esc_html_e( 'Select', 'bb-theme-child' ); ?></option>
			<option value="Hairstylist"><?php esc_html_e( 'Hairstylist', 'bb-theme-child' ); ?></option>
			<option value="Esthetician"><?php esc_html_e( 'Esthetician', 'bb-theme-child' ); ?></option>
			<option value="Lash Artist"><?php esc_html_e( 'Lash Artist', 'bb-theme-child' ); ?></option>
			<option value="Barber"><?php esc_html_e( 'Barber', 'bb-theme-child' ); ?></option>
			<option value="Nail Technician"><?php esc_html_e( 'Nail Technician', 'bb-theme-child' ); ?></option>
			<option value="Makeup Artist"><?php esc_html_e( 'Makeup Artist', 'bb-theme-child' ); ?></option>
			<option value="Other"><?php esc_html_e( 'Other', 'bb-theme-child' ); ?></option>
		</select>
	</div>
	<div class="cw-field suite-popup-profession-other-wrap" style="display:none;">
		<input id="suite-popup-profession-other" name="profession_other" type="text" placeholder="<?php esc_attr_e( 'Please specify', 'bb-theme-child' ); ?>" />
	</div>
	<div class="cw-field">
		<label for="suite-popup-location"><?php esc_html_e( 'Location', 'bb-theme-child' ); ?></label>
		<select id="suite-popup-location" name="location_preference" required>
			<option value=""><?php esc_html_e( 'Select', 'bb-theme-child' ); ?></option>
			<option value="Little road"><?php esc_html_e( 'Little road', 'bb-theme-child' ); ?></option>
			<option value="Interstate 20 Location"><?php esc_html_e( 'Interstate 20 Location', 'bb-theme-child' ); ?></option>
			<option value="Cooper Street Location"><?php esc_html_e( 'Cooper Street Location', 'bb-theme-child' ); ?></option>
			<option value="Open to both"><?php esc_html_e( 'Open to both', 'bb-theme-child' ); ?></option>
		</select>
	</div>
	<div class="cw-field">
		<label for="suite-popup-timeline"><?php esc_html_e( 'Timeline', 'bb-theme-child' ); ?></label>
		<select id="suite-popup-timeline" name="timeline" required>
			<option value=""><?php esc_html_e( 'Select', 'bb-theme-child' ); ?></option>
			<option value="Ready now"><?php esc_html_e( 'Ready now', 'bb-theme-child' ); ?></option>
			<option value="Within 30 days"><?php esc_html_e( 'Within 30 days', 'bb-theme-child' ); ?></option>
			<option value="60–90 days"><?php esc_html_e( '60–90 days', 'bb-theme-child' ); ?></option>
			<option value="Just exploring"><?php esc_html_e( 'Just exploring', 'bb-theme-child' ); ?></option>
		</select>
	</div>
	<div class="cw-field">
		<label for="suite-popup-budget"><?php esc_html_e( 'Budget', 'bb-theme-child' ); ?></label>
		<select id="suite-popup-budget" name="budget_weekly" required>
			<option value=""><?php esc_html_e( 'Select', 'bb-theme-child' ); ?></option>
			<option value="$150–$250"><?php esc_html_e( '$150–$250', 'bb-theme-child' ); ?></option>
			<option value="$250–$350"><?php esc_html_e( '$250–$350', 'bb-theme-child' ); ?></option>
			<option value="$350+"><?php esc_html_e( '$350+', 'bb-theme-child' ); ?></option>
			<option value="Not sure yet"><?php esc_html_e( 'Not sure yet', 'bb-theme-child' ); ?></option>
		</select>
	</div>
</div>
<div class="text-center mt-4">
	<button class="btn btn-matching suite-matching-popup__submit" type="button"><?php esc_html_e( 'Find a Suite', 'bb-theme-child' ); ?></button>
</div>
