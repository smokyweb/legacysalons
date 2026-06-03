<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!function_exists('pfvideo_menu_callback_fun')) {
    function pfvideo_menu_callback_fun()
    { ?>
        <div class="wrap">

            <?php $tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'dashboard'; ?>

            <h1><?php esc_html_e( 'Featured Video', 'post-featured-video' ); ?></h1>

            <div class="notice pfvideo--notice">
                <div>
                    <h3><?php esc_html_e('Post Featured Video', 'post-featured-video'); ?></h3>
                    <p>Here's a link to the documentation for the plugin. This will help you learn more about its features and how to use it.</p>
                    <div class="e-notice__actions">
                        <a href="https://wp-plugins.galaxyweblinks.com/wp-plugins/post-featured-video/doc/" class="e-button--cta cta-secondary" target="_blank"><span>Documentation</span></a>
                    </div>
                    <p class="e-note">For any feedback or queries regarding this plugin, please contact our <a href="https://wp-plugins.galaxyweblinks.com/contact/" target="_blank">Support team</a>.</p>
                </div>
            </div>

            <h2 class="nav-tab-wrapper">
                <a href="<?php echo esc_url(admin_url('admin.php?page=featuredvideo')); ?>" class="nav-tab <?php echo esc_attr($tab == 'dashboard' ? 'nav-tab-active' : ''); ?>">
                    <?php esc_html_e('Dashboard', 'post-featured-video'); ?>
                </a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=featuredvideo&tab=settings')); ?>" class="nav-tab <?php echo $tab == 'settings' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e('General Settings', 'post-featured-video'); ?>
                </a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=featuredvideo&tab=help')); ?>" class="nav-tab <?php echo $tab == 'help' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e('Help/Usage', 'post-featured-video'); ?>
                </a>
            </h2>

            <?php if ((string) $tab === 'dashboard') { ?>

                <div id="poststuff">

                    <?php
                    wp_enqueue_style('pfv_backend_style');
                    if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true'):
                        echo '<div id="setting-error-settings_updated" class="updated settings-error"> 
    				<p><strong>Settings saved.</strong></p></div>';
                    endif;

                    echo '<h3>' . esc_html__('Enable featured video option for post types', 'post-featured-video') . '</h3>';
                    echo "<p class='pvf-notic-msg'>" . esc_html__("This setting enables an option on the page/post in the backend where the user can set video. To display video, please make sure the featured image set.", "post-featured-video") . "</p>";
                    //add_settings_section()
                    echo '<form method="post" action="options.php">';
                    do_settings_sections('pfv_featured_settings_group');
                    settings_fields('pfv_featured_settings_group');
                    $get_reg_settins = get_option('pfv_seetings_opt');

                    $args = array(
                        'public'   => true,
                        '_builtin' => false
                    );

                    $output = 'names'; // 'names' or 'objects' (default: 'names')
                    $operator = 'and'; // 'and' or 'or' (default: 'and')

                    $post_types = get_post_types($args, $output, $operator);
                    $post_types['post'] = "post";
                    $post_types['page'] = "page";
                    if ($post_types) {
                        foreach ($post_types  as $post_type) { //Add menu to exist custom post type				    	
                            $pt = get_post_type_object($post_type);
                            echo '<div class="pfv-pty-cls"><div class="pfv-posty-labl">' . esc_html($pt->labels->name) . '</div>';
                    ?>
                            <label class="pfv_onoff_switcher">
                                <input type="checkbox" name="pfv_seetings_opt[]" value="<?php echo esc_attr($post_type); ?>" <?php if (!empty($get_reg_settins) && in_array($post_type, $get_reg_settins)) {
                                                                                                                                    echo "checked";
                                                                                                                                } ?>>
                                <span class="pfv_onoff_slder"></span>
                            </label>
                    <?php
                            echo "</div>";
                        }
                    }
                    submit_button();
                    echo '</form>'; ?>

                </div>
            <?php
            } else if ((string) $tab === 'settings') {
                if (isset($_POST['pfv_general_setig']) && current_user_can('manage_options')) {
                    /*Video Autoplay*/
                    if (isset($_POST['pfv_autoply_video'])) {
                        update_option('pfv_autoply_video', (int) $_POST['pfv_autoply_video']);
                    } else {
                        update_option('pfv_autoply_video', 0);
                    }
                    /*open video in popup*/
                    if (isset($_POST['pfv_open_vid_inpopup'])) {
                        update_option('pfv_open_vid_inpopup', (int) $_POST['pfv_open_vid_inpopup']);
                    } else {
                        update_option('pfv_open_vid_inpopup', 0);
                    }
                    /*set display mode*/
                    if (isset($_POST['vpfy_disply_video'])) {
                        update_option('vpfy_disply_video', sanitize_text_field(wp_unslash($_POST['vpfy_disply_video'])));
                    }
                    if (isset($_POST['pfvvideoheight'])) {
                        update_option('pfvvideoheight', sanitize_text_field(wp_unslash($_POST['pfvvideoheight'])));
                    }
                    if (isset($_POST['pfv_video_display_only_single_post'])) {
                        update_option('pfv_video_display_only_single_post', (int) $_POST['pfv_video_display_only_single_post']);
                    } else {
                        update_option('pfv_video_display_only_single_post', 0);
                    }

                    echo '<div class="updated notice is-dismissible"><p>Settings updated!</p></div>';
                }
            ?>
                <form method="post" action="">
                    <h3><?php esc_html_e('Settings', 'post-featured-video'); ?></h3>

                    <p>
                        <input type="checkbox" name="pfv_autoply_video" id="pfv_autoply_video" value="1" <?php checked(get_option('pfv_autoply_video'), 1); ?>>
                        <label for="pfv_autoply_video" data-toggle="tooltip" title="Autoplay only works when the popup is enabled">
                            <?php echo wp_kses_post(__('Autoplay <small>(Autoplay only works when the popup is enabled)</small>', 'post-featured-video')); ?>

                        </label>

                    </p>
                    <p>
                        <input type="checkbox" name="pfv_open_vid_inpopup" id="pfv_open_vid_inpopup" value="1" <?php if (get_option('pfv_open_vid_inpopup') == 1) echo 'checked'; ?>> <label for="pfv_open_vid_inpopup"><?php esc_html_e('Open video in popup', 'post-featured-video'); ?> </label>
                    </p>

                    <h3><?php esc_html_e('Display mode', 'post-featured-video'); ?></h3>

                    <div class="pvfdisplymod">
                        <!-- youtube video -->
                        <p>
                            <input type="radio" name="vpfy_disply_video" value="pfvyoutubeurl" <?php
                                                                                                if (empty(get_option('vpfy_disply_video')) || (get_option('vpfy_disply_video') == 'pfvyoutubeurl')) {
                                                                                                    echo 'checked';
                                                                                                }
                                                                                                ?>>
                            <label for="youtubeurl"><?php esc_html_e('Youtube', 'post-featured-video'); ?></label>
                        </p>
                        <!-- Vimeo Video -->
                        <p>
                            <input type="radio" name="vpfy_disply_video" value="pfvvimeourl" <?php if (get_option('vpfy_disply_video') == 'pfvvimeourl') echo 'checked'; ?>>
                            <label for="Vimeo"><?php esc_html_e('Vimeo', 'post-featured-video'); ?></label>
                        </p>

                        <!-- HTML Video/ Upload Video -->

                        <p>
                            <input type="radio" name="vpfy_disply_video" value="pfvuploder" <?php if (get_option('vpfy_disply_video') == 'pfvuploder') echo 'checked'; ?>>
                            <label for="cutomvideo"><?php esc_html_e('Custom Video Upload', 'post-featured-video'); ?></label>
                        </p>

                    </div>
                    <h3><?php esc_html_e('Youtube video height', 'post-featured-video'); ?></h3>
                    <p>
                        <label>
                            Height:
                            <input type="number" name="pfvvideoheight" value="<?php if (!empty(get_option('pfvvideoheight'))) {
                                                                                    echo esc_attr(get_option('pfvvideoheight'));
                                                                                }  ?>" />px
                        </label>
                    </p>
                    <h3><?php esc_html_e('Video display setting', 'post-featured-video'); ?></h3>
                    <p>
                        <input type="checkbox" name="pfv_video_display_only_single_post" id="pfv_video_display_only_single_post" value="1" <?php if (get_option('pfv_video_display_only_single_post') == 1) echo 'checked'; ?>>
                        <label for="pfv_video_display_only_single_post" data-toggle="tooltip"><?php esc_html_e('If this option is enabled, the video will appear only on single posts, pages, and custom post types.', 'post-featured-video'); ?></label>
                    </p>
                    <p><input type="submit" name="pfv_general_setig" class="button button-primary" value="<?php esc_html_e('Save Changes', 'post-featured-video'); ?>"></p>
                </form>
            <?php
            } else if ((string) $tab === 'help') { ?>
                <div id="poststuff">
                    <h3><?php esc_html_e('Help & Usage Details', 'post-featured-video'); ?></h3>
                    <h4><?php esc_html_e('To display video instead of featured image', 'post-featured-video'); ?></h4>
                    <p><?php esc_html_e('1] To enable the featured video options for the specific post type, go to the plugin setting (dashboard) page and enable it.', 'post-featured-video'); ?></p>
                    <p><?php esc_html_e('2] There are many options to show which type of video ie. Youtube, Vimeo or Uploaded/Media library MP4 Video, you can select from plugin setting page. Once selected you will see the options to add video on post/page.', 'post-featured-video'); ?></p>
                    <p><?php esc_html_e('3] There are many more options for video ie. open video in the popup, autoplay video etc.', 'post-featured-video'); ?></p>
                </div>
            <?php
            }
            ?>
        </div>
<?php
    }
}
/*Register settings*/
if (!function_exists('pfvideo_featured_vd_settings')) {
    function pfvideo_featured_vd_settings()
    {
        register_setting('pfv_featured_settings_group', 'pfv_seetings_opt');
    }
}
