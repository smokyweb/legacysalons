<?php do_action( 'fl_content_close' ); ?>

	</div><!-- .fl-page-content -->
	<?php

	do_action( 'fl_after_content' );

	if ( FLTheme::has_footer() ) :

		?>
	<footer class="fl-page-footer-wrap"<?php FLTheme::print_schema( ' itemscope="itemscope" itemtype="https://schema.org/WPFooter"' ); ?>  role="contentinfo">
		<?php

		do_action( 'fl_footer_wrap_open' );
		do_action( 'fl_before_footer_widgets' );

		FLTheme::footer_widgets();

		do_action( 'fl_after_footer_widgets' );
		do_action( 'fl_before_footer' );

		FLTheme::footer();

		do_action( 'fl_after_footer' );
		do_action( 'fl_footer_wrap_close' );

		?>
	</footer>
	<?php endif; ?>
	<?php do_action( 'fl_page_close' ); ?>
</div><!-- .fl-page -->




<!-- <div class="fab-container">
  <div class="fab shadow">
    <div class="fab-content">
      <span class="material-icons">support_agent</span>
    </div>
  </div>
</div> -->

<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script>
jQuery(document).ready(function() {
  // Initialize Main Slider
  jQuery('.slider-main').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: true,
    fade: true,
    // Custom Arrow HTML
    prevArrow: '<button type="button" class="slick-prev"><i class="fa-solid fa-chevron-left"></i></button>',
	nextArrow: '<button type="button" class="slick-next"><i class="fa-solid fa-chevron-right"></i></button>',
  });

  // Initialize Thumbnail Slider
  jQuery('.slider-nav').slick({
    slidesToShow: 4,
    slidesToScroll: 1,
    asNavFor: '.slider-main', // Link to main
    dots: false,
    centerMode: true,
    focusOnSelect: true
  });

  // Optional: Pause video when sliding away
  jQuery('.slider-main').on('beforeChange', function(event, slick, currentSlide, nextSlide) {
    let video = jQuery(slick.$slides[currentSlide]).find('video');
    if (video.length > 0) {
      video[0].pause();
    }
  });
});
jQuery(document).ready(function() {
  // Initialize Main Slider
  jQuery('.slider-main').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: true,
    fade: true,
    asNavFor: '.slider-nav' // Link to nav
  });

  // Initialize Thumbnail Slider
  jQuery('.slider-nav').slick({
    slidesToShow: 4,
    slidesToScroll: 1,
    asNavFor: '.slider-main', // Link to main
    dots: false,
    centerMode: true,
    focusOnSelect: true
  });

  // Optional: Pause video when sliding away
  jQuery('.slider-main').on('beforeChange', function(event, slick, currentSlide, nextSlide) {
    let video = $(slick.$slides[currentSlide]).find('video');
    if (video.length > 0) {
      video[0].pause();
    }
  });
});
</script>

<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons" />

<script>

jQuery(document).ready(function(){
    jQuery('#topbar .notice_close, header .notice .notice_close').on('click', function(event){
        event.preventDefault();
        event.stopPropagation();
        
        var modal = document.getElementById('signup-a-suite-modal');
        if (modal && modal.classList.contains('is-open')) {
            if (window.legacySignupSuite && typeof window.legacySignupSuite.closeModal === 'function') {
                window.legacySignupSuite.closeModal(modal);
            } else {
                var active = document.activeElement;
                if (active && modal.contains(active) && typeof active.blur === 'function') {
                    active.blur();
                }
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
                modal.setAttribute('inert', '');
                document.body.classList.remove('signup-a-suite-modal-open');
            }
            return;
        }
        jQuery('.fl-node-ngzi3hcf4td6').css('display', 'none');
    });
});    
</script>

<script>
jQuery(function() {
//   Owl Carousel
  var owl = jQuery(".home-carousel");
  owl.owlCarousel({
    items: 1,
    margin: 30,
    loop: false,
    nav: false
  });
});    
</script>
<script>
jQuery(document).ready(function() {
    
  var sync1 = jQuery("#sync1");
    var sync2 = jQuery("#sync2");
    var slidesPerPage = 8;
    var blockSync = false;

    sync1.owlCarousel({
        items: 1,
        loop: true,
        nav: true,
        dots: false,
        smartSpeed: 500
    }).on('changed.owl.carousel', function (e) {
        if (blockSync) return;

        var index = e.item.index;
        var realIndex = e.relatedTarget.relative(index);

        sync2.trigger('to.owl.carousel', [realIndex, 300, true]);
        sync2.find('.owl-item').removeClass('current')
            .eq(realIndex).addClass('current');
    });

    sync2.owlCarousel({
        items: slidesPerPage,
        loop: true,
        nav: false,
        dots: false,
        smartSpeed: 300
    });

    // 🔒 Block sync when nav buttons of sync2 are clicked
    sync2.on('click', '.owl-next, .owl-prev', function () {
        blockSync = true;
        setTimeout(function () {
            blockSync = false;
        }, 50);
    });

    // ✅ Click on item SHOULD sync
    sync2.on("click", ".owl-item", function () {
        var index = jQuery(this).index();
        var realIndex = sync2.data('owl.carousel').relative(index);

        sync1.trigger('to.owl.carousel', [realIndex, 300, true]);
        sync2.find('.owl-item').removeClass('current');
        $(this).addClass('current');
    });
    
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<?php

wp_footer();

do_action( 'fl_body_close' );

FLTheme::footer_code();

?>
</body>
</html>