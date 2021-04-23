<?php
defined( 'ABSPATH' ) || exit;
$pages = XWOO_function()->get_pages();
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

    // #Hide XWOO Campaign From Shop Page
    // array(
    //     'id'        => 'hide_cf_campaign_from_shop_page',
    //     'type'      => 'checkbox',
    //     'value'     => 'true',
    //     'label'     => __('Hide XWOO Campaign From Shop Page','xwoo'),
    //     'desc'      => __('Enable/Disable','xwoo'),
    // ),

    // #Product Single Page Fullwith
/*    array(
        'id'        => 'wp_single_page_id',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Enable/Disable','xwoo'),
        'desc'      => __('XWOO Product Single Page Fullwith.','xwoo'),
    ),*/


    // #Listing Page Select
    array(
        'id'        => 'hide_cf_address_from_checkout',
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

    // #Campaign Registration Page Select
    array(
        'id'        => 'wp_registration_page_id',
        'type'      => 'dropdown',
        'option'    => $pages,
        'label'     => __('Select Registration Page','xwoo'),
        'desc'      => __('Select XWOO Registration Page.','xwoo'),
    ),

	// #Categories
	array(
		'type'      => 'seperator',
		'label'     => __('Categories Settings','xwoo'),
		'desc'      => __('Exclude or include WooCommerce product categories.','xwoo'),
		'top_line'  => 'true',
	),

	// array(
	// 	'id'        => 'seperate_xwoo_categories',
	// 	'type'      => 'checkbox',
	// 	'value'     => 'true',
	// 	'label'     => __('Separate XWOO Categories','xwoo'),
	// 	'desc'      => __('Enable/Disable','xwoo'),
	// ),

    // #Listing Page Seperator
    array(
        'type'      => 'seperator',
        'label'     => __('Submit Form Text Settings','xwoo'),
        'desc'      => __('All settings related to Submit Form Text.','xwoo'),
        'top_line'  => 'true',
    ),

    // #Campaign Submit Form Requirement Title
    array(
        'id'        => 'wp_requirement_title',
        'type'      => 'text',
        'label'     => __('Submit Form Requirement Title','xwoo'),
        'desc'      => __('Additional title for Submit Form Requirement Title goes here.','xwoo'),
        'value'     => ''
    ),

    // #Campaign Submit Form Requirement Text
    array(
        'id'        => 'wp_requirement_text',
        'type'      => 'textarea',
        'value'     => '',
        'label'     => __('Submit Form Requirement Text','xwoo'),
        'desc'      => __('Additional text for Submit Form Requirement goes here.','xwoo'),
    ),

    // #Campaign Submit Form Requirement Agree Title
    array(
        'id'        => 'wp_requirement_agree_title',
        'type'      => 'text',
        'value'     => '',
        'label'     => __('Submit Form Agree Title','xwoo'),
        'desc'      => __('The checkmark text for agreeing with terms and conditions.','xwoo'),
    ),

    array(
        'id'        => 'wp_xwoo_add_to_cart_redirect',
        'type'      => 'radio',
        'option'    =>  array( 'checkout_page' => 'Checkout Page', 'cart_page' => 'Cart Page', 'none' => 'None' ) ,
        'label'     => __('Button Submit Action of "Back This Campaign" ','xwoo'),
        'desc'      => __('This action will determine where to redirect after clicking on “Back This Campaign” button of campaign single page.','xwoo'),
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
        'id'        => 'XWOO_listing_post_number',
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

    // #Reward fixed price
    array(
        'id'        => 'wp_reward_fixed_price',
        'type'      => 'checkbox',
        'value'     => 'true',
        'label'     => __('Set fixed price instead of range on Rewards','xwoo'),
        'desc'      => __('Enable/Disable','xwoo'),
    ),

	array(
		'type'      => 'seperator',
		'label'     => __('Tax Settings','xwoo'),
		'top_line'  => 'true',
	),
	// #Reward fixed price
	array(
		'id'        => 'XWOO_enable_tax',
		'type'      => 'checkbox',
		'value'     => 'true',
		'label'     => __('Enable/Disable','xwoo'),
		'desc'      => __('Enable Tax in XWOO Products','xwoo'),
	),

	// #Save Function
    array(
        'id'        => 'wp_xwoo_admin_tab',
        'type'      => 'hidden',
        'value'     => 'tab_woocommerce',
    ),
);
XWOO_function()->generator( apply_filters('wp_xwoo_wc_settings', $arr) );