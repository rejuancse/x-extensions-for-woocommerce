<?php
defined( 'ABSPATH' ) || exit;

    $arr =  array(
                // #Style Seperator
                array(
                    'type'      => 'seperator',
                    'label'     => __('Style Settings','xwoo'),
                    'top_line'  => 'true',
                    ),

                // #Enable Color Scheme
                array(
                    'id'        => 'wpneo_enable_color_styling',
                    'type'      => 'checkbox',
                    'value'     => 'true',
                    'label'     => __('Enable Color Styling','xwoo'),
                    'desc'      => __('Enable color styling option for custom color layout.','xwoo'),
                    ),
    
                // #Button Background Color
                array(
                    'id'        => 'wpneo_color_scheme',
                    'type'      => 'color',
                    'label'     => __('Color Scheme','xwoo'),
                    'desc'      => __('Select color scheme of plugins.','xwoo'),
                    'value'     => '#1adc68',
                    ),

                // #Button Background Color
                array(
                    'id'        => 'wpneo_button_bg_color',
                    'type'      => 'color',
                    'label'     => __('Button BG Color','xwoo'),
                    'desc'      => __('Select button background color.','xwoo'),
                    'value'     => '#1adc68',
                    ),

                // #Button Background Hover Color
                array(
                    'id'        => 'wpneo_button_bg_hover_color',
                    'type'      => 'color',
                    'label'     => __('Button BG Hover Color','xwoo'),
                    'desc'      => __('Select button background hover color.','xwoo'),
                    'value'     => '#2554ec',
                    ),
                
                // #Button Text Color
                array(
                    'id'        => 'wpneo_button_text_color',
                    'type'      => 'color',
                    'label'     => __('Button Text Color','xwoo'),
                    'desc'      => __('Select button text color.','xwoo'),
                    'value'     => '#fff',
                    ),

                // #Button Text Hover Color
                array(
                    'id'        => 'wpneo_button_text_hover_color',
                    'type'      => 'color',
                    'label'     => __('Button Text Hover Color','xwoo'),
                    'desc'      => __('Select button text hover color.','xwoo'),
                    'value'     => '#fff',
                    ),
                
                // #Custom CSS
                array(
                    'id'        => 'wpneo_custom_css',
                    'type'      => 'textarea',
                    'label'     => __('Custom CSS','xwoo'),
                    'desc'      => __('Put custom CSS here.','xwoo'),  
                    'value'     => '',
                    ),
                
                
                // #Save Function
                array(
                    'id'        => 'wpneo_xwoo_admin_tab',
                    'type'      => 'hidden',
                    'value'     => 'tab_style',
                    ),
    );
XWOO_function()->generator( $arr );