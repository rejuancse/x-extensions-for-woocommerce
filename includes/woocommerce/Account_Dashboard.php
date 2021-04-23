<?php
namespace XWOO\woocommerce;

defined( 'ABSPATH' ) || exit;

class Account_Dashboard {

    public function __construct(){            
        add_action( 'init',                                                 array( $this, 'endpoints') );
        add_filter( 'query_vars',                                           array( $this, 'query_vars'), 0 );
        add_filter( 'woocommerce_account_menu_items',                       array( $this, 'menu_items') );
        add_action( 'woocommerce_account_xwoo-dashboard_endpoint',  array( $this, 'dashboard_callback' ) );
        add_action( 'woocommerce_account_profile_endpoint',                 array( $this, 'profile_callback') );
        add_action( 'woocommerce_account_my-campaigns_endpoint',            array( $this, 'campaigns_callback') );
        add_action( 'woocommerce_account_backed-campaigns_endpoint',        array( $this, 'backed_campaigns_callback') );
        add_action( 'woocommerce_account_pledges-received_endpoint',        array( $this, 'pledges_received_callback') );
        add_action( 'woocommerce_account_bookmarks_endpoint',               array( $this, 'bookmarks_callback') );
    }


    // Rewrite Rules For Woocommerce My Account Page
    public function endpoints() {
        add_rewrite_endpoint( 'xwoo-dashboard', EP_ROOT | EP_PAGES );
        add_rewrite_endpoint( 'profile', EP_ROOT | EP_PAGES );
        add_rewrite_endpoint( 'my-campaigns', EP_ROOT | EP_PAGES );
        add_rewrite_endpoint( 'backed-campaigns', EP_ROOT | EP_PAGES );
        add_rewrite_endpoint( 'pledges-received', EP_ROOT | EP_PAGES );
        add_rewrite_endpoint( 'bookmarks', EP_ROOT | EP_PAGES );
    }

    // Query Variable
    public function query_vars( $vars ) {
        $vars[] = 'xwoo-dashboard';
        $vars[] = 'profile';
        $vars[] = 'my-campaigns';
        $vars[] = 'backed-campaigns';
        $vars[] = 'pledges-received';
        $vars[] = 'bookmarks';
        return $vars;
    }

    // Woocommerce Menu Items
    public function menu_items( $items ) {
        $new_items = array(
            'xwoo-dashboard'=> __( 'Xwoo Dashboard', 'xwoo' ),
            'profile'               => __( 'Profile', 'xwoo' ),
            'my-campaigns'          => __( 'My Campaigns', 'xwoo' ),
            'backed-campaigns'      => __( 'Backed Campaigns', 'xwoo' ),
            'pledges-received'      => __( 'Pledges Received', 'xwoo' ),
            'bookmarks'             => __( 'Bookmarks', 'xwoo' ),
        );
        $items = array_merge( $new_items,$items );
        return $items;
    }


    // Xwoo Dashboard
    public function dashboard_callback() {
        $html = '';
        require_once XWOO_DIR_PATH.'includes/woocommerce/dashboard/dashboard.php';
        echo $html;
    }

    // Profile
    public function profile_callback() {
        $html = '';
        require_once XWOO_DIR_PATH.'includes/woocommerce/dashboard/profile.php';
        echo $html;
    }

    // My Profile
    public function campaigns_callback() {
        $html = '';
        require_once XWOO_DIR_PATH.'includes/woocommerce/dashboard/campaign.php';
        echo $html;
    }

    // Backed Campaigns
    public function backed_campaigns_callback() {
        $html = '';
        require_once XWOO_DIR_PATH.'includes/woocommerce/dashboard/investment.php';
        echo $html;
    }

    // Pledges Received
    public function pledges_received_callback() {
        $html = '';
        require_once XWOO_DIR_PATH.'includes/woocommerce/dashboard/order.php';
        echo $html;
    }

    // Bookmarks
    public function bookmarks_callback() {
        $html = '';
        require_once XWOO_DIR_PATH.'includes/woocommerce/dashboard/bookmark.php';
        echo $html;
    }
}