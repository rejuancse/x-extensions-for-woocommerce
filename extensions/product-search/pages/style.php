<?php
defined( 'ABSPATH' ) || exit;

$arr =  array(
    // #Style Seperator
    // array(
    //     'type'      => 'seperator',
    //     'label'     => __('Search Style Settings','xwoo'),
    //     'top_line'  => 'true',
    // ),
    # Search input field size
    array(
        'id'        => 'wp_search_input_size',
        'type'      => 'text',
        'label'     => __('Search input field size','xwoo'),
        'desc'      => __('Customize your search input field size.','xwoo'),
        'value'     => '100%',
    ),
    array(
        'id'        => 'wp_search_input_focus_color',
        'type'      => 'color',
        'label'     => __('Input Focus Color','xwoo'),
        'desc'      => __('Select Search input focus color.','xwoo'),
        'value'     => '#ffffff',
    ),
    # Button Background Color
    array(
        'id'        => 'wp_search_btn_font_size',
        'type'      => 'text',
        'label'     => __('Search Button Font Size','xwoo'),
        'desc'      => __('Customize your Search Button font size','xwoo'),
        'value'     => '16px',
    ),
    array(
        'id'        => 'wp_search_btn_font_line_height',
        'type'      => 'text',
        'label'     => __('Search Button Font Line Height','xwoo'),
        'desc'      => __('Customize your Search Button font line height','xwoo'),
        'value'     => '23px',
    ),
    array(
        'id'        => 'wp_search_btn_font_weight',
        'type'      => 'text',
        'label'     => __('Search Button Font Weight','xwoo'),
        'desc'      => __('Customize your Search Button font line height','xwoo'),
        'value'     => '700',
    ),
    array(
        'id'        => 'wp_search_bg_color',
        'type'      => 'color',
        'label'     => __('Button Text Color','xwoo'),
        'desc'      => __('Select button background color.','xwoo'),
        'value'     => '#1adc68',
    ),
    array(
        'id'        => 'wp_search_btn_color',
        'type'      => 'color',
        'label'     => __('Button BG Color','xwoo'),
        'desc'      => __('Select Search Button color.','xwoo'),
        'value'     => '#ffffff',
    ),
    # Button Text hover Color
    array(
        'id'        => 'wp_search_text_hover_color',
        'type'      => 'color',
        'label'     => __('Button Text Hover Color','xwoo'),
        'desc'      => __('Select button text hover color.','xwoo'),
        'value'     => '#fff',
    ),
    # Button Background Hover Color
    array(
        'id'        => 'wp_search_bg_hover_color',
        'type'      => 'color',
        'label'     => __('Button BG Hover Color','xwoo'),
        'desc'      => __('Select button background hover color.','xwoo'),
        'value'     => '#2554ec',
    ),
    
    # Save Function
    array(
        'id'        => 'wp_xwoo_admin_tab',
        'type'      => 'hidden',
        'value'     => 'tab_style',
    ),
);
xwoo_function()->generator( $arr );
