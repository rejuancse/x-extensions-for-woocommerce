<?php
defined( 'ABSPATH' ) || exit;

$arr =  array(

    # Slider Number
    array(
        'id'        => 'wp_number_of_slider',
        'type'      => 'text',
        'value'     => '3',
        'label'     => __('Number of Slider','xwoo'),
    ),
    array(
        'id'        => 'wp_slider_product_category',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Enable Product Category','xwoo'),
        'desc'      => __('Enable category for slider view.','xwoo'),
    ),
    array(
        'id'        => 'wp_slider_arrow',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Enable Slider Arrow','xwoo'),
        'desc'      => __('Enable arrow for slider view.','xwoo'),
    ),
    array(
        'id'        => 'wp_slider_dots',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Enable Slider Nav','xwoo'),
        'desc'      => __('Slider navigation enable.','xwoo'),
    ),
    // #Save Function
    array(
        'id'        => 'wp_xwoo_admin_tab',
        'type'      => 'hidden',
        'value'     => 'tab_style',
    ),
);
xwoo_function()->generator( $arr );
