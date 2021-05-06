<?php
defined( 'ABSPATH' ) || exit;
$pages = xwoo_function()->get_pages();
$page_array = array();
if (count($pages)>0) {
    foreach ($pages as $page) {
        $page_array[$page->ID] = $page->post_title;
    }
}
$pages = $page_array;


// #WooCommerce Settings (Tab Settings)
$arr =  array(
    // #Listing Page Seperator
    array(
        'type'      => 'seperator',
        'label'     => __('WooCommerce Settings','xwoo'),
        'desc'      => __('All settings related to WooCommerce','xwoo'),
        'top_line'  => 'true',
    ),

    // #Listing Page Select
    array(
        'id'        => 'hide_xwoo_address_from_checkout',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Hide Billing Address From Checkout Page','xwoo'),
        'desc'      => __('Enable/Disable','xwoo'),
    ),

    // #Listing Page Select
    array(
        'id'        => 'wp_listing_page_id',
        'type'      => 'dropdown',
        'option'    => $pages,
        'label'     => __('Select Listing Page','xwoo'),
        'desc'      => __('Select XWOO Product Listing Page.','xwoo'),
    ),

    // #Listing Page Seperator
    array(
        'type'      => 'seperator',
        'label'     => __('Listing Page Settings','xwoo'),
        'top_line'  => 'true',
    ),

    // #Number of Columns in a Row
    array(
        'id'        => 'number_of_collumn_in_row',
        'type'      => 'dropdown',
        'option'    => array(
            '2' => __('2','xwoo'),
            '3' => __('3','xwoo'),
            '4' => __('4','xwoo'),
        ),
        'label'     => __('Number of Columns in a Row','xwoo'),
        'desc'      => __('Number of Columns in your Listing Page','xwoo'),
    ),

    // #Number of Words Shown in Listing Description
    array(
        'id'        => 'number_of_words_show_in_listing_description',
        'type'      => 'number',
        'min'       => '1',
        'max'       => '',
        'value'     => '20',
        'label'     => __('Number of Words Shown in Listing Description','xwoo'),
    ),

    // # Number of post
    array(
        'id'        => 'xwoo_listing_post_number',
        'type'      => 'number',
        'min'       => '1',
        'max'       => '',
        'value'     => '10',
        'label'     => __('Product Number','xwoo'),
    ),

    // #Single Page Seperator
    array(
        'type'      => 'seperator',
        'label'     => __('Single Page Settings','xwoo'),
        'top_line'  => 'true',
    ),

    //Load campaign in single page
    array(
        'id'        => 'wp_single_page_template',
        'type'      => 'radio',
        'option'    => array(
            'in_wp_xwoo' => __('In WP XWOO own template','xwoo'),
            'in_woocommerce' => __('In WooCommerce Default','xwoo'),
        ),
        'label'     => __('Template for campaign single page','xwoo'),
    ),

    // #Number of Columns in a Row
    array(
        'id'        => 'wp_single_page_reward_design',
        'type'      => 'dropdown',
        'option'    => array(
            '1' => __('1','xwoo'),
            '2' => __('2','xwoo'),
        ),
        'label'     => __('Select Style for Rewards','xwoo'),
    ),

	// #Save Function
    array(
        'id'        => 'wp_xwoo_admin_tab',
        'type'      => 'hidden',
        'value'     => 'tab_woocommerce',
    ),
);
xwoo_function()->generator( apply_filters('wp_xwoo_wc_settings', $arr) );