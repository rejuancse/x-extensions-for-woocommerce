<?php
defined( 'ABSPATH' ) || exit;

$arr =  array(

    // Show Description
    array(
        'id'        => 'xwoo_show_description',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Enable/Disable','xwoo'),
        'desc'      => __('Description','xwoo')
    ),

    array(
        'id'        => 'wp_xwoo_admin_tab',
        'type'      => 'hidden',
        'value'     => 'tab_fields',
    ),
);
xwoo_function()->generator( $arr );
