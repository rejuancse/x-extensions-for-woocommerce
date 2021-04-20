<?php
defined( 'ABSPATH' ) || exit;
global $wp_roles;
$pages = XWOO_function()->get_pages();
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

    // #Default Campaign Status
    array(
        'id'        => 'wpneo_default_campaign_status',
        'type'      => 'dropdown',
        'option'    => array(
            'publish'    => __('Published','xwoo'),
            'pending'    => __('Pending Review','xwoo'),
            'draft'      => __('Draft','xwoo'),
        ),
        'label'     => __('Default Campaign Status','xwoo'),
        'desc'      => __('Default status of a campaign added by a user','xwoo'),
    ),

    //Update by campaign owner
	array(
		'id'        => 'wpneo_campaign_edit_status',
		'type'      => 'dropdown',
		'option'    => array(
			'publish'    => __('Campaign remain publish','xwoo'),
			'pending'    => __('Required Review (Pending)','xwoo'),
			'draft'      => __('Required Review (Draft)','xwoo'),
		),
		'label'     => __('Campaign Edit Status','xwoo'),
		'desc'      => __('What will be campaign status when a campaign owner edit/update his own campaign','xwoo'),
	),

    // #Select Dashboard Page
    array(
        'id'        => 'wpneo_crowdfunding_dashboard_page_id',
        'type'      => 'dropdown',
        'option'    => $pages,
        'label'     => __('Select Dashboard Page','xwoo'),
        'desc'      => __('Select a page for access crowdfunding frontend dashboard','xwoo'),
    ),

    // #Select Campaign Submit Form
    array(
        'id'        => 'wpneo_form_page_id',
        'type'      => 'dropdown',
        'option'    => $pages,
        'label'     => __('Select Campaign Submit Form','xwoo'),
        'desc'      => __('Select a WooCommerce campaign submission form page','xwoo'),
    ),

    // #User Role Selector Option
    array(
        'id'        => 'wpneo_user_role_selector',
        'type'      => 'multiple',
        'multiple'  => 'true',
        'option'    => $campaign_creator,
        'label'     => __('Campaign Creator','xwoo'),
        'desc'      => __('Select roles that can enable frontend campaign submission form.','xwoo'),
    ),

    // #Save Function
    array(
        'id'        => 'wpneo_crowdfunding_admin_tab',
        'type'      => 'hidden',
        'value'     => 'tab_general',
    ),

	// #Show Campaign Never End
	array(
		'id'        => 'XWOO_user_reg_success_redirect_uri',
		'type'      => 'text',
		'value'     => esc_url( home_url( '/' ) ),
		'label'     => __('Redirect URL for User Registration Success','xwoo'),
	),
);
XWOO_function()->generator( $arr );