<?php
if (!defined('ABSPATH')) exit;

function custom_html_output() {

    $args = array(
        'post_type'      => 'stylist',
        'posts_per_page' =>  3,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'ASC',
    );
    
    $posts = get_posts($args);

    if (empty($posts)) {
        return '<p class="text-center">No stylists found.</p>';
    }

    $html  = '<div class="home-demo">';
    $html .= '<div class="owl-carousel home-carousel owl-theme">';

    foreach ($posts as $post) {

        $postId        = $post->ID;
        $profile_image = get_the_post_thumbnail_url($postId, 'thumbnail');
        if (empty($profile_image)) {
            $profile_image = home_url('/wp-content/uploads/2026/01/imageprofile.png');
        }
        $postTitle     = get_the_title($postId);
        $bio           = get_post_meta($postId, 'short_bio', true);
        $specialization = get_post_meta($postId, 'specialization', true);

        $html .= '
        <div class="item">
            <div class="testimonial-card">
                <div class="d-flex align-items-start justify-content-center flex-column flex mb-3 textBox">
                    
                    <img src="/wp-content/uploads/2026/02/Union.png" class="quoteImg"/>

                    <p class="testimonial-text">"' . esc_html($bio) . '"</p> 
                </div>
 
                <div class="d-flex align-items-center justify-content-center flex-row mb-3"> 
                    <img src="' . esc_url($profile_image) . '" class="profile-img" alt="' . esc_attr($postTitle) . '">
                    <div class="user-info">
                        <h5>' . esc_html($postTitle) . '</h5>
                        <span>' . esc_html($specialization) . ' <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div></span>
                    </div>
                </div>
            </div> 
        </div>';
    }

    $html .= '</div></div>';

    return $html; 
}
add_shortcode('my_html_shortcode', 'custom_html_output');

// featured_salon_suites
function featured_salon_suites($atts) {
    

    $atts = shortcode_atts(
        array(
            'limit' => -1,
        ),
        $atts,
        'featured_suites'
    );

    $limit = intval($atts['limit']);

    $posts = get_posts(array(
        'post_type'      => 'luxury_suites',
        'posts_per_page' => $limit,
        'post_status'    => 'publish',
        'meta_query'     => array(
            array(
                'key'     => 'marked_as_featured',
                'value'   => '1',
                'compare' => '='
            )
        ),
        'orderby' => 'date',
        'order'   => 'ASC'
    ));

    if (empty($posts)) {
        return '';
    }

    $total_posts = count($posts);
    $use_slider = $total_posts > 5;

    ob_start();
    ?>

    <div class="container-fluid p-0">

        <?php if ($use_slider): ?>
        <div class="swiper suiteSwiper">
            <div class="swiper-wrapper">
        <?php else: ?>
        <div class="row g-4 suite-grid-center">
        <?php endif; ?>

            <?php foreach ($posts as $post) : setup_postdata($post); ?>

                <?php
                    $availability = get_post_meta($post->ID, 'availability', true);
                    $suiteVideoId = get_post_meta($post->ID, 'suite_video', true);
                    $suiteVideoUrl = '';
                    $thumbnail = get_the_post_thumbnail_url($post->ID, 'full');
                    $beforeImageId = absint(get_post_meta($post->ID, 'before_image_thumbnail', true));
                    $price = get_post_meta($post->ID, 'price', true);
                    $size = get_post_meta($post->ID, 'size', true);
                    $amenities = get_post_meta($post->ID, 'amenities', true);
                    $amenities = maybe_unserialize($amenities);
                    $tourLink = get_post_meta($post->ID, 'tour_link', true);
                    $suiteNumber = trim((string) get_post_meta($post->ID, 'suite_number', true));
                    $suiteNumberAttr = esc_attr($suiteNumber);

                    if (!empty($suiteVideoId) && is_numeric($suiteVideoId)) {
                        $suiteVideoUrl = wp_get_attachment_url($suiteVideoId);
                    }

                    $hasSuiteMedia = !empty($suiteVideoUrl) || !empty($thumbnail);
                ?>

                <div class="<?php echo $use_slider ? 'swiper-slide' : 'col-md-12 col-lg-2 col-sm-6'; ?>">

                    <div class="card service-card">

                        <?php if ( '1' === $availability ) : ?>
                            <div class="tag"><?php esc_html_e( 'Available', 'custom-widget' ); ?></div>
                        <?php endif; ?>

                        <?php if ($hasSuiteMedia) : ?>
                            <?php
                            $suite_image_wrap_tag = ( '1' === $availability )
                                ? 'a'
                                : 'div';
                            $suite_image_wrap_attrs = ( '1' === $availability )
                                ? ' href="#signup-a-suite-modal" class="suite-image-wrap suite-image-wrap--signup-link js-open-signup-a-suite-modal featured-suite-trigger" data-signup-discount="" data-suite-no="' . $suiteNumberAttr . '"'
                                : ' class="suite-image-wrap" data-suite-no="' . $suiteNumberAttr . '"';
                            ?>
                            <<?php echo $suite_image_wrap_tag . $suite_image_wrap_attrs; ?>>
                                <?php if (!empty($suiteVideoUrl)) : ?>

                                    <div class="video-card" data-video="<?php echo esc_url($suiteVideoUrl); ?>">
                                        <div class="video-thumbnail" style="background-image: url('<?php echo esc_url($thumbnail); ?>');">
                                            <div class="play-button">▶</div>
                                        </div>
                                    </div>

                                <?php elseif ($thumbnail) : ?>

                                    <img src="<?php echo esc_url($thumbnail); ?>"
                                         class="card-img-top"
                                         alt="<?php echo esc_attr($post->post_title); ?>">

                                <?php endif; ?>

                                <?php
                                $suite_before_overlay_html = custom_functions_get_suite_before_overlay_image_html($beforeImageId);
                                if ($suite_before_overlay_html !== '') :
                                ?>
                                    <div class="suite-before-overlay" aria-hidden="true">
                                        <?php echo $suite_before_overlay_html; ?>
                                    </div>
                                <?php endif; ?>
                            </<?php echo $suite_image_wrap_tag; ?>>
                        <?php endif; ?>

                        <div class="card-body">
                            <h5 class="card-title">
                                <?php echo esc_html($post->post_title); ?>
                                <span>
                                    <?php echo !empty($price) ? '$'.$price.'/month' : 'N/A'; ?>
                                </span>
                            </h5>

                            <ul>
                                <li>
                                    <img src="/wp-content/uploads/2026/04/green.png" />
                                    <?php echo !empty($size) ? 'Approx. '.$size.' sq ft' : 'N/A'; ?>
                                </li>

                                <?php if (!empty($amenities) && is_array($amenities)) : ?>
                                    <?php foreach ($amenities as $data) : ?>
                                        <li>
                                            <img src="/wp-content/uploads/2026/04/green.png" />
                                            <?php echo esc_html($data); ?>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>

                           <a
                                href="#signup-a-suite-modal"
                                class="view-details js-open-signup-a-suite-modal featured-suite-trigger"
                                data-signup-discount=""
                                data-suite-no="<?php echo $suiteNumberAttr; ?>"
                            >
                                <?php esc_html_e( 'Learn More', 'custom-widget' ); ?>
                            </a>
                        </div>

                    </div>

                </div>

            <?php endforeach; wp_reset_postdata(); ?>

        <?php if ($use_slider): ?>
            </div>

            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
            <!--<div class="swiper-pagination"></div>-->

        </div>
        <?php else: ?>
        </div>
        <?php endif; ?>

    </div>
    


    <!-- VIDEO MODAL -->
    <div id="videoModal" class="video-modal">
        <div class="video-modal-content">
            <span class="close-video">&times;</span>
            <video controls autoplay id="modalVideo">
                <source src="" type="video/mp4">
            </video>
        </div>
    </div>

    <?php if ($use_slider): ?>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        new Swiper(".suiteSwiper", {
            slidesPerView: 5,
            spaceBetween: 20,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                320: { slidesPerView: 1 },
                576: { slidesPerView: 2 },
                768: { slidesPerView: 2 },
                1024: { slidesPerView: 5 }
            }
        });
    });
    </script>
    <?php endif; 

    return ob_get_clean();
}
add_shortcode('featured_suites', 'featured_salon_suites');


