<?php
defined( 'ABSPATH' ) || exit;

$arr =  array(
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
