<?php

/**
 * 
 * 
 Template Name: Single Stylist New
 *
 * 
 **/
 
 get_header();
 
 
$stylist_id = $_GET['stylist_id'];
$profile_image = get_the_post_thumbnail_url($stylist_id, 'thumbnail', 'full');
$postTitle = get_the_title($stylist_id);
$position = get_post_meta($stylist_id, 'position', true);
$street = get_post_meta($stylist_id, 'street', true);
$city = get_post_meta($stylist_id, 'city', true);
$state = get_post_meta($stylist_id, 'state', true);
$experience = get_post_meta($stylist_id, 'experience', true);
if(!empty($experience)){
    $experience = $experience.'+ years of experience';
}
$contact = get_post_meta($stylist_id, 'contact', true);
$instagramUrl = get_post_meta($stylist_id, 'instagram', true);
$facebookUrl = get_post_meta($stylist_id, 'facebook', true);
$bio = get_post_meta($stylist_id, 'short_bio', true);
$speciality = get_post_meta($stylist_id, 'specialization', true);
$bookingLink = get_post_meta($stylist_id, 'booking_link', true);
$stlist_availability = get_post_meta($stylist_id, 'stylist_availability', true);
$services = get_the_terms($stylist_id, 'stylist_service');
$gallery = get_post_meta($stylist_id, 'stylist_gallery', true);
$gallery = !empty($gallery) ? $gallery : [];
$suiteAddress = get_post_meta($stylist_id, 'suite_address', true);
 ?>
 <style>
 h3, h1{
	font-family: "Inter", sans-serif!important;
 }
 </style>
 <script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
 
 <section class="relative py-20 bg-[#053c57] from-slate-900 to-slate-700">
        <div class="container mx-auto px-6 text-center">
            <div class="profileWrap">
                <div class="relative inline-block">
                    <?php if (!empty($profile_image)) { ?>
                        <img src="<?php echo esc_url($profile_image); ?>" 
                             alt="Murielle" 
                             class="w-44 h-44 rounded-full border-4 border-white shadow-lg mx-auto object-cover">
                    <?php } else { ?>
                        <img src="/wp-content/uploads/2026/01/imageprofile.png" 
                             alt="Murielle" 
                             class="w-44 h-44 rounded-full border-4 border-white shadow-lg mx-auto object-cover">
            
                    <?php } ?>
            
                </div>
            
                <div class="text-start">
                    <h1 class="text-4xl font-bold text-white mt-4 pt-1"><?php echo !empty($postTitle) ? $postTitle : ''; ?></h1>
                    <p class="text-slate-300 text-lg uppercase tracking-widest mt-2"><?php echo !empty($position) ? $position : '' ?></p>
            
                    <div class="flex justify-start items-center mt-1 space-x-2">
                        <div class="flex text-yellow-400 text-sm">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
            
                        <span class="text-white font-medium">(4.9 | 157 reviews)</span>
                    </div>
                </div>
            </div>
        </div> 
    </section>


    <main class="container mx-auto p3-4 -mt-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1 space-y-6">
                <div class="glass-card rounded-2xl p-6">
                    <h3 class="text-3xl font-bold mb-4 flex items-center">
                        About Me
                    </h3>

                    <div class="flex items-center text-gray-700 mb-3">
                        <i class="fa-solid fa-location-dot w-8 text-[#053c57] me-3"></i>
                        <span class="text-2xl"> <?php 
                            if (!empty($street) && !empty($city) && !empty($state)) {
                                echo $street . ' &bull; ' . $city . ' &bull; ' . $state;
                            }else{
                                echo '';
                            }
                            ?>
                        </span>
                    </div>

                    <div class="flex items-center text-gray-700 mb-3">
                        <i class="fa-solid fa-award w-8 text-[#053c57] me-3"></i>
                        <span class="text-2xl font-medium text-gray-500"><?php echo !empty($experience) ? $experience : ''; ?></span>
                    </div>

                    <div class="flex items-center text-gray-700 mb-2">
                        <i class="fa-solid fa-phone w-8 text-[#053c57] me-3"></i>
                        <span class="text-2xl font-medium text-gray-500"><?php echo !empty($contact) ? $contact : ''; ?></span>
                    </div>

                    

                    <div class="mb-3 mt-3">
                        <div class="flex items-center space-x-4">
                            <?php
                            if(!empty($instagramUrl)){
                                ?>
                                <a href="<?php echo $instagramUrl; ?>" target="_blank" class="text-4xl text-gray-600 hover:text-gray-600 transition">
                                    <i class="fa-brands fa-instagram"></i>
                                </a>
                                <?php
                            }else{
                                ?>
                                <span class="text-4xl text-gray-400 cursor-not-allowed">
                                    <i class="fa-brands fa-instagram"></i>
                                </span>
                                <?php
                            }
                            if(!empty($facebookUrl)){
                                ?>
                                <a href="<?php echo $facebookUrl; ?>" target="_blank" class="text-4xl text-gray-600 hover:text-gray-600 transition">
                                    <i class="fa-brands fa-facebook"></i>
                                </a>
                                <?php
                            }else{
                                ?>
                                 <span class="text-4xl text-gray-400 cursor-not-allowed">
                                   <i class="fa-brands fa-facebook"></i>
                                </span>
                                <?php
                            }
                            ?>
                        </div>
                    </div>

                    <hr class="border-gray-100 mb-2">
                   
                    <p class="text-gray-600 leading-relaxed"><?php echo !empty($bio) ? $bio : ''; ?></p>
                    <hr class="my-6 border-gray-200">
                    <ul class="space-y-4">
                        <li class="flex justify-between">
                            <span class="font-semibold">Experience:</span>
                            <span><?php echo !empty($experience) ? $experience : ''; ?></span>
                        </li>
                        <li class="flex justify-between">
                            <span class="font-semibold">Specialty:</span>
                            <span ><?php echo !empty($speciality) ? $speciality : ''; ?></span>
                        </li>
                    </ul>
                    <a href="<?php echo !empty($bookingLink) ? $bookingLink : 'javascript:void(0);' ?>">
                        <button class="w-full mt-6 bg-[#FF5792] text-white py-3 rounded-xl font-bold hover:bg-[#FF5792] transition duration-300 border-none hover:border-none">
                            Book Appointment
                        </button>
                    </a>
                </div>
                <?php
                $has_data = false;
                
                if (!empty($stlist_availability) && is_array($stlist_availability)) {
                    foreach ($stlist_availability as $day => $data) {
                        if (
                            isset($data['open']) && $data['open'] === 'on' &&
                            !empty($data['start']) && !empty($data['end'])
                        ) {
                            $has_data = true;
                            break;
                        }
                    }
                }
                ?>
                
                <div class="glass-card rounded-2xl p-6">
                    <h3 class="text-3xl font-bold mb-4">Availability</h3>
                    <div class="text-[14px] space-y-2">
                
                        <?php if (!$has_data): ?>
                            <?php 
                            $days_order = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                            foreach ($days_order as $day): ?>
                                
                                <div class="flex justify-between font-semibold">
                                    <span><?php echo esc_html($day); ?></span>
                                    <span class="text-gray-400">—</span> <!-- or "Not Available" -->
                                </div>
                        
                            <?php endforeach; ?>
                
                        <?php else: 
                
                            $days_order = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                            $grouped = [];
                
                            foreach ($days_order as $day) {
                
                                if (!isset($stlist_availability[$day])) continue;
                
                                $data = $stlist_availability[$day];
                
                                // Closed days
                                if (!isset($data['open']) || $data['open'] !== 'on') {
                                    $grouped['closed'][] = $day;
                                    continue;
                                }
                
                                // Skip incomplete time
                                if (empty($data['start']) || empty($data['end'])) continue;
                
                                $key = $data['start'] . '-' . $data['end'];
                                $grouped[$key][] = $day;
                            }
                
                            foreach ($grouped as $time => $days):
                
                                if ($time === 'closed'):
                                    foreach ($days as $day): ?>
                                        <div class="flex justify-between text-red-500 font-semibold">
                                            <span><?php echo esc_html($day); ?></span>
                                            <span>Closed</span>
                                        </div>
                                    <?php endforeach;
                
                                else:
                                    $times = explode('-', $time);
                                    $start = date("g:i A", strtotime($times[0]));
                                    $end   = date("g:i A", strtotime($times[1]));
                
                                    $label = count($days) > 1
                                        ? substr($days[0], 0, 3) . ' - ' . substr(end($days), 0, 3)
                                        : $days[0];
                                    ?>
                
                                    <div class="flex justify-between font-semibold">
                                        <span><?php echo esc_html($label); ?></span>
                                        <span class="text-gray-500">
                                            <?php echo esc_html($start . ' - ' . $end); ?>
                                        </span>
                                    </div>
                
                                <?php endif;
                
                            endforeach;
                
                        endif; ?>
                
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-8">
                
                <?php if (!empty($services) && !is_wp_error($services)) : ?>
                <div class="glass-card rounded-2xl p-6">
                    <h3 class="text-3xl font-bold mb-6">Service Menu</h3>
                
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($services as $service) : 
                
                            // Get price (adjust if using ACF or term meta)
                            $price = get_field('price', 'stylist_service_' . $service->term_id); 
                            if (!$price) {
                                $price = '';
                            }
                
                            // Get description
                            $description = term_description($service->term_id);
                            if (!$description) {
                                $description = '';
                            }
                        ?>
                            <div class="p-4 border border-gray-100 rounded-xl hover:bg-white transition shadow-sm">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-2xl text-[#053c57]">
                                        <?php echo esc_html($service->name); ?>
                                    </span>
                
                                    <span class="text-[#ff5792] text-2xl font-bold">
                                        <?php echo ($price === '') ? '' : '$' . esc_html($price) . '+'; ?>
                                    </span>
                                </div>
                
                                <p class="text-[14px] text-gray-400 mt-1">
                                    <?php echo $description; ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php else : ?>
                    <div class="glass-card rounded-2xl p-6">
                        <h3 class="text-3xl font-bold mb-6">Service Menu</h3>
                        <p class="text-gray-400"></p>
                    </div>
                <?php endif; ?>
                
                <div>
                    <h3 class="text-3xl font-bold mb-6 flex items-center">
                        <i class="fa-solid fa-camera-retro mr-2"></i> Gallery
                    </h3>
                
                    <?php if (!empty($gallery)) : ?>
                
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                
                            <?php foreach ($gallery as $item): ?>
                
                                <div class="gallery-item overflow-hidden rounded-xl h-48">
                
                                    <?php if (preg_match('/\.(mp4|webm|ogg)$/i', $item)) : ?>
                                        <video class="w-full h-full object-cover" controls>
                                            <source src="<?php echo esc_url($item); ?>">
                                        </video>
                                    <?php else : ?>
                                        <img src="<?php echo esc_url($item); ?>"
                                             class="w-full h-full object-cover"
                                             alt="Gallery Image">
                                    <?php endif; ?>
                
                                </div>
                
                            <?php endforeach; ?>
                
                        </div>
                
                    <?php else : ?>
                
                        <div class="bg-slate-50 rounded-xl p-10 text-center border border-dashed">
                
                            <i class="fa-solid fa-image text-4xl text-slate-400 mb-3"></i>
                
                            <p class="text-slate-600 font-semibold">
                                No work uploaded yet
                            </p>
                
                            <p class="text-sm text-slate-400 mt-1">
                                This stylist hasn’t added their portfolio yet.
                            </p>
                
                        </div>
                
                    <?php endif; ?>
                </div>

            </div>

            <div class="lg:col-span-3 mt-12 mb-5">
                <div class="glass-card rounded-2xl overflow-hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2">
                        
                        <div class="p-8 flex flex-col justify-start">
                            <h3 class="text-3xl font-bold mb-6 flex items-center">
                                <i class="fa-solid fa-location-dot mr-3 text-red-500"></i> Find the Studio
                            </h3>
                            
                            <div class="space-y-6">
                                <div>
                                    <p class="text-black text-2xl font-bold tracking-widest">Address</p>
                                    <p class="text-[14px] text-gray-700 leading-relaxed mt-1">
                                        <?php echo !empty($suiteAddress) ? esc_html($suiteAddress) : ''; ?>
                                    </p>
                                </div>
            
                                <?php if (!empty($suiteAddress)) : ?>
                                    <div class="flex flex-wrap gap-3">
                                        <a href="https://www.google.com/maps/dir/?api=1&destination=<?php echo urlencode($suiteAddress); ?>" 
                                           target="_blank"
                                           class="flex items-center bg-[#FF5792] text-white px-5 py-3 rounded-xl text-2xl font-bold hover:bg-indigo-600 transition">
                                            <i class="fa-solid fa-route mr-2"></i> Get Directions
                                        </a>
                                    </div>
                                <?php endif; ?>
            
                            </div>
                        </div>
            
                        <div class="h-80 md:h-full min-h-[300px] w-full grayscale hover:grayscale-0 transition duration-500">
                            
                            <?php if (!empty($suiteAddress)) : ?>
                                <iframe 
                                    src="https://www.google.com/maps?q=<?php echo urlencode($suiteAddress); ?>&output=embed"
                                    width="100%" 
                                    height="100%" 
                                    style="border:0;" 
                                    allowfullscreen="" 
                                    loading="lazy">
                                </iframe>
                            <?php else : ?>
                            <?php $suiteAddress = '5930 W I-20, Suite #200, Arlington, TX 76017, USA'; ?>
                             <iframe 
                                    src="https://www.google.com/maps?q=<?php echo urlencode($suiteAddress); ?>&output=embed"
                                    width="100%" 
                                    height="100%" 
                                    style="border:0;" 
                                    allowfullscreen="" 
                                    loading="lazy">
                                </iframe>
                            <?php endif; ?>
            
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php get_footer(); ?>