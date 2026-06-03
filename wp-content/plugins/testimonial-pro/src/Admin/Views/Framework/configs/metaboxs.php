<?php
/**
 * The admin-specific meta fields of the plugin.
 *
 * @since        2.5.0
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Admin
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

use ShapedPlugin\TestimonialPro\Admin\Views\Framework\Classes\SPFTESTIMONIAL;

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

/**
 * Sanitize function for text field.
 */
if ( ! function_exists( 'sp_tpro_sanitize_text' ) ) {
	/**
	 * Sp_tpro_sanitize_text
	 *
	 * @param  mixed $value value.
	 * @return statement
	 */
	function sp_tpro_sanitize_text( $value ) {

		$safe_text = htmlspecialchars( $value );
		return $safe_text;
	}
}

if ( ! function_exists( 'sp_tpro_sanitize_text_field' ) ) {
	/**
	 *
	 * Sanitize title
	 *
	 * @param mixed $value value.
	 * @since 3.1.0
	 * @version 3.1.0
	 */
	function sp_tpro_sanitize_text_field( $value ) {
		if ( ! $value ) {
			return $value;
		}

		$allowed_html = array(
			'span'   => array(
				'class' => array(),
				'style' => array(),
			),
			'em'     => array(),
			'strong' => array(),
		);
		$allowed_html = apply_filters( 'sp_tpro_text_field_allowed_html', $allowed_html );

		return wp_kses( $value, $allowed_html );
	}
}

//
// Metabox of the testimonial shortcode generator.
// Set a unique slug-like ID.
//
$prefix_shortcode_opts = 'sp_tpro_shortcode_options';
$tpro_edit_tax_link    = admin_url( 'edit-tags.php?taxonomy=testimonial_cat&post_type=spt_testimonial' );

/**
 * Shortcode metabox.
 *
 * @param string $prefix The metabox main Key.
 * @return void
 */
SPFTESTIMONIAL::createMetabox(
	'sp_tpro_shortcode_show',
	array(
		'title'             => __( 'How To Use', 'testimonial-pro' ),
		'post_type'         => 'spt_shortcodes',
		'context'           => 'side',
		'show_restore'      => false,
		'sp_tpro_shortcode' => false,
	)
);
SPFTESTIMONIAL::createSection(
	'sp_tpro_shortcode_show',
	array(
		'fields' => array(
			array(
				'type'      => 'shortcode',
				'shortcode' => 'manage_view',
				'class'     => 'sp_tpro-admin-sidebar',
			),
		),
	)
);
SPFTESTIMONIAL::createMetabox(
	'sp_tpro_builder_option',
	array(
		'title'             => __( 'Page Builders', 'testimonial-pro' ),
		'post_type'         => 'spt_shortcodes',
		'context'           => 'side',
		'show_restore'      => false,
		'sp_tpro_shortcode' => false,
	)
);
SPFTESTIMONIAL::createSection(
	'sp_tpro_builder_option',
	array(
		'fields' => array(
			array(
				'type'      => 'shortcode',
				'shortcode' => false,
				'class'     => 'sp_tpro-admin-sidebar',
			),
		),
	)
);

/**
 * Preview metabox.
 *
 * @param string $prefix The metabox main Key.
 * @return void
 */
SPFTESTIMONIAL::createMetabox(
	'sp_tpro_live_preview',
	array(
		'title'             => __( 'Live Preview', 'testimonial-pro' ),
		'post_type'         => 'spt_shortcodes',
		'show_restore'      => false,
		'sp_tpro_shortcode' => false,
		'context'           => 'normal',
	)
);
SPFTESTIMONIAL::createSection(
	'sp_tpro_live_preview',
	array(
		'fields' => array(
			array(
				'type' => 'preview',
			),
		),
	)
);

SPFTESTIMONIAL::createMetabox(
	'sp_tpro_layout_options',
	array(
		'title'             => __( 'Layout', 'testimonial-pro' ),
		'post_type'         => 'spt_shortcodes',
		'show_restore'      => false,
		'sp_tpro_shortcode' => false,
		'context'           => 'normal',
	)
);
SPFTESTIMONIAL::createSection(
	'sp_tpro_layout_options',
	array(
		'fields' => array(
			array(
				'type'    => 'heading',
				'image'   => esc_url( SP_TPRO_URL ) . '/Admin/assets/images/real-testimonials-logo.svg',
				'after'   => '<i class="fa fa-life-ring"></i> Support',
				'link'    => 'https://shapedplugin.com/support/?user=lite',
				'class'   => 'spftestimonial-admin-header',
				'version' => SP_TPRO_VERSION,
			),
			array(
				'id'      => 'layout',
				'type'    => 'image_select',
				'title'   => __( 'Layout Preset', 'testimonial-pro' ),
				'class'   => 'tpro-layout-preset',
				'options' => array(
					'slider'           => array(
						'image'           => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/layouts/slider.svg',
						'name'            => __( 'Slider', 'testimonial-pro' ),
						'option_demo_url' => 'https://realtestimonials.io/demos/slider/',
					),
					'thumbnail_slider' => array(
						'image'           => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/layouts/thumbnails_slider.svg',
						'name'            => __( 'Thumbnails Slider', 'testimonial-pro' ),
						'option_demo_url' => 'https://realtestimonials.io/demos/thumbnails-slider/',
					),
					'carousel'         => array(
						'image'           => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/layouts/carousel.svg',
						'name'            => __( 'Carousel', 'testimonial-pro' ),
						'option_demo_url' => 'https://realtestimonials.io/demos/carousel/',
					),
					'multi_rows'       => array(
						'image'           => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/carousel-mode/multi_rows.svg',
						'name'            => __( 'Multi-row', 'testimonial-pro' ),
						'option_demo_url' => 'https://realtestimonials.io/demos/carousel/#multi-rows-carousel',
					),
					'grid'             => array(
						'image'           => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/layouts/grid.svg',
						'name'            => __( 'Grid', 'testimonial-pro' ),
						'option_demo_url' => 'https://realtestimonials.io/demos/grid/',
					),
					'masonry'          => array(
						'image'           => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/layouts/filter_masonry.svg',
						'name'            => __( 'Masonry', 'testimonial-pro' ),
						'option_demo_url' => 'https://realtestimonials.io/demos/masonry/',
					),
					'list'             => array(
						'image'           => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/layouts/list.svg',
						'name'            => __( 'List', 'testimonial-pro' ),
						'option_demo_url' => 'https://realtestimonials.io/demos/list/',
					),
					'filter'           => array(
						'image'           => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/layouts/isotope.svg',
						'name'            => __( 'Isotope', 'testimonial-pro' ),
						'option_demo_url' => 'https://realtestimonials.io/demos/isotope-shuffle-filter/',
					),
				),
				'default' => 'slider',
			),
			array(
				'id'         => 'carousel_mode',
				'type'       => 'image_select',
				'class'      => 'sp_carousel_mode',
				'title'      => __( 'Carousel Style', 'testimonial-pro' ),
				'options'    => array(
					'standard' => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/carousel-mode/standard.svg',
						'name'  => __( 'Standard', 'testimonial-pro' ),
					),
					'center'   => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/carousel-mode/center.svg',
						'name'  => __( 'Center', 'testimonial-pro' ),
					),
					'ticker'   => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/carousel-mode/slider_ticker.svg',
						'name'  => __( 'Ticker', 'testimonial-pro' ),
					),
				),
				'default'    => 'standard',
				'dependency' => array( 'layout', '==', 'carousel', true ),
			),
			array(
				'id'         => 'theme_style_thumbnail',
				'type'       => 'image_select',
				'class'      => 'sp_carousel_mode',
				'title'      => __( 'Thumbnails Style', 'testimonial-pro' ),
				'options'    => array(
					'theme-one'   => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/thumbnail-style/thumbs_top.svg',
						'name'  => __( 'Thumbs Top', 'testimonial-pro' ),
					),
					'theme-two'   => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/thumbnail-style/thumbs_bottom.svg',
						'name'  => __( 'Thumbs Bottom', 'testimonial-pro' ),
					),
					'theme-three' => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/thumbnail-style/thumbs_left.svg',
						'name'  => __( 'Thumbs Left', 'testimonial-pro' ),
					),
					'theme-four'  => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/thumbnail-style/thumbs_right.svg',
						'name'  => __( 'Thumbs Right', 'testimonial-pro' ),
					),
				),
				'default'    => 'theme-one',
				'dependency' => array( 'layout', '==', 'thumbnail_slider', true ),
			),
			array(
				'id'         => 'filter_style',
				'class'      => 'filter_style sp_carousel_mode',
				'type'       => 'image_select',
				'title'      => __( 'Isotope Style', 'testimonial-pro' ),
				'options'    => array(
					'even'    => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/layouts/grid_even.svg',
						'name'  => __( 'Even', 'testimonial-pro' ),
					),
					'masonry' => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/layouts/masonry.svg',
						'name'  => __( 'Masonry', 'testimonial-pro' ),
					),
				),
				'default'    => 'even',
				'dependency' => array(
					'layout',
					'==',
					'filter',
					true,
				),
			),
		),
	)
);

//
// Testimonial metabox.
//
SPFTESTIMONIAL::createMetabox(
	$prefix_shortcode_opts,
	array(
		'title'           => __( 'Shortcode Options', 'testimonial-pro' ),
		'post_type'       => 'spt_shortcodes',
		'context'         => 'normal',
		'enqueue_webfont' => false,
		'nav'             => 'inline',
	)
);

//
// General Settings section.
//
SPFTESTIMONIAL::createSection(
	$prefix_shortcode_opts,
	array(
		'title'  => __( 'General Settings', 'testimonial-pro' ),
		'icon'   => 'sptpro-icon-cog',
		'fields' => array(
			array(
				'id'         => 'slider_row',
				'type'       => 'column',
				'title'      => __( 'Row', 'testimonial-pro' ),
				'subtitle'   => __( 'Set number of row in different devices.', 'testimonial-pro' ),
				'default'    => array(
					'large_desktop' => '2',
					'desktop'       => '2',
					'laptop'        => '2',
					'tablet'        => '1',
					'mobile'        => '1',
				),
				'dependency' => array( 'layout', '==', 'multi_rows', true ),
			),
			array(
				'id'       => 'columns',
				'type'     => 'column',
				'class'    => 'testimonial_columns',
				'title'    => __( 'Columns', 'testimonial-pro' ),
				'subtitle' => __( 'Set number of columns in different devices.', 'testimonial-pro' ),
				'default'  => array(
					'large_desktop' => '1',
					'desktop'       => '1',
					'laptop'        => '1',
					'tablet'        => '1',
					'mobile'        => '1',
				),
			),
			array(
				'id'                => 'testimonial_margin',
				'class'             => 'testimonial-slide-margin',
				'type'              => 'spacing',
				'title'             => __( 'Space', 'testimonial-pro' ),
				'subtitle'          => __( 'Set space between the testimonials.', 'testimonial-pro' ),
				'title_help'        => '<div class="spftestimonial-img-tag"><img src="' . SP_TPRO_URL . 'Admin/Views/Framework/assets/images/help-visuals/space.svg" alt="Space Between Testimonials"></div><div class="spftestimonial-info-label">' . __( 'Space Between Testimonials', 'testimonial-pro' ) . '</div>',
				'right'             => true,
				'top'               => true,
				'left'              => false,
				'bottom'            => false,
				'top_placeholder'   => __( 'gap', 'testimonial-pro' ),
				'right_placeholder' => __( 'gap', 'testimonial-pro' ),
				'right_text'        => __( 'Vertical Gap', 'testimonial-pro' ),
				'top_text'          => __( 'Gap', 'testimonial-pro' ),
				'right_icon'        => '<i class="fa fa-arrows-v"></i>',
				'top_icon'          => '<i class="fa fa-arrows-h"></i>',
				'unit'              => true,
				'units'             => array( 'px' ),
				'default'           => array(
					'top'   => '20',
					'right' => '20',
				),
				'dependency'        => array(
					'theme_style|layout',
					'any|!=',
					'theme-one,theme-two,theme-three,theme-four,theme-five,theme-six,theme-seven,theme-eight,theme-nine,theme-ten|thumbnail_slider',
					true,
				),
			),
			array(
				'id'       => 'display_testimonials_from',
				'class'    => 'display_testimonials_from',
				'type'     => 'select',
				'title'    => __( 'Filter Testimonials', 'testimonial-pro' ),
				'subtitle' => __( 'Select an option to display the testimonials.', 'testimonial-pro' ),
				'desc'     => __( '<a href="' . esc_url( $tpro_edit_tax_link ) . '">Manage Groups</a>', 'testimonial-pro' ),
				'options'  => array(
					'latest'                => __( 'Latest', 'testimonial-pro' ),
					'category'              => __( 'Groups', 'testimonial-pro' ),
					'specific_testimonials' => __( 'Specific', 'testimonial-pro' ),
					'based_on_rating_star'  => __( 'Based on Star Rating', 'testimonial-pro' ),
				),
				'default'  => 'latest',
			),

			array(
				'id'         => 'filter_by_rating',
				'type'       => 'checkbox',
				'class'      => 'tpro-rating-star',
				'title'      => __( 'Based on Star Rating', 'testimonial-pro' ),
				'subtitle'   => __( 'Check which star-rated testimonials you want to display.', 'testimonial-pro' ),
				'options'    => array(
					'five_star'  => '  ',
					'four_star'  => '  ',
					'three_star' => '  ',
					'two_star'   => '  ',
					'one_star'   => '  ',
				),
				'default'    => array( 'five_star', 'four_star' ),
				'dependency' => array(
					'display_testimonials_from',
					'==',
					'based_on_rating_star',
					true,
				),
			),
			array(
				'id'          => 'category_list',
				'type'        => 'select',
				'title'       => __( 'Select Group', 'testimonial-pro' ),
				'subtitle'    => __( 'Choose the group(s) to show the testimonial from.', 'testimonial-pro' ),
				'class'       => 'tpro-group-list',
				'options'     => 'categories',
				'query_args'  => array(
					'type'       => 'spt_testimonial',
					'taxonomy'   => 'testimonial_cat',
					'hide_empty' => 0,
				),
				'sortable'    => true,
				'chosen'      => true,
				'multiple'    => true,
				'placeholder' => __( 'Choose Group(s)', 'testimonial-pro' ),
				'dependency'  => array(
					'display_testimonials_from',
					'==',
					'category',
					true,
				),

			),
			array(
				'id'         => 'category_operator',
				'type'       => 'select',
				'title'      => __( 'Group Relation Type', 'testimonial-pro' ),
				'subtitle'   => __( 'Select a relation type for group.', 'testimonial-pro' ),
				'class'      => 'tpro-group-operator',
				'options'    => array(
					'IN'     => __( 'IN', 'testimonial-pro' ),
					'AND'    => __( 'AND', 'testimonial-pro' ),
					'NOT IN' => __( 'NOT IN', 'testimonial-pro' ),
				),
				'help'       => __( 'IN - Show testimonials which associate with one or more terms<br>AND - Show testimonials which match all terms<br/>NOT IN - Show testimonials which don\'t match the terms', 'testimonial-pro' ),
				'default'    => 'IN',
				'dependency' => array( 'display_testimonials_from', '==', 'category', true ),
			),
			array(
				'id'          => 'specific_testimonial',
				'type'        => 'select',
				'title'       => __( 'Specific Testimonial(s)', 'testimonial-pro' ),
				'subtitle'    => __( 'Choose the specific testimonial(s) to display.', 'testimonial-pro' ),
				'class'       => 'tpro-specific-testimonial',
				'options'     => 'posts',
				'query_args'  => array(
					'post_type'      => 'spt_testimonial',
					'post_status'    => 'publish',
					'posts_per_page' => -1,
				),
				'sortable'    => true,
				'chosen'      => true,
				'multiple'    => true,
				'placeholder' => __( 'Choose Testimonial(s)', 'testimonial-pro' ),
				'dependency'  => array( 'display_testimonials_from', '==', 'specific_testimonials', true ),
			),
			array(
				'id'          => 'exclude_testimonial',
				'type'        => 'select',
				'title'       => __( 'Exclude Testimonial(s)', 'testimonial-pro' ),
				'subtitle'    => __( 'Exclude the testimonial(s).', 'testimonial-pro' ),
				'class'       => 'tpro-exclude-testimonial',
				'options'     => 'posts',
				'query_args'  => array(
					'post_type'      => 'spt_testimonial',
					'post_status'    => 'publish',
					'posts_per_page' => -1,
				),
				'chosen'      => true,
				'multiple'    => true,
				'placeholder' => __( 'Exclude Testimonial(s)', 'testimonial-pro' ),
				'dependency'  => array( 'display_testimonials_from', 'any', 'latest,category' ),
			),
			array(
				'id'       => 'number_of_total_testimonials',
				'type'     => 'spinner',
				'title'    => __( 'Limit', 'testimonial-pro' ),
				'subtitle' => __( 'Leave it empty to show all testimonials.', 'testimonial-pro' ),
				'default'  => '',
				'min'      => -1,
			),
			array(
				'id'         => 'random_order',
				'type'       => 'switcher',
				'title'      => __( 'Random Order', 'testimonial-pro' ),
				'subtitle'   => __( 'Enable to display testimonials in random order.', 'testimonial-pro' ),
				'text_on'    => __( 'Enabled', 'testimonial-pro' ),
				'text_off'   => __( 'Disabled', 'testimonial-pro' ),
				'text_width' => 100,
				'default'    => false,
			),
			array(
				'id'         => 'testimonial_order_by',
				'type'       => 'select',
				'title'      => __( 'Order By', 'testimonial-pro' ),
				'subtitle'   => __( 'Select an order by option.', 'testimonial-pro' ),
				'title_help' => __( 'For the Drag and Drop option, you need to reorder your testimonials by dragging and dropping them on the All Testimonials page.', 'testimonial-pro' ),
				'options'    => array(
					'ID'         => __( 'Testimonial ID', 'testimonial-pro' ),
					'date'       => __( 'Date', 'testimonial-pro' ),
					'title'      => __( 'Title', 'testimonial-pro' ),
					'modified'   => __( 'Modified', 'testimonial-pro' ),
					'menu_order' => __( 'Drag & Drop', 'testimonial-pro' ),
				),
				'default'    => 'menu_order',
				'dependency' => array( 'random_order', '==', 'false' ),
			),
			array(
				'id'         => 'testimonial_order',
				'type'       => 'select',
				'title'      => __( 'Order Type', 'testimonial-pro' ),
				'subtitle'   => __( 'Select an order option.', 'testimonial-pro' ),
				'options'    => array(
					'ASC'  => __( 'Ascending', 'testimonial-pro' ),
					'DESC' => __( 'Descending', 'testimonial-pro' ),
				),
				'default'    => 'DESC',
				'dependency' => array( 'random_order', '==', 'false' ),
			),
			array(
				'id'         => 'ajax_live_filter',
				'type'       => 'switcher',
				'title'      => __( 'Ajax Live Filters', 'testimonial-pro' ),
				'class'      => 'testimonial-live-filter',
				'subtitle'   => __( 'Enable the Ajax Live Filters by groups or star ratings.', 'testimonial-pro' ),
				'title_help' => '<div class="spftestimonial-img-tag"><img src="' . SP_TPRO_URL . 'Admin/Views/Framework/assets/images/help-visuals/ajax_live_filter.svg" alt="Ajax Live Filters"></div><div class="spftestimonial-info-label">' . __( 'Ajax Live Filters', 'testimonial-pro' ) . '</div><a class="spftestimonial-open-live-demo" href="https://realtestimonials.io/demos/advanced-ajax-features/" target="_blank">Live Demo</a>',
				'text_on'    => __( 'Enabled', 'testimonial-pro' ),
				'text_off'   => __( 'Disabled', 'testimonial-pro' ),
				'text_width' => 100,
				'default'    => false,
				'dependency' => array( 'layout', 'not-any', 'thumbnail_slider,filter', true ),
			),
			array(
				'type'       => 'subheading',
				'content'    => __( 'AJAX LIVE FILTERS', 'testimonial-pro' ),
				'dependency' => array( 'ajax_live_filter|layout', '==|not-any', 'true|filter,thumbnail_slider', true ),
			),
			array(
				'type'       => 'subheading',
				'content'    => __( 'ISOTOPE FILTER', 'testimonial-pro' ),
				'dependency' => array( 'layout', '==', 'filter', true ),
			),
			array(
				'id'         => 'live_filter_sorter',
				'type'       => 'sortable',
				'title'      => __( 'Filter By', 'testimonial-pro' ),
				'subtitle'   => __( 'Enable your filter(s).', 'testimonial-pro' ),
				'class'      => 'live_filter_sorter',
				'default'    => array(
					'filter_by_star_rating' => false,
					'filter_by_group'       => true,
				),
				'fields'     => array(
					array(
						'id'         => 'filter_by_star_rating',
						'type'       => 'switcher',
						'title'      => __( 'Star Rating', 'testimonial-pro' ),
						'text_on'    => __( 'Show', 'testimonial-pro' ),
						'text_off'   => __( 'Hide', 'testimonial-pro' ),
						'text_width' => 80,
					),
					array(
						'id'         => 'filter_by_group',
						'type'       => 'switcher',
						'title'      => __( 'Groups', 'testimonial-pro' ),
						'text_on'    => __( 'Show', 'testimonial-pro' ),
						'text_off'   => __( 'Hide', 'testimonial-pro' ),
						'text_width' => 80,
					),
				),
				'dependency' => array( 'layout|ajax_live_filter', 'not-any|==', 'thumbnail_slider,filter|true', true ),
			),
			array(
				'id'       => 'live_filter_type',
				'type'     => 'image_select',
				'class'    => 'live_filter_type sp-testimonial-live-filters-options',
				'title'    => __( 'Filter Style', 'testimonial-pro' ),
				'subtitle' => __( 'Choose a filter style.', 'testimonial-pro' ),
				'options'  => array(
					'filter_button'   => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/filter-button.svg',
						'name'  => __( 'Button', 'testimonial-pro' ),
					),
					'filter_dropdown' => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/filter-dropdown.svg',
						'name'  => __( 'Dropdown', 'testimonial-pro' ),
					),
				),
				'default'  => 'filter_button',
			),
			array(
				'id'         => 'filter_ratings_label_type',
				'type'       => 'image_select',
				'class'      => 'filter_ratings_label_type',
				'title'      => __( 'Star Rating Label Style', 'testimonial-pro' ),
				'subtitle'   => __( 'Select a star rating label style.', 'testimonial-pro' ),
				'options'    => array(
					'text'           => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/rating-style/text-r.svg',
						'name'  => __( 'Label', 'testimonial-pro' ),
					),
					'icon'           => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/rating-style/icon-r.svg',
						'name'  => __( 'Star', 'testimonial-pro' ),
					),
					'text_with_icon' => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/rating-style/text-icon-r.svg',
						'name'  => __( 'Label with Star', 'testimonial-pro' ),
					),
				),
				'default'    => 'text',
				'dependency' => array( 'layout|ajax_live_filter|filter_by_star_rating', 'not-any|==|==', 'thumbnail_slider,filter|true|true', true ),
			),
			array(
				'id'         => 'star_rating_filter_as_dropdown',
				'type'       => 'switcher',
				'class'      => 'star_rating_filter_as_dropdown',
				'title'      => __( 'Star Rating Filter as Dropdown', 'testimonial-pro' ),
				'subtitle'   => __( 'Enable the dropdown style.', 'testimonial-pro' ),
				'text_on'    => __( 'Enabled', 'testimonial-pro' ),
				'text_off'   => __( 'Disabled', 'testimonial-pro' ),
				'text_width' => 100,
				'default'    => false,
				'title_help' => '<div class="spftestimonial-info-label">' . __( 'Star Rating Filter as Dropdown', 'testimonial-pro' ) . '</div><div class="spftestimonial-short-content">' . __( 'When Enabled, the Star Rating filter will appear as a dropdown along with the button-styled Group filter.', 'testimonial-pro' ) . '</div><a class="spftestimonial-open-live-demo" href="https://realtestimonials.io/demos/advanced-ajax-features/#star-rating-filter-as-dropdown" target="_blank">' . __( 'Live Demo', 'testimonial-pro' ) . '</a>',
				'dependency' => array( 'layout|ajax_live_filter|filter_by_star_rating|live_filter_type|filter_by_group|filter_by_star_rating', 'not-any|==|==|==|==|==', 'thumbnail_slider,filter|true|true|filter_button|true|true', true ),
			),
			array(
				'id'         => 'enable_inline_filter_style',
				'type'       => 'switcher',
				'class'      => 'enable_inline_filter_style',
				'title'      => __( 'Inline Filter Style', 'testimonial-pro' ),
				'subtitle'   => __( 'Enable the testimonial inline filter style.', 'testimonial-pro' ),
				'text_on'    => __( 'Enabled', 'testimonial-pro' ),
				'text_off'   => __( 'Disabled', 'testimonial-pro' ),
				'text_width' => 100,
				'default'    => false,
				'title_help' => '<div class="spftestimonial-img-tag"><img src="' . SP_TPRO_URL . 'Admin/Views/Framework/assets/images/help-visuals/inline_filter_style.svg" alt="Inline Filter Style"></div><div class="spftestimonial-info-label">' . __( 'Inline Filter Style', 'testimonial-pro' ) . '</div><div class="spftestimonial-short-content">' . __( 'When Enabled, It will show the Group and Star Rating filters in inline style; when disabled, it will show the filters in horizontal style.', 'testimonial-pro' ) . '</div><a class="spftestimonial-open-live-demo" href="https://realtestimonials.io/demos/advanced-ajax-features/#live-filter-inline-style" target="_blank">Live Demo</a>',
				'dependency' => array( 'layout|ajax_live_filter|filter_by_group|filter_by_star_rating', 'not-any|==|==|==', 'thumbnail_slider,filter|true|true|true', true ),
			),
			array(
				'id'         => 'filter_by_rating_orientation',
				'type'       => 'button_set',
				'title'      => __( 'Star Rating Filter Orientation', 'testimonial-pro' ),
				'subtitle'   => __( 'Choose a star rating filter orientation, whether horizontal or vertical.', 'testimonial-pro' ),
				'options'    => array(
					'horizontal' => 'Horizontal',
					'vertical'   => 'Vertical',
				),
				'default'    => 'horizontal',
				'title_help' => '<div class="spftestimonial-img-tag"><img src="' . SP_TPRO_URL . 'Admin/Views/Framework/assets/images/help-visuals/star_rating_filter_orientation.svg" alt="Star Rating Filter Orientation"></div><div class="spftestimonial-info-label">' . __( 'Star Rating Filter Orientation', 'testimonial-pro' ) . '</div><div class="spftestimonial-short-content">' . __( 'For the vertical style, when the average rating section is enabled with the rating star filter, the rating star filter will show as a side-by-side view.', 'testimonial-pro' ) . '</div><a class="spftestimonial-open-live-demo" href="https://realtestimonials.io/demos/advanced-ajax-features/#live-filter-by-star-rating" target="_blank">Live Demo</a>',
				'dependency' => array( 'layout|ajax_live_filter|filter_by_star_rating|live_filter_type|star_rating_filter_as_dropdown', 'not-any|==|==|==|!=', 'thumbnail_slider,filter|true|true|filter_button|true', true ),
			),
			array(
				'id'         => 'filter_testimonials_counter',
				'type'       => 'switcher',
				'title'      => __( 'Counter', 'testimonial-pro' ),
				'subtitle'   => __( 'Show/Hide testimonial counter for star rating.', 'testimonial-pro' ),
				'text_on'    => __( 'Show', 'testimonial-pro' ),
				'text_off'   => __( 'Hide', 'testimonial-pro' ),
				'text_width' => 80,
				'default'    => false,
				'dependency' => array( 'filter_by_star_rating', '==', 'true', true ),
				'dependency' => array( 'layout|filter_by_star_rating|ajax_live_filter', 'not-any|==|==', 'thumbnail_slider,filter|true|true', true ),
			),
			array(
				'id'         => 'all_tab',
				'type'       => 'switcher',
				'class'      => 'sp-testimonial-live-filters-options',
				'title'      => __( 'Display All Tab', 'testimonial-pro' ),
				'subtitle'   => __( 'Show/Hide "All" tab.', 'testimonial-pro' ),
				'text_on'    => __( 'Show', 'testimonial-pro' ),
				'text_off'   => __( 'Hide', 'testimonial-pro' ),
				'text_width' => 80,
				'default'    => true,
			),
			array(
				'id'         => 'all_tab_text',
				'type'       => 'text',
				'class'      => 'all_tab_text sp-testimonial-live-filters-options',
				'title'      => __( 'All Tab Label', 'testimonial-pro' ),
				'subtitle'   => __( 'Set "All" tab label text.', 'testimonial-pro' ),
				'default'    => 'All',
				'dependency' => array( 'all_tab', '==', 'true', true ),
			),
			array(
				'id'       => 'filter_alignment',
				'type'     => 'button_set',
				'class'    => 'button_set_smaller sp-testimonial-live-filters-options',
				'title'    => __( 'Button Alignment', 'testimonial-pro' ),
				'subtitle' => __( 'Set alignment for filter button.', 'testimonial-pro' ),
				'options'  => array(
					'left'   => '<i class="fa fa-align-left" title="left"></i>',
					'center' => '<i class="fa fa-align-center" title="center"></i>',
					'right'  => '<i class="fa fa-align-right" title="right"></i>',
				),
				'default'  => 'center',
			),
			array(
				'id'       => 'filter_colors',
				'type'     => 'color_group',
				'class'    => 'sp-testimonial-live-filters-options',
				'title'    => __( 'Filter Button Color', 'testimonial-pro' ),
				'subtitle' => __( 'Set color for filter button.', 'testimonial-pro' ),
				'options'  => array(
					'color'             => __( 'Color', 'testimonial-pro' ),
					'active-color'      => __( 'Active Color', 'testimonial-pro' ),
					'background'        => __( 'Background', 'testimonial-pro' ),
					'active-background' => __( 'Active Background', 'testimonial-pro' ),
				),
				'default'  => array(
					'color'             => '#7e7e7e',
					'active-color'      => '#ffffff',
					'background'        => '#ffffff',
					'active-background' => '#1595CE',
				),
			),
			array(
				'id'          => 'filter_border',
				'type'        => 'border',
				'title'       => __( 'Border', 'testimonial-pro' ),
				'class'       => 'sp-testimonial-live-filters-options',
				'subtitle'    => __( 'Set border for the filter button.', 'testimonial-pro' ),
				'all'         => true,
				'hover_color' => true,
				'default'     => array(
					'all'         => '2',
					'style'       => 'solid',
					'color'       => '#aeaeae',
					'hover-color' => '#1595CE',
				),
			),
			array(
				'id'          => 'filter_margin',
				'type'        => 'spacing',
				'title'       => __( 'Margin', 'testimonial-pro' ),
				'class'       => 'sp-testimonial-live-filters-options',
				'subtitle'    => __( 'Set margin for filter.', 'testimonial-pro' ),
				'default'     => array(
					'top'    => '0',
					'right'  => '0',
					'bottom' => '24',
					'left'   => '0',
					'unit'   => 'px',
				),
				'top_text'    => __( 'Top', 'testimonial-pro' ),
				'right_text'  => __( 'Right', 'testimonial-pro' ),
				'bottom_text' => __( 'Bottom', 'testimonial-pro' ),
				'left_text'   => __( 'Left', 'testimonial-pro' ),
				'units'       => array( 'px' ),
			),
			array(
				'type'       => 'subheading',
				'content'    => __( 'AJAX PAGINATION', 'testimonial-pro' ),
				'dependency' => array( 'layout', 'any', 'grid,filter,masonry,list', true ),
			),
			array(
				'id'         => 'filter_pagination',
				'type'       => 'switcher',
				'title'      => __( 'Pagination', 'testimonial-pro' ),
				'subtitle'   => __( 'Enable/Disable pagination.', 'testimonial-pro' ),
				'text_on'    => __( 'Enabled', 'testimonial-pro' ),
				'text_off'   => __( 'Disabled', 'testimonial-pro' ),
				'text_width' => 100,
				'default'    => true,
				'dependency' => array(
					'layout',
					'==',
					'filter',
					true,
				),
			),
			array(
				'id'         => 'filter_pagination_type',
				'type'       => 'radio',
				'title'      => __( 'Pagination Type', 'testimonial-pro' ),
				'subtitle'   => __( 'Choose a pagination type.', 'testimonial-pro' ),
				'options'    => array(
					'ajax_load_more'  => __( 'Load More Button (Ajax)', 'testimonial-pro' ),
					'infinite_scroll' => __( 'Infinite Scroll (Ajax)', 'testimonial-pro' ),
				),
				'default'    => 'ajax_load_more',
				'dependency' => array(
					'layout|filter_pagination',
					'==|==',
					'filter|true',
					true,
				),
			),
			array(
				'id'         => 'filter_per_page',
				'type'       => 'spinner',
				'title'      => __( 'Testimonial(s) To Show Per Page', 'testimonial-pro' ),
				'subtitle'   => __( 'Set number of testimonial(s) to show per page/click.', 'testimonial-pro' ),
				'default'    => 12,
				'dependency' => array(
					'layout|filter_pagination',
					'==|==',
					'filter|true',
					true,
				),
			),
			array(
				'id'         => 'filter_load_more_label',
				'type'       => 'text',
				'title'      => __( 'Load More Button Label', 'testimonial-pro' ),
				'subtitle'   => __( 'Change load more button label text.', 'testimonial-pro' ),
				'default'    => 'Load More',
				'dependency' => array(
					'filter_pagination|layout|filter_pagination_type',
					'==|==|==',
					'true|filter|ajax_load_more',
					true,
				),
			),
			array(
				'id'         => 'filter_pagination_alignment',
				'type'       => 'button_set',
				'class'      => 'button_set_smaller',
				'title'      => __( 'Alignment', 'testimonial-pro' ),
				'subtitle'   => __( 'Select pagination alignment.', 'testimonial-pro' ),
				'options'    => array(
					'left'   => '<i class="fa fa-align-left" title="left"></i>',
					'center' => '<i class="fa fa-align-center" title="center"></i>',
					'right'  => '<i class="fa fa-align-right" title="right"></i>',
				),
				'default'    => 'center',
				'dependency' => array(
					'filter_pagination|layout',
					'==|==',
					'true|filter',
					true,
				),
			),
			array(
				'id'          => 'filter_pagination_margin',
				'type'        => 'spacing',
				'title'       => __( 'Margin', 'testimonial-pro' ),
				'subtitle'    => __( 'Set pagination margin.', 'testimonial-pro' ),
				'default'     => array(
					'top'    => '20',
					'right'  => '0',
					'bottom' => '20',
					'left'   => '0',
					'unit'   => 'px',
				),
				'top_text'    => __( 'Top', 'testimonial-pro' ),
				'right_text'  => __( 'Right', 'testimonial-pro' ),
				'bottom_text' => __( 'Bottom', 'testimonial-pro' ),
				'left_text'   => __( 'Left', 'testimonial-pro' ),
				'units'       => array( 'px' ),
				'dependency'  => array(
					'filter_pagination|layout|filter_pagination_type',
					'==|==|!=',
					'true|filter|infinite_scroll',
					true,
				),
			),
			array(
				'id'         => 'filter_pagination_colors',
				'type'       => 'color_group',
				'title'      => __( 'Color', 'testimonial-pro' ),
				'subtitle'   => __( 'Set color for pagination.', 'testimonial-pro' ),
				'options'    => array(
					'color'            => __( 'Color', 'testimonial-pro' ),
					'hover-color'      => __( 'Hover Color', 'testimonial-pro' ),
					'background'       => __( 'Background', 'testimonial-pro' ),
					'hover-background' => __( 'Hover Background', 'testimonial-pro' ),
				),
				'default'    => array(
					'color'            => '#5e5e5e',
					'hover-color'      => '#ffffff',
					'background'       => '#ffffff',
					'hover-background' => '#1595CE',
				),
				'dependency' => array(
					'filter_pagination|layout|filter_pagination_type',
					'==|any|!=',
					'true|filter|infinite_scroll',
					true,
				),
			),
			array(
				'id'          => 'filter_pagination_border',
				'type'        => 'border',
				'title'       => __( 'Border', 'testimonial-pro' ),
				'subtitle'    => __( 'Set pagination border.', 'testimonial-pro' ),
				'all'         => true,
				'hover_color' => true,
				'default'     => array(
					'all'         => '2',
					'style'       => 'solid',
					'color'       => '#bbbbbb',
					'hover-color' => '#1595CE',
				),
				'dependency'  => array(
					'filter_pagination|layout|filter_pagination_type',
					'==|filter|!=',
					'true|filter|infinite_scroll',
					true,
				),
			),
			array(
				'id'         => 'grid_pagination',
				'type'       => 'switcher',
				'title'      => __( 'Pagination', 'testimonial-pro' ),
				'subtitle'   => __( 'Enable/Disable pagination.', 'testimonial-pro' ),
				'text_on'    => __( 'Enabled', 'testimonial-pro' ),
				'text_off'   => __( 'Disabled', 'testimonial-pro' ),
				'text_width' => 100,
				'default'    => true,
				'dependency' => array(
					'layout',
					'any',
					'grid,masonry,list',
					true,
				),
			),
			array(
				'id'         => 'tp_pagination_type',
				'type'       => 'radio',
				'title'      => __( 'Pagination Type', 'testimonial-pro' ),
				'subtitle'   => __( 'Choose a pagination type.', 'testimonial-pro' ),
				'options'    => array(
					'ajax_load_more'  => __( 'Load More Button (Ajax)', 'testimonial-pro' ),
					'ajax_pagination' => __( 'Ajax Number Pagination', 'testimonial-pro' ),
					'infinite_scroll' => __( 'Infinite Scroll (Ajax)', 'testimonial-pro' ),
					'no_ajax'         => __( 'No Ajax (Normal Pagination)', 'testimonial-pro' ),
				),
				'default'    => 'no_ajax',
				'dependency' => array(
					'layout|grid_pagination',
					'any|==',
					'grid,masonry,list|true',
					true,
				),
			),
			array(
				'id'         => 'tp_per_page',
				'type'       => 'spinner',
				'title'      => __( 'Testimonial(s) To Show Per Page', 'testimonial-pro' ),
				'subtitle'   => __( 'Set number of testimonial(s) to show per page/click.', 'testimonial-pro' ),
				'default'    => 12,
				'dependency' => array(
					'layout|grid_pagination',
					'any|==',
					'grid,masonry,list|true',
					true,
				),
			),
			array(
				'id'         => 'load_more_label',
				'type'       => 'text',
				'title'      => __( 'Load More Button Label', 'testimonial-pro' ),
				'subtitle'   => __( 'Change load more button label text.', 'testimonial-pro' ),
				'default'    => 'Load More',
				'dependency' => array(
					'grid_pagination|layout|tp_pagination_type',
					'==|any|==',
					'true|grid,masonry,list|ajax_load_more',
					true,
				),
			),
			array(
				'id'         => 'grid_pagination_alignment',
				'type'       => 'button_set',
				'class'      => 'button_set_smaller',
				'title'      => __( 'Alignment', 'testimonial-pro' ),
				'subtitle'   => __( 'Select pagination alignment.', 'testimonial-pro' ),
				'options'    => array(
					'left'   => '<i class="fa fa-align-left" title="left"></i>',
					'center' => '<i class="fa fa-align-center" title="center"></i>',
					'right'  => '<i class="fa fa-align-right" title="right"></i>',
				),
				'default'    => 'center',
				'dependency' => array(
					'grid_pagination|layout',
					'==|any',
					'true|grid,masonry,list',
					true,
				),
			),
			array(
				'id'          => 'grid_pagination_margin',
				'type'        => 'spacing',
				'title'       => __( 'Margin', 'testimonial-pro' ),
				'subtitle'    => __( 'Set pagination margin.', 'testimonial-pro' ),
				'default'     => array(
					'top'    => '20',
					'right'  => '0',
					'bottom' => '20',
					'left'   => '0',
					'unit'   => 'px',
				),
				'top_text'    => __( 'Top', 'testimonial-pro' ),
				'right_text'  => __( 'Right', 'testimonial-pro' ),
				'bottom_text' => __( 'Bottom', 'testimonial-pro' ),
				'left_text'   => __( 'Left', 'testimonial-pro' ),
				'units'       => array( 'px' ),
				'dependency'  => array(
					'grid_pagination|layout|tp_pagination_type',
					'==|any|!=',
					'true|grid,masonry,list|infinite_scroll',
					true,
				),
			),
			array(
				'id'         => 'grid_pagination_colors',
				'type'       => 'color_group',
				'title'      => __( 'Color', 'testimonial-pro' ),
				'subtitle'   => __( 'Set color for pagination.', 'testimonial-pro' ),
				'options'    => array(
					'color'            => __( 'Color', 'testimonial-pro' ),
					'hover-color'      => __( 'Hover Color', 'testimonial-pro' ),
					'background'       => __( 'Background', 'testimonial-pro' ),
					'hover-background' => __( 'Hover Background', 'testimonial-pro' ),
				),
				'default'    => array(
					'color'            => '#5e5e5e',
					'hover-color'      => '#ffffff',
					'background'       => '#ffffff',
					'hover-background' => '#1595CE',
				),
				'dependency' => array(
					'grid_pagination|layout|tp_pagination_type',
					'==|any|!=',
					'true|grid,masonry,list|infinite_scroll',
					true,
				),
			),
			array(
				'id'          => 'grid_pagination_border',
				'type'        => 'border',
				'title'       => __( 'Border', 'testimonial-pro' ),
				'subtitle'    => __( 'Set pagination border.', 'testimonial-pro' ),
				'all'         => true,
				'hover_color' => true,
				'default'     => array(
					'all'         => '2',
					'style'       => 'solid',
					'color'       => '#bbbbbb',
					'hover-color' => '#1595CE',
				),
				'dependency'  => array(
					'grid_pagination|layout|tp_pagination_type',
					'==|any|!=',
					'true|grid,masonry,list|infinite_scroll',
					true,
				),
			),
		),
	)
);

