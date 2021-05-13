<?php
defined( 'ABSPATH' ) || exit;
global $wp_roles;
$pages = xwoo_function()->get_pages();
$page_array = array();
if (count($pages)>0) {
    foreach ($pages as $page) {
        $page_array[$page->ID] = $page->post_title;
    }
}
$pages = $page_array;

$campaign_creator    = array();
$roles  = get_editable_roles();

if (count($roles)){
    foreach( $roles as $key=>$role ){
        $campaign_creator[] = $key;
    }
}

$arr =  array(
    // #General Seperator
    array(
        'type'      => 'seperator',
        'label'     => __('General Settings','xwoo'),
        'top_line'  => 'true',
    ),

    // #Funds Manager
    array(
        'id'        => 'vendor_type',
        'type'      => 'dropdown',
        'option'    => array(
            'woocommerce' => __('Woocommerce','xwoo'),
        ),
        'label'     => __('Funds Manager','xwoo'),
        'desc'      => __('Define the system you want to use to receive and manage the funds raised for your campaigns','xwoo'),
    ),

    // #Save Function
    array(
        'id'        => 'wp_xwoo_admin_tab',
        'type'      => 'hidden',
        'value'     => 'tab_general',
    ),

	// #Show Campaign Never End
	array(
		'id'        => 'xwoo_user_reg_success_redirect_uri',
		'type'      => 'text',
		'value'     => esc_url( home_url( '/' ) ),
		'label'     => __('Redirect URL for User Registration Success','xwoo'),
	),
);
xwoo_function()->generator( $arr );