// Suite Rentals
function suites_rentals_shortcode($atts) {

    $atts = shortcode_atts(
        array(
            'limit' => -1,
        ),
        $atts,
        'featured_suites'
    );

    $limit = intval($atts['limit']);

    $posts = get_posts(array(
        'post_type'      => 'luxury_suites',
        'posts_per_page' => $limit,
        'post_status'    => 'publish',
        'meta_query'     => array(
            array(
                'key'     => 'marked_as_featured',
                'value'   => '1',
                'compare' => '='
            )
        ),
        'orderby' => 'date',
        'order'   => 'ASC'
    ));

    if (empty($posts)) {
        return '';
    }

    $total_posts = count($posts);
    $use_slider = $total_posts > 5;

    ob_start();
    ?>

    <div class="container-fluid p-0">

        <?php if ($use_slider): ?>
        <div class="swiper suiteSwiper">
            <div class="swiper-wrapper">
        <?php else: ?>
        <div class="row g-4 suite-grid-center">
        <?php endif; ?>

            <?php foreach ($posts as $post) : setup_postdata($post); ?>

                <?php
                    $availability = get_post_meta($post->ID, 'availability', true);
                    $suiteVideoId = get_post_meta($post->ID, 'suite_video', true);
                    $suiteVideoUrl = '';
                    $thumbnail = get_the_post_thumbnail_url($post->ID, 'full');
                    $beforeImageId = absint(get_post_meta($post->ID, 'before_image_thumbnail', true));
                    $price = get_post_meta($post->ID, 'price', true);
                    $size = get_post_meta($post->ID, 'size', true);
                    $amenities = get_post_meta($post->ID, 'amenities', true);
                    $amenities = maybe_unserialize($amenities);
                    $tourLink = get_post_meta($post->ID, 'tour_link', true);

                    if (!empty($suiteVideoId) && is_numeric($suiteVideoId)) {
                        $suiteVideoUrl = wp_get_attachment_url($suiteVideoId);
                    }

                    $hasSuiteMedia = !empty($suiteVideoUrl) || !empty($thumbnail);
                ?>

                <div class="<?php echo $use_slider ? 'swiper-slide' : 'col-md-12 col-lg-2 col-sm-6'; ?>">

                    <div class="card service-card">

                        <?php if($availability === '1'): ?>
                            <div class="tag">Available</div>
                        <?php endif; ?>

                        <?php if ($hasSuiteMedia) : ?>
                            <div class="suite-image-wrap">
                                <?php if (!empty($suiteVideoUrl)) : ?>

                                    <div class="video-card" data-video="<?php echo esc_url($suiteVideoUrl); ?>">
                                        <div class="video-thumbnail" style="background-image: url('<?php echo esc_url($thumbnail); ?>');">
                                            <div class="play-button">▶</div>
                                        </div>
                                    </div>

                                <?php elseif ($thumbnail) : ?>

                                    <img src="<?php echo esc_url($thumbnail); ?>"
                                         class="card-img-top"
                                         alt="<?php echo esc_attr($post->post_title); ?>">

                                <?php endif; ?>

                                <?php
                                $suite_before_overlay_html = custom_functions_get_suite_before_overlay_image_html($beforeImageId);
                                if ($suite_before_overlay_html !== '') :
                                ?>
                                    <div class="suite-before-overlay" aria-hidden="true">
                                        <?php echo $suite_before_overlay_html; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <div class="card-body">
                            <h5 class="card-title">
                                <?php echo esc_html($post->post_title); ?>
                                <span>
                                    <?php echo !empty($price) ? '$'.$price.'/month' : 'N/A'; ?>
                                </span>
                            </h5>

                            <ul>
                                <li>
                                    <img src="/wp-content/uploads/2026/04/green.png" />
                                    <?php echo !empty($size) ? 'Approx. '.$size.' sq ft' : 'N/A'; ?>
                                </li>

                                <?php if (!empty($amenities) && is_array($amenities)) : ?>
                                    <?php foreach ($amenities as $data) : ?>
                                        <li>
                                            <img src="/wp-content/uploads/2026/04/green.png" />
                                            <?php echo esc_html($data); ?>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>

                            <a href="<?php echo (!empty($tourLink)) ? htmlspecialchars($tourLink) : 'javascript:void(0)'; ?>" class="view-details">View Details</a>
                        </div>

                    </div>

                </div>

            <?php endforeach; wp_reset_postdata(); ?>

        <?php if ($use_slider): ?>
            </div>

            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
            <!--<div class="swiper-pagination"></div>-->

        </div>
        <?php else: ?>
        </div>
        <?php endif; ?>

    </div>

    <!-- VIDEO MODAL -->
    <div id="videoModal" class="video-modal">
        <div class="video-modal-content">
            <span class="close-video">&times;</span>
            <video controls autoplay id="modalVideo">
                <source src="" type="video/mp4">
            </video>
        </div>
    </div>

    <?php if ($use_slider): ?>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        new Swiper(".suiteSwiper", {
            slidesPerView: 5,
            spaceBetween: 20,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                320: { slidesPerView: 1 },
                576: { slidesPerView: 2 },
                768: { slidesPerView: 2 },
                1024: { slidesPerView: 5 }
            }
        });
    });
    </script>
    <?php endif; 

    return ob_get_clean();
}
add_shortcode('suites_rentals', 'suites_rentals_shortcode');



