<?php
if ( ! class_exists( 'LoftLoader_Pro_Frontend_Google_Font' ) ) {
    class LoftLoader_Pro_Frontend_Google_Font {
        /**
        * String all weight string
        */
        protected $all_weight = '100,200,300,400,500,600,700,800';
        /**
        * Construct function
        */
        public function __construct() {
            add_filter( 'loftloader_pro_google_fonts', array( $this, 'get_message_font' ) );
            add_filter( 'loftloader_pro_google_fonts', array( $this, 'get_progress_font' ) );
            $this->load_font();
        }
        /**
        * To load google font if needed
        */
        protected function load_font() {
            $fonts = apply_filters( 'loftloader_pro_google_fonts', array() );
            if ( is_array( $fonts ) && ( count( $fonts ) > 0 ) ) {
                $font_family = $this->get_font_family( $fonts );
                $fonts_url = add_query_arg( array(
                    'family' => urlencode( $font_family ),
                ), 'https://fonts.googleapis.com/css' );
                wp_enqueue_style( 'loftloader-google-font', $fonts_url, array(), LOFTLOADERPRO_ASSET_VERSION );
            }
        }
        /**
        * Get message google font
        */
        public function get_message_font( $fonts ) {
            $enabled = llp_module_enabled( 'loftloader_pro_message_enable_google_font' );
            if ( $enabled ) {
                $font = llp_get_loader_setting( 'loftloader_pro_message_font_family' );
                if ( ! empty( $font ) && $this->is_message_set() && ! isset( $fonts[ $font ] ) ) {
                    $fonts[ $font ] = $this->all_weight;
                }
            }
            return $fonts;
        }
        /**
        * Get progress google font
        */
        public function get_progress_font( $fonts ) {
            $enabled = llp_module_enabled( 'loftloader_pro_progress_number_enable_google_font' );
            if ( $enabled ) {
                $font = llp_get_loader_setting( 'loftloader_pro_progress_number_font_family' );
                if ( ! empty( $font ) && $this->is_progress_with_number_set() && ! isset( $fonts[ $font ] ) ) {
                    $fonts[ $font ] = $this->all_weight;
                }
            }
            return $fonts;
        }
        /**
        * Condition function if message set
        */
        protected function is_message_set() {
			if ( 'on' === llp_get_loader_setting( 'loftloader_pro_enable_random_message_text' ) ) {
				$messages = trim( llp_get_loader_setting( 'loftloader_pro_random_message_text' ) );
				return ! empty( $messages ) || is_customize_preview();
			} else {
				$raw_message = llp_get_loader_setting( 'loftloader_pro_message_text' );
				return ! empty( $raw_message ) || is_customize_preview();
			}
        }
        /**
        * Condition function if progress number set
        */
        protected function is_progress_with_number_set() {
            $progress = llp_get_loader_setting( 'loftloader_progress' );
            return in_array( $progress, array( 'number', 'bar-number' ) );
        }
        /**
        * Generate font family
        */
        protected function get_font_family( $fonts ) {
            $family = array();
            foreach ( $fonts as $font => $weight ) {
                array_push( $family, sprintf( '%1$s:%2$s', $font, $weight ) );
            }
            return implode( '|', $family );
        }
    }
    new LoftLoader_Pro_Frontend_Google_Font();
}