SPFTESTIMONIAL::createSection(
	$prefix_shortcode_opts,
	array(
		'title'  => __( 'Theme Settings', 'testimonial-pro' ),
		'icon'   => 'sptpro-icon-theme-styles',
		'fields' => array(
			array(
				'id'         => 'theme_style',
				'class'      => 'theme_style',
				'type'       => 'image_select',
				'title'      => __( 'Select Your Theme', 'testimonial-pro' ),
				'subtitle'   => __( 'Select which theme style you want to use. <b>Please note:</b> To get the perfect view for certain themes, you need to configure some settings below.', 'testimonial-pro' ),
				'options'    => array(
					'theme-one'   => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/theme-style/theme_one.svg',
						'name'  => __( 'Theme One', 'testimonial-pro' ),
					),
					'theme-two'   => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/theme-style/theme_two.svg',
						'name'  => __( 'Theme Two', 'testimonial-pro' ),
					),
					'theme-three' => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/theme-style/theme_three.svg',
						'name'  => __( 'Theme Three', 'testimonial-pro' ),
					),
					'theme-four'  => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/theme-style/theme_four.svg',
						'name'  => __( 'Theme Four', 'testimonial-pro' ),
					),
					'theme-five'  => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/theme-style/theme_five.svg',
						'name'  => __( 'Theme Five', 'testimonial-pro' ),
					),
					'theme-six'   => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/theme-style/theme_six.svg',
						'name'  => __( 'Theme Six', 'testimonial-pro' ),
					),
					'theme-seven' => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/theme-style/theme_seven.svg',
						'name'  => __( 'Theme Seven', 'testimonial-pro' ),
					),
					'theme-eight' => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/theme-style/theme_eight.svg',
						'name'  => __( 'Theme Eight', 'testimonial-pro' ),
					),
					'theme-nine'  => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/theme-style/theme_nine.svg',
						'name'  => __( 'Theme Nine', 'testimonial-pro' ),
					),
					'theme-ten'   => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/theme-style/theme_ten.svg',
						'name'  => __( 'Theme Ten', 'testimonial-pro' ),
					),
				),
				'default'    => 'theme-one',
				'dependency' => array( 'layout', '!=', 'thumbnail_slider', true ),
			),
			array(
				'type'       => 'subheading',
				'content'    => __( 'CONFIGURE THEME', 'testimonial-pro' ),
				'dependency' => array( 'layout', '!=', 'thumbnail_slider', true ),
			),
			array(
				'id'         => 'client_image_position',
				'type'       => 'button_set',
				'class'      => 'button_set_smaller',
				'title'      => __( 'Reviewer Image Alignment', 'testimonial-pro' ),
				'subtitle'   => __( 'Set an alignment for the reviewer image.', 'testimonial-pro' ),
				'options'    => array(
					'left'   => '<i class="fa fa-align-left" title="left"></i>',
					'center' => '<i class="fa fa-align-center" title="center"></i>',
					'right'  => '<i class="fa fa-align-right" title="right"></i>',
				),
				'default'    => 'center',
				'dependency' => array(
					'client_image|theme_style|layout',
					'==|any|!=',
					'true|theme-one,theme-eight,theme-ten|thumbnail_slider',
					true,
				),
			),
			array(
				'id'         => 'client_image_vertical_position',
				'type'       => 'select',
				'class'      => 'button_set_smaller',
				'title'      => __( 'Reviewer Image Position', 'testimonial-pro' ),
				'subtitle'   => __( 'Select a position for the reviewer image.', 'testimonial-pro' ),
				'options'    => array(
					'top'    => 'Top',
					'middle' => 'Middle',
					'bottom' => 'Bottom',
				),
				'default'    => 'top',
				'dependency' => array(
					'client_image|theme_style|layout',
					'==|==|!=',
					'true|theme-one|thumbnail_slider',
					true,
				),
			),
			array(
				'id'         => 'client_image_position_two',
				'type'       => 'select',
				'title'      => __( 'Reviewer Image Position', 'testimonial-pro' ),
				'subtitle'   => __( 'Select a position for the reviewer image.', 'testimonial-pro' ),
				'options'    => array(
					'left'   => __( 'Left', 'testimonial-pro' ),
					'right'  => __( 'Right', 'testimonial-pro' ),
					'top'    => __( 'Top', 'testimonial-pro' ),
					'bottom' => __( 'Bottom', 'testimonial-pro' ),
				),
				'default'    => 'left',
				'dependency' => array(
					'client_image|theme_style',
					'==|any',
					'true|theme-two,theme-four,theme-five',
					true,
				),
			),
			array(
				'id'         => 'client_image_position_three',
				'type'       => 'select',
				'title'      => __( 'Reviewer Image Position', 'testimonial-pro' ),
				'subtitle'   => __( 'Select a position for the reviewer image.', 'testimonial-pro' ),
				'options'    => array(
					'left-top'     => __( 'Left Top', 'testimonial-pro' ),
					'left-bottom'  => __( 'Left Bottom', 'testimonial-pro' ),
					'right-top'    => __( 'Right Top', 'testimonial-pro' ),
					'right-bottom' => __( 'Right Bottom', 'testimonial-pro' ),
					'top-left'     => __( 'Top Left', 'testimonial-pro' ),
					'top-right'    => __( 'Top Right', 'testimonial-pro' ),
					'bottom-left'  => __( 'Bottom Left', 'testimonial-pro' ),
					'bottom-right' => __( 'Bottom Right', 'testimonial-pro' ),
				),
				'default'    => 'left-top',
				'dependency' => array(
					'client_image|theme_style',
					'==|any',
					'true|theme-three,theme-six',
					true,
				),
			),
			array(
				'id'       => 'background_color_type',
				'type'     => 'button_set',
				'title'    => __( 'Background Color Type', 'testimonial-pro' ),
				'subtitle' => __( 'Choose background color type.', 'testimonial-pro' ),
				'options'  => array(
					'solid'           => 'Solid',
					'gradient'        => 'Gradient',
					'gradient_preset' => 'Preset',
				),
				'default'  => 'solid',
			),
			array(
				'id'         => 'gradient_preset_color',
				'type'       => 'select',
				'class'      => 'gradient_preset_color',
				'title'      => __( 'Gradient Preset', 'testimonial-pro' ),
				'subtitle'   => __( 'Select a gradient preset.', 'testimonial-pro' ),
				'desc'       => __( '<div class="testimonial-gradient-color-preview">The plugin is simple to set up and does exactly what you need.</div>', 'testimonial-pro' ),
				'options'    => array(
					'light_blue'        => __( 'Light Blue', 'testimonial-pro' ),
					'sea_fog'           => __( 'Sea Fog', 'testimonial-pro' ),
					'below_content'     => __( 'Lavender Breeze', 'testimonial-pro' ),
					'soft_peach'        => __( 'Soft Peach', 'testimonial-pro' ),
					'clean_blue'        => __( 'Clean Blue', 'testimonial-pro' ),
					'muted_green'       => __( 'Muted Green', 'testimonial-pro' ),
					'light_grey'        => __( 'Light Grey', 'testimonial-pro' ),
					'pastel_rainbow'    => __( 'Pastel Rainbow', 'testimonial-pro' ),
					'neon_glow'         => __( 'Neon Glow', 'testimonial-pro' ),
					'holographic_dream' => __( 'Holographic Dream', 'testimonial-pro' ),
					'sky_blue'          => __( 'Sky Blue', 'testimonial-pro' ),
				),
				'default'    => 'above_title',
				'dependency' => array( 'background_color_type', '==', 'gradient_preset', true ),
			),
			array(
				'id'                    => 'testimonial_bg_gradient_color',
				'type'                  => 'background',
				'class'                 => 'testimonial_bg_gradient_color',
				'title'                 => __( 'Gradient Color', 'testimonial-pro' ),
				'subtitle'              => __( 'Set background color for testimonial.', 'testimonial-pro' ),
				'background_gradient'   => true,
				'background_color'      => true,
				'background_image'      => false,
				'background_position'   => false,
				'background_repeat'     => false,
				'background_attachment' => false,
				'background_size'       => false,
				'default'               => array(
					'background-color'              => '#2193b0',
					'background-gradient-color'     => '#6dd5ed',
					'background-gradient-direction' => '135deg',
				),
				'dependency'            => array(
					'background_color_type',
					'==',
					'gradient',
					true,
				),
			),
			array(
				'id'         => 'testimonial_bg_for_thumbnail',
				'type'       => 'color',
				'title'      => __( 'Solid Color', 'testimonial-pro' ),
				'subtitle'   => __( 'Set background color for testimonial.', 'testimonial-pro' ),
				'default'    => 'transparent',
				'dependency' => array(
					'layout|background_color_type',
					'==|==',
					'thumbnail_slider|solid',
					true,
				),
			),
			array(
				'id'         => 'testimonial_bg_for_one',
				'type'       => 'color',
				'title'      => __( 'Solid Color', 'testimonial-pro' ),
				'subtitle'   => __( 'Set background color for testimonial.', 'testimonial-pro' ),
				'default'    => 'transparent',
				'dependency' => array(
					'theme_style|layout|background_color_type',
					'==|!=|==',
					'theme-one|thumbnail_slider|solid',
					true,
				),
			),
			array(
				'id'         => 'testimonial_bg',
				'type'       => 'color',
				'title'      => __( 'Solid Color', 'testimonial-pro' ),
				'subtitle'   => __( 'Set background color for testimonial.', 'testimonial-pro' ),
				'default'    => '#ffffff',
				'dependency' => array(
					'theme_style|layout|background_color_type',
					'any|!=|==',
					'theme-two,theme-three,theme-five,theme-six,theme-ten|thumbnail_slider|solid',
					true,
				),
			),
			array(
				'id'         => 'testimonial_bg_two',
				'type'       => 'color',
				'title'      => __( 'Solid Color', 'testimonial-pro' ),
				'subtitle'   => __( 'Set background color for testimonial.', 'testimonial-pro' ),
				'default'    => '#f5f5f5',
				'dependency' => array(
					'theme_style|layout|background_color_type',
					'any|!=|==',
					'theme-four|thumbnail_slider|solid',
					true,
				),
			),
			array(
				'id'         => 'testimonial_bg_three',
				'type'       => 'color',
				'title'      => __( 'Solid Color', 'testimonial-pro' ),
				'subtitle'   => __( 'Set background color for testimonial.', 'testimonial-pro' ),
				'default'    => '#e57373',
				'dependency' => array(
					'theme_style|layout|background_color_type',
					'any|!=|==',
					'theme-seven,theme-eight,theme-nine|thumbnail_slider|solid',
					true,
				),
			),
			array(
				'id'         => 'testimonial_border_for_one',
				'type'       => 'border',
				'title'      => __( 'Border', 'testimonial-pro' ),
				'subtitle'   => __( 'Set border for testimonial.', 'testimonial-pro' ),
				'all'        => true,
				'radius'     => true,
				'default'    => array(
					'all'    => '0',
					'style'  => 'solid',
					'color'  => '#e3e3e3',
					'radius' => '0',
					'unit'   => '%',
				),
				'units'      => array(
					'px',
					'%',
				),
				'dependency' => array(
					'theme_style|layout',
					'==|!=',
					'theme-one|thumbnail_slider',
					true,
				),
			),
			array(
				'id'         => 'testimonial_border_for_thumbnail',
				'type'       => 'border',
				'title'      => __( 'Border', 'testimonial-pro' ),
				'subtitle'   => __( 'Set border for testimonial.', 'testimonial-pro' ),
				'all'        => true,
				'radius'     => true,
				'default'    => array(
					'all'    => '0',
					'style'  => 'solid',
					'color'  => '#e3e3e3',
					'radius' => '0',
				),
				'dependency' => array(
					'layout',
					'==',
					'thumbnail_slider',
					true,
				),
			),
			array(
				'id'         => 'testimonial_border',
				'type'       => 'border',
				'class'      => 'testimonial_border',
				'title'      => __( 'Border', 'testimonial-pro' ),
				'subtitle'   => __( 'Set border for testimonial.', 'testimonial-pro' ),
				'all'        => true,
				'radius'     => true,
				'default'    => array(
					'all'    => '1',
					'style'  => 'solid',
					'color'  => '#e3e3e3',
					'radius' => '0',
				),
				'dependency' => array(
					'theme_style|layout',
					'any|!=',
					'theme-two,theme-three,theme-four,theme-five,theme-six,theme-seven,theme-eight,theme-nine|thumbnail_slider',
					true,
				),
			),
			array(
				'id'         => 'testimonial_border_theme_ten',
				'type'       => 'border',
				'title'      => __( 'Border', 'testimonial-pro' ),
				'subtitle'   => __( 'Set border for testimonial.', 'testimonial-pro' ),
				'all'        => true,
				'radius'     => true,
				'default'    => array(
					'all'    => '1',
					'style'  => 'solid',
					'color'  => '#e3e3e3',
					'radius' => '10',
				),
				'dependency' => array(
					'theme_style|layout',
					'==|!=',
					'theme-ten|thumbnail_slider',
					true,
				),
			),
			array(
				'id'       => 'testimonial_shadow_type',
				'type'     => 'button_set',
				'title'    => __( 'BoxShadow', 'testimonial-pro' ),
				'subtitle' => __( 'Set boxshadow for the testimonial.', 'testimonial-pro' ),
				'options'  => array(
					'shadow_inset'  => __( 'Inset', 'testimonial-pro' ),
					'shadow_outset' => __( 'Outset', 'testimonial-pro' ),
					'none'          => __( 'None', 'testimonial-pro' ),
				),
				'default'  => 'none',
			),
			array(
				'id'          => 'testimonial_box_shadow_property',
				'type'        => 'box_shadow',
				'title'       => __( 'Box-Shadow Values', 'testimonial-pro' ),
				'subtitle'    => __( 'Set box-shadow property values for the item.', 'testimonial-pro' ),
				'hover_color' => true,
				'default'     => array(
					'horizontal'  => '0',
					'vertical'    => '0',
					'blur'        => '6',
					'spread'      => '0',
					'color'       => '#ededed',
					'hover_color' => '#dddddd',
				),
				'dependency'  => array(
					'testimonial_shadow_type',
					'!=',
					'none',
					true,
				),
			),
			array(
				'id'          => 'testimonial_inner_padding_for_thumbnail',
				'type'        => 'spacing',
				'class'       => 'testimonial_inner_padding',
				'title'       => __( 'Inner Padding', 'testimonial-pro' ),
				'subtitle'    => __( 'Set testimonial inner padding.', 'testimonial-pro' ),
				'title_help'  => '<div class="spftestimonial-img-tag"><img src="' . SP_TPRO_URL . 'Admin/Views/Framework/assets/images/help-visuals/inner_padding.svg" alt="Inner Padding"></div><div class="spftestimonial-info-label">' . __( 'Inner Padding', 'testimonial-pro' ) . '</div>',
				'default'     => array(
					'top'    => '0',
					'right'  => '0',
					'bottom' => '0',
					'left'   => '0',
					'unit'   => 'px',
				),
				'top_text'    => __( 'Top', 'testimonial-pro' ),
				'right_text'  => __( 'Right', 'testimonial-pro' ),
				'bottom_text' => __( 'Bottom', 'testimonial-pro' ),
				'left_text'   => __( 'Left', 'testimonial-pro' ),
				'unit'        => true,
				'units'       => array( 'px' ),
				'dependency'  => array(
					'layout',
					'==',
					'thumbnail_slider',
					true,
				),
			),
			array(
				'id'          => 'testimonial_inner_padding_for_one',
				'type'        => 'spacing',
				'class'       => 'testimonial_inner_padding',
				'title'       => __( 'Inner Padding', 'testimonial-pro' ),
				'subtitle'    => __( 'Set testimonial inner padding.', 'testimonial-pro' ),
				'title_help'  => '<div class="spftestimonial-img-tag"><img src="' . SP_TPRO_URL . 'Admin/Views/Framework/assets/images/help-visuals/inner_padding.svg" alt="Inner Padding"></div><div class="spftestimonial-info-label">' . __( 'Inner Padding', 'testimonial-pro' ) . '</div>',
				'default'     => array(
					'top'    => '0',
					'right'  => '0',
					'bottom' => '0',
					'left'   => '0',
				),
				'top_text'    => __( 'Top', 'testimonial-pro' ),
				'right_text'  => __( 'Right', 'testimonial-pro' ),
				'bottom_text' => __( 'Bottom', 'testimonial-pro' ),
				'left_text'   => __( 'Left', 'testimonial-pro' ),
				'unit'        => true,
				'units'       => array( 'px' ),
				'dependency'  => array(
					'theme_style|layout',
					'==|!=',
					'theme-one|thumbnail_slider',
					true,
				),
			),
			array(
				'id'         => 'testimonial_top_bg_color_type',
				'type'       => 'button_set',
				'title'      => __( 'Top Background Color Type', 'testimonial-pro' ),
				'subtitle'   => __( 'Set testimonial top background color.', 'testimonial-pro' ),
				'options'    => array(
					'solid'    => 'Solid',
					'gradient' => 'Gradient',
				),
				'default'    => 'solid',
				'dependency' => array(
					'theme_style|layout',
					'any|!=',
					'theme-ten|thumbnail_slider',
					true,
				),
			),
			array(
				'id'         => 'testimonial_top_bg',
				'type'       => 'color',
				'title'      => __( 'Solid Color', 'testimonial-pro' ),
				'subtitle'   => __( 'Set top background color for testimonial.', 'testimonial-pro' ),
				'default'    => '#ff8a00',
				'dependency' => array(
					'theme_style|layout|testimonial_top_bg_color_type',
					'any|!=|==',
					'theme-ten|thumbnail_slider|solid',
					true,
				),
			),
			array(
				'id'                    => 'testimonial_top_bg_gradient_color',
				'type'                  => 'background',
				'class'                 => 'testimonial_bg_gradient_color',
				'title'                 => __( 'Gradient Color', 'testimonial-pro' ),
				'subtitle'              => __( 'Set background color for testimonial.', 'testimonial-pro' ),
				'background_gradient'   => true,
				'background_color'      => true,
				'background_image'      => false,
				'background_position'   => false,
				'background_repeat'     => false,
				'background_attachment' => false,
				'background_size'       => false,
				'default'               => array(
					'background-color'              => '#2193b0',
					'background-gradient-color'     => '#6dd5ed',
					'background-gradient-direction' => '135deg',
				),
				'dependency'            => array(
					'theme_style|layout|testimonial_top_bg_color_type',
					'any|!=|==',
					'theme-ten|thumbnail_slider|gradient',
					true,
				),
			),
			array(
				'id'         => 'testimonial_arrow_position_seven',
				'type'       => 'spinner',
				'title'      => __( 'Arrow Position', 'testimonial-pro' ),
				'subtitle'   => __( 'Set arrow position to move from left to right.', 'testimonial-pro' ),
				'default'    => '30',
				'dependency' => array(
					'theme_style|layout',
					'==|!=',
					'theme-seven|thumbnail_slider',
					true,
				),
			),
			array(
				'id'         => 'testimonial_arrow_position_nine',
				'type'       => 'spinner',
				'title'      => __( 'Arrow Position', 'testimonial-pro' ),
				'subtitle'   => __( 'Set arrow position/ Move arrow to left or right.', 'testimonial-pro' ),
				'default'    => '70',
				'dependency' => array(
					'theme_style|layout',
					'==|!=',
					'theme-nine|thumbnail_slider',
					true,
				),
			),
			array(
				'id'          => 'testimonial_inner_padding',
				'type'        => 'spacing',
				'class'       => 'testimonial_inner_padding',
				'title'       => __( 'Inner Padding', 'testimonial-pro' ),
				'subtitle'    => __( 'Set testimonial inner padding.', 'testimonial-pro' ),
				'title_help'  => '<div class="spftestimonial-img-tag"><img src="' . SP_TPRO_URL . 'Admin/Views/Framework/assets/images/help-visuals/inner_padding.svg" alt="Inner Padding"></div><div class="spftestimonial-info-label">' . __( 'Inner Padding', 'testimonial-pro' ) . '</div>',
				'default'     => array(
					'top'    => '22',
					'right'  => '22',
					'bottom' => '22',
					'left'   => '22',
					'unit'   => 'px',
				),
				'top_text'    => __( 'Top', 'testimonial-pro' ),
				'right_text'  => __( 'Right', 'testimonial-pro' ),
				'bottom_text' => __( 'Bottom', 'testimonial-pro' ),
				'left_text'   => __( 'Left', 'testimonial-pro' ),
				'unit'        => true,
				'units'       => array( 'px' ),
				'dependency'  => array(
					'theme_style|layout',
					'any|!=',
					'theme-two,theme-three,theme-four,theme-five,theme-six,theme-seven,theme-eight,theme-nine,theme-ten|thumbnail_slider',
					true,
				),
			),
			array(
				'id'         => 'testimonial_info_position',
				'type'       => 'button_set',
				'title'      => __( 'Reviewer Info Position', 'testimonial-pro' ),
				'subtitle'   => __( 'Select a position for the reviewer information.', 'testimonial-pro' ),
				'options'    => array(
					'top'    => __( 'Top', 'testimonial-pro' ),
					'bottom' => __( 'Bottom', 'testimonial-pro' ),
					'left'   => __( 'Left', 'testimonial-pro' ),
					'right'  => __( 'Right', 'testimonial-pro' ),
				),
				'default'    => 'bottom',
				'dependency' => array(
					'theme_style|layout',
					'any|!=',
					'theme-eight|thumbnail_slider',
					true,
				),
			),
			array(
				'id'         => 'testimonial_info_position_two',
				'type'       => 'button_set',
				'title'      => __( 'Reviewer Info Position', 'testimonial-pro' ),
				'subtitle'   => __( 'Select a position for the reviewer information.', 'testimonial-pro' ),
				'options'    => array(
					'top_left'     => __( 'Top Left', 'testimonial-pro' ),
					'top_right'    => __( 'Top Right', 'testimonial-pro' ),
					'bottom_left'  => __( 'Bottom Left', 'testimonial-pro' ),
					'bottom_right' => __( 'Bottom Right', 'testimonial-pro' ),
				),
				'default'    => 'bottom_left',
				'dependency' => array(
					'theme_style|layout',
					'any|!=',
					'theme-nine|thumbnail_slider',
					true,
				),
			),
			array(
				'id'         => 'testimonial_info_border',
				'type'       => 'border',
				'title'      => __( 'Reviewer Info Border', 'testimonial-pro' ),
				'subtitle'   => __( 'Set a border for the reviewer information.', 'testimonial-pro' ),
				'all'        => true,
				'default'    => array(
					'all'   => '0',
					'style' => 'solid',
					'color' => '#e3e3e3',
				),
				'dependency' => array(
					'theme_style|layout',
					'any|!=',
					'theme-eight,theme-nine|thumbnail_slider',
					true,
				),
			),
			array(
				'id'         => 'testimonial_info_bg_color_type',
				'type'       => 'button_set',
				'title'      => __( 'Info Background Color Type', 'testimonial-pro' ),
				'subtitle'   => __( 'Set a background color for reviewer info.', 'testimonial-pro' ),
				'options'    => array(
					'solid'    => 'Solid',
					'gradient' => 'Gradient',
				),
				'default'    => 'solid',
				'dependency' => array(
					'theme_style|layout',
					'any|!=',
					'theme-eight,theme-nine|thumbnail_slider',
					true,
				),
			),
			array(
				'id'         => 'testimonial_info_bg',
				'type'       => 'color',
				'title'      => __( 'Solid Color', 'testimonial-pro' ),
				'subtitle'   => __( 'Set a background color for reviewer information.', 'testimonial-pro' ),
				'default'    => '#f1e9e0',
				'dependency' => array(
					'theme_style|layout|testimonial_info_bg_color_type',
					'any|!=|==',
					'theme-eight,theme-nine|thumbnail_slider|solid',
					true,
				),
			),
			array(
				'id'                    => 'testimonial_info_bg_gradient_color',
				'type'                  => 'background',
				'class'                 => 'testimonial_bg_gradient_color',
				'title'                 => __( 'Gradient Color', 'testimonial-pro' ),
				'subtitle'              => __( 'Set background color for reviewer information.', 'testimonial-pro' ),
				'background_gradient'   => true,
				'background_color'      => true,
				'background_image'      => false,
				'background_position'   => false,
				'background_repeat'     => false,
				'background_attachment' => false,
				'background_size'       => false,
				'default'               => array(
					'background-color'              => '#2193b0',
					'background-gradient-color'     => '#6dd5ed',
					'background-gradient-direction' => '135deg',
				),
				'dependency'            => array(
					'theme_style|layout|testimonial_info_bg_color_type',
					'any|!=|==',
					'theme-eight,theme-nine|thumbnail_slider|gradient',
					true,
				),
			),
			array(
				'id'          => 'testimonial_info_inner_padding',
				'type'        => 'spacing',
				'class'       => 'testimonial_inner_padding',
				'title'       => __( 'Reviewer Info Inner Padding', 'testimonial-pro' ),
				'subtitle'    => __( 'Set an inner padding for the reviewer information.', 'testimonial-pro' ),
				'default'     => array(
					'top'    => '22',
					'right'  => '22',
					'bottom' => '22',
					'left'   => '22',
					'unit'   => 'px',
				),
				'top_text'    => __( 'Top', 'testimonial-pro' ),
				'right_text'  => __( 'Right', 'testimonial-pro' ),
				'bottom_text' => __( 'Bottom', 'testimonial-pro' ),
				'left_text'   => __( 'Left', 'testimonial-pro' ),
				'unit'        => true,
				'units'       => array( 'px', '%' ),
				'dependency'  => array(
					'theme_style|layout',
					'any|!=',
					'theme-eight,theme-nine|thumbnail_slider',
					true,
				),
			),
			array(
				'id'         => 'testimonial_same_height',
				'type'       => 'switcher',
				'title'      => __( 'Enable Equal Height', 'testimonial-pro' ),
				'subtitle'   => __( 'Enable to adjust the height of all the testimonial items to match the tallest one.', 'testimonial-pro' ),
				'title_help' => '<div class="spftestimonial-img-tag"><img src="' . SP_TPRO_URL . 'Admin/Views/Framework/assets/images/help-visuals/equalize_testimonials_height.svg" alt="Enable Equal Height"></div><div class="spftestimonial-info-label">' . __( 'Enable Equal Height', 'testimonial-pro' ) . '</div>',
				'default'    => false,
				'text_on'    => __( 'Enabled', 'testimonial-pro' ),
				'text_off'   => __( 'Disabled', 'testimonial-pro' ),
				'text_width' => 100,
				'dependency' => array( 'layout', 'any', 'slider,carousel,grid', true ),
			),
		),
	)
);

