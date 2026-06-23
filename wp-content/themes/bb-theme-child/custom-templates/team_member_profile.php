<?php

/**
 * 
 * 
 Template Name: Team Profile
 *
 * 
 **/
 
 get_header(); 
 
$memberId = $_GET['team_member_id'];
$profile_image = get_the_post_thumbnail_url($memberId, 'thumbnail');
$postTitle = get_the_title($memberId);
$specialization = get_post_meta($memberId, 'specialization', true);
$instagramUrl = get_post_meta($memberId, 'instagram', true);
$facebookUrl = get_post_meta($memberId, 'facebook', true);
$tikTokUrl = get_post_meta($memberId, 'tiktok', true);
$contact = get_post_meta($memberId, 'contact', true);
$street = get_post_meta($memberId, 'street', true);
$city = get_post_meta($memberId, 'city', true);
$state = get_post_meta($memberId, 'state', true);
$zip_code = get_post_meta($memberId, 'zip_code', true);
$address = $street.', '.$city.', '.$state.' '.$zip_code;
$email = get_post_meta($memberId, 'email', true);
?>
   <header class="profile-hero">
        <div class="container">
            <div class="hero-flex">
                <?php if ($profile_image): ?>
                        <img src="<?php echo esc_url($profile_image); ?>" alt="Profile Image" class="hero-img">
                    <?php else: ?>
                        <img src="<?php echo esc_url('/wp-content/uploads/2026/01/imageprofile.png'); ?>" alt="Profile Image" class="hero-img">
                    <?php endif; ?>
                <div class="hero-text">
                    <h1><?php echo $postTitle; ?></h1>
                    <p class="tagline"><?php echo $specialization; ?></p>
                    <div class="hero-social">
                    <?php
                    if(!empty($instagramUrl)){
                        ?>
                        <a href="<?php echo $instagramUrl; ?>"><img src="https://legacysalonsarlington.com/wp-content/uploads/2026/01/instagram.png" /></a>
                        <?php
                    }
                    if(!empty($facebookUrl)){
                        ?>
                        <a href="<?php echo $facebookUrl; ?>"><img src="https://legacysalonsarlington.com/wp-content/uploads/2026/01/facebook.png" /></a>
                        <?php
                    }
                    if(!empty($tikTokUrl)){
                        ?>
                        <a href="<?php echo $tikTokUrl; ?>"><img src="https://legacysalonsarlington.com/wp-content/uploads/2026/01/music.png" /></a>
                        <?php
                    }
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="container page-content">
        <div class="grid-layout">
            
            <aside class="details-sidebar">
                <section class="info-group">
                    <h4>Contact Information</h4>
                    <ul>
                        <li><i class="fa-solid fa-phone"></i><?php echo $contact; ?></li>
                        <li><i class="fa-solid fa-location-dot"></i><?php echo $address; ?></li>
                        <li><i class="fa-solid fa-envelope"></i><?php echo $email; ?></li>
                    </ul>
                </section>

                <!--<section class="info-group">-->
                <!--    <h4>Technical Proficiencies</h4>-->
                <!--    <div class="skill-tags">-->
                <!--        <span>System Architecture</span>-->
                <!--        <span>React / Node.js</span>-->
                <!--        <span>Cloud Infrastructure</span>-->
                <!--        <span>Agile Leadership</span>-->
                <!--        <span>Python</span>-->
                <!--    </div>-->
                <!--</section>-->
            </aside>

            <article class="main-article">
                <section>
                    <h2>Professional Summary</h2>
                    <?php
                        $post = get_post($memberId);
                        if ($post) {
                            echo '<p>' . apply_filters('the_content', $post->post_content) . '</p>';
                        }
                    ?>
                </section>

                <!--<section class="experience-list">-->
                <!--    <h2>Key Projects & Experience</h2>-->
                <!--    <div class="exp-item">-->
                <!--        <div class="exp-header">-->
                <!--            <h3>Global Infrastructure Overhaul</h3>-->
                <!--            <span class="date">2024 - Present</span>-->
                <!--        </div>-->
                <!--        <p>Leading the migration of legacy on-premise servers to a multi-cloud AWS/GCP environment, resulting in a 40% reduction in latency and significant cost savings.</p>-->
                <!--    </div>-->

                <!--    <div class="exp-item">-->
                <!--        <div class="exp-header">-->
                <!--            <h3>API Gateway Modernization</h3>-->
                <!--            <span class="date">2022 - 2024</span>-->
                <!--        </div>-->
                <!--        <p>Architected a high-throughput GraphQL gateway that currently handles upwards of 50,000 requests per second for our mobile application ecosystem.</p>-->
                <!--    </div>-->
                <!--</section>-->
            </article>

        </div>
    </main>
<?php
 get_footer();
 
 ?>