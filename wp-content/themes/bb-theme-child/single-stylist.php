<?php

/**
 * 
 * 
 Template Name: Single Stylist
 *
 * 
 **/
 
 get_header(); 
 

$stylist_id = $_GET['stylist_id'];
$profile_image = get_the_post_thumbnail_url($stylist_id, 'thumbnail');
$postTitle = get_the_title($stylist_id);
$bussinessName = get_post_meta($stylist_id, 'business_name', true);
$city = get_post_meta($stylist_id, 'city', true);
$state = get_post_meta($stylist_id, 'state', true);
$street = get_post_meta($stylist_id, 'street', true);
$address = urlencode($street . ', ' . $city . ', ' . $state);
$instagramUrl = get_post_meta($stylist_id, 'instagram', true);
$facebookUrl = get_post_meta($stylist_id, 'facebook', true);
$tikTokUrl = get_post_meta($stylist_id, 'tiktok', true);
$contact = get_post_meta($stylist_id, 'contact', true);
$experience = get_post_meta($stylist_id, 'experience', true);
$bio = get_post_meta($stylist_id, 'short_bio', true);
$instaUsername = get_post_meta($stylist_id, 'instagram_username', true);
$bookingLink = get_post_meta($stylist_id, 'booking_link', true);
$instagramLink = get_post_meta($stylist_id, 'instagram', true);
$facebookLink = get_post_meta($stylist_id, 'facebook', true);
$suite = get_post_meta($stylist_id, 'suite', true);
$services = get_the_terms($stylist_id, 'stylist_service');
$parent_terms = array();
$sub_terms = array();
foreach ($services as $service) {
    if ($service->parent == 0) {
        $parent_terms[] = $service;
    }else{
        $sub_terms[] = $service;
    }
}

if ($contact && preg_match('/^\d{10}$/', $contact)) {
    $formatted_contact = sprintf(
        '(%s) %s - %s',
        substr($contact, 0, 3),
        substr($contact, 3, 3),
        substr($contact, 6, 4)
    );
} else {
    $formatted_contact = $contact; 
}

 
 
 
 ?>
 
 
 <style>