//
// Display Settings section.
//
SPFTESTIMONIAL::createSection(
	$prefix_shortcode_opts,
	array(
		'title'  => __( 'Display Settings', 'testimonial-pro' ),
		'icon'   => 'fa fa-th-large',
		'fields' => array(
			array(
				'type'  => 'tabbed',
				'class' => 'tabbed-inside-display-settings',
				'tabs'  => array(
					array(
						'title'  => __( 'Basic Preferences', 'testimonial-pro' ),
						'icon'   => '<i class="testimonial--icon sptpro-icon-basic-preferences"></i>',
						'fields' => array(
							array(
								'id'         => 'section_title',
								'type'       => 'switcher',
								'title'      => __( 'Section Title', 'testimonial-pro' ),
								'subtitle'   => __( 'Show/Hide testimonial section title (shortcode name).', 'testimonial-pro' ),
								'text_on'    => __( 'Show', 'testimonial-pro' ),
								'text_off'   => __( 'Hide', 'testimonial-pro' ),
								'text_width' => 80,
								'default'    => false,
							),
							array(
								'id'         => 'average_rating_top',
								'type'       => 'switcher',
								'title'      => __( 'Average Rating', 'testimonial-pro' ),
								'subtitle'   => __( 'Show/Hide average rating.', 'testimonial-pro' ),
								'title_help' => '<div class="spftestimonial-img-tag"><img src="' . SP_TPRO_URL . 'Admin/Views/Framework/assets/images/help-visuals/average_rating.svg" alt="Average Rating"></div><div class="spftestimonial-info-label">' . __( 'Average Rating', 'testimonial-pro' ) . '</div>',
								'text_on'    => __( 'Show', 'testimonial-pro' ),
								'text_off'   => __( 'Hide', 'testimonial-pro' ),
								'text_width' => 80,
								'default'    => false,
							),
							array(
								'id'          => 'average_rating_margin',
								'type'        => 'spacing',
								'title'       => __( 'Average Rating Margin', 'testimonial-pro' ),
								'subtitle'    => __( 'Set margin for average Rating.', 'testimonial-pro' ),
								'default'     => array(
									'top'    => '0',
									'right'  => '0',
									'bottom' => '25',
									'left'   => '0',
									'unit'   => 'px',
								),
								'top_text'    => __( 'Top', 'testimonial-pro' ),
								'right_text'  => __( 'Right', 'testimonial-pro' ),
								'bottom_text' => __( 'Bottom', 'testimonial-pro' ),
								'left_text'   => __( 'Left', 'testimonial-pro' ),
								'units'       => array( 'px' ),
								'dependency'  => array(
									'average_rating_top',
									'==',
									'true',
									true,
								),
							),
							array(
								'id'         => 'ajax_search',
								'type'       => 'switcher',
								'title'      => __( 'Ajax Testimonial Search', 'testimonial-pro' ),
								'subtitle'   => __( 'Enable/Disable ajax search for testimonial.', 'testimonial-pro' ),
								'title_help' => '<div class="spftestimonial-img-tag"><img src="' . SP_TPRO_URL . 'Admin/Views/Framework/assets/images/help-visuals/ajax_testimonial_search.svg" alt="Ajax Testimonial Search"></div><div class="spftestimonial-info-label">' . __( 'Ajax Testimonial Search', 'testimonial-pro' ) . '</div>',
								'text_on'    => __( 'Enabled', 'testimonial-pro' ),
								'text_off'   => __( 'Disabled', 'testimonial-pro' ),
								'text_width' => 100,
								'default'    => false,
								'dependency' => array( 'layout', '!=', 'thumbnail_slider', true ),
							),
							array(
								'id'          => 'testimonial_search_text',
								'type'        => 'text',
								'title'       => __( 'Search Label', 'testimonial-pro' ),
								'placeholder' => __( 'Search Testimonials', 'testimonial-pro' ),
								'subtitle'    => __( 'Change search label. Leave it empty to hide label.', 'testimonial-pro' ),
								'default'     => '',
								'dependency'  => array( 'ajax_search|layout', '==|!=', 'true|thumbnail_slider', true ),
							),
							array(
								'id'         => 'tpro_schema_markup',
								'type'       => 'switcher',
								'title'      => __( 'Schema Markup', 'testimonial-pro' ),
								'subtitle'   => __( 'Enable/Disable schema markup.', 'testimonial-pro' ),
								'title_help' => __( '<div class="spftestimonial-info-label schema-markup">Enable Schema.org Markup</div><div class="spftestimonial-short-content">Reviews Schema.org markup will let search engines read the reviews and overall ratings on your website and display them in search results. It will increase the attractiveness of your website snippet and, consequently, it will lead to a higher number of redirects from search engines.</div>', 'testimonial-pro' ),
								'text_on'    => __( 'Enabled', 'testimonial-pro' ),
								'text_off'   => __( 'Disabled', 'testimonial-pro' ),
								'text_width' => 100,
								'default'    => false,
							),
							array(
								'id'         => 'tpro_global_item_name',
								'type'       => 'text',
								'title'      => __( 'Item Global Name', 'testimonial-pro' ),
								'subtitle'   => __( 'Type item global name', 'testimonial-pro' ),
								'class'      => 'tpro-item-global-name',
								'title_help' => __( 'The item (company or product/service) name that is being reviewed or rated (not visible on your website, used for search engines). If nothing is set on the individual testimonial, this will be used as the item reviewed value for the testimonial. Let them know what your Testimonials are all about!', 'testimonial-pro' ),
								'dependency' => array( 'tpro_schema_markup', '==', 'true' ),
								'sanitize'   => 'sp_tpro_sanitize_text',
							),
							array(
								'id'         => 'preloader',
								'type'       => 'switcher',
								'title'      => __( 'Preloader', 'testimonial-pro' ),
								'subtitle'   => __( 'Enable/Disable preloader.', 'testimonial-pro' ),
								'text_on'    => __( 'Enabled', 'testimonial-pro' ),
								'text_off'   => __( 'Disabled', 'testimonial-pro' ),
								'text_width' => 100,
								'default'    => false,
							),
						),
					),
					array(
						'title'  => __( 'Testimonial Content', 'testimonial-pro' ),
						'icon'   => '<i class="testimonial--icon sptpro-icon-testimonial-content"></i>',
						'fields' => array(
							array(
								'id'         => 'testimonial_title',
								'type'       => 'switcher',
								'title'      => __( 'Testimonial Title', 'testimonial-pro' ),
								'subtitle'   => __( 'Show/Hide testimonial tagline or title.', 'testimonial-pro' ),
								'text_on'    => __( 'Show', 'testimonial-pro' ),
								'text_off'   => __( 'Hide', 'testimonial-pro' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'         => 'testimonial_title_length',
								'type'       => 'fieldset',
								'title'      => 'Length',
								'class'      => 'testimonial_text_limit',
								'subtitle'   => __( 'Set testimonial title length. Leave it empty to show full title.', 'testimonial-pro' ),
								'fields'     => array(
									array(
										'id'         => 'testimonial_title_limit',
										'type'       => 'spinner',
										'dependency' => array( 'testimonial_title_length_type', '==', 'characters', true ),
									),
									array(
										'id'         => 'testimonial_title_word_limit',
										'type'       => 'spinner',
										'dependency' => array( 'testimonial_title_length_type', '==', 'words', true ),
									),
									array(
										'id'      => 'testimonial_title_length_type',
										'type'    => 'select',
										'options' => array(
											'characters' => __( 'Characters', 'testimonial-pro' ),
											'words'      => __( 'Words', 'testimonial-pro' ),
										),
										'default' => 'characters',
									),
								),
								'dependency' => array( 'testimonial_title', '==', 'true', true ),
							),
							array(
								'id'         => 'testimonial_title_tag',
								'type'       => 'select',
								'title'      => __( 'HTML Tag', 'testimonial-pro' ),
								'subtitle'   => __( 'Select testimonial title HTML tag.', 'testimonial-pro' ),
								'options'    => array(
									'h1'   => 'h1',
									'h2'   => 'h2',
									'h3'   => 'h3',
									'h4'   => 'h4',
									'h5'   => 'h5',
									'h6'   => 'h6',
									'p'    => 'p',
									'span' => 'span',
									'div'  => 'div',
								),
								'default'    => 'h3',
								'dependency' => array(
									'testimonial_title',
									'==',
									'true',
									true,
								),
							),
							array(
								'id'         => 'testimonial_title_quote_symbol',
								'type'       => 'switcher',
								'title'      => __( 'Add a Quote Symbol', 'testimonial-pro' ),
								'subtitle'   => __( 'Add a quote symbol before testimonial title.', 'testimonial-pro' ),
								'default'    => false,
								'text_on'    => __( 'Show', 'testimonial-pro' ),
								'text_off'   => __( 'Hide', 'testimonial-pro' ),
								'text_width' => 80,
							),
							array(
								'id'         => 'quote_symbol_color',
								'type'       => 'color',
								'title'      => __( 'Quote Symbol Color', 'testimonial-pro' ),
								'subtitle'   => __( 'Set a color for quote symbol.', 'testimonial-pro' ),
								'default'    => '#ffffff',
								'dependency' => array( 'testimonial_title_quote_symbol', '==', 'true', true ),
							),
							array(
								'id'         => 'testimonial_text',
								'type'       => 'switcher',
								'title'      => __( 'Testimonial Content', 'testimonial-pro' ),
								'subtitle'   => __( 'Show/Hide testimonial content.', 'testimonial-pro' ),
								'text_on'    => __( 'Show', 'testimonial-pro' ),
								'text_off'   => __( 'Hide', 'testimonial-pro' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'         => 'testimonial_content_type',
								'type'       => 'button_set',
								'title'      => __( 'Content Display Type', 'testimonial-pro' ),
								'subtitle'   => __( 'Choose content display type.', 'testimonial-pro' ),
								'options'    => array(
									'full_content'       => __( 'Full', 'testimonial-pro' ),
									'content_with_limit' => __( 'Limit', 'testimonial-pro' ),
								),
								'default'    => 'content_with_limit',
								'dependency' => array(
									'testimonial_text',
									'==',
									'true',
									true,
								),
							),
							array(
								'id'         => 'testimonial_content_length',
								'type'       => 'fieldset',
								'class'      => 'testimonial_text_limit',
								'title'      => 'Length',
								'subtitle'   => __( 'Set testimonial content length.', 'testimonial-pro' ),
								'fields'     => array(
									array(
										'id'         => 'testimonial_characters_limit',
										'type'       => 'spinner',
										'default'    => '300',
										'dependency' => array( 'testimonial_content_length_type', '==', 'characters', true ),
									),
									array(
										'id'         => 'testimonial_word_limit',
										'type'       => 'spinner',
										'unit'       => __( 'word', 'testimonial-pro' ),
										'default'    => '300',
										'dependency' => array( 'testimonial_content_length_type', '==', 'words', true ),
									),
									array(
										'id'      => 'testimonial_content_length_type',
										'type'    => 'select',
										'options' => array(
											'characters' => __( 'Characters', 'testimonial-pro' ),
											'words'      => __( 'Words', 'testimonial-pro' ),
										),
										'default' => 'characters',
									),
								),
								'dependency' => array(
									'testimonial_text|testimonial_content_type',
									'==|==',
									'true|content_with_limit',
									true,
								),
							),
							array(
								'id'         => 'testimonial_read_more_ellipsis',
								'type'       => 'text',
								'title'      => __( 'Ellipsis', 'testimonial-pro' ),
								'subtitle'   => __( 'Type ellipsis.', 'testimonial-pro' ),
								'default'    => '...',
								'dependency' => array(
									'testimonial_text|testimonial_content_type',
									'==|==',
									'true|content_with_limit',
									true,
								),
								'sanitize'   => 'sp_tpro_sanitize_text',
							),
							array(
								'id'         => 'testimonial_strip_tags',
								'type'       => 'checkbox',
								'title'      => __( 'Strip All HTML Tags', 'testimonial-pro' ),
								'title_help' => __( 'Check to strip all HTML tags from testimonial content including script and style.', 'testimonial-pro' ),
								'default'    => true,
								'dependency' => array(
									'testimonial_text',
									'==',
									'true',
									true,
								),
							),
							array(
								'id'         => 'testimonial_read_more',
								'type'       => 'switcher',
								'title'      => __( 'Read More', 'testimonial-pro' ),
								'subtitle'   => __( 'Show/Hide testimonial read more button.', 'testimonial-pro' ),
								'text_on'    => __( 'Show', 'testimonial-pro' ),
								'text_off'   => __( 'Hide', 'testimonial-pro' ),
								'text_width' => 80,
								'default'    => false,
							),
							array(
								'id'         => 'testimonial_read_more_link_action',
								'type'       => 'button_set',
								'title'      => __( 'Read More Action Type', 'testimonial-pro' ),
								'subtitle'   => __( 'Select read more link action type.', 'testimonial-pro' ),
								'options'    => array(
									'expand' => __( 'Expand', 'testimonial-pro' ),
									'popup'  => __( 'Popup', 'testimonial-pro' ),
								),
								'default'    => 'expand',
								'dependency' => array(
									'testimonial_text|testimonial_read_more|testimonial_content_type',
									'==|==|==',
									'true|true|content_with_limit',
									true,
								),
							),
							array(
								'id'         => 'popup_background',
								'type'       => 'color',
								'title'      => __( 'Popup Background', 'testimonial-pro' ),
								'subtitle'   => __( 'Set popup background color.', 'testimonial-pro' ),
								'default'    => '#ffffff',
								'dependency' => array(
									'testimonial_read_more|testimonial_read_more_link_action',
									'==|==',
									'true|popup',
									true,
								),
							),
							array(
								'id'         => 'testimonial_read_more_text',
								'type'       => 'text',
								'title'      => __( 'Read More Label', 'testimonial-pro' ),
								'subtitle'   => __( 'Type read more label.', 'testimonial-pro' ),
								'default'    => 'Read More',
								'dependency' => array(
									'testimonial_read_more',
									'==',
									'true',
									true,
								),
								'sanitize'   => 'sp_tpro_sanitize_text',
							),
							array(
								'id'         => 'testimonial_read_less_text',
								'type'       => 'text',
								'title'      => __( 'Read Less Label', 'testimonial-pro' ),
								'subtitle'   => __( 'Type read less label.', 'testimonial-pro' ),
								'default'    => 'Read Less',
								'dependency' => array(
									'testimonial_text|testimonial_read_more|testimonial_read_more_link_action|testimonial_content_type',
									'==|==|==|==',
									'true|true|expand|content_with_limit',
									true,
								),
								'sanitize'   => 'sp_tpro_sanitize_text',
							),
							array(
								'id'         => 'testimonial_readmore_color',
								'type'       => 'color_group',
								'title'      => __( 'Read More Color', 'testimonial-pro' ),
								'subtitle'   => __( 'Set read more color.', 'testimonial-pro' ),
								'options'    => array(
									'color'       => __( 'Color', 'testimonial-pro' ),
									'hover-color' => __( 'Hover Color', 'testimonial-pro' ),
								),
								'default'    => array(
									'color'       => '#1595CE',
									'hover-color' => '#2684a6',
								),
								'dependency' => array(
									'testimonial_read_more',
									'==',
									'true',
									true,
								),
							),

						),
					),
					array(
						'title'  => __( 'Reviewer Information', 'testimonial-pro' ),
						'icon'   => '<i class="testimonial--icon sptpro-icon-reviewer-info"></i>',
						'fields' => array(
							array(
								'id'         => 'testimonial_client_name',
								'type'       => 'switcher',
								'title'      => __( 'Full Name', 'testimonial-pro' ),
								'subtitle'   => __( 'Show/Hide reviewer full name.', 'testimonial-pro' ),
								'text_on'    => __( 'Show', 'testimonial-pro' ),
								'text_off'   => __( 'Hide', 'testimonial-pro' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'         => 'testimonial_client_name_tag',
								'type'       => 'select',
								'title'      => __( 'HTML Tag', 'testimonial-pro' ),
								'subtitle'   => __( 'Select a HTML tag for reviewer full name.', 'testimonial-pro' ),
								'options'    => array(
									'h1'   => 'h1',
									'h2'   => 'h2',
									'h3'   => 'h3',
									'h4'   => 'h4',
									'h5'   => 'h5',
									'h6'   => 'h6',
									'p'    => 'p',
									'span' => 'span',
									'div'  => 'div',
								),
								'default'    => 'h4',
								'dependency' => array(
									'testimonial_client_name',
									'==',
									'true',
									true,
								),
							),
							array(
								'id'         => 'client_designation',
								'type'       => 'switcher',
								'title'      => __( 'Designation', 'testimonial-pro' ),
								'subtitle'   => __( 'Show/Hide designation.', 'testimonial-pro' ),
								'text_on'    => __( 'Show', 'testimonial-pro' ),
								'text_off'   => __( 'Hide', 'testimonial-pro' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'         => 'client_company_name',
								'type'       => 'switcher',
								'title'      => __( 'Company Name', 'testimonial-pro' ),
								'subtitle'   => __( 'Show/Hide company name.', 'testimonial-pro' ),
								'text_on'    => __( 'Show', 'testimonial-pro' ),
								'text_off'   => __( 'Hide', 'testimonial-pro' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'         => 'client_company_logo',
								'type'       => 'switcher',
								'title'      => __( 'Company Logo', 'testimonial-pro' ),
								'subtitle'   => __( 'Show/Hide company Logo.', 'testimonial-pro' ),
								'text_on'    => __( 'Show', 'testimonial-pro' ),
								'text_off'   => __( 'Hide', 'testimonial-pro' ),
								'text_width' => 80,
								'default'    => false,
							),
							array(
								'id'         => 'company_logo_custom_color',
								'type'       => 'logo_color',
								'title'      => __( 'Logo Custom Color', 'testimonial-pro' ),
								'subtitle'   => __( 'Set custom color for the company logo.', 'testimonial-pro' ),
								'title_help' => __( 'Custom color applied only to PNG files.', 'testimonial-pro' ),
								'options'    => array(
									'color'       => __( 'Color', 'testimonial-pro' ),
									'color_hover' => __( 'Hover', 'testimonial-pro' ),
								),
								'default'    => array(
									'color'       => 'transparent',
									'color_hover' => 'transparent',
								),
								'dependency' => array(
									'client_company_logo',
									'==',
									'true',
									true,
								),
							),
							array(
								'id'         => 'company_image_sizes',
								'type'       => 'image_sizes',
								'title'      => __( 'Dimensions', 'testimonial-pro' ),
								'subtitle'   => __( 'Select a size (width & height) for the company logo.', 'testimonial-pro' ),
								'default'    => 'thumbnail',
								'dependency' => array(
									'client_company_logo',
									'==',
									'true',
									true,
								),
							),
							array(
								'id'         => 'company_image_custom_size',
								'type'       => 'custom_size',
								'title'      => __( 'Custom Size', 'testimonial-pro' ),
								'subtitle'   => __( 'Set a custom width and height of the company logo image.', 'testimonial-pro' ),
								'default'    => array(
									'width'  => '120',
									'height' => '120',
									'crop'   => 'hard-crop',
									'unit'   => 'px',
								),
								'attributes' => array(
									'min' => 0,
								),
								'dependency' => array(
									'client_company_logo|company_image_sizes',
									'==|==',
									'true|custom',
									true,
								),
							),
							array(
								'id'         => 'testimonial_client_location',
								'type'       => 'switcher',
								'title'      => __( 'Location', 'testimonial-pro' ),
								'subtitle'   => __( 'Show/Hide Reviewer location.', 'testimonial-pro' ),
								'text_on'    => __( 'Show', 'testimonial-pro' ),
								'text_off'   => __( 'Hide', 'testimonial-pro' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'         => 'testimonial_client_location_country',
								'type'       => 'select',
								'title'      => __( 'Country', 'testimonial-pro' ),
								'subtitle'   => __( 'Choose a country display type.', 'testimonial-pro' ),
								'options'    => array(
									'name'              => __( 'Country Name', 'testimonial-pro' ),
									'country_with_flag' => __( 'Country Name with Flag', 'testimonial-pro' ),
									'flag'              => __( 'Only Flag', 'testimonial-pro' ),
									'none'              => __( 'None', 'testimonial-pro' ),
								),
								'title_help' => 'E.g: Country Name - Bangladesh; Country Name with Flag - Bangladesh 🇧🇩; Only Flag - 🇧🇩;',
								'default'    => 'name',
								'dependency' => array(
									'testimonial_client_location',
									'==',
									'true',
									true,
								),
							),
							array(
								'id'         => 'before_country_flag',
								'type'       => 'checkbox',
								'title'      => __( 'Flag Before Country Name', 'testimonial-pro' ),
								'default'    => false,
								'dependency' => array(
									'testimonial_client_location|testimonial_client_location_country',
									'==|==',
									'true|country_with_flag',
									true,
								),
							),
							array(
								'id'         => 'testimonial_client_phone',
								'type'       => 'switcher',
								'title'      => __( 'Phone or Mobile', 'testimonial-pro' ),
								'subtitle'   => __( 'Show/Hide phone or mobile number.', 'testimonial-pro' ),
								'text_on'    => __( 'Show', 'testimonial-pro' ),
								'text_off'   => __( 'Hide', 'testimonial-pro' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'         => 'testimonial_client_email',
								'type'       => 'switcher',
								'title'      => __( 'E-mail Address', 'testimonial-pro' ),
								'subtitle'   => __( 'Show/Hide e-mail address.', 'testimonial-pro' ),
								'text_on'    => __( 'Show', 'testimonial-pro' ),
								'text_off'   => __( 'Hide', 'testimonial-pro' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'         => 'testimonial_client_date',
								'type'       => 'switcher',
								'title'      => __( 'Date', 'testimonial-pro' ),
								'subtitle'   => __( 'Show/Hide testimonial date.', 'testimonial-pro' ),
								'text_on'    => __( 'Show', 'testimonial-pro' ),
								'text_off'   => __( 'Hide', 'testimonial-pro' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'         => 'testimonial_date_format_type',
								'type'       => 'select',
								'title'      => __( 'Date Format', 'testimonial-pro' ),
								'subtitle'   => __( 'Set date format.', 'testimonial-pro' ),
								'class'      => 'post_meta_date_format',
								'options'    => array(
									'default'  => __( 'Default', 'testimonial-pro' ),
									'time_ago' => __( 'Time ago (human time)', 'testimonial-pro' ),
									'custom'   => __( 'Custom', 'testimonial-pro' ),
								),
								'default'    => 'default',
								'dependency' => array( 'testimonial_client_date', '==', 'true', true ),
							),
							array(
								'id'         => 'testimonial_date_format',
								'type'       => 'select',
								'title'      => ' ',
								'options'    => array(
									'M j, Y' => date_i18n( 'M j, Y' ) . ' (M j, Y)',
									'F j, Y' => date_i18n( 'F j, Y' ) . ' (F j, Y)',
									'Y-m-d'  => date_i18n( 'Y-m-d' ) . ' (Y-m-d)',
									'm/d/Y'  => date_i18n( 'm/d/Y' ) . ' (m/d/Y)',
									'd/m/Y'  => date_i18n( 'd/m/Y' ) . ' (d/m/Y)',
								),
								'default'    => 'M j, Y',
								'dependency' => array(
									'testimonial_client_date|testimonial_date_format_type',
									'==|==',
									'true|default',
									true,
								),
							),
							array(
								'id'         => 'testimonial_client_date_format',
								'class'      => 'testimonial_client_date_format',
								'type'       => 'text',
								'title'      => ' ',
								'default'    => 'M j, Y',
								'after'      => '<a target="_blank" href="https://wordpress.org/support/article/formatting-date-and-time/">Documentation on date formatting.</a>',
								'dependency' => array(
									'testimonial_client_date|testimonial_date_format_type',
									'==|==',
									'true|custom',
									true,
								),
							),
							array(
								'id'         => 'testimonial_client_website',
								'type'       => 'switcher',
								'title'      => __( 'Website', 'testimonial-pro' ),
								'subtitle'   => __( 'Show/Hide website.', 'testimonial-pro' ),
								'text_on'    => __( 'Show', 'testimonial-pro' ),
								'text_off'   => __( 'Hide', 'testimonial-pro' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'         => 'identity_linking_website',
								'type'       => 'checkbox',
								'title'      => __( 'Designation & Company Linking via Website URL', 'testimonial-pro' ),
								'title_help' => __( 'Check to link designation & company name via website URL.', 'testimonial-pro' ),
								'default'    => false,
							),
							array(
								'id'       => 'website_link_target',
								'type'     => 'select',
								'title'    => __( 'Link Target', 'testimonial-pro' ),
								'subtitle' => __( 'Set a target to open the website URL.', 'testimonial-pro' ),
								'options'  => array(
									'_blank' => __( 'New Tab', 'testimonial-pro' ),
									'_self'  => __( 'Same Tab', 'testimonial-pro' ),
								),
								'default'  => '_blank',
							),
							array(
								'id'         => 'testimonial_client_addition_info',
								'type'       => 'switcher',
								'title'      => __( 'Additional Custom Fields', 'testimonial-pro' ),
								'subtitle'   => __( 'Show/Hide additional custom fields.', 'testimonial-pro' ),
								'text_on'    => __( 'Show', 'testimonial-pro' ),
								'text_off'   => __( 'Hide', 'testimonial-pro' ),
								'text_width' => 80,
								'default'    => false,
							),
						),
					),
					array(
						'title'  => __( 'Star Rating', 'testimonial-pro' ),
						'icon'   => '<i class="testimonial--icon fa fa-star-o"></i>',
						'fields' => array(
							array(
								'id'         => 'testimonial_client_rating',
								'type'       => 'switcher',
								'title'      => __( 'Star Rating', 'testimonial-pro' ),
								'subtitle'   => __( 'Show/Hide star rating.', 'testimonial-pro' ),
								'text_on'    => __( 'Show', 'testimonial-pro' ),
								'text_off'   => __( 'Hide', 'testimonial-pro' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'         => 'tpro_star_icon',
								'type'       => 'image_select',
								'title'      => __( 'Rating Icon Style', 'testimonial-pro' ),
								'subtitle'   => __( 'Choose a star rating icon style.', 'testimonial-pro' ),
								'options'    => array(
									'rating-star-1'  => array(
										'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/rating-icon/rating-star-1.svg',
									),
									'rating-star-2'  => array(
										'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/rating-icon/rating-star-2.svg',
									),
									'rating-star-3'  => array(
										'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/rating-icon/rating-star-3.svg',
									),
									'rating-star-4'  => array(
										'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/rating-icon/rating-star-4.svg',
									),
									'rating-star-5'  => array(
										'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/rating-icon/rating-star-5.svg',
									),
									'rating-star-6'  => array(
										'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/rating-icon/rating-star-6.svg',
									),
									'rating-star-6b' => array(
										'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/rating-icon/rating-star-6b.svg',
									),
									'rating-star-7'  => array(
										'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/rating-icon/rating-star-7.svg',
									),
									'rating-star-7b' => array(
										'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/rating-icon/rating-star-7b.svg',
									),
									'rating-star-8'  => array(
										'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/rating-icon/rating-star-8.svg',
									),
									'rating-star-9'  => array(
										'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/rating-icon/rating-star-9.svg',
									),
									'rating-star-10' => array(
										'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/rating-icon/rating-star-10.svg',
									),
									'rating-star-11' => array(
										'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/rating-icon/rating-star-11.svg',
									),
									'rating-star-12' => array(
										'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/rating-icon/rating-star-12.svg',
									),
									'rating-star-13' => array(
										'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/rating-icon/rating-star-13.svg',
									),
								),
								'default'    => 'rating-star-1',
								'dependency' => array( 'testimonial_client_rating', '==', 'true', true ),
							),
							array(
								'id'         => 'testimonial_client_rating_color',
								'type'       => 'color_group',
								'title'      => __( 'Rating Color', 'testimonial-pro' ),
								'subtitle'   => __( 'Set color for the rating.', 'testimonial-pro' ),
								'options'    => array(
									'color'       => __( 'Default', 'testimonial-pro' ),
									'hover-color' => __( ' Rating', 'testimonial-pro' ),
								),
								'default'    => array(
									'color'       => '#bbc2c7',
									'hover-color' => '#ffb900',
								),
								'dependency' => array( 'testimonial_client_rating', '==', 'true', true ),
							),
							array(
								'id'         => 'rating_icon_size',
								'type'       => 'spacing',
								'class'      => 'border_radius_by_spacing',
								'title'      => __( 'Rating Icon Size', 'testimonial-pro' ),
								'subtitle'   => __( 'Set a size for the rating icon.', 'testimonial-pro' ),
								'all_text'   => '',
								'all'        => true,
								'units'      => array( 'px' ),
								'default'    => array(
									'all'  => '19',
									'unit' => 'px',
								),
								'dependency' => array(
									'client_image|testimonial_client_rating',
									'==|==',
									'true|true',
									true,
								),
							),
							array(
								'id'         => 'rating_icon_gap',
								'type'       => 'spacing',
								'class'      => 'border_radius_by_spacing',
								'title'      => __( 'Gap', 'testimonial-pro' ),
								'subtitle'   => __( 'Set a gap between the rating icons.', 'testimonial-pro' ),
								'all_text'   => '',
								'all_icon'   => '<i class="fa fa-arrows-h"></i>',
								'all'        => true,
								'units'      => array( 'px' ),
								'default'    => array(
									'all'  => '2',
									'unit' => 'px',
								),
								'dependency' => array(
									'client_image|testimonial_client_rating',
									'==|==',
									'true|true',
									true,
								),
							),
							array(
								'id'         => 'rating_star_position',
								'type'       => 'select',
								'title'      => __( 'Rating Position', 'testimonial-pro' ),
								'subtitle'   => __( 'Select a position for the star rating.', 'testimonial-pro' ),
								'options'    => array(
									'below_name'           => __( 'Below Reviewer Name', 'testimonial-pro' ),
									'below_reviewer_designation' => __( 'Below Reviewer Designation', 'testimonial-pro' ),
									'above_title'          => __( 'Above Testimonial Title', 'testimonial-pro' ),
									'below_title'          => __( 'Below Testimonial Title', 'testimonial-pro' ),
									'below_content'        => __( 'Below Testimonial Content', 'testimonial-pro' ),
									'below_reviewer_image' => __( 'Below Reviewer Image', 'testimonial-pro' ),
								),
								'default'    => 'below_name',
								'dependency' => array( 'testimonial_client_rating', '==', 'true', true ),
							),
							array(
								'id'         => 'testimonial_client_rating_alignment',
								'type'       => 'button_set',
								'class'      => 'button_set_smaller',
								'title'      => __( 'Rating Alignment', 'testimonial-pro' ),
								'subtitle'   => __( 'Set alignment for the rating.', 'testimonial-pro' ),
								'options'    => array(
									'left'   => '<i class="fa fa-align-left" title="left"></i>',
									'center' => '<i class="fa fa-align-center" title="center"></i>',
									'right'  => '<i class="fa fa-align-right" title="right"></i>',
								),
								'default'    => 'center',
								'dependency' => array(
									'testimonial_client_rating',
									'==',
									'true',
									true,
								),
							),
							array(
								'id'          => 'testimonial_client_rating_margin',
								'type'        => 'spacing',
								'title'       => __( 'Rating Margin', 'testimonial-pro' ),
								'subtitle'    => __( 'Set margin for the rating.', 'testimonial-pro' ),
								'title_help'  => '<div class="spftestimonial-img-tag"><img src="' . SP_TPRO_URL . 'Admin/Views/Framework/assets/images/help-visuals/rating_margin.svg" alt="Rating Margin"></div><div class="spftestimonial-info-label">' . __( 'Rating Margin', 'testimonial-pro' ) . '</div>',
								'default'     => array(
									'top'    => '0',
									'right'  => '0',
									'bottom' => '6',
									'left'   => '0',
								),
								'top_text'    => __( 'Top', 'testimonial-pro' ),
								'right_text'  => __( 'Right', 'testimonial-pro' ),
								'bottom_text' => __( 'Bottom', 'testimonial-pro' ),
								'left_text'   => __( 'Left', 'testimonial-pro' ),
								'units'       => array( 'px' ),
								'dependency'  => array(
									'testimonial_client_rating',
									'==',
									'true',
									true,
								),
							),
						),
					),
					array(
						'title'  => __( 'Reviewer Image', 'testimonial-pro' ),
						'icon'   => '<i class="testimonial--icon sptpro-icon-reviewer-image"></i>',
						'fields' => array(
							array(
								'id'         => 'client_image',
								'type'       => 'switcher',
								'title'      => __( 'Reviewer Image ', 'testimonial-pro' ),
								'subtitle'   => __( 'Show/Hide reviewer image.', 'testimonial-pro' ),
								'text_on'    => __( 'Show', 'testimonial-pro' ),
								'text_off'   => __( 'Hide', 'testimonial-pro' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'         => 'image_sizes',
								'type'       => 'image_sizes',
								'title'      => __( 'Dimensions', 'testimonial-pro' ),
								'subtitle'   => __( 'Select dimension for reviewer image.', 'testimonial-pro' ),
								'default'    => 'custom',
								'dependency' => array(
									'client_image',
									'==',
									'true',
									true,
								),
							),
							array(
								'id'         => 'image_custom_size',
								'type'       => 'custom_size',
								'title'      => __( 'Custom Dimensions', 'testimonial-pro' ),
								'subtitle'   => __( 'Set a custom width and height for the reviewer image.', 'testimonial-pro' ),
								'default'    => array(
									'width'  => '120',
									'height' => '120',
									'crop'   => 'hard-crop',
									'unit'   => 'px',
								),
								'attributes' => array(
									'min' => 0,
								),
								'dependency' => array(
									'client_image|image_sizes',
									'==|==',
									'true|custom',
									true,
								),
							),
							array(
								'id'         => 'load_2x_image',
								'type'       => 'switcher',
								'title'      => __( 'Load 2x Resolution Image in Retina Display', 'testimonial-pro' ),
								'subtitle'   => __( 'You should upload 2x sized images for the retina display.', 'testimonial-pro' ),
								'text_on'    => __( 'Enabled', 'testimonial-pro' ),
								'text_off'   => __( 'Disabled', 'testimonial-pro' ),
								'text_width' => 94,
								'default'    => false,
								'dependency' => array( 'client_image|image_sizes', '==|==', 'true|custom', true ),
							),
							array(
								'id'         => 'client_image_style',
								'class'      => 'client_image_style',
								'type'       => 'image_select',
								'title'      => __( 'Image Shape', 'testimonial-pro' ),
								'subtitle'   => __( 'Choose a reviewer image shape.', 'testimonial-pro' ),
								'options'    => array(
									'three' => array(
										'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/image_shape/circle.svg',
										'name'  => __( 'Circle', 'testimonial-pro' ),
									),
									'two'   => array(
										'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/image_shape/rounded.svg',
										'name'  => __( 'Rounded', 'testimonial-pro' ),
									),
									'one'   => array(
										'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/image_shape/square.svg',
										'name'  => __( 'Square', 'testimonial-pro' ),
									),
								),
								'default'    => 'three',
								'dependency' => array(
									'client_image',
									'==',
									'true',
									true,
								),
							),
							array(
								'id'       => 'reviewer_fallback_image',
								'type'     => 'radio',
								'title'    => __( 'Reviewer Fallback Images', 'testimonial-pro' ),
								'subtitle' => __( 'If no Featured Image is set, a reviewer fallback image can be used.', 'testimonial-pro' ),
								'options'  => array(
									'mystery_person'  => __( 'Mystery Person', 'testimonial-pro' ),
									'text_avatar'     => __( 'Smart Text Avatars', 'testimonial-pro' ),
									'no_fallback_img' => __( 'No Fallback Image', 'testimonial-pro' ),
								),
								'default'  => 'mystery_person',
							),
							array(
								'id'         => 'border_radius_on_rounded_image',
								'type'       => 'spacing',
								'class'      => 'border_radius_by_spacing',
								'title'      => __( 'Radius', 'testimonial-pro' ),
								'all'        => true,
								'units'      => array(
									'px',
									'%',
								),
								'default'    => array(
									'all'  => '5',
									'unit' => 'px',
								),
								'dependency' => array( 'client_image|client_image_style', '==|==', 'true|two', true ),
							),
							array(
								'id'         => 'client_image_bg',
								'type'       => 'color',
								'title'      => __( 'Image Background', 'testimonial-pro' ),
								'subtitle'   => __( 'Set reviewer image background color.', 'testimonial-pro' ),
								'default'    => '#ffffff',
								'dependency' => array( 'client_image', '==', 'true', true ),
							),
							array(
								'id'         => 'image_padding',
								'type'       => 'spacing',
								'class'      => 'border_radius_by_spacing',
								'title'      => __( 'Padding', 'testimonial-pro' ),
								'subtitle'   => __( 'Set padding for reviewer image.', 'testimonial-pro' ),
								'title_help' => '<div class="spftestimonial-img-tag"><img src="' . SP_TPRO_URL . 'Admin/Views/Framework/assets/images/help-visuals/image_padding.svg" alt="Padding"></div><div class="spftestimonial-info-label">' . __( 'Padding', 'testimonial-pro' ) . '</div>',
								'all_text'   => '',
								'all'        => true,
								'units'      => array( 'px' ),
								'default'    => array(
									'all'  => '0',
									'unit' => 'px',
								),
								'dependency' => array(
									'client_image',
									'==',
									'true',
									true,
								),
							),
							array(
								'id'         => 'image_border',
								'type'       => 'border',
								'title'      => __( 'Border', 'testimonial-pro' ),
								'subtitle'   => __( 'Set reviewer image border.', 'testimonial-pro' ),
								'all'        => true,
								'default'    => array(
									'all'   => '0',
									'style' => 'solid',
									'color' => '#dddddd',
									'unit'  => 'px',
								),
								'dependency' => array( 'client_image', '==', 'true', true ),
							),
							array(
								'id'       => 'client_image_border_shadow',
								'type'     => 'button_set',
								'title'    => __( 'BoxShadow', 'testimonial-pro' ),
								'subtitle' => __( 'Set boxshadow for the reviewer image.', 'testimonial-pro' ),
								'options'  => array(
									'shadow_inset'  => __( 'Inset', 'testimonial-pro' ),
									'shadow_outset' => __( 'Outset', 'testimonial-pro' ),
									'none'          => __( 'None', 'testimonial-pro' ),
								),
								'default'  => 'none',
							),
							array(
								'id'         => 'client_image_box_shadow_property',
								'type'       => 'box_shadow',
								'title'      => __( 'Box-Shadow Values', 'testimonial-pro' ),
								'subtitle'   => __( 'Set reviewer image box-shadow property values.', 'testimonial-pro' ),
								'default'    => array(
									'horizontal' => '0',
									'vertical'   => '0',
									'blur'       => '7',
									'spread'     => '0',
									'color'      => '#888888',
								),
								'dependency' => array(
									'client_image|client_image_border_shadow',
									'==|!=',
									'true|none',
									true,
								),
							),
							array(
								'id'         => 'img_lightbox',
								'type'       => 'switcher',
								'title'      => __( 'Lightbox ', 'testimonial-pro' ),
								'subtitle'   => __( 'Enable/Disable reviewer image lightbox.', 'testimonial-pro' ),
								'text_on'    => __( 'Enabled', 'testimonial-pro' ),
								'text_off'   => __( 'Disabled', 'testimonial-pro' ),
								'text_width' => 100,
								'default'    => false,
								'dependency' => array(
									'client_image|layout',
									'==|!=',
									'true|thumbnail_slider',
									true,
								),
							),
							array(
								'id'         => 'image_zoom_effect',
								'type'       => 'select',
								'title'      => __( 'Zoom Effect', 'testimonial-pro' ),
								'subtitle'   => __( 'Set a zoom effect on hover for the reviewer image.', 'testimonial-pro' ),
								'options'    => array(
									''         => __( 'Normal', 'testimonial-pro' ),
									'zoom_in'  => __( 'Zoom In', 'testimonial-pro' ),
									'zoom_out' => __( 'Zoom Out', 'testimonial-pro' ),
								),
								'default'    => '',
								'class'      => 'chosen',
								'dependency' => array(
									'client_image|layout',
									'==|!=',
									'true|thumbnail_slider',
									true,
								),
							),
							array(
								'id'         => 'image_grayscale',
								'type'       => 'select',
								'title'      => __( 'Image Mode', 'testimonial-pro' ),
								'subtitle'   => __( 'Select a reviewer image mode.', 'testimonial-pro' ),
								'options'    => array(
									'none'            => __( 'Original', 'testimonial-pro' ),
									'normal_on_hover' => __( 'Grayscale and original on hover', 'testimonial-pro' ),
									'on_hover'        => __( 'Grayscale on hover', 'testimonial-pro' ),
									'always'          => __( 'Always grayscale', 'testimonial-pro' ),
								),
								'default'    => 'none',
								'dependency' => array(
									'client_image|layout',
									'==|!=',
									'true|thumbnail_slider',
									true,
								),
							),
							array(
								'id'          => 'client_image_margin',
								'type'        => 'spacing',
								'title'       => __( 'Margin', 'testimonial-pro' ),
								'subtitle'    => __( 'Set margin for the reviewer image.', 'testimonial-pro' ),
								'default'     => array(
									'top'    => '0',
									'right'  => '0',
									'bottom' => '22',
									'left'   => '0',
								),
								'top_text'    => __( 'Top', 'testimonial-pro' ),
								'right_text'  => __( 'Right', 'testimonial-pro' ),
								'bottom_text' => __( 'Bottom', 'testimonial-pro' ),
								'left_text'   => __( 'Left', 'testimonial-pro' ),
								'units'       => array( 'px' ),
								'dependency'  => array(
									'client_image|theme_style',
									'==|any',
									'true|theme-one,theme-eight,theme-ten',
									true,
								),
							),
							array(
								'id'          => 'client_image_margin_tow',
								'type'        => 'spacing',
								'title'       => __( 'Margin', 'testimonial-pro' ),
								'subtitle'    => __( 'Set margin for the reviewer image.', 'testimonial-pro' ),
								'default'     => array(
									'top'    => '0',
									'right'  => '22',
									'bottom' => '0',
									'left'   => '0',
									'unit'   => 'px',
								),
								'top_text'    => __( 'Top', 'testimonial-pro' ),
								'right_text'  => __( 'Right', 'testimonial-pro' ),
								'bottom_text' => __( 'Bottom', 'testimonial-pro' ),
								'left_text'   => __( 'Left', 'testimonial-pro' ),
								'dependency'  => array(
									'client_image|theme_style',
									'==|==',
									'true|theme-nine',
									true,
								),
							),
						),
					),
					array(
						'title'  => __( 'Video Testimonial', 'testimonial-pro' ),
						'icon'   => '<i class="testimonial--icon sptpro-icon-video-testimonial"></i>',
						'fields' => array(
							array(
								'id'         => 'video_icon',
								'type'       => 'switcher',
								'title'      => __( 'Video Testimonial', 'testimonial-pro' ),
								'subtitle'   => __( 'Show/Hide video testimonial.', 'testimonial-pro' ),
								'text_on'    => __( 'Show', 'testimonial-pro' ),
								'text_off'   => __( 'Hide', 'testimonial-pro' ),
								'text_width' => 80,
								'default'    => true,
								'dependency' => array(
									'client_image|layout',
									'==|!=',
									'true|thumbnail_slider',
									true,
								),
							),
							array(
								'id'         => 'video_play_place',
								'type'       => 'button_set',
								'title'      => __( 'Video Play Mode', 'testimonial-pro' ),
								'subtitle'   => __( 'Choose a play mode for the video testimonial.', 'testimonial-pro' ),
								'options'    => array(
									'default' => __( 'Inline', 'testimonial-pro' ),
									'popup'   => __( 'Popup', 'testimonial-pro' ),
								),
								'default'    => 'popup',
								'dependency' => array(
									'client_image|layout|video_icon',
									'==|!=|==',
									'true|thumbnail_slider|true',
									true,
								),
							),
							array(
								'id'         => 'video_icon_size',
								'type'       => 'spinner',
								'title'      => __( 'Icon Size', 'testimonial-pro' ),
								'subtitle'   => __( 'Set testimonial icon size for video and lightbox.', 'testimonial-pro' ),
								'default'    => '32',
								'dependency' => array(
									'client_image|layout|video_play_place|video_icon',
									'==|!=|==|==',
									'true|thumbnail_slider|popup|true',
									true,
								),
							),
							array(
								'id'         => 'video_icon_color',
								'type'       => 'color_group',
								'title'      => __( 'Icon Color', 'testimonial-pro' ),
								'subtitle'   => __( 'Set testimonial icon color for video and lightbox.', 'testimonial-pro' ),
								'options'    => array(
									'color'       => __( 'Color', 'testimonial-pro' ),
									'hover-color' => __( 'Hover Color', 'testimonial-pro' ),
								),
								'default'    => array(
									'color'       => '#e2e2e2',
									'hover-color' => '#ffffff',
								),
								'dependency' => array(
									'client_image|layout|video_play_place|video_icon',
									'==|!=|==|==',
									'true|thumbnail_slider|popup|true',
									true,
								),
							),
							array(
								'id'         => 'video_icon_overlay',
								'type'       => 'color',
								'title'      => __( 'Icon Overlay Color', 'testimonial-pro' ),
								'subtitle'   => __( 'Set testimonial icon overlay color for video and lightbox.', 'testimonial-pro' ),
								'default'    => 'rgba(51, 51, 51, 0.4)',
								'dependency' => array(
									'client_image|layout|video_play_place|video_icon',
									'==|!=|==|==',
									'true|thumbnail_slider|popup|true',
									true,
								),
							),
						),
					),
					array(
						'title'  => __( 'Social Media', 'testimonial-pro' ),
						'icon'   => '<i class="testimonial--icon sptpro-icon-social"></i>',
						'fields' => array(
							array(
								'id'         => 'social_profile',
								'type'       => 'switcher',
								'title'      => __( 'Social Profiles', 'testimonial-pro' ),
								'subtitle'   => __( 'Show/Hide social profiles.', 'testimonial-pro' ),
								'text_on'    => __( 'Show', 'testimonial-pro' ),
								'text_off'   => __( 'Hide', 'testimonial-pro' ),
								'text_width' => 80,
								'default'    => false,
							),
							array(
								'id'         => 'social_profile_position',
								'type'       => 'button_set',
								'class'      => 'button_set_smaller',
								'title'      => __( 'Alignment', 'testimonial-pro' ),
								'subtitle'   => __( 'Social profiles alignment.', 'testimonial-pro' ),
								'options'    => array(
									'left'   => '<i class="fa fa-align-left" title="left"></i>',
									'center' => '<i class="fa fa-align-center" title="center"></i>',
									'right'  => '<i class="fa fa-align-right" title="right"></i>',
								),
								'default'    => 'center',
								'dependency' => array(
									'social_profile',
									'==',
									'true',
									true,
								),
							),
							array(
								'id'         => 'social_icon_border_radius',
								'type'       => 'spacing',
								'class'      => 'border_radius_by_spacing',
								'title'      => __( 'Icon Border Radius', 'testimonial-pro' ),
								'subtitle'   => __( 'Set social icon border radius.', 'testimonial-pro' ),
								'all'        => true,
								'all_text'   => 'Width',
								'units'      => array(
									'px',
									'%',
								),
								'default'    => array(
									'all'  => '50',
									'unit' => '%',
								),
								'dependency' => array(
									'social_profile',
									'==',
									'true',
									true,
								),
							),
							array(
								'id'         => 'social_icon_color_type',
								'type'       => 'button_set',
								'title'      => __( 'Icon Color Type', 'testimonial-pro' ),
								'subtitle'   => __( 'Choose icon color type.', 'testimonial-pro' ),
								'options'    => array(
									'original' => 'Original',
									'custom'   => 'Custom',
								),
								'default'    => 'original',
								'dependency' => array(
									'social_profile',
									'==',
									'true',
									true,
								),
							),
							array(
								'id'         => 'social_icon_color',
								'type'       => 'color_group',
								'title'      => __( 'Icon Color', 'testimonial-pro' ),
								'subtitle'   => __( 'Set social icon color.', 'testimonial-pro' ),
								'options'    => array(
									'color'            => __( 'Color', 'testimonial-pro' ),
									'hover-color'      => __( 'Hover Color', 'testimonial-pro' ),
									'background'       => __( 'Background', 'testimonial-pro' ),
									'hover-background' => __( 'Hover Background', 'testimonial-pro' ),
								),
								'default'    => array(
									'color'            => '#aaaaaa',
									'hover-color'      => '#ffffff',
									'background'       => 'transparent',
									'hover-background' => '#1595CE',
								),
								'dependency' => array(
									'social_profile|social_icon_color_type',
									'==|==',
									'true|custom',
									true,
								),
							),
							array(
								'id'          => 'social_icon_border',
								'type'        => 'border',
								'title'       => __( 'Icon Border', 'testimonial-pro' ),
								'subtitle'    => __( 'Set social icon border.', 'testimonial-pro' ),
								'all'         => true,
								'hover_color' => true,
								'default'     => array(
									'all'         => '1',
									'style'       => 'solid',
									'color'       => '#dddddd',
									'hover-color' => '#1595CE',
								),
								'dependency'  => array(
									'social_profile|social_icon_color_type',
									'==|==',
									'true|custom',
									true,
								),
							),
							array(
								'id'          => 'social_profile_margin',
								'type'        => 'spacing',
								'title'       => __( 'Margin', 'testimonial-pro' ),
								'subtitle'    => __( 'Set margin for social profiles.', 'testimonial-pro' ),
								'title_help'  => '<div class="spftestimonial-img-tag"><img src="' . SP_TPRO_URL . 'Admin/Views/Framework/assets/images/help-visuals/social_media_margin.svg" alt="Margin"></div><div class="spftestimonial-info-label">' . __( 'Margin', 'testimonial-pro' ) . '</div>',
								'default'     => array(
									'top'    => '15',
									'right'  => '0',
									'bottom' => '6',
									'left'   => '0',
									'unit'   => 'px',
								),
								'top_text'    => __( 'Top', 'testimonial-pro' ),
								'right_text'  => __( 'Right', 'testimonial-pro' ),
								'bottom_text' => __( 'Bottom', 'testimonial-pro' ),
								'left_text'   => __( 'Left', 'testimonial-pro' ),
								'units'       => array( 'px' ),
								'dependency'  => array(
									'social_profile',
									'==',
									'true',
									true,
								),
							),
						),
					),
				),
			),
		),
	)
);

//
// Slider Settings section.
//
SPFTESTIMONIAL::createSection(
	$prefix_shortcode_opts,
	array(
		'title'  => __( 'Slider Settings', 'testimonial-pro' ),
		'icon'   => 'fa fa-sliders',
		'fields' => array(
			array(
				'type'  => 'tabbed',
				'class' => 'slider-settings-tab-section',
				'tabs'  => array(
					array(
						'title'  => __( 'Slider Basics', 'testimonial-pro' ),
						'icon'   => '<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"><g clip-path="url(#A)"><path fill-rule="evenodd" d="M1.224 1.224c-.009.009-.024.03-.024.076v13.4c0 .046.015.067.024.076s.03.024.076.024h13.4c.02-.017.019-.043.012-.082l-.012-.058V14.6 1.3c0-.046-.015-.067-.024-.076s-.03-.024-.076-.024H1.3c-.046 0-.067.015-.076.024zM0 1.3A1.28 1.28 0 0 1 1.3 0h13.3a1.28 1.28 0 0 1 1.3 1.3v13.247c.058.368-.014.734-.248 1.02-.244.299-.602.433-.952.433H1.3A1.28 1.28 0 0 1 0 14.7V1.3zm12.4 3h-.9c-.3-.7-1.1-1.2-1.9-1.2-.9 0-1.6.5-1.9 1.2H3.6c-.5 0-.9.4-.9.9s.4.9.9.9h4.1c.3.8 1 1.3 1.9 1.3s1.6-.5 1.9-1.2h.9c.5 0 .9-.4.9-.9s-.4-1-.9-1zm-7.9 7.4h-.9c-.5 0-.9-.4-.9-.9s.4-.9.9-.9h.9c.3-.8 1-1.3 1.9-1.3s1.6.5 1.9 1.3h4.1c.5 0 .9.4.9.9s-.4.9-.9.9H8.3c-.3.7-1 1.2-1.9 1.2-.8 0-1.6-.5-1.9-1.2z" fill="#000"/></g><defs><clipPath id="A"><path fill="#fff" d="M0 0h16v16H0z"/></clipPath></defs></svg></span>',
						'fields' => array(
							array(
								'id'     => 'carousel_autoplay',
								'class'  => 'sp_testimonial-navigation-and-pagination-style autoplay',
								'type'   => 'fieldset',
								'fields' => array(
									array(
										'id'         => 'slider_auto_play',
										'type'       => 'switcher',
										'title'      => __( 'AutoPlay', 'testimonial-pro' ),
										'subtitle'   => __( 'Enable/Disable autoplay.', 'testimonial-pro' ),
										'class'      => 'sp_testimonial_navigation',
										'text_on'    => __( 'Enabled', 'testimonial-pro' ),
										'text_off'   => __( 'Disabled', 'testimonial-pro' ),
										'text_width' => 100,
										'default'    => true,
										'dependency' => array( 'layout', 'any', 'slider,thumbnail_slider,carousel,center,multi-rows', true ),
									),
									array(
										'id'         => 'autoplay_disable_on_mobile',
										'type'       => 'checkbox',
										'class'      => 'spt_hide_on_mobile',
										'title'      => __( 'Disable on Mobile', 'testimonial-pro' ),
										'default'    => false,
										'dependency' => array( 'layout|slider_auto_play', 'any|==', 'slider,thumbnail_slider,carousel,center,multi-rows|true', true ),
									),
								),
							),
							array(
								'id'         => 'slider_auto_play_speed',
								'type'       => 'slider',
								'title'      => __( 'AutoPlay Delay', 'testimonial-pro' ),
								'subtitle'   => __( 'Set auto play delay time in millisecond.', 'testimonial-pro' ),
								'title_help' => __( '<div class="spftestimonial-info-label">AutoPlay Delay Time</div><div class="spftestimonial-short-content">Set autoplay delay or interval time. The amount of time to delay between automatically cycling a testimonial item. e.g. 1000 milliseconds(ms) = 1 second.</div>', 'testimonial-pro' ),
								'unit'       => __( 'ms', 'testimonial-pro' ),
								'step'       => 50,
								'min'        => 100,
								'max'        => 30000,
								'default'    => 3000,
								'dependency' => array(
									'slider_auto_play|layout',
									'==|any',
									'true|slider,thumbnail_slider,carousel,center,multi-rows',
									true,
								),
							),
							array(
								'id'         => 'slider_scroll_speed',
								'type'       => 'slider',
								'title'      => __( 'Pagination Speed', 'testimonial-pro' ),
								'subtitle'   => __( 'Set pagination speed in millisecond.', 'testimonial-pro' ),
								'title_help' => __( '<div class="spftestimonial-info-label">AutoPlay Delay Time</div><div class="spftestimonial-short-content">Set carousel scrolling speed. e.g. 1000 milliseconds(ms) = 1 second.</div>', 'testimonial-pro' ),
								'unit'       => __( 'ms', 'testimonial-pro' ),
								'step'       => 100,
								'max'        => 20000,
								'min'        => 100,
								'default'    => 600,
								'dependency' => array( 'layout', 'any', 'slider,thumbnail_slider,carousel,center,multi-rows,ticker', true ),
							),
							array(
								'id'         => 'slide_to_scroll',
								'type'       => 'column',
								'title'      => __( 'Slide To Scroll', 'testimonial-pro' ),
								'subtitle'   => __( 'Number of testimonial(s) to scroll at a time.', 'testimonial-pro' ),
								'default'    => array(
									'large_desktop' => '1',
									'desktop'       => '1',
									'laptop'        => '1',
									'tablet'        => '1',
									'mobile'        => '1',
								),
								'dependency' => array( 'layout', 'any', 'carousel,center', true ),
							),
							array(
								'id'         => 'slider_pause_on_hover',
								'type'       => 'switcher',
								'title'      => __( 'Pause on Hover', 'testimonial-pro' ),
								'subtitle'   => __( 'Enable/Disable slider pause on hover.', 'testimonial-pro' ),
								'text_on'    => __( 'Enabled', 'testimonial-pro' ),
								'text_off'   => __( 'Disabled', 'testimonial-pro' ),
								'text_width' => 100,
								'default'    => true,
								'dependency' => array(
									'slider_auto_play|layout',
									'==|any',
									'true|carousel,center,slider,multi_rows,thumbnail_slider',
									true,
								),
							),
							array(
								'id'         => 'slider_infinite',
								'type'       => 'switcher',
								'title'      => __( 'Infinite Loop', 'testimonial-pro' ),
								'subtitle'   => __( 'Enable/Disable infinite loop mode.', 'testimonial-pro' ),
								'text_on'    => __( 'Enabled', 'testimonial-pro' ),
								'text_off'   => __( 'Disabled', 'testimonial-pro' ),
								'text_width' => 100,
								'default'    => true,
								'dependency' => array( 'layout', 'any', 'carousel,center,slider,multi_rows,thumbnail_slider', true ),
							),
							array(
								'id'         => 'slider_animation',
								'type'       => 'select',
								'title'      => __( 'Transition Effect', 'testimonial-pro' ),
								'subtitle'   => __( 'Select a transition effect.', 'testimonial-pro' ),
								'options'    => array(
									'slide'            => __( 'Slide', 'testimonial-pro' ),
									'fade'             => __( 'Fade', 'testimonial-pro' ),
									'flipHorizontally' => __( 'Flip Horizontally ', 'testimonial-pro' ),
									'flipVertically'   => __( 'Flip Vertically', 'testimonial-pro' ),
								),
								'default'    => 'slide',
								'dependency' => array( 'layout', 'any', 'carousel,center,slider,multi_rows,thumbnail_slider', true ),
							),
							array(
								'id'       => 'slider_direction',
								'type'     => 'button_set',
								'title'    => __( 'Direction', 'testimonial-pro' ),
								'subtitle' => __( 'Slider direction.', 'testimonial-pro' ),
								'options'  => array(
									'ltr' => __( 'Right to Left', 'testimonial-pro' ),
									'rtl' => __( 'Left to Right', 'testimonial-pro' ),
								),
								'default'  => 'ltr',
							),
							array(
								'type'       => 'notice',
								'style'      => 'normal',
								'content'    => __( 'The carousel controls are not necessary in <strong>ticker</strong>.', 'testimonial-pro' ),
								'dependency' => array( 'layout', '==', 'ticker', true ),
							),
						),
					),
					array(
						'title'  => __( 'Navigation', 'testimonial-pro' ),
						'icon'   => '<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#343434" ><path d="M2.2 8l4.1-4.1a.85.85 0 0 0 0-1.3c-.4-.3-1-.3-1.3.1L.3 7.4a.85.85 0 0 0 0 1.3L5 13.3c.3.3.9.3 1.2 0a.85.85 0 0 0 0-1.3l-4-4zM11 2.7l4.7 4.7c.4.3.4.9-.1 1.3l-4.7 4.7c-.4.4-1 .2-1.2 0a.85.85 0 0 1 0-1.3L13.8 8l-4-4.1c-.4-.3-.4-.9-.1-1.2a.85.85 0 0 1 1.3 0zM6.5 6a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1h-3z"/></svg></span>',
						'fields' => array(
							array(
								'id'     => 'spt_carousel_navigation',
								'class'  => 'sp_testimonial-navigation-and-pagination-style',
								'type'   => 'fieldset',
								'fields' => array(
									array(
										'id'         => 'navigation',
										'type'       => 'switcher',
										'class'      => 'sp_testimonial_navigation',
										'title'      => __( 'Navigation', 'testimonial-pro' ),
										'subtitle'   => __( 'Show/Hide carousel navigation.', 'testimonial-pro' ),
										'default'    => true,
										'text_on'    => __( 'Show', 'testimonial-pro' ),
										'text_off'   => __( 'Hide', 'testimonial-pro' ),
										'text_width' => 80,
										'dependency' => array( 'layout', 'any', 'slider,thumbnail_slider,carousel,center,multi-rows', true ),
									),
									array(
										'id'         => 'navigation_hide_on_mobile',
										'type'       => 'checkbox',
										'class'      => 'spt_hide_on_mobile',
										'title'      => __( 'Disable on Mobile', 'testimonial-pro' ),
										'default'    => false,
										'dependency' => array( 'layout|navigation', 'any|==', 'slider,thumbnail_slider,carousel,center,multi-rows|true', true ),
									),
								),
							),
							array(
								'id'         => 'navigation_position',
								'type'       => 'select',
								'class'      => 'chosen spftestimonial-carousel-nav-position',
								'title'      => __( 'Select Position', 'testimonial-pro' ),
								'subtitle'   => __( 'Select a position for the navigation arrows.', 'testimonial-pro' ),
								'preview'    => true,
								'options'    => array(
									'top_right'       => __( 'Top Right', 'testimonial-pro' ),
									'top_center'      => __( 'Top Center', 'testimonial-pro' ),
									'top_left'        => __( 'Top Left', 'testimonial-pro' ),
									'bottom_left'     => __( 'Bottom Left', 'testimonial-pro' ),
									'bottom_center'   => __( 'Bottom Center', 'testimonial-pro' ),
									'bottom_right'    => __( 'Bottom Right', 'testimonial-pro' ),
									'vertical_inner'  => __( 'Vertical Inner', 'testimonial-pro' ),
									'vertical_outer'  => __( 'Vertical Outer', 'testimonial-pro' ),
									'vertical_center' => __( 'Vertical Center', 'testimonial-pro' ),
								),
								'default'    => 'vertical_center',
								'dependency' => array( 'navigation|carousel_mode', '!=|!=', 'false|ticker', true ),
							),
							array(
								'id'         => 'nav_visible_on_hover',
								'type'       => 'checkbox',
								'title'      => __( 'Show On Hover', 'testimonial-pro' ),
								'subtitle'   => __( 'Check to show navigation on hover in the carousel or slider area.', 'testimonial-pro' ),
								'default'    => false,
								'dependency' => array(
									'navigation|carousel_mode|navigation_position',
									'!=|!=|any',
									'false|ticker|vertical_inner,vertical_center,vertical_outer',
									true,
								),
							),
							array(
								'id'         => 'navigation_icons',
								'type'       => 'button_set',
								'class'      => 'navigation_icons',
								'title'      => __( 'Navigation Arrow Style', 'testimonial-pro' ),
								'subtitle'   => __( 'choose a navigation arrow icon style.', 'testimonial-pro' ),
								'options'    => array(
									'angle'              => '<i class="sptpro-icon-angle-right"></i>',
									'chevron_open_big'   => '<i class="sptpro-icon-right-open-big"></i>',
									'right_open'         => '<i class="sptpro-icon-right-open"></i>',
									'chevron'            => '<i class="sptpro-icon-right-open-1"></i>',
									'right_open_3'       => '<i class="sptpro-icon-right-open-3"></i>',
									'right_open_outline' => '<i class="sptpro-icon-right-open-outline"></i>',
									'arrow'              => '<i class="sptpro-icon-right"></i>',
									'triangle'           => '<i class="sptpro-icon-arrow-triangle-right"></i>',
								),
								'default'    => 'angle',
								'dependency' => array(
									'navigation|carousel_mode',
									'!=|!=',
									'false|ticker',
									true,
								),
							),
							array(
								'id'         => 'navigation_icon_size',
								'type'       => 'spinner',
								'title'      => __( 'Navigation Icon Size', 'testimonial-pro' ),
								'subtitle'   => __( 'Change navigation icon size.', 'testimonial-pro' ),
								'default'    => '20',
								'dependency' => array(
									'navigation|carousel_mode',
									'!=|!=',
									'false|ticker',
									true,
								),
							),
							array(
								'id'         => 'navigation_color',
								'type'       => 'color_group',
								'title'      => __( 'Navigation Color', 'testimonial-pro' ),
								'subtitle'   => __( 'Set the navigation color.', 'testimonial-pro' ),
								'options'    => array(
									'color'            => __( 'Color', 'testimonial-pro' ),
									'hover-color'      => __( 'Hover Color', 'testimonial-pro' ),
									'background'       => __( 'Background', 'testimonial-pro' ),
									'hover-background' => __( 'Hover Background', 'testimonial-pro' ),
								),
								'default'    => array(
									'color'            => '#777777',
									'hover-color'      => '#ffffff',
									'background'       => 'transparent',
									'hover-background' => '#1595CE',
								),
								'dependency' => array(
									'navigation|carousel_mode',
									'!=|!=',
									'false|ticker',
									true,
								),
							),
							array(
								'id'          => 'navigation_border',
								'type'        => 'border',
								'title'       => __( 'Navigation Border', 'testimonial-pro' ),
								'subtitle'    => __( 'Set the navigation border.', 'testimonial-pro' ),
								'all'         => true,
								'hover_color' => true,
								'default'     => array(
									'all'         => '1',
									'style'       => 'solid',
									'color'       => '#777777',
									'hover-color' => '#1595CE',
								),
								'dependency'  => array(
									'navigation|carousel_mode',
									'!=|!=',
									'false|ticker',
									true,
								),
							),
							array(
								'id'         => 'navigation_border_radius',
								'type'       => 'spacing',
								'title'      => __( 'Border Radius', 'testimonial-pro' ),
								'subtitle'   => __( 'Set the navigation border radius.', 'testimonial-pro' ),
								'all'        => true,
								'units'      => array(
									'px',
									'%',
								),
								'default'    => array(
									'all'  => '50',
									'unit' => '%',
								),
								'dependency' => array(
									'navigation|carousel_mode',
									'!=|!=',
									'false|ticker',
									true,
								),
							),
						),
					),
					array(
						'title'  => __( 'Pagination', 'testimonial-pro' ),
						'icon'   => '<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" ><g clip-path="url(#A)" fill="#343434"><path d="M5.2 10.2a2.2 2.2 0 1 0 0-4.4 2.2 2.2 0 1 0 0 4.4zm6.2-.5a1.7 1.7 0 0 0 0-3.4 1.7 1.7 0 0 0 0 3.4z"/><path fill-rule="evenodd" d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-.5h12a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-.5.5H2a.5.5 0 0 1-.5-.5V4a.5.5 0 0 1 .5-.5z"/></g><defs><clipPath id="A"><path fill="#fff" d="M0 0h16v16H0z"/></clipPath></defs></svg></span>',
						'fields' => array(
							array(
								'id'     => 'spt_carousel_pagination',
								'class'  => 'sp_testimonial-navigation-and-pagination-style',
								'type'   => 'fieldset',
								'fields' => array(
									array(
										'id'         => 'pagination',
										'type'       => 'switcher',
										'class'      => 'sp_testimonial_pagination',
										'title'      => __( 'Pagination', 'testimonial-pro' ),
										'subtitle'   => __( 'Show/Hide carousel pagination.', 'testimonial-pro' ),
										'default'    => true,
										'text_on'    => __( 'Show', 'testimonial-pro' ),
										'text_off'   => __( 'Hide', 'testimonial-pro' ),
										'text_width' => 80,
										'dependency' => array( 'carousel_mode', '!=', 'ticker', true ),
									),
									array(
										'id'         => 'pagination_hide_on_mobile',
										'type'       => 'checkbox',
										'class'      => 'spt_hide_on_mobile',
										'title'      => __( 'Disable on Mobile', 'testimonial-pro' ),
										'default'    => false,
										'dependency' => array( 'carousel_mode|pagination', '!=|==', 'ticker|true', true ),
									),
								),
							),
							array(
								'id'         => 'carousel_pagination_type',
								'type'       => 'image_select',
								'class'      => 'carousel_pagination_style',
								'title'      => __( 'Pagination Style', 'testimonial-pro' ),
								'subtitle'   => __( 'Select carousel pagination type.', 'testimonial-pro' ),
								'options'    => array(
									'dots'      => array(
										'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/pagination/bullets.svg',
										'name'  => __( 'Bullets', 'testimonial-pro' ),
									),
									'dynamic'   => array(
										'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/pagination/dynamic.svg',
										'name'  => __( 'Dynamic', 'testimonial-pro' ),
									),
									'strokes'   => array(
										'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/pagination/strokes.svg',
										'name'  => __( 'Strokes', 'testimonial-pro' ),
									),
									'scrollbar' => array(
										'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/pagination/scrollbar.svg',
										'name'  => __( 'Scrollbar', 'testimonial-pro' ),
									),
									'fraction'  => array(
										'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/pagination/fraction.svg',
										'name'  => __( 'Fraction', 'testimonial-pro' ),
									),
									'numbers'   => array(
										'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/pagination/custom-numbers.svg',
										'name'  => __( 'Numbers', 'testimonial-pro' ),
									),
								),
								'radio'      => true,
								'default'    => 'dots',
								'dependency' => array( 'carousel_mode|pagination', '!=|==', 'ticker|true', true ),
							),

							array(
								'id'         => 'pagination_colors',
								'type'       => 'color_group',
								'title'      => __( 'Pagination Color', 'testimonial-pro' ),
								'subtitle'   => __( 'Set the pagination color.', 'testimonial-pro' ),
								'options'    => array(
									'color'        => __( 'Color', 'testimonial-pro' ),
									'active-color' => __( 'Active Color', 'testimonial-pro' ),
								),
								'default'    => array(
									'color'        => '#cccccc',
									'active-color' => '#1595CE',
								),
								'dependency' => array(
									'pagination|carousel_mode',
									'!=|!=',
									'false|ticker',
									true,
								),
							),
							array(
								'id'          => 'pagination_margin',
								'type'        => 'spacing',
								'title'       => __( 'Margin', 'testimonial-pro' ),
								'subtitle'    => __( 'Set pagination margin.', 'testimonial-pro' ),
								'default'     => array(
									'top'    => '21',
									'right'  => '0',
									'bottom' => '8',
									'left'   => '0',
									'unit'   => 'px',
								),
								'top_text'    => __( 'Top', 'testimonial-pro' ),
								'right_text'  => __( 'Right', 'testimonial-pro' ),
								'bottom_text' => __( 'Bottom', 'testimonial-pro' ),
								'left_text'   => __( 'Left', 'testimonial-pro' ),
								'units'       => array( 'px' ),
								'dependency'  => array( 'pagination|carousel_mode', '!=|!=', 'false|ticker', true ),
							),
						),
					),
					array(
						'title'  => __( 'Miscellaneous', 'testimonial-pro' ),
						'icon'   => '<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"><g clip-path="url(#A)" fill="#343434"><path d="M12.4 3.9h-6c-.4 0-.8.4-.8.8s.4.8.8.8h6c.4 0 .8-.3.8-.8 0-.4-.3-.8-.8-.8zm0 3.3h-6c-.4 0-.8.4-.8.8s.4.8.8.8h6c.4 0 .8-.3.8-.8 0-.4-.3-.8-.8-.8zm-6 3.2h6c.5 0 .8.4.8.8 0 .5-.4.8-.8.8h-6c-.4 0-.8-.4-.8-.8s.4-.8.8-.8zM4.9 4.8a.94.94 0 0 1-1 1c-.5 0-1-.4-1-1a.94.94 0 0 1 1-1 .94.94 0 0 1 1 1zM3.9 9a.94.94 0 0 0 1-1 .94.94 0 0 0-1-1 .94.94 0 0 0-1 1c0 .6.5 1 1 1zm1 2.2a.94.94 0 0 1-1 1c-.5 0-1-.4-1-1a.94.94 0 0 1 1-1 .94.94 0 0 1 1 1z"/><path fill-rule="evenodd" d="M13.2 0H2.9C1.3 0 0 1.3 0 2.9v10.2C0 14.7 1.3 16 2.9 16h10.2c1.6 0 2.9-1.3 2.9-2.8V2.9C16 1.3 14.7 0 13.2 0zm1.4 13.2c0 .8-.6 1.4-1.4 1.4H2.9c-.8 0-1.4-.6-1.4-1.4V2.9c0-.8.6-1.4 1.4-1.4h10.3c.8 0 1.4.6 1.4 1.4v10.3z"/></g><defs><clipPath id="A"><path fill="#fff" d="M0 0h16v16H0z"/></clipPath></defs></svg></span>',
						'fields' => array(
							array(
								'id'         => 'adaptive_height',
								'type'       => 'switcher',
								'title'      => __( 'Adaptive Slider Height', 'testimonial-pro' ),
								'title_help' => __( '<div class="spftestimonial-info-label">Adaptive Slider Height</div><div class="spftestimonial-short-content">This dynamically adjusts slider height based on each slide\'s height.</div>', 'testimonial-pro' ),
								'subtitle'   => __( 'Enable/Disable adaptive slider height.', 'testimonial-pro' ),
								'text_on'    => __( 'Enabled', 'testimonial-pro' ),
								'text_off'   => __( 'Disabled', 'testimonial-pro' ),
								'text_width' => 100,
								'default'    => false,
								'dependency' => array( 'carousel_mode', '!=', 'ticker', true ),
							),
							array(
								'id'         => 'slider_swipe',
								'type'       => 'switcher',
								'title'      => __( 'Touch Swipe', 'testimonial-pro' ),
								'subtitle'   => __( 'Enable/Disable swipe mode.', 'testimonial-pro' ),
								'text_on'    => __( 'Enabled', 'testimonial-pro' ),
								'text_off'   => __( 'Disabled', 'testimonial-pro' ),
								'text_width' => 100,
								'default'    => true,
								'dependency' => array( 'carousel_mode', '!=', 'ticker', true ),
							),
							array(
								'id'         => 'slider_draggable',
								'type'       => 'switcher',
								'title'      => __( 'Mouse Draggable', 'testimonial-pro' ),
								'subtitle'   => __( 'Enable/Disable mouse draggable mode.', 'testimonial-pro' ),
								'text_on'    => __( 'Enabled', 'testimonial-pro' ),
								'text_off'   => __( 'Disabled', 'testimonial-pro' ),
								'text_width' => 100,
								'default'    => true,
								'dependency' => array( 'slider_swipe|carousel_mode', '==|!=', 'true|ticker', true ),
							),
							array(
								'id'         => 'swipe_to_slide',
								'type'       => 'switcher',
								'title'      => __( 'Mousewheel', 'testimonial-pro' ),
								'subtitle'   => __( 'Enable/Disable mousewheel control.', 'testimonial-pro' ),
								'text_on'    => __( 'Enabled', 'testimonial-pro' ),
								'text_off'   => __( 'Disabled', 'testimonial-pro' ),
								'text_width' => 100,
								'default'    => false,
								'dependency' => array( 'slider_swipe|carousel_mode', '==|!=', 'true|ticker', true ),
							),
						),
					),
				),
			),
		),
	)
);

//
// Typography section.
//
SPFTESTIMONIAL::createSection(
	$prefix_shortcode_opts,
	array(
		'title'  => __( 'Typography', 'testimonial-pro' ),
		'icon'   => 'fa fa-font',
		'fields' => array(
			array(
				'type'  => 'tabbed',
				'class' => 'tabbed-inside-typo-settings',
				'tabs'  => array(
					array(
						'title'  => __( 'Section Title', 'testimonial-pro' ),
						'fields' => array(
							array(
								'type'    => 'submessage',
								'class'   => 'testimonial_backend-notice',
								/* translators: 1: start link tag, 2: close tag. */
								'content' => sprintf( __( 'The %1$sGlobal Google Fonts%2$s option must be enabled for the font family to work properly, and the font weight depends on the font family.', 'testimonial-pro' ), '<a href="' . esc_url( admin_url( 'edit.php?post_type=spt_testimonial&page=tpro_settings#tab=advanced' ) ) . '" target="_blank"><b>', '</b></a>' ),
							),
							array(
								'id'       => 'section_title_font_load',
								'type'     => 'switcher',
								'title'    => __( 'Load Section Title Font', 'testimonial-pro' ),
								'subtitle' => __( 'On/Off google font for the testimonial section title.', 'testimonial-pro' ),
								'default'  => false,
							),
							array(
								'id'            => 'section_title_typography',
								'type'          => 'typography',
								'title'         => __( 'Section Title', 'testimonial-pro' ),
								'subtitle'      => __( 'Set testimonial section title font properties.', 'testimonial-pro' ),
								'default'       => array(
									'font-family'    => '',
									'font-weight'    => '',
									'type'           => 'google',
									'font-size'      => '22',
									'line-height'    => '22',
									'text-align'     => 'center',
									'text-transform' => 'none',
									'letter-spacing' => 0,
									'color'          => '#444444',
									'margin-bottom'  => '60',
								),
								'margin_bottom' => true,
								'preview'       => true,
								'preview_text'  => 'What Our Customers Saying', // Replace preview text with any text you like.
							),
						),
					),
					array(
						'title'  => __( 'Testimonial Content', 'testimonial-pro' ),
						'fields' => array(
							array(
								'id'       => 'testimonial_title_font_load',
								'type'     => 'switcher',
								'title'    => __( 'Load Testimonial Title Font', 'testimonial-pro' ),
								'subtitle' => __( 'On/Off google font for the testimonial tagline or title.', 'testimonial-pro' ),
								'default'  => false,
							),
							array(
								'id'            => 'testimonial_title_typography',
								'type'          => 'typography',
								'title'         => __( 'Testimonial Title', 'testimonial-pro' ),
								'subtitle'      => __( 'Set testimonial tagline or title font properties.', 'testimonial-pro' ),
								'default'       => array(
									'font-family'    => '',
									'font-weight'    => '',
									'type'           => 'google',
									'font-size'      => '20',
									'line-height'    => '30',
									'text-align'     => 'center',
									'text-transform' => 'none',
									'letter-spacing' => 0,
									'color'          => '#333333',
									'margin-top'     => '0',
									'margin-right'   => '0',
									'margin-bottom'  => '18',
									'margin-left'    => '0',
								),
								'margin_top'    => true,
								'margin_right'  => true,
								'margin_bottom' => true,
								'margin_left'   => true,
								'preview'       => true,
								'preview_text'  => 'The Testimonial Title', // Replace preview text with any text you like.
							),
							array(
								'id'       => 'testimonial_text_font_load',
								'type'     => 'switcher',
								'title'    => __( 'Load Testimonial Content Font', 'testimonial-pro' ),
								'subtitle' => __( 'On/Off google font for the testimonial content.', 'testimonial-pro' ),
								'default'  => false,
							),
							array(
								'id'            => 'testimonial_text_typography',
								'type'          => 'typography',
								'title'         => __( 'Testimonial Content', 'testimonial-pro' ),
								'subtitle'      => __( 'Set testimonial content font properties.', 'testimonial-pro' ),
								'default'       => array(
									'font-family'    => '',
									'font-weight'    => '',
									'type'           => 'google',
									'font-size'      => '16',
									'line-height'    => '26',
									'text-align'     => 'center',
									'text-transform' => 'none',
									'letter-spacing' => 0,
									'color'          => '#333333',
									'margin-top'     => '0',
									'margin-right'   => '0',
									'margin-bottom'  => '20',
									'margin-left'    => '0',
								),
								'color'         => true,
								'preview'       => true,
								'margin_top'    => true,
								'margin_right'  => true,
								'margin_bottom' => true,
								'margin_left'   => true,
							),
						),
					),
					array(
						'title'  => __( 'Reviewer Information', 'testimonial-pro' ),
						'fields' => array(
							array(
								'id'       => 'client_name_font_load',
								'type'     => 'switcher',
								'title'    => __( 'Load Full Name Font', 'testimonial-pro' ),
								'subtitle' => __( 'On/Off google font for the full name.', 'testimonial-pro' ),
								'default'  => false,
							),
							array(
								'id'            => 'client_name_typography',
								'type'          => 'typography',
								'title'         => __( 'Full Name', 'testimonial-pro' ),
								'subtitle'      => __( 'Set full name font properties.', 'testimonial-pro' ),
								'default'       => array(
									'font-family'    => '',
									'font-weight'    => '',
									'type'           => 'google',
									'font-size'      => '16',
									'line-height'    => '24',
									'text-align'     => 'center',
									'text-transform' => 'none',
									'letter-spacing' => 0,
									'color'          => '#333333',
									'margin-top'     => '0',
									'margin-right'   => '0',
									'margin-bottom'  => '8',
									'margin-left'    => '0',
								),
								'color'         => true,
								'preview'       => true,
								'margin_top'    => true,
								'margin_right'  => true,
								'margin_bottom' => true,
								'margin_left'   => true,
								'preview_text'  => 'Jacob Firebird', // Replace preview text with any text you like.
							),
							array(
								'id'       => 'designation_company_font_load',
								'type'     => 'switcher',
								'title'    => __( 'Load Designation & Company Name Font', 'testimonial-pro' ),
								'subtitle' => __( 'On/Off google font for the designation & company name.', 'testimonial-pro' ),
								'default'  => false,
							),
							array(
								'id'            => 'client_designation_company_typography',
								'type'          => 'typography',
								'title'         => __( 'Designation & Company Name', 'testimonial-pro' ),
								'subtitle'      => __( 'Set designation & company name font properties.', 'testimonial-pro' ),
								'default'       => array(
									'font-family'    => '',
									'font-weight'    => '',
									'type'           => 'google',
									'font-size'      => '16',
									'line-height'    => '24',
									'text-align'     => 'center',
									'text-transform' => 'none',
									'letter-spacing' => 0,
									'color'          => '#444444',
									'margin-top'     => '0',
									'margin-right'   => '0',
									'margin-bottom'  => '8',
									'margin-left'    => '0',
								),
								'color'         => true,
								'preview'       => true,
								'margin_top'    => true,
								'margin_right'  => true,
								'margin_bottom' => true,
								'margin_left'   => true,
								'preview_text'  => 'CEO - Firebird Media Inc.', // Replace preview text with any text you like.
							),
							array(
								'id'       => 'location_font_load',
								'type'     => 'switcher',
								'title'    => __( 'Load Location Font', 'testimonial-pro' ),
								'subtitle' => __( 'On/Off google font for the location.', 'testimonial-pro' ),
								'default'  => false,
							),
							array(
								'id'            => 'client_location_typography',
								'type'          => 'typography',
								'title'         => __( 'Location', 'testimonial-pro' ),
								'subtitle'      => __( 'Set location font properties.', 'testimonial-pro' ),
								'default'       => array(
									'font-family'    => '',
									'font-weight'    => '',
									'type'           => 'google',
									'font-size'      => '15',
									'line-height'    => '20',
									'text-align'     => 'center',
									'text-transform' => 'none',
									'letter-spacing' => 0,
									'color'          => '#444444',
									'margin-top'     => '0',
									'margin-right'   => '0',
									'margin-bottom'  => '5',
									'margin-left'    => '0',
								),
								'color'         => true,
								'preview'       => true,
								'margin_top'    => true,
								'margin_right'  => true,
								'margin_bottom' => true,
								'margin_left'   => true,
								'preview_text'  => 'Los Angeles', // Replace preview text with any text you like.
							),
							array(
								'id'       => 'phone_font_load',
								'type'     => 'switcher',
								'title'    => __( 'Load Phone or Mobile Font', 'testimonial-pro' ),
								'subtitle' => __( 'On/Off google font for the phone or mobile.', 'testimonial-pro' ),
								'default'  => false,
							),
							array(
								'id'            => 'client_phone_typography',
								'type'          => 'typography',
								'title'         => __( 'Phone or Mobile', 'testimonial-pro' ),
								'subtitle'      => __( 'Set phone or mobile font properties.', 'testimonial-pro' ),
								'default'       => array(
									'font-family'    => '',
									'font-weight'    => '',
									'type'           => 'google',
									'font-size'      => '15',
									'line-height'    => '20',
									'text-align'     => 'center',
									'text-transform' => 'none',
									'letter-spacing' => 0,
									'color'          => '#444444',
									'margin-top'     => '0',
									'margin-right'   => '0',
									'margin-bottom'  => '3',
									'margin-left'    => '0',
								),
								'color'         => true,
								'preview'       => true,
								'margin_top'    => true,
								'margin_right'  => true,
								'margin_bottom' => true,
								'margin_left'   => true,
								'preview_text'  => '+1 234567890', // Replace preview text with any text you like.
							),
							array(
								'id'       => 'email_font_load',
								'type'     => 'switcher',
								'title'    => __( 'Load E-mail Address Font', 'testimonial-pro' ),
								'subtitle' => __( 'On/Off google font for the email address.', 'testimonial-pro' ),
								'default'  => false,
							),
							array(
								'id'            => 'client_email_typography',
								'type'          => 'typography',
								'title'         => __( 'E-mail Address', 'testimonial-pro' ),
								'subtitle'      => __( 'Set e-mail address font properties.', 'testimonial-pro' ),
								'default'       => array(
									'font-family'    => '',
									'font-weight'    => '',
									'type'           => 'google',
									'font-size'      => '15',
									'line-height'    => '20',
									'text-align'     => 'center',
									'text-transform' => 'none',
									'letter-spacing' => 0,
									'color'          => '#444444',
									'margin-top'     => '0',
									'margin-right'   => '0',
									'margin-bottom'  => '5',
									'margin-left'    => '0',
								),
								'color'         => true,
								'preview'       => true,
								'margin_top'    => true,
								'margin_right'  => true,
								'margin_bottom' => true,
								'margin_left'   => true,
								'preview_text'  => 'mail@yourwebsite.com', // Replace preview text with any text you like.
							),
							array(
								'id'       => 'date_font_load',
								'type'     => 'switcher',
								'title'    => __( 'Load Date Font', 'testimonial-pro' ),
								'subtitle' => __( 'On/Off google font for the date.', 'testimonial-pro' ),
								'default'  => false,
							),
							array(
								'id'            => 'testimonial_date_typography',
								'type'          => 'typography',
								'title'         => __( 'Date', 'testimonial-pro' ),
								'subtitle'      => __( 'Set date font properties.', 'testimonial-pro' ),
								'default'       => array(
									'font-family'    => '',
									'font-weight'    => '',
									'type'           => 'google',
									'font-size'      => '15',
									'line-height'    => '20',
									'text-align'     => 'center',
									'text-transform' => 'none',
									'letter-spacing' => 0,
									'color'          => '#444444',
									'margin-top'     => '0',
									'margin-right'   => '0',
									'margin-bottom'  => '6',
									'margin-left'    => '0',
								),
								'color'         => true,
								'preview'       => true,
								'margin_top'    => true,
								'margin_right'  => true,
								'margin_bottom' => true,
								'margin_left'   => true,
								'preview_text'  => 'February 21, 2018', // Replace preview text with any text you like.
							),
							array(
								'id'       => 'website_font_load',
								'type'     => 'switcher',
								'title'    => __( 'Load Website Font', 'testimonial-pro' ),
								'subtitle' => __( 'On/Off google font for the website.', 'testimonial-pro' ),
								'default'  => false,
							),
							array(
								'id'            => 'client_website_typography',
								'type'          => 'typography',
								'title'         => __( 'Website', 'testimonial-pro' ),
								'subtitle'      => __( 'Set website font properties.', 'testimonial-pro' ),
								'default'       => array(
									'font-family'    => '',
									'font-weight'    => '',
									'type'           => 'google',
									'font-size'      => '14',
									'line-height'    => '20',
									'text-align'     => 'center',
									'text-transform' => 'none',
									'letter-spacing' => 0,
									'color'          => '#444444',
									'margin-top'     => '0',
									'margin-right'   => '0',
									'margin-bottom'  => '6',
									'margin-left'    => '0',
								),
								'color'         => true,
								'preview'       => true,
								'margin_top'    => true,
								'margin_right'  => true,
								'margin_bottom' => true,
								'margin_left'   => true,
								'preview_text'  => 'www.example.com', // Replace preview text with any text you like.
							),
							array(
								'id'         => 'extra_fields_typography_font_load',
								'type'       => 'switcher',
								'title'      => __( 'Load Additional Custom Fields Font', 'testimonial-pro' ),
								'subtitle'   => __( 'On/Off google font for the additional information button.', 'testimonial-pro' ),
								'default'    => false,
								'dependency' => array( 'testimonial_client_addition_info', '==', 'true', true ),
							),
							array(
								'id'            => 'client_extra_fields_typography',
								'type'          => 'typography',
								'title'         => __( 'Additional Custom Fields', 'testimonial-pro' ),
								'subtitle'      => __( 'Set reviewer additional information font properties.', 'testimonial-pro' ),
								'default'       => array(
									'font-family'    => '',
									'font-weight'    => '',
									'font-style'     => '',
									'type'           => 'google',
									'font-size'      => '14',
									'line-height'    => '20',
									'text-align'     => 'center',
									'text-transform' => 'none',
									'letter-spacing' => 0,
									'color'          => '#444444',
									'margin-top'     => '0',
									'margin-right'   => '0',
									'margin-bottom'  => '6',
									'margin-left'    => '0',
								),
								'color'         => true,
								'preview'       => true,
								'margin_top'    => true,
								'margin_right'  => true,
								'margin_bottom' => true,
								'margin_left'   => true,
								'preview_text'  => 'This is reviewer additional custom fields', // Replace preview text with any text you like.
							),
						),
					),
					array(
						'title'  => __( 'Filter Button', 'testimonial-pro' ),
						'fields' => array(
							array(
								'id'       => 'filter_font_load',
								'type'     => 'switcher',
								'title'    => __( 'Load Filter button Font', 'testimonial-pro' ),
								'subtitle' => __( 'On/Off google font for the isotope filter button.', 'testimonial-pro' ),
								'default'  => false,
							),
							array(
								'id'           => 'filter_typography',
								'type'         => 'typography',
								'title'        => __( 'Filter Button', 'testimonial-pro' ),
								'subtitle'     => __( 'Set isotope filter button font properties.', 'testimonial-pro' ),
								'default'      => array(
									'font-family'    => '',
									'font-weight'    => '',
									'type'           => 'google',
									'font-size'      => '15',
									'line-height'    => '24',
									'text-align'     => 'center',
									'text-transform' => 'none',
									'letter-spacing' => 0,
								),
								'color'        => false,
								'preview'      => true,
								'preview_text' => 'All', // Replace preview text with any text you like.
							),
						),
					),
				),
			),
		),
	)
);

//
// Metabox of the testimonial form generator.
// Set a unique slug-like ID.
//
$prefix_form_elements_opts = 'sp_tpro_form_elements_options';

//
// Form metabox.
//
SPFTESTIMONIAL::createMetabox(
	$prefix_form_elements_opts,
	array(
		'title'           => __( 'Testimonial Form Fields', 'testimonial-pro' ),
		'post_type'       => 'spt_testimonial_form',
		'context'         => 'side',
		'enqueue_webfont' => false,
	)
);

//
// Form Editor section.
//
SPFTESTIMONIAL::createSection(
	$prefix_form_elements_opts,
	array(
		'fields' => array(

			array(
				'id'      => 'form_elements',
				'type'    => 'checkbox',
				'options' => array(
					'name'              => __( 'Full Name', 'testimonial-pro' ),
					'email'             => __( 'E-mail Address', 'testimonial-pro' ),
					'position'          => __( 'Designation', 'testimonial-pro' ),
					'company'           => __( 'Company Name', 'testimonial-pro' ),
					'testimonial_title' => __( 'Testimonial Title', 'testimonial-pro' ),
					'testimonial'       => __( 'Testimonial Content', 'testimonial-pro' ),
					'groups'            => __( 'Groups', 'testimonial-pro' ),
					'image'             => __( 'Image', 'testimonial-pro' ),
					'location'          => __( 'Location', 'testimonial-pro' ),
					'phone_mobile'      => __( 'Phone or Mobile', 'testimonial-pro' ),
					'website'           => __( 'Website', 'testimonial-pro' ),
					'video_url'         => __( 'Video Record/URL', 'testimonial-pro' ),
					'rating'            => __( 'Star Rating', 'testimonial-pro' ),
					'social_profile'    => __( 'Social Profile', 'testimonial-pro' ),
					'agree_checkbox'    => __( 'Checkbox', 'testimonial-pro' ),
					'recaptcha'         => __( 'reCAPTCHA', 'testimonial-pro' ),
				),
				'default' => array( 'name', 'email', 'position', 'company', 'website', 'image', 'testimonial_title', 'testimonial', 'rating' ),
			),

		),
	)
);

//
// Metabox of the testimonial form generator.
// Set a unique slug-like ID.
//
$prefix_form_code_opts = 'sp_tpro_form_code_options';

/**
 * Preview metabox.
 *
 * @param string $prefix The metabox main Key.
 * @return void
 */
SPFTESTIMONIAL::createMetabox(
	'sp_tpro_form_live_preview',
	array(
		'title'             => __( 'Live Preview', 'testimonial-pro' ),
		'post_type'         => 'spt_testimonial_form',
		'show_restore'      => false,
		'sp_tpro_shortcode' => false,
		'context'           => 'normal',
	)
);
SPFTESTIMONIAL::createSection(
	'sp_tpro_form_live_preview',
	array(
		'fields' => array(
			array(
				'type' => 'preview',
			),
		),
	)
);

//
// Form shortcode.
//
SPFTESTIMONIAL::createMetabox(
	$prefix_form_code_opts,
	array(
		'title'           => __( 'How to Use', 'testimonial-pro' ),
		'post_type'       => 'spt_testimonial_form',
		'context'         => 'side',
		'enqueue_webfont' => false,
	)
);

//
// Testimonial Form Code section.
//
SPFTESTIMONIAL::createSection(
	$prefix_form_code_opts,
	array(
		'fields' => array(

			array(
				'id'        => 'form_shortcode',
				'type'      => 'shortcode',
				'shortcode' => 'form',
			),

		),
	)
);

//
// Metabox of the testimonial form generator.
// Set a unique slug-like ID.
//
$prefix_form_opts = 'sp_tpro_form_options';

//
// Form metabox.
//
SPFTESTIMONIAL::createMetabox(
	$prefix_form_opts,
	array(
		'title'           => __( 'Form Options', 'testimonial-pro' ),
		'post_type'       => 'spt_testimonial_form',
		'context'         => 'normal',
		'enqueue_webfont' => false,
	)
);

//
// Form Editor section.
//
SPFTESTIMONIAL::createSection(
	$prefix_form_opts,
	array(
		'title'  => __( 'Form Editor', 'testimonial-pro' ),
		'icon'   => 'testimonial--icon sptpro-icon-form-editor',
		'fields' => array(
			array(
				'id'     => 'form_fields',
				'class'  => 'form_fields',
				'type'   => 'sortable',
				'fields' => array(
					array(
						'id'         => 'full_name',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Full Name', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default' => __( 'Full Name', 'testimonial-pro' ),
									),
									array(
										'id'    => 'placeholder',
										'type'  => 'text',
										'title' => __( 'Placeholder', 'testimonial-pro' ),
									),
									array(
										'id'    => 'before',
										'type'  => 'text',
										'title' => __( 'Before', 'testimonial-pro' ),
									),
									array(
										'id'      => 'after',
										'type'    => 'text',
										'title'   => __( 'After', 'testimonial-pro' ),
										'default' => __( 'What is your full name?', 'testimonial-pro' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-pro' ),
										'default' => true,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'name', true ),
					),
					array(
						'id'         => 'email_address',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'E-mail Address', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default' => __( 'E-mail Address', 'testimonial-pro' ),
									),
									array(
										'id'    => 'placeholder',
										'type'  => 'text',
										'title' => __( 'Placeholder', 'testimonial-pro' ),
									),
									array(
										'id'    => 'before',
										'type'  => 'text',
										'title' => __( 'Before', 'testimonial-pro' ),
									),
									array(
										'id'      => 'after',
										'type'    => 'text',
										'title'   => __( 'After', 'testimonial-pro' ),
										'default' => __( 'What is your e-mail address?', 'testimonial-pro' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-pro' ),
										'default' => true,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'email', true ),
					),
					array(
						'id'         => 'identity_position',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Designation', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default' => __( 'Designation', 'testimonial-pro' ),
									),
									array(
										'id'    => 'placeholder',
										'type'  => 'text',
										'title' => __( 'Placeholder', 'testimonial-pro' ),
									),
									array(
										'id'    => 'before',
										'type'  => 'text',
										'title' => __( 'Before', 'testimonial-pro' ),
									),
									array(
										'id'      => 'after',
										'type'    => 'text',
										'title'   => __( 'After', 'testimonial-pro' ),
										'default' => __( 'What is your designation?', 'testimonial-pro' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-pro' ),
										'default' => false,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'position', true ),
					),
					array(
						'id'         => 'company_name',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Company Name', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default' => __( 'Company Name', 'testimonial-pro' ),
									),
									array(
										'id'    => 'placeholder',
										'type'  => 'text',
										'title' => __( 'Placeholder', 'testimonial-pro' ),
									),
									array(
										'id'    => 'before',
										'type'  => 'text',
										'title' => __( 'Before', 'testimonial-pro' ),
									),
									array(
										'id'      => 'after',
										'type'    => 'text',
										'title'   => __( 'After', 'testimonial-pro' ),
										'default' => __( 'What is your company name?', 'testimonial-pro' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-pro' ),
										'default' => false,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'company', true ),
					),
					array(
						'id'         => 'testimonial_title',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Testimonial Title', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default' => __( 'Testimonial Title', 'testimonial-pro' ),
									),
									array(
										'id'    => 'placeholder',
										'type'  => 'text',
										'title' => __( 'Placeholder', 'testimonial-pro' ),
									),
									array(
										'id'     => 'title_length',
										'type'   => 'fieldset',
										'class'  => 'testimonial_text_limit title_limit',
										'title'  => __( 'Maximum Length', 'testimonial-pro' ),
										'fields' => array(
											array(
												'id'      => 'title_char_limit',
												'type'    => 'spinner',
												'class'   => 'character_limit',
												'default' => '50',
												'dependency' => array( 'title_length_type', '==', 'characters', true ),
											),
											array(
												'id'      => 'title_word_limit',
												'type'    => 'spinner',
												'class'   => 'word_limit',
												'unit'    => __( 'word', 'testimonial-pro' ),
												'default' => '6',
												'dependency' => array( 'title_length_type', '==', 'words', true ),
											),
											array(
												'id'      => 'title_length_type',
												'type'    => 'select',
												'class'   => 'title-length_type',
												'options' => array(
													'characters' => __( 'Characters', 'testimonial-pro' ),
													'words'      => __( 'Words', 'testimonial-pro' ),
												),
												'default' => 'characters',
											),
										),
									),
									array(
										'id'    => 'before',
										'type'  => 'text',
										'title' => __( 'Before', 'testimonial-pro' ),
									),
									array(
										'id'      => 'after',
										'type'    => 'text',
										'title'   => __( 'After', 'testimonial-pro' ),
										'default' => __( 'A headline or tagline for your testimonial.', 'testimonial-pro' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-pro' ),
										'default' => false,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'testimonial_title', true ),
					),
					array(
						'id'         => 'testimonial',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Testimonial Content', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default' => __( 'Testimonial', 'testimonial-pro' ),
									),
									array(
										'id'    => 'placeholder',
										'type'  => 'text',
										'title' => __( 'Placeholder', 'testimonial-pro' ),
									),
									array(
										'id'     => 'content_length',
										'type'   => 'fieldset',
										'class'  => 'testimonial_text_limit content_limit',
										'title'  => __( 'Maximum Length', 'testimonial-pro' ),
										'fields' => array(
											array(
												'id'      => 'content_char_limit',
												'type'    => 'spinner',
												'class'   => 'character_limit',
												'default' => '500',
												'dependency' => array( 'content_length_type', '==', 'characters', true ),
											),
											array(
												'id'      => 'content_word_limit',
												'type'    => 'spinner',
												'class'   => 'word_limit',
												'unit'    => __( 'word', 'testimonial-pro' ),
												'default' => '80',
												'dependency' => array( 'content_length_type', '==', 'words', true ),
											),
											array(
												'id'      => 'content_length_type',
												'type'    => 'select',
												'class'   => 'content-length_type',
												'options' => array(
													'characters' => __( 'Characters', 'testimonial-pro' ),
													'words'      => __( 'Words', 'testimonial-pro' ),
												),
												'default' => 'characters',
											),
										),
									),
									array(
										'id'    => 'before',
										'type'  => 'text',
										'title' => __( 'Before', 'testimonial-pro' ),
									),
									array(
										'id'      => 'after',
										'type'    => 'text',
										'title'   => __( 'After', 'testimonial-pro' ),
										'default' => __( 'What do you think about us?', 'testimonial-pro' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-pro' ),
										'default' => true,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'testimonial', true ),
					),
					array(
						'id'         => 'groups',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Groups', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default' => __( 'Groups', 'testimonial-pro' ),
									),
									array(
										'id'    => 'placeholder',
										'type'  => 'text',
										'title' => __( 'Placeholder', 'testimonial-pro' ),
									),
									array(
										'id'    => 'before',
										'type'  => 'text',
										'title' => __( 'Before', 'testimonial-pro' ),
									),
									array(
										'id'      => 'after',
										'type'    => 'text',
										'title'   => __( 'After', 'testimonial-pro' ),
										'default' => __( 'Select groups name.', 'testimonial-pro' ),
									),
									array(
										'id'      => 'multiple_selection',
										'type'    => 'checkbox',
										'title'   => __( 'Multiple Group Selection?', 'testimonial-pro' ),
										'default' => false,
									),
									array(
										'id'          => 'groups_list',
										'class'       => 'form_groups_list',
										'type'        => 'select',
										'title'       => __( 'Select Group(s)', 'testimonial-pro' ),
										'desc'        => '<span class="sp-tpro-select-group">' . __( 'Select groups for the frontend form. Leave blank for the all groups.', 'testimonial-pro' ) . '</span><a class="sp-tpro-manage-group-link" href="' . esc_url( $tpro_edit_tax_link ) . '">' . __( 'Manage Groups', 'testimonial-pro' ) . '</a>',
										'options'     => 'categories',
										'query_args'  => array(
											'type'       => 'spt_testimonial',
											'taxonomy'   => 'testimonial_cat',
											'hide_empty' => 0,
										),
										'placeholder' => __( 'Select Groups', 'testimonial-pro' ),
										'title_help'  => __( 'The group field automatically hides on the frontend when only a single group is selected.', 'testimonial-pro' ),
										'chosen'      => true,
										'multiple'    => true,
										'sortable'    => true,
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-pro' ),
										'default' => false,
									),

								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'groups', true ),
					),
					array(
						'id'         => 'featured_image',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Image', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default' => __( 'Photo', 'testimonial-pro' ),
									),
									array(
										'id'    => 'before',
										'type'  => 'text',
										'title' => __( 'Before', 'testimonial-pro' ),
									),
									array(
										'id'      => 'after',
										'type'    => 'text',
										'title'   => __( 'After', 'testimonial-pro' ),
										'default' => __( 'Would you like to include photo?', 'testimonial-pro' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-pro' ),
										'default' => false,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'image', true ),
					),
					array(
						'id'         => 'location',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Location', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default' => __( 'Location', 'testimonial-pro' ),
									),
									array(
										'id'         => 'country_selector',
										'type'       => 'switcher',
										'title'      => __( 'Country Selector', 'testimonial-pro' ),
										'text_on'    => esc_html__( 'Enabled', 'testimonial-pro' ),
										'text_off'   => esc_html__( 'Disabled', 'testimonial-pro' ),
										'text_width' => 100,
										'default'    => false,
									),
									array(
										'id'    => 'placeholder',
										'type'  => 'text',
										'title' => __( 'Placeholder', 'testimonial-pro' ),
									),
									array(
										'id'    => 'before',
										'type'  => 'text',
										'title' => __( 'Before', 'testimonial-pro' ),
									),
									array(
										'id'      => 'after',
										'type'    => 'text',
										'title'   => __( 'After', 'testimonial-pro' ),
										'default' => __( 'What is your location?', 'testimonial-pro' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-pro' ),
										'default' => false,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'location', true ),
					),
					array(
						'id'         => 'phone_mobile',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Phone or Mobile', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default' => __( 'Phone or Mobile', 'testimonial-pro' ),
									),
									array(
										'id'    => 'placeholder',
										'type'  => 'text',
										'title' => __( 'Placeholder', 'testimonial-pro' ),
									),
									array(
										'id'    => 'before',
										'type'  => 'text',
										'title' => __( 'Before', 'testimonial-pro' ),
									),
									array(
										'id'      => 'after',
										'type'    => 'text',
										'title'   => __( 'After', 'testimonial-pro' ),
										'default' => __( 'What is your phone number?', 'testimonial-pro' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-pro' ),
										'default' => false,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'phone_mobile', true ),
					),
					array(
						'id'         => 'website',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Website', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default' => __( 'Website', 'testimonial-pro' ),
									),
									array(
										'id'    => 'placeholder',
										'type'  => 'text',
										'title' => __( 'Placeholder', 'testimonial-pro' ),
									),
									array(
										'id'    => 'before',
										'type'  => 'text',
										'title' => __( 'Before', 'testimonial-pro' ),
									),
									array(
										'id'      => 'after',
										'type'    => 'text',
										'title'   => __( 'After', 'testimonial-pro' ),
										'default' => __( 'What is your website?', 'testimonial-pro' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-pro' ),
										'default' => false,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'website', true ),
					),
					array(
						'id'         => 'video_url',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Video Record/URL', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'form_video_type',
										'type'    => 'button_set',
										'title'   => __( 'Video Type', 'testimonial-pro' ),
										'radio'   => true,
										'options' => array(
											'record_video' => __( 'Record Video', 'testimonial-pro' ),
											'video_by_url' => __( 'Video by URL', 'testimonial-pro' ),
										),
										'default' => 'record_video',
									),
									array(
										'id'         => 'label',
										'type'       => 'text',
										'title'      => __( 'Label', 'testimonial-pro' ),
										'desc'       => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default'    => __( 'Video URL', 'testimonial-pro' ),
										'dependency' => array( 'form_video_type', '==', 'video_by_url' ),
									),
									array(
										'id'         => 'placeholder',
										'type'       => 'text',
										'title'      => __( 'Placeholder', 'testimonial-pro' ),
										'dependency' => array( 'form_video_type', '==', 'video_by_url' ),
									),
									array(
										'id'         => 'before',
										'type'       => 'text',
										'title'      => __( 'Before', 'testimonial-pro' ),
										'dependency' => array( 'form_video_type', '==', 'video_by_url' ),
									),
									array(
										'id'         => 'after',
										'type'       => 'text',
										'title'      => __( 'After', 'testimonial-pro' ),
										'default'    => __( 'Type video URL.', 'testimonial-pro' ),
										'dependency' => array( 'form_video_type', '==', 'video_by_url' ),
									),
									array(
										'id'         => 'record_video_format',
										'type'       => 'select',
										'title'      => __( 'Preferred Video Format', 'testimonial-pro' ),
										'options'    => array(
											'webm' => __( 'Webm', 'testimonial-pro' ),
											'mp4'  => __( 'MP4', 'testimonial-pro' ),
										),
										'default'    => 'webm',
										'dependency' => array( 'form_video_type', '==', 'record_video' ),
									),
									array(
										'id'         => 'record_label',
										'type'       => 'text',
										'title'      => __( 'Label', 'testimonial-pro' ),
										'desc'       => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default'    => __( 'Leave A Video Review', 'testimonial-pro' ),
										'dependency' => array( 'form_video_type', '==', 'record_video' ),
									),
									array(
										'id'         => 'record_btn_text',
										'type'       => 'text',
										'title'      => __( 'Record Button Text', 'testimonial-pro' ),
										'default'    => 'Record video',
										'dependency' => array( 'form_video_type', '==', 'record_video' ),
									),
									array(
										'id'         => 'recording_time',
										'type'       => 'spinner',
										'title'      => __( 'Maximum Recording Time', 'testimonial-pro' ),
										'default'    => 2,
										'unit'       => 'Mins',
										'min'        => '0',
										'max'        => '5',
										'dependency' => array( 'form_video_type', '==', 'record_video' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-pro' ),
										'default' => false,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'video_url', true ),
					),
					array(
						'id'         => 'rating',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Star Rating', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default' => __( 'Star Rating', 'testimonial-pro' ),
									),
									array(
										'id'    => 'before',
										'type'  => 'text',
										'title' => __( 'Before', 'testimonial-pro' ),
									),
									array(
										'id'      => 'after',
										'type'    => 'text',
										'title'   => __( 'After', 'testimonial-pro' ),
										'default' => __( 'Would you like to include star rating?', 'testimonial-pro' ),
									),
									array(
										'id'      => 'tpro_preselect_rating',
										'type'    => 'rating',
										'title'   => __( 'Preselect Star Rating', 'testimonial-pro' ),
										'options' => array(
											'five_star'  => __( '5 Stars', 'testimonial-pro' ),
											'four_star'  => __( '4 Stars', 'testimonial-pro' ),
											'three_star' => __( '3 Stars', 'testimonial-pro' ),
											'two_star'   => __( '2 Stars', 'testimonial-pro' ),
											'one_star'   => __( '1 Star', 'testimonial-pro' ),
										),
										'default' => '',
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-pro' ),
										'default' => false,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'rating', true ),
					),
					array(
						'id'         => 'social_profile',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Social Profile', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default' => __( 'Social Profile', 'testimonial-pro' ),
									),
									array(
										'id'    => 'before',
										'type'  => 'text',
										'title' => __( 'Before', 'testimonial-pro' ),
									),
									array(
										'id'      => 'after',
										'type'    => 'text',
										'title'   => __( 'After', 'testimonial-pro' ),
										'default' => __( 'Would you like to include social profiles?', 'testimonial-pro' ),
									),
									array(
										'id'          => 'social_profile_list',
										'type'        => 'select',
										'title'       => __( 'Select Social Profile(s)', 'testimonial-pro' ),
										'desc'        => __( 'Select social profile for the frontend form. Leave blank for the all socials.', 'testimonial-pro' ),
										'options'     => 'social_profile_name_list',
										'placeholder' => __( 'Select Social Profile(s)', 'testimonial-pro' ),
										'chosen'      => true,
										'multiple'    => true,
										'sortable'    => true,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'social_profile', true ),
					),
					array(
						'id'         => 'recaptcha',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'reCAPTCHA', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'    => 'label',
										'type'  => 'text',
										'title' => __( 'Label', 'testimonial-pro' ),
										'desc'  => __( 'To hide this label, leave it empty.', 'testimonial-pro' ) . __( ' You must setup ', 'testimonial-pro' ) . '<a class="sp-tpro-manage-group-link" target="_blank" href="' . esc_url( admin_url( 'edit.php?post_type=spt_testimonial&page=tpro_settings#tab=recaptcha' ) ) . '">' . __( 'Google reCaptcha', 'testimonial-pro' ) . '</a>' . __( ' in the plugin settings.', 'testimonial-pro' ),
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'recaptcha', true ),
					),
					array(
						'id'         => 'agree_checkbox',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Checkbox', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-pro' ),
										'default' => __( 'I agree to publish the testimonial on the website.', 'testimonial-pro' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-pro' ),
										'default' => true,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'agree_checkbox', true ),
					),
					array(
						'id'         => 'submit_btn',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Submit Button', 'testimonial-pro' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-pro' ),
										'desc'    => __( 'Type submit button label.', 'testimonial-pro' ),
										'default' => __( 'SUBMIT TESTIMONIAL', 'testimonial-pro' ),
									),
								),
							),
						),
					),

				),
			),

		),
	)
);

$admin_email = get_option( 'admin_email' );

//
// Messages section.
//
SPFTESTIMONIAL::createSection(
	$prefix_form_opts,
	array(
		'title'  => __( 'Labels & Messages', 'testimonial-pro' ),
		'icon'   => 'testimonial--icon sptpro-icon-notification-messages',
		'fields' => array(
			array(
				'id'         => 'required_notice',
				'type'       => 'switcher',
				'title'      => __( 'Required Notice', 'testimonial-pro' ),
				'subtitle'   => __( 'Display required notice at top of the form.', 'testimonial-pro' ),
				'text_on'    => esc_html__( 'Enabled', 'testimonial-pro' ),
				'text_off'   => esc_html__( 'Disabled', 'testimonial-pro' ),
				'text_width' => 100,
				'default'    => true,
			),
			array(
				'id'         => 'notice_label',
				'type'       => 'text',
				'title'      => __( 'Notice Label', 'testimonial-pro' ),
				'subtitle'   => __( 'Set a label for the required notice.', 'testimonial-pro' ),
				'default'    => __( 'Red asterisk fields are required.', 'testimonial-pro' ),
				'dependency' => array( 'required_notice', '==', 'true', true ),
			),
			array(
				'type'    => 'subheading',
				'content' => esc_html__( 'FORM ACTIONS', 'testimonial-pro' ),
			),
			array(
				'id'         => 'ajax_form_submission',
				'type'       => 'switcher',
				'title'      => __( 'Ajax Form Submission', 'testimonial-pro' ),
				'subtitle'   => __( 'Submit the form without reloading the page.', 'testimonial-pro' ),
				'text_on'    => esc_html__( 'Enabled', 'testimonial-pro' ),
				'text_off'   => esc_html__( 'Disabled', 'testimonial-pro' ),
				'text_width' => 100,
				'default'    => true,
			),
			array(
				'id'       => 'tpro_redirect',
				'type'     => 'select',
				'title'    => __( 'Redirect', 'testimonial-pro' ),
				'subtitle' => __( 'After successful submit, where the page will redirect to.', 'testimonial-pro' ),
				'options'  => array(
					'same_page'  => __( 'Same page', 'testimonial-pro' ),
					'to_a_page'  => __( 'To a page', 'testimonial-pro' ),
					'custom_url' => __( 'To a custom URL', 'testimonial-pro' ),
				),
				'default'  => 'same_page',
			),
			array(
				'id'         => 'tpro_redirect_to_page',
				'type'       => 'select',
				'title'      => __( 'Page', 'testimonial-pro' ),
				'subtitle'   => __( 'Select redirect page.', 'testimonial-pro' ),
				'options'    => 'pages',
				'dependency' => array( 'tpro_redirect', '==', 'to_a_page' ),
			),
			array(
				'id'         => 'tpro_redirect_custom_url',
				'type'       => 'text',
				'title'      => __( 'Custom URL', 'testimonial-pro' ),
				'subtitle'   => __( 'Type redirect custom url.', 'testimonial-pro' ),
				'dependency' => array( 'tpro_redirect', '==', 'custom_url' ),
			),
			array(
				'id'         => 'successful_message',
				'type'       => 'text',
				'class'      => 'larger_text_input',
				'title'      => __( 'Successful Message', 'testimonial-pro' ),
				'subtitle'   => __( 'Set a submission success message.', 'testimonial-pro' ),
				'default'    => __( 'Thank you! Your testimonial is currently waiting to be approved.', 'testimonial-pro' ),
				'dependency' => array( 'tpro_redirect', '==', 'same_page' ),
			),
			array(
				'id'       => 'error_message',
				'type'     => 'text',
				'title'    => __( 'Error Message', 'testimonial-pro' ),
				'class'    => 'larger_text_input',
				'subtitle' => __( 'Set a submission error message.', 'testimonial-pro' ),
				'default'  => __( 'We encountered an issue while processing your testimonial.', 'testimonial-pro' ),
			),
			array(
				'id'         => 'tpro_message_position',
				'type'       => 'button_set',
				'title'      => __( 'Form Submission Message Position', 'testimonial-pro' ),
				'subtitle'   => __( 'Set a form submission message position.', 'testimonial-pro' ),
				'radio'      => true,
				'options'    => array(
					'top'    => __( 'Top', 'testimonial-pro' ),
					'bottom' => __( 'Bottom', 'testimonial-pro' ),
				),
				'default'    => 'bottom',
				'dependency' => array( 'tpro_redirect', '==', 'same_page' ),
			),
			array(
				'id'         => 'tpro_hide_form_after_submit',
				'subtitle'   => __( 'Hide testimonial form after submission by user.', 'testimonial-pro' ),
				'type'       => 'checkbox',
				'title'      => __( 'Hide Form', 'testimonial-pro' ),
				'dependency' => array( 'ajax_form_submission', '==', 'true' ),
			),
			array(
				'type'    => 'subheading',
				'content' => esc_html__( 'FORM DISPLAY MODE', 'testimonial-pro' ),
			),
			array(
				'id'       => 'tpro_form_display_mode',
				'type'     => 'button_set',
				'title'    => __( 'Display Mode', 'testimonial-pro' ),
				'subtitle' => __( 'Choose a form display mode.', 'testimonial-pro' ),
				'options'  => array(
					'on_page' => __( 'On Page', 'testimonial-pro' ),
					'popup'   => __( 'Popup', 'testimonial-pro' ),
				),
				'default'  => 'on_page',
			),
			array(
				'id'         => 'popup_button_style',
				'type'       => 'image_select',
				'class'      => 'live_filter_type',
				'title'      => __( 'Button Style', 'testimonial-pro' ),
				'subtitle'   => __( 'Choose a button style.', 'testimonial-pro' ),
				'options'    => array(
					'button' => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/button.svg',
						'name'  => __( 'Button', 'testimonial-pro' ),
					),
					'text'   => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/text.svg',
						'name'  => __( 'Text Link', 'testimonial-pro' ),
					),
				),
				'default'    => 'button',
				'dependency' => array( 'tpro_form_display_mode', '==', 'popup', true ),
			),
			array(
				'id'         => 'popup_button_label',
				'type'       => 'text',
				'title'      => __( 'Button Label', 'testimonial-pro' ),
				'default'    => 'Leave Your Review',
				'subtitle'   => __( 'Set button label.', 'testimonial-pro' ),
				'dependency' => array( 'tpro_form_display_mode', '==', 'popup', true ),
			),
		),
	)
);

