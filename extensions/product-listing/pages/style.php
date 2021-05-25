<?php
defined( 'ABSPATH' ) || exit;

$arr =  array(
    // #Style Seperator
    array(
        'type'      => 'seperator',
        'label'     => __('Title Style Settings','xwoo'),
        'top_line'  => 'true',
    ),
    // #Button Background Color
    array(
        'id'        => 'wp_slider_title_font_size',
        'type'      => 'text',
        'label'     => __('Slider Title Font Size','xwoo'),
        'desc'      => __('Customize your slider title font size','xwoo'),
        'value'     => '28px',
    ),
    array(
        'id'        => 'wp_slider_title_font_line_height',
        'type'      => 'text',
        'label'     => __('Slider Title Font Line Height','xwoo'),
        'desc'      => __('Customize your slider title font line height','xwoo'),
        'value'     => '30px',
    ),
    array(
        'id'        => 'wp_slider_title_font_weight',
        'type'      => 'text',
        'label'     => __('Slider title Font Weight','xwoo'),
        'desc'      => __('Customize your slider title font line height','xwoo'),
        'value'     => '700',
    ),
    array(
        'id'        => 'wp_slider_title_color',
        'type'      => 'color',
        'label'     => __('Button BG Color','xwoo'),
        'desc'      => __('Select Slider Title color.','xwoo'),
        'value'     => '#000000',
    ),


    // Sub title Seperator
    array(
        'type'      => 'seperator',
        'label'     => __('SubTitle Style Settings','xwoo'),
        'top_line'  => 'true',
    ),
    // #Button Background Color
    array(
        'id'        => 'wp_slider_subtitle_font_size',
        'type'      => 'text',
        'label'     => __('Slider subTitle Font Size','xwoo'),
        'desc'      => __('Customize your slider subtitle font size','xwoo'),
        'value'     => '22px',
    ),
    array(
        'id'        => 'wp_slider_subtitle_font_line_height',
        'type'      => 'text',
        'label'     => __('Slider subTitle Font Line Height','xwoo'),
        'desc'      => __('Customize your slider subtitle font line height','xwoo'),
        'value'     => '30px',
    ),
    array(
        'id'        => 'wp_slider_subtitle_font_weight',
        'type'      => 'text',
        'label'     => __('Slider subtitle Font Weight','xwoo'),
        'desc'      => __('Customize your slider subtitle font line height','xwoo'),
        'value'     => '700',
    ),
    array(
        'id'        => 'wp_slider_subtitle_color',
        'type'      => 'color',
        'label'     => __('Button BG Color','xwoo'),
        'desc'      => __('Select Slider subtitle color.','xwoo'),
        'value'     => '#000000',
    ),


    // Sub title Seperator
    array(
        'type'      => 'seperator',
        'label'     => __('Slider Button Style Settings','xwoo'),
        'top_line'  => 'true',
    ),
    // #Button Background Color
    array(
        'id'        => 'wp_slider_button_bg_color',
        'type'      => 'color',
        'label'     => __('Button BG Color','xwoo'),
        'desc'      => __('Select button background color.','xwoo'),
        'value'     => '#1adc68',
    ),

    // #Button Background Hover Color
    array(
        'id'        => 'wp_slider_button_bg_hover_color',
        'type'      => 'color',
        'label'     => __('Button BG Hover Color','xwoo'),
        'desc'      => __('Select button background hover color.','xwoo'),
        'value'     => '#2554ec',
    ),
    
    // #Button Text Color
    array(
        'id'        => 'wp_slider_button_text_color',
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
