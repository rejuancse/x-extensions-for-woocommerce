<?php

// namespace XWOO\extensions\quickview;

defined( 'ABSPATH' ) || exit;

class Xwoo_Product_Slider_Extensions {
    /**
     * @var null
     *
     * Instance of this class
     */
    protected static $_instance = null;

    /**
     * @return null|XWOO
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action('admin_menu', array($this, 'xwoo_add_product_slider_page'));
        add_action('admin_init', array($this, 'save_slider_menu_settings' ));
    }

    public function xwoo_add_product_slider_page(){
        add_submenu_page(
            'xwoo', 
            __('Product Slider', 'xwoo'), 
            __('Product Slider', 'xwoo'), 
            'manage_options', 
            'xwoo-slider', 
            array($this, 'xwoo_slider_products_func')
        );
    }

    /**
     * Display a custom menu page
     */
    public function xwoo_slider_products_func(){
        if (xwoo_function()->post('wp_settings_page_nonce_field')){
            echo '<div class="notice notice-success is-dismissible">';
                echo '<p>'.__( "Quick view data have been Saved.", "xwoo" ).'</p>';
            echo '</div>';
        } ?>

        <form id="xwoo" role="form" method="post" action="">
            <?php
            //Load tab file
            include_once XWOO_DIR_PATH.'extensions/quickview/classes/quick-view-tab.php';
            
            wp_nonce_field( 'wp_settings_page_action', 'wp_settings_page_nonce_field' );
            submit_button( null, 'primary', 'wp_admin_settings_submit_btn' );
            ?>
        </form>
        <?php
    }

    /**
     * Add menu settings action
     */
    public function save_slider_menu_settings() {
        
        if (xwoo_function()->post('wp_settings_page_nonce_field') && wp_verify_nonce( sanitize_text_field(xwoo_function()->post('wp_settings_page_nonce_field')), 'wp_settings_page_action' ) ){

            $current_tab = sanitize_text_field(xwoo_function()->post('wp_xwoo_admin_tab'));

            if( ! empty($current_tab) ){
                /**
                 * General Settings
                 */
                $styling = sanitize_text_field(xwoo_function()->post('wp_quick_view'));
                xwoo_function()->update_checkbox( 'wp_quick_view', $styling);

                $mobile_view = sanitize_text_field(xwoo_function()->post('mobile_quick_view'));
                xwoo_function()->update_text('mobile_quick_view', $mobile_view);

                $product_status = sanitize_text_field(xwoo_function()->post('btn_quick_view'));
                xwoo_function()->update_text('btn_quick_view', $product_status);

                # Style.
                $button_bg_color = sanitize_text_field(xwoo_function()->post('wp_button_bg_color'));
                xwoo_function()->update_text('wp_button_bg_color', $button_bg_color);

                $button_bg_hover_color = sanitize_text_field(xwoo_function()->post('wp_button_bg_hover_color'));
                xwoo_function()->update_text('wp_button_bg_hover_color', $button_bg_hover_color);

                $button_text_color = sanitize_text_field(xwoo_function()->post('wp_button_text_color'));
                xwoo_function()->update_text('wp_button_text_color', $button_text_color);
            }
        }
    }

}
Xwoo_Product_Slider_Extensions::instance();
