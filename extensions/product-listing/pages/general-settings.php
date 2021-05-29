<?php
defined( 'ABSPATH' ) || exit;

$arr =  array(

    # Product Number
    array(
        'id'        => 'wp_product_list_order',
        'type'      => 'dropdown',
        'option'    => array(
            'asc'    => __('ASC','xwoo'),
            'desc'    => __('DESC','xwoo'),
        ),
        'label'     => __('Product List Order','xwoo'),
        'desc'      => __('Default listing of a products','xwoo'),
    ),
    array(
        'id'        => 'wp_number_of_coulmn',
        'type'      => 'dropdown',
        'option'    => array(
            '4'    => __('4 Column','xwoo'),
            '3'    => __('3 Column','xwoo'),
            '2'    => __('2 Column','xwoo'),
            '1'    => __('1 Column','xwoo'),
        ),
        'label'     => __('Number of Coulmn','xwoo'),
        'desc'      => __('Select your products column','xwoo'),
    ),
    array(
        'id'        => 'wp_number_of_product', 
        'type'      => 'text',
        'value'     => '9',
        'label'     => __('Number of Product','xwoo'),
    ),
    array(
        'id'        => 'wp_xwoo_product_category',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Enable Product Category','xwoo'),
        'desc'      => __('Enable category for product view.','xwoo'),
    ),
    # Save Function
    array(
        'id'        => 'wp_xwoo_admin_tab',
        'type'      => 'hidden',
        'value'     => 'tab_style',
    ),
);
xwoo_function()->generator( $arr );
