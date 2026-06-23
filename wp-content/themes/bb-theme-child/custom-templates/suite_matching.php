<?php
/*
Template Name: Suite Matching
*/

get_header();

$admin_email = get_option( 'admin_email' );
$nonce       = wp_create_nonce( 'cw_suite_match_submit' );

// $qs_contact_name  = isset( $_GET['customer_name'] ) ? sanitize_text_field( wp_unslash( $_GET['customer_name'] ) ) : '';
// $qs_contact_email = isset( $_GET['customer_email'] ) ? sanitize_email( wp_unslash( $_GET['customer_email'] ) ) : '';
// $qs_contact_phone = isset( $_GET['customer_phone'] ) ? sanitize_text_field( wp_unslash( $_GET['customer_phone'] ) ) : '';

// // Back-compat for older links using contact_* params.
// if ( '' === $qs_contact_name && isset( $_GET['contact_name'] ) ) {
// 	$qs_contact_name = sanitize_text_field( wp_unslash( $_GET['contact_name'] ) );
// }
// if ( '' === $qs_contact_email && isset( $_GET['contact_email'] ) ) {
// 	$qs_contact_email = sanitize_email( wp_unslash( $_GET['contact_email'] ) );
// }
// if ( '' === $qs_contact_phone && isset( $_GET['contact_phone'] ) ) {
// 	$qs_contact_phone = sanitize_text_field( wp_unslash( $_GET['contact_phone'] ) );
// }
?>

