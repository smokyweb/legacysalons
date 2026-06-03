<?php
/**
 * Plugin Name: Post Featured Video
 * Plugin URI:  https://wordpress.org/plugins/post-featured-video
 * Description: Post Featured video plugin is highly customizable. You can display video in the lightbox, enable autoplay for video. It can replace the post featured image with a video.
 * Version:     1.7
 * Author:      Galaxy Weblinks
 * Author URI:  https://www.galaxyweblinks.com/
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: post-featured-video
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if(!defined('PFVIDEO_PLUGIN_VERSION')){
	define('PFVIDEO_PLUGIN_VERSION', '1.7');
}
if(!defined('PFVIDEO_POST_FEATURED_PLUGINURL')){
	define('PFVIDEO_POST_FEATURED_PLUGINURL', plugin_dir_url(__FILE__));
}
if(!defined('PFVIDEO_POST_FEATURED_PLUGINPATH')){
	define('PFVIDEO_POST_FEATURED_PLUGINPATH', plugin_dir_path(__FILE__));
}

/*include js and css file*/
require_once(PFVIDEO_POST_FEATURED_PLUGINPATH.'pfv-enqueue-scripts-style.php');
require_once(PFVIDEO_POST_FEATURED_PLUGINPATH.'pfv-backend-setting-page.php');

//admin notice when activate plugin
register_activation_hook(__FILE__, 'pfvideo_pst_adminnotice');
if (!function_exists('pfvideo_pst_adminnotice')){
	function pfvideo_pst_adminnotice(){
		update_option('pfvideo_pst_adminnotice','enabled');
	}
}

if (!function_exists('pfvideo_pst_admin_notice__success')){
	function pfvideo_pst_admin_notice__success() {
		if(get_option('pfvideo_pst_adminnotice') == 'enabled'){
	    	?>
		
	    <div class="notice notice-success is-dismissible">
			<p>
				<?php echo esc_html__( 'To view setting please ', 'post-featured-video' ); ?>
				<a href="<?php echo esc_url( admin_url('admin.php?page=featuredvideo') ); ?>">
					<?php echo esc_html__( 'click here', 'post-featured-video' ); ?>
				</a>
			</p>
	    </div>
	    <?php 
		delete_option('pfvideo_pst_adminnotice');
		}
	}
}
add_action( 'admin_notices', 'pfvideo_pst_admin_notice__success' );

//Add Menu Page
add_action('admin_menu', 'pfvideo_pst_add_menu_page');
if (!function_exists('pfvideo_pst_add_menu_page')){
	function pfvideo_pst_add_menu_page(){
		add_menu_page('Post featured video', __('Featured Video','post-featured-video'), 'manage_options', 'featuredvideo', 'pfvideo_menu_callback_fun');	
		add_action('admin_init','pfvideo_featured_vd_settings');
	}
}
//add custom field in the featured image section.
if (!function_exists('pfvideo_add_featured_image_display_settings')){
	function pfvideo_add_featured_image_display_settings( $content, $post_id, $thumbnail_id ) {
		wp_enqueue_style('pfv_backend_style');
		$meta_key = 'pfv_featured_video_uploading';
		// get the meta value of video attachment
		$meta_ket = get_post_meta($post_id, $meta_key, true);
		$pfv_currnt_pty = get_post_type($post_id);
		$get_reg_settins = get_option('pfv_seetings_opt');
		if(!empty($get_reg_settins) && in_array($pfv_currnt_pty, $get_reg_settins)){
			$pfup = pfvideo_uploader_callback($meta_key, $meta_ket, $post_id); 	
			$content.= $pfup;
		} 
		return $content;
	}
}
add_filter( 'admin_post_thumbnail_html', 'pfvideo_add_featured_image_display_settings', 10, 3 );