$admin_email = get_option( 'admin_email' );

//
// Notifications section.
//
SPFTESTIMONIAL::createSection(
	$prefix_form_opts,
	array(
		'title'  => __( 'Status & Notifications', 'testimonial-pro' ),
		'icon'   => 'testimonial--icon sptpro-icon-notifications',
		'fields' => array(
			array(
				'id'       => 'testimonial_approval_status',
				'type'     => 'select',
				'class'    => 'testimonial_approval_status',
				'title'    => __( 'Testimonial Status', 'testimonial-pro' ),
				'subtitle' => __( 'Select testimonial approval status for the front-end form submission.', 'testimonial-pro' ),
				'options'  => array(
					'pending'              => esc_html__( 'Pending Review', 'testimonial-pro' ),
					'private'              => esc_html__( 'Private', 'testimonial-pro' ),
					'draft'                => esc_html__( 'Draft', 'testimonial-pro' ),
					'publish'              => esc_html__( 'Auto Publish', 'testimonial-pro' ),
					'based_on_rating_star' => esc_html__( 'Auto Publish Based on Star Rating', 'testimonial-pro' ),
				),
				'default'  => 'pending',
			),
			array(
				'id'         => 'tpro_auto_publish_rating',
				'type'       => 'checkbox',
				'class'      => 'tpro-rating-star',
				'title'      => __( 'Based on Star Rating', 'testimonial-pro' ),
				'subtitle'   => __( 'Check which star-rated testimonials will be automatically published ', 'testimonial-pro' ),
				'options'    => array(
					'five_star'  => '  ',
					'four_star'  => '  ',
					'three_star' => '  ',
					'two_star'   => '  ',
					'one_star'   => '  ',
				),
				'default'    => array( 'five_star', 'four_star' ),
				'dependency' => array(
					'testimonial_approval_status',
					'==',
					'based_on_rating_star',
					true,
				),
			),
			array(
				'type'  => 'tabbed',
				'class' => 'sp-status-and-notification sp_tabbed_horizontal',
				'tabs'  => array(
					array(
						'title'  => __( 'ADMIN NOTIFICATION', 'testimonial-pro' ),
						'icon'   => '<i class="testimonial--icon sptpro-icon-admin-notification"></i>',
						'fields' => array(
							array(
								'id'         => 'submission_email_notification',
								'type'       => 'switcher',
								'title'      => __( 'Admin Notification', 'testimonial-pro' ),
								'text_on'    => esc_html__( 'Enabled', 'testimonial-pro' ),
								'text_off'   => esc_html__( 'Disabled', 'testimonial-pro' ),
								'text_width' => 100,
								'default'    => true,
							),
							array(
								'id'         => 'submission_email_to',
								'type'       => 'text',
								'class'      => 'larger_text_input',
								'title'      => __( 'To', 'testimonial-pro' ),
								'default'    => $admin_email,
								'dependency' => array( 'submission_email_notification', '==', 'true' ),
								'title_help' => 'For multiple emails, use comma between these.',
							),
							array(
								'id'         => 'submission_email_from',
								'type'       => 'text',
								'class'      => 'larger_text_input',
								'title'      => __( 'From', 'testimonial-pro' ),
								'default'    => '{site_title} {' . $admin_email . '}',
								'dependency' => array( 'submission_email_notification', '==', 'true' ),
							),
							array(
								'id'         => 'submission_email_subject',
								'type'       => 'text',
								'class'      => 'larger_text_input',
								'title'      => __( 'Subject', 'testimonial-pro' ),
								'default'    => 'A New Testimonial is Pending for {site_title}!',
								'dependency' => array( 'submission_email_notification', '==', 'true' ),
							),
							array(
								'id'         => 'submission_email_body',
								'type'       => 'wp_editor',
								'class'      => 'email_body_area',
								'title'      => __( 'Message Body', 'testimonial-pro' ),
								'default'    => '<h2 style="text-align: center;font-size: 24px;">New Testimonial!</h2>
								Hi There,
								A new testimonial has been submitted to your website. Following is the reviewer\'s information.
								Name: {name}
								Email: {email}
								Testimonial Content: {testimonial_text}
								Rating: {rating}
								Please go <a href="' . esc_url( admin_url( 'edit.php?post_type=spt_testimonial' ) ) . '">admin dashboard</a> to review it and publish.
								Thank you!',
								'desc'       => '
									<div class="template-heading">
									Available Tags for Subject and Message for Email Template
									</div>
									<div class="template-content">
									{name} {email} {position} {company_name} {location} {phone} {website} {video_url} {testimonial_title} {testimonial_text} {groups} {rating} {site_title}
									</div>',
								'dependency' => array( 'submission_email_notification', '==', 'true' ),
							),
						),
					),
					array(
						'title'  => __( 'AWAITING NOTIFICATION', 'testimonial-pro' ),
						'icon'   => '<i class="testimonial--icon sptpro-icon-awaiting-notification"></i>',
						'fields' => array(
							array(
								'id'         => 'reviewer_awaiting_notification',
								'type'       => 'switcher',
								'title'      => __( 'Reviewer Awaiting Notification', 'testimonial-pro' ),
								'text_on'    => esc_html__( 'Enabled', 'testimonial-pro' ),
								'text_off'   => esc_html__( 'Disabled', 'testimonial-pro' ),
								'text_width' => 100,
								'default'    => true,
								'dependency' => array( 'testimonial_approval_status', '!=', 'publish', true ),
							),
							array(
								'id'         => 'reviewer_awaiting_to',
								'type'       => 'text',
								'class'      => 'larger_text_input',
								'title'      => __( 'To', 'testimonial-pro' ),
								'default'    => '{email}',
								'dependency' => array( 'reviewer_awaiting_notification|testimonial_approval_status', '==|!=', 'true|publish', true ),
							),
							array(
								'id'         => 'reviewer_awaiting_from',
								'type'       => 'text',
								'class'      => 'larger_text_input',
								'title'      => __( 'From', 'testimonial-pro' ),
								'default'    => '{site_title} {' . $admin_email . '}',
								'dependency' => array( 'reviewer_awaiting_notification|testimonial_approval_status', '==|!=', 'true|publish', true ),
							),
							array(
								'id'         => 'reviewer_awaiting_email_subject',
								'type'       => 'text',
								'class'      => 'larger_text_input',
								'title'      => __( 'Subject', 'testimonial-pro' ),
								'default'    => 'Your Testimonial has been Received for {site_title}!',
								'dependency' => array( 'reviewer_awaiting_notification|testimonial_approval_status', '==|!=', 'true|publish', true ),
							),
							array(
								'id'         => 'reviewer_awaiting_email_body',
								'type'       => 'wp_editor',
								'class'      => 'email_body_area',
								'title'      => __( 'Message Body', 'testimonial-pro' ),
								'default'    => '<h2 style="text-align:center;font-size:24px;">Your Testimonial Received!</h2>
								Hi {name},
								Thank you for submitting your testimonial. Your testimonial has been received and is awaiting approval from our website administrator.
								Thank you again for helping us improve our products and services.',
								'desc'       => '<div class="template-heading">Available Tags for Subject and Message for Email Template</div>
									<div class="template-content">
									{name} {email} {position} {company_name} {location} {phone} {website} {video_url} {testimonial_title} {testimonial_text} {groups} {rating} {site_title}
									</div>',
								'dependency' => array( 'reviewer_awaiting_notification|testimonial_approval_status', '==|!=', 'true|publish', true ),
							),
						),
					),
					array(
						'title'  => __( 'APPROVAL NOTIFICATION', 'testimonial-pro' ),
						'icon'   => '<i class="testimonial--icon sptpro-icon-approval-notification"></i>',
						'fields' => array(
							array(
								'id'         => 'approval_notification',
								'type'       => 'switcher',
								'title'      => __( 'Testimonial Approval Notification', 'testimonial-pro' ),
								'text_on'    => esc_html__( 'Enabled', 'testimonial-pro' ),
								'text_off'   => esc_html__( 'Disabled', 'testimonial-pro' ),
								'text_width' => 100,
								'default'    => true,
							),
							array(
								'id'         => 'approval_email_to',
								'type'       => 'text',
								'class'      => 'larger_text_input',
								'title'      => __( 'To', 'testimonial-pro' ),
								'default'    => '{email}',
								'dependency' => array( 'approval_notification', '==', 'true' ),
							),
							array(
								'id'         => 'approval_email_from',
								'type'       => 'text',
								'class'      => 'larger_text_input',
								'title'      => __( 'From', 'testimonial-pro' ),
								'default'    => '{site_title} {' . $admin_email . '}',
								'dependency' => array( 'approval_notification', '==', 'true' ),
							),
							array(
								'id'         => 'approval_email_subject',
								'type'       => 'text',
								'class'      => 'larger_text_input',
								'title'      => __( 'Subject', 'testimonial-pro' ),
								'default'    => 'Congratulations! Your Testimonial has been Approved!',
								'dependency' => array( 'approval_notification', '==', 'true' ),
							),
							array(
								'id'         => 'approval_email_body',
								'type'       => 'wp_editor',
								'class'      => 'email_body_area',
								'title'      => __( 'Message Body', 'testimonial-pro' ),
								'default'    => '<h2 style="text-align:center;font-size:24px;">Congrats, Your Testimonial Approved!</h2>
								Hi {name},
								Congratulations! We are delighted to inform you that your testimonial has been approved. We appreciate your input and value your loyalty. You can now view it on the website. 
								Thank you!',
								'desc'       => '<div class="template-heading">Available Tags for Subject and Message for Email Template</div>
									<div class="template-content">
									{name} {email} {position} {company_name} {location} {phone} {website} {video_url} {testimonial_title} {testimonial_text} {groups} {rating} {site_title}
									</div>',
								'dependency' => array( 'approval_notification', '==', 'true' ),
							),
						),
					),
				),
			),
		),
	)
);

