<?php
    if (!defined('ABSPATH')) exit;

    function add_ajaxurl_to_frontend() {
        ?>
        <script>
            var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        </script>
        <?php
    }
    add_action('wp_head', 'add_ajaxurl_to_frontend');

    function enqueue_frontend_assets() {

        // SweetAlert
        wp_enqueue_script(
            'sweetalert2',
            'https://cdn.jsdelivr.net/npm/sweetalert2@11',
            array(),
            null,
            true
        );

        // Swiper CSS
        wp_enqueue_style(
            'swiper-css',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css'
        );

        // Swiper JS
        wp_enqueue_script(
            'swiper-js',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
            array(),
            null,
            true
        );

        // ✅ FIXED: Plugin path use karo
        wp_enqueue_script(
            'custom-js',
            plugin_dir_url(__FILE__) . 'js/custom.js',
            array('jquery', 'sweetalert2', 'swiper-js'),
            time(), // cache issue avoid
            true
        );

        // AJAX data
        wp_localize_script('custom-js', 'customAjax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('custom_ajax_nonce')
        ));
    }
    add_action('wp_enqueue_scripts', 'enqueue_frontend_assets');

    function enqueue_admin_assets($hook) {

        wp_enqueue_script(
            'admin-js',
            plugin_dir_url(__FILE__) . 'js/admin.js',
            array('jquery'),
            time(), // cache avoid
            true
        );

        // AJAX (agar use kar rahe ho)
        wp_localize_script('admin-js', 'adminAjax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('admin_ajax_nonce')
        ));
    }
    add_action('admin_enqueue_scripts', 'enqueue_admin_assets');

    function enqueue_google_maps_admin($hook) {

        wp_enqueue_script(
            'google-maps-places',
            'https://maps.googleapis.com/maps/api/js?key=AIzaSyCtHynalZ4XTBgV5cCOjn8qEe74JbkqUYI&libraries=places',
            array(),
            null,
            true
        );

        wp_enqueue_script(
            'admin-js',
            plugin_dir_url(__FILE__) . 'js/admin.js',
            array('jquery', 'google-maps-places'),
            time(),
            true
        );
    }
    add_action('admin_enqueue_scripts', 'enqueue_google_maps_admin');

    // function enqueue_custom_admin_script($hook) {
    //     wp_enqueue_script(
    //         'custom-admin-js',
    //         plugin_dir_url(__FILE__) . 'js/custom.js',
    //         array('jquery'),
    //         '1.0',
    //         true 
    //     );
    // }
    // add_action('admin_enqueue_scripts', 'enqueue_custom_admin_script');

    // function enqueue_sweetalert() {
    //     wp_enqueue_script(
    //         'sweetalert2',
    //         'https://cdn.jsdelivr.net/npm/sweetalert2@11',
    //         array(),
    //         null,
    //         true
    //     );

    //     wp_enqueue_script(
    //         'custom-js',
    //         get_template_directory_uri() . '/js/custom.js',
    //         array('jquery', 'sweetalert2'),
    //         '1.0',
    //         true
    //     );
    // }
    // add_action('wp_enqueue_scripts', 'enqueue_sweetalert');

    // function custom_ajax_enqueue_scripts() {
    //     wp_enqueue_script(
    //         'custom-ajax-js',
    //         plugin_dir_url(__FILE__) . 'js/custom.js',
    //         array('jquery', 'owl-carousel'),
    //         null,
    //         true
    //     );

    //     wp_localize_script('custom-ajax-js', 'customAjax', array(
    //         'ajax_url' => admin_url('admin-ajax.php'),
    //         'nonce'    => wp_create_nonce('custom_ajax_nonce')
    //     ));
    // }
    // add_action('wp_enqueue_scripts', 'custom_ajax_enqueue_scripts');

    // function salon_slider_assets() {

    //     wp_enqueue_style(
    //         'swiper-css',
    //         'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css'
    //     );

    //     wp_enqueue_script(
    //         'swiper-js',
    //         'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
    //         array(),
    //         null,
    //         true
    //     );

    // }
    // add_action('wp_enqueue_scripts', 'salon_slider_assets');

    // Stylist services
    function register_stylist_services_taxonomy() {

        $labels = array(
        'name' => 'Services',
        'singular_name' => 'Service',
        'search_items' => 'Search Services',
        'all_items' => 'All Services',
        'edit_item' => 'Edit Service',
        'update_item' => 'Update Service',
        'add_new_item' => 'Add New Service',
        'new_item_name' => 'New Service Name',
        'menu_name' => 'Services',
        );
        
        
        $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'stylist-service'),
        );

        // register_taxonomy('stylist_service', array('stylist','luxury_suites'), $args);
        register_taxonomy('stylist_service', array('stylist'), $args);
    }
    add_action('init', 'register_stylist_services_taxonomy');

    // Notice page
    add_action('admin_menu', 'my_notice_options_page');
    function my_notice_options_page() {
        add_menu_page(
            'Notice Settings',
            'Notice',
            'manage_options',
            'notice-settings',
            'my_notice_settings_page',
            'dashicons-megaphone',
            20
        );
    }

    add_action('admin_init', 'my_notice_register_settings');
    function my_notice_register_settings() {
        register_setting(
            'my_notice_group',
        //     'my_single_notice'
        // );
        'my_single_notice',
            array(
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_textarea_field',
            )
        );
        register_setting(
            'my_notice_group',
            'my_notice_discount_percentage',
            array(
                'type'              => 'integer',
                'sanitize_callback' => 'my_notice_sanitize_discount_percentage',
                'default'           => 20,
            )
        );
    }

    function my_notice_sanitize_discount_percentage( $value ) {
        $value = absint( $value );
        return min( 100, $value );
    }

    function legacy_get_notice_discount_percentage() {
        $value = get_option( 'my_notice_discount_percentage', 20 );
        if ( '' === $value || false === $value ) {
            return 0;
        }
        return my_notice_sanitize_discount_percentage( $value );
    }

    function legacy_apply_notice_discount_placeholders( $text ) {
        if ( ! is_string( $text ) || '' === $text ) {
            return $text;
        }

        $discount = legacy_get_notice_discount_percentage() . '%';
        $text     = str_replace( array( '{discount}', '{{discount}}' ), $discount, $text );

        return $text;
    }

    function legacy_notice_topbar_discount_attributes( $content ) {
        if (
            ! is_string( $content )
            || (
                false === stripos( $content, 'id="topbar"' )
                && false === stripos( $content, "id='topbar'" )
            )
        ) {
            return $content;
        }

        $percentage = legacy_get_notice_discount_percentage();

        if ( ! preg_match( '/\sid=["\']topbar["\']/i', $content ) ) {
            return $content;
        }

        if ( false === stripos( $content, 'data-percentage=' ) ) {
            $content = preg_replace(
                '/(<div[^>]*\sid=["\']topbar["\'])(?![^>]*data-percentage=)/i',
                '$1 data-percentage="' . esc_attr( $percentage ) . '"',
                $content,
                1
            );
        }

        return legacy_apply_notice_discount_placeholders( $content );
    }
    
    add_filter( 'fl_builder_render_content', 'legacy_notice_topbar_discount_attributes', 20 );
    add_filter( 'fl_builder_render_layout', 'legacy_notice_topbar_discount_attributes', 20 );

    function legacy_notice_topbar_discount_footer_script() {
        if ( is_admin() ) {
            return;
        }

        $percentage  = legacy_get_notice_discount_percentage();
        $notice_text = legacy_apply_notice_discount_placeholders( get_option( 'my_single_notice', '' ) );
        ?>
        <script>
        (function () {
            var topbar = document.getElementById('topbar');
            if (!topbar) {
                return;
            }
            topbar.setAttribute('data-percentage', <?php echo wp_json_encode( (string) $percentage ); ?>);

            var noticeText = <?php echo wp_json_encode( $notice_text ); ?>;
            if (!noticeText) {
                return;
            }

            var welcome = topbar.querySelector('.welcome');
            if (!welcome) {
                return;
            }

            var closeBtn = welcome.querySelector('.notice_close');
            var paragraph = welcome.querySelector('p');
            if (paragraph) {
                paragraph.textContent = noticeText;
            } else {
                welcome.insertAdjacentHTML('afterbegin', '<p></p>');
                welcome.querySelector('p').textContent = noticeText;
            }
            if (closeBtn) {
                welcome.appendChild(closeBtn);
            }
             topbar.style.cursor = 'pointer';
        })();
        </script>
        <?php
    }
    add_action( 'wp_footer', 'legacy_notice_topbar_discount_footer_script', 5 );

    function my_notice_settings_page() {
        $discount = legacy_get_notice_discount_percentage();
        ?>
        <div class="wrap">
            <h1>Notice Settings</h1>

            <form method="post" action="options.php">
                <?php
                    settings_fields('my_notice_group');
                    do_settings_sections('my_notice_group');
                ?>

                <table class="form-table">
                    <tr>
                        <!--<th scope="row">Notice Text</th>-->
                        <th scope="row"><?php esc_html_e( 'Notice Text', 'custom-widget' ); ?></th>
                        <td>
                            <textarea
                                name="my_single_notice"
                                rows="5"
                                cols="50"
                             class="large-text"
                            ><?php echo esc_textarea( get_option( 'my_single_notice' ) ); ?></textarea>
                            <p class="description">
                                <?php esc_html_e( 'Use {discount} in the text to insert the discount percentage (e.g. "Get {discount} Off First Month").', 'custom-widget' ); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Discount Percentage', 'custom-widget' ); ?></th>
                        <td>
                            <input
                                type="number"
                                name="my_notice_discount_percentage"
                                id="my_notice_discount_percentage"
                                value="<?php echo esc_attr( $discount ); ?>"
                                min="0"
                                max="100"
                                step="1"
                                class="small-text"
                            />
                            <span>%</span>
                            <p class="description">
                                <?php esc_html_e( 'Stored on the top bar as data-percentage and used for {discount} in the notice.', 'custom-widget' ); ?>
                            </p>
                        </td>
                    </tr>
                </table>

                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    // Register Gallery Post Type
    function legacy_register_gallery_post_type() {

        register_post_type('legacy_gallery', array(
            'labels' => array(
                'name' => 'Gallery',
                'singular_name' => 'Gallery Item'
            ),
            'public' => true,
            'supports' => array('title', 'thumbnail'),
            'menu_icon' => 'dashicons-format-gallery',
            'has_archive' => false,
            'show_in_rest' => true
        ));

    }
    add_action('init', 'legacy_register_gallery_post_type');

    // Register Gallery Categories
    function legacy_gallery_taxonomy() {

        register_taxonomy('legacy_gallery_category', 'legacy_gallery', array(
            'label' => 'Gallery Categories',
            'hierarchical' => true,
            'show_in_rest' => true,
        ));

    }
    add_action('init', 'legacy_gallery_taxonomy');


    /*--------------------------------------------------------------
    CUSTOM STYLIST PERMALINK
    --------------------------------------------------------------*/
    function custom_stylist_permalink($url, $post) {
        if ($post->post_type === 'stylist') {
            return site_url('/stylist-profile/?stylist_id=' . $post->ID);
        }
        return $url;
    }
    add_filter('post_type_link', 'custom_stylist_permalink', 10, 2);

    add_action( 'wp_ajax_fl_search_query', 'modify_bb_ajax_search', 1 );
    add_action( 'wp_ajax_nopriv_fl_search_query', 'modify_bb_ajax_search', 1 );

    function modify_bb_ajax_search() {

        add_filter( 'posts_where', function( $where ) {
            global $wpdb;

            // Restrict to your CPT only
            $where .= " AND {$wpdb->posts}.post_type = 'stylist' ";

            return $where;
        });

    }

    // Dynamic Content Post type
    function register_page_content_post_type() {

        register_post_type('page_content', array(
            'labels' => array(
                'name' => 'Pages Content',
                'singular_name' => 'Page Content Item'
            ),
            'public' => true,
            'supports' => array('title', 'thumbnail'),
            'menu_icon' => 'dashicons-admin-site',
            'has_archive' => false,
            'show_in_rest' => true
        ));

    }
    add_action('init', 'register_page_content_post_type');

    // Our Locations Post type
    function register_our_locations_post_type() {

        register_post_type('our_locations', array(
            'labels' => array(
                'name' => 'Our Locations',
                'singular_name' => 'Our Locations Item'
            ),
            'public' => true,
            'supports' => array('title', 'thumbnail'),
            'menu_icon' => 'dashicons-location',
            'has_archive' => false,
            'show_in_rest' => true
        ));

    }
    add_action('init', 'register_our_locations_post_type');

    // filter suites slider
    add_action('wp_ajax_filter_suites', 'filter_suites_by_service');
    add_action('wp_ajax_nopriv_filter_suites', 'filter_suites_by_service');

    function filter_suites_by_service() {
        if (!isset($_POST['service'])) {
            wp_send_json_error('No service provided');
        }

        $service_slug = sanitize_text_field($_POST['service']);
    

        $args = array(
            'post_type' => 'luxury_suites',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => 'stylist_service',
                    'field'    => 'slug',
                    'terms'    => $service_slug,
                ),
            ),
        );

        $posts = get_posts($args);
        

        if (empty($posts)) {
            wp_send_json_success('<p>No suites found for this service.</p>');
        }

    ob_start();
    foreach($posts as $post) {
        setup_postdata($post);
        $postId = $post->ID;
        $image = esc_url(get_the_post_thumbnail_url($postId, 'full'));
        $tourLink = get_post_meta($postId, 'tour_link', true);
    $content = apply_filters('the_content', get_post_field('post_content', $postId));
        ?>
        <div class="swiper-slide">
            <div class="suite-slide">
                <div class="suite-image">
                    <img src="<?php echo $image; ?>" alt="<?php the_title(); ?>">
                </div>
                <div class="mainContent">
                <div class="suite-content">
                    <h3><?php echo $post->post_title; ?></h3>
                    <div class="content">
                        <?php echo $content; ?>
                    </div>
                </div>
                <a class="suite-btn" style="text-decoration:none;" href="<?php echo esc_url($tourLink); ?>">
                        Schedule Your Tour Today
                </a>
                </div>
            </div>
        </div>
        <?php
    }
    wp_reset_postdata();
    $html = ob_get_clean();
    wp_send_json_success($html);
    }

    // Location Category fo Stylists
    function register_locations_taxonomy() {

        register_taxonomy(
            'locations',
            'stylist',
            array(
                'label' => 'Locations',
                'hierarchical' => true,
                'public' => true,
                'show_admin_column' => true,
                'rewrite' => array('slug' => 'location'),
            )
        );

    }
    add_action('init', 'register_locations_taxonomy');


    // Hide Menu Page
    function hide_admin_menus_for_admin() {
        if (current_user_can('administrator')) {
            remove_menu_page('edit-comments.php');
            remove_menu_page('wpcf7'); 
        }
    }
    add_action('admin_menu', 'hide_admin_menus_for_admin', 999);


    // Disable comments on frontend
    add_filter( 'comments_open', '__return_false', 20, 2 );
    add_filter( 'pings_open', '__return_false', 20, 2 );
    add_filter( 'comments_array', '__return_empty_array', 10, 2 );

    // Replace comment template with blank to prevent frontend rendering
    add_filter( 'comments_template', function( $theme_template ) {
        return ABSPATH . 'wp-includes/theme-compat/comments.php';
    }, 20 );

    // Remove comment support from all post types
    add_action( 'init', function() {
        $post_types = get_post_types();
        foreach ( $post_types as $post_type ) {
            if ( post_type_supports( $post_type, 'comments' ) ) {
                remove_post_type_support( $post_type, 'comments' );
                remove_post_type_support( $post_type, 'trackbacks' );
            }
        }
    }, 100 );

    // Remove comment reply script
    add_action( 'wp_enqueue_scripts', function() {
        wp_deregister_script( 'comment-reply' );
    } );

    // CSS fallback to hide any hardcoded comment sections
    add_action( 'wp_head', function() {
        echo '<style>#comments, .comments-area, .comment-respond, .comments-title { display: none !important; }</style>';
    } );



    // Add admin menu for FAQs
    function faq_admin_menu() {
        add_menu_page(
            'FAQ Settings',       // Page title
            'FAQs',               // Menu title
            'manage_options',     // Capability
            'faq-settings',       // Menu slug
            'faq_admin_page',     // Callback function
            'dashicons-editor-help', // Icon
            20                    // Position
        );
    }
    add_action('admin_menu', 'faq_admin_menu');

    // Admin page content
    function faq_admin_page() {
        if (isset($_POST['faq_submit'])) {
            check_admin_referer('faq_save', 'faq_nonce');
            
            $faqs = array();
            if (isset($_POST['faq_question']) && isset($_POST['faq_answer'])) {
                $questions = $_POST['faq_question'];
                $answers = $_POST['faq_answer'];
                
                for ($i = 0; $i < count($questions); $i++) {
                    $q = sanitize_text_field($questions[$i]);
                    $a = sanitize_textarea_field($answers[$i]);
                    if ($q && $a) {
                        $faqs[] = array('question' => $q, 'answer' => $a);
                    }
                }
            }
            update_option('faq_list', $faqs);
            echo '<div class="updated"><p>FAQs saved!</p></div>';
        }

        // Get saved FAQs
        $saved_faqs = get_option('faq_list', array());
        ?>

        <div class="wrap">
            <h1>Manage FAQs</h1>
            <form method="post">
                <?php wp_nonce_field('faq_save', 'faq_nonce'); ?>
                <table id="faq_table" class="form-table">
                    <thead>
                        <tr>
                            <th>Question</th>
                            <th>Answer</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($saved_faqs): ?>
                            <?php foreach($saved_faqs as $faq): ?>
                                <tr>
                                    <td><input type="text" name="faq_question[]" value="<?php echo esc_attr($faq['question']); ?>" style="width:100%"></td>
                                    <td><textarea name="faq_answer[]" style="width:100%"><?php echo esc_textarea($faq['answer']); ?></textarea></td>
                                    <td><button type="button" class="remove-faq button">Remove</button></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <p><button type="button" class="add-faq button">Add FAQ</button></p>
                <p><input type="submit" name="faq_submit" class="button button-primary" value="Save FAQs"></p>
            </form>
        </div>

        <?php
    }

    // Our Team post type
    function create_our_team_cpt() {
        $labels = array(
            'name'                  => _x('Our Team', 'Post Type General Name', 'textdomain'),
            'singular_name'         => _x('Team Member', 'Post Type Singular Name', 'textdomain'),
            'menu_name'             => __('Our Team', 'textdomain'),
            'name_admin_bar'        => __('Team Member', 'textdomain'),
            'add_new_item'          => __('Add New Team Member', 'textdomain'),
            'edit_item'             => __('Edit Team Member', 'textdomain'),
            'view_item'             => __('View Team Member', 'textdomain'),
            'all_items'             => __('All Team Members', 'textdomain'),
            'search_items'          => __('Search Team Members', 'textdomain'),
        );

        $args = array(
            'label'                 => __('Our Team', 'textdomain'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail', 'excerpt'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-groups',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
        );

        register_post_type('our_team', $args);
    }
    add_action('init', 'create_our_team_cpt', 0);


    // testimonial Video Input form 

    add_action('add_meta_boxes', function () {
        add_meta_box(
            'custom_video_upload',
            'Upload Video',
            'custom_video_upload_callback',
            'spt_testimonial',
            'normal',
            'default'
        );
    });

    function custom_video_upload_callback($post) {
        $meta = get_post_meta($post->ID, 'sp_tpro_meta_options', true);
        $meta = is_array($meta) ? $meta : array();
        $saved_video_url = isset($meta['tpro_video_url']) ? esc_url($meta['tpro_video_url']) : '';

        wp_nonce_field('save_tpro_video_meta', 'tpro_video_meta_nonce');
        ?>
        <input
            type="text"
            id="tpro_video_url"
            name="tpro_video_url"
            value="<?php echo esc_attr($saved_video_url); ?>"
            style="width:70%;"
        />
        <button type="button" class="button button-primary" id="upload_video_button">Upload Video</button>
        <button type="button" class="button" id="remove_video_button">Remove Video</button>

        <div id="tpro-video-preview-wrap" style="margin-top:12px;">
            <?php if (!empty($saved_video_url)) : ?>
                <video
                    id="tpro-video-preview"
                    src="<?php echo esc_url($saved_video_url); ?>"
                    controls
                    style="width:320px;max-width:100%;max-height:180px;display:block;background:#000;"
                ></video>
            <?php else : ?>
                <p id="tpro-video-empty" style="margin:0;color:#666;">No video selected.</p>
            <?php endif; ?>
        </div>

        <script>
        jQuery(document).ready(function($){
            let frame;
            const $videoInput = $('#tpro_video_url');
            const $previewWrap = $('#tpro-video-preview-wrap');

            function renderPreview(videoUrl) {
                if (!videoUrl) {
                    $previewWrap.html('<p id="tpro-video-empty" style="margin:0;color:#666;">No video selected.</p>');
                    return;
                }

                const videoHtml = '<video id="tpro-video-preview" src="' + videoUrl + '" controls style="width:320px;max-width:100%;max-height:180px;display:block;background:#000;"></video>';
                $previewWrap.html(videoHtml);
            }

            $('#upload_video_button').click(function(e){
                e.preventDefault();

                if (frame) {
                    frame.open();
                    return;
                }

                frame = wp.media({
                    title: 'Select or Upload Video',
                    button: { text: 'Use this video' },
                    multiple: false,
                    library: { type: 'video' }
                });

                frame.on('select', function(){
                    let attachment = frame.state().get('selection').first().toJSON();
                    $videoInput.val(attachment.url);
                    renderPreview(attachment.url);
                });

                frame.open();
            });

            $('#remove_video_button').on('click', function(e){
                e.preventDefault();
                $videoInput.val('');
                renderPreview('');
            });
        });
        </script>
        <?php
    }

    add_action('save_post', function ($post_id) {

        if (!isset($_POST['tpro_video_meta_nonce']) ||
            !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tpro_video_meta_nonce'])), 'save_tpro_video_meta')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (get_post_type($post_id) !== 'spt_testimonial') return;
        if (!current_user_can('edit_post', $post_id)) return;
        if (!isset($_POST['tpro_video_url'])) return;

        $video_url = esc_url_raw(wp_unslash($_POST['tpro_video_url']));
        $meta = get_post_meta($post_id, 'sp_tpro_meta_options', true);
        if (!is_array($meta)) {
            $meta = [];
        }

        if (!empty($video_url)) {
            $meta['tpro_video_url'] = $video_url;
        } else {
            unset($meta['tpro_video_url']);
        }

        update_post_meta($post_id, 'sp_tpro_meta_options', $meta);

    }, 999);


    add_action('admin_enqueue_scripts', function ($hook) {

        if ($hook !== 'post.php' && $hook !== 'post-new.php') return;

        $screen = get_current_screen();
        if ($screen->post_type !== 'spt_testimonial') return;
        wp_enqueue_media();

        wp_enqueue_script(
            'tpro-video-upload',
            get_stylesheet_directory_uri() . '/js/tpro-video-upload.js',
            ['jquery'],
            time(),
            true
        );
    });


    add_action('wp_insert_post', 'set_testimonial_title_from_name', 20, 3);
    function set_testimonial_title_from_name($post_id, $post, $update) {

        if ( ! $post_id ) {
            return;
        }

        // $post can be null during auto-draft creation (e.g. post-new.php for any CPT).
        $post_type = ( $post instanceof WP_Post ) ? $post->post_type : get_post_type( $post_id );
        if ( 'spt_testimonial' !== $post_type ) {
            return;
        }

        if (!empty($_POST['tpro_client_name'])) {
            $name = sanitize_text_field($_POST['tpro_client_name']);
            remove_action('wp_insert_post', 'set_testimonial_title_from_name', 20);
            wp_update_post([
                'ID' => $post_id,
                'post_title' => $name
            ]);
            add_action('wp_insert_post', 'set_testimonial_title_from_name', 20, 3);
        }
    }

    // Suite Match Request
    add_action('wp_ajax_match_salon_suites', 'match_salon_suites_handler');
    add_action('wp_ajax_nopriv_match_salon_suites', 'match_salon_suites_handler');
    function match_salon_suites_handler() {
        
        $profession = $_POST['profession'] ?? '';
        $location = $_POST['location'] ?? '';
        $timeline = $_POST['timeline'] ?? '';
        $budget = $_POST['budget'] ?? '';
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $contactMethod = $_POST['contact_method'] ?? '';

        $submitted_at = current_time( 'mysql' );

        $post_id = wp_insert_post(
            array(
                'post_type'   => 'suite_match_request',
                'post_status' => 'publish',
                'post_title'  => $name . ' - ' . $submitted_at,
            ),
            true
        );

        if ( is_wp_error( $post_id ) ) {
            return $post_id;
        }

        update_post_meta( $post_id, 'profession', $profession);
        update_post_meta( $post_id, 'location_preference', $location);
        update_post_meta( $post_id, 'timeline', $timeline);
        update_post_meta( $post_id, 'budget_weekly', $budget);
        update_post_meta( $post_id, 'contact_name', $name);
        update_post_meta( $post_id, 'contact_email', $email);
        update_post_meta( $post_id, 'contact_phone', $phone);
        update_post_meta( $post_id, 'prefer_contact', $contactMethod);

        // $to      = get_option( 'admin_email' );
        $to      = 'lsdayspaarlington@gmail.com';
        // $to      = 'jamesdoestesting123@yopmail.com';
        $subject = 'New Suite Match Request';
        $body    =
            "New match request received:\n\n" .
            "Name: {$name}\n" .
            "Email: {$email}\n" .
            "Phone: {$phone}\n\n" .
            "Profession: {$profession}\n" .
            "Location Preference: {$location}\n" .
            "Timeline: {$timeline}\n" .
            "Weekly Budget Comfort: {$budget}\n\n" .
            "Saved in admin as Match Request ID: {$post_id}\n";

        wp_mail( $to, $subject, $body );
        
        wp_send_json_success(
            array(
                'message' => __( 'Thanks! We received your info and will contact you soon.', 'custom-widget' ),
                'id'      => $post_id,
            )
        );

        wp_die();
    }
    
    /**
     * Post meta: hide stylist from public listing shortcodes.
     */
    function legacy_get_stylist_hide_from_listing_meta_key() {
        return 'hide_from_stylist_listing';
    }

    /**
     * @param int $post_id Stylist post ID.
     * @return bool
     */
    function legacy_stylist_is_hidden_from_listing( $post_id ) {
        $post_id = absint( $post_id );
        if ( ! $post_id ) {
            return false;
        }

        return '1' === get_post_meta( $post_id, legacy_get_stylist_hide_from_listing_meta_key(), true );
    }

    /**
     * Meta query clause: only stylists that are not restricted from listing.
     *
     * @return array<string, mixed>
     */
    function legacy_get_stylist_listing_visibility_meta_query() {
        $meta_key = legacy_get_stylist_hide_from_listing_meta_key();

        return array(
            'relation' => 'OR',
            array(
                'key'     => $meta_key,
                'compare' => 'NOT EXISTS',
            ),
            array(
                'key'     => $meta_key,
                'value'   => '1',
                'compare' => '!=',
            ),
        );
    }

    /**
     * Append listing-visibility filter to a WP_Query/get_posts args array.
     *
     * @param array<string, mixed> $args Query args (passed by reference).
     */
    function legacy_append_stylist_listing_visibility_meta_query( array &$args ) {
        if ( ! isset( $args['meta_query'] ) || ! is_array( $args['meta_query'] ) ) {
            $args['meta_query'] = array();
        }

        $args['meta_query'][] = legacy_get_stylist_listing_visibility_meta_query();

        if ( count( $args['meta_query'] ) > 1 ) {
            $args['meta_query']['relation'] = 'AND';
        }
    }

    add_action( 'add_meta_boxes', 'legacy_add_stylist_listing_visibility_meta_box' );
    function legacy_add_stylist_listing_visibility_meta_box() {
        add_meta_box(
            'legacy_stylist_listing_visibility',
            __( 'Stylist Listing', 'custom-functions' ),
            'legacy_render_stylist_listing_visibility_meta_box',
            'stylist',
            'normal',
            'high'
        );
    }

    /**
     * @param WP_Post $post Current stylist post.
     */
    function legacy_render_stylist_listing_visibility_meta_box( $post ) {
        if ( ! $post instanceof WP_Post ) {
            return;
        }

        wp_nonce_field( 'legacy_save_stylist_listing_visibility', 'legacy_stylist_listing_visibility_nonce' );

        $meta_key = legacy_get_stylist_hide_from_listing_meta_key();
        $hidden   = legacy_stylist_is_hidden_from_listing( $post->ID );
        ?>
        <p>
            <label>
                <input
                    type="checkbox"
                    name="<?php echo esc_attr( $meta_key ); ?>"
                    value="1"
                    <?php checked( $hidden ); ?>
                />
                <?php esc_html_e( 'Restrict from listing on stylist page and homepage', 'custom-functions' ); ?>
            </label>
        </p>
        <?php
    }

    add_action( 'save_post_stylist', 'legacy_save_stylist_listing_visibility_meta_box', 10, 2 );
    /**
     * @param int     $post_id Post ID.
     * @param WP_Post $post    Post object.
     */
    function legacy_save_stylist_listing_visibility_meta_box( $post_id, $post ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! isset( $_POST['legacy_stylist_listing_visibility_nonce'] ) ||
            ! wp_verify_nonce(
                sanitize_text_field( wp_unslash( $_POST['legacy_stylist_listing_visibility_nonce'] ) ),
                'legacy_save_stylist_listing_visibility'
            ) ) {
            return;
        }

        if ( ! $post instanceof WP_Post || 'stylist' !== $post->post_type ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        $meta_key = legacy_get_stylist_hide_from_listing_meta_key();

        if ( ! empty( $_POST[ $meta_key ] ) ) {
            update_post_meta( $post_id, $meta_key, '1' );
            return;
        }

        delete_post_meta( $post_id, $meta_key );
    }
    

    // Stlist Availability
    // Add metabox
    add_action('add_meta_boxes', 'add_salon_location_metabox');
    function add_salon_location_metabox() {
        add_meta_box(
            'availability',
            'Availability',
            'render_availability_metabox',
            'stylist', 
            'normal',      
            'default'       
        );
    }

    // Render the form
    function render_availability_metabox($post) {

        wp_nonce_field('save_availability_details', 'stylist_availability_nonce');

        $working_hours = get_post_meta($post->ID, 'stylist_availability', true);

        if (!is_array($working_hours)) {
            $working_hours = [];
        }

        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];

        echo '<style>
            table.working-hours-table input[type="time"] { width:120px; }
            table.working-hours-table input[type="checkbox"] { transform:scale(1.3); }
        </style>';

        echo '<table class="widefat working-hours-table">';
        echo '<thead>
                <tr>
                    <th>Day</th>
                    <th>Open</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                </tr>
            </thead>';
        echo '<tbody>';

        foreach ($days as $day) {

            $open  = isset($working_hours[$day]['open']) && $working_hours[$day]['open'] === 'on' ? 'checked' : '';
            $start = isset($working_hours[$day]['start']) ? $working_hours[$day]['start'] : '';
            $end   = isset($working_hours[$day]['end']) ? $working_hours[$day]['end'] : '';

            echo '<tr>';
            echo '<td>' . esc_html($day) . '</td>';
            echo '<td><input type="checkbox" name="working_hours[' . esc_attr($day) . '][open]" ' . $open . '></td>';
            echo '<td><input type="time" name="working_hours[' . esc_attr($day) . '][start]" value="' . esc_attr($start) . '"></td>';
            echo '<td><input type="time" name="working_hours[' . esc_attr($day) . '][end]" value="' . esc_attr($end) . '"></td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    }

    // Save the data
    add_action('save_post', 'save_stylist_availability_data');
    function save_stylist_availability_data($post_id) {

        if (!isset($_POST['stylist_availability_nonce']) || 
            !wp_verify_nonce($_POST['stylist_availability_nonce'], 'save_availability_details')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;

        if (!isset($_POST['working_hours']) || !is_array($_POST['working_hours'])) {
            delete_post_meta($post_id, 'stylist_availability');
            return;
        }

        $sanitized_data = [];

        foreach ($_POST['working_hours'] as $day => $data) {

            $sanitized_data[$day] = [
                'open'  => isset($data['open']) ? 'on' : 'off',
                'start' => isset($data['start']) ? sanitize_text_field($data['start']) : '',
                'end'   => isset($data['end']) ? sanitize_text_field($data['end']) : '',
            ];
        }

        update_post_meta($post_id, 'stylist_availability', $sanitized_data);
    }


    // Stylist Gallery Metabox
    function stylist_gallery_meta_box() {
        add_meta_box(
            'stylist_gallery',
            'Stylist Gallery',
            'stylist_gallery_callback',
            'stylist', 
            'normal',
            'high'
        );
    }
    add_action('add_meta_boxes', 'stylist_gallery_meta_box');

    function stylist_gallery_callback($post) {

        wp_nonce_field('stylist_gallery_nonce_action', 'stylist_gallery_nonce');

        $gallery = get_post_meta($post->ID, 'stylist_gallery', true);
        $gallery = is_array($gallery) ? $gallery : [];

        ?>

        <div>

            <button type="button" class="button button-primary" id="add-gallery">
                Add Images / Videos
            </button>

            <div id="gallery-message" style="margin-top:10px;"></div>

            <ul id="gallery-list"
                style="margin-top:15px; display:flex; flex-wrap:wrap; gap:10px;">

                <?php if (!empty($gallery)) : ?>

                    <?php foreach ($gallery as $item): ?>

                        <li class="gallery-item"
                            data-url="<?php echo esc_url($item); ?>"
                            style="position:relative;width:100px;">

                            <?php if (preg_match('/\.(mp4|webm|ogg)$/i', $item)) : ?>
                                <video src="<?php echo esc_url($item); ?>"
                                    style="width:100px;height:80px;object-fit:cover;" muted></video>
                            <?php else : ?>
                                <img src="<?php echo esc_url($item); ?>"
                                    style="width:100px;height:80px;object-fit:cover;">
                            <?php endif; ?>

                            <button type="button" class="remove-item"
                                style="position:absolute;top:2px;right:2px;
                                background:red;color:#fff;border:none;
                                border-radius:50%;width:18px;height:18px;
                                font-size:12px;cursor:pointer;">
                                ×
                            </button>

                        </li>

                    <?php endforeach; ?>

                <?php else : ?>

                    <li class="empty-gallery" style="color:#999;font-size:13px;">
                        No media added yet
                    </li>

                <?php endif; ?>

            </ul>

            <input type="hidden"
                name="stylist_gallery_data"
                id="stylist-gallery-data"
                value="<?php echo esc_attr(wp_json_encode($gallery)); ?>">

        </div>

        <?php
    }

    add_action('admin_footer', function () {
        global $post;

        if (!$post || $post->post_type !== 'stylist') return;
        ?>

        <script>
        jQuery(document).ready(function($){

            let frame;

            function syncGallery() {
                let data = [];

                $('#gallery-list .gallery-item').each(function () {
                    data.push($(this).data('url'));
                });

                $('#stylist-gallery-data').val(JSON.stringify(data));

                if (data.length > 0) {
                    $('.empty-gallery').remove();
                }
            }

            function showMessage(msg) {
                $('#gallery-message').html(
                    `<div style="background:#fef3c7;color:#92400e;
                    padding:8px;border-radius:6px;font-size:13px;">
                    ${msg}
                    </div>`
                );
            }

            $('#add-gallery').on('click', function(e){
                e.preventDefault();

                if(frame){
                    frame.open();
                    return;
                }

                frame = wp.media({
                    title: 'Select Images or Videos',
                    button: { text: 'Add to Gallery' },
                    multiple: true
                });

                frame.on('select', function(){

                    let files = frame.state().get('selection').toJSON();

                    let existing = [];

                    $('#gallery-list .gallery-item').each(function(){
                        existing.push($(this).data('url'));
                    });

                    let currentCount = existing.length;
                    let remaining = 9 - currentCount;

                    if (remaining <= 0) {
                        showMessage("⚠️ Maximum 9 items allowed in gallery.");
                        return;
                    }

                    let addedCount = 0;

                    files.forEach(function(file){

                        if (existing.includes(file.url)) return;
                        if (addedCount >= remaining) return;

                        addedCount++;

                        let html = `
                        <li class="gallery-item"
                            data-url="${file.url}"
                            style="position:relative;width:100px;">

                            ${file.type === 'video'
                                ? `<video src="${file.url}" style="width:100px;height:80px;object-fit:cover;" muted></video>`
                                : `<img src="${file.url}" style="width:100px;height:80px;object-fit:cover;">`
                            }

                            <button type="button" class="remove-item"
                                style="position:absolute;top:2px;right:2px;
                                background:red;color:#fff;border:none;
                                border-radius:50%;width:18px;height:18px;
                                font-size:12px;cursor:pointer;">
                                ×
                            </button>

                        </li>`;

                        $('.empty-gallery').remove();
                        $('#gallery-list').append(html);
                    });

                    syncGallery();

                    if (files.length > remaining) {
                        showMessage(`⚠️ Only ${remaining} item(s) added. Max limit is 9.`);
                    } else {
                        $('#gallery-message').html('');
                    }

                });

                frame.open();
            });

            $(document).on('click', '.remove-item', function(){
                $(this).closest('.gallery-item').remove();
                syncGallery();
            });

        });
        </script>

        <?php
    });

    function save_stylist_gallery($post_id) {

        if (!isset($_POST['stylist_gallery_nonce']) ||
            !wp_verify_nonce($_POST['stylist_gallery_nonce'], 'stylist_gallery_nonce_action')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

        if (isset($_POST['stylist_gallery_data'])) {

            $data = json_decode(stripslashes($_POST['stylist_gallery_data']), true);
            if (!is_array($data)) {
                $data = [];
            }
            $data = array_slice($data, 0, 9);

            update_post_meta($post_id, 'stylist_gallery', $data);
        }
    }
    add_action('save_post', 'save_stylist_gallery');
    
    
    /**
     * Stylist experience (years) — digits only.
     */
    function legacy_sanitize_stylist_experience_value( $value ) {
       $digits = preg_replace( '/\D+/', '', (string) $value );

        return substr( $digits, 0, 3 );
    }

    function legacy_configure_stylist_experience_field( $field ) {
        $field['type']   = 'number';
        $field['min']    = 0;
        $field['max']    = 999;
        $field['step']   = 1;
        $field['append'] = '';

        return $field;
    }
    add_filter( 'acf/load_field/name=experience', 'legacy_configure_stylist_experience_field' );

    function legacy_validate_stylist_experience_value( $valid, $value, $field, $input ) {
        if ( true !== $valid || '' === $value ) {
            return $valid;
        }

        if ( ! preg_match( '/^\d{1,3}$/', (string) $value ) ) {
            return __( 'Experience must be a whole number up to 3 digits.', 'custom-functions' );
        }

        if ( (int) $value > 999 ) {
            return __( 'Experience cannot be more than 999 years.', 'custom-functions' );
        }

        return $valid;
    }
    add_filter( 'acf/validate_value/name=experience', 'legacy_validate_stylist_experience_value', 10, 4 );

    function legacy_update_stylist_experience_value( $value, $post_id, $field ) {
        if ( '' === $value || null === $value ) {
            return '';
        }

        return legacy_sanitize_stylist_experience_value( $value );
    }
    add_filter( 'acf/update_value/name=experience', 'legacy_update_stylist_experience_value', 10, 3 );

    function legacy_sanitize_stylist_experience_on_save( $post_id ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        $experience = get_post_meta( $post_id, 'experience', true );

        if ( '' === $experience || null === $experience ) {
            return;
        }

        $sanitized = legacy_sanitize_stylist_experience_value( $experience );

        if ( $sanitized !== (string) $experience ) {
            update_post_meta( $post_id, 'experience', $sanitized );
        }
    }
    add_action( 'save_post_stylist', 'legacy_sanitize_stylist_experience_on_save', 20 );



    // Suite Transformation Post Type

    function register_suites_transformation_post_type() {
        register_post_type(
            'suites_transform',
            array(
                'labels' => array(
                    'name'          => 'Suites Transformation',
                    'singular_name' => 'Suite Transformation',
                    'add_new_item'  => 'Add Suite Transformation',
                    'edit_item'     => 'Edit Suite Transformation',
                    'all_items'     => 'All Suites Transformation',
                ),
                'public'              => true,
                'publicly_queryable'  => true,
                'show_ui'             => true,
                'show_in_menu'        => true,
                'menu_position'       => 25,
                'menu_icon'           => 'dashicons-images-alt2',
                'has_archive'         => true,
                'show_in_rest'        => true,
                'rewrite'             => array( 'slug' => 'suites-transformation' ),
                'capability_type'     => 'post',
                'map_meta_cap'        => true,
                'supports'            => array(
                    'title',
                    'thumbnail',
                    'page-attributes',
                ),
            )
        );
    }

    add_action( 'init', 'register_suites_transformation_post_type' );
    add_action( 'init', 'st_register_suites_transform_meta' );

    /**
     * Register post meta for suites_transform (REST + classic editor).
     */
    function st_register_suites_transform_meta() {
        register_post_meta(
            'suites_transform',
            ST_META_VIDEO,
            array(
                'type'              => 'integer',
                'single'            => true,
                'show_in_rest'      => true,
                'sanitize_callback' => 'absint',
                'auth_callback'     => function () {
                    return current_user_can( 'edit_posts' );
                },
            )
        );
    }

    /**
     * -----------------------------------------------------------------------------
     * Suites Transformation — meta keys & helpers
     * -----------------------------------------------------------------------------
     */
    if ( ! defined( 'ST_META_SHORT_DESC' ) ) {
        define( 'ST_META_SHORT_DESC', 'st_short_description' );
    }
    if ( ! defined( 'ST_META_BEFORE_IMAGE' ) ) {
        define( 'ST_META_BEFORE_IMAGE', 'st_before_image_id' );
    }
    if ( ! defined( 'ST_META_AFTER_IMAGE' ) ) {
        define( 'ST_META_AFTER_IMAGE', 'st_after_image_id' );
    }
    if ( ! defined( 'ST_META_VIDEO' ) ) {
    define( 'ST_META_VIDEO', 'st_meta_video' );
    }

    /**
     * Allow auto-draft inserts for suites_transform (avoids empty-content rejection edge cases).
     *
     * @param bool  $maybe_empty Whether the post is considered empty.
     * @param array $postarr     Post data.
     * @return bool
     */
    function st_allow_suites_transform_auto_draft( $maybe_empty, $postarr ) {
        if (
            ! empty( $postarr['post_type'] )
            && 'suites_transform' === $postarr['post_type']
            && ! empty( $postarr['post_status'] )
            && 'auto-draft' === $postarr['post_status']
        ) {
            return false;
        }
        return $maybe_empty;
    }
    add_filter( 'wp_insert_post_empty_content', 'st_allow_suites_transform_auto_draft', 5, 2 );

    /**
     * Default placeholder when before/after images are missing.
     *
     * @return string
     */
    function st_get_fallback_image_url() {
        $fallback = apply_filters(
            'st_transform_fallback_image_url',
            home_url( '/wp-content/uploads/2026/01/imageprofile.png' )
        );
        return esc_url( $fallback );
    }

    /**
     * Resolve attachment ID to image URL with fallback.
     *
     * @param int    $attachment_id Attachment post ID.
     * @param string $size          Image size slug.
     * @return string
     */
    function st_get_transform_image_url( $attachment_id, $size = 'large' ) {
        $attachment_id = absint( $attachment_id );
        if ( ! $attachment_id || ! wp_attachment_is_image( $attachment_id ) ) {
            return st_get_fallback_image_url();
        }

        $url = wp_get_attachment_image_url( $attachment_id, $size );
        if ( ! $url ) {
            $url = wp_get_attachment_image_url( $attachment_id, 'full' );
        }
        if ( ! $url ) {
            $url = wp_get_attachment_url( $attachment_id );
        }

        return $url ? esc_url( $url ) : st_get_fallback_image_url();
    }

    /**
     * Resolve video attachment ID to URL.
     *
     * @param int|string $attachment_id Attachment post ID.
     * @return string Empty string when missing or invalid.
     */
function st_get_transform_video_url( $video_meta ) {
    if ( empty( $video_meta ) ) {
        return '';
    }

    if ( is_numeric( $video_meta ) ) {
        $attachment_id = absint( $video_meta );
        if ( ! $attachment_id ) {
            return '';
        }
        $url = wp_get_attachment_url( $attachment_id );
        return $url ? esc_url( $url ) : '';
    }

    $video_meta = trim( (string) $video_meta );
    if ( filter_var( $video_meta, FILTER_VALIDATE_URL ) ) {
        return esc_url( $video_meta );
    }

    return '';
}

    /**
     * MIME type for a video attachment.
     *
     * @param int|string $attachment_id Attachment post ID.
     * @return string
     */
    function st_get_transform_video_mime( $attachment_id ) {
        $attachment_id = absint( $attachment_id );
        if ( ! $attachment_id ) {
            return 'video/mp4';
        }

        $mime = get_post_mime_type( $attachment_id );
    return $mime ? $mime : 'video/mp4';
}

/**
 * Whether attachment is a valid video.
 *
 * @param int $attachment_id Attachment post ID.
 * @return bool
 */
function st_is_valid_video_attachment( $attachment_id ) {
    $attachment_id = absint( $attachment_id );
    if ( ! $attachment_id || 'attachment' !== get_post_type( $attachment_id ) ) {
        return false;
    }

    $mime = get_post_mime_type( $attachment_id );
    return $mime && 0 === strpos( $mime, 'video/' );
}

/**
 * Flag: shortcode rendered on this request (for conditional asset loading).
 */
    function st_mark_suites_transformation_shortcode() {
        global $st_suites_transformation_shortcode_used;
        $st_suites_transformation_shortcode_used = true;
    }

    /**
     * @return bool
     */
    function st_is_suites_transformation_shortcode_used() {
        global $st_suites_transformation_shortcode_used;
        return ! empty( $st_suites_transformation_shortcode_used );
    }

    /**
     * -----------------------------------------------------------------------------
     * Admin meta box
     * -----------------------------------------------------------------------------
     */
    add_action( 'add_meta_boxes', 'st_add_suites_transform_meta_box' );

    function st_add_suites_transform_meta_box() {
        add_meta_box(
            'st_suites_transform_details',
            __( 'Transformation Details', 'custom-functions' ),
            'st_render_suites_transform_meta_box',
            'suites_transform',
            'normal',
            'high'
        );
    }

    /**
     * Render meta box fields (short description, before/after images).
     *
     * @param WP_Post $post Current post object.
     */
    function st_render_suites_transform_meta_box( $post ) {
        if ( ! $post instanceof WP_Post ) {
            return;
        }

        wp_nonce_field( 'st_save_suites_transform_meta', 'st_suites_transform_nonce' );

        $short_desc   = get_post_meta( $post->ID, ST_META_SHORT_DESC, true );
        $before_id    = absint( get_post_meta( $post->ID, ST_META_BEFORE_IMAGE, true ) );
        $after_id     = absint( get_post_meta( $post->ID, ST_META_AFTER_IMAGE, true ) );
        $before_url   = $before_id ? wp_get_attachment_image_url( $before_id, 'medium' ) : '';
        $after_url    = $after_id ? wp_get_attachment_image_url( $after_id, 'medium' ) : '';
        $video_id        = get_post_meta( $post->ID, ST_META_VIDEO, true );
        
        
        ?>
        <div class="st-transform-metabox">
            <p class="st-metabox-intro">
                <?php esc_html_e( 'Add a short summary and before/after photos for this suite transformation.', 'custom-functions' ); ?>
            </p>

            <div class="st-field st-field--full">
                <label for="st_short_description"><?php esc_html_e( 'Short Description', 'custom-functions' ); ?></label>
                <textarea
                    id="st_short_description"
                    name="st_short_description"
                    rows="4"
                    class="widefat"
                    placeholder="<?php esc_attr_e( 'Brief description shown on the frontend carousel…', 'custom-functions' ); ?>"
                ><?php echo esc_textarea( $short_desc ); ?></textarea>
                
            </div>
            <?php st_render_video_field( absint( $video_id ) ); ?>

            <div class="st-image-fields">
                <?php
                st_render_image_field( 'before', __( 'Before Image', 'custom-functions' ), $before_id, $before_url );
                st_render_image_field( 'after', __( 'After Image', 'custom-functions' ), $after_id, $after_url );
                ?>
            </div>
        </div>
        <?php
    }

    /**
     * Video field — WordPress media library (saves attachment ID to post meta).
     *
     * @param int $video_id Attachment ID.
     */
    function st_render_video_field( $video_id ) {
        $video_id  = absint( $video_id );
        $video_url = $video_id ? wp_get_attachment_url( $video_id ) : '';
        $has_video = $video_id && $video_url && st_is_valid_video_attachment( $video_id );
        $title     = $has_video ? get_the_title( $video_id ) : '';
        ?>
        <div class="st-field st-field--full st-video-field" data-st-video-field>
            <label><?php esc_html_e( 'Suite Transformation Video', 'custom-functions' ); ?></label>
            <input
                type="hidden"
                name="st_video_id"
                id="st_video_id"
                value="<?php echo esc_attr( $video_id ); ?>"
            />
            <div class="st-video-preview<?php echo $has_video ? ' has-video' : ''; ?>" id="st_video_preview">
                <?php if ( $has_video ) : ?>
                    <video src="<?php echo esc_url( $video_url ); ?>" controls preload="metadata"></video>
                    <p class="st-video-filename"><?php echo esc_html( $title ); ?></p>
                <?php else : ?>
                    <span class="st-video-placeholder"><?php esc_html_e( 'No video selected', 'custom-functions' ); ?></span>
                <?php endif; ?>
            </div>
            <div class="st-video-actions">
                <button type="button" class="button button-primary st-upload-video">
                    <?php esc_html_e( 'Select / Upload Video', 'custom-functions' ); ?>
                </button>
                <button type="button" class="button st-remove-video"<?php echo $has_video ? '' : ' style="display:none;"'; ?>>
                    <?php esc_html_e( 'Remove Video', 'custom-functions' ); ?>
                </button>
            </div>
            <p class="description">
                <?php esc_html_e( 'Choose a video from the Media Library. The attachment ID is saved when you click Update.', 'custom-functions' ); ?>
            </p>
        </div>
        <?php
    }

    /**
     * Single image upload field markup.
     *
     * @param string $key   Field key: before|after.
     * @param string $label Field label.
     * @param int    $id    Attachment ID.
     * @param string $url   Preview URL.
     */
    function st_render_image_field( $key, $label, $id, $url ) {
        $has_image = ! empty( $id ) && ! empty( $url );
        ?>
        <div class="st-field st-image-field" data-st-image-field="<?php echo esc_attr( $key ); ?>">
            <label><?php echo esc_html( $label ); ?></label>
            <input
                type="hidden"
                name="st_<?php echo esc_attr( $key ); ?>_image_id"
                id="st_<?php echo esc_attr( $key ); ?>_image_id"
                value="<?php echo esc_attr( $id ); ?>"
            />
            <div class="st-image-preview<?php echo $has_image ? ' has-image' : ''; ?>" id="st_<?php echo esc_attr( $key ); ?>_preview">
                <?php if ( $has_image ) : ?>
                    <img src="<?php echo esc_url( $url ); ?>" alt="" />
                <?php else : ?>
                    <span class="st-image-placeholder"><?php esc_html_e( 'No image selected', 'custom-functions' ); ?></span>
                <?php endif; ?>
            </div>
            <div class="st-image-actions">
                <button type="button" class="button button-primary st-upload-image" data-target="<?php echo esc_attr( $key ); ?>">
                    <?php esc_html_e( 'Upload / Select', 'custom-functions' ); ?>
                </button>
                <button type="button" class="button st-remove-image" data-target="<?php echo esc_attr( $key ); ?>"<?php echo $has_image ? '' : ' style="display:none;"'; ?>>
                    <?php esc_html_e( 'Remove', 'custom-functions' ); ?>
                </button>
            </div>
        </div>
        <?php
    }

    /**
     * Admin styles for the transformation meta box (suites_transform edit screens only).
     *
     * @param string $hook Current admin page hook.
     */
    function st_enqueue_suites_transform_admin_assets( $hook ) {
        if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
            return;
        }

        $screen = get_current_screen();
        if ( ! $screen || 'suites_transform' !== $screen->post_type ) {
            return;
        }

        wp_enqueue_media();

        $admin_js = plugin_dir_path( __FILE__ ) . 'js/admin.js';
        if ( file_exists( $admin_js ) ) {
            wp_enqueue_script(
                'st-suites-transform-admin',
                plugin_dir_url( __FILE__ ) . 'js/admin.js',
                array( 'jquery' ),
                (string) filemtime( $admin_js ),
                true
            );
        }

        wp_add_inline_style( 'wp-admin', st_get_admin_metabox_css() );
    }
    add_action( 'admin_enqueue_scripts', 'st_enqueue_suites_transform_admin_assets', 15 );

    /**
     * Inline admin CSS for meta box UI.
     *
     * @return string
     */
    function st_get_admin_metabox_css() {
        return '
        .st-transform-metabox { max-width: 920px; }
        .st-metabox-intro { color: #646970; margin: 0 0 16px; font-size: 13px; }
        .st-field { margin-bottom: 18px; }
        .st-field label { display: block; font-weight: 600; margin-bottom: 8px; color: #1d2327; }
        .st-image-fields { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
        .st-image-field { background: #f6f7f7; border: 1px solid #dcdcde; border-radius: 10px; padding: 16px; }
        .st-image-preview { background: #fff; border: 1px dashed #c3c4c7; border-radius: 8px; min-height: 160px; display: flex; align-items: center; justify-content: center; overflow: hidden; margin-bottom: 12px; }
        .st-image-preview img { width: 100%; height: auto; max-height: 220px; object-fit: cover; display: block; }
        .st-image-preview.has-image .st-image-placeholder { display: none; }
        .st-image-placeholder { color: #a7aaad; font-size: 13px; }
        .st-image-actions { display: flex; flex-wrap: wrap; gap: 8px; }
        .st-video-field { background: #f6f7f7; border: 1px solid #dcdcde; border-radius: 10px; padding: 16px; margin-bottom: 18px; }
        .st-video-preview { background: #fff; border: 1px dashed #c3c4c7; border-radius: 8px; min-height: 120px; display: flex; flex-direction: column; align-items: center; justify-content: center; overflow: hidden; margin-bottom: 12px; padding: 12px; }
        .st-video-preview video { max-width: 100%; max-height: 220px; display: block; }
        .st-video-preview.has-video .st-video-placeholder { display: none; }
        .st-video-filename { margin: 8px 0 0; font-size: 12px; color: #50575e; }
        .st-video-placeholder { color: #a7aaad; font-size: 13px; }
        .st-video-actions { display: flex; flex-wrap: wrap; gap: 8px; }
        ';
    }

    /**
     * -----------------------------------------------------------------------------
     * Save meta box data (nonce, autosave, capabilities, sanitization)
     * -----------------------------------------------------------------------------
     */
    add_action( 'save_post_suites_transform', 'st_save_suites_transform_meta', 10, 2 );

    /**
     * @param int     $post_id Post ID.
     * @param WP_Post $post    Post object.
     */
    function st_save_suites_transform_meta( $post_id, $post ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! isset( $_POST['st_suites_transform_nonce'] ) ||
            ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['st_suites_transform_nonce'] ) ), 'st_save_suites_transform_meta' ) ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        if ( isset( $_POST['st_short_description'] ) ) {
            update_post_meta(
                $post_id,
                ST_META_SHORT_DESC,
                sanitize_textarea_field( wp_unslash( $_POST['st_short_description'] ) )
            );
        }

        if ( isset( $_POST['st_video_id'] ) ) {
            $video_id = absint( wp_unslash( $_POST['st_video_id'] ) );
            if ( $video_id && ! st_is_valid_video_attachment( $video_id ) ) {
                $video_id = 0;
            }
            if ( $video_id ) {
                update_post_meta( $post_id, ST_META_VIDEO, $video_id );
            } else {
                delete_post_meta( $post_id, ST_META_VIDEO );
            }
        }

        if ( ! empty( $_FILES['st_video']['name'] ) ) {
            if ( ! function_exists( 'media_handle_upload' ) ) {
                require_once ABSPATH . 'wp-admin/includes/image.php';
                require_once ABSPATH . 'wp-admin/includes/file.php';
                require_once ABSPATH . 'wp-admin/includes/media.php';
            }

            $uploaded_video_id = media_handle_upload( 'st_video', $post_id );

            if ( ! is_wp_error( $uploaded_video_id ) ) {
                update_post_meta( $post_id, ST_META_VIDEO, $uploaded_video_id );
            }
        }



        $before_id = isset( $_POST['st_before_image_id'] ) ? absint( $_POST['st_before_image_id'] ) : 0;
        $after_id  = isset( $_POST['st_after_image_id'] ) ? absint( $_POST['st_after_image_id'] ) : 0;
        

        if ( $before_id && ! wp_attachment_is_image( $before_id ) ) {
            $before_id = 0;
        }
        if ( $after_id && ! wp_attachment_is_image( $after_id ) ) {
            $after_id = 0;
        }

        update_post_meta( $post_id, ST_META_BEFORE_IMAGE, $before_id );
        update_post_meta( $post_id, ST_META_AFTER_IMAGE, $after_id );

    }

    /**
     * -----------------------------------------------------------------------------
     * Frontend assets — only when [suites_transformation] shortcode is used
     * -----------------------------------------------------------------------------
     */
    add_action( 'wp_enqueue_scripts', 'st_enqueue_suites_transformation_frontend_assets', 30 );

    function st_enqueue_suites_transformation_frontend_assets() {
        if ( ! st_should_load_suites_transformation_assets() ) {
            return;
        }

        $plugin_url = plugin_dir_url( __FILE__ );
        $plugin_path = plugin_dir_path( __FILE__ );

        if ( ! wp_style_is( 'swiper-css', 'enqueued' ) ) {
            wp_enqueue_style(
                'swiper-css',
                'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
                array(),
                '11'
            );
        }

        if ( ! wp_script_is( 'swiper-js', 'enqueued' ) ) {
            wp_enqueue_script(
                'swiper-js',
                'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
                array(),
                '11',
                true
            );
        }

        $style_path = $plugin_path . 'style.css';
        if ( file_exists( $style_path ) ) {
            wp_enqueue_style(
                'suites-transformation',
                $plugin_url . 'style.css',
                array( 'swiper-css' ),
                (string) filemtime( $style_path )
            );
        }

        $script_path = $plugin_path . 'js/suites-transformation.js';
        if ( file_exists( $script_path ) ) {
            wp_enqueue_script(
                'suites-transformation',
                $plugin_url . 'js/suites-transformation.js',
                array( 'jquery', 'swiper-js' ),
                (string) filemtime( $script_path ),
                true
            );
        }

        $video_script = $plugin_path . 'js/st-video-modal.js';
        if ( file_exists( $video_script ) ) {
            wp_enqueue_script(
                'st-video-modal',
                $plugin_url . 'js/st-video-modal.js',
                array(),
                (string) filemtime( $video_script ),
                true
            );
        }
    }

    /**
     * Ensure assets load when shortcode renders after wp_enqueue_scripts (e.g. Beaver Builder).
     */
    function st_footer_enqueue_suites_transform_assets() {
        if ( ! st_is_suites_transformation_shortcode_used() ) {
            return;
        }
        st_enqueue_suites_transformation_frontend_assets();
    }
    add_action( 'wp_footer', 'st_footer_enqueue_suites_transform_assets', 1 );

    /**
     * Whether to load transformation frontend assets (shortcode flag or has_shortcode).
     *
     * @return bool
     */
    function st_should_load_suites_transformation_assets() {
        if ( st_is_suites_transformation_shortcode_used() ) {
            return true;
        }

        if ( is_singular() ) {
            global $post;
            if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'suites_transformation' ) ) {
                return true;
            }
        }

        return false;
    }

    add_action( 'post_edit_form_tag', 'st_add_post_enctype' );

    function st_add_post_enctype() {
        $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
        if ( $screen && 'suites_transform' === $screen->post_type ) {
            echo ' enctype="multipart/form-data"';
        }
    }
    
    /**
 * Flag: featured suite swiper shortcode rendered in slider mode (for conditional hooks).
 */
function custom_functions_mark_featured_suite_swiper_used() {
    global $custom_functions_featured_suite_swiper_used;
    $custom_functions_featured_suite_swiper_used = true;
}

/**
 * @return bool
 */
function custom_functions_is_featured_suite_swiper_used() {
    global $custom_functions_featured_suite_swiper_used;
    return ! empty( $custom_functions_featured_suite_swiper_used );
}

/**
 * Image size slug for suite card "before" overlay thumbnails (featured_suites shortcode).
 * Hard-cropped to 72×72 px; WordPress generates this on upload from the full attachment.
 */
define('CUSTOM_FUNCTIONS_SUITE_BEFORE_SIZE', 'suite_before_overlay');

/**
 * Register custom image sizes used by Custom Functions shortcodes.
 */
function custom_functions_register_image_sizes() {
    add_image_size(
        CUSTOM_FUNCTIONS_SUITE_BEFORE_SIZE,
        72,
        72,
        true // Hard crop — exact 72×72 square (center crop).
    );
}
add_action('after_setup_theme', 'custom_functions_register_image_sizes');

/**
 * Ensure the 72×72 cropped derivative exists (legacy uploads uploaded before this size existed).
 *
 * Runs metadata regeneration only when the intermediate file is missing, so each attachment
 * is processed at most once. New uploads get the size automatically via wp_generate_attachment_metadata().
 *
 * @param int $attachment_id Attachment post ID.
 * @return bool True when the suite_before_overlay size is available.
 */
function custom_functions_ensure_suite_before_overlay_size($attachment_id) {
    $attachment_id = absint($attachment_id);

    if (!$attachment_id || !wp_attachment_is_image($attachment_id)) {
        return false;
    }

    $size = CUSTOM_FUNCTIONS_SUITE_BEFORE_SIZE;

    if (image_get_intermediate_size($attachment_id, $size)) {
        return true;
    }

    if (!function_exists('wp_generate_attachment_metadata')) {
        require_once ABSPATH . 'wp-admin/includes/image.php';
    }

    $file = get_attached_file($attachment_id);
    if (!$file || !is_readable($file)) {
        return false;
    }

    $metadata = wp_generate_attachment_metadata($attachment_id, $file);
    if (is_wp_error($metadata) || empty($metadata)) {
        return false;
    }

    wp_update_attachment_metadata($attachment_id, $metadata);

    return (bool) image_get_intermediate_size($attachment_id, $size);
}

/**
 * Optimized 72×72 before-image markup for suite cards (lazy-loaded, no decorative alt).
 *
 * @param int $attachment_id Attachment post ID from before_image_thumbnail meta.
 * @return string HTML from wp_get_attachment_image(), or empty string if unavailable.
 */
function custom_functions_get_suite_before_overlay_image_html($attachment_id) {
    if (!custom_functions_ensure_suite_before_overlay_size($attachment_id)) {
        return '';
    }

    return wp_get_attachment_image(
        $attachment_id,
        CUSTOM_FUNCTIONS_SUITE_BEFORE_SIZE,
        false,
        array(
            'class'    => 'suite-before-overlay__img',
            'alt'      => '',
            'loading'  => 'lazy',
            'decoding' => 'async',
            'sizes'    => '(max-width: 575px) 52px, (max-width: 991px) 64px, 72px',
        )
    );
}


    // SignUp A Suite — store requests and admin list
    add_action( 'init', 'register_signup_suite_request_cpt' );
    function register_signup_suite_request_cpt() {
        register_post_type(
            'signup_suite_request',
            array(
                'labels'              => array(
                    'name'          => __( 'Find A Suite Request', 'custom-widget' ),
                    'singular_name' => __( 'Find A Suite Request', 'custom-widget' ),
                ),
                'public'              => false,
                'show_ui'             => false,
                'show_in_menu'        => false,
                'show_in_admin_bar'   => false,
                'exclude_from_search' => true,
                'publicly_queryable'  => false,
                'supports'            => array( 'title' ),
                'capability_type'     => 'post',
                'map_meta_cap'        => true,
                'capabilities'        => array(
                    'edit_posts'          => 'manage_options',
                    'edit_others_posts'   => 'manage_options',
                    'publish_posts'       => 'manage_options',
                    'read_private_posts'  => 'manage_options',
                    'create_posts'        => 'manage_options',
                    'delete_posts'        => 'manage_options',
                ),
            )
        );
    }

    
      /**
     * Stylist service taxonomy options for the Find a Suite form.
     *
     * @return array<string, string> Term slug => label.
     */
    function signup_a_suite_get_profession_service_options() {
        $terms = get_terms(
            array(
                'taxonomy'   => 'stylist_service',
                'hide_empty' => false,
                'orderby'    => 'name',
                'order'      => 'ASC',
            )
        );
          if ( is_wp_error( $terms ) || empty( $terms ) ) {
            return array();
        }

        $options = array();

        foreach ( $terms as $term ) {
            if ( $term instanceof WP_Term ) {
                $options[ $term->slug ] = $term->name;
            }
        }

        return $options;
    }

    function signup_a_suite_get_location_labels() {
        return array(
            'little_road' => __( 'Little Road', 'custom-widget' ),
            'cooper'      => __( 'Cooper', 'custom-widget' ),
        );
    }

    /**
     * Legacy location values mapped to current preferred_location slugs.
     *
     * @return array<string, string>
     */
    function signup_a_suite_get_location_aliases() {
        return array(
            'village'     => 'little_road',
            'little-road' => 'little_road',
            'little road' => 'little_road',
        );
    }

    add_action( 'admin_menu', 'signup_suite_requests_admin_menu' );
    function signup_suite_requests_admin_menu() {
        add_menu_page(
           __( 'Find A Suite Request', 'custom-widget' ),
            __( 'Find A Suite Request', 'custom-widget' ),
            'manage_options',
            'signup-suite-requests',
            'render_signup_suite_requests_admin_page',
            'dashicons-email-alt',
            26
        );
    }

    function render_signup_suite_requests_admin_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $requests = get_posts(
            array(
                'post_type'          => 'signup_suite_request',
                'post_status'        => array( 'publish', 'private', 'draft', 'pending' ),
                'posts_per_page'     => -1,
                'orderby'            => 'date',
                'order'              => 'DESC',
                'perm'               => '',
                'suppress_filters'   => true,
                'no_found_rows'      => true,
                'update_post_meta_cache' => true,
            )
        );
        ?>
        <div class="wrap">
           <h1><?php esc_html_e( 'Find A Suite Request', 'custom-widget' ); ?></h1>
            <?php if ( ! empty( $requests ) ) : ?>
                <p class="description">
                    <?php
                    printf(
                        /* translators: %d: number of submissions */
                        esc_html( _n( '%d submission', '%d submissions', count( $requests ), 'custom-widget' ) ),
                        (int) count( $requests )
                    );
                    ?>
                </p>
            <?php endif; ?>
            <?php if ( empty( $requests ) ) : ?>
                <p><?php esc_html_e( 'No form submissions yet.', 'custom-widget' ); ?></p>
            <?php else : ?>
                <table class="widefat fixed striped" style="margin-top: 16px;">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'First Name', 'custom-widget' ); ?></th>
                            <th><?php esc_html_e( 'Last Name', 'custom-widget' ); ?></th>
                            <th><?php esc_html_e( 'Email', 'custom-widget' ); ?></th>
                            <th><?php esc_html_e( 'Phone', 'custom-widget' ); ?></th>
                            <th><?php esc_html_e( 'Preferred Suite', 'custom-widget' ); ?></th>
                            <th><?php esc_html_e( 'Preferred Location', 'custom-widget' ); ?></th>
                             <th><?php esc_html_e( 'Profession/Services', 'custom-widget' ); ?></th>
                            <th><?php esc_html_e( 'Discount (%)', 'custom-widget' ); ?></th>
                            <th><?php esc_html_e( 'Message', 'custom-widget' ); ?></th>
                            <th style="width: 120px;"><?php esc_html_e( 'Submitted', 'custom-widget' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $requests as $request ) : ?>
                            <?php
                            $request_id = $request->ID;
                            $first_name = get_post_meta( $request_id, 'first_name', true );
                            $last_name  = get_post_meta( $request_id, 'last_name', true );
                            $email      = get_post_meta( $request_id, 'email', true );
                            $phone      = get_post_meta( $request_id, 'phone', true );
                            $preferred_suite = get_post_meta( $request_id, 'preferred_suite', true );
                            $location   = get_post_meta( $request_id, 'preferred_location_label', true );
                            if ( '' === $location ) {
                                $location = get_post_meta( $request_id, 'preferred_location', true );
                            }
                            $profession_services = get_post_meta( $request_id, 'profession_services_label', true );
                            if ( '' === $profession_services ) {
                                $profession_services = get_post_meta( $request_id, 'reason_label', true );
                            }
                            if ( '' === $profession_services ) {
                                $profession_services = get_post_meta( $request_id, 'reason', true );
                            }
                            // $message = get_post_meta( $request_id, 'message', true );
                            $message  = get_post_meta( $request_id, 'message', true );
                            $discount = get_post_meta( $request_id, 'discount_percentage', true );
                            if ( '' !== $discount && false !== $discount && null !== $discount ) {
                                $discount = absint( $discount ) . '%';
                            } else {
                                $discount = '—';
                            }
                            ?>
                            <tr>
                                <td><?php echo esc_html( $first_name ); ?></td>
                                <td><?php echo esc_html( $last_name ); ?></td>
                                <td>
                                    <?php if ( $email ) : ?>
                                        <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo esc_html( $phone ); ?></td>
                                <td><?php echo esc_html( $preferred_suite ? $preferred_suite : '—' ); ?></td>
                                <td><?php echo esc_html( $location ); ?></td>
                                 <td><?php echo esc_html( $profession_services ? $profession_services : '—' ); ?></td>
                                <td><?php echo esc_html( $discount ); ?></td>
                                <td style="max-width: 320px; white-space: pre-wrap;"><?php echo esc_html( $message ); ?></td>
                                <td><?php echo esc_html( get_the_date( 'M j, Y', $request ) ); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <?php
    }

    add_action( 'wp_ajax_signup_a_suite_contact', 'signup_a_suite_contact_handler' );
    add_action( 'wp_ajax_nopriv_signup_a_suite_contact', 'signup_a_suite_contact_handler' );
    function signup_a_suite_contact_handler() {
        check_ajax_referer( 'signup_a_suite_contact', 'nonce' );

        $first_name = sanitize_text_field( wp_unslash( $_POST['first_name'] ?? '' ) );
        $last_name  = sanitize_text_field( wp_unslash( $_POST['last_name'] ?? '' ) );
        $email      = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
        $phone              = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
        $preferred_suite    = sanitize_text_field( wp_unslash( $_POST['preferred_suite'] ?? '' ) );
        $preferred_location = sanitize_text_field( wp_unslash( $_POST['preferred_location'] ?? '' ) );
         if ( function_exists( 'legacy_normalize_preferred_location_slug' ) ) {
            $normalized_location = legacy_normalize_preferred_location_slug( $preferred_location );
            if ( '' !== $normalized_location ) {
                $preferred_location = $normalized_location;
            }
        }
         $profession_services_raw = isset( $_POST['profession_services'] ) ? wp_unslash( $_POST['profession_services'] ) : array();
        if ( ! is_array( $profession_services_raw ) ) {
            $profession_services_raw = array( $profession_services_raw );
        }
        $message             = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );
        $discount_percentage = isset( $_POST['discount_percentage'] ) ? absint( wp_unslash( $_POST['discount_percentage'] ) ) : 0;
        if ( $discount_percentage > 100 ) {
            $discount_percentage = 100;
        }

      $service_options        = signup_a_suite_get_profession_service_options();
        $profession_services    = array();
        $profession_service_labels = array();

        foreach ( $profession_services_raw as $service_slug ) {
            $service_slug = sanitize_title( (string) $service_slug );
            if ( '' === $service_slug || ! isset( $service_options[ $service_slug ] ) ) {
                continue;
            }
            if ( in_array( $service_slug, $profession_services, true ) ) {
                continue;
            }
            $profession_services[]         = $service_slug;
            $profession_service_labels[] = $service_options[ $service_slug ];
        }

        $profession_services_label = implode( ', ', $profession_service_labels );

        if ( '' === $first_name || '' === $last_name || '' === $email || '' === $phone || '' === $preferred_location || '' === $message ) {
            wp_send_json_error(
                array(
                    'message' => __( 'Please fill in all required fields.', 'custom-widget' ),
                ),
                400
            );
        }

        if ( ! is_email( $email ) ) {
            wp_send_json_error(
                array(
                    'message' => __( 'Please enter a valid email address.', 'custom-widget' ),
                ),
                400
            );
        }

         if ( empty( $profession_services ) ) {
            wp_send_json_error(
                array(
                    'message' => __( 'Please select at least one profession/service.', 'custom-widget' ),
                ),
                400
            );
        }
        $location_labels = signup_a_suite_get_location_labels();
        $location_label  = $location_labels[ $preferred_location ] ?? $preferred_location;

        if ( ! isset( $location_labels[ $preferred_location ] ) ) {
            wp_send_json_error(
                array(
                    'message' => __( 'Please select a valid preferred location.', 'custom-widget' ),
                ),
                400
            );
        }

        $submitted_at = current_time( 'mysql' );

        $post_id = wp_insert_post(
            array(
                'post_type'   => 'signup_suite_request',
                'post_status' => 'publish',
                'post_title'  => trim( $first_name . ' ' . $last_name ) . ' - ' . $submitted_at,
            ),
            true
        );

        if ( is_wp_error( $post_id ) ) {
            wp_send_json_error(
                array(
                    'message' => __( 'Unable to save your request right now. Please try again later.', 'custom-widget' ),
                ),
                500
            );
        }

        update_post_meta( $post_id, 'first_name', $first_name );
        update_post_meta( $post_id, 'last_name', $last_name );
        update_post_meta( $post_id, 'email', $email );
        update_post_meta( $post_id, 'phone', $phone );
        update_post_meta( $post_id, 'preferred_suite', $preferred_suite );
        update_post_meta( $post_id, 'preferred_location', $preferred_location );
        update_post_meta( $post_id, 'preferred_location_label', $location_label );
        update_post_meta( $post_id, 'profession_services', $profession_services );
        update_post_meta( $post_id, 'profession_services_label', $profession_services_label );
        update_post_meta( $post_id, 'reason', implode( ',', $profession_services ) );
        update_post_meta( $post_id, 'reason_label', $profession_services_label );
        update_post_meta( $post_id, 'message', $message );
        update_post_meta( $post_id, 'submitted_at', $submitted_at );
        update_post_meta( $post_id, 'discount_percentage', $discount_percentage );
        update_post_meta( $post_id, 'source', sanitize_text_field( wp_unslash( $_POST['source'] ?? 'signup_form' ) ) );

        $to      = 'lsdayspaarlington@gmail.com';
        $subject = 'SignUp A Suite — New Message';
        $body    =
            "New message from SignUp A Suite:\n\n" .
            "Name: {$first_name} {$last_name}\n" .
            "Email: {$email}\n" .
            "Phone: {$phone}\n" .
            ( $preferred_suite ? "Preferred Suite: {$preferred_suite}\n" : '' ) .
            "Preferred Location: {$location_label}\n" .
            "Profession/Services: {$profession_services_label}\n";

            if ( $discount_percentage > 0 ) {
                $body .= "Promotional Discount: {$discount_percentage}%\n";
            }

        $body .=
            "\nMessage:\n{$message}\n\n" .
            "Saved in admin as Request ID: {$post_id}\n";

        wp_mail( $to, $subject, $body );

        wp_send_json_success(
            array(
                'message' => __( "Thanks! We received your message and will get back to you as soon as possible.", 'custom-widget' ),
                'id'      => $post_id,
            )
        );
    }
    
    


/**
     * -----------------------------------------------------------------------------
     * Luxury Suites — Location (sidebar) + Gallery (images/videos)
     * -----------------------------------------------------------------------------
     */
     
     
    /**
     * Get raw suite location text from post meta (supports ACF array values).
     *
     * @param int $suite_id Luxury suite post ID.
     * @return string
     */
    function legacy_get_suite_location_text( $suite_id ) {
        $location_text = get_post_meta( $suite_id, 'location', true );

        if ( is_array( $location_text ) ) {
            $location_text = $location_text['value'] ?? $location_text['label'] ?? '';
        }

        return trim( (string) $location_text );
    }

    /**
     * Normalize a location label/slug to signup preferred_location (little_road|cooper).
     *
     * @param string $value Location text or slug.
     * @return string
     */
    function legacy_normalize_preferred_location_slug( $value ) {
        $value = strtolower( trim( sanitize_text_field( (string) $value ) ) );

        if ( '' === $value || ! function_exists( 'signup_a_suite_get_location_labels' ) ) {
            return '';
        }

        if ( function_exists( 'signup_a_suite_get_location_aliases' ) ) {
            $aliases = signup_a_suite_get_location_aliases();

            if ( isset( $aliases[ $value ] ) ) {
                return $aliases[ $value ];
            }

            $value_underscore = str_replace( '-', '_', $value );
            if ( isset( $aliases[ $value_underscore ] ) ) {
                return $aliases[ $value_underscore ];
            }
        }

        foreach ( signup_a_suite_get_location_labels() as $slug => $label ) {
            $label_lower = strtolower( $label );
            $slug_dashed = str_replace( '_', '-', $slug );

            if ( $value === $slug || $value === $slug_dashed || $value === $label_lower ) {
                return $slug;
            }

            if ( false !== strpos( $value, $slug ) || false !== strpos( $value, $label_lower ) ) {
                return $slug;
            }
        }

        return '';
    }

    /**
     * Resolve our_locations post ID from a location text value.
     *
     * @param string $location_text Location label or slug.
     * @return int
     */
    function legacy_resolve_suite_location_id_from_text( $location_text ) {
        $location_text = trim( (string) $location_text );

        if ( '' === $location_text ) {
            return 0;
        }

        $locations = get_posts(
            array(
                'post_type'      => 'our_locations',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'orderby'        => 'title',
                'order'          => 'ASC',
            )
        );

        $needle = strtolower( $location_text );

        foreach ( $locations as $location_post ) {
            $title = strtolower( get_the_title( $location_post->ID ) );
            $slug  = strtolower( $location_post->post_name );

            if ( $needle === $title || $needle === $slug ) {
                return (int) $location_post->ID;
            }

            if ( false !== strpos( $title, $needle ) || false !== strpos( $needle, $title ) ) {
                return (int) $location_post->ID;
            }
        }

        return 0;
    }

    /**
     * Get selected location post ID for a suite.
     *
     * @param int $suite_id Luxury suite post ID.
     * @return int
     */
    function legacy_get_suite_location_id( $suite_id ) {
        $location_id = absint( get_post_meta( $suite_id, 'suite_location_id', true ) );

        if ( $location_id && 'our_locations' === get_post_type( $location_id ) ) {
            return $location_id;
        }

        return legacy_resolve_suite_location_id_from_text( legacy_get_suite_location_text( $suite_id ) );
    }

    /**
     * Get selected location object for a suite.
     *
     * @param int $suite_id Luxury suite post ID.
     * @return WP_Post|null
     */
    function legacy_get_suite_location_post( $suite_id ) {
        $location_id = legacy_get_suite_location_id( $suite_id );
        if ( ! $location_id || 'our_locations' !== get_post_type( $location_id ) ) {
            return null;
        }

        $location = get_post( $location_id );
        return $location instanceof WP_Post ? $location : null;
    }

    /**
     * Map suite location meta to SignUp form preferred_location value (little_road|cooper).
     *
     * @param int $suite_id Luxury suite post ID.
     * @return string Empty string when unknown.
     */
    function legacy_get_suite_preferred_location_slug( $suite_id ) {
        $location = legacy_get_suite_location_post( $suite_id );

        if ( $location ) {
            $slug = legacy_normalize_preferred_location_slug( $location->post_name );

            if ( '' !== $slug ) {
                return $slug;
            }

            $slug = legacy_normalize_preferred_location_slug( get_the_title( $location->ID ) );

            if ( '' !== $slug ) {
                return $slug;
            }
        }

        return legacy_normalize_preferred_location_slug( legacy_get_suite_location_text( $suite_id ) );
    }

    /**
     * Backfill suite_location_id for suites that only have a location text value.
     */
    function legacy_backfill_suite_location_ids() {
        if ( get_option( 'legacy_suite_location_backfill_v1' ) ) {
            return;
        }

        if ( ! function_exists( 'legacy_resolve_suite_location_id_from_text' ) || ! function_exists( 'legacy_get_suite_location_text' ) ) {
            return;
        }

        $suites = get_posts(
            array(
                'post_type'      => 'luxury_suites',
                'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
                'posts_per_page' => -1,
                'fields'         => 'ids',
            )
        );

        foreach ( $suites as $suite_id ) {
            if ( absint( get_post_meta( $suite_id, 'suite_location_id', true ) ) ) {
                continue;
            }

            $resolved_id = legacy_resolve_suite_location_id_from_text( legacy_get_suite_location_text( $suite_id ) );

            if ( $resolved_id ) {
                update_post_meta( $suite_id, 'suite_location_id', $resolved_id );
            }
        }

        update_option( 'legacy_suite_location_backfill_v1', 1, false );
    }
    add_action( 'init', 'legacy_backfill_suite_location_ids', 99 );
     
    function legacy_add_luxury_suites_meta_boxes() {
        add_meta_box(
            'legacy_suite_location',
            __( 'Suite Location', 'custom-functions' ),
            'legacy_render_suite_location_meta_box',
            'luxury_suites',
            'side',
            'default'
        );

        // add_meta_box(
        //     'legacy_suite_gallery',
        //     __( 'Suite Gallery', 'custom-functions' ),
        //     'legacy_render_suite_gallery_meta_box',
        //     'luxury_suites',
        //     'normal',
        //     'high'
        // );

        add_meta_box(
            'legacy_suite_services',
            __( 'Suite Services', 'custom-functions' ),
            'legacy_render_suite_services_meta_box',
            'luxury_suites',
            'normal',
            'default'
        );
    }
    add_action( 'add_meta_boxes', 'legacy_add_luxury_suites_meta_boxes' );

    /**
     * Location dropdown (our_locations CPT).
     *
     * @param WP_Post $post Current suite post.
     */
    function legacy_render_suite_location_meta_box( $post ) {
        if ( ! $post instanceof WP_Post ) {
            return;
        }

        wp_nonce_field( 'legacy_save_suite_meta', 'legacy_suite_meta_nonce' );

        $selected_location_id = absint( get_post_meta( $post->ID, 'suite_location_id', true ) );

        $locations = get_posts(
            array(
                'post_type'      => 'our_locations',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'orderby'        => 'title',
                'order'          => 'ASC',
                'fields'         => 'ids',
            )
        );
        ?>
        <p>
            <label for="legacy_suite_location_id" class="screen-reader-text">
                <?php esc_html_e( 'Suite Location', 'custom-functions' ); ?>
            </label>
            <select
                name="legacy_suite_location_id"
                id="legacy_suite_location_id"
                style="width:100%;"
            >
                <option value=""><?php esc_html_e( 'Select location', 'custom-functions' ); ?></option>
                <?php foreach ( $locations as $location_id ) : ?>
                    <option
                        value="<?php echo esc_attr( $location_id ); ?>"
                        <?php selected( $selected_location_id, $location_id ); ?>
                    >
                        <?php echo esc_html( get_the_title( $location_id ) ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php
    }



    /**
     * Get saved suite services for frontend display.
     *
     * @param int $post_id Suite post ID.
     * @return string[]
     */
    function legacy_get_suite_services( $post_id ) {
        $services = get_post_meta( $post_id, 'suite_services', true );
        if ( ! is_array( $services ) ) {
            return array();
        }

        $services = array_map(
            static function ( $service ) {
                return sanitize_text_field( (string) $service );
            },
            $services
        );

        // return array_values( array_filter( $services, 'strlen' ) );
         $services = array_values( array_filter( $services, 'strlen' ) );

        return array_slice( $services, 0, 4 );
    }

    /**
      * Normalize suite services for the editor (min 1 row, max 4).
     *
     * @param int $post_id Suite post ID.
     * @return string[]
     */
    function legacy_get_suite_services_for_editor( $post_id ) {
        $services = get_post_meta( $post_id, 'suite_services', true );
        $services = is_array( $services ) ? $services : array();

        $services = array_values(
            array_map(
                static function ( $service ) {
                    return sanitize_text_field( (string) $service );
                },
                $services
            )
        );

        if ( empty( $services ) ) {
            $services[] = '';
        }

        return array_slice( $services, 0, 4 );
    }

    /**
      * Suite services repeater (optional, up to 4 total).
     *
     * @param WP_Post $post Current suite post.
     */
    function legacy_render_suite_services_meta_box( $post ) {
        if ( ! $post instanceof WP_Post ) {
            return;
        }

        wp_nonce_field( 'legacy_save_suite_meta', 'legacy_suite_meta_nonce' );

        $services = legacy_get_suite_services_for_editor( $post->ID );
        ?>
        <div class="legacy-suite-services-wrap">
            <div id="legacy-suite-services-list">
                   <?php foreach ( $services as $service ) : ?>
                    <?php $can_remove = count( $services ) > 1; ?>
                    <div class="legacy-suite-service-row" style="display:flex;gap:8px;align-items:center;margin-bottom:8px;">
                        <input
                            type="text"
                            name="legacy_suite_services[]"
                            value="<?php echo esc_attr( $service ); ?>"
                            class="regular-text legacy-suite-service-input"
                            placeholder="<?php esc_attr_e( 'Enter suite service', 'custom-functions' ); ?>"
                          
                            style="flex:1;"
                        />
                        <button
                            type="button"
                            class="button legacy-remove-suite-service"
                            <?php echo $can_remove ? '' : 'disabled'; ?>
                        >
                            <?php esc_html_e( 'Remove', 'custom-functions' ); ?>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>

            <p style="margin:12px 0 8px;">
                 <button type="button" class="button" id="legacy-add-suite-service" <?php disabled( count( $services ) >= 4 ); ?>>
                    <?php esc_html_e( 'Add Service', 'custom-functions' ); ?>
                </button>
            </p>

            <p class="description">
                 <?php esc_html_e( 'Add up to 4 suite services (optional).', 'custom-functions' ); ?>
            </p>
        </div>
        <?php
    }



    /**
     * Inline JS for Suite Gallery media picker + remove + reorder.
     */
    function legacy_suite_gallery_admin_footer_js() {
        $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
        if ( ! $screen || 'luxury_suites' !== $screen->post_type ) {
            return;
        }
        ?>
        <script>
        jQuery(function ($) {
            var frame;
            var $list = $('#legacy-suite-gallery-list');
            var $input = $('#legacy_suite_gallery_ids');

            if (!$list.length || !$input.length) {
                return;
            }

            function renderItem(attachment) {
                var isVideo = attachment.type === 'video';
                var previewUrl = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
                var mediaHtml = '';

                if (isVideo) {
                    mediaHtml =
                        '<div style="position:relative;width:100%;height:100%;">' +
                            '<img src="' + previewUrl + '" alt="" style="width:100%;height:100%;object-fit:cover;" />' +
                            '<span style="position:absolute;left:6px;bottom:6px;background:rgba(0,0,0,.7);color:#fff;padding:2px 6px;font-size:11px;border-radius:10px;">Video</span>' +
                        '</div>';
                } else {
                    mediaHtml = '<img src="' + previewUrl + '" alt="" style="width:100%;height:100%;object-fit:cover;" />';
                }

                return (
                    '<li class="legacy-suite-gallery-item" data-attachment-id="' + attachment.id + '"' +
                        ' style="position:relative;width:110px;height:110px;border:1px solid #ddd;background:#fff;cursor:move;display:flex;align-items:center;justify-content:center;overflow:hidden;">' +
                        mediaHtml +
                        '<button type="button" class="legacy-remove-suite-media" ' +
                            'style="position:absolute;top:4px;right:4px;width:20px;height:20px;border:none;background:#d63638;color:#fff;border-radius:50%;line-height:20px;text-align:center;cursor:pointer;" ' +
                            'aria-label="Remove media">×</button>' +
                    '</li>'
                );
            }

            function syncGalleryIds() {
                var ids = [];
                $list.find('.legacy-suite-gallery-item').each(function () {
                    var id = parseInt($(this).attr('data-attachment-id'), 10);
                    if (!isNaN(id) && id > 0) {
                        ids.push(id);
                    }
                });
                $input.val(JSON.stringify(ids));

                if (!ids.length) {
                    if (!$list.find('.legacy-suite-gallery-empty').length) {
                        $list.append('<li class="legacy-suite-gallery-empty" style="color:#666;">No media added yet.</li>');
                    }
                } else {
                    $list.find('.legacy-suite-gallery-empty').remove();
                }
            }

            $('#legacy-add-suite-gallery').on('click', function (e) {
                e.preventDefault();

                if (frame) {
                    frame.open();
                    return;
                }

                frame = wp.media({
                    title: 'Select Suite Gallery Media',
                    button: { text: 'Add to Gallery' },
                    library: { type: ['image', 'video'] },
                    multiple: true
                });

                frame.on('select', function () {
                    var selection = frame.state().get('selection').toJSON();
                    if (!selection.length) {
                        return;
                    }

                    var existingIds = {};
                    $list.find('.legacy-suite-gallery-item').each(function () {
                        existingIds[$(this).attr('data-attachment-id')] = true;
                    });

                    selection.forEach(function (attachment) {
                        if (!attachment || !attachment.id || existingIds[attachment.id]) {
                            return;
                        }
                        $list.append(renderItem(attachment));
                    });

                    syncGalleryIds();
                });

                frame.open();
            });

            $list.on('click', '.legacy-remove-suite-media', function () {
                $(this).closest('.legacy-suite-gallery-item').remove();
                syncGalleryIds();
            });

            if ($.fn.sortable) {
                $list.sortable({
                    items: '.legacy-suite-gallery-item',
                    update: syncGalleryIds
                });
            }

            syncGalleryIds();
        });
        </script>
        <?php
    }
    add_action( 'admin_footer', 'legacy_suite_gallery_admin_footer_js' );

    /**
     * Inline JS for Suite Services add/remove rows.
     */
    function legacy_suite_services_admin_footer_js() {
        $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
        if ( ! $screen || 'luxury_suites' !== $screen->post_type ) {
            return;
        }
        ?>
        <script>
        jQuery(function ($) {
            var $list = $('#legacy-suite-services-list');
            var $addButton = $('#legacy-add-suite-service');
            var minRows = 1;
            var maxRows = 4;

            if (!$list.length || !$addButton.length) {
                return;
            }

            function getRows() {
                return $list.find('.legacy-suite-service-row');
            }

            function updateRowState() {
                var rows = getRows();
                var count = rows.length;

                rows.each(function () {
                    $(this).find('.legacy-remove-suite-service').prop('disabled', count <= minRows);
                });

                $addButton.prop('disabled', count >= maxRows);
            }

           function buildRow() {
                return (
                    '<div class="legacy-suite-service-row" style="display:flex;gap:8px;align-items:center;margin-bottom:8px;">' +
                        '<input type="text" name="legacy_suite_services[]" value="" class="regular-text legacy-suite-service-input" ' +
                           'placeholder="Enter suite service" style="flex:1;" />' +
                        '<button type="button" class="button legacy-remove-suite-service">Remove</button>' +
                    '</div>'
                );
            }

            $addButton.on('click', function (e) {
                e.preventDefault();

                if (getRows().length >= maxRows) {
                    return;
                }

                $list.append(buildRow());
                updateRowState();
            });

            $list.on('click', '.legacy-remove-suite-service', function (e) {
                e.preventDefault();

                if (getRows().length <= minRows) {
                    return;
                }

                $(this).closest('.legacy-suite-service-row').remove();
                updateRowState();
            });

            updateRowState();
        });
        </script>
        <?php
    }
    add_action( 'admin_footer', 'legacy_suite_services_admin_footer_js' );

    /**
     * Save Suite Location + Suite Services meta.
     *
     * @param int $post_id Current post ID.
     */
    function legacy_save_luxury_suites_meta( $post_id ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! isset( $_POST['legacy_suite_meta_nonce'] ) ||
            ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['legacy_suite_meta_nonce'] ) ), 'legacy_save_suite_meta' ) ) {
            return;
        }

        if ( 'luxury_suites' !== get_post_type( $post_id ) ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // Save location selection.
        $location_id = isset( $_POST['legacy_suite_location_id'] ) ? absint( wp_unslash( $_POST['legacy_suite_location_id'] ) ) : 0;
        if ( $location_id && 'our_locations' === get_post_type( $location_id ) ) {
            update_post_meta( $post_id, 'suite_location_id', $location_id );
        } elseif ( isset( $_POST['legacy_suite_location_id'] ) ) {
            if ( function_exists( 'legacy_resolve_suite_location_id_from_text' ) && function_exists( 'legacy_get_suite_location_text' ) ) {
            $resolved_id = legacy_resolve_suite_location_id_from_text( legacy_get_suite_location_text( $post_id ) );
            if ( $resolved_id ) {
                update_post_meta( $post_id, 'suite_location_id', $resolved_id );
            } else {
                    delete_post_meta( $post_id, 'suite_location_id' );
                }
            } else {
                delete_post_meta( $post_id, 'suite_location_id' );
            }
        }

        

        if ( isset( $_POST['legacy_suite_services'] ) && is_array( $_POST['legacy_suite_services'] ) ) {
            $services = array();

            foreach ( wp_unslash( $_POST['legacy_suite_services'] ) as $service ) {
                $service = sanitize_text_field( $service );
                if ( '' !== $service ) {
                    $services[] = $service;
                }
            }

            // $services = array_slice( $services, 0, 5 );
              $services = array_slice( $services, 0, 4 );

            if ( empty( $services ) ) {
                delete_post_meta( $post_id, 'suite_services' );
            } else {
                update_post_meta( $post_id, 'suite_services', $services );
            }
        }
    }
    add_action( 'save_post_luxury_suites', 'legacy_save_luxury_suites_meta' );

    /**
     * -----------------------------------------------------------------------------
     * Luxury Suites — Frontend helper / example functions
     * -----------------------------------------------------------------------------
     */

    // /**
    //  * Get selected location post ID for a suite.
    //  *
    //  * @param int $suite_id Luxury suite post ID.
    //  * @return int
    //  */
    // function legacy_get_suite_location_id( $suite_id ) {
    //     $location_id = absint( get_post_meta( $suite_id, 'suite_location_id', true ) );

    //     if ( $location_id && 'our_locations' === get_post_type( $location_id ) ) {
    //         return $location_id;
    //     }

    //     return legacy_resolve_suite_location_id_from_text( legacy_get_suite_location_text( $suite_id ) );
    // }

    // /**
    //  * Get selected location object for a suite.
    //  *
    //  * @param int $suite_id Luxury suite post ID.
    //  * @return WP_Post|null
    //  */
    // function legacy_get_suite_location_post( $suite_id ) {
    //     $location_id = legacy_get_suite_location_id( $suite_id );
    //     if ( ! $location_id || 'our_locations' !== get_post_type( $location_id ) ) {
    //         return null;
    //     }
    //     $location = get_post( $location_id );
    //     return $location instanceof WP_Post ? $location : null;
    // }

    // /**
    //  * Map suite location meta to SignUp form preferred_location value (village|cooper).
    //  *
    //  * @param int $suite_id Luxury suite post ID.
    //  * @return string Empty string when unknown.
    //  */
    // function legacy_get_suite_preferred_location_slug( $suite_id ) {
    //     $location = legacy_get_suite_location_post( $suite_id );
    //     if ( $location ) {
    //         $slug = legacy_normalize_preferred_location_slug( $location->post_name );

    //         if ( '' !== $slug ) {
    //             return $slug;
    //         }

    //         $slug = legacy_normalize_preferred_location_slug( get_the_title( $location->ID ) );

    //         if ( '' !== $slug ) {
    //             return $slug;
    //         }
    //     }

    //     return legacy_normalize_preferred_location_slug( legacy_get_suite_location_text( $suite_id ) );
    // }

    // /**
    //  * Backfill suite_location_id for suites that only have a location text value.
    //  */
    // function legacy_backfill_suite_location_ids() {
    //     if ( get_option( 'legacy_suite_location_backfill_v1' ) ) {
    //         return;
    //     }

    //       $suites = get_posts(
    //         array(
    //             'post_type'      => 'luxury_suites',
    //             'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
    //             'posts_per_page' => -1,
    //             'fields'         => 'ids',
    //         )
    //     );

    //   foreach ( $suites as $suite_id ) {
    //         if ( absint( get_post_meta( $suite_id, 'suite_location_id', true ) ) ) {
    //             continue;
    //         }
    //       $resolved_id = legacy_resolve_suite_location_id_from_text( legacy_get_suite_location_text( $suite_id ) );

    //         if ( $resolved_id ) {
    //             update_post_meta( $suite_id, 'suite_location_id', $resolved_id );
    //         }
    //     }

    //     update_option( 'legacy_suite_location_backfill_v1', 1, false );
    // }
    // add_action( 'init', 'legacy_backfill_suite_location_ids', 30 );

    /**
     * Get ordered gallery attachment IDs for a suite.
     *
     * @param int $suite_id Luxury suite post ID.
     * @return int[]
     */
    function legacy_get_suite_gallery_ids( $suite_id ) {
        $ids = get_post_meta( $suite_id, 'suite_gallery_ids', true );
        if ( ! is_array( $ids ) ) {
            return array();
        }
        return array_values( array_filter( array_map( 'absint', $ids ) ) );
    }

    /**
     * Get gallery media entries with attachment ID + type + URL.
     *
     * @param int $suite_id Luxury suite post ID.
     * @return array<int, array<string, mixed>>
     */
    function legacy_get_suite_gallery_media( $suite_id ) {
        $gallery_items = array();
        $gallery_ids   = legacy_get_suite_gallery_ids( $suite_id );

        foreach ( $gallery_ids as $attachment_id ) {
            if ( 'attachment' !== get_post_type( $attachment_id ) ) {
                continue;
            }

            $mime = (string) get_post_mime_type( $attachment_id );
            $url  = wp_get_attachment_url( $attachment_id );
            if ( ! $url ) {
                continue;
            }

            $type = '';
            if ( 0 === strpos( $mime, 'image/' ) ) {
                $type = 'image';
            } elseif ( 0 === strpos( $mime, 'video/' ) ) {
                $type = 'video';
            }

            if ( '' === $type ) {
                continue;
            }

            $gallery_items[] = array(
                'id'   => $attachment_id,
                'type' => $type,
                'url'  => esc_url( $url ),
            );
        }

        return $gallery_items;
    }

    /**
     * Example renderer for single luxury suite page.
     * You can call this inside your single-luxury_suites template.
     *
     * @param int|null $suite_id Optional suite ID.
     */
    function legacy_render_suite_frontend_example( $suite_id = null ) {
        $suite_id = $suite_id ? absint( $suite_id ) : get_the_ID();
        if ( ! $suite_id ) {
            return;
        }

        $location = legacy_get_suite_location_post( $suite_id );
        $media    = legacy_get_suite_gallery_media( $suite_id );

        echo '<div class="legacy-suite-meta">';

        if ( $location ) {
            echo '<p class="legacy-suite-location"><strong>' . esc_html__( 'Location:', 'custom-functions' ) . '</strong> ';
            echo esc_html( get_the_title( $location->ID ) );
            echo '</p>';
        }

        if ( ! empty( $media ) ) {
            echo '<div class="legacy-suite-gallery" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;">';

            foreach ( $media as $item ) {
                if ( 'image' === $item['type'] ) {
                    echo '<img src="' . esc_url( $item['url'] ) . '" alt="" loading="lazy" />';
                } elseif ( 'video' === $item['type'] ) {
                    echo '<video controls preload="metadata" src="' . esc_url( $item['url'] ) . '"></video>';
                }
            }

            echo '</div>';
        }

        echo '</div>';
    }

    /**
     * Base capabilities for the Business Admin role (editor-level content access).
     *
     * @return array<string, bool>
     */
    function legacy_get_business_admin_capabilities() {
        $editor = get_role( 'editor' );

        if ( $editor && ! empty( $editor->capabilities ) ) {
            return $editor->capabilities;
        }

        return array(
            'read'                   => true,
            'edit_posts'             => true,
            'edit_pages'             => true,
            'edit_published_posts'   => true,
            'edit_published_pages'   => true,
            'publish_posts'          => true,
            'publish_pages'          => true,
            'upload_files'           => true,
            'delete_posts'           => true,
            'delete_pages'           => true,
            'delete_published_posts' => true,
            'delete_published_pages' => true,
            'edit_others_posts'      => true,
            'edit_others_pages'      => true,
            'delete_others_posts'    => true,
            'delete_others_pages'    => true,
            'manage_categories'      => true,
            'moderate_comments'      => true,
        );
    }

    /**
     * Create or update the Business Admin role.
     */
    function legacy_ensure_business_admin_role() {
        $caps = legacy_get_business_admin_capabilities();
        $role = get_role( 'business_admin' );

        if ( ! $role ) {
            add_role( 'business_admin', __( 'Business Admin', 'custom-functions' ), $caps );
            return;
        }

        foreach ( $caps as $cap => $grant ) {
            if ( $grant ) {
                $role->add_cap( $cap );
            }
        }
    }
    add_action( 'init', 'legacy_ensure_business_admin_role', 5 );

    /**
     * Beaver Builder user-access keys required for page + header/footer editing.
     *
     * @return string[]
     */
    function legacy_get_business_admin_beaver_builder_access_keys() {
        return array(
            'builder_access',
            'unrestricted_editing',
            'builder_admin',
            'theme_builder_editing',
        );
    }

    /**
     * Ensure Business Admin is enabled in Beaver Builder user-access settings.
     *
     * @param array<string, array<string, bool>> $settings Saved Beaver Builder user-access settings.
     * @return array<string, array<string, bool>>
     */
    function legacy_merge_business_admin_beaver_builder_user_access( $settings ) {
        if ( ! get_role( 'business_admin' ) ) {
            return is_array( $settings ) ? $settings : array();
        }

        if ( ! is_array( $settings ) ) {
            $settings = array();
        }

        foreach ( legacy_get_business_admin_beaver_builder_access_keys() as $key ) {
            if ( ! isset( $settings[ $key ] ) || ! is_array( $settings[ $key ] ) ) {
                $settings[ $key ] = array();
            }

            $settings[ $key ]['business_admin'] = true;
        }

        return $settings;
    }

    /**
     * Always apply Business Admin Beaver Builder access at runtime.
     *
     * @param mixed $value Option value from the database.
     * @return array<string, array<string, bool>>|mixed
     */
    function legacy_filter_business_admin_beaver_builder_user_access_option( $value ) {
        if ( false === $value ) {
            return legacy_merge_business_admin_beaver_builder_user_access( array() );
        }

        if ( is_array( $value ) ) {
            return legacy_merge_business_admin_beaver_builder_user_access( $value );
        }

        return $value;
    }
    add_filter( 'pre_option__fl_builder_user_access', 'legacy_filter_business_admin_beaver_builder_user_access_option' );
    add_filter( 'pre_site_option__fl_builder_user_access', 'legacy_filter_business_admin_beaver_builder_user_access_option' );

    /**
     * Persist Beaver Builder user-access settings for Business Admin in the database.
     */
    function legacy_grant_business_admin_beaver_builder_access() {
        if ( ! class_exists( 'FLBuilderUserAccess' ) || ! class_exists( 'FLBuilderModel' ) ) {
            return;
        }

        if ( ! get_role( 'business_admin' ) ) {
            return;
        }

        remove_filter( 'pre_option__fl_builder_user_access', 'legacy_filter_business_admin_beaver_builder_user_access_option' );
        remove_filter( 'pre_site_option__fl_builder_user_access', 'legacy_filter_business_admin_beaver_builder_user_access_option' );

        $raw_settings = FLBuilderUserAccess::get_raw_settings();
        $settings     = legacy_merge_business_admin_beaver_builder_user_access( $raw_settings );
        $changed      = false;

        foreach ( legacy_get_business_admin_beaver_builder_access_keys() as $key ) {
            if ( empty( $raw_settings[ $key ]['business_admin'] ) ) {
                $changed = true;
                break;
            }
        }

        if ( $changed ) {
            FLBuilderModel::update_admin_settings_option( '_fl_builder_user_access', $settings, false, true );
        }

        add_filter( 'pre_option__fl_builder_user_access', 'legacy_filter_business_admin_beaver_builder_user_access_option' );
        add_filter( 'pre_site_option__fl_builder_user_access', 'legacy_filter_business_admin_beaver_builder_user_access_option' );
    }
    add_action( 'after_setup_theme', 'legacy_grant_business_admin_beaver_builder_access', 20 );
    add_action( 'init', 'legacy_grant_business_admin_beaver_builder_access', 20 );

    /**
     * Whether the current user has the Business Admin role.
     *
     * @return bool
     */
    function legacy_current_user_is_business_admin() {
        $user = wp_get_current_user();
        if ( ! $user || ! $user->exists() ) {
            return false;
        }

        return in_array( 'business_admin', (array) $user->roles, true );
    }

    /**
     * Admin menu slugs / URLs to hide for Business Admin users.
     *
     * @return string[]
     */
    function legacy_get_business_admin_hidden_admin_menus() {
        return array(
            'edit.php?post_type=our_team',
            'edit.php?post_type=rmp_menu',
            // 'edit.php?post_type=legacy_gallery',
            'edit.php?post_type=page_content',
            // 'tsvg-admin',
            'featuredvideo',
            'smush',
            'ipanorama',
            'postman',
        );
    }

    /**
     * Post types Business Admin must not access in wp-admin.
     *
     * @return string[]
     */
    function legacy_get_business_admin_hidden_post_types() {
        return array(
            'our_team',
            'rmp_menu',
            // 'legacy_gallery',
            'page_content',
        );
    }

    /**
     * admin.php?page= values Business Admin must not access.
     *
     * @return string[]
     */
    function legacy_get_business_admin_hidden_admin_pages() {
        return array(
            // 'tsvg-admin',
            // 'tsvg-builder',
            // 'tsvg-pro',
            // 'tsvg-add-ons',
            'featuredvideo',
            'smush',
            'smush-bulk',
            'smush-lazy-preload',
            'smush-cdn',
            'smush-next-gen',
            'smush-integrations',
            'smush-settings',
            'ipanorama',
            'postman',
            'postman_email_log',
        );
    }

    /**
     * Hide selected wp-admin menu items for Business Admin role.
     */
    function legacy_hide_admin_menus_for_business_admin() {
        if ( ! is_admin() || ! legacy_current_user_is_business_admin() ) {
            return;
        }

        foreach ( legacy_get_business_admin_hidden_admin_menus() as $menu_slug ) {
            remove_menu_page( $menu_slug );
        }
    }
    add_action( 'admin_menu', 'legacy_hide_admin_menus_for_business_admin', 999 );

    /**
     * Block direct access to hidden admin screens for Business Admin.
     */
    function legacy_block_business_admin_restricted_admin_pages() {
        if ( ! is_admin() || ! legacy_current_user_is_business_admin() ) {
            return;
        }

        global $pagenow;

        $post_type = isset( $_GET['post_type'] ) ? sanitize_key( wp_unslash( $_GET['post_type'] ) ) : '';
        $page      = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';

        if ( in_array( $post_type, legacy_get_business_admin_hidden_post_types(), true ) ) {
            legacy_deny_business_admin_admin_access();
        }

        if ( in_array( $page, legacy_get_business_admin_hidden_admin_pages(), true ) ) {
            legacy_deny_business_admin_admin_access();
        }

        if ( in_array( $pagenow, array( 'post.php', 'post-new.php' ), true ) ) {
            $editing_type = $post_type;

            if ( ! $editing_type && ! empty( $_GET['post'] ) ) {
                $editing_type = get_post_type( absint( $_GET['post'] ) );
            }

            if ( $editing_type && in_array( $editing_type, legacy_get_business_admin_hidden_post_types(), true ) ) {
                legacy_deny_business_admin_admin_access();
            }
        }
    }
    add_action( 'admin_init', 'legacy_block_business_admin_restricted_admin_pages', 1 );

    /**
     * Deny wp-admin access for restricted Business Admin screens.
     */
    function legacy_deny_business_admin_admin_access() {
        wp_die(
            esc_html__( 'You do not have permission to access this page.', 'custom-functions' ),
            esc_html__( 'Access denied', 'custom-functions' ),
            array( 'response' => 403 )
        );
    }


