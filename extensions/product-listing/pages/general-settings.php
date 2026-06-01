<?php
defined( 'ABSPATH' ) || exit;

$arr =  array(

    # Product Number
    array(
        'id'        => 'wp_product_list_order',
        'type'      => 'dropdown',
        'option'    => array(
            'desc'    => __('DESC','x-extensions-for-woocommerce'),
            'asc'    => __('ASC','x-extensions-for-woocommerce'),
        ),
        'label'     => __('Product List Order','x-extensions-for-woocommerce'),
        'desc'      => __('Default listing of a products','x-extensions-for-woocommerce'),
    ),
    array(
        'id'        => 'wp_number_of_coulmn',
        'type'      => 'dropdown',
        'option'    => array(
            '4'    => __('4 Column','x-extensions-for-woocommerce'),
            '3'    => __('3 Column','x-extensions-for-woocommerce'),
            '2'    => __('2 Column','x-extensions-for-woocommerce'),
            '1'    => __('1 Column','x-extensions-for-woocommerce'),
        ),
        'label'     => __('Number of Coulmn','x-extensions-for-woocommerce'),
        'desc'      => __('Select your products column','x-extensions-for-woocommerce'),
    ),
    array(
        'id'        => 'wp_number_of_product',
        'type'      => 'text',
        'value'     => '9',
        'label'     => __('Number of Product','x-extensions-for-woocommerce'),
    ),
    array(
        'id'        => 'wp_xewc_product_category',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Enable Product Category','x-extensions-for-woocommerce'),
        'desc'      => __('Enable category for product view.','x-extensions-for-woocommerce'),
    ),
    # Save Function
    array(
        'id'        => 'wp_xewc_product_listing_admin_tab',
        'type'      => 'hidden',
        'value'     => 'tab_style',
    ),
);
xewc_function()->generator( $arr );