<div class="suite-matching-page">
	<div id="suite-wizard" class="cw-suite-wizard" data-admin-email="<?php echo esc_attr( $admin_email ); ?>">
		<form id="wizard-form" class="cw-wizard-form" method="post">
			<input type="hidden" name="action" value="cw_suite_match_submit" />
			<input type="hidden" name="cw_nonce" value="<?php echo esc_attr( $nonce ); ?>" />
			<!--<input type="hidden" name="customer_name" value="<?php //echo esc_attr( $qs_contact_name ); ?>" />-->
			<!--<input type="hidden" name="customer_email" value="<?php //echo esc_attr( $qs_contact_email ); ?>" />-->
			<!--<input type="hidden" name="customer_phone" value="<?php //echo esc_attr( $qs_contact_phone ); ?>" />-->

			<div class="cw-step cw-step-1" data-step="1">
				<h3><?php esc_html_e( 'Step 1 — Profession/Services', 'custom-widget' ); ?></h3>
				<p><?php esc_html_e( 'What best describes you?', 'custom-widget' ); ?></p>

				<div class="cw-options">
					<label><input type="radio" name="profession" value="Hairstylist" required> <?php esc_html_e( 'Hairstylist', 'custom-widget' ); ?></label>
					<label><input type="radio" name="profession" value="Esthetician"> <?php esc_html_e( 'Esthetician', 'custom-widget' ); ?></label>
					<label><input type="radio" name="profession" value="Lash Artist"> <?php esc_html_e( 'Lash Artist', 'custom-widget' ); ?></label>
					<label><input type="radio" name="profession" value="Barber"> <?php esc_html_e( 'Barber', 'custom-widget' ); ?></label>
					<label><input type="radio" name="profession" value="Nail Technician"> <?php esc_html_e( 'Nail Technician', 'custom-widget' ); ?></label>
					<label><input type="radio" name="profession" value="Makeup Artist"> <?php esc_html_e( 'Makeup Artist', 'custom-widget' ); ?></label>
					<label>
						<input type="radio" name="profession" value="Other" class="cw-profession-other">
						<?php esc_html_e( 'Other', 'custom-widget' ); ?>
					</label>
					<div class="cw-other-wrap" style="display:none;">
						<label class="screen-reader-text" for="cw-profession-other-text"><?php esc_html_e( 'Other profession', 'custom-widget' ); ?></label>
						<input id="cw-profession-other-text" type="text" name="profession_other" placeholder="<?php esc_attr_e( 'Please specify', 'custom-widget' ); ?>" />
					</div>
				</div>

				<div class="cw-nav">
					<button type="button" class="cw-next"><?php esc_html_e( 'Next', 'custom-widget' ); ?></button>
				</div>
			</div>

			<div class="cw-step cw-step-2" data-step="2" style="display:none;">
				<h3><?php esc_html_e( 'Step 2 — Location Preference', 'custom-widget' ); ?></h3>
				<p><?php esc_html_e( 'Where would you like your suite?', 'custom-widget' ); ?></p>

				<div class="cw-options">
					<label><input type="radio" name="location_preference" value="Interstate 20 Location" required> <?php esc_html_e( 'Interstate 20 Location', 'custom-widget' ); ?></label>
					<label><input type="radio" name="location_preference" value="Cooper Street Location"> <?php esc_html_e( 'Cooper Street Location', 'custom-widget' ); ?></label>
					<label><input type="radio" name="location_preference" value="Open to both"> <?php esc_html_e( 'Open to both', 'custom-widget' ); ?></label>
				</div>

				<div class="cw-nav">
					<button type="button" class="cw-prev"><?php esc_html_e( 'Previous', 'custom-widget' ); ?></button>
					<button type="button" class="cw-next"><?php esc_html_e( 'Next', 'custom-widget' ); ?></button>
				</div>
			</div>

			<div class="cw-step cw-step-3" data-step="3" style="display:none;">
				<h3><?php esc_html_e( 'Step 3 — Timeline', 'custom-widget' ); ?></h3>
				<p><?php esc_html_e( 'When are you looking to move?', 'custom-widget' ); ?></p>

				<div class="cw-options">
					<label><input type="radio" name="timeline" value="Ready now" required> <?php esc_html_e( 'Ready now', 'custom-widget' ); ?></label>
					<label><input type="radio" name="timeline" value="Within 30 days"> <?php esc_html_e( 'Within 30 days', 'custom-widget' ); ?></label>
					<label><input type="radio" name="timeline" value="60–90 days"> <?php esc_html_e( '60–90 days', 'custom-widget' ); ?></label>
					<label><input type="radio" name="timeline" value="Just exploring"> <?php esc_html_e( 'Just exploring', 'custom-widget' ); ?></label>
				</div>

				<div class="cw-nav">
					<button type="button" class="cw-prev"><?php esc_html_e( 'Previous', 'custom-widget' ); ?></button>
					<button type="button" class="cw-next"><?php esc_html_e( 'Next', 'custom-widget' ); ?></button>
				</div>
			</div>

			<div class="cw-step cw-step-4" data-step="4" style="display:none;">
				<h3><?php esc_html_e( 'Step 4 — Budget Comfort', 'custom-widget' ); ?></h3>
				<p><?php esc_html_e( 'What weekly range feels comfortable?', 'custom-widget' ); ?></p>

				<div class="cw-options">
					<label><input type="radio" name="budget_weekly" value="$150–$250" required> <?php esc_html_e( '$150–$250', 'custom-widget' ); ?></label>
					<label><input type="radio" name="budget_weekly" value="$250–$350"> <?php esc_html_e( '$250–$350', 'custom-widget' ); ?></label>
					<label><input type="radio" name="budget_weekly" value="$350+"> <?php esc_html_e( '$350+', 'custom-widget' ); ?></label>
					<label><input type="radio" name="budget_weekly" value="Not sure yet"> <?php esc_html_e( 'Not sure yet', 'custom-widget' ); ?></label>
				</div>

				<div class="cw-nav">
					<button type="button" class="cw-prev"><?php esc_html_e( 'Previous', 'custom-widget' ); ?></button>
					<button type="button" class="cw-next"><?php esc_html_e( 'Next', 'custom-widget' ); ?></button>
					<!--<button type="button" class="cw-submit"><?php //esc_html_e( 'Submit', 'custom-widget' ); ?></button>-->
				</div>
			</div>
			<div class="cw-step cw-step-5" data-step="5" style="display:none;">
				<h3><?php esc_html_e( 'Step 5 — Personal Information', 'custom-widget' ); ?></h3>
				<!--<p><?php //esc_html_e( 'Fill All Required Personal Information', 'custom-widget' ); ?></p>-->

		        <div>
		            <label>Enter Your Name</label>
		            <input type="text" name="customer_name" placeholder="Enter Your Name." required>
		            <label>Enter Your Email</label>
		            <input type="email" name="customer_email" placeholder="Enter Your Email." required>
		            <label>Enter Your Phone Number</label>
		            <input type="tel" name="customer_phone" placeholder="Enter Your Phone Number.">
		            <label>How do you prefer to be contacted?</label>
		            <div class="label_wrap">
    		            <label><input type="radio" name="prefer_contact" value="Email" required> <?php esc_html_e( 'Email', 'custom-widget' ); ?></label>
    					<label><input type="radio" name="prefer_contact" value="Phone"> <?php esc_html_e( 'Phone', 'custom-widget' ); ?></label>
		            </div>

		        </div>

				<div class="cw-nav">
					<button type="button" class="cw-prev"><?php esc_html_e( 'Previous', 'custom-widget' ); ?></button>
					<button type="button" class="cw-submit"><?php esc_html_e( 'Submit', 'custom-widget' ); ?></button>
				</div>
			</div>
		</form>

		<div class="cw-wizard-result" style="display:none;"></div>
	</div>
</div>