function meet_stylists_shortcode() {

    $keyword  = isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : '';
    $location = isset($_GET['stylist_location']) ? sanitize_text_field($_GET['stylist_location']) : '';
    $service  = isset($_GET['stylist_service']) ? sanitize_text_field($_GET['stylist_service']) : '';

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    $args = array(
        'post_type'      => 'stylist', 
        'posts_per_page' => 5,
        'paged'          => $paged,
    );
    
    $query = new WP_Query($args);
    
            if (!empty($keyword)) {
            //$args['s'] = $keyword;
        
            $args['meta_query'][] = array(
                'relation' => 'OR',
                array(
                    'key' => 'business_name',
                    'value' => $keyword,
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => 'city',
                    'value' => $keyword,
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => 'state',
                    'value' => $keyword,
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => 'specialization',
                    'value' => $keyword,
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => 'suite',
                    'value' => $keyword,
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => 'experience',
                    'value' => $keyword,
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => 'short_bio',
                    'value' => $keyword,
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => 'contact',
                    'value' => $keyword,
                    'compare' => 'LIKE',
                ),
            );
        }

    
    // Taxonomy filters
    $tax_query = array();
    
    // Service filter
    if (!empty($service)) {
        $tax_query[] = array(
            'taxonomy' => 'stylist_service',
            'field'    => 'slug',
            'terms'    => $service,
        );
    }
    
    // Location filter (NOW SAME AS SERVICE)
    if (!empty($location)) {
        $tax_query[] = array(
            'taxonomy' => 'locations', // <-- IMPORTANT (your location taxonomy)
            'field'    => 'slug',
            'terms'    => $location,
        );
    }
    
    // Apply tax_query if exists
    if (!empty($tax_query)) {
        $tax_query['relation'] = 'AND';
        $args['tax_query'] = $tax_query;
    }
    
    // Fix meta_query relation
    if (!empty($args['meta_query'])) {
        $args['meta_query']['relation'] = 'AND';
    }
    
    $query = new WP_Query($args);



    if (!$query->have_posts()) {
        return '<p class="text-center">No stylists found.</p>';
    }

    ob_start();
    
    while ($query->have_posts()) : $query->the_post();
        $postId = '';
        $profile_image = '';
        $postTitle = '';
        $bussinessName = '';
        $specialization = '';
        $contact = '';
        $formatted_contact = '';
        $appointmentTypeArray = '';
        $city = '';
        $state = '';
        $suite = '';
        $services = '';
        $bookingUrl = '';
        $instagramUrl = '';
        $facebookUrl = '';
        $stylistSuit = '';
        $postId = get_the_ID();
        $profile_image = get_the_post_thumbnail_url($postId, 'thumbnail');
        $postTitle = get_the_title();
        $bussinessName = get_post_meta($postId, 'business_name', true);
        $specialization = get_post_meta($postId, 'specialization', true);
        $contact = get_post_meta($postId, 'contact', true);
        $appointmentTypeArray = get_post_meta($postId, 'appointment_type', true);
        $appointmentType = '';
        if (is_array($appointmentTypeArray) && !empty($appointmentTypeArray[0])) {
            $appointmentType = $appointmentTypeArray[0];
        }
     
        $city = get_post_meta($postId, 'city', true);
        $state = get_post_meta($postId, 'state', true);
        $suite = get_post_meta($postId, 'suite', true);
        $services = get_the_terms($postId, 'stylist_service');
        $parent_terms = array();
        $bookingUrl = get_post_meta($postId, 'booking_link', true);
        $instagramUrl = get_post_meta($postId, 'instagram', true);
        $facebookUrl = get_post_meta($postId, 'facebook', true);
        if(!empty($suite)){
            $stylistSuit = 'Suite'. ' ' .$suite;
        }
        if($state === 'S'){
           $state = ''; 
        }else{
           $state = ', '.$state; 
        }
        if($contact){
                $formatted_contact = sprintf(
                    "(%s) %s-%s",
                    substr($contact,0,3),
                    substr($contact,3,3),
                    substr($contact,6)
                ); 
            }
        if (!empty($services) && !is_wp_error($services)) {
            foreach ($services as $service) {
                if ($service->parent == 0) {
                    $parent_terms[] = $service;
                }
            }
        }
    ?> 
    <div class="container" style="max-width: 768px;">
        <div class="salon-list-item p-4 shadow-sm d-flex align-items-center flex-wrap flex-md-nowrap">
            
            <div class="me-4 mb-3 mb-md-0">
                <div class="profile-thumb bg-secondary-subtle d-flex align-items-center justify-content-center">
                    <?php if ($profile_image): ?>
                        <img src="<?php echo esc_url($profile_image); ?>" alt="Profile Image" class="img-fluid">
                    <?php else: ?> 
                        <img src="<?php echo esc_url('/wp-content/uploads/2026/01/imageprofile.png'); ?>" alt="Profile Image" class="img-fluid">
                    <?php endif; ?>
                    <?php if(!empty($appointmentType)){
                    ?>
                        <p class="appointment"><?php echo $appointmentType; ?></p>
                        <?php
                    }
                    ?>
                    
                </div>
            </div>
    
            <div class="flex-grow-1 me-md-4">
                <h5 class="fw-bold mb-0 mt-0"><?php echo !empty($postTitle) ? $postTitle : 'N/A'; ?></h5>
                <div class="mb-2 text-warning"><i class="bi bi-star"></i><i class="bi bi-star"></i> <i class="bi bi-star"></i><i class="bi bi-star"></i><i class="bi bi-star"></i><span class="rating small">(4.9 | 157 reviews)</span></div>
                <p class="text-muted small mb-0 mt-2">
                    <p class="stylist-info"><i class="bi bi-person"></i> <?php echo !empty($specialization) ? $specialization : 'N/A';?></p>
                    <p><i class="bi bi-building"></i> <?php echo !empty($stylistSuit) ? $stylistSuit : 'N/A'; ?></p>
                    <p><i class="bi bi-geo-alt"></i> <?php echo !empty($bussinessName && $city && $state) ? $bussinessName.' - '.$city.$state : 'N/A';?></p>
                    <p><i class="bi bi-phone"></i> <?php echo !empty($formatted_contact) ? $formatted_contact : 'N/A'; ?></p>
                </p>
                <div class="d-flex gap-2 mb-4 justify-content-start">
                <?php
                if(!empty($bookingUrl)){
                    ?>
                    <a href="<?php echo $bookingUrl; ?>" target="_blank" class="btn btn-book-sm shadow-sm">Book Now</a>
                    <?php
                }else if(!empty($instagramUrl)){
                    ?>
                    <a href="<?php echo $instagramUrl; ?>" target="_blank" class="btn btn-book-sm shadow-sm">Instagram</a>
                    <?php
                }else if(!empty($facebookUrl)){
                    ?>
                    <a href="<?php echo $facebookUrl; ?>" target="_blank" class="btn btn-book-sm shadow-sm">Facebook</a>
                    <?php
                }
                ?>
                <a href="<?php echo site_url('/stylist-profile/?stylist_id=' . $postId); ?>" class="btn btn-outline-profile btn-sm">View Profile</a>
                </div>
            </div>
        </div>
    </div>
    <?php
    endwhile;?>
    <div class="stylists-pagination-wrapper text-center my-4">
    <?php
    echo paginate_links(array(
        'total'     => $query->max_num_pages,
        'current'   => max(1, get_query_var('paged')),
        'prev_text' => __('« Prev'),
        'next_text' => __('Next »'),
        'type'      => 'list',
    ));
    ?>
</div>
<script>
jQuery(function ($) {

    if ($('body').hasClass('page-id-12')) {

        function cleanURL() {

            if (window.location.search.length > 0) {

                var cleanUrl = window.location.origin + window.location.pathname;

                window.history.replaceState(null, '', cleanUrl);
            }
        }

        // Run immediately
        cleanURL();

        // Run again after full load (important for WordPress/forms)
        $(window).on('load', function () {
            setTimeout(cleanURL, 500);
        });

    }

});
</script>
<?php
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('meet_stylists', 'meet_stylists_shortcode');

/** Meet Stylist home **/
function meet_stylists_home_shortcode() {

    $search_term = isset($_GET['stylist_search']) ? sanitize_text_field($_GET['stylist_search']) : '';

    $args = array(
        'post_type'      => 'stylist',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
        'meta_query'     => array(
            array(
                'key'     => 'marked_as_featured',
                'value'   => '1',
                'compare' => '='
            )
        )
    );

    if (!empty($search_term)) {

        $matching_terms = get_terms(array(
            'taxonomy'   => 'stylist_service',
            'hide_empty' => false,
            'search'     => $search_term,
        ));

        $term_ids = array();

        if (!empty($matching_terms) && !is_wp_error($matching_terms)) {
            $term_ids = wp_list_pluck($matching_terms, 'term_id');
        }

        $args['s'] = $search_term;

        $args['meta_query'][] = array(
            'relation' => 'OR',
            array('key' => 'business_name', 'value' => $search_term, 'compare' => 'LIKE'),
            array('key' => 'city', 'value' => $search_term, 'compare' => 'LIKE'),
            array('key' => 'state', 'value' => $search_term, 'compare' => 'LIKE'),
        );

        if (!empty($term_ids)) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'stylist_service',
                    'field'    => 'term_id',
                    'terms'    => $term_ids,
                )
            );
        }
    }

    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        return '<p class="text-center">No stylists found.</p>';
    }

    $total_posts = $query->post_count;
    $use_slider = $total_posts > 5;

    ob_start();
    ?>

    <div class="container-fluid">

        <?php if ($use_slider): ?>
            <div class="swiper stylistSwiper">
                <div class="swiper-wrapper">
        <?php else: ?>
            <div class="stylist-grid-center">
        <?php endif; ?>

        <?php while ($query->have_posts()) : $query->the_post();

            $postId = get_the_ID();
            $profile_imageId = get_post_meta($postId, '_thumbnail_id', true);
            $profile_image = wp_get_attachment_image_url($profile_imageId, 'medium');

            $postTitle = get_the_title();
            $bussinessName = get_post_meta($postId, 'business_name', true);
            $specialization = get_post_meta($postId, 'specialization', true);
            $spec_array = array_map('trim', explode(',', $specialization));
            $limited_specs = array_slice($spec_array, 0, 2);
            $contact = get_post_meta($postId, 'contact', true);
            $appointmentTypeArray = get_post_meta($postId, 'appointment_type', true);
            $appointmentType = (is_array($appointmentTypeArray) && !empty($appointmentTypeArray[0])) ? $appointmentTypeArray[0] : '';
            $city = get_post_meta($postId, 'city', true);
            $state = get_post_meta($postId, 'state', true);
            $suite = get_post_meta($postId, 'suite', true);
            $bookingUrl = get_post_meta($postId, 'booking_link', true);
            $instagramUrl = get_post_meta($postId, 'instagram', true);
            $facebookUrl = get_post_meta($postId, 'facebook', true);

            $stylistSuit = !empty($suite) ? 'Suite ' . $suite : '';

        ?>

            <div class="<?php echo $use_slider ? 'swiper-slide' : 'stylist-item'; ?>">

                <div class="salon-list-item p-4 shadow-sm d-flex align-items-start flex-wrap h-100">

                    <div class="profile-thumb">
                        <img src="<?php echo esc_url($profile_image ?: '/wp-content/uploads/2026/01/imageprofile.png'); ?>">
                    </div>

                    <div class="flex-grow-1 p-3">
                        <div class="stylistData">
                            <h5 class="fw-bold mb-0">
                                <?php echo esc_html($postTitle); ?>
                                <span><?php echo esc_html($stylistSuit); ?></span>
                            </h5>
                            <p class="text-muted small mb-0 mt-2">
                                <span class="stylist-info"><img src="/wp-content/uploads/2026/04/vecteezy_user-icon-on-transparent-background_19879186-1.png" /> <?php echo implode(', ', $limited_specs);; ?><br>
                                <img src="/wp-content/uploads/2026/04/location-2952_256-2.png" />
                                <?php echo !empty($bussinessName) ? $bussinessName : 'N/A'; ?>
                                <?php if (!empty($bussinessName && $city)) : ?> &bull; <?php echo $city; endif; ?>
                                <?php if (!empty($bussinessName && $city && $state)) : ?> &bull; <?php echo $state; endif; ?>
                            </p>
                            
                        </div>

                   

                        <div class="d-flex gap-2 mt-3">

                            <?php if (!empty($bookingUrl)) : ?>
                                <a href="<?php echo esc_url($bookingUrl); ?>" class="btn btn-book-sm">Book Now</a>
                            <?php elseif (!empty($instagramUrl)) : ?>
                                <a href="<?php echo esc_url($instagramUrl); ?>" class="btn btn-book-sm">Instagram</a>
                            <?php elseif (!empty($facebookUrl)) : ?>
                                <a href="<?php echo esc_url($facebookUrl); ?>" class="btn btn-book-sm">Facebook</a>
                            <?php endif; ?>

                            <a href="<?php echo site_url('/stylist-profile/?stylist_id=' . $postId); ?>"
                               class="btn btn-outline-profile btn-sm">
                                View Profile
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        <?php endwhile; wp_reset_postdata(); ?>

        <?php if ($use_slider): ?>
                </div>

                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <!--<div class="swiper-pagination"></div>-->

            </div>
        <?php else: ?>
            </div>
        <?php endif; ?>

    </div>

    <?php if ($use_slider): ?>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        new Swiper(".stylistSwiper", {
            slidesPerView: 5,
            spaceBetween: 20,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true
            },
            breakpoints: {
                320: { slidesPerView: 1 },
                576: { slidesPerView: 2 },
                768: { slidesPerView: 3 },
                1024: { slidesPerView: 5 }
            }
        });
    });
    </script>
    <?php endif; ?>

    <?php
    return ob_get_clean();
}
add_shortcode('meet_stylists_home', 'meet_stylists_home_shortcode');



