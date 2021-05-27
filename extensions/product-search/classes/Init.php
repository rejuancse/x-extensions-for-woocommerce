<?php

defined( 'ABSPATH' ) || exit;

class Xwoo_Product_Search_Extensions {
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
        add_action('admin_menu', array($this, 'xwoo_add_product_search_page'));
        add_action('admin_init', array($this, 'save_product_search_menu_settings' ));
        add_action( 'wp_enqueue_scripts', array( $this, 'xwoo_search_enqueue_frontend_script') );
    }

    public function xwoo_add_product_search_page(){
        add_submenu_page(
            'xwoo', 
            __('Product Search', 'xwoo'), 
            __('Product Search', 'xwoo'), 
            'manage_options', 
            'xwoo-search', 
            array($this, 'xwoo_product_search_func')
        );
    }

    public function xwoo_search_enqueue_frontend_script() {
        wp_enqueue_script('xwoo-search-front', XWOO_DIR_URL .'extensions/product-search/assets/js/productSearch.js', array('jquery'), XWOO_VERSION, true);
    }

    /**
     * Display a custom menu page
     */
    public function xwoo_product_search_func(){
        if (xwoo_function()->post('wp_settings_page_nonce_field')){
            echo '<div class="notice notice-success is-dismissible">';
                echo '<p>'.__( "Quick view data have been Saved.", "xwoo" ).'</p>';
            echo '</div>';
        }

        $default_file = XWOO_DIR_PATH.'extensions/product-search/pages/general-settings.php';
        $style_file = XWOO_DIR_PATH.'extensions/product-search/pages/style.php';
        $shortcode_file = XWOO_DIR_PATH.'extensions/product-search/pages/shortcode.php';

        // Settings Tab With slug and Display name
        $tabs = apply_filters('xwoo_listing_page_panel_tabs', array(
                'general_settings' 	=>
                    array(
                        'tab_name' => __('General Settings','xwoo'),
                        'load_form_file' => $default_file
                    ),
                'listing_style' 	=>
                    array(
                        'tab_name' => __('Style','xwoo'),
                        'load_form_file' => $style_file
                    ),
                'listing_shortcode' 	=>
                    array(
                        'tab_name' => __('Shortcodes','xwoo'),
                        'load_form_file' => $shortcode_file
                    )
            )
        );

        $current_page = 'general_settings';
        if( ! empty($_GET['tab']) ){
            $current_page = sanitize_text_field($_GET['tab']);
        }

        // Print the Tab Title
        echo '<h2 class="xwoo-setting-title">'.__( "XWOO Product Search" , "xwoo" ).'</h2>';
        echo '<h2 class="nav-tab-wrapper">';
        foreach( $tabs as $tab => $name ){
            $class = ( $tab == $current_page ) ? ' nav-tab-active' : '';
            echo "<a class='nav-tab$class' href='?page=xwoo-search&tab=$tab'>{$name['tab_name']}</a>";
        }
        echo '</h2>'; ?>

        <form id="xwoo" role="form" method="post" action="">
            <?php
            //Load tab file
            $request_file = $tabs[$current_page]['load_form_file'];

            if (array_key_exists(trim(esc_attr($current_page)), $tabs)){
                if (file_exists($default_file)){
                    include_once $request_file;
                }else{
                    include_once $default_file;
                }
            } else {
                include_once $default_file;
            }
            wp_nonce_field( 'wp_settings_page_action', 'wp_settings_page_nonce_field' );
            submit_button( null, 'primary', 'wp_admin_settings_submit_btn' );
            ?>
        </form>
        <?php
    }

    /**
     * Add menu settings action
     */
    public function save_product_search_menu_settings() {
        
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
Xwoo_Product_Search_Extensions::instance();
