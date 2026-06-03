<?php
/**
 * Plugin Name: Stylists
 * Description: Adds functionlity for Stylists 
 * Version: 1.0
 * Author: Your Name
 */

if (!defined('ABSPATH')) exit;

/**
 * Add Meta Box
 */
add_action('add_meta_boxes', function () {
    add_meta_box(
        'stylist_location',
        'Stylist Location',
        'stylist_location_box',
        'stylists',
        'normal',
        'default'
    );
});

/**
 * Meta Box HTML
 */
function stylist_location_box($post) {

    $state = get_post_meta($post->ID, '_state', true);
    $city  = get_post_meta($post->ID, '_city', true);

    wp_nonce_field('stylist_location_save', 'stylist_location_nonce');
    ?>
    <p>
        <label><strong>State</strong></label><br>
        <select name="stylist_state" id="stylist_state">
            <option value="">Select State</option>
            <?php foreach (get_us_states() as $code => $name): ?>
                <option value="<?= esc_attr($code); ?>" <?= selected($state, $code); ?>>
                    <?= esc_html($name); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>

    <p>
        <label><strong>City</strong></label><br>
        <select name="stylist_city" id="stylist_city">
            <?php if ($city): ?>
                <option value="<?= esc_attr($city); ?>" selected><?= esc_html($city); ?></option>
            <?php else: ?>
                <option value="">Select City</option>
            <?php endif; ?>
        </select>
    </p>
    <?php
}

/**
 * Save Meta
 */
add_action('save_post', function ($post_id) {

    if (!isset($_POST['stylist_location_nonce'])) return;
    if (!wp_verify_nonce($_POST['stylist_location_nonce'], 'stylist_location_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    update_post_meta($post_id, '_state', sanitize_text_field($_POST['stylist_state']));
    update_post_meta($post_id, '_city', sanitize_text_field($_POST['stylist_city']));
});

/**
 * Enqueue Scripts
 */
add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook !== 'post.php' && $hook !== 'post-new.php') return;

    wp_enqueue_script(
        'stylist-city-loader',
        plugin_dir_url(__FILE__) . 'js/city-loader.js',
        ['jquery'],
        null,
        true
    );

    wp_localize_script('stylist-city-loader', 'stylistAjax', [
        'ajax_url' => admin_url('admin-ajax.php')
    ]);
});

/**
 * AJAX City Loader
 */
add_action('wp_ajax_get_us_cities', function () {

    $state = sanitize_text_field($_POST['state']);
    $fips  = get_state_fips($state);

    if (!$fips) wp_send_json([]);

    $url = "https://api.census.gov/data/2019/pep/population?get=NAME&for=place:*&in=state:$fips";
    $response = wp_remote_get($url);

    $data = json_decode(wp_remote_retrieve_body($response), true);
    $cities = [];

    if ($data) {
        foreach ($data as $key => $row) {
            if ($key === 0) continue;
            $cities[] = $row[0];
        }
    }

    wp_send_json($cities);
});

/**
 * US States
 */
function get_us_states() {
    return [
        'AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas',
        'CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut','DE'=>'Delaware',
        'FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois',
        'IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana',
        'ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts','MI'=>'Michigan',
        'MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana',
        'NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire','NJ'=>'New Jersey',
        'NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota',
        'OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania',
        'RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota',
        'TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont',
        'VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia',
        'WI'=>'Wisconsin','WY'=>'Wyoming'
    ];
}

/**
 * State → FIPS
 */
function get_state_fips($state) {
    $map = [
        'AL'=>'01','AK'=>'02','AZ'=>'04','AR'=>'05','CA'=>'06','CO'=>'08','CT'=>'09','DE'=>'10',
        'FL'=>'12','GA'=>'13','HI'=>'15','ID'=>'16','IL'=>'17','IN'=>'18','IA'=>'19','KS'=>'20',
        'KY'=>'21','LA'=>'22','ME'=>'23','MD'=>'24','MA'=>'25','MI'=>'26','MN'=>'27','MS'=>'28',
        'MO'=>'29','MT'=>'30','NE'=>'31','NV'=>'32','NH'=>'33','NJ'=>'34','NM'=>'35','NY'=>'36',
        'NC'=>'37','ND'=>'38','OH'=>'39','OK'=>'40','OR'=>'41','PA'=>'42','RI'=>'44','SC'=>'45',
        'SD'=>'46','TN'=>'47','TX'=>'48','UT'=>'49','VT'=>'50','VA'=>'51','WA'=>'53','WV'=>'54',
        'WI'=>'55','WY'=>'56'
    ];
    return $map[$state] ?? '';
}