// Resource page shortcode
function resources_page_shortcode() {
    ob_start();
    ?>

    <style>
    .combined-card {
        background: white;
        width: 100%;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
        margin: 30px auto;
    }

    .card-header {
        padding: 40px;
        text-align: left;
        border-bottom: 1px solid #eee;
        padding-top: 0;
    }

    .card-header h1 {
        margin-bottom: 10px;
    }

    .media-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        padding: 30px;
        background: #fafafa;
    }

    .media-item {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
    }

    .media-container {
        height: 160px;
        background: #000;
    }

    .media-container img,
    .media-container video,
    .media-container iframe {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .item-details {
        padding: 15px;
    }

    .card-footer {
        text-align: center;
        padding: 20px;
    }
    </style>

    <h2 class="customTitle">Resources</h2>

    <?php
    $resources = new WP_Query(array(
        'post_type' => 'resources',
        'posts_per_page' => -1
    ));

    if ($resources->have_posts()) :
        while ($resources->have_posts()) : $resources->the_post();
    ?>

    <div class="combined-card">

        <!-- Header -->
        
        <!-- Media Grid -->
        <div class="media-grid">

            <!-- Thumbnail -->
            <?php if (has_post_thumbnail()) : ?>
            <div class="media-item">
                <div class="media-container">
                    <?php the_post_thumbnail('medium'); ?>
                </div>
                <div class="item-details">
                    <h4><?php the_title(); ?></h4>
                </div>
            </div>
            <?php endif; ?>

            <!-- Video -->
            <?php
            $video_type = get_field('resource_video_type');

            if ($video_type == 'url') :
            ?>
                <div class="media-item">
                    <div class="media-container">
                        <?php echo get_field('resource_video_url'); ?>
                    </div>
                    <div class="item-details">
                        <h4>Video</h4>
                    </div>
                </div>

            <?php elseif ($video_type == 'upload') :
                $video_file = get_field('resource_video_file');
                if ($video_file) :
            ?>
                <div class="media-item">
                    <div class="media-container">
                        <video controls>
                            <source src="<?php echo esc_url($video_file); ?>" type="video/mp4">
                        </video>
                    </div>
                    <div class="item-details">
                        <h4>Video</h4>
                    </div>
                </div>
            <?php endif; endif; ?>

            <!-- Document -->
            <?php
            $file = get_field('resource_file');
            if ($file) :
            ?>
            <div class="media-item">
                <div class="media-container" style="display:flex;align-items:center;justify-content:center;background:#f1f1f1;">
                    ðŸ“„
                </div>
                <div class="item-details">
                    <h4>Document</h4>
                    <a href="<?php echo esc_url($file); ?>" target="_blank">Download</a>
                </div>
            </div>
            <?php endif; ?>

        </div>
        
        <div class="card-header">
            <h1><?php the_title(); ?></h1>
            <p><?php the_content(); ?></p>
        </div>

    </div>

    <?php
        endwhile;
        wp_reset_postdata();
    else :
        echo "<p>No resources found.</p>";
    endif;

    return ob_get_clean();
}
add_shortcode('resources_page', 'resources_page_shortcode');

