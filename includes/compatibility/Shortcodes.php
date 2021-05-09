<?php
defined( 'ABSPATH' ) || exit;

//SHORTCODES
add_shortcode( 'wp_product_shortcode', array( $xwoo_product_listing, 'product_callback' ) );
