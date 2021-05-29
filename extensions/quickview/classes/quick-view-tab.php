<?php
defined( 'ABSPATH' ) || exit;

$arr =  array(
    // #General Seperator
    array(
        'type'      => 'seperator',
        'label'     => __('WooCommerce Quick View Settings', 'xwoo'),
        'top_line'  => 'true',
    ),
    # Enable Quick View
    array(
        'id'        => 'wp_quick_view',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Enable Quick View','xwoo'),
    ),
    # Enable Quick View on mobile device
    array(
        'id'        => 'mobile_quick_view',
        'type'      => 'checkbox', 
        'value'     => 'true',
        'label'     => __('Enable Quick View on mobile','xwoo'),
        'desc'      => '<p>'.__('Enable quick view features on mobile device too','xwoo').'</p>',
    ),
    # Enable Quick View on mobile device
    array(
        'id'        => 'btn_quick_view',
        'type'      => 'text', 
        'value'     => 'Quick View',
        'label'     => __('Quick View Button Label','xwoo'),
        'desc'      => '<p>'.__('Label for the quick view button in the WooCommerce loop.','xwoo').'</p>',
    ),

    // #Style Seperator
    array(
        'type'      => 'seperator',
        'label'     => __('Style Settings','xwoo'),
        'top_line'  => 'true',
    ),

    # Button Background Color
    array(
        'id'        => 'wp_button_bg_color',
        'type'      => 'color',
        'label'     => __('Button BG Color','xwoo'),
        'desc'      => __('Select button background color.','xwoo'),
        'value'     => '#1adc68',
    ),
    # Close Button Color
    array(
        'id'        => 'wp_close_button_color',
        'type'      => 'color',
        'label'     => __('Modal close button color','xwoo'),
        'desc'      => __('Select quick view modal close button color.','xwoo'),
        'value'     => '#2b74aa',
    ),
    # Modal close button hover color
    array(
        'id'        => 'wp_close_button_hover_color',
        'type'      => 'color',
        'label'     => __('Modal close button hover color','xwoo'),
        'desc'      => __('Select quick view modal close button hover color.','xwoo'),
        'value'     => '#2554ec',
    ),
    # Save Function
    array(
        'id'        => 'wp_xwoo_quick_view_admin_tab',
        'type'      => 'hidden',
        'value'     => 'tab_style',
    ),
);
xwoo_function()->generator( $arr );