function video_gallery_slider() {
    ob_start();
    ?>

    <style>
    #sync2 .owl-item.current {
        border: 3px solid #000;
    }
    .owl-prev, .owl-next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0,0,0,0.6);
        color: #fff;
        padding: 10px 14px;
        border-radius: 50%;
        font-size: 18px;
        z-index: 10;
    }
    .owl-prev { left: 15px; }
    .owl-next { right: 15px; }

    video {
        width: 100%;
        height: auto;
    }
    </style>

    <?php

    // Optional: taxonomy filters (if you have categories for videos)
    $terms = get_terms(array(
        'taxonomy' => 'video_category', // change if needed
        'hide_empty' => true,
    ));

    ?>

    <!-- MAIN SLIDER -->
    <div id="sync1" class="owl-carousel owl-theme">

    <?php
    $query = new WP_Query(array(
        'post_type' => 'videos',
        'posts_per_page' => -1
    ));

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();

            // Categories (optional)
            $terms = get_the_terms(get_the_ID(), 'video_category');
            $cat_slugs = '';

            if (!empty($terms) && !is_wp_error($terms)) {
                $cat_slugs = implode(',', wp_list_pluck($terms, 'slug'));
            }

            $video_type = get_field('video_type');

            echo '<div class="item" data-category="'.$cat_slugs.'">';

            if ($video_type == 'url') {

                // YouTube / Vimeo embed
                echo '<div class="video-wrapper">';
                echo get_field('video_url');
                echo '</div>';

            } elseif ($video_type == 'upload') {

                $video_file = get_field('video_file');

                if ($video_file) {
                    ?>
                    <video controls>
                        <source src="<?php echo esc_url($video_file); ?>" type="video/mp4">
                    </video>
                    <?php
                }
            }

            echo '</div>';

        endwhile;
        wp_reset_postdata();
    endif;
    ?>

    </div>

    <!-- THUMBNAIL SLIDER -->
    <div id="sync2" class="owl-carousel owl-theme">

    <?php
    $query = new WP_Query(array(
        'post_type' => 'videos',
        'posts_per_page' => -1
    ));

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();

            echo '<div class="item">';

            // Use featured image as thumbnail
            if (has_post_thumbnail()) {
                the_post_thumbnail('thumbnail', ['class' => 'img-fluid']);
            } else {
                echo '<p>No Thumbnail</p>';
            }

            echo '</div>';

        endwhile;
        wp_reset_postdata();
    endif;
    ?>

    </div>

    <?php

    return ob_get_clean();
}
add_shortcode('videoGallerySlider', 'video_gallery_slider');