<script>
(function(){
	var root = document.getElementById('suite-wizard');
	if(!root) return;

	var form = root.querySelector('#wizard-form');
	var result = root.querySelector('.cw-wizard-result');
	var steps = [].slice.call(root.querySelectorAll('.cw-step'));
	var current = 1;

	function showStep(n){
		steps.forEach(function(s){
			s.style.display = (parseInt(s.getAttribute('data-step'),10) === n) ? '' : 'none';
		});
		current = n;
	}

	function validateStep(n){
		var step = root.querySelector('.cw-step[data-step="'+n+'"]');
		if(!step) return true;
		var required = [].slice.call(step.querySelectorAll('[required]'));
		for(var i=0;i<required.length;i++){
			var el = required[i];
			if(el.type === 'radio'){
				var name = el.name;
				if(!step.querySelector('input[type="radio"][name="'+name+'"]:checked')) return false;
			} else if(!el.value) {
				return false;
			}
		}
		// If Other selected, require text
		var other = step.querySelector('input.cw-profession-other');
		if(other && other.checked){
			var otherText = root.querySelector('input[name="profession_other"]');
			if(otherText && !otherText.value) return false;
		}
		return true;
	}

	// Start immediately on page load.
	showStep(1);

	// Other toggle
	var otherRadio = root.querySelector('input.cw-profession-other');
	var otherWrap = root.querySelector('.cw-other-wrap');
	var profRadios = [].slice.call(root.querySelectorAll('input[name="profession"]'));
	profRadios.forEach(function(r){
		r.addEventListener('change', function(){
			if(!otherWrap) return;
			otherWrap.style.display = (otherRadio && otherRadio.checked) ? '' : 'none';
		});
	});

	root.addEventListener('click', function(e){
		var next = e.target.closest('.cw-next');
		var prev = e.target.closest('.cw-prev');
		if(next){
			e.preventDefault();
			if(!validateStep(current)) return;
			showStep(Math.min(current+1, steps.length));
		}
		if(prev){
			e.preventDefault();
			showStep(Math.max(current-1, 1));
		}
	});

	if(form){
		// Prevent native submit; use only AJAX click handler.
		form.addEventListener('submit', function(e){ e.preventDefault(); });

		var submitBtn = form.querySelector('.cw-submit');
		if(!submitBtn) return;

		submitBtn.addEventListener('click', function(e){
			e.preventDefault();
			if(!validateStep(current)){
			    result.style.display = '';
				result.innerHTML = 'Missing contact info. Please enter your name and email.';
				return;
			}

			// Contact info comes from query string and must be present.
			// Accept either customer_* or contact_* (back-compat with older templates).
			var contactName = form.querySelector('input[name="customer_name"]') || form.querySelector('input[name="contact_name"]');
			var contactEmail = form.querySelector('input[name="customer_email"]') || form.querySelector('input[name="contact_email"]');
			if(!contactName || !contactName.value || !contactEmail || !contactEmail.value){
				result.style.display = '';
				result.innerHTML = 'Missing contact info. Please enter your name and email.';
				return;
			}

			submitBtn.disabled = true;

			var data = new FormData(form);
			// Use only one endpoint.
			data.set('action', 'cw_suite_match_submit');
			result.style.display = '';
			result.innerHTML = 'Submitting...';

			fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
				method: 'POST',
				credentials: 'same-origin',
				body: data
			}).then(function(r){ return r.json(); })
			.then(function(json){
				submitBtn.disabled = false;
				if(json && json.success){
					form.style.display = 'none';
					var msg = (json.data && json.data.message) ? json.data.message : 'Submitted.';
					var matches = (json.data && json.data.matches) ? json.data.matches : [];
					if(matches && matches.length){
						var html = '<div class="cw-success-msg">'+msg+'</div><ol class="cw-matches">';
						matches.forEach(function(m){
							var title = m.title || ('Suite #' + (m.id || ''));
							var url = m.url || '';
							html += '<li>' + (url ? ('<a href="'+url+'">'+title+'</a>') : title) + '</li>';
						});
						html += '</ol>';
						result.innerHTML = html;
					
                        setTimeout(function() {
                            window.location.href = '/'; 
                        }, 3000);
						
					} else {
						result.innerHTML = msg;
						setTimeout(function() {
                            window.location.href = '/'; 
                        }, 3000);
					}
				} else {
					result.innerHTML = (json && json.data && json.data.message) ? json.data.message : 'Submission failed. Please try again.';
					setTimeout(function() {
                        window.location.href = '/'; 
                    }, 3000);
				}
			}).catch(function(){
				submitBtn.disabled = false;
				result.innerHTML = 'Submission failed. Please try again.';
				setTimeout(function() {
                    window.location.href = '/'; 
                }, 3000);
			});
		});
	}
})();
</script>

<?php
get_footer();