//
// Stylization section.
//
SPFTESTIMONIAL::createSection(
	$prefix_form_opts,
	array(
		'title'  => __( 'Form Styles', 'testimonial-pro' ),
		'icon'   => 'testimonial--icon sptpro-icon-form-style',
		'fields' => array(
			array(
				'id'       => 'label_position',
				'type'     => 'image_select',
				'class'    => 'label_position',
				'title'    => __( 'Form Layout', 'testimonial-pro' ),
				'subtitle' => __( 'Choose a testimonial form layout.', 'testimonial-pro' ),
				'options'  => array(
					'top'  => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/form_style_one.svg',
						'name'  => __( 'Style One', 'testimonial-pro' ),
					),
					'left' => array(
						'image' => SP_TPRO_URL . '/Admin/Views/Framework/assets/images/form_style_two.svg',
						'name'  => __( 'Style Two', 'testimonial-pro' ),
					),
				),
				'default'  => 'top',
			),
			array(
				'id'              => 'testimonial_form_width',
				'class'           => 'testimonial-slide-margin',
				'type'            => 'spacing',
				'title'           => __( 'Form Width', 'testimonial-pro' ),
				'subtitle'        => __( 'Set width for the form.', 'testimonial-pro' ),
				'right'           => false,
				'top'             => true,
				'left'            => false,
				'bottom'          => false,
				'top_placeholder' => __( 'width', 'testimonial-pro' ),
				'top_text'        => __( 'Width', 'testimonial-pro' ),
				'top_icon'        => '<i class="fa fa-arrows-h"></i>',
				'unit'            => true,
				'units'           => array( 'px' ),
				'default'         => array(
					'top' => '680',
				),
			),
			array(
				'id'       => 'label_color',
				'type'     => 'color',
				'title'    => __( 'Label Color', 'testimonial-pro' ),
				'subtitle' => __( 'Set color for the field label.', 'testimonial-pro' ),
				'default'  => '#444444',
			),
			array(
				'id'                    => 'form_input_field_styles',
				'type'                  => 'border',
				'title'                 => __( 'Input Field', 'testimonial-pro' ),
				'subtitle'              => __( 'Set input field style.', 'testimonial-pro' ),
				'all'                   => true,
				'radius'                => true,
				'background_color'      => true,
				'background_color_text' => __( 'BG Color', 'testimonial-pro' ),
				'default'               => array(
					'all'              => '1',
					'style'            => 'solid',
					'color'            => '#444444',
					'background_color' => '#FFFFFF',
					'radius'           => '4',
				),
				'units'                 => array(
					'px',
					'%',
				),
			),
			array(
				'id'       => 'record_button_color',
				'type'     => 'color_group',
				'title'    => __( 'Record Button Color', 'testimonial-pro' ),
				'subtitle' => __( 'Set color for the video record button.', 'testimonial-pro' ),
				'options'  => array(
					'color'            => esc_html__( 'Color', 'testimonial-pro' ),
					'hover-color'      => esc_html__( 'Hover Color', 'testimonial-pro' ),
					'background'       => esc_html__( 'Background', 'testimonial-pro' ),
					'hover-background' => esc_html__( 'Hover Background', 'testimonial-pro' ),
				),
				'default'  => array(
					'color'            => '#005BDF',
					'hover-color'      => '#005BDF',
					'background'       => 'rgba(0, 91, 223, 0.12)',
					'hover-background' => 'rgba(0, 91, 223, 0.18)',
				),
			),
			array(
				'id'                    => 'testimonial_form_rating_styles',
				'type'                  => 'border',
				'title'                 => __( 'Rating', 'testimonial-pro' ),
				'subtitle'              => __( 'Set styles for the star rating.', 'testimonial-pro' ),
				'all'                   => true,
				'background_color'      => true,
				'radius'                => false,
				'style'                 => false,
				'hover_color'           => true,
				'all_above_text'        => 'Size',
				'color_label'           => 'Empty Color',
				'background_color_text' => 'Star Color',
				'default'               => array(
					'all'              => '16',
					'color'            => '#d4d4d4',
					'background_color' => '#f3bb00',
					'hover-color'      => '#de7202',
				),
				'units'                 => array(
					'px',
					'%',
				),
			),

			array(
				'id'       => 'submit_button_color',
				'type'     => 'color_group',
				'title'    => __( 'Submit Button Color', 'testimonial-pro' ),
				'subtitle' => __( 'Set color for the submit button.', 'testimonial-pro' ),
				'options'  => array(
					'color'            => esc_html__( 'Color', 'testimonial-pro' ),
					'hover-color'      => esc_html__( 'Hover Color', 'testimonial-pro' ),
					'background'       => esc_html__( 'Background', 'testimonial-pro' ),
					'hover-background' => esc_html__( 'Hover Background', 'testimonial-pro' ),
				),
				'default'  => array(
					'color'            => '#ffffff',
					'hover-color'      => '#ffffff',
					'background'       => '#005BDF',
					'hover-background' => '#005BDF',
				),
			),
			array(
				'id'       => 'form_background_color',
				'type'     => 'color',
				'title'    => __( 'Form Background', 'testimonial-pro' ),
				'subtitle' => __( 'Set testimonial form background style.', 'testimonial-pro' ),
				'default'  => '#FFFFFF',
			),
			array(
				'id'       => 'testimonial_form_border',
				'type'     => 'border',
				'title'    => __( 'Border', 'testimonial-pro' ),
				'subtitle' => __( 'Set testimonial form border.', 'testimonial-pro' ),
				'all'      => true,
				'radius'   => true,
				'default'  => array(
					'all'    => '1',
					'style'  => 'solid',
					'color'  => '#e3e3e3',
					'radius' => '6',
					'unit'   => '%',
				),
				'units'    => array(
					'px',
					'%',
				),
			),
			array(
				'id'       => 'form_shadow_type',
				'type'     => 'button_set',
				'title'    => __( 'Box-Shadow', 'testimonial-pro' ),
				'subtitle' => __( 'Choose box-shadow type for the testimonial form.', 'testimonial-pro' ),
				'options'  => array(
					'inset'  => __( 'Inset', 'testimonial-pro' ),
					'outset' => __( 'Outset', 'testimonial-pro' ),
					'none'   => __( 'None', 'testimonial-pro' ),
				),
				'default'  => 'none',
			),
			array(
				'id'         => 'testimonial_form_box_shadow_properties',
				'type'       => 'box_shadow',
				'title'      => __( 'Box-Shadow Values', 'testimonial-pro' ),
				'subtitle'   => __( 'Set box-shadow property values for the testimonial form.', 'testimonial-pro' ),
				'default'    => array(
					'horizontal' => '0',
					'vertical'   => '0',
					'blur'       => '6',
					'spread'     => '0',
					'color'      => 'rgba(10,10,10,0.1)',
				),
				'dependency' => array( 'form_shadow_type', '!=', 'none', true ),
			),
			array(
				'id'       => 'form_alignment',
				'type'     => 'button_set',
				'class'    => 'button_set_smaller',
				'title'    => __( 'Form Alignment', 'testimonial-pro' ),
				'subtitle' => __( 'Set alignment for the form.', 'testimonial-pro' ),
				'options'  => array(
					'left'   => '<i class="fa fa-align-left" title="left"></i>',
					'center' => '<i class="fa fa-align-center" title="center"></i>',
					'right'  => '<i class="fa fa-align-right" title="right"></i>',
				),
				'default'  => 'center',
			),
			array(
				'id'          => 'form_inner_padding',
				'type'        => 'spacing',
				'class'       => 'testimonial_inner_padding',
				'title'       => __( 'Padding', 'testimonial-pro' ),
				'subtitle'    => __( 'Set padding for testimonial form.', 'testimonial-pro' ),
				'default'     => array(
					'top'    => '32',
					'right'  => '32',
					'bottom' => '32',
					'left'   => '32',
				),
				'top_text'    => __( 'Top', 'testimonial-pro' ),
				'right_text'  => __( 'Right', 'testimonial-pro' ),
				'bottom_text' => __( 'Bottom', 'testimonial-pro' ),
				'left_text'   => __( 'Left', 'testimonial-pro' ),
				'unit'        => true,
				'units'       => array( 'px' ),
			),
		),
	)
);

