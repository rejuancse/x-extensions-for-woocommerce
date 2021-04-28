<?php
defined( 'ABSPATH' ) || exit;

//SHORTCODES
add_shortcode( 'wp_search_shortcode', array( $xwoo_search_box, 'search_callback' ) );