/* video uploader */
if (!function_exists('pfvideo_uploader_callback')){
	function pfvideo_uploader_callback($name, $value, $post_id)
	{
		global $post;
		wp_enqueue_script('pfv_vid_uploader');
		/*Vimeo Video*/
		if(null !== get_option('vpfy_disply_video') && get_option('vpfy_disply_video') == 'pfvvimeourl'){
			if(!empty(get_post_meta($post_id, '_pfv_vimeo_video_url', true)) && (null!== get_post_meta($post_id, '_pfv_vimeo_video_url', true))){
				$pfv_vimeo_url = get_post_meta($post_id, '_pfv_vimeo_video_url', true);
			}
			else{
				$pfv_vimeo_url = "";
			}
			return '
		    <div class="pfv_bttn_sect"><p><small>'.__("Featured Videos require a Featured Image for automatic replacement.", "post-featured-video").'</small></p><p><strong>'.__("Featured Vimeo Video URL","post-featured-video").'</strong></p><p class="pfvvideourl"><textarea id="pfvvideourl" name="pfv_vimeo_video_url" placeholder="Add video URL">'.$pfv_vimeo_url.'</textarea></p></div>';
		}
		/*Custom video uploader*/
		if(null !== get_option('vpfy_disply_video') && get_option('vpfy_disply_video') == 'pfvuploder'){
			$image = ' button">Upload Video';
			$display = 'none'; 
			// Attachment id of video is $value
			if( $media = wp_get_attachment_url($value)) {  // getting video here
				$video = $media;
				$image = '"><video controls="" src="'.$video.'" style="max-width:95%;display:block;"></video>';
				$display = 'inline-block';
			}

			return '<div class="pfv_bttn_sect"><p><small>'.__("Featured Videos require a Featured Image for automatic replacement.", "post-featured-video").'</small></p><p><strong>'.__("Upload Video","post-featured-video").'</strong></p><a href="#" class="pfv_uploader_video_button' . $image . '</a>
			    <input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '" />
			    <a href="#" class="pfv_remove_fetured_video" style="display:inline-block;display:' . $display . '">Remove Video</a>
			    </div>';
		}
		/*Youtube Video*/
		else{
			
			if(!empty(get_post_meta($post_id, '_pfv_custom_vid_url', true)) && (null!== get_post_meta($post_id, '_pfv_custom_vid_url', true))){
				$pfv_vid_url = get_post_meta($post_id, '_pfv_custom_vid_url', true);
			}
			else{
				$pfv_vid_url = "";
			}
			return '
		    <div class="pfv_bttn_sect"><p><small>'.__("Featured Videos require a Featured Image for automatic replacement.", "post-featured-video").'</small></p><p><strong>'.__("Featured Youtube Video URL","post-featured-video").'</strong></p><p class="pfvvideourl"><textarea id="pfvvideourl" name="pfv_custom_vid_url" placeholder="Add video URL">'.$pfv_vid_url.'</textarea></p></div>';

		}
		   
	}
}
/* Save Video thumbnail*/

