<?php
namespace XWOO\settings;

defined( 'ABSPATH' ) || exit;

class Admin_Menu {

    public function __construct() {
        add_action('admin_menu', array($this, 'register_menu_page' ));
    }
    /**
     * XWOO Menu Option Page
     */
    public function register_menu_page(){
        add_menu_page( 
            'XWOO Extensions',
            'XWOO Extensions',
            'manage_options',
            'xwoo',
            '',
            'dashicons-xing', 
            null
        );

        $addon_pro =  __('Extensions', 'xwoo');
        if( !defined('XWOO_PRO_FILE') ){
            $addon_pro = __('Extensions <span class="dashicons dashicons-star-filled" style="color:#ef450b"/>', 'xwoo');
        }
        add_submenu_page(
            'xwoo',
            __('Extensions', 'xwoo'),
            $addon_pro,
            'manage_options',
            'xwoo',
            array( $this, 'xwoo_manage_extensions' )
        );
    }
    
    // Addon Listing
    public function xwoo_manage_extensions() {
        include XWOO_DIR_PATH.'settings/view/Addons.php';
    }
}