// Services Shortcode
function stylist_services_shortcode() {
    $taxonomy = 'stylist_service';
    $parent_terms = get_terms([
        'taxonomy'   => $taxonomy,
        'parent'     => 0,
        'hide_empty' => false,
    ]);

    if (is_wp_error($parent_terms) || empty($parent_terms)) {
        return '<p>No services found.</p>';
    }

    // Find the first term with posts
    $active_set = false;
    ob_start();
    ?>
    <div class="container-fluid">
        <ul id="pills-tab" class="nav nav-pills nav-center" role="tablist">
            <?php foreach ($parent_terms as $term): 
                $slug = esc_attr($term->slug);

                // Check if term has posts
                $posts_count = new WP_Query([
                    'post_type' => 'luxury_suites',
                    'post_status' => 'publish',
                    'tax_query' => [
                        [
                            'taxonomy' => $taxonomy,
                            'field'    => 'slug',
                            'terms'    => $slug,
                        ],
                    ],
                    'posts_per_page' => 1, // Only need to know if there is at least 1 post
                ]);

                $active = '';
                if (!$active_set && $posts_count->have_posts()) {
                    $active = 'activeNav';
                    $active_set = true;
                }
                wp_reset_postdata();
            ?>
                <li class="nav-item" role="presentation">
                    <button
                        id="<?php echo $slug; ?>-tab"
                        class="nav-link <?php echo $active; ?>"
                        role="tab"
                        data-service="<?php echo $slug; ?>"
                        type="button"
                        data-bs-toggle="pill"
                        data-bs-target="#<?php echo $slug; ?>"
                    >
                        <?php echo esc_html($term->name); ?>
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('stylist_services', 'stylist_services_shortcode');

// notice shortcode
function my_dynamic_notice_shortcode() {
    // $notice = get_option('my_single_notice');
    $notice = get_option( 'my_single_notice' );

    if ( function_exists( 'legacy_apply_notice_discount_placeholders' ) ) {
        $notice = legacy_apply_notice_discount_placeholders( $notice );
    }

    if ( ! empty( $notice ) ) {
        return esc_html( $notice );
    }

    return '';
}
add_shortcode('my_dynamic_notice', 'my_dynamic_notice_shortcode');

// Contact page content
function contact_content_shortcode(){
    $contact_content = get_page_by_title('Contact Us', OBJECT, 'page_content');
    if ($contact_content) {
        $postId = $contact_content->ID;
        $contact = get_post_meta($postId, 'contact_no', true);
        $contactDisc = get_post_meta($postId, 'contact_discription', true);
        $email = get_post_meta($postId, 'email', true);
        $emailDisc = get_post_meta($postId, 'email_discription', true);
       
    }
    
    $args = array(
        'post_type'      => 'our_locations',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'order'          => 'DESC',
    );
    
    $query = new WP_Query($args);
    
    $location_count = $query->found_posts;
    
    
    ob_start();
    ?>

    <div class="contact-cards">
        <!-- Call Us -->
        <?php if (!empty($contact)) : ?>
            <div class="contact-card">
                <div class="icon">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <h4>Call Us</h4>
                <p class="highlight">
                    <a href="tel:+1<?php echo $contact; ?>" style="text-decoration:none; color:black;"><?php echo $contact; ?></a>
                </p>
                <p class="small-text"><?php echo $contactDisc; ?></p>
            </div>
        <?php endif; ?>

        <!-- Email Us -->
        <?php if (!empty($email)) : ?>
            <div class="contact-card">
                <div class="icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h4>Email Us</h4>
                <p class="highlight">
                    <a href="mailto:<?php echo esc_attr($email); ?>" style="text-decoration:none; color:black;"><?php echo esc_html($email); ?></a>
                </p>
                <p class="small-text"><?php echo $emailDisc; ?></p>
            </div>
        <?php endif; ?>

        <!-- Visit Us (Always Visible) -->
        <div class="contact-card">
            <div class="icon">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <h4>Visit Us</h4>
            <p class="highlight" style="color:black;"><?php echo $location_count; ?> Locations</p>
            <p class="small-text">See locations below</p>
        </div>
    </div>

    <?php
    return ob_get_clean();
     
}
add_shortcode('contact_content', 'contact_content_shortcode');

// Our Locations shortcode
function our_locations_shortcode(){
    ?>
    <style>
    .locations-wrapper{
        display:grid;
        grid-template-columns:repeat(3,1fr);
        gap:30px;
    }
    </style>
    <?php
    $args = array(
        'post_type'      => 'our_locations',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'order'          => 'DESC',
    );
    $query = new WP_Query($args);

    ob_start();
    if($query->have_posts()){
        echo '<div class="locations-wrapper">';
        while($query->have_posts()){
            $query->the_post();
            $post_id = get_the_ID();
            $title   = get_the_title();
            $street = get_post_meta($post_id,'street_address',true);
            $suite = get_post_meta($post_id,'suite__unit',true);
            $city = get_post_meta($post_id,'city',true);
            $state = get_post_meta($post_id,'state',true);
            $zipCode = get_post_meta($post_id,'zip_code',true);
            $country = get_post_meta($post_id,'country',true);
            $contact = get_post_meta($post_id,'contact',true);
            $formatted_contact = '';
            if($contact){
                $formatted_contact = sprintf(
                    "(%s) %s-%s",
                    substr($contact,0,3),
                    substr($contact,3,3),
                    substr($contact,6)
                );
            }
            $address = $street;
            if($suite){
                $address .= ' ' . $suite;
            }
            $address .= ', ' . $city . ', ' . $state . ' ' . $zipCode;
            $map_link = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($address);

            ?>
            <div class="location-card">

                <h3><?php echo esc_html($title); ?></h3>
            
                <div class="info-row">
                    <i class="fa-solid fa-location-dot"></i>
                    <div class="info-text">
                        <?php echo esc_html($address); ?>
                    </div>
                </div>
            
                <div class="info-row">
                    <i class="fa-solid fa-phone"></i>
                    <div class="info-text">
                        <a style="text-decoration: none;" href="tel:<?php echo esc_attr($contact); ?>">
                            <?php echo esc_html($formatted_contact); ?>
                        </a>
                    </div>
                </div>
            
                <?php 
                $map_link = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($address);
                ?>
            
                <a class="btn btn-directions" style="text-decoration: none;" href="<?php echo esc_url($map_link); ?>" target="_blank">
                    Get Directions
                </a>
            
            </div>

            <?php
        }

        echo '</div>';
        wp_reset_postdata();
    }

    return ob_get_clean();
}
add_shortcode('our_locations', 'our_locations_shortcode');

function contact_us_shortcode(){
    ob_start(); 
    ?>
    <div class="triger_open">
    <?php
        $contact_post  = get_page_by_title('Contact Us', OBJECT, 'page_content');
        if ($contact_post) {
            $postId = $contact_post->ID;
            // $contact = get_post_meta($postId, 'contact_no', true);
            // $address = get_post_meta($postId, 'address', true);
            $linkedInURL = get_post_meta($postId, 'linkedin_url', true);
            $youTubeURL = get_post_meta($postId, 'youtube_url', true);
            $facebookURL = get_post_meta($postId, 'facebook_url', true);
            $instagramURL = get_post_meta($postId, 'instagram_url', true);
        }
        
        $args = array(
            'post_type'      => 'our_locations',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'order'          => 'DESC',
        );
        
        $posts = get_posts($args);
        
    ?>
    <div class="containerwe">
        
        <div class="triger_close">X</div> 
        Contact Us
        
        <div class="contactInfo">
            <?php
            if (!empty($posts)) {
                $address = [];
                $contact = [];
            
                foreach ($posts as $post) {
                    $postId = $post->ID;
                    $title = get_the_title($postId);
                    $street = get_post_meta($postId, 'street_address', true);
                    $suite = get_post_meta($postId, 'suite__unit', true);
                    $city = get_post_meta($postId, 'city', true);
                    $state = get_post_meta($postId, 'state', true);
                    $zip_code = get_post_meta($postId, 'zip_code', true);
                    
                    $addr = $street.' '.$suite.', '.$city.', '.$state.' '.$zip_code;
                    $phone = get_post_meta($postId, 'contact', true);
            
                    if (!empty($addr)) {
                    $addresses[] = [
                        'title' => $title,
                        'address' => $addr
                    ];
                    }
            
                    if (!empty($phone)) {
                        $contacts[] = [
                            'title' => $title,
                            'phone' => $phone
                        ];
                    }
                }
               
            }
 
                if(!empty($contacts)){
                    foreach($contacts as $contactNo){
                    ?>
                    <span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" fill="#1A5364"><path d="M224.2 89C216.3 70.1 195.7 60.1 176.1 65.4L170.6 66.9C106 84.5 50.8 147.1 66.9 223.3C104 398.3 241.7 536 416.7 573.1C493 589.3 555.5 534 573.1 469.4L574.6 463.9C580 444.2 569.9 423.6 551.1 415.8L453.8 375.3C437.3 368.4 418.2 373.2 406.8 387.1L368.2 434.3C297.9 399.4 241.3 341 208.8 269.3L253 233.3C266.9 222 271.6 202.9 264.8 186.3L224.2 89z"/></svg>
                    <div>
                        <span><?php echo esc_html($contactNo['title']); ?></span>
                        <a href="tel:+1<?php esc_html($contactNo['phone']); ?>" style="text-decoration:none; color:black;"><?php echo esc_html($contactNo['phone']); ?></a>
                    </div>
                    </span>
                    <?php
                    }
                }

                if (!empty($addresses)) {
                    foreach($addresses as $add){
                    $map_url = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($add['address']);
                    ?>
                    <span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" fill="#1A5364"><path d="M128 252.6C128 148.4 214 64 320 64C426 64 512 148.4 512 252.6C512 371.9 391.8 514.9 341.6 569.4C329.8 582.2 310.1 582.2 298.3 569.4C248.1 514.9 127.9 371.9 127.9 252.6zM320 320C355.3 320 384 291.3 384 256C384 220.7 355.3 192 320 192C284.7 192 256 220.7 256 256C256 291.3 284.7 320 320 320z"/></svg>
                    <div>
                        <span><?php echo esc_html($add['title']); ?></span>
                        <a href="<?php echo esc_url($map_url); ?>" 
                           target="_blank" 
                           style="text-decoration: none; color: black;">
                            <?php echo esc_html($add['address']); ?>
                        </a>
                    </div>
                    </span>
                    <?php
                    }
                }
                ?>

            
            <div class="socialLinks">
            
                <?php if (!empty($facebookURL)) : ?>
                    <span>
                        <a href="<?php echo esc_url($facebookURL); ?>" target="_blank">
                            <img src="/wp-content/uploads/2026/02/Frame-1082.png" alt="Facebook" />
                        </a>
                    </span>
                <?php endif; ?>
            
                <?php if (!empty($instagramURL)) : ?>
                    <span>
                        <a href="<?php echo esc_url($instagramURL); ?>" target="_blank">
                            <img src="/wp-content/uploads/2026/02/Group-1058.png" alt="Instagram" />
                        </a>
                    </span>
                <?php endif; ?>
            
            </div>
        </div>
        
        <h3 class="subTitle">Fill out the form below.</h3>
        
        <div class="contactForm">
            <?php echo do_shortcode('[contact-form-7 id="507f6f1" title="Contact Us"]'); ?>
        </div>
    </div>
</div>
<?php
    return ob_get_clean();
}
add_shortcode('contactus', 'contact_us_shortcode');

// faq shortcode
function display_faq_shortcode() {
    $faqs = get_option('faq_list', array());
    if (!$faqs) return '<p>No FAQs available.</p>';

    $output = '<section class="faq-container">';
    //$output .= '<h1>Frequently Asked Questions</h1>';

    foreach ($faqs as $index => $faq) {
        $open = $index === 0 ? ' open' : ''; // Only first FAQ open
        $output .= '<div class="faq-item">';
        $output .= '<details' . $open . '>';
        $output .= '<summary>' . esc_html($faq['question']) . '</summary>';
        $output .= '<div class="content"><p>' . esc_html($faq['answer']) . '</p></div>';
        $output .= '</details>';
        $output .= '</div>';
    }

    $output .= '</section>';
    return $output;
}
add_shortcode('faq_list', 'display_faq_shortcode');

//Our Team Shortcode
function our_team_shortcode(){
     $args = array(
        'post_type'      => 'our_team',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'order'          => 'DESC',
    );
    $query = new WP_Query($args);
    
       if (!$query->have_posts()) {
        return '<p class="text-center">No team member found.</p>';
    }

    $html = '<div class="containerfluid">';
    $html .= '<div class="row justify-content-center g-4">';

    while ($query->have_posts()) {
        $query->the_post();
        $post_id = get_the_ID();
        $name  = get_the_title();
        $title = get_post_meta(get_the_ID(), 'member_title', true);
        $bio   = get_post_meta(get_the_ID(), 'short_bio', true);
        $specialization   = get_post_meta(get_the_ID(), 'specialization', true);

        $image = get_the_post_thumbnail_url(get_the_ID(), 'medium');
      

        if (empty($image)) {
            $image = home_url('/wp-content/uploads/2026/01/imageprofile.png');
        }

        // Fallback image if no featured image
        if (!$image) {
            $image = 'https://via.placeholder.com/300x300';
        }

        $html .= '
        <div class="col-md-6 col-lg-3">
            <div class="card profile-card">
                <div class="profile-img-container">
                    <img src="'.esc_url($image).'" alt="'.esc_attr($name).'" class="img-fluid">
                </div>
                <div class="card-body p-0">
                    <h3 class="member-name">'.esc_html($name).'</h3>
                    <p class="member-title">'.esc_html($specialization).'</p>
                     <a href="'.esc_url(site_url('/team-member-profile/?team_member_id=' . $post_id)).'" class="btn btn-primary mt-2">
                        View Profile
                    </a>
                </div>
            </div>
        </div>';
    } 

    $html .= '</div></div>';

    wp_reset_postdata();

    return $html;
    
}
add_shortcode('our_team', 'our_team_shortcode');

/**
 * -----------------------------------------------------------------------------
 * Legacy Transformations gallery — modal/lightbox
 *
 * Modal markup is output below; styles:
 * `plugins/custom-functions/css/legacy-gallery-modal.css` (enqueued after child-style).
 * Behavior: `plugins/custom-functions/js/custom.js`.
 * -----------------------------------------------------------------------------
 */

/**
 * Modal markup — printed once when multiple shortcode instances exist on the page.
 */
function legacy_transformation_modal_html_once() {
    static $printed = false;
    if ($printed) {
        return '';
    }
    $printed = true;

    return '
<div id="legacy-gallery-modal" class="legacy-gallery-modal" aria-hidden="true">
	<div class="legacy-gallery-modal__backdrop"></div>
	<div class="legacy-gallery-modal__dialog" role="dialog" aria-modal="true" aria-label="' . esc_attr__('Gallery preview', 'custom-functions') . '">
		<div class="legacy-gallery-modal__toolbar">
			<button type="button" class="legacy-gallery-modal__fullscreen" aria-label="' . esc_attr__('Enter fullscreen', 'custom-functions') . '">&#x2922;</button>
			<button type="button" class="legacy-gallery-modal__close" aria-label="' . esc_attr__('Close', 'custom-functions') . '">&times;</button>
		</div>
		<div class="legacy-gallery-modal__viewport">
			<div class="legacy-gallery-modal__body">
				<div class="legacy-gallery-modal__slide"></div>
			</div>
			<div class="legacy-gallery-modal__nav-layer">
				<button type="button" class="legacy-gallery-modal__nav legacy-gallery-modal__nav--prev" aria-label="' . esc_attr__('Previous item', 'custom-functions') . '">&#8249;</button>
				<button type="button" class="legacy-gallery-modal__nav legacy-gallery-modal__nav--next" aria-label="' . esc_attr__('Next item', 'custom-functions') . '">&#8250;</button>
			</div>
		</div>
	</div>
</div>';
}

// Legacy transformation shortcode
function legacy_transformation_shortcode() {
    $args = array(
        'post_type'      => 'legacy_gallery',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'ASC',
    );

    $posts = get_posts($args);

    if (empty($posts)) {
        return '<p class="text-center">No gallery found.</p>';
    }

    ob_start();
    ?>

    <div class="legacy-gallery-root">
    <div class="legacy-gallery-grid">
        <?php foreach ($posts as $post) :
            $post_id = $post->ID;
            $title         = get_the_title($post_id);
            $stylist_name  = trim((string) get_post_meta($post_id, 'stylist_name', true));
            $thumb   = get_the_post_thumbnail_url($post_id, 'medium');
            $full_img = get_the_post_thumbnail_url($post_id, 'full');

            // Get video (URL or attachment ID); keep raw URL for markup and escape once below.
            $video_file = get_post_meta($post_id, 'video_file', true);

            $video_src = '';

            if (!empty($video_file)) {
                $file_url = wp_get_attachment_url($video_file);
                if ($file_url) {
                    $video_src = $file_url;
                }
            }

            // Data attributes drive modal content (types: video | image); escape URLs once for attributes.
            if ($video_src) {
                $gallery_type = 'video';
                $media_url_esc = esc_url($video_src);
            } else {
                $gallery_type = 'image';
                $media_url_esc = $full_img ? esc_url($full_img) : esc_url((string) $thumb);
            }
            ?>

        <div class="gallery-card legacy-gallery-card"
            data-title="<?php echo esc_attr($title); ?>"
            data-stylist-name="<?php echo esc_attr($stylist_name); ?>"
            data-gallery-type="<?php echo esc_attr($gallery_type); ?>"
            data-media-url="<?php echo $media_url_esc; ?>"
            <?php if ($video_src && $thumb) : ?>
            data-poster="<?php echo esc_url($thumb); ?>"
            <?php endif; ?>>

            <?php if ($video_src) : ?>
                <div class="video-wrapper">
                    <video
                        muted
                        loop
                        playsinline
                        preload="metadata"
                        poster="<?php echo esc_url($thumb); ?>"
                        class="hover-video"
                    >
                        <source src="<?php echo esc_url($video_src); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            <?php else : ?>
                <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr($title); ?>">
            <?php endif; ?>

            <?php if ($stylist_name !== '') : ?>
                <span class="legacy-gallery-card__stylist"><?php echo esc_html($stylist_name); ?></span>
            <?php endif; ?>

        </div>

            <?php
            endforeach; ?>
    </div>
    </div>
    <?php echo legacy_transformation_modal_html_once(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

    <?php
    return ob_get_clean();
}
add_shortcode('legacy_transformations', 'legacy_transformation_shortcode');

/**
 * -----------------------------------------------------------------------------
 * Suites Transformation carousel — [suites_transformation]
 *
 * Displays all published `suites_transform` posts in a Swiper slider with
 * draggable before/after image comparison on each card.
 * -----------------------------------------------------------------------------
 */

/**
 * @param array $atts Shortcode attributes.
 * @return string
 */
function suites_transformation_shortcode( $atts ) {
    st_mark_suites_transformation_shortcode();
    // Enqueue when shortcode renders (may run after wp_enqueue_scripts).
    st_enqueue_suites_transformation_frontend_assets();

    $atts = shortcode_atts(
        array(
            'limit'  => -1,
            'title'  => __( 'Suite Transformations', 'custom-functions' ),
            'subtitle' => __( 'See how our luxury suites evolve from vision to reality.', 'custom-functions' ),
        ),
        $atts,
        'suites_transformation'
    );

    $limit = intval( $atts['limit'] );

    $posts = get_posts(
        array(
            'post_type'      => 'suites_transform',
            'posts_per_page' => $limit,
            'post_status'    => 'publish',
            'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
            'order'          => 'ASC',
        )
    );

    ob_start();

    if ( empty( $posts ) ) {
        ?>
        <section class="st-transform-section">
            <div class="st-transform-empty" role="status">
                <?php esc_html_e( 'No suite transformations have been published yet. Check back soon.', 'custom-functions' ); ?>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }

    $swiper_id    = 'st-transform-swiper-' . wp_unique_id();
    $modal_id     = 'st-video-modal-' . wp_unique_id();
    $has_any_video = false;
    foreach ( $posts as $check_post ) {
        if ( st_get_transform_video_url( get_post_meta( $check_post->ID, ST_META_VIDEO, true ) ) ) {
            $has_any_video = true;
            break;
        }
    }
    ?>
    <section
        class="st-transform-section"
        aria-label="<?php echo esc_attr( $atts['title'] ); ?>"
        <?php if ( $has_any_video ) : ?>
        data-st-video-modal-id="<?php echo esc_attr( $modal_id ); ?>"
        <?php endif; ?>
    >

        <div class="st-transform-slider-wrap">
            <div class="st-transform-carousel">
                <button type="button" class="st-transform-nav st-transform-prev swiper-button-prev" aria-label="<?php esc_attr_e( 'Previous slide', 'custom-functions' ); ?>"></button>

                <div class="swiper st-transform-swiper" id="<?php echo esc_attr( $swiper_id ); ?>">
                <div class="swiper-wrapper">
                    <?php
                    foreach ( $posts as $post ) :
                        setup_postdata( $post );
                        $post_id    = $post->ID;
                        $title      = get_the_title( $post_id );
                        $short_desc = get_post_meta( $post_id, ST_META_SHORT_DESC, true );
                        $before_id  = absint( get_post_meta( $post_id, ST_META_BEFORE_IMAGE, true ) );
                        $after_id   = absint( get_post_meta( $post_id, ST_META_AFTER_IMAGE, true ) );

                        $before_url = st_get_transform_image_url( $before_id, 'large' );
                        $after_url  = st_get_transform_image_url( $after_id, 'large' );
                        if ( $before_id && $after_id && $before_url === $after_url ) {
                            $after_url = wp_get_attachment_image_url( $after_id, 'full' ) ?: $after_url;
                            $before_url = wp_get_attachment_image_url( $before_id, 'full' ) ?: $before_url;
                        }
                        $video_id    = absint( get_post_meta( $post_id, ST_META_VIDEO, true ) );
                        $video_url   = st_get_transform_video_url( $video_id );
                        $video_mime  = $video_id ? st_get_transform_video_mime( $video_id ) : 'video/mp4';
                        $has_video   = ! empty( $video_url );
                        ?>
                    <div class="swiper-slide">
                        <article class="st-transform-card<?php echo $has_video ? ' st-transform-card--has-video' : ''; ?>">
                            <div class="st-ba-wrap">
                                <?php if ( $has_video ) : ?>
                                <div class="st-ba-video-thumb swiper-no-swiping">
                                    <img
                                        class="st-ba-video-thumb__img"
                                        src="<?php echo esc_url( $after_url ); ?>"
                                        alt="<?php echo esc_attr( sprintf( __( '%s — transformation video thumbnail', 'custom-functions' ), $title ) ); ?>"
                                        loading="lazy"
                                        decoding="async"
                                    />
                                    <span
                                        role="button"
                                        tabindex="0"
                                        class="st-video-play-btn swiper-no-swiping"
                                        data-video-url="<?php echo esc_url( $video_url ); ?>"
                                        data-video-type="<?php echo esc_attr( $video_mime ); ?>"
                                        aria-label="<?php echo esc_attr( sprintf( __( 'Play %s transformation video', 'custom-functions' ), $title ) ); ?>"
                                        onclick="return window.STTransformVideo &amp;&amp; window.STTransformVideo.handlePlayClick(this, event);"
                                    >
                                        <span class="st-video-play-btn__icon" aria-hidden="true"></span>
                                    </span>
                                </div>
                                <?php else : ?>
                                <!-- Desktop: draggable before/after comparison -->
                                <div
                                    class="st-ba-compare"
                                    role="img"
                                    aria-label="<?php echo esc_attr( sprintf( __( '%s before and after comparison', 'custom-functions' ), $title ) ); ?>"
                                    data-before-id="<?php echo esc_attr( $before_id ); ?>"
                                    data-after-id="<?php echo esc_attr( $after_id ); ?>"
                                >
                                    <img
                                        class="st-ba-before"
                                        src="<?php echo esc_url( $before_url ); ?>"
                                        alt="<?php echo esc_attr( sprintf( __( '%s — before', 'custom-functions' ), $title ) ); ?>"
                                        loading="lazy"
                                        decoding="async"
                                    />
                                    <div class="st-ba-after">
                                        <img
                                            class="st-ba-after-img"
                                            src="<?php echo esc_url( $after_url ); ?>"
                                            alt="<?php echo esc_attr( sprintf( __( '%s — after', 'custom-functions' ), $title ) ); ?>"
                                            loading="lazy"
                                            decoding="async"
                                        />
                                    </div>
                                    <div class="st-ba-labels">
                                        <span class="st-ba-label"><?php esc_html_e( 'Before', 'custom-functions' ); ?></span>
                                        <span class="st-ba-label"><?php esc_html_e( 'After', 'custom-functions' ); ?></span>
                                    </div>
                                    <div class="st-ba-handle" aria-hidden="true"></div>
                                </div>

                                <!-- Mobile: toggle between before and after -->
                                <div class="st-ba-toggle" role="group" aria-label="<?php esc_attr_e( 'Toggle before or after view', 'custom-functions' ); ?>">
                                    <button type="button" class="st-ba-toggle-btn is-active" data-view="before" aria-pressed="true"><?php esc_html_e( 'Before', 'custom-functions' ); ?></button>
                                    <button type="button" class="st-ba-toggle-btn" data-view="after" aria-pressed="false"><?php esc_html_e( 'After', 'custom-functions' ); ?></button>
                                </div>
                                <div class="st-ba-mobile-stack">
                                    <div class="st-ba-panel is-visible" data-view="before">
                                        <img src="<?php echo esc_url( $before_url ); ?>" alt="<?php echo esc_attr( sprintf( __( '%s — before', 'custom-functions' ), $title ) ); ?>" loading="lazy" decoding="async" />
                                    </div>
                                    <div class="st-ba-panel" data-view="after">
                                        <img src="<?php echo esc_url( $after_url ); ?>" alt="<?php echo esc_attr( sprintf( __( '%s — after', 'custom-functions' ), $title ) ); ?>" loading="lazy" decoding="async" />
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="st-transform-card__body">
                                <?php if ( $title ) : ?>
                                    <h3 class="st-transform-card__title"><?php echo esc_html( $title ); ?></h3>
                                <?php endif; ?>
                                <?php if ( $short_desc ) : ?>
                                    <p class="st-transform-card__desc"><?php echo esc_html( $short_desc ); ?></p>
                                <?php endif; ?>
                            </div>
                        </article>
                    </div>
                        <?php
                    endforeach;
                    wp_reset_postdata();
                    ?>
                </div>
            </div>

                <button type="button" class="st-transform-nav st-transform-next swiper-button-next" aria-label="<?php esc_attr_e( 'Next slide', 'custom-functions' ); ?>"></button>
            </div>

            <div class="st-transform-pagination swiper-pagination"></div>
        </div>

        <?php if ( $has_any_video ) : ?>
        <div
            id="<?php echo esc_attr( $modal_id ); ?>"
            class="st-video-modal"
            role="dialog"
            aria-modal="true"
            aria-hidden="true"
            aria-label="<?php esc_attr_e( 'Transformation video player', 'custom-functions' ); ?>"
        >
            <div class="st-video-modal__backdrop" tabindex="-1"></div>
            <div class="st-video-modal__dialog">
                <div class="st-video-modal__toolbar">
                    <button
                        type="button"
                        class="st-video-modal__minimize"
                        aria-label="<?php esc_attr_e( 'Exit fullscreen', 'custom-functions' ); ?>"
                        title="<?php esc_attr_e( 'Exit fullscreen', 'custom-functions' ); ?>"
                    >
                        <span aria-hidden="true">&#8722;</span>
                    </button>
                    <button
                        type="button"
                        class="st-video-modal__fullscreen"
                        aria-label="<?php esc_attr_e( 'Enter fullscreen', 'custom-functions' ); ?>"
                        title="<?php esc_attr_e( 'Enter fullscreen', 'custom-functions' ); ?>"
                    >
                        <span aria-hidden="true">&#9974;</span>
                    </button>
                    <button
                        type="button"
                        class="st-video-modal__close"
                        aria-label="<?php esc_attr_e( 'Close video', 'custom-functions' ); ?>"
                        title="<?php esc_attr_e( 'Close', 'custom-functions' ); ?>"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <video class="st-video-modal__player" controls playsinline preload="metadata">
                    <source src="" type="video/mp4" />
                </video>
            </div>
        </div>
        <?php endif; ?>

    </section>
    <?php

    return ob_get_clean();
}
add_shortcode( 'suites_transformation', 'suites_transformation_shortcode' );