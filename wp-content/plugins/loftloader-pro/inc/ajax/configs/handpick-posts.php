<?php
if ( ! class_exists( 'LoftLoader_Pro_Query_Posts' ) ) {
    class LoftLoader_Pro_Query_Posts {
        /**
        * Construct function
        */
        public function __construct() {
            add_action( 'wp_ajax_loftloader_pro_query_posts', array( $this, 'do_posts_query' ) );
            add_action( 'wp_ajax_nopriv_loftloader_pro_query_posts', array( $this, 'do_posts_query' ) );
        }
        /**
        * Actual post query function
        */
        public function do_posts_query() {
            if ( ! empty( $_REQUEST['title_sesrch'] ) ) {
                $title_search = esc_sql( wp_unslash( $_REQUEST['title_sesrch'] ) );
                $post_type = empty( $_REQUEST['post_type'] ) ? '' : esc_sql( wp_unslash( $_REQUEST['post_type'] ) );
                add_filter( 'posts_where', array( $this, 'search_post_title_only' ), 500, 2 );
                $return = $this->get_search_result( $title_search, $post_type );
                remove_filter( 'posts_where', array( $this, 'search_post_title_only' ), 500, 2 );
                wp_send_json_success( $return );
            }
            wp_send_json_error();
        }
        /**
        * Modify default find where claud
        */
        public function search_post_title_only( $where, $wp_query ) {
            global $wpdb;
            if ( $search_term = $wp_query->get( 'loftloader_pro_query_post_title' ) ) {
                $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $wpdb->esc_like( $search_term ) ) . '%\'';
            }
            return $where;
        }
        /**
        * Get search result array
        */
        protected function get_search_result( $search, $post_type = false ) {
            $return = array();
            $ppp = 100;
            $paged = 0;
            $query = false;
            $all_post_types = llp_get_post_types( true, true );
            $post_types = empty( $post_type ) ? array_keys( $all_post_types ) : explode( ',', $post_type );
            do {
                $query = new WP_Query(
                    array( 'post_type' => $post_types,
                    'loftloader_pro_query_post_title' => $search,
                    'offset' => ( $paged * $ppp ),
                    'posts_per_page' => $ppp,
                    'post_status' => 'publish'
                ) );
                while( $query->have_posts() ) {
                    $query->the_post();
                    $return[ get_the_ID() ] = esc_js( get_the_title() );
                }
                $paged ++;
            } while( $query->post_count === $ppp );
            wp_reset_postdata();
            return $return;
        }
    }
    new LoftLoader_Pro_Query_Posts();
}
