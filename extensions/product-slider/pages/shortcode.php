<?php
defined( 'ABSPATH' ) || exit;

$arr =  array(
    // #Style Seperator
    array(
        'type'      => 'seperator',
        'label'     => __('Shortcode List','xwoo'),
        'top_line'  => 'true',
    ),

    // #Button Background Color
    array(
        'id'        => 'wp_shortcode',
        'type'      => 'text',
        'label'     => __('Simple Slider','xwoo'),
        'desc'      => __('Copy this shortcode.','xwoo'),
        'value'     => '[xwoo_slider]',
    ),

);
xwoo_function()->generator( $arr );
