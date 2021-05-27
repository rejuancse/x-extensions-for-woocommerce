<?php
defined( 'ABSPATH' ) || exit;

$arr =  array(

    # Slider Number
    array(
        'id'        => 'wp_number_of_product',
        'type'      => 'text',
        'value'     => '9',
        'label'     => __('Number of Product','xwoo'),
    ),
    array(
        'id'        => 'wp_number_of_coulmn',
        'type'      => 'text',
        'value'     => '4',
        'label'     => __('Number of Coulmn','xwoo'),
    ),
    array(
        'id'        => 'wp_product_category',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Enable Product Category','xwoo'),
        'desc'      => __('Enable category for product view.','xwoo'),
    ),

    // #Save Function
    array(
        'id'        => 'wp_xwoo_admin_tab',
        'type'      => 'hidden',
        'value'     => 'tab_style',
    ),
);
xwoo_function()->generator( $arr );