add_action('save_post', 'pfvideo_featured_vid_save', 10, 1);
if (!function_exists('pfvideo_featured_vid_save')){
	function pfvideo_featured_vid_save($post_id){
		// Add nonce for security and authentication.
		$nonce_name = isset( $_POST['pfv_custom_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['pfv_custom_nonce'] ) ) : '';
	    $nonce_action = 'pfv_custom_nonce_action';

	    // Check if nonce is valid.
	    if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
	        return;
	    }

		$meta_key = 'pfv_featured_video_uploading';

		if(!current_user_can('edit_post', $post_id, $meta_key)) return;

		if(isset($_POST[$meta_key])){	
			// Sanitize the unslashed value
			$keyvalue = sanitize_text_field(wp_unslash($_POST[$meta_key]));

			update_post_meta($post_id, $meta_key, $keyvalue );			
		}

		/*Youtube Video*/
		if(isset($_POST['pfv_custom_vid_url'])){	
			$custom_vid_url = esc_url(sanitize_text_field(wp_unslash($_POST['pfv_custom_vid_url'])));	
			update_post_meta($post_id, '_pfv_custom_vid_url', $custom_vid_url );		
		}

		/*Vimeo Video*/
		if(isset($_POST['pfv_vimeo_video_url'])){	
			$custom_vid_url = esc_url(sanitize_text_field(wp_unslash($_POST['pfv_vimeo_video_url'])));
			update_post_meta($post_id, '_pfv_vimeo_video_url', $custom_vid_url );	
		}    
	    return $post_id;
	}
}
/*Display fetured video*/
add_filter('post_thumbnail_html', 'pfvideo_filter_post_thumbnail_html', 10, 5 );
if (!function_exists('pfvideo_filter_post_thumbnail_html')){
	function pfvideo_filter_post_thumbnail_html( $image, $attachment_id, $size, $icon  ) { 
		// Check if we're on a singular post/page/custom post type
		$pfvvideodisplaysetting = get_option('pfv_video_display_only_single_post');

		if ($pfvvideodisplaysetting == 1 && !is_singular()) {
			return $image;
		}
		wp_enqueue_script('pfv_lightbox_scrpt');
		wp_enqueue_style('pfv_frontnd_style');
		$post_id = get_the_ID();
		$meta_key = 'pfv_featured_video_uploading';
		$meta_ket = get_post_meta($post_id, $meta_key, true);

		$meta_ket = get_post_meta($post_id, $meta_key, true);
		$pfv_currnt_pty = get_post_type($post_id);
		$get_reg_settins = get_option('pfv_seetings_opt');
		$pfvvideoheight = get_option('pfvvideoheight');
		
		if(!empty($get_reg_settins) && in_array($pfv_currnt_pty, $get_reg_settins)){
			$autplyvideo = get_option("pfv_autoply_video");
			$popupvideo = get_option("pfv_open_vid_inpopup");

			/*Vimeo Video*/
			if(null !== get_option('vpfy_disply_video') && get_option('vpfy_disply_video') == 'pfvvimeourl'){	
				if(!empty(get_post_meta($post_id, '_pfv_vimeo_video_url', true))){
					$vimeovideoURL = get_post_meta($post_id, '_pfv_vimeo_video_url', true);
					//$expld = explode('com/', $vimeovideoURL);
					$vimimgid = (int) substr(parse_url($vimeovideoURL, PHP_URL_PATH), 1);
					$responsearry = wp_remote_get("http://vimeo.com/api/v2/video/$vimimgid.php");
					$resp_body = unserialize(wp_remote_retrieve_body($responsearry));			
					/*Popup Video*/
					if(!empty($popupvideo) && $popupvideo == 1){
						return '<div class="modelpup pfvthumvido"><button class="pfv-vvideo-playbtton ytp-button" aria-label="Play" onclick="vmio_lightbox_open('.$post_id.','.$autplyvideo.');"><img src="'.plugin_dir_url( __FILE__ ) . 'includes/img/pfvplay.png" /></button><a href="#" onclick="vmio_lightbox_open('.$post_id.','.$autplyvideo.');"><img class="vidthumimg" src="'.$resp_body[0]['thumbnail_large'].'"/></a>

						<div id="pfv_vvideo_lightbox_'.$post_id.'" class="pfv_vvideo_lightbox" >
						  <a class="boxclose" id="boxclose" onclick="vmio_lightbox_close('.$post_id.');"></a>
						  <div style="padding:56.25% 0 0 0;position:relative;"><iframe id="pfviframeVideo_'.$post_id.'" src="https://player.vimeo.com/video/'.$vimimgid.'?autoplay='.$autplyvideo.'" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe></div>
						</div>

						<div id="pfv_video_fadelayout_'.$post_id.'" class="pfv_video_fadelayout" onClick="vmio_lightbox_close('.$post_id.');"></div></div>';
					}
					else{
						return '<div style="padding:56.25% 0 0 0;position:relative;"><iframe id="pfviframeVideo_'.$post_id.'" src="https://player.vimeo.com/video/'.$vimimgid.'" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe></div>';
					}
				}
				else{
					return $image;
				}			
			}
			
			/*Custom video uploader*/
			if(null !== get_option('vpfy_disply_video') && get_option('vpfy_disply_video') == 'pfvuploder'){
				//$pfv_video_height = (!empty($pfvvideoheight) && isset($pfvvideoheight)) ? $pfvvideoheight."px" : "auto";
				if( $media = wp_get_attachment_url($meta_ket)) { 
					if(!empty($autplyvideo) && $autplyvideo == 1){
						$autoplayhtml = 1;
					}
					else{
						$autoplayhtml = 0;
					}

					if(!empty($popupvideo) && $popupvideo == 1){
						//get thumbimage
						$thumbnailimg =	get_the_post_thumbnail_url($post_id, 'full');

						return '<div class="modelpup pfvthumvido"><button class="pfv-vvideo-playbtton ytp-button" aria-label="Play" onclick="mp4_lightbox_open('.$post_id.','.$autoplayhtml.');"><img src="'.plugin_dir_url( __FILE__ ) . 'includes/img/pfvplay.png" /></button><a href="javascript:void(0);" onclick="mp4_lightbox_open('.$post_id.','.$autoplayhtml.');"><img class="vidthumimg" src="'.$thumbnailimg.'" alt="thumbimage"/></a>

						<div id="pfv_vvideo_lightbox_'.$post_id.'" class="pfv_vvideo_lightbox">
						  	<a class="boxclose" id="boxclose" onclick="mp4_lightbox_close('.$post_id.');"></a>
						  	<div id="pfv_vidBox" class="pfv_vidBox" style="clear:both"><video  class="pfv_htmlvideo" id="pfv_htmlvideo_'.$post_id.'" controls src="'.$media.'" style="width:100%;display:block;"></video></div>
						</div>
						<div id="pfv_video_fadelayout_'.$post_id.'" class="pfv_video_fadelayout" onClick="mp4_lightbox_close('.$post_id.');"></div></div>';
					}
					else{
						return '<div id="pfv_vidBox" class="pfv_vidBox" style="clear:both"><video  class="pfv_htmlvideo" id="pfv_htmlvideo_'.$post_id.'" controls src="'.$media.'" style="width:100%;display:block;"></video></div>';
					}				
				}
				else{
					return $image;
				}
			}
			/*Youtube Video*/
			else{
				$pfv_video_height = (!empty($pfvvideoheight) && isset($pfvvideoheight)) ? $pfvvideoheight : "480";
				if(!empty(get_post_meta($post_id, '_pfv_custom_vid_url', true))){
					$videoURL = get_post_meta($post_id, '_pfv_custom_vid_url', true); 

					// YouTube video url
					if(!empty($videoURL)){
						$urlArr = explode("v=", $videoURL);
						if (strpos($urlArr[1], '&') !== false) {
						    $filterValu = explode("&",$urlArr[1]);
						    $youtubeVideoId = $filterValu[0];
						}
						else{
							$youtubeVideoId = $urlArr[1];
						}
					}
					
					// Generate youtube thumbnail url
					$thumbURL = 'http://img.youtube.com/vi/'.$youtubeVideoId.'/maxresdefault.jpg';

					/*Popup Video*/
					if(!empty($popupvideo) && $popupvideo == 1){				
						return '<div class="modelpup pfvthumvido"><button class="pfv-vvideo-playbtton ytp-button" aria-label="Play" onclick="ytube_lightbox_open('.$post_id.','.$autplyvideo.');"><img src="'.plugin_dir_url( __FILE__ ) . 'includes/img/pfvplay.png" /></button><a href="#" onclick="ytube_lightbox_open('.$post_id.','.$autplyvideo.');"><img class="vidthumimg" src="'.$thumbURL.'"/></a>

							<div id="pfv_vvideo_lightbox_'.$post_id.'" class="pfv_vvideo_lightbox" style="max-height:'.$pfv_video_height.'px;">
							  <a class="boxclose" id="boxclose" onclick="ytube_lightbox_close('.$post_id.');"></a>
							  <iframe id="pfviframeVideo_'.$post_id.'" src="https://www.youtube.com/embed/'.$youtubeVideoId.'" allow="autoplay;" style="height:'.$pfv_video_height.'px;width:100%;"></iframe>				  
							</div>
							<div id="pfv_video_fadelayout_'.$post_id.'" class="pfv_video_fadelayout" onClick="ytube_lightbox_close('.$post_id.');"></div></div>';
					}
					else{
						return '<iframe id="pfviframeVideo_'.$post_id.'" src="https://www.youtube.com/embed/'.$youtubeVideoId.'" style="height:'.$pfv_video_height.'px;width:100%;"></iframe>';
					}
				}
				else{
					return $image;
				}

			}

		}
		return $image;
	}
}

/**
 * Register meta box(es).
 * Metabox for block editor
 */
if (!function_exists('pfvideo_register_metabox_fun')){
	function pfvideo_register_metabox_fun() {
		
		if(!empty(get_option('pfv_seetings_opt'))){
			$get_reg_settins_opt = get_option('pfv_seetings_opt');
		    add_meta_box( 'meta-box-id-featured-vid', __( 'Featured Video', 'post-featured-video' ), 'pfvideo_metabx_display_callback', $get_reg_settins_opt, 'side','high', array(
		        '__back_compat_meta_box' => false,
		    ));
		}
	}
}
add_action( 'add_meta_boxes', 'pfvideo_register_metabox_fun' );
 
/**
 * Meta box display callback.
 */
if (!function_exists('pfvideo_metabx_display_callback')){
	function pfvideo_metabx_display_callback( $post ) {
		// Add nonce for security and authentication.
	    wp_nonce_field( 'pfv_custom_nonce_action', 'pfv_custom_nonce' );
	    $thumbnail_id = get_post_thumbnail_id($post->ID);
	    echo pfvideo_add_featured_image_display_settings( $content='', $post->ID, $thumbnail_id ); 
		
	}
} 

/**
 * You can use these filters to add custom links to your plugin row in the plugin list.
 * @param $links, $file
 * @return $links [array]
 */
if (!function_exists('pfvideo_add_custom_plugin_links')){
	function pfvideo_add_custom_plugin_links($links, $file)
	{
		if ($file === 'post-featured-video/post-featured-video-plugin.php') {
			$links[] = '<a href="https://wp-plugins.galaxyweblinks.com/wp-plugins/post-featured-video/doc/" target="_blank">Documentation</a>';
			$links[] = '<a href="https://wp-plugins.galaxyweblinks.com/contact/" target="_blank">Contact Support</a>';
		}
		return $links;
	}
}
add_filter('plugin_row_meta', 'pfvideo_add_custom_plugin_links', 10, 2);

/*
 *  Plugin Setting Link 
*/
if (! function_exists('pfvideo_settings_link')) {
	function pfvideo_settings_link($actions, $plugin_file)
	{
		static $plugin;

		if (!isset($plugin))
			$plugin = plugin_basename(__FILE__);
		if ($plugin == $plugin_file) {

			$settings = array('settings' => '<a href="' . admin_url('admin.php?page=featuredvideo') . '">' . __('Settings', 'post-featured-video') . '</a>');
			$actions = array_merge($settings, $actions);
		}
		return $actions;
	}
}
add_filter('plugin_action_links', 'pfvideo_settings_link', 10, 5);