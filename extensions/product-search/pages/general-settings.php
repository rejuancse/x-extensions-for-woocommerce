<?php
defined( 'ABSPATH' ) || exit;

$arr =  array(

    # Product Search
    array(
        'id'        => 'wp_product_search_image',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Enable Search Image','xwoo'),
        'desc'      => __('Enable WooCommerce product search image on load.','xwoo'),
    ),
    array(
        'id'        => 'wp_product_search_btn_off',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Enable Search Button','xwoo'),
        'desc'      => __('Enable WooCommerce product search button.','xwoo'),
    ),

    // #Save Function
    array(
        'id'        => 'wp_xwoo_search_admin_tab',
        'type'      => 'hidden',
        'value'     => 'tab_style',
    ),
);
xwoo_function()->generator( $arr );
