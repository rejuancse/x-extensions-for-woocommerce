<?php
defined( 'ABSPATH' ) || exit;

//SHORTCODES
add_shortcode( 'wp_product_listing_shortcode', array( $xwoo_product_listing, 'product_listing_callback' ) );
add_shortcode( 'wp_product_search_shortcode', array( $xwoo_product_search, 'product_search_callback' ) );
