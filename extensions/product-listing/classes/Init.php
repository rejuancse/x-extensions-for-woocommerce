<?php

// namespace XWOO\extensions\quickview;

defined( 'ABSPATH' ) || exit;

class Xwoo_Product_Listing_Extensions {
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
        add_action('admin_menu', array($this, 'xwoo_add_product_listing_page'));
        add_action('admin_init', array($this, 'save_listing_menu_settings' ));
    }

    public function xwoo_add_product_listing_page(){
        add_submenu_page(
            'xwoo', 
            __('Product Listing', 'xwoo'), 
            __('Product Listing', 'xwoo'), 
            'manage_options', 
            'xwoo-listing', 
            array($this, 'xwoo_listing_products_func')
        );
    }

    /**
     * Display a custom menu page
     */
    public function xwoo_listing_products_func(){
        if (xwoo_function()->post('wp_settings_page_nonce_field')){
            echo '<div class="notice notice-success is-dismissible">';
                echo '<p>'.__( "Quick view data have been Saved.", "xwoo" ).'</p>';
            echo '</div>';
        }

        $default_file = XWOO_DIR_PATH.'extensions/product-listing/pages/general-settings.php';
        $shortcode_file = XWOO_DIR_PATH.'extensions/product-listing/pages/shortcode.php';

        // Settings Tab With slug and Display name
        $tabs = apply_filters('xwoo_listing_page_panel_tabs', array(
                'general_settings' 	=>
                    array(
                        'tab_name' => __('General Settings','xwoo'),
                        'load_form_file' => $default_file
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
        echo '<h2 class="xwoo-setting-title">'.__( "XWOO Product Listing" , "xwoo" ).'</h2>';
        echo '<h2 class="nav-tab-wrapper">';
        foreach( $tabs as $tab => $name ){
            $class = ( $tab == $current_page ) ? ' nav-tab-active' : '';
            echo "<a class='nav-tab$class' href='?page=xwoo-listing&tab=$tab'>{$name['tab_name']}</a>";
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
    public function save_listing_menu_settings() {
        
        if (xwoo_function()->post('wp_settings_page_nonce_field') && wp_verify_nonce( sanitize_text_field(xwoo_function()->post('wp_settings_page_nonce_field')), 'wp_settings_page_action' ) ){

            $current_tab = sanitize_text_field(xwoo_function()->post('wp_xwoo_product_listing_admin_tab'));

            if( ! empty($current_tab) ){
                /**
                 * General Settings
                 */
                $product_order = sanitize_text_field(xwoo_function()->post('wp_product_list_order'));
                xwoo_function()->update_text( 'wp_product_list_order', $product_order);

                $product_number = sanitize_text_field(xwoo_function()->post('wp_number_of_product'));
                xwoo_function()->update_text( 'wp_number_of_product', $product_number);

                $column_number = sanitize_text_field(xwoo_function()->post('wp_number_of_coulmn'));
                xwoo_function()->update_text('wp_number_of_coulmn', $column_number);

                $wp_product_category = sanitize_text_field(xwoo_function()->post('wp_xwoo_product_category'));
                xwoo_function()->update_checkbox('wp_xwoo_product_category', $wp_product_category);
            }
        }
    }

}
Xwoo_Product_Listing_Extensions::instance();
