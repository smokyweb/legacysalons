<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<?php do_action( 'fl_head_open' ); ?>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<?php echo apply_filters( 'fl_theme_viewport', "<meta name='viewport' content='width=device-width, initial-scale=1.0' />\n" ); ?>
<?php echo apply_filters( 'fl_theme_xua_compatible', "<meta http-equiv='X-UA-Compatible' content='IE=edge' />\n" ); ?>
<link rel="profile" href="https://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css 
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.3/assets/owl.carousel.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.3/assets/owl.theme.default.min.css" />	
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
<link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet" />
<?php

wp_head();

FLTheme::head();

?>
</head>
<body <?php body_class(); ?><?php FLTheme::print_schema( ' itemscope="itemscope" itemtype="https://schema.org/WebPage"' ); ?>>
<?php

FLTheme::header_code();

do_action( 'fl_body_open' );

?>

<div class="fl-page">
	<?php

	do_action( 'fl_page_open' );

	FLTheme::fixed_header();

	do_action( 'fl_before_top_bar' );

	FLTheme::top_bar();

	do_action( 'fl_after_top_bar' );
	do_action( 'fl_before_header' );

	FLTheme::header_layout();

	do_action( 'fl_after_header' );
	do_action( 'fl_before_content' );

	?>
	<div class="notice"<?php echo function_exists( 'legacy_get_notice_discount_percentage' ) ? ' data-percentage="' . esc_attr( legacy_get_notice_discount_percentage() ) . '"' : ''; ?>>
	    <?php
	     $notice = get_option( 'my_single_notice' );
	     if ( function_exists( 'legacy_apply_notice_discount_placeholders' ) ) {
	         $notice = legacy_apply_notice_discount_placeholders( $notice );
	     }
	     if ( ! empty( $notice ) ) {
	          echo esc_html( $notice ); ?><div class="notice_close">X</div>
	          <?php
	     }
	    
    ?>
    </div>

	<div id="fl-main-content" class="fl-page-content" itemprop="mainContentOfPage" role="main">

		<?php do_action( 'fl_content_open' ); ?>