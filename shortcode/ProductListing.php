<?php
namespace XWOO\shortcode;

defined( 'ABSPATH' ) || exit;

class Product_Listing {

    function __construct() {
        add_shortcode( 'product_listing', array( $this, 'products_callback_function' ) );
    }

    function products_callback_function( $atts, $shortcode ) {

        $post_limit = get_option( 'product_listing_post_number', 10 );

        $a = shortcode_atts( array(
            'cat'         => null,
            'number'      => $post_limit,
            'order'       => 'DESC',
        ), $atts, $shortcode );

        $paged = 1;
        if ( get_query_var('paged') ){
            $paged = absint( get_query_var('paged') );
        } elseif (get_query_var('page')) {
            $paged = absint( get_query_var('page') );
        }

        $query_args = array(
            'post_type'     => 'product',
            'post_status'   => 'publish',
            'posts_per_page'    => $a['number'],
            'paged'             => $paged,
            'orderby' 		    => 'post_title',
            'order'             => $a['order'],
        );

        $html = '';
        
        query_posts($query_args);
        ob_start();
        xwoo_function()->template('xwoo-listing');
        $html = ob_get_clean();
        wp_reset_query();
        return $html;
    }
}
