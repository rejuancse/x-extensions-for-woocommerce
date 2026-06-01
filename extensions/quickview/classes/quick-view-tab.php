<?php
defined( 'ABSPATH' ) || exit;

$arr =  array(
    // #General Seperator
    array(
        'type'      => 'seperator',
        'label'     => __('WooCommerce Quick View Settings', 'x-extensions-for-woocommerce'),
        'top_line'  => 'true',
    ),
    # Enable Quick View
    array(
        'id'        => 'wp_quick_view',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Enable Quick View','x-extensions-for-woocommerce'),
    ),
    # Enable Quick View on mobile device
    array(
        'id'        => 'mobile_quick_view',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Enable Quick View on mobile','x-extensions-for-woocommerce'),
        'desc'      => '<p>'.__('Enable quick view features on mobile device too','x-extensions-for-woocommerce').'</p>',
    ),
    # Enable Quick View on mobile device
    array(
        'id'        => 'btn_quick_view',
        'type'      => 'text',
        'value'     => 'Quick View',
        'label'     => __('Quick View Button Label','x-extensions-for-woocommerce'),
        'desc'      => '<p>'.__('Label for the quick view button in the WooCommerce loop.','x-extensions-for-woocommerce').'</p>',
    ),

    // #Style Seperator
    array(
        'type'      => 'seperator',
        'label'     => __('Style Settings','x-extensions-for-woocommerce'),
        'top_line'  => 'true',
    ),

    # Button Background Color
    array(
        'id'        => 'wp_button_bg_color',
        'type'      => 'color',
        'label'     => __('Popup Background Color','x-extensions-for-woocommerce'),
        'desc'      => __('Select button background color.','x-extensions-for-woocommerce'),
        'value'     => '#fafafa',
    ),
    # Close Button Color
    array(
        'id'        => 'wp_close_button_color',
        'type'      => 'color',
        'label'     => __('Modal close button color','x-extensions-for-woocommerce'),
        'desc'      => __('Select quick view modal close button color.','x-extensions-for-woocommerce'),
        'value'     => '#2b74aa',
    ),
    # Modal close button hover color
    array(
        'id'        => 'wp_close_button_hover_color',
        'type'      => 'color',
        'label'     => __('Modal close button hover color','x-extensions-for-woocommerce'),
        'desc'      => __('Select quick view modal close button hover color.','x-extensions-for-woocommerce'),
        'value'     => '#2554ec',
    ),
    # Save Function
    array(
        'id'        => 'wp_xewc_quick_view_admin_tab',
        'type'      => 'hidden',
        'value'     => 'tab_style',
    ),
);
xewc_function()->generator( $arr );