.bg-cream { background-color: #f8f5f0; }
.btn-dark-custom { background-color: #4b443d; color: white; border-radius: 50px; padding: 10px 30px; border: none; }
.btn-dark-custom:hover { background-color: #332e29; color: #fff; }

.profile-img { border-radius: 20px; width: 100%; max-width: 350px; }
.service-list { list-style: none; padding: 0; }
.service-list li { border-bottom: 1px solid #eee; padding: 10px 0; display: flex; justify-content: space-between; }

.gallery-img { width: 100%; aspect-ratio: 1/1; object-fit: cover; border-radius: 10px; }
.map-placeholder { background-color: #e9ecef;  border-radius: 15px; height: 300px; display: flex; align-items: center; justify-content: center; }     
 </style>
 
<section class="container stylistDetails rightPadding">
        <div class="row align-items-center">
            <div class="col-md-5 text-center">
                 <?php if ($profile_image): ?>
                        <img src="<?php echo esc_url($profile_image); ?>" alt="Profile Image" class="profile-img shadow-sm">
                    <?php else: ?>
                        <img src="<?php echo esc_url('/wp-content/uploads/2026/01/imageprofile.png'); ?>" alt="Profile Image" class="profile-img shadow-sm">
                    <?php endif; ?>
            </div>
            <div class="col-md-7 pt-4 pt-md-0">
                <h1><?php echo $postTitle; ?></h1>
                <p class="lead"><?php echo $bussinessName; ?></p>
                <div class="mb-3 text-warning">★★★★★ <span class="text-muted small">(4.9 | 157 reviews)</span></div>
                <ul class="list-unstyled mb-4">
                    
                    <li><img src="https://legacysalon.betaplanets.com/wp-content/uploads/2026/01/pin.png" /> <?php if(!empty($city && $state)){echo $city. ' - ' .$state; }else{ echo 'NA';} ?></li>
                    <li><img src="https://legacysalon.betaplanets.com/wp-content/uploads/2026/01/badge.png" /> <?php if(!empty($experience)){ echo $experience; ?>+ years of experience <?php }else{ echo 'NA'; } ?></li>
                </ul>
                <strong>Call: <?php echo $formatted_contact; ?></strong>
                <div class="social">
                    <?php
                    if(!empty($instagramUrl)){
                        ?>
                        <a href="<?php echo $instagramUrl; ?>"><img src="https://legacysalon.betaplanets.com/wp-content/uploads/2026/01/instagram.png" /></a>
                        <?php
                    }
                    if(!empty($facebookUrl)){
                        ?>
                        <a href="<?php echo $facebookUrl; ?>"><img src="https://legacysalon.betaplanets.com/wp-content/uploads/2026/01/facebook.png" /></a>
                        <?php
                    }
                    if(!empty($tikTokUrl)){
                        ?>
                        <a href="<?php echo $tikTokUrl; ?>"><img src="https://legacysalon.betaplanets.com/wp-content/uploads/2026/01/music.png" /></a>
                        <?php
                    }
                    ?>
                </div>
                
                <a href="<?php echo $bookingLink; ?>" target="_blank">
                <button class="btn btn-dark-custom">Book Appointment</button>
                </a>
            </div>
        </div>
    </section>

    <section class="bg-cream py-5 text-center stylistBio rightPadding">
        <div class="container">
            <small>ABOUT <?php echo $postTitle; ?></small>
            <p class="mx-auto"><?php echo $bio; ?></p>
        </div>
    </section>

<section class="container py-5 services rightPadding">
    <h2 class="text-center mb-5">Services</h2>

    <div class="row g-5">
        <?php

        // ✅ Fallback: if no parent terms, treat subterms as parents
        if (empty($parent_terms) && !empty($sub_terms)) {
            $parent_terms = $sub_terms;
            $sub_terms = array(); // prevent grouping
        }

        // Avoid division by zero
        $chunk_count = count($parent_terms) > 0 ? ceil(count($parent_terms) / 2) : 1;
        $columns = array_chunk($parent_terms, $chunk_count);

        foreach ($columns as $column_terms) {
            echo '<div class="col-md-6">';

            foreach ($column_terms as $pterm) {

                echo '<h4 class="serif mb-3 fw-bold mt-5">' . esc_html($pterm->name) . '</h4>';
                echo '<ul class="service-list">';

                // Find child terms for this parent
                $current_sub_terms = array_filter($sub_terms, function ($subterm) use ($pterm) {
                    return $subterm->parent == $pterm->term_id;
                });

                if (!empty($current_sub_terms)) {
                    // ✅ Display child terms
                    foreach ($current_sub_terms as $sterm) {
                        $price = get_term_meta($sterm->term_id, 'price', true);
                        echo '<li>' . esc_html($sterm->name) . 
                             '<span>$' . esc_html($price) . '+</span></li>';
                    }
                } else {
                    // ✅ If no children → show parent (or fallback subterm)
                    $price = get_term_meta($pterm->term_id, 'price', true);
                    echo '<li>' . esc_html($pterm->name) . 
                         '<span>$' . esc_html($price) . '+</span></li>';
                }

                echo '</ul>';
            }

            echo '</div>';
        }
        ?>
    </div>

    <div class="text-center mt-5 consultation">
        <p>Prices may vary. Contact for detailed quote.</p>
        <a href="<?php echo $bookingLink; ?>" target="_blank" class="text-dark fw-bold">
            Book a Consultation
        </a>
    </div>
</section>

    <section class="container py-5 follow">
        <div class="text-center mb-4">
            <h2 class="mb-0">Follow <?php echo $postTitle; ?></h2>
            <p class="text-muted small"><?php echo $instaUsername; ?> on Instagram</p>
        </div>
        <div class="row">
            <!--<div class="col"><img src="https://images.unsplash.com/photo-1562322140-8baeececf3df?auto=format&fit=crop&w=400&q=60" class="gallery-img" alt="Work"></div>-->
            <!--<div class="col"><img src="https://images.unsplash.com/photo-1522336572468-97b06e8ef143?auto=format&fit=crop&w=400&q=60" class="gallery-img" alt="Work"></div>-->
            <!--<div class="col"><img src="https://images.unsplash.com/photo-1560869713-7d0a29430863?auto=format&fit=crop&w=400&q=60" class="gallery-img" alt="Work"></div>-->
            <!--<div class="col"><img src="https://images.unsplash.com/photo-1595476108010-b4d1f802b1b1?auto=format&fit=crop&w=400&q=60" class="gallery-img" alt="Work"></div>-->
            <!--<div class="col"><img src="https://images.unsplash.com/photo-1492106087820-71f1a00d2b11?auto=format&fit=crop&w=400&q=60" class="gallery-img" alt="Work"></div>-->
            <!--<div class="col"><img src="https://images.unsplash.com/photo-1633681926022-84c23e8cb2d6?auto=format&fit=crop&w=400&q=60" class="gallery-img" alt="Work"></div>-->
            
            <div class="col">
				<?php echo do_shortcode("[trustindex-feed-instagram]"); ?>
			</div>
        </div>
        <div class="text-center mt-4">
            <!-- <button class="btn btn-dark-custom btn-sm">Follow on Instagram</button>-->
            <a href="<?php echo $instagramLink; ?>" target="_blank">
                <button class="btn btn-dark-custom btn-sm">Follow on Instagram</button>
            </a>
        </div>
    </section>

    <section class="bg-cream py-5 findme">
        <div class="container">
            <h2 class="text-center mb-5">Where to Find Me</h2>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="fw-bold">Legacy Salons - Central Arlington</h5>
                    <p class="mb-4">Suite: <?php if(!empty($suite)){echo $suite; }else{ echo 'NA'; } ?></p>
                    <p class="mb-4 d-flex align-items-center gap-4"><img src="https://legacysalon.betaplanets.com/wp-content/uploads/2026/01/pin.png" /> <span><?php if(!empty($street && $city && $state)){echo $street; ?><br><?php echo $city .', '.$state; }else{ echo 'NA'; } ?></span></p>
                    <p class="mb-4 d-flex align-items-center gap-4"><img src="https://legacysalon.betaplanets.com/wp-content/uploads/2026/01/car.png" /> Free parking available at front of building</p>
                    <!--<button class="btn btn-dark-custom btn-sm" onclick="getDirections()">Get Directions</button>-->
                    <a href="https://www.google.com/maps/dir/?api=1&destination=<?php echo $address; ?>" 
                       target="_blank" 
                       class="btn btn-dark-custom btn-sm" style="text-decoration:none;">
                       Get Directions
                    </a>
                </div>
                <div class="col-md-6 mt-4 mt-md-0">
                    <div class="map-placeholder">
                        <span class="text-muted">Interactive Map View</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="container py-5 mt-5 readyBook">
        <h2 class="text-center mb-5">Ready to Book with <?php echo $postTitle; ?>?</h2>
        <p class="lead text-center">Transform your look today</p>
       <div class="d-flex justify-content-center">
         <a href="<?php echo $bookingLink; ?>" target="_blank">
           <button class="btn btn-dark-custom" type="button">Book Appointment</button>
         </a>
      </div>
    </section>
 
 <?php get_footer(); ?>