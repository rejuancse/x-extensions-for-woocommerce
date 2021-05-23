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

    // #Button Background Color
    array(
        'id'        => 'wp_button_bg_color',
        'type'      => 'color',
        'label'     => __('Button BG Color','xwoo'),
        'desc'      => __('Select button background color.','xwoo'),
        'value'     => '#1adc68',
    ),

    // #Button Background Hover Color
    array(
        'id'        => 'wp_button_bg_hover_color',
        'type'      => 'color',
        'label'     => __('Button BG Hover Color','xwoo'),
        'desc'      => __('Select button background hover color.','xwoo'),
        'value'     => '#2554ec',
    ),
    
    // #Button Text Color
    array(
        'id'        => 'wp_button_text_color',
        'type'      => 'color',
        'label'     => __('Button Text Color','xwoo'),
        'desc'      => __('Select button text color.','xwoo'),
        'value'     => '#fff',
    ),
   
    // #Save Function
    array(
        'id'        => 'wp_xwoo_admin_tab',
        'type'      => 'hidden',
        'value'     => 'tab_style',
    ),
);
xwoo_function()->generator( $arr );