//
// Metabox of the Testimonial.
// Set a unique slug-like ID.
//
$prefix_testimonial_opts = 'sp_tpro_meta_options';

//
// Testimonial metabox.
//
SPFTESTIMONIAL::createMetabox(
	$prefix_testimonial_opts,
	array(
		'title'           => __( 'Testimonial Options', 'testimonial-pro' ),
		'post_type'       => 'spt_testimonial',
		'context'         => 'normal',
		'enqueue_webfont' => false,
	)
);

//
// Reviewer Information section.
//
SPFTESTIMONIAL::createSection(
	$prefix_testimonial_opts,
	array(
		'title'  => __( 'Reviewer Information', 'testimonial-pro' ),
		'class'  => 'reviewer-information',
		'fields' => array(
			array(
				'id'       => 'tpro_name',
				'type'     => 'text',
				'title'    => __( 'Full Name', 'testimonial-pro' ),
				'sanitize' => 'sp_tpro_sanitize_text',
			),
			array(
				'id'       => 'tpro_email',
				'type'     => 'text',
				'title'    => __( 'E-mail Address', 'testimonial-pro' ),
				'sanitize' => 'sp_tpro_sanitize_text',
			),
			array(
				'id'       => 'tpro_designation',
				'type'     => 'text',
				'title'    => __( 'Designation', 'testimonial-pro' ),
				'sanitize' => 'sp_tpro_sanitize_text_field',
			),
			array(
				'id'       => 'tpro_company_name',
				'type'     => 'text',
				'title'    => __( 'Company Name', 'testimonial-pro' ),
				'sanitize' => 'sp_tpro_sanitize_text_field',
			),

			array(
				'id'    => 'tpro_company_logo',
				'type'  => 'media',
				'url'   => false,
				'title' => __( 'Company Logo', 'testimonial-pro' ),
			),
			array(
				'id'     => 'testimonial_clients_location',
				'class'  => 'sp-testimonial-location',
				'type'   => 'fieldset',
				'title'  => __( 'Location', 'testimonial-pro' ),
				'fields' => array(
					array(
						'id'       => 'tpro_location',
						'type'     => 'text',
						'sanitize' => 'sp_tpro_sanitize_text',
					),
					array(
						'id'      => 'tpro_country',
						'type'    => 'select',
						'options' => 'select_country',
					),
				),
			),
			array(
				'id'       => 'tpro_phone',
				'type'     => 'text',
				'title'    => __( 'Phone or Mobile', 'testimonial-pro' ),
				'sanitize' => 'sp_tpro_sanitize_text',
			),
			array(
				'id'       => 'tpro_website',
				'type'     => 'text',
				'title'    => __( 'Website', 'testimonial-pro' ),
				'sanitize' => 'sp_tpro_sanitize_text',
			),

			array(
				'id'       => 'tpro_video_url',
				'type'     => 'text',
				'title'    => __( 'Video Testimonial URL', 'testimonial-pro' ),
				'sanitize' => 'sp_tpro_sanitize_text',
			),
			array(
				'id'      => 'tpro_rating',
				'type'    => 'rating',
				'title'   => __( 'Star Rating', 'testimonial-pro' ),
				'options' => array(
					'five_star'  => __( '5 Stars', 'testimonial-pro' ),
					'four_star'  => __( '4 Stars', 'testimonial-pro' ),
					'three_star' => __( '3 Stars', 'testimonial-pro' ),
					'two_star'   => __( '2 Stars', 'testimonial-pro' ),
					'one_star'   => __( '1 Star', 'testimonial-pro' ),
				),
				'default' => '',
			),
			array(
				'id'    => 'tpro_client_checkbox',
				'class' => 'tpro_client_checkbox hidden',
				'type'  => 'checkbox',
				'title' => __( 'Checkbox', 'testimonial-pro' ),
			),
			array(
				'id'       => 'tpro_form_id',
				'class'    => 'hidden',
				'type'     => 'text',
				'default'  => '',
				'sanitize' => 'sp_tpro_sanitize_text',
			),
			array(
				'type'    => 'subheading',
				'content' => esc_html__( 'ADDITIONAL CUSTOM FIELDS', 'testimonial-pro' ),
			),
			array(
				'id'           => 'testimonial_extra_fields',
				'type'         => 'repeater',
				'title'        => 'Reviewer Custom Information',
				'class'        => 'social-profile-repeater testimonial_extra_info',
				'button_title' => esc_html__( 'Add Field', 'testimonial-pro' ),
				'sort'         => true,
				'clone'        => false,
				'remove'       => true,
				'fields'       => array(
					array(
						'id'           => 'testimonial_extra_fields_icon',
						'type'         => 'icon',
						'button_title' => esc_html__( 'Add Icon', 'testimonial-pro' ),
						'remove_title' => '<i class="fa fa-trash"></i>',
					),
					array(
						'id'          => 'testimonial_extra_fields_type',
						'type'        => 'select',
						'options'     => array(
							'text'   => esc_html__( 'Text', 'testimonial-pro' ),
							'number' => esc_html__( 'Number', 'testimonial-pro' ),
							'email'  => esc_html__( 'Email', 'testimonial-pro' ),
							'link'   => esc_html__( 'Link', 'testimonial-pro' ),
							'date'   => esc_html__( 'Date', 'testimonial-pro' ),
						),
						'placeholder' => 'Field type',
						'class'       => 'repeater-select',
					),
					array(
						'id'    => 'testimonial_extra_fields_value',
						'type'  => 'text',
						'class' => 'repeater-text',
					),
				),
				'default'      => array(
					array(),
				),
			),
			array(
				'type'    => 'subheading',
				'content' => __( 'SOCIAL MEDIA', 'testimonial-pro' ),
			),
			array(
				'id'           => 'tpro_social_profiles',
				'type'         => 'repeater',
				'button_title' => esc_html__( 'Add Social', 'testimonial-pro' ),
				'title'        => esc_html__( 'Social Profiles', 'testimonial-pro' ),
				'class'        => 'social-profile-repeater',
				'clone'        => false,
				'fields'       => array(
					array(
						'id'          => 'social_name',
						'type'        => 'select',
						'options'     => 'social_profile_name_list',
						'placeholder' => esc_html__( 'Select', 'testimonial-pro' ),
					),
					array(
						'id'    => 'social_url',
						'type'  => 'text',
						'class' => 'social-url',
					),
				),
				'default'      => array(
					array(),
				),
			),

		),
	)
);
